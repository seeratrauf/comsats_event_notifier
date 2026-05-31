<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Redirect root to login page or dashboard if already logged in
Route::get('/', function () {
    if (Auth::check()) {
        return Auth::user()->isAdmin() 
            ? redirect()->route('admin.dashboard') 
            : redirect()->route('student.dashboard');
    }
    return redirect()->route('login');
});

// Guest Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Administrator Module Routes
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // Event Management
        Route::get('/events/create', [AdminController::class, 'createEvent'])->name('events.create');
        Route::post('/events', [AdminController::class, 'storeEvent'])->name('events.store');
        Route::get('/events/{event}/edit', [AdminController::class, 'editEvent'])->name('events.edit');
        Route::put('/events/{event}', [AdminController::class, 'updateEvent'])->name('events.update');
        Route::delete('/events/{event}', [AdminController::class, 'destroyEvent'])->name('events.destroy');
        
        // Enrollments View
        Route::get('/events/{event}/enrollments', [AdminController::class, 'viewEnrollments'])->name('events.enrollments');
    });

    // Student Module Routes
    Route::middleware('student')->prefix('student')->name('student.')->group(function () {
        Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
        
        // Event enrollment actions
        Route::post('/events/{event}/enroll', [StudentController::class, 'enroll'])->name('enroll');
        Route::post('/events/{event}/unenroll', [StudentController::class, 'unenroll'])->name('unenroll');
        
        // Student Profile & Notification Preferences
        Route::get('/profile', [StudentController::class, 'profile'])->name('profile');
        Route::post('/profile', [StudentController::class, 'updateProfile'])->name('updateProfile');
    });
});
