<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        if (Auth::check()) {
            return Auth::user()->isAdmin() 
                ? redirect()->route('admin.dashboard') 
                : redirect()->route('student.dashboard');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'program' => ['required', 'string', 'in:BSCS,BSSE,BSDS,MCS,MSCS,PhD CS'], // Strictly restrict to CS programs
            'phone_number' => ['required_if:notification_preference,sms,both', 'nullable', 'string', 'max:20'],
            'notification_preference' => ['required', 'string', 'in:email,sms,both,none'],
        ], [
            'program.in' => 'Registration is restricted strictly to Computer Science department students.',
            'phone_number.required_if' => 'A phone number is required to receive SMS notifications.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student', // Default self-registration role
            'program' => $request->program,
            'phone_number' => $request->phone_number,
            'notification_preference' => $request->notification_preference,
        ]);

        Auth::login($user);

        // Notify in session about successful signup
        return redirect()->route('student.dashboard')->with('success', 'Registration successful! Welcome to the CS Event Notifier.');
    }

    public function showLoginForm()
    {
        if (Auth::check()) {
            return Auth::user()->isAdmin() 
                ? redirect()->route('admin.dashboard') 
                : redirect()->route('student.dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            if (Auth::user()->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('student.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }
}
