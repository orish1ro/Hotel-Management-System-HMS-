<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Cloudinary\Cloudinary;

class StaffController extends Controller
{
    // ==========================================
    // HELPER: Upload image to Cloudinary
    // ==========================================

    private function uploadToCloudinary($file, $folder = 'hotel')
    {
        $cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key'    => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ],
        ]);

        $uploaded = $cloudinary->uploadApi()->upload($file->getRealPath(), [
            'folder' => $folder,
        ]);

        return $uploaded['secure_url'];
    }

    // ==========================================
    // AUTH
    // ==========================================

    public function loginPage()
    {
        return view('staff.login');
    }

    public function signupPage()
    {
        return view('staff.signup');
    }

    public function signupSubmit(Request $request) {
        $request->validate([
            'first_name' => 'required',
            'last_name'  => 'required',
            'email'      => 'required|email|unique:staff,Email',
            'password'   => 'required|min:6',
            'role'       => 'required|in:Admin,Staff',
        ]);

        DB::table('staff')->insert([
            'First_Name' => $request->first_name,
            'Last_Name'  => $request->last_name,
            'Email'      => $request->email,
            'Password'   => Hash::make($request->password),
            'Role'       => $request->role,
            'Status'     => 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/staff/login')->with('success', 'Staff account created!');
    }

    public function loginSubmit(Request $request) {
        $request->validate(['email' => 'required|email', 'password' => 'required']);
        $staff = DB::table('staff')->where('Email', $request->email)->first();

        if ($staff && Hash::check($request->password, $staff->Password)) {
            session([
                'staff_id'   => $staff->STAFF_ID,
                'staff_name' => $staff->First_Name,
                'staff_role' => $staff->Role,
            ]);
            return redirect('/staff/dashboard');
        }
        return back()->with('error', 'Invalid email or password.');
    }

    public function logout()
    {
        session()->forget(['staff_id', 'staff_name', 'staff_role']);
        return redirect('/staff/login')->with('success', 'Staff logged out successfully.');
    }

    // ==========================================
    // DASHBOARD
    // ==========================================

    public function dashboard()
    {
        if (!session()->has('staff_id')) return redirect('/staff/login')->with('error', 'Access denied.');

        $role = session('staff_role');

        // Shared data for both roles
        $totalRooms          = DB::table('room')->count();
        $bookedRoomIds       = DB::table('reservation')
                                    ->whereIn('Status', ['Confirmed', 'Booked'])
                                    ->pluck('ROOM_ID')->unique();
        $bookedRooms         = $bookedRoomIds->count();
        $availableRooms      = $totalRooms - $bookedRooms;
        $pendingReservations = DB::table('reservation')->where('Status', 'Pending')->count();

        // -------------------------------------------------------
        // ADMIN DASHBOARD
        // -------------------------------------------------------
        if ($role === 'Admin') {

            // Revenue: all-time total
            $confirmedReservationIds = DB::table('reservation')
                ->whereIn('Status', ['Confirmed', 'Booked', 'Checked Out'])
                ->pluck('RESERVATION_ID');

            $totalRevenue = DB::table('payment')
                ->whereIn('RESERVATION_ID', $confirmedReservationIds)
                ->sum('Amount') ?? 0;

            // Revenue: today only
            $revenueToday = DB::table('payment')
                ->whereDate('Payment_Date', today())
                ->sum('Amount') ?? 0;

            // Revenue trend vs yesterday
            $revenueYesterday = DB::table('payment')
                ->whereDate('Payment_Date', today()->subDay())
                ->sum('Amount') ?? 0;

            if ($revenueYesterday > 0) {
                $trendPercent     = (($revenueToday - $revenueYesterday) / $revenueYesterday) * 100;
                $revenueDirection = $trendPercent >= 0 ? 'up' : 'down';
                $revenueTrend     = abs(round($trendPercent, 1)) . '%';
            } else {
                $revenueDirection = 'up';
                $revenueTrend     = '0%';
            }

            // Extra admin metrics
            $totalGuests   = DB::table('guest')->count();
            $occupancyRate = $totalRooms > 0 ? round(($bookedRooms / $totalRooms) * 100, 1) : 0;
            $roomsToClean  = DB::table('room')->where('cleaning_status', 'Needs Cleaning')->count();

            // Improved recent reservations with room + dates
            $recentReservations = DB::table('reservation')
                ->join('guest', 'reservation.GUEST_ID', '=', 'guest.GUEST_ID')
                ->join('room', 'reservation.ROOM_ID', '=', 'room.ROOM_ID')
                ->select(
                    'reservation.RESERVATION_ID',
                    'reservation.Status',
                    'reservation.Check_In_Date',
                    'reservation.Check_Out_Date',
                    'guest.First_Name',
                    'guest.Last_Name',
                    'room.Room_Number',
                    'room.Room_Type'
                )
                ->orderBy('reservation.created_at', 'desc')
                ->limit(5)
                ->get();

            return view('staff.admin-dashboard', [
                'totalRooms'          => $totalRooms,
                'availableRooms'      => $availableRooms,
                'bookedRooms'         => $bookedRooms,
                'totalRevenue'        => $totalRevenue,
                'revenueToday'        => $revenueToday,
                'pendingReservations' => $pendingReservations,
                'revenueDirection'    => $revenueDirection,
                'revenueTrend'        => $revenueTrend,
                'totalGuests'         => $totalGuests,
                'occupancyRate'       => $occupancyRate,
                'roomsToClean'        => $roomsToClean,
                'recentReservations'  => $recentReservations,
            ]);
        }

        // -------------------------------------------------------
        // STAFF DASHBOARD
        // -------------------------------------------------------
        $roomsToClean = DB::table('room')
            ->where('cleaning_status', 'Needs Cleaning')
            ->count();

        $recentArrivals = DB::table('reservation')
            ->where('Check_In_Date', today())
            ->whereIn('Status', ['Confirmed', 'Booked'])
            ->count();

        $recentReservations = DB::table('reservation')
            ->join('guest', 'reservation.GUEST_ID', '=', 'guest.GUEST_ID')
            ->select('reservation.RESERVATION_ID', 'guest.First_Name', 'guest.Last_Name', 'reservation.Status')
            ->orderBy('reservation.created_at', 'desc')
            ->limit(5)
            ->get();

        return view('staff.staff-dashboard', [
            'totalRooms'          => $totalRooms,
            'availableRooms'      => $availableRooms,
            'bookedRooms'         => $bookedRooms,
            'pendingReservations' => $pendingReservations,
            'roomsToClean'        => $roomsToClean,
            'recentArrivals'      => $recentArrivals,
            'recentReservations'  => $recentReservations,
        ]);
    }

    // ==========================================
    // RESERVATIONS
    // ==========================================

    public function reservations()
    {
        if (!session()->has('staff_id')) return redirect('/staff/login');

        $filters = [
            'search'        => request('search'),
            'statusFilter'  => request('status'),
            'paymentFilter' => request('payment_status'),
            'dateFrom'      => request('date_from'),
            'dateTo'        => request('date_to'),
        ];

        $query = DB::table('reservation')
            ->join('guest', 'reservation.GUEST_ID', '=', 'guest.GUEST_ID')
            ->join('room', 'reservation.ROOM_ID', '=', 'room.ROOM_ID')
            ->leftJoinSub(
                DB::table('payment')
                    ->select('RESERVATION_ID',
                        DB::raw('MIN(PAYMENT_ID) as PAYMENT_ID'),
                        DB::raw('MIN(Amount) as Amount'),
                        DB::raw('MAX(Payment_Status) as Payment_Status'),
                        DB::raw('MIN(Payment_Method) as Payment_Method'),
                        DB::raw('MIN(Receipt_Image) as Receipt_Image')
                    )
                    ->groupBy('RESERVATION_ID'),
                'payment', 'payment.RESERVATION_ID', '=', 'reservation.RESERVATION_ID'
            )
            ->select(
                'reservation.RESERVATION_ID',
                'reservation.Status',
                'reservation.Check_In_Date',
                'reservation.Check_Out_Date',
                'reservation.Total_Amount',
                'guest.First_Name',
                'guest.Last_Name',
                'room.Room_Type',
                'room.Room_Number',
                'payment.Payment_Method',
                'payment.Amount as Amount_Paid',
                'payment.Receipt_Image',
                'payment.Payment_Status'
            );

        if (!empty($filters['search'])) {
            $s = $filters['search'];
            $query->where(function($q) use ($s) {
                $q->where('guest.First_Name',  'like', "%{$s}%")
                  ->orWhere('guest.Last_Name',  'like', "%{$s}%")
                  ->orWhere('room.Room_Type',   'like', "%{$s}%")
                  ->orWhere(DB::raw('CAST(reservation.RESERVATION_ID AS CHAR)'), 'like', "%{$s}%");
            });
        }

        if (!empty($filters['statusFilter'])) {
            $query->where('reservation.Status', $filters['statusFilter']);
        }

        if (!empty($filters['paymentFilter'])) {
            $query->where('payment.Payment_Status', $filters['paymentFilter']);
        }

        if (!empty($filters['dateFrom'])) {
            $query->whereDate('reservation.Check_In_Date', '>=', $filters['dateFrom']);
        }
        if (!empty($filters['dateTo'])) {
            $query->whereDate('reservation.Check_In_Date', '<=', $filters['dateTo']);
        }

        $reservations = $query->orderBy('reservation.RESERVATION_ID', 'desc')
                              ->paginate(7)
                              ->appends($filters);

        return view('staff.reservations', [
            'reservations' => $reservations,
            'filters'      => $filters,
        ]);
    }

    public function updateReservation(Request $request, $id)
    {
        if (!session()->has('staff_id')) return redirect('/staff/login');

        $reservation = DB::table('reservation')->where('RESERVATION_ID', $id)->first();

        if (!$reservation) {
            return redirect('/staff/reservations')->with('error', 'Reservation not found.');
        }

        if ($request->status === 'Confirmed') {
            DB::table('room')
                ->where('ROOM_ID', $reservation->ROOM_ID)
                ->update([
                    'Status'          => 'Booked',
                    'cleaning_status' => 'Needs Cleaning',
                    'updated_at'      => now(),
                ]);
        }

        if ($request->status === 'Cancelled') {
            DB::table('room')
                ->where('ROOM_ID', $reservation->ROOM_ID)
                ->update(['Status' => 'Available', 'updated_at' => now()]);
        }

        DB::table('reservation')
            ->where('RESERVATION_ID', $id)
            ->update([
                'Status'   => $request->status,
                'STAFF_ID' => session('staff_id'),
            ]);

        if ($request->status === 'Confirmed') {
            $details = DB::table('reservation')
                ->join('guest', 'reservation.GUEST_ID', '=', 'guest.GUEST_ID')
                ->join('room', 'reservation.ROOM_ID', '=', 'room.ROOM_ID')
                ->leftJoin('payment', 'payment.RESERVATION_ID', '=', 'reservation.RESERVATION_ID')
                ->where('reservation.RESERVATION_ID', $id)
                ->select(
                    'guest.First_Name',
                    'guest.Last_Name',
                    'room.Room_Type',
                    'room.Room_Number',
                    'payment.Amount',
                    'payment.Payment_Method',
                    'reservation.Total_Amount'
                )
                ->first();

            $guestName     = $details ? trim($details->First_Name . ' ' . $details->Last_Name) : 'Guest';
            $roomLabel     = $details ? $details->Room_Type . ' (#' . $details->Room_Number . ')' : 'Room';
            $amount        = $details ? number_format($details->Amount ?? $details->Total_Amount, 2) : '0.00';
            $paymentMethod = $details->Payment_Method ?? 'N/A';
            $reservationNo = str_pad($id, 4, '0', STR_PAD_LEFT);

            return redirect('/staff/reservations')->with('payment_confirmed', [
                'reservation_no' => $reservationNo,
                'guest_name'     => $guestName,
                'room'           => $roomLabel,
                'amount'         => $amount,
                'method'         => $paymentMethod,
                'confirmed_by'   => session('staff_name'),
                'confirmed_at'   => now()->format('M d, Y h:i A'),
            ]);
        }

        return redirect('/staff/reservations')->with('success', 'Reservation status updated to ' . $request->status . '!');
    }

    public function checkOutGuest($id)
    {
        if (!session()->has('staff_id')) return redirect('/staff/login');

        $reservation = DB::table('reservation')->where('RESERVATION_ID', $id)->first();

        if (!$reservation) {
            return redirect('/staff/reservations')->with('error', 'Reservation not found.');
        }

        DB::table('reservation')
            ->where('RESERVATION_ID', $id)
            ->update(['Status' => 'Checked Out']);

        $payment = DB::table('payment')->where('RESERVATION_ID', $id)->first();
        if ($payment && ($payment->Payment_Status ?? '50% Deposit') !== 'Fully Paid') {
            DB::table('payment')->insert([
                'RESERVATION_ID' => $id,
                'STAFF_ID'       => session('staff_id'),
                'Amount'         => $payment->Amount,
                'Payment_Method' => $payment->Payment_Method,
                'Payment_Date'   => now(),
                'Payment_Status' => 'Fully Paid',
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }
        DB::table('payment')
            ->where('RESERVATION_ID', $id)
            ->update(['Payment_Status' => 'Fully Paid', 'updated_at' => now()]);

        DB::table('room')
            ->where('ROOM_ID', $reservation->ROOM_ID)
            ->update([
                'Status'          => 'Needs Cleaning',
                'cleaning_status' => 'Needs Cleaning',
                'updated_at'      => now(),
            ]);

        return redirect('/staff/reservations')->with('success', 'Guest checked out. Room #' . $reservation->ROOM_ID . ' is now set to Needs Cleaning.');
    }

    public function markFullyPaid($id)
    {
        if (!session()->has('staff_id')) return redirect('/staff/login');

        $payment = DB::table('payment')->where('RESERVATION_ID', $id)->first();

        if ($payment) {
            DB::table('payment')->insert([
                'RESERVATION_ID' => $id,
                'STAFF_ID'       => session('staff_id'),
                'Amount'         => $payment->Amount,
                'Payment_Method' => $payment->Payment_Method,
                'Payment_Date'   => now(),
                'Payment_Status' => 'Fully Paid',
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            DB::table('payment')
                ->where('PAYMENT_ID', $payment->PAYMENT_ID)
                ->update(['Payment_Status' => 'Fully Paid', 'updated_at' => now()]);
        }

        return redirect('/staff/reservations')->with('success', 'Reservation #' . str_pad($id, 4, '0', STR_PAD_LEFT) . ' marked as Fully Paid. Balance added to revenue.');
    }

    // ==========================================
    // ROOMS
    // ==========================================

    public function rooms()
    {
        if (!session()->has('staff_id')) return redirect('/staff/login');

        $filters = [
            'search' => request('search'),
            'status' => request('status'),
            'type'   => request('type'),
            'sort'   => request('sort', 'Room_Number'),
        ];

        $query = DB::table('room');

        if (!empty($filters['search'])) {
            $s = $filters['search'];
            $query->where(function($q) use ($s) {
                $q->where('Room_Number', 'like', "%{$s}%")
                  ->orWhere('Room_Type', 'like', "%{$s}%");
            });
        }

        if (!empty($filters['status'])) {
            $query->where('Status', $filters['status']);
        }

        if (!empty($filters['type'])) {
            $query->where('Room_Type', $filters['type']);
        }

        $allowedSorts = ['Room_Number', 'Price_Per_Night', 'Capacity'];
        $sort = in_array($filters['sort'], $allowedSorts) ? $filters['sort'] : 'Room_Number';
        $query->orderBy($sort);

        $rooms     = $query->paginate(8)->appends($filters);
        $roomTypes = DB::table('room')->distinct()->pluck('Room_Type');

        return view('staff.rooms', [
            'rooms'     => $rooms,
            'filters'   => $filters,
            'roomTypes' => $roomTypes,
        ]);
    }

    public function addRoomPage()
    {
        if (!session()->has('staff_id')) return redirect('/staff/login');
        return view('staff.add-room');
    }

    public function addRoomSubmit(Request $request)
    {
        $request->validate([
            'room_number' => 'required',
            'room_type'   => 'required',
            'price'       => 'required|numeric',
            'capacity'    => 'required|numeric',
            'details'     => 'required',
            'room_image'  => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        // Upload image to Cloudinary
        $pictureUrl = $this->uploadToCloudinary($request->file('room_image'), 'rooms');

        DB::table('room')->insert([
            'Room_Number'     => $request->room_number,
            'Room_Type'       => $request->room_type,
            'Price_Per_Night' => $request->price,
            'Capacity'        => $request->capacity,
            'Details'         => $request->details,
            'Picture_Url'     => $pictureUrl,
            'Status'          => 'Available',
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        return redirect('/staff/rooms')->with('success', 'Room added successfully!');
    }

    public function editRoomPage($id)
    {
        if (!session()->has('staff_id')) return redirect('/staff/login');
        $room = DB::table('room')->where('ROOM_ID', $id)->first();
        return view('staff.edit-room', ['room' => $room]);
    }

    public function editRoomSubmit(Request $request, $id)
    {
        if (!session()->has('staff_id')) return redirect('/staff/login');

        $updateData = [
            'Room_Number'     => $request->room_number,
            'Room_Type'       => $request->room_type,
            'Price_Per_Night' => $request->price,
            'Capacity'        => $request->capacity,
            'Details'         => $request->details,
            'updated_at'      => now(),
        ];

        if ($request->hasFile('room_image')) {
            $request->validate(['room_image' => 'image|mimes:jpg,jpeg,png,webp|max:5120']);
            // Upload new image to Cloudinary
            $updateData['Picture_Url'] = $this->uploadToCloudinary($request->file('room_image'), 'rooms');
        }

        DB::table('room')->where('ROOM_ID', $id)->update($updateData);

        return redirect('/staff/rooms')->with('success', 'Room updated successfully!');
    }

    public function updateAvailability(Request $request, $id)
    {
        if (!session()->has('staff_id')) return redirect('/staff/login');

        $newStatus  = $request->availability;
        $updateData = [
            'Status'     => $newStatus,
            'updated_at' => now(),
        ];

        if ($newStatus === 'Booked') {
            $updateData['cleaning_status'] = 'Needs Cleaning';
        }

        DB::table('room')->where('ROOM_ID', $id)->update($updateData);

        return redirect('/staff/rooms')->with('success', 'Room #' . $id . ' availability updated to ' . $newStatus . '!');
    }

    public function deleteRoom($id)
    {
        if (!session()->has('staff_id')) return redirect('/staff/login');

        DB::table('room')->where('ROOM_ID', $id)->delete();
        return redirect('/staff/rooms')->with('success', 'Room deleted forever.');
    }

    // ==========================================
    // TRANSACTIONS
    // ==========================================

    public function transactions()
    {
        if (!session()->has('staff_id')) return redirect('/staff/login');

        $filters = [
            'search'    => request('search'),
            'status'    => request('status'),
            'method'    => request('method'),
            'date_from' => request('date_from'),
            'date_to'   => request('date_to'),
        ];

        $query = DB::table('payment')
            ->join('reservation', 'payment.RESERVATION_ID', '=', 'reservation.RESERVATION_ID')
            ->join('guest', 'reservation.GUEST_ID', '=', 'guest.GUEST_ID')
            ->join('room', 'reservation.ROOM_ID', '=', 'room.ROOM_ID')
            ->select(
                'payment.*',
                'guest.First_Name',
                'guest.Last_Name',
                'room.Room_Type',
                'room.Room_Number',
                'reservation.Status as ReservationStatus'
            );

        if (!empty($filters['search'])) {
            $s = $filters['search'];
            $query->where(function($q) use ($s) {
                $q->where('guest.First_Name', 'like', "%{$s}%")
                  ->orWhere('guest.Last_Name',  'like', "%{$s}%")
                  ->orWhere('room.Room_Type',   'like', "%{$s}%")
                  ->orWhere('room.Room_Number', 'like', "%{$s}%");
            });
        }

        if (!empty($filters['status'])) {
            $query->where('reservation.Status', $filters['status']);
        }

        if (!empty($filters['method'])) {
            $query->where('payment.Payment_Method', $filters['method']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('payment.Payment_Date', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->whereDate('payment.Payment_Date', '<=', $filters['date_to']);
        }

        $countFiltered = (clone $query)->count();
        $totalFiltered = (clone $query)->sum('payment.Amount');

        $transactions = $query->orderBy('payment.Payment_Date', 'desc')->paginate(10)->appends($filters);

        return view('staff.transactions', [
            'transactions'  => $transactions,
            'filters'       => $filters,
            'countFiltered' => $countFiltered,
            'totalFiltered' => $totalFiltered,
        ]);
    }

    // ==========================================
    // TRANSACTIONS EXPORT PDF
    // ==========================================

    public function exportTransactionsPdf(Request $request)
    {
        if (!session()->has('staff_id')) return redirect('/staff/login');

        $filters = [
            'search'    => $request->search,
            'status'    => $request->status,
            'method'    => $request->method,
            'date_from' => $request->date_from,
            'date_to'   => $request->date_to,
        ];

        $query = DB::table('payment')
            ->join('reservation', 'payment.RESERVATION_ID', '=', 'reservation.RESERVATION_ID')
            ->join('guest', 'reservation.GUEST_ID', '=', 'guest.GUEST_ID')
            ->join('room', 'reservation.ROOM_ID', '=', 'room.ROOM_ID')
            ->select(
                'payment.PAYMENT_ID',
                'payment.Amount',
                'payment.Payment_Date',
                'payment.Payment_Method',
                'reservation.Status as ReservationStatus',
                'guest.First_Name',
                'guest.Last_Name',
                'room.Room_Type',
                'room.Room_Number'
            );

        if (!empty($filters['search'])) {
            $s = $filters['search'];
            $query->where(function($q) use ($s) {
                $q->where('guest.First_Name', 'like', "%{$s}%")
                  ->orWhere('guest.Last_Name', 'like', "%{$s}%")
                  ->orWhere('room.Room_Type', 'like', "%{$s}%");
            });
        }
        if (!empty($filters['status']))    $query->where('reservation.Status', $filters['status']);
        if (!empty($filters['method']))    $query->where('payment.Payment_Method', $filters['method']);
        if (!empty($filters['date_from'])) $query->whereDate('payment.Payment_Date', '>=', $filters['date_from']);
        if (!empty($filters['date_to']))   $query->whereDate('payment.Payment_Date', '<=', $filters['date_to']);

        $transactions = $query->orderBy('payment.Payment_Date', 'desc')->get();
        $total        = $transactions->sum('Amount');

        $html = view('staff.transactions-pdf', [
            'transactions' => $transactions,
            'filters'      => $filters,
            'total'        => $total,
            'exportedBy'   => session('staff_name'),
            'exportedAt'   => now()->format('M d, Y h:i A'),
        ])->render();

        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return response($dompdf->output(), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="transactions_' . now()->format('Y-m-d') . '.pdf"',
        ]);
    }

    // ==========================================
    // HOUSEKEEPING
    // ==========================================

    public function housekeeping()
    {
        if (!session()->has('staff_id')) return redirect('/staff/login');

        $allRooms = DB::table('room')->get();
        $rooms    = DB::table('room')->paginate(8);

        return view('staff.housekeeping', [
            'allRooms' => $allRooms,
            'rooms'    => $rooms,
        ]);
    }

    public function updateRoomStatus(Request $request, $id)
    {
        if (!session()->has('staff_id')) return redirect('/staff/login');

        $allowed = ['Needs Cleaning', 'Available'];
        $status  = $request->status;

        if (!in_array($status, $allowed)) {
            return redirect('/staff/housekeeping')->with('error', 'Invalid status.');
        }

        DB::table('room')->where('ROOM_ID', $id)->update([
            'cleaning_status' => $status,
            'Status'          => $status === 'Available' ? 'Available' : 'Needs Cleaning',
            'updated_at'      => now(),
        ]);

        return redirect('/staff/housekeeping')->with('success', 'Room cleaning status updated to ' . $status . '!');
    }

    // ==========================================
    // ROOMS VIEW (Staff read-only)
    // ==========================================

    public function roomsView()
    {
        if (!session()->has('staff_id')) return redirect('/staff/login');

        $filters = [
            'search' => request('search'),
            'status' => request('status'),
            'type'   => request('type'),
            'sort'   => request('sort', 'Room_Number'),
        ];

        $query = DB::table('room');

        if (!empty($filters['search'])) {
            $s = $filters['search'];
            $query->where(function($q) use ($s) {
                $q->where('Room_Number', 'like', "%{$s}%")
                  ->orWhere('Room_Type', 'like', "%{$s}%");
            });
        }

        if (!empty($filters['status'])) {
            $query->where('Status', $filters['status']);
        }

        if (!empty($filters['type'])) {
            $query->where('Room_Type', $filters['type']);
        }

        $allowedSorts = ['Room_Number', 'Price_Per_Night', 'Capacity'];
        $sort = in_array($filters['sort'], $allowedSorts) ? $filters['sort'] : 'Room_Number';
        $query->orderBy($sort);

        $rooms     = $query->paginate(12)->appends($filters);
        $roomTypes = DB::table('room')->distinct()->pluck('Room_Type');

        return view('staff.staff-rooms', [
            'rooms'     => $rooms,
            'filters'   => $filters,
            'roomTypes' => $roomTypes,
        ]);
    }

    // ==========================================
    // SERVICES (Admin only)
    // ==========================================

    public function services()
    {
        if (!session()->has('staff_id')) return redirect('/staff/login');

        $filters = [
            'search'   => request('search'),
            'category' => request('category'),
        ];

        $query = DB::table('services');

        if (!empty($filters['search'])) {
            $s = $filters['search'];
            $query->where(function($q) use ($s) {
                $q->where('Service_Name', 'like', "%{$s}%")
                  ->orWhere('Description', 'like', "%{$s}%");
            });
        }

        if (!empty($filters['category'])) {
            $query->where('Service_Category', $filters['category']);
        }

        $query->orderBy('Service_Category')->orderBy('Service_Name');

        $services   = $query->paginate(10)->appends($filters);
        $categories = DB::table('services')->distinct()->orderBy('Service_Category')->pluck('Service_Category');

        return view('staff.services', [
            'services'   => $services,
            'filters'    => $filters,
            'categories' => $categories,
        ]);
    }

    public function addServiceSubmit(Request $request)
    {
        if (!session()->has('staff_id')) return redirect('/staff/login');
        if (session('staff_role') !== 'Admin') return redirect('/staff/dashboard')->with('error', 'Access denied.');

        $request->validate([
            'service_name' => 'required|string|max:100',
            'price'        => 'required|numeric|min:0',
            'category'     => 'required|string|max:50',
            'description'  => 'nullable|string',
        ]);

        DB::table('services')->insert([
            'Service_Name'     => $request->service_name,
            'Price'            => $request->price,
            'Service_Category' => $request->category,
            'Description'      => $request->description,
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        return redirect('/staff/services')->with('success', 'Service "' . $request->service_name . '" added successfully!');
    }

    public function editServiceSubmit(Request $request, $id)
    {
        if (!session()->has('staff_id')) return redirect('/staff/login');
        if (session('staff_role') !== 'Admin') return redirect('/staff/dashboard')->with('error', 'Access denied.');

        $request->validate([
            'service_name' => 'required|string|max:100',
            'price'        => 'required|numeric|min:0',
            'category'     => 'required|string|max:50',
            'description'  => 'nullable|string',
        ]);

        DB::table('services')->where('SERVICES_ID', $id)->update([
            'Service_Name'     => $request->service_name,
            'Price'            => $request->price,
            'Service_Category' => $request->category,
            'Description'      => $request->description,
            'updated_at'       => now(),
        ]);

        return redirect('/staff/services')->with('success', 'Service updated successfully!');
    }

    public function deleteService($id)
    {
        if (!session()->has('staff_id')) return redirect('/staff/login');
        if (session('staff_role') !== 'Admin') return redirect('/staff/dashboard')->with('error', 'Access denied.');

        $service = DB::table('services')->where('SERVICES_ID', $id)->first();
        DB::table('services')->where('SERVICES_ID', $id)->delete();

        return redirect('/staff/services')->with('success', 'Service "' . ($service->Service_Name ?? '') . '" deleted.');
    }
}