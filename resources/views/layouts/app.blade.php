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
</head>
<body>

    @yield('content')

    @yield('scripts')
</body>
</html>
