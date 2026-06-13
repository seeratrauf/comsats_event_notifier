<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CUI Vehari CS Event Notifier')</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @yield('styles')
</head>
<body>

    <!-- Header Navigation -->
    <nav class="navbar">
        <div class="container">
            <a href="{{ url('/') }}" class="navbar-brand">
                <img src="{{ asset('images/comsats-logo.jpg') }}" alt="COMSATS Logo" class="navbar-logo">
                <div class="brand-text">
                    <h1>COMSATS UNIVERSITY ISLAMABAD</h1>
                    <p>Vehari Campus • CS Event Notifier</p>
                </div>
            </a>
            
            <ul class="navbar-nav">
                @auth
                    @if(auth()->user()->isAdmin())
                        <li><a href="{{ route('admin.dashboard') }}" style="color: #fff; font-weight: 600;">Admin Dashboard</a></li>
                        <li><a href="{{ route('admin.events.create') }}" style="color: var(--accent); font-weight: 600;">Add Event</a></li>
                    @else
                        <li><a href="{{ route('student.dashboard') }}" style="color: #fff; font-weight: 600;">Events Portal</a></li>
                        <li><a href="{{ route('student.profile') }}" style="color: var(--primary-light); font-weight: 600;">My Preferences</a></li>
                    @endif
                    <li class="navbar-user">
                        <span class="user-badge">{{ auth()->user()->isAdmin() ? 'Admin' : auth()->user()->program }}</span>
                        <span style="font-weight: 500;">{{ auth()->user()->name }}</span>
                    </li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn-nav-logout">Logout</button>
                        </form>
                    </li>
                @else
                    <li><a href="{{ route('login') }}" style="color: #fff; font-weight: 600;">Login</a></li>
                    <li><a href="{{ route('register') }}" style="color: var(--accent); font-weight: 600;">Sign Up</a></li>
                @endauth
            </ul>
        </div>
    </nav>

    <!-- Main View Section -->
    <div class="main-content">
        <div class="container">
            <!-- Flash Message Alerts -->
            @if(session('success'))
                <div class="alert alert-success">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; {{ date('Y') }} COMSATS University Islamabad, Vehari Campus. All rights reserved.</p>
            <p style="margin-top: 0.25rem; font-size: 0.75rem; color: #64748b;">CS Department Portal • Programmed with <span>♥</span></p>
        </div>
    </footer>

    @yield('scripts')
</body>
</html>
