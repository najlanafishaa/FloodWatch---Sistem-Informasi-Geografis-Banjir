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
                <div class="sidebar-brand">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: var(--primary-blue); display: inline-block; vertical-align: middle; margin-right: 0.3rem;"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    <span>FloodWatch<span class="brand-dot">.</span></span>
                </div>
                <button class="sidebar-close-btn" id="sidebar-close-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            
            <!-- Tab Navigation -->
            <div class="sidebar-tabs">
                <button class="tab-btn active" data-tab="realtime">Banjir Aktif</button>
                <button class="tab-btn" data-tab="kaggle">Analisis Kaggle</button>
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

            <!-- Tab 2: Kaggle Dataset Content -->
            <div class="sidebar-scrollable-content d-none" id="tab-kaggle-content">
                <!-- Layers Control Section -->
                <div>
                    <label class="filter-group-title">Lapisan Peta (Layers)</label>
                    <div class="layers-container">
                        <label class="layer-item">
                            <input type="checkbox" id="layer-geojson-checkbox" checked>
                            <span class="layer-dot" style="background: var(--primary-blue);"></span>
                            <span class="layer-label">Batas Administrasi (GeoJSON)</span>
                        </label>
                        <label class="layer-item">
                            <input type="checkbox" id="layer-realtime-checkbox" checked>
                            <span class="layer-dot" style="background: var(--status-awas);"></span>
                            <span class="layer-label">Titik Banjir Real-Time</span>
                        </label>
                        <label class="layer-item">
                            <input type="checkbox" id="layer-bps-geojson-checkbox" checked>
                            <span class="layer-dot" style="background: var(--primary-green);"></span>
                            <span class="layer-label">Batas Administrasi BPS (GeoJSON)</span>
                        </label>
                        <label class="layer-item">
                            <input type="checkbox" id="layer-esri-checkbox">
                            <span class="layer-dot" style="background: var(--primary-yellow);"></span>
                            <span class="layer-label">Esri Satellite Basemap</span>
                        </label>
                    </div>
                </div>

                <!-- Kaggle Weather & Flood Correlation Statistics -->
                <div>
                    <div class="results-info-container">
                        <label class="filter-group-title" style="margin-bottom: 0;">Korelasi Variabel Cuaca</label>
                        <span class="results-count" id="kaggle-dataset-status">Dataset Lampung</span>
                    </div>
                    
                    <div class="kaggle-stats-grid" id="kaggle-stats-grid">
                        <div class="stat-loading">Klik tab ini untuk memuat analisis cuaca...</div>
                    </div>
                </div>

                <!-- Recent Weather Datasets List -->
                <div>
                    <label class="filter-group-title" style="margin-bottom: 0.8rem;">Data Cuaca Historis</label>
                    <div class="historical-records-list" id="historical-records-list">
                        <!-- Loaded dynamically via AJAX -->
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
