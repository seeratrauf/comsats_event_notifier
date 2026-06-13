@extends('layouts.app')

@section('title', 'Event Enrollments - CUI Vehari Event Notifier')

@section('content')
<div style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
    <a href="{{ route('admin.dashboard') }}" style="color: var(--text-muted); font-weight: 500; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 0.25rem;">
        &larr; Back to Dashboard
    </a>
</div>

<div class="card-glass" style="margin-bottom: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem;">
        <div>
            <span style="font-size: 0.8rem; font-weight: 700; color: var(--primary); text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 0.25rem;">Enrollments List</span>
            <h2 style="font-size: 1.6rem; line-height: 1.2; margin-bottom: 0.5rem;">{{ $event->title }}</h2>
            <div style="font-size: 0.9rem; color: var(--text-muted); display: flex; gap: 1.5rem; flex-wrap: wrap; margin-top: 0.5rem;">
                <span><strong>Date:</strong> {{ \Carbon\Carbon::parse($event->date)->format('M d, Y') }} at {{ \Carbon\Carbon::parse($event->time)->format('h:i A') }}</span>
                <span><strong>Venue:</strong> {{ $event->venue }}</span>
                <span><strong>Type:</strong> 
                    @if($event->type === 'paid')
                        <span class="badge badge-paid" style="box-shadow: none;">Paid: Rs. {{ number_format($event->fee, 0) }}</span>
                    @else
                        <span class="badge badge-free" style="box-shadow: none;">Free</span>
                    @endif
                </span>
            </div>
        </div>
        <div style="background-color: var(--primary-dark); color: #fff; padding: 0.75rem 1.5rem; border-radius: var(--radius-md); text-align: center;">
            <div style="font-size: 1.75rem; font-weight: 800; line-height: 1.2;">{{ $event->enrolledStudents->count() }}</div>
            <div style="font-size: 0.7rem; text-transform: uppercase; font-weight: 700; color: var(--primary-light); letter-spacing: 0.5px;">Total Enrolled</div>
        </div>
    </div>
</div>

<div class="events-section-title">
    <h3>Registered CS Students</h3>
</div>

@if($event->enrolledStudents->isEmpty())
    <div class="empty-state">
        <div class="empty-state-icon">👥</div>
        <h3>No Enrollments Yet</h3>
        <p>No Computer Science students have enrolled in this event so far.</p>
    </div>
@else
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Email Address</th>
                    <th>CS Degree Program</th>
                    <th>Phone Number</th>
                    <th>Alert Channels</th>
                    <th>Enrolled At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($event->enrolledStudents as $student)
                    <tr>
                        <td>
                            <strong style="color: var(--primary-dark); font-size: 0.95rem;">{{ $student->name }}</strong>
                        </td>
                        <td>{{ $student->email }}</td>
                        <td>
                            <span class="user-badge" style="background-color: rgba(15, 118, 110, 0.05); border-color: rgba(15, 118, 110, 0.2); color: var(--primary);">
                                {{ $student->program }}
                            </span>
                        </td>
                        <td>{{ $student->phone_number ?? 'Not provided' }}</td>
                        <td style="text-transform: capitalize;">
                            @if($student->notification_preference === 'both')
                                <span style="color: var(--success); font-weight: 600;">Email & SMS</span>
                            @elseif($student->notification_preference === 'email')
                                <span style="color: var(--primary); font-weight: 600;">Email Only</span>
                            @elseif($student->notification_preference === 'sms')
                                <span style="color: var(--accent-hover); font-weight: 600;">SMS Only</span>
                            @else
                                <span style="color: var(--text-muted);">None</span>
                            @endif
                        </td>
                        <td style="font-size: 0.85rem; color: var(--text-muted);">
                            {{ \Carbon\Carbon::parse($student->pivot->created_at)->format('M d, Y') }}
                            <span style="font-size: 0.75rem; display: block; color: #94a3b8;">{{ \Carbon\Carbon::parse($student->pivot->created_at)->format('h:i A') }}</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection
