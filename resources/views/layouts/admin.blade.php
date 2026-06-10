<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel — FloodWatch')</title>
    
    <!-- Design System & Admin Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @yield('styles')
</head>
<body>

    <div class="admin-wrapper">
        
        <!-- Admin Navigation Sidebar -->
        <aside class="admin-sidebar" id="admin-sidebar">
            <div class="admin-logo">
                <span>FloodWatch Admin<span style="color: var(--primary-blue);">.</span></span>
            </div>
            
            <ul class="admin-nav">
                <!-- Dashboard overview -->
                <li class="admin-nav-item {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9"/><rect x="14" y="3" width="7" height="5"/><rect x="14" y="12" width="7" height="9"/><rect x="3" y="16" width="7" height="5"/></svg>
                        Ringkasan
                    </a>
                </li>
                
                <!-- Manage flood data -->
                <li class="admin-nav-item {{ Route::is('admin.floods.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.floods.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        Data Banjir
                    </a>
                </li>
                
                <!-- Upload geojson layers -->
                <li class="admin-nav-item {{ Route::is('admin.geojson.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.geojson.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>
                        Upload GeoJSON
                    </a>
                </li>
                
                <!-- View map -->
                <li class="admin-nav-item">
                    <a href="{{ route('map') }}" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/><line x1="9" y1="3" x2="9" y2="18"/><line x1="15" y1="6" x2="15" y2="21"/></svg>
                        Lihat Peta Publik
                    </a>
                </li>
            </ul>
            
            <div class="admin-sidebar-footer">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="admin-logout-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                        Logout Admin
                    </button>
                </form>
            </div>
        </aside>
        
        <!-- Main Content Area -->
        <main class="admin-content">
            
            <!-- Alert Session Banners -->
            @if (session('success'))
                <div class="alert-banner alert-success" id="success-banner">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button onclick="document.getElementById('success-banner').remove()" style="background:transparent; border:none; color:inherit; cursor:pointer;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert-banner alert-error" id="error-banner">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button onclick="document.getElementById('error-banner').remove()" style="background:transparent; border:none; color:inherit; cursor:pointer;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>
            @endif

            @yield('content')
            
        </main>
        
    </div>

    @yield('scripts')
</body>
</html>
