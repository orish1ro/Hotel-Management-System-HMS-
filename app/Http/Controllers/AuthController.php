<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validate that the user actually typed an email and password
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. The "Security Guard" Check: Attempt to match the database
        if (Auth::attempt($credentials)) {
            // SUCCESS: They exist in the database and the password is correct!
            $request->session()->regenerate();
            
            // Send them to the Home Page or Dashboard
            return redirect('/'); 
        }

        // 3. FAILED: The email or password was wrong, or they haven't signed up
        return back()->withErrors([
            'email' => 'Wait! You cannot login because you have not signed up yet.',
        ])->onlyInput('email');
    }
}
