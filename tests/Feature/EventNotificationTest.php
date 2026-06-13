<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class EventNotificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_guests_can_view_registration_form()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    /** @test */
    public function test_student_registration_requires_a_computer_science_degree()
    {
        // Attempt registration with non-CS degree program
        $response = $this->post('/register', [
            'name' => 'Ali Ahmed',
            'email' => 'ali@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'program' => 'BBA', // Non-CS degree
            'phone_number' => '+923001234567',
            'notification_preference' => 'both',
        ]);

        $response->assertSessionHasErrors('program');
        $this->assertDatabaseCount('users', 0);
    }

    /** @test */
    public function test_student_can_register_with_valid_computer_science_degree()
    {
        $response = $this->post('/register', [
            'name' => 'Ali Ahmed',
            'email' => 'ali@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'program' => 'BSCS', // Valid CS degree
            'phone_number' => '+923001234567',
            'notification_preference' => 'both',
        ]);

        $response->assertRedirect('/student/dashboard');
        $this->assertDatabaseHas('users', [
            'email' => 'ali@example.com',
            'program' => 'BSCS',
            'notification_preference' => 'both',
        ]);
        
        $this->assertTrue(auth()->check());
    }

    /** @test */
    public function test_students_cannot_access_admin_dashboard()
    {
        $student = User::factory()->create([
            'role' => 'student',
            'program' => 'BSCS',
        ]);

        $response = $this->actingAs($student)->get('/admin/dashboard');
        $response->assertRedirect('/student/dashboard');
    }

    /** @test */
    public function test_admins_can_access_admin_dashboard_and_are_redirected_correctly()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'program' => null,
        ]);

        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $response->assertStatus(200);
    }

    /** @test */
    public function test_admins_can_create_events_and_dispatch_notifications()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        // Create student to verify notification log
        $student = User::factory()->create([
            'role' => 'student',
            'program' => 'BSSE',
            'notification_preference' => 'both',
            'phone_number' => '+923339876543',
        ]);

        Log::shouldReceive('info')->twice(); // Once for Email notification, once for SMS notification

        $response = $this->actingAs($admin)->post('/admin/events', [
            'title' => 'AI Seminar 2026',
            'description' => 'A seminar exploring advanced agentic AI models.',
            'date' => now()->addDays(5)->toDateString(),
            'time' => '10:00',
            'venue' => 'Seminar Hall',
            'type' => 'paid',
            'fee' => '250',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertDatabaseHas('events', [
            'title' => 'AI Seminar 2026',
            'type' => 'paid',
            'fee' => 250.00,
        ]);
    }

    /** @test */
    public function test_students_can_enroll_and_unenroll_in_events()
    {
        $student = User::factory()->create([
            'role' => 'student',
            'program' => 'BSDS',
        ]);

        $event = Event::create([
            'title' => 'Git Workshop',
            'description' => 'Learn version control best practices.',
            'date' => now()->addDays(2)->toDateString(),
            'time' => '14:00',
            'venue' => 'CS Lab 1',
            'type' => 'free',
            'fee' => 0.00,
        ]);

        // Enroll
        $response = $this->actingAs($student)->post("/student/events/{$event->id}/enroll");
        $response->assertRedirect();
        $this->assertDatabaseHas('enrollments', [
            'user_id' => $student->id,
            'event_id' => $event->id,
        ]);

        // Re-enrollment check (should fail)
        $response2 = $this->actingAs($student)->post("/student/events/{$event->id}/enroll");
        $response2->assertSessionHas('error', 'You are already enrolled in this event.');

        // Unenroll
        $response3 = $this->actingAs($student)->post("/student/events/{$event->id}/unenroll");
        $response3->assertRedirect();
        $this->assertDatabaseMissing('enrollments', [
            'user_id' => $student->id,
            'event_id' => $event->id,
        ]);
    }
}
