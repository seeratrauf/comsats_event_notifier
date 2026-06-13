@extends('layouts.app')

@section('title', 'Sign Up - CUI Vehari Event Notifier')

@section('content')
<div class="auth-wrapper" style="min-height: auto; padding: 1.5rem 1rem;">
    <div class="card-glass auth-card" style="max-width: 580px;">
        <div class="auth-header">
            <img src="{{ asset('images/comsats-logo.jpg') }}" alt="COMSATS University Logo">
            <h2>Create Account</h2>
            <p>CS Department Event Notifier Registration</p>
        </div>

        <form action="{{ route('register') }}" method="POST">
            @csrf

            <!-- Name -->
            <div class="form-group">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required placeholder="e.g. Ali Ahmed">
                @error('name')
                    <span class="form-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group row">
                <!-- Email -->
                <div>
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required placeholder="ali@example.com">
                    @error('email')
                        <span class="form-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <!-- CS Program -->
                <div>
                    <label for="program" class="form-label">CS Degree Program</label>
                    <select name="program" id="program" class="form-control" required>
                        <option value="" disabled {{ old('program') == '' ? 'selected' : '' }}>Select Degree</option>
                        <option value="BSCS" {{ old('program') == 'BSCS' ? 'selected' : '' }}>BS Computer Science (BSCS)</option>
                        <option value="BSSE" {{ old('program') == 'BSSE' ? 'selected' : '' }}>BS Software Engineering (BSSE)</option>
                        <option value="BSDS" {{ old('program') == 'BSDS' ? 'selected' : '' }}>BS Data Science (BSDS)</option>
                        <option value="MCS" {{ old('program') == 'MCS' ? 'selected' : '' }}>MCS</option>
                        <option value="MSCS" {{ old('program') == 'MSCS' ? 'selected' : '' }}>MS Computer Science</option>
                        <option value="PhD CS" {{ old('program') == 'PhD CS' ? 'selected' : '' }}>PhD Computer Science</option>
                    </select>
                    @error('program')
                        <span class="form-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Phone Number -->
            <div class="form-group">
                <label for="phone_number" class="form-label">Phone Number <small style="color: var(--text-muted); font-weight: normal;">(Required for SMS alerts)</small></label>
                <input type="text" name="phone_number" id="phone_number" class="form-control" value="{{ old('phone_number') }}" placeholder="e.g. +92 300 1234567">
                @error('phone_number')
                    <span class="form-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Passwords -->
            <div class="form-group row">
                <div>
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required placeholder="••••••••">
                    @error('password')
                        <span class="form-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required placeholder="••••••••">
                </div>
            </div>

            <!-- Notification Preference -->
            <div class="form-group">
                <label class="form-label" style="margin-bottom: 0.2rem;">How would you like to receive upcoming event alerts?</label>
                <span style="font-size: 0.75rem; color: var(--text-muted); display: block; margin-bottom: 0.5rem;">Choose your preferred notification channels:</span>
                
                <div class="preference-selector">
                    <div class="preference-option">
                        <input type="radio" name="notification_preference" id="pref_both" value="both" {{ old('notification_preference', 'both') == 'both' ? 'checked' : '' }}>
                        <label for="pref_both" class="preference-label">
                            <strong>Email & SMS</strong>
                            <small>Full alerts on both channels</small>
                        </label>
                    </div>

                    <div class="preference-option">
                        <input type="radio" name="notification_preference" id="pref_email" value="email" {{ old('notification_preference') == 'email' ? 'checked' : '' }}>
                        <label for="pref_email" class="preference-label">
                            <strong>Email Only</strong>
                            <small>Get details via inbox</small>
                        </label>
                    </div>

                    <div class="preference-option">
                        <input type="radio" name="notification_preference" id="pref_sms" value="sms" {{ old('notification_preference') == 'sms' ? 'checked' : '' }}>
                        <label for="pref_sms" class="preference-label">
                            <strong>SMS Only</strong>
                            <small>Get text notifications</small>
                        </label>
                    </div>

                    <div class="preference-option">
                        <input type="radio" name="notification_preference" id="pref_none" value="none" {{ old('notification_preference') == 'none' ? 'checked' : '' }}>
                        <label for="pref_none" class="preference-label">
                            <strong>None</strong>
                            <small>View on portal dashboard only</small>
                        </label>
                    </div>
                </div>
                @error('notification_preference')
                    <span class="form-feedback">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary btn-block" style="margin-top: 1.5rem;">
                Create Account
            </button>
        </form>

        <div style="text-align: center; margin-top: 1.5rem; font-size: 0.9rem; color: var(--text-muted);">
            Already have an account? <a href="{{ route('login') }}" style="font-weight: 700; color: var(--primary);">Sign In</a>
        </div>
    </div>
</div>
@endsection
