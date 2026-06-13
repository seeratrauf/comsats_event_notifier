<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function dashboard()
    {
        $student = Auth::user();

        // Get all upcoming events
        $upcomingEvents = Event::upcoming()->get();

        // Get array of event IDs the student is currently enrolled in
        $enrolledEventIds = $student->enrolledEvents()->pluck('event_id')->toArray();

        return view('student.dashboard', compact('upcomingEvents', 'enrolledEventIds'));
    }

    public function enroll(Event $event)
    {
        $student = Auth::user();

        // Verify the event is upcoming
        if ($event->date < now()->toDateString()) {
            return back()->with('error', 'Cannot enroll in a past event.');
        }

        // Verify not already enrolled
        if ($student->enrolledEvents()->where('event_id', $event->id)->exists()) {
            return back()->with('error', 'You are already enrolled in this event.');
        }

        // Attach enrollment
        $student->enrolledEvents()->attach($event->id);

        return back()->with('success', sprintf('Successfully enrolled in "%s"!', $event->title));
    }

    public function unenroll(Event $event)
    {
        $student = Auth::user();

        // Detach enrollment
        $student->enrolledEvents()->detach($event->id);

        return back()->with('success', sprintf('Successfully cancelled enrollment for "%s".', $event->title));
    }

    public function profile()
    {
        $student = Auth::user();
        return view('student.profile', compact('student'));
    }

    public function updateProfile(Request $request)
    {
        $student = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required_if:notification_preference,sms,both', 'nullable', 'string', 'max:20'],
            'notification_preference' => ['required', 'string', 'in:email,sms,both,none'],
        ], [
            'phone_number.required_if' => 'A phone number is required to receive SMS notifications.',
        ]);

        $student->update([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'notification_preference' => $request->notification_preference,
        ]);

        return back()->with('success', 'Profile and notification preferences updated successfully.');
    }
}
