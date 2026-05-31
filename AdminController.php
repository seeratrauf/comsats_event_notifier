<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_students' => User::where('role', 'student')->count(),
            'upcoming_events' => Event::upcoming()->count(),
            'total_enrollments' => Enrollment::count(),
        ];

        // List all events
        $events = Event::orderBy('date', 'desc')
            ->orderBy('time', 'desc')
            ->get();

        return view('admin.dashboard', compact('stats', 'events'));
    }

    public function createEvent()
    {
        return view('admin.events.create');
    }

    public function storeEvent(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required',
            'venue' => 'required|string|max:255',
            'type' => 'required|in:free,paid',
            'fee' => 'required_if:type,paid|nullable|numeric|min:0',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'fee.required_if' => 'The registration fee is required when the event is paid.',
            'date.after_or_equal' => 'The event date must be today or a future date.',
        ]);

        $posterPath = null;
        if ($request->hasFile('poster')) {
            $posterPath = $request->file('poster')->store('posters', 'public');
        }

        $event = Event::create([
            'title' => $request->title,
            'description' => $request->description,
            'date' => $request->date,
            'time' => $request->time,
            'venue' => $request->venue,
            'type' => $request->type,
            'fee' => $request->type === 'paid' ? $request->fee : 0.00,
            'poster_path' => $posterPath,
        ]);

        // Dispatch notifications to all registered students based on their preferences
        $this->notifyStudents($event);


        return redirect()->route('admin.dashboard')->with('success', 'Event created successfully and notifications dispatched.');
    }

    public function editEvent(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function updateEvent(Request $request, Event $event)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'time' => 'required',
            'venue' => 'required|string|max:255',
            'type' => 'required|in:free,paid',
            'fee' => 'required_if:type,paid|nullable|numeric|min:0',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'fee.required_if' => 'The registration fee is required when the event is paid.',
        ]);

        $posterPath = $event->poster_path;
        if ($request->hasFile('poster')) {
            // Delete old poster if exists
            if ($posterPath) {
                Storage::disk('public')->delete($posterPath);
            }
            $posterPath = $request->file('poster')->store('posters', 'public');
        }

        $event->update([
            'title' => $request->title,
            'description' => $request->description,
            'date' => $request->date,
            'time' => $request->time,
            'venue' => $request->venue,
            'type' => $request->type,
            'fee' => $request->type === 'paid' ? $request->fee : 0.00,
            'poster_path' => $posterPath,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Event updated successfully.');
    }

    public function destroyEvent(Event $event)
    {
        if ($event->poster_path) {
            Storage::disk('public')->delete($event->poster_path);
        }
        $event->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Event deleted successfully.');
    }

    public function viewEnrollments(Event $event)
    {
        $event->load(['enrolledStudents' => function ($query) {
            $query->orderBy('enrollments.created_at', 'desc');
        }]);

        return view('admin.events.enrollments', compact('event'));
    }

    private function notifyStudents(Event $event)
    {
        $students = User::where('role', 'student')->get();

        foreach ($students as $student) {
            $pref = $student->notification_preference;

            if ($pref === 'email' || $pref === 'both') {
                Log::info(sprintf(
                    "[EMAIL NOTIFICATION] Sent to %s (%s): New CS department event '%s' scheduled for %s at %s. Fee: %s.",
                    $student->name,
                    $student->email,
                    $event->title,
                    $event->date,
                    $event->time,
                    $event->type === 'paid' ? 'Rs. ' . $event->fee : 'Free'
                ));
            }

            if ($pref === 'sms' || $pref === 'both') {
                Log::info(sprintf(
                    "[SMS NOTIFICATION] Sent to %s (%s): Upcoming Event: '%s' on %s at %s. Venue: %s.",
                    $student->name,
                    $student->phone_number ?? 'N/A',
                    $event->title,
                    $event->date,
                    $event->time,
                    $event->venue
                ));
            }
        }
    }
}

