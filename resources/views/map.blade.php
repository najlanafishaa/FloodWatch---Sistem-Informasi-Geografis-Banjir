@extends('layouts.app')

@section('title', 'Peta Interaktif Banjir Lampung — FloodWatch')

@section('styles')
    {{-- Leaflet CSS - only loaded on map page --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <link rel="stylesheet" href="{{ asset('css/map.css') }}">
@endsection

@section('content')
    <div class="map-page-wrapper">
        
        <!-- Floating Home Button -->
        <a href="{{ route('landing') }}" class="map-home-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Kembali ke Beranda
        </a>

        <!-- Mobile Filter Toggle Button -->
        <button class="btn-primary mobile-toggle-filters-btn" id="mobile-toggle-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
            Filter Peta
        </button>

        <!-- Sidebar Filter Panel -->
        <aside class="map-sidebar glass-panel" id="map-sidebar">
            <div class="sidebar-header">
                <a href="{{ route('landing') }}" class="sidebar-brand" style="text-decoration: none;">
                    <span>FloodWatch<span class="brand-dot">.</span></span>
                </a>
                <button class="sidebar-close-btn" id="sidebar-close-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            
            <!-- Tab Navigation -->
            <div class="sidebar-tabs">
                <button class="tab-btn active" data-tab="realtime" style="width: 100%;">Banjir Aktif</button>
            </div>

            <!-- Tab 1: Real-time Content -->
            <div class="sidebar-scrollable-content" id="tab-realtime-content">
                <!-- Search Filter -->
                <div>
                    <label class="filter-group-title">Cari Lokasi</label>
                    <div class="search-wrapper">
                        <input type="text" id="search-input" class="search-input" placeholder="Cari nama kelurahan/desa/jalan..." value="{{ request('search') }}">
                        <svg class="search-icon-svg" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    </div>
                </div>

                <!-- Region Filter -->
                <div>
                    <label class="filter-group-title">Wilayah Fokus</label>
                    <select id="region-filter" class="custom-select">
                        <option value="">-- Semua Wilayah --</option>
                        @foreach($regions as $region)
                            <option value="{{ $region->id }}" {{ request('region_id') == $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="filter-group-title">Status Kerawanan</label>
                    <select id="status-filter" class="custom-select">
                        <option value="">-- Semua Status --</option>
                        <option value="Aman">AMAN (Surut/Kondusif)</option>
                        <option value="Waspada">WASPADA (Rendah)</option>
                        <option value="Siaga">SIAGA (Sedang)</option>
                        <option value="Awas">AWAS (Tinggi/Ekstrem)</option>
                    </select>
                </div>

                <!-- Result Incidents List -->
                <div>
                    <div class="results-info-container">
                        <label class="filter-group-title" style="margin-bottom: 0;">Titik Banjir</label>
                        <span class="results-count" id="results-counter">Memuat data...</span>
                    </div>
                    
                    <div class="incidents-list" id="incidents-list" style="margin-top: 1rem;">
                        <!-- List loaded dynamically via AJAX -->
                    </div>
                </div>
            </div>


        </aside>

        <!-- Map Canvas Container -->
        <main class="map-canvas-container">
            <div id="map"></div>
        </main>
        
    </div>
@endsection

@section('scripts')
    {{-- Leaflet JS - only loaded on map page --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <!-- Pass Laravel Asset Helper Paths to JS -->
    <script>
        window.floodWatchConfig = {
            apiFloodsUrl: "{{ route('api.floods') }}",
            apiGeojsonUrl: "{{ route('api.geojson') }}",
            apiBpsGeojsonUrl: "{{ route('api.bps-geojson') }}",
            apiDatasetExistsUrl: "{{ route('api.dataset-exists') }}",
            apiDatasetStatsUrl: "{{ route('api.dataset-stats') }}",
            defaultRegionId: "{{ request('region_id', '') }}"
        };
    </script>
    <script src="{{ asset('js/map.js') }}"></script>
    <script src="{{ asset('js/kaggle-analysis.js') }}"></script>
@endsection
