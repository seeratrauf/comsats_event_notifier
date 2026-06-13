@extends('layouts.app')

@section('title', 'My Alert Settings - CUI Vehari Event Notifier')

@section('content')
<div style="max-width: 680px; margin: 0 auto;">
    <div style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
        <a href="{{ route('student.dashboard') }}" style="color: var(--text-muted); font-weight: 500; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 0.25rem;">
            &larr; Back to Events Portal
        </a>
    </div>

    <div class="card-glass">
        <h2 style="font-size: 1.5rem; margin-bottom: 0.5rem; border-bottom: 2px solid var(--border-color); padding-bottom: 0.75rem;">Notification Preferences</h2>
        <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.5rem;">Update your profile settings and choose how you want to be alerted about newly published Computer Science department events.</p>

        <form action="{{ route('student.updateProfile') }}" method="POST">
            @csrf

            <!-- Name -->
            <div class="form-group">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $student->name) }}" required>
                @error('name')
                    <span class="form-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Email (Read-Only) -->
            <div class="form-group row">
                <div>
                    <label class="form-label">Email Address</label>
                    <input type="text" class="form-control" value="{{ $student->email }}" readonly style="background-color: var(--bg-light); cursor: not-allowed; border-color: #cbd5e1;">
                    <span style="font-size: 0.75rem; color: var(--text-muted); display: block; margin-top: 0.2rem;">Email cannot be changed after registration.</span>
                </div>
                <!-- Degree Program (Read-Only) -->
                <div>
                    <label class="form-label">CS Degree Program</label>
                    <input type="text" class="form-control" value="{{ $student->program }}" readonly style="background-color: var(--bg-light); cursor: not-allowed; border-color: #cbd5e1;">
                    <span style="font-size: 0.75rem; color: var(--text-muted); display: block; margin-top: 0.2rem;">Department degree program is locked.</span>
                </div>
            </div>

            <!-- Phone Number -->
            <div class="form-group">
                <label for="phone_number" class="form-label">Phone Number <small style="color: var(--text-muted); font-weight: normal;">(Required if SMS alerts are enabled)</small></label>
                <input type="text" name="phone_number" id="phone_number" class="form-control" value="{{ old('phone_number', $student->phone_number) }}" placeholder="e.g. +92 300 1234567">
                @error('phone_number')
                    <span class="form-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Notification Preference -->
            <div class="form-group">
                <label class="form-label" style="margin-bottom: 0.2rem;">How would you like to receive upcoming event alerts?</label>
                <span style="font-size: 0.75rem; color: var(--text-muted); display: block; margin-bottom: 0.5rem;">Configure your active notification channels:</span>
                
                <div class="preference-selector">
                    <div class="preference-option">
                        <input type="radio" name="notification_preference" id="pref_both" value="both" {{ old('notification_preference', $student->notification_preference) == 'both' ? 'checked' : '' }}>
                        <label for="pref_both" class="preference-label">
                            <strong>Email & SMS</strong>
                            <small>Full alerts on both channels</small>
                        </label>
                    </div>

                    <div class="preference-option">
                        <input type="radio" name="notification_preference" id="pref_email" value="email" {{ old('notification_preference', $student->notification_preference) == 'email' ? 'checked' : '' }}>
                        <label for="pref_email" class="preference-label">
                            <strong>Email Only</strong>
                            <small>Get details via inbox</small>
                        </label>
                    </div>

                    <div class="preference-option">
                        <input type="radio" name="notification_preference" id="pref_sms" value="sms" {{ old('notification_preference', $student->notification_preference) == 'sms' ? 'checked' : '' }}>
                        <label for="pref_sms" class="preference-label">
                            <strong>SMS Only</strong>
                            <small>Get text notifications</small>
                        </label>
                    </div>

                    <div class="preference-option">
                        <input type="radio" name="notification_preference" id="pref_none" value="none" {{ old('notification_preference', $student->notification_preference) == 'none' ? 'checked' : '' }}>
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
                Update Preferences
            </button>
        </form>
    </div>
</div>
@endsection
