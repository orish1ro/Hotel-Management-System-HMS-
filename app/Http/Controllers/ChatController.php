<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Guest;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    // --- GUEST FUNCTIONS ---

    public function sendMessage(Request $request)
    {
        if (!session()->has('guest_id')) {
            return response()->json(['error' => 'Not logged in'], 401);
        }

        $chatMessage = new Message();
        $chatMessage->guest_id     = session('guest_id');
        $chatMessage->staff_id     = null;
        $chatMessage->sender_type  = 'Guest';
        $chatMessage->message_text = $request->message;
        $chatMessage->status       = 'Sent';
        $chatMessage->save();

        return response()->json(['success' => true]);
    }

    public function fetchMessages()
    {
        if (!session()->has('guest_id')) {
            return response()->json([]);
        }

        $messages = Message::where('guest_id', session('guest_id'))
                    ->orderBy('created_at', 'asc')
                    ->get();

        return response()->json($messages);
    }

    // --- STAFF FUNCTIONS ---

    public function staffIndex()
    {
        return view('staff.messages');
    }

    public function getChatSummary()
    {
        $guests = DB::table('messages')
            ->join('guest', 'messages.guest_id', '=', 'guest.GUEST_ID')
            ->select(
                'messages.guest_id',
                DB::raw("CONCAT(guest.First_Name, ' ', guest.Last_Name) as guest_name")
            )
            ->distinct()
            ->get();

        return response()->json($guests);
    }

    public function fetchGuestMessages($guest_id)
    {
        $messages = Message::where('guest_id', $guest_id)
                    ->orderBy('created_at', 'asc')
                    ->get();

        return response()->json($messages);
    }

    public function sendStaffMessage(Request $request)
    {
        $request->validate([
            'guest_id' => 'required',
            'message'  => 'required|string',
        ]);

        $chat = new Message();
        $chat->guest_id     = (int) $request->guest_id;
        // staff_id is null — the staff table has no seeded records so passing
        // any session value would violate the foreign key constraint
        $chat->staff_id = session('staff_id') ?? null;
        $chat->sender_type  = 'Staff';
        $chat->message_text = $request->message;
        $chat->status       = 'Sent';
        $chat->save();

        return response()->json(['success' => true]);
    }
}