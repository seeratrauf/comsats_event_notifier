@extends('layouts.app')

@section('title', 'Admin Dashboard - CUI Vehari Event Notifier')

@section('content')
<div class="dashboard-header">
    <div class="dashboard-title">
        <h2>Administrator Panel</h2>
        <p>Manage Computer Science department events, poster uploads, and student enrollments.</p>
    </div>
    <div>
        <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add New Event
        </a>
    </div>
</div>

<!-- Stats Section -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background-color: rgba(15, 118, 110, 0.08); color: var(--primary);">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
        </div>
        <div class="stat-info">
            <h3>Registered Students</h3>
            <p>{{ $stats['total_students'] }}</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background-color: rgba(245, 158, 11, 0.08); color: var(--accent);">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        </div>
        <div class="stat-info">
            <h3>Upcoming Events</h3>
            <p>{{ $stats['upcoming_events'] }}</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background-color: rgba(16, 185, 129, 0.08); color: var(--success);">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
            </svg>
        </div>
        <div class="stat-info">
            <h3>Total Enrollments</h3>
            <p>{{ $stats['total_enrollments'] }}</p>
        </div>
    </div>
</div>

<!-- Events Table List -->
<div class="events-section-title">
    <h3>All Departmental Events</h3>
</div>

@if($events->isEmpty())
    <div class="empty-state">
        <div class="empty-state-icon">📅</div>
        <h3>No Events Found</h3>
        <p>Start by creating the first event to notify students of upcoming activities.</p>
        <a href="{{ route('admin.events.create') }}" class="btn btn-primary btn-sm" style="margin-top: 1rem;">Create Event</a>
    </div>
@else
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 80px;">Poster</th>
                    <th>Event Details</th>
                    <th>Date & Time</th>
                    <th>Venue</th>
                    <th>Type</th>
                    <th style="text-align: center;">Enrolled</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($events as $event)
                    <tr>
                        <td>
                            @if($event->poster_path)
                                <img src="{{ asset('storage/' . $event->poster_path) }}" alt="Poster" style="width: 60px; height: 40px; object-fit: cover; border-radius: 4px; border: 1px solid #cbd5e1;">
                            @else
                                <div style="width: 60px; height: 40px; background-color: #e2e8f0; display: flex; align-items: center; justify-content: center; border-radius: 4px; font-size: 0.65rem; color: var(--text-muted); font-weight: bold; border: 1px solid #cbd5e1;">No Poster</div>
                            @endif
                        </td>
                        <td>
                            <strong style="font-size: 0.95rem; color: var(--primary-dark);">{{ $event->title }}</strong>
                            <p style="font-size: 0.8rem; color: var(--text-muted); max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $event->description }}</p>
                        </td>
                        <td>
                            <div style="font-size: 0.9rem; font-weight: 600;">{{ \Carbon\Carbon::parse($event->date)->format('M d, Y') }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">{{ \Carbon\Carbon::parse($event->time)->format('h:i A') }}</div>
                        </td>
                        <td style="font-size: 0.9rem;">{{ $event->venue }}</td>
                        <td>
                            @if($event->type === 'paid')
                                <span class="badge badge-paid">Paid: Rs. {{ number_format($event->fee, 0) }}</span>
                            @else
                                <span class="badge badge-free">Free</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <a href="{{ route('admin.events.enrollments', $event->id) }}" class="btn btn-secondary btn-sm" style="font-size: 0.75rem; padding: 0.25rem 0.5rem; display: inline-flex; align-items: center; gap: 0.25rem;">
                                <strong>{{ $event->enrolledStudents()->count() }}</strong> Enrolled
                            </a>
                        </td>
                        <td style="text-align: right;">
                            <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                                <a href="{{ route('admin.events.edit', $event->id) }}" class="btn btn-secondary btn-sm" style="padding: 0.35rem 0.6rem;">
                                    Edit
                                </a>
                                <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this event? This will remove all student enrollments associated with it.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" style="padding: 0.35rem 0.6rem;">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection
