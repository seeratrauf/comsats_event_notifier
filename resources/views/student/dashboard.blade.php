@extends('layouts.app')

@section('title', 'Student Dashboard - CUI Vehari Event Notifier')

@section('content')
</div> <!-- Close container to allow full-width ticker bar -->

<!-- Scrolling Notification Ticker -->
<div class="ticker-wrap">
    @if($upcomingEvents->isEmpty())
        <div class="ticker-move">
            <div class="ticker-item">📢 No upcoming Computer Science events scheduled at this moment. Stay tuned for updates!</div>
        </div>
    @else
        <div class="ticker-move">
            @foreach($upcomingEvents as $event)
                <div class="ticker-item">
                    📢 Upcoming Event: <strong>{{ $event->title }}</strong> on {{ \Carbon\Carbon::parse($event->date)->format('M d, Y') }} at {{ \Carbon\Carbon::parse($event->time)->format('h:i A') }} (Venue: {{ $event->venue }})
                    @if($event->type === 'paid')
                        <span class="ticker-badge-new">Paid (Rs. {{ number_format($event->fee, 0) }})</span>
                    @else
                        <span class="ticker-badge-new" style="background-color: var(--success); color: white;">Free</span>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>

<div class="container" style="margin-top: 2rem;"> <!-- Re-open container -->

<div class="dashboard-header">
    <div class="dashboard-title">
        <h2>Welcome back, {{ auth()->user()->name }}!</h2>
        <p>Computer Science Department • <strong>{{ auth()->user()->program }}</strong> Student</p>
    </div>
    <div>
        <a href="{{ route('student.profile') }}" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 0.25rem;">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
            </svg>
            Alert Settings
        </a>
    </div>
</div>

<!-- Alert Configuration Check -->
@if(auth()->user()->notification_preference === 'none')
    <div class="alert alert-danger" style="background-color: rgba(245, 158, 11, 0.08); color: #b45309; border-color: rgba(245, 158, 11, 0.2); margin-bottom: 2rem;">
        <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
        </svg>
        <span><strong>Notification Warning:</strong> You have turned off all event notifications. You will only see new events when you log in to this portal. <a href="{{ route('student.profile') }}" style="color: var(--primary-dark); font-weight: 700; text-decoration: underline;">Enable Email or SMS Alerts</a>.</span>
    </div>
@endif

<!-- Upcoming Events Section -->
<div class="events-section-title">
    <h3>Upcoming Academic Activities</h3>
</div>

@if($upcomingEvents->isEmpty())
    <div class="empty-state">
        <div class="empty-state-icon">🎉</div>
        <h3>No Upcoming Events</h3>
        <p>There are no upcoming CS department events scheduled at this moment. Please check back later!</p>
    </div>
@else
    <div class="events-grid">
        @foreach($upcomingEvents as $event)
            @php
                $isEnrolled = in_array($event->id, $enrolledEventIds);
            @endphp
            <div class="event-card">
                <div class="event-poster-wrapper">
                    @if($event->poster_path)
                        <img src="{{ asset('storage/' . $event->poster_path) }}" alt="{{ $event->title }}" class="event-poster">
                    @else
                        <!-- Premium Fallback Emblem using COMSATS official logo -->
                        <img src="{{ asset('images/comsats-logo.jpg') }}" alt="COMSATS logo" class="event-poster" style="object-fit: contain; padding: 2rem; background-color: var(--primary-dark);">
                    @endif
                    
                    <div class="event-badge-group">
                        @if($isEnrolled)
                            <span class="badge badge-enrolled">Enrolled</span>
                        @endif
                        @if($event->type === 'paid')
                            <span class="badge badge-paid">Paid: Rs. {{ number_format($event->fee, 0) }}</span>
                        @else
                            <span class="badge badge-free">Free</span>
                        @endif
                    </div>
                </div>

                <div class="event-details">
                    <div class="event-time-meta">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ \Carbon\Carbon::parse($event->date)->format('M d, Y') }} at {{ \Carbon\Carbon::parse($event->time)->format('h:i A') }}</span>
                    </div>

                    <h4 class="event-title">{{ $event->title }}</h4>
                    <p class="event-desc">{{ $event->description }}</p>

                    <div class="event-meta-info">
                        <div class="event-meta-item">
                            <span class="event-meta-label">Venue:</span>
                            <span style="color: var(--text-main);">{{ $event->venue }}</span>
                        </div>
                        <div class="event-meta-item">
                            <span class="event-meta-label">Cost:</span>
                            <span style="color: var(--text-main); font-weight: 600;">
                                @if($event->type === 'paid')
                                    Rs. {{ number_format($event->fee, 0) }}
                                @else
                                    Free Registration
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="event-actions">
                        @if($isEnrolled)
                            <form action="{{ route('student.unenroll', $event->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel your enrollment for this event?');">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-block">
                                    Cancel Enrollment
                                </button>
                            </form>
                        @else
                            <form action="{{ route('student.enroll', $event->id) }}" method="POST" onsubmit="return confirm('Would you like to register and enroll in this event?');">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-block">
                                    Enroll Now
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

@endsection
