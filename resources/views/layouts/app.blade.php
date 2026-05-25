<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FloodWatch — Sistem Informasi Geografis Banjir')</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Sistem Informasi Geografis (SIG) Pemetaan dan Pemantauan Daerah Rawan Banjir Provinsi Lampung secara real-time dan interaktif berbasis peta digital.">
    <meta name="keywords" content="SIG Banjir, Pemetaan Banjir, Lampung Banjir, Leaflet, Geografis, FloodWatch">
    
    <!-- Google Fonts & Stylesheets -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('styles')
    
    <!-- Leaflet.js Library (Loaded on demand or globally for mapping) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
</head>
<body>

    @yield('content')

    @yield('scripts')
</body>
</html>
