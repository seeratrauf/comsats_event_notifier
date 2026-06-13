@extends('layouts.app')

@section('title', 'Edit Event - CUI Vehari Event Notifier')

@section('content')
<div style="max-width: 760px; margin: 0 auto;">
    <div style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
        <a href="{{ route('admin.dashboard') }}" style="color: var(--text-muted); font-weight: 500; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 0.25rem;">
            &larr; Back to Dashboard
        </a>
    </div>

    <div class="card-glass">
        <h2 style="font-size: 1.5rem; margin-bottom: 0.5rem; border-bottom: 2px solid var(--border-color); padding-bottom: 0.75rem;">Edit Event</h2>
        <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.5rem;">Modify the event fields below. Note that changing details will not send out new emails/SMS notification bursts to prevent spam.</p>

        <form action="{{ route('admin.events.update', $event->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div class="form-group">
                <label for="title" class="form-label">Event Title</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $event->title) }}" required>
                @error('title')
                    <span class="form-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Date & Time -->
            <div class="form-group row">
                <div>
                    <label for="date" class="form-label">Event Date</label>
                    <input type="date" name="date" id="date" class="form-control" value="{{ old('date', $event->date) }}" required>
                    @error('date')
                        <span class="form-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="time" class="form-label">Event Time</label>
                    <input type="time" name="time" id="time" class="form-control" value="{{ old('time', \Carbon\Carbon::parse($event->time)->format('H:i')) }}" required>
                    @error('time')
                        <span class="form-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Venue & Type -->
            <div class="form-group row">
                <div>
                    <label for="venue" class="form-label">Venue</label>
                    <input type="text" name="venue" id="venue" class="form-control" value="{{ old('venue', $event->venue) }}" required>
                    @error('venue')
                        <span class="form-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="type" class="form-label">Event Type</label>
                    <select name="type" id="type" class="form-control" required onchange="toggleFeeField(this.value)">
                        <option value="free" {{ old('type', $event->type) == 'free' ? 'selected' : '' }}>Free Event</option>
                        <option value="paid" {{ old('type', $event->type) == 'paid' ? 'selected' : '' }}>Paid Event</option>
                    </select>
                    @error('type')
                        <span class="form-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Registration Fee -->
            <div class="form-group" id="fee_group" style="display: {{ old('type', $event->type) == 'paid' ? 'block' : 'none' }};">
                <label for="fee" class="form-label">Registration Fee (PKR)</label>
                <input type="number" name="fee" id="fee" class="form-control" value="{{ old('fee', $event->fee) }}" min="0" step="1">
                @error('fee')
                    <span class="form-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Current and New Poster File Upload -->
            <div class="form-group">
                <label class="form-label">Event Poster</label>
                @if($event->poster_path)
                    <div style="margin-bottom: 0.75rem;">
                        <span style="font-size: 0.8rem; color: var(--text-muted); display: block; margin-bottom: 0.25rem;">Current Poster:</span>
                        <img src="{{ asset('storage/' . $event->poster_path) }}" alt="Current Poster" style="max-height: 140px; border-radius: var(--radius-sm); border: 1px solid #cbd5e1; box-shadow: var(--shadow-sm);">
                    </div>
                @endif
                <label for="poster" class="form-label" style="font-size: 0.8rem; font-weight: normal; color: var(--text-muted);">Upload New Poster (Optional - will replace current):</label>
                <input type="file" name="poster" id="poster" class="form-control" accept="image/*" style="padding: 0.5rem 1rem;">
                @error('poster')
                    <span class="form-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Description -->
            <div class="form-group">
                <label for="description" class="form-label">Event Description</label>
                <textarea name="description" id="description" class="form-control" rows="5" required style="resize: vertical;">{{ old('description', $event->description) }}</textarea>
                @error('description')
                    <span class="form-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Action buttons -->
            <div style="display: flex; gap: 1rem; margin-top: 2rem; border-top: 1px solid var(--border-color); padding-top: 1.5rem;">
                <button type="submit" class="btn btn-primary" style="flex: 2;">
                    Save Changes
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
            feeInput.required = true;
        } else {
            feeGroup.style.display = 'none';
            feeInput.required = false;
        }
    }
</script>
@endsection
