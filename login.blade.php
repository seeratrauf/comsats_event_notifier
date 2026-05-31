@extends('layouts.app')

@section('title', 'Login - CUI Vehari Event Notifier')

@section('content')
<div class="auth-wrapper">
    <div class="card-glass auth-card">
        <div class="auth-header">
            <img src="{{ asset('images/comsats-logo.jpg') }}" alt="COMSATS University Logo">
            <h2>Sign In</h2>
            <p>Welcome back! Please enter your details below.</p>
        </div>

        <form action="{{ route('login') }}" method="POST">
            @csrf

            <!-- Email -->
            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required autofocus placeholder="student@example.com">
                @error('email')
                    <span class="form-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" required placeholder="••••••••">
                @error('password')
                    <span class="form-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="form-group" style="display: flex; align-items: center; gap: 0.5rem; margin-top: 0.5rem;">
                <input type="checkbox" name="remember" id="remember" style="cursor: pointer; width: 16px; height: 16px; accent-color: var(--primary);">
                <label for="remember" class="form-label" style="margin-bottom: 0; cursor: pointer; user-select: none; font-weight: 500;">Remember me</label>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn btn-primary btn-block" style="margin-top: 1.5rem;">
                Sign In
            </button>
        </form>

        <div style="text-align: center; margin-top: 1.5rem; font-size: 0.9rem; color: var(--text-muted);">
            Don't have an account? <a href="{{ route('register') }}" style="font-weight: 700; color: var(--primary);">Sign Up</a>
        </div>
    </div>
</div>
@endsection
