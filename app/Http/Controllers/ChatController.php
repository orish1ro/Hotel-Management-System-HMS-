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
        $chatMessage->GUEST_ID     = session('guest_id');
        $chatMessage->STAFF_ID     = null;
        $chatMessage->Message_Text = $request->message;
        $chatMessage->Status       = 'Unread';
        $chatMessage->save();

        return response()->json(['success' => true]);
    }

    public function fetchMessages()
    {
        if (!session()->has('guest_id')) {
            return response()->json([]);
        }

        $messages = Message::where('GUEST_ID', session('guest_id'))
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
            ->join('guest', 'messages.GUEST_ID', '=', 'guest.GUEST_ID')
            ->select(
                'messages.GUEST_ID as guest_id',
                DB::raw("CONCAT(guest.First_Name, ' ', guest.Last_Name) as guest_name")
            )
            ->distinct()
            ->get();

        return response()->json($guests);
    }

    public function fetchGuestMessages($guest_id)
    {
        $messages = Message::where('GUEST_ID', $guest_id)
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
        $chat->GUEST_ID     = (int) $request->guest_id;
        $chat->STAFF_ID     = session('staff_id') ?? null;
        $chat->Message_Text = $request->message;
        $chat->Status       = 'Sent';
        $chat->save();

        return response()->json(['success' => true]);
    }
}