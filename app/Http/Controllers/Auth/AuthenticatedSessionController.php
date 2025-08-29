<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;
use App\Mail\TwoFactorCodeMail;
use Illuminate\Support\Facades\Mail;

use App\Traits\LogsActivity;

class AuthenticatedSessionController extends Controller
{
    use LogsActivity;
    
    public function create(): View
    {
        return view('auth.login');
    }

   
    public function store(LoginRequest $request): RedirectResponse
    {
       
        $request->validate([
            'captcha' => 'required|captcha'
        ], [
            'captcha.captcha' => 'Invalid captcha'
        ]);
        $request->authenticate();
        $user = Auth::user();

        $this->logActivity("User '{$user->name}' logged in");

        $user->generateTwoFactorCode();
        try {
            Mail::to($user->email)->send(new TwoFactorCodeMail($user->two_factor_code));
        } catch (\Exception $e) {
            //$this->logActivity("Failed to send 2FA code to user '{$user->name}'.");
            return redirect()->back()->withErrors(['email' => 'Could not send 2FA code. Please try again later.']);
        }



      
        $userId = $user->id;
        Auth::logout();
        $request->session()->put('user_id', $userId);

       
        return redirect()->route('2fa.index');
    }


    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
