<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Guest;

class GuestController extends Controller
{
    // ==========================================
    // PAGES
    // ==========================================

    public function home()
    {
        return view('guest.home');
    }

    public function loginPage()
    {
        return view('guest.login');
    }

    public function signupPage()
    {
        return view('guest.signup');
    }

    public function rooms()
    {
        $rooms = DB::table('room')->paginate(6); // 6 rooms per page (2 rows × 3 cols)
        return view('guest.rooms', ['rooms' => $rooms]);
    }

    public function reservations()
    {
        if (!session()->has('guest_id')) {
            return redirect('/login')->with('error', 'Please login first to view your reservations.');
        }

        $reservations = DB::table('reservation')
            ->join('room', 'reservation.ROOM_ID', '=', 'room.ROOM_ID')
            ->leftJoin('payment', 'reservation.RESERVATION_ID', '=', 'payment.RESERVATION_ID')
            ->where('reservation.GUEST_ID', session('guest_id'))
            ->select(
                'reservation.*',
                'room.Room_Type',
                'room.Picture_Url',
                'payment.PAYMENT_ID'
            )
            ->orderBy('reservation.RESERVATION_ID', 'desc')
            ->get();

        return view('guest.reservations', ['reservations' => $reservations]);
    }

    // ==========================================
    // AUTH
    // ==========================================

    public function signupSubmit(Request $request)
    {
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
    }

    public function loginSubmit(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $guest = Guest::where('Email', $request->email)->first();

        if ($guest && Hash::check($request->password, $guest->Password)) {
            session(['guest_id' => $guest->GUEST_ID]);
            return redirect('/');
        }

        return back()->with('error', 'Wait! You cannot login because you have not signed up yet, or your password is wrong.');
    }

    public function logout()
    {
        session()->flush();
        return redirect('/');
    }

    // ==========================================
    // BOOKING & PAYMENT
    // ==========================================

    public function bookRedirect()
    {
        return redirect('/rooms')->with('error', 'Please select a room to book first.');
    }

    public function bookRoom($id)
    {
        if (!session()->has('guest_id')) {
            return redirect('/login')->with('error', 'Please login to book a room.');
        }

        $room = DB::table('room')->where('ROOM_ID', $id)->first();

        if (!$room) {
            return redirect('/rooms')->with('error', 'Sorry, we could not find that room.');
        }

        return view('guest.book', ['room' => $room]);
    }

    public function paymentProcess(Request $request)
    {
        $request->validate([
            'room_id'        => 'required',
            'check_in'       => 'required|date',
            'check_out'      => 'required|date|after:check_in',
            'nights'         => 'required|integer',
            'guests'         => 'required|integer',
            'payment_method' => 'required',
        ]);

        $room = DB::table('room')->where('ROOM_ID', $request->room_id)->first();

        if (!$room || $room->Status !== 'Available') {
            return redirect('/rooms')->with('error', 'Sorry, this room has just been booked by someone else!');
        }

        $totalCharges = $room->Price_Per_Night * $request->nights;
        $finalAmount  = $totalCharges + ($totalCharges * 0.12);

        return view('guest.payment', [
            'amount'         => $finalAmount,
            'room_id'        => $room->ROOM_ID,
            'room_type'      => $room->Room_Type,
            'check_in'       => $request->check_in,
            'check_out'      => $request->check_out,
            'nights'         => $request->nights,
            'guests'         => $request->guests,
            'payment_method' => $request->payment_method,
        ]);
    }

    public function bookFinalSubmit(Request $request)
    {
        $reservationId = DB::table('reservation')->insertGetId([
            'GUEST_ID'         => session('guest_id'),
            'ROOM_ID'          => $request->room_id,
            'Number_of_Guests' => $request->guests,
            'Check_In_Date'    => $request->check_in,
            'Check_Out_Date'   => $request->check_out,
            'Total_Amount'     => $request->amount,
            'Status'           => 'Pending',
            'created_at'       => \Carbon\Carbon::now(),
            'updated_at'       => \Carbon\Carbon::now(),
        ]);

        DB::table('payment')->insert([
            'RESERVATION_ID' => $reservationId,
            'Amount'         => $request->amount,
            'Payment_Date'   => \Carbon\Carbon::now(),
            'Payment_Method' => $request->payment_method,
            'created_at'     => \Carbon\Carbon::now(),
            'updated_at'     => \Carbon\Carbon::now(),
        ]);

        return redirect('/receipt/' . $reservationId)->with('success', 'Booking request submitted! Please wait for staff confirmation.');
    }

    public function receipt($id)
    {
        if (!session()->has('guest_id')) {
            return redirect('/login');
        }

        $receiptData = DB::table('reservation')
            ->join('room', 'reservation.ROOM_ID', '=', 'room.ROOM_ID')
            ->join('payment', 'reservation.RESERVATION_ID', '=', 'payment.RESERVATION_ID')
            ->join('guest', 'reservation.GUEST_ID', '=', 'guest.GUEST_ID')
            ->where('reservation.RESERVATION_ID', $id)
            ->where('reservation.GUEST_ID', session('guest_id'))
            ->first();

        if (!$receiptData) {
            return redirect('/reservations')->with('error', 'Receipt not found.');
        }

        return view('guest.receipt', ['data' => $receiptData]);
    }
}