<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Guest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ChatController;

// ==========================================
// GUEST ROUTES
// ==========================================

Route::get('/', function () {
    return view('guest.home');
});

Route::get('/login', function () {
    return view('guest.login');
});

Route::get('/signup', function () {
    return view('guest.signup');
});

Route::get('/rooms', function (Request $request) {
    $checkin        = $request->checkin;
    $checkout       = $request->checkout;
    $guests         = $request->guests;
    $type           = $request->type;
    $minPrice       = $request->min_price;
    $maxPrice       = $request->max_price;
    $sort           = $request->sort;
    $availableOnly  = $request->available_only;

    $query = DB::table('room');

    // Date-based availability filter
    if ($checkin && $checkout) {
        $bookedRoomIds = DB::table('reservation')
            ->whereIn('Status', ['Pending', 'Confirmed', 'Booked'])
            ->where('Check_In_Date', '<', $checkout)
            ->where('Check_Out_Date', '>', $checkin)
            ->pluck('ROOM_ID');

        $query->whereNotIn('ROOM_ID', $bookedRoomIds)
              ->where('Status', 'Available');
    } elseif ($availableOnly) {
        $query->where('Status', 'Available');
    }

    // Room type filter
    if ($type) {
        $query->where('Room_Type', $type);
    }

    // Price range filter
    if ($minPrice !== null && $minPrice !== '') {
        $query->where('Price_Per_Night', '>=', $minPrice);
    }
    if ($maxPrice !== null && $maxPrice !== '') {
        $query->where('Price_Per_Night', '<=', $maxPrice);
    }

    // Sorting
    if ($sort === 'price_asc')       $query->orderBy('Price_Per_Night', 'asc');
    elseif ($sort === 'price_desc')  $query->orderBy('Price_Per_Night', 'desc');
    elseif ($sort === 'type_asc')    $query->orderBy('Room_Type', 'asc');
    else                             $query->orderBy('ROOM_ID', 'asc');

    $rooms     = $query->paginate(8)->appends($request->query());
    $roomTypes = DB::table('room')->distinct()->pluck('Room_Type');

    return view('guest.rooms', [
        'rooms'     => $rooms,
        'roomTypes' => $roomTypes,
        'checkin'   => $checkin,
        'checkout'  => $checkout,
        'guests'    => $guests,
    ]);
});

Route::get('/reservations', function () {
    if (!session()->has('guest_id')) {
        return redirect('/login')->with('error', 'Please login first to view your reservations.');
    }

    $reservations = DB::table('reservation')
        ->join('room', 'reservation.ROOM_ID', '=', 'room.ROOM_ID')
        ->leftJoinSub(
            DB::table('payment')
                ->select(
                    'RESERVATION_ID',
                    DB::raw('MIN(PAYMENT_ID) as PAYMENT_ID'),
                    DB::raw('MAX(Payment_Status) as Payment_Status')
                )
                ->groupBy('RESERVATION_ID'),
            'payment', 'payment.RESERVATION_ID', '=', 'reservation.RESERVATION_ID'
        )
        ->where('reservation.GUEST_ID', session('guest_id'))
        ->when(request('status') && request('status') !== 'all', function($q) {
            $q->where('reservation.Status', request('status'));
        })
        ->select(
            'reservation.*',
            'room.Room_Type',
            'room.Room_Number',
            'room.Picture_Url',
            'payment.PAYMENT_ID',
            'payment.Payment_Status'
        )
        ->orderBy('reservation.RESERVATION_ID', 'desc')
        ->paginate(5);

    // Load services for each reservation
    foreach ($reservations as $res) {
        $res->services = DB::table('reservation_services')
            ->join('services', 'reservation_services.SERVICES_ID', '=', 'services.SERVICES_ID')
            ->where('reservation_services.RESERVATION_ID', $res->RESERVATION_ID)
            ->select('services.*', 'reservation_services.Quantity')
            ->get();
    }

    return view('guest.reservations', ['reservations' => $reservations]);
});

Route::post('/signup-submit', function (Request $request) {
    $request->validate([
        'email'    => 'required|email|unique:guest,Email',
        'password' => 'required|min:6',
    ]);

    $guest = new Guest();
    $guest->First_Name   = $request->first_name;
    $guest->Last_Name    = $request->last_name;
    $guest->Email        = $request->email;
    $guest->Phone_Number = $request->phone_number;
    $guest->Password     = Hash::make($request->password);
    $guest->save();

    return redirect('/login')->with('success', 'Account created! Please login.');
});

Route::post('/login-submit', function (Request $request) {
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);

    $guest = Guest::where('Email', $request->email)->first();

    if ($guest && Hash::check($request->password, $guest->Password)) {
        session(['guest_id' => $guest->GUEST_ID]);
        return redirect('/');
    } else {
        return back()->with('error', 'Wait! You cannot login because you have not signed up yet, or your password is wrong.');
    }
});

// ==========================================
// BOOKING & PAYMENT ROUTES
// ==========================================

Route::get('/book', function () {
    return redirect('/rooms')->with('error', 'Please select a room to book first.');
});

Route::get('/book/{id}', function ($id) {
    if (!session()->has('guest_id')) {
        return redirect('/login')->with('error', 'Please login to book a room.');
    }

    $room = DB::table('room')->where('ROOM_ID', $id)->first();

    if (!$room) {
        return redirect('/rooms')->with('error', 'Sorry, we could not find that room.');
    }

    $guest = DB::table('guest')->where('GUEST_ID', session('guest_id'))->first();
    return view('guest.book', ['room' => $room, 'guest' => $guest]);
});

Route::post('/payment-process', function (Request $request) {
    $request->validate([
        'room_id'        => 'required',
        'check_in'       => 'required|date',
        'check_out'      => 'required|date|after:check_in',
        'nights'         => 'required|integer',
        'payment_method' => 'required',
    ]);

    $room = DB::table('room')->where('ROOM_ID', $request->room_id)->first();

    if (!$room || $room->Status !== 'Available') {
        return redirect('/rooms')->with('error', 'Sorry, this room has just been booked by someone else!');
    }

    $roomCharges   = $room->Price_Per_Night * $request->nights;
    $servicesTotal = floatval($request->services_total ?? 0);
    $grandTotal    = $roomCharges + $servicesTotal;
    $finalAmount   = $grandTotal * 0.50; // 50% deposit only

    // Fetch selected service names for display on payment page
    $selectedServiceIds = $request->services ?? [];
    $selectedServices   = [];
    if (!empty($selectedServiceIds)) {
        $selectedServices = DB::table('services')
            ->whereIn('SERVICES_ID', $selectedServiceIds)
            ->get(['SERVICES_ID', 'Service_Name', 'Price'])
            ->toArray();
    }

    return view('guest.payment', [
        'amount'           => $finalAmount,
        'room_id'          => $room->ROOM_ID,
        'room_type'        => $room->Room_Type,
        'check_in'         => $request->check_in,
        'check_out'        => $request->check_out,
        'nights'           => $request->nights,
        'payment_method'   => $request->payment_method,
        'services_total'   => $servicesTotal,
        'selected_services'=> $selectedServices,
        'service_ids'      => $selectedServiceIds,
    ]);
});

Route::post('/book-final-submit', function (Request $request) {

    // 0. Handle receipt image upload
    $receiptPath = null;
    if ($request->hasFile('receipt_image')) {
        $file = $request->file('receipt_image');
        $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
        $file->storeAs('receipts', $filename, 'public');
        $receiptPath = '/storage/receipts/' . $filename;
    }

    // 1. Save the reservation as Pending and get the new ID
    $reservationId = DB::table('reservation')->insertGetId([
        'GUEST_ID'       => session('guest_id'),
        'ROOM_ID'        => $request->room_id,
        'Check_In_Date'  => $request->check_in,
        'Check_Out_Date' => $request->check_out,
        'Total_Amount'   => $request->amount,
        'Status'         => 'Pending',
    ]);

    // 2. Insert the payment record
    DB::table('payment')->insert([
        'RESERVATION_ID' => $reservationId,
        'Amount'         => $request->amount,
        'Payment_Method' => $request->payment_method,
        'Payment_Date'   => now(),
        'Receipt_Image'  => $receiptPath,
    ]);

    // 3. Save selected add-on services
    $serviceIds = $request->service_ids ? explode(',', $request->service_ids) : [];
    foreach ($serviceIds as $svcId) {
        $svcId = trim($svcId);
        if (!$svcId) continue;
        DB::table('reservation_services')->insert([
            'RESERVATION_ID' => $reservationId,
            'SERVICES_ID'    => $svcId,
            'Quantity'       => 1,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);
    }

    // 4. Update the room status to 'Booked' so no one else can book it
    DB::table('room')
        ->where('ROOM_ID', $request->room_id)
        ->update(['Status' => 'Booked']);

    return redirect('/reservations')->with('success', 'Booking submitted! Please wait for confirmation.');
});

Route::get('/receipt/{id}', function ($id) {
    if (!session()->has('guest_id')) return redirect('/login');

    $receiptData = DB::table('reservation')
        ->join('room', 'reservation.ROOM_ID', '=', 'room.ROOM_ID')
        ->join('payment', 'reservation.RESERVATION_ID', '=', 'payment.RESERVATION_ID')
        ->join('guest', 'reservation.GUEST_ID', '=', 'guest.GUEST_ID')
        ->where('reservation.RESERVATION_ID', $id)
        ->where('reservation.GUEST_ID', session('guest_id'))
        ->first();

    if (!$receiptData) return redirect('/reservations')->with('error', 'Receipt not found.');

    return view('guest.receipt', ['data' => $receiptData]);
});

Route::post('/reservations/cancel/{id}', function ($id) {
    if (!session()->has('guest_id')) return redirect('/login');

    // Get the reservation to find the room
    $reservation = DB::table('reservation')
        ->where('RESERVATION_ID', $id)
        ->where('GUEST_ID', session('guest_id'))
        ->where('Status', 'Pending')
        ->first();

    if ($reservation) {
        // Cancel the reservation
        DB::table('reservation')
            ->where('RESERVATION_ID', $id)
            ->update(['Status' => 'Cancelled']);

        // Free up the room
        DB::table('room')
            ->where('ROOM_ID', $reservation->ROOM_ID)
            ->update(['Status' => 'Available']);
    }

    return redirect('/reservations')->with('success', 'Your reservation has been cancelled. The room has been released.');
});

// ==========================================
// LOGOUT ROUTES
// ==========================================

Route::get('/logout', function () {
    $isStaff = session()->has('staff_id');
    session()->flush();
    return $isStaff ? redirect('/staff/login') : redirect('/');
});

Route::get('/staff/logout', [StaffController::class, 'logout']);

// ==========================================
// STAFF ROUTES
// ==========================================

Route::get('/staff/login',          [StaffController::class, 'loginPage']);
Route::get('/staff/signup',         [StaffController::class, 'signupPage']);
Route::post('/staff/signup-submit', [StaffController::class, 'signupSubmit']);
Route::post('/staff/login-submit',  [StaffController::class, 'loginSubmit']);

Route::get('/staff/dashboard',      [StaffController::class, 'dashboard']);

Route::get('/staff/reservations',              [StaffController::class, 'reservations']);
Route::post('/staff/update-reservation/{id}',  [StaffController::class, 'updateReservation']);
Route::post('/staff/checkout/{id}',            [StaffController::class, 'checkOutGuest']);
Route::post('/staff/mark-fully-paid/{id}',     [StaffController::class, 'markFullyPaid']);

Route::get('/staff/rooms',                     [StaffController::class, 'rooms']);
Route::get('/staff/add-room',                  [StaffController::class, 'addRoomPage']);
Route::post('/staff/add-room-submit',          [StaffController::class, 'addRoomSubmit']);
Route::get('/staff/edit-room/{id}',            [StaffController::class, 'editRoomPage']);
Route::post('/staff/edit-room-submit/{id}',    [StaffController::class, 'editRoomSubmit']);
Route::post('/staff/delete-room/{id}',         [StaffController::class, 'deleteRoom']);

Route::get('/staff/transactions',              [StaffController::class, 'transactions']);

Route::get('/staff/rooms-view',                [StaffController::class, 'roomsView']);
Route::get('/staff/housekeeping',              [StaffController::class, 'housekeeping']);
Route::post('/staff/update-room-status/{id}',  [StaffController::class, 'updateRoomStatus']);

Route::post('/staff/update-availability/{id}',  [StaffController::class, 'updateAvailability']);

// ==========================================
// SERVICES ROUTES
// ==========================================
Route::get('/staff/services',                   [StaffController::class, 'services']);
Route::post('/staff/add-service',               [StaffController::class, 'addServiceSubmit']);
Route::post('/staff/edit-service/{id}',         [StaffController::class, 'editServiceSubmit']);
Route::post('/staff/delete-service/{id}',       [StaffController::class, 'deleteService']);

// ==========================================
// CHAT ROUTES
// ==========================================

Route::post('/send-message',   [ChatController::class, 'sendMessage']);
Route::get('/get-messages',    [ChatController::class, 'fetchMessages']);

Route::get('/staff/messages',                    [ChatController::class, 'staffIndex']);
Route::get('/staff/get-guests',                  [ChatController::class, 'getChatSummary']);
Route::get('/staff/get-messages/{guest_id}',     [ChatController::class, 'fetchGuestMessages']);
Route::post('/staff/send-message',               [ChatController::class, 'sendStaffMessage']);