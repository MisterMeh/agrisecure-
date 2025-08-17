<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mail\TwoFactorCodeMail;
use Illuminate\Support\Facades\Mail;

class TwoFactorController extends Controller
{
   
    public function index()
    {
       
        if (!session('user_id')) {
            return redirect()->route('login');
        }

        return view('auth.2fa_verify');
    }

 
    public function store(Request $request)
    {
        $request->validate([
            'two_factor_code' => 'required|numeric',
        ]);

        $user = User::findOrFail(session('user_id'));

        
        if ($request->input('two_factor_code') == $user->two_factor_code && $user->two_factor_expires_at > now()) {
           
            $user->resetTwoFactorCode();

          
            Auth::login($user);

           
            $request->session()->forget('user_id');


            if ($user->role == 0) {
                return redirect()->intended(route('employee-file'));
            } else {
                return redirect()->intended(route('dashboard'));
            }
        }

        return redirect()->back()->withErrors(['two_factor_code' => 'The 2FA code is invalid or has expired.']);
    }

  
    public function resend(Request $request)
    {
        if (!session('user_id')) {
            return redirect()->route('login');
        }

        $user = User::findOrFail(session('user_id'));
        $user->generateTwoFactorCode();

     
        Mail::to($user->email)->send(new TwoFactorCodeMail($user->two_factor_code));

        return redirect()->back()->with('status', 'A new 2FA code has been sent to your email.');
    }
}
