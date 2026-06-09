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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('styles')
</head>
<body>

    @yield('content')

    @yield('scripts')
</body>
</html>
