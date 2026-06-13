@extends('layouts.app')

@section('title', 'Add New Event - CUI Vehari Event Notifier')

@section('content')
<div style="max-width: 760px; margin: 0 auto;">
    <div style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
        <a href="{{ route('admin.dashboard') }}" style="color: var(--text-muted); font-weight: 500; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 0.25rem;">
            &larr; Back to Dashboard
        </a>
    </div>

    <div class="card-glass">
        <h2 style="font-size: 1.5rem; margin-bottom: 0.5rem; border-bottom: 2px solid var(--border-color); padding-bottom: 0.75rem;">Create Upcoming Event</h2>
        <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.5rem;">Fill in the details below to add a departmental event. Students will be notified based on their registered alerts (Email/SMS).</p>

        <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Title -->
            <div class="form-group">
                <label for="title" class="form-label">Event Title</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required placeholder="e.g. Workshop on Web Application Development">
                @error('title')
                    <span class="form-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Date & Time -->
            <div class="form-group row">
                <div>
                    <label for="date" class="form-label">Event Date</label>
                    <input type="date" name="date" id="date" class="form-control" value="{{ old('date') }}" required>
                    @error('date')
                        <span class="form-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="time" class="form-label">Event Time</label>
                    <input type="time" name="time" id="time" class="form-control" value="{{ old('time') }}" required>
                    @error('time')
                        <span class="form-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Venue & Type -->
            <div class="form-group row">
                <div>
                    <label for="venue" class="form-label">Venue</label>
                    <input type="text" name="venue" id="venue" class="form-control" value="{{ old('venue') }}" required placeholder="e.g. CS Lab 2 / Seminar Hall">
                    @error('venue')
                        <span class="form-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="type" class="form-label">Event Type</label>
                    <select name="type" id="type" class="form-control" required onchange="toggleFeeField(this.value)">
                        <option value="free" {{ old('type') == 'free' ? 'selected' : '' }}>Free Event</option>
                        <option value="paid" {{ old('type') == 'paid' ? 'selected' : '' }}>Paid Event</option>
                    </select>
                    @error('type')
                        <span class="form-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Registration Fee (Conditional) -->
            <div class="form-group" id="fee_group" style="display: {{ old('type') == 'paid' ? 'block' : 'none' }};">
                <label for="fee" class="form-label">Registration Fee (PKR)</label>
                <input type="number" name="fee" id="fee" class="form-control" value="{{ old('fee', '0') }}" min="0" step="1" placeholder="e.g. 500">
                @error('fee')
                    <span class="form-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Poster File Upload -->
            <div class="form-group">
                <label for="poster" class="form-label">Event Poster <small style="color: var(--text-muted); font-weight: normal;">(Optional - JPEG, PNG up to 2MB)</small></label>
                <input type="file" name="poster" id="poster" class="form-control" accept="image/*" style="padding: 0.5rem 1rem;">
                @error('poster')
                    <span class="form-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Description -->
            <div class="form-group">
                <label for="description" class="form-label">Event Description</label>
                <textarea name="description" id="description" class="form-control" rows="5" required placeholder="Describe the topics covered, speakers, and benefits of attending this event..." style="resize: vertical;">{{ old('description') }}</textarea>
                @error('description')
                    <span class="form-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Action buttons -->
            <div style="display: flex; gap: 1rem; margin-top: 2rem; border-top: 1px solid var(--border-color); padding-top: 1.5rem;">
                <button type="submit" class="btn btn-primary" style="flex: 2;">
                    Publish Event & Notify Students
                </button>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary" style="flex: 1; text-align: center;">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function toggleFeeField(val) {
        const feeGroup = document.getElementById('fee_group');
        const feeInput = document.getElementById('fee');
        
        if (val === 'paid') {
            feeGroup.style.display = 'block';
            if (feeInput.value === '0') {
                feeInput.value = '';
            }
            feeInput.required = true;
        } else {
            feeGroup.style.display = 'none';
            feeInput.required = false;
        }
    }
</script>
@endsection
