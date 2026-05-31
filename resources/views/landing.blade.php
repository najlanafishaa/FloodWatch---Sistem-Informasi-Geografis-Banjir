@extends('layouts.app')

@section('title', 'FloodWatch — SIG Pemetaan Banjir Provinsi Lampung')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
@endsection

@section('content')
    <!-- Navbar -->
    <nav class="nav-container">
        <a href="{{ route('landing') }}" class="brand">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: var(--primary-blue);"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            <span>FloodWatch<span class="brand-dot">.</span></span>
        </a>
        <ul class="nav-links">
            <li><a href="{{ route('landing') }}" class="nav-link active">Beranda</a></li>
            <li><a href="{{ route('map') }}" class="nav-link">Peta Interaktif</a></li>
            @auth
                <li><a href="{{ route('admin.dashboard') }}" class="btn-secondary" style="padding: 0.5rem 1.2rem; font-size: 0.85rem;">Dashboard</a></li>
            @else
                <li><a href="{{ route('login') }}" class="btn-secondary" style="padding: 0.5rem 1.2rem; font-size: 0.85rem;">Login Admin</a></li>
            @endauth
        </ul>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        @if($totalActiveFloods > 0)
            <div class="hero-tag">
                <span class="hero-tag-dot"></span>
                <span>Lampung Status Siaga: {{ $totalActiveFloods }} Titik Terdeteksi</span>
            </div>
        @else
            <div class="hero-tag" style="background: var(--status-aman-bg); color: var(--status-aman); border-color: var(--primary-dark-blue);">
                <span class="hero-tag-dot" style="background-color: var(--status-aman); animation: none; border: 1px solid var(--primary-dark-blue);"></span>
                <span>Lampung Kondisi Aman Kondusif</span>
            </div>
        @endif
        
        <h1 class="hero-title">Sistem Informasi Geografis Pemetaan Banjir Lampung</h1>
        <p class="hero-subtitle">Pantau titik kerawanan banjir, ketinggian air, status siaga wilayah, serta data spasial Lampung secara real-time dan interaktif untuk keselamatan bersama.</p>
        
        <div class="hero-actions">
            <a href="{{ route('map') }}" class="btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/><line x1="9" y1="3" x2="9" y2="18"/><line x1="15" y1="6" x2="15" y2="21"/></svg>
                Eksplorasi Peta Digital
            </a>
            @guest
                <a href="{{ route('login') }}" class="btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                    Akses Dashboard Admin
                </a>
            @endguest
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="stats-grid">
            <div class="stat-card glass-panel">
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
                <div class="stat-number">{{ $totalActiveFloods }}</div>
                <div class="stat-label">Titik Banjir Aktif</div>
                <p class="stat-desc">Dalam status pantauan Waspada, Siaga, & Awas</p>
            </div>
            
            <div class="stat-card glass-panel">
                <div class="stat-icon" style="color: var(--primary-blue);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <div class="stat-number" style="color: var(--primary-blue);">
                    {{ number_format($totalAffectedPopulation) }}
                </div>
                <div class="stat-label">Estimasi Terdampak</div>
                <p class="stat-desc">Jiwa di area berstatus aktif tergenang</p>
            </div>

            <div class="stat-card glass-panel">
                <div class="stat-icon" style="color: var(--status-awas);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                </div>
                <div class="stat-number" style="font-size: 1.6rem; line-height: 2.8rem; color: #EF476F;">
                    {{ $mostAffectedRegion ? $mostAffectedRegion->name : 'N/A' }}
                </div>
                <div class="stat-label">Dampak Tertinggi</div>
                <p class="stat-desc">Dihitung berdasarkan jumlah akumulasi warga</p>
            </div>
        </div>
    </section>

    <!-- Regions Section -->
    <section class="regions-section">
        <div class="section-header">
            <div class="section-info">
                <span class="section-subtitle">Wilayah Prioritas</span>
                <h2 class="section-title">{{ count($regions) }} Wilayah Fokus Monitoring</h2>
            </div>
            <p style="color: var(--text-secondary); max-width: 450px;">Berdasarkan rekam historis cuaca ekstrem, daerah-daerah ini menjadi fokus integrasi spasial data kebencanaan di Lampung.</p>
        </div>

        <div class="regions-grid">
            @foreach($regions as $reg)
                <div class="region-card glass-panel">
                    <div class="region-meta">
                        <h3 class="region-name">{{ $reg->name }}</h3>
                        <p class="region-desc">{{ $reg->description }}</p>
                    </div>
                    <a href="{{ route('map', ['region_id' => $reg->id]) }}" class="region-link">
                        <span>Lihat Peta Wilayah</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                    </a>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Recent Updates Section -->
    <section class="updates-section">
        <div class="section-header">
            <div class="section-info">
                <span class="section-subtitle">Real-time Feed</span>
                <h2 class="section-title">Laporan & Pembaruan Terkini</h2>
            </div>
            <a href="{{ route('map') }}" class="btn-secondary" style="padding: 0.6rem 1.4rem; font-size: 0.9rem;">Lihat Semua Laporan</a>
        </div>

        <div class="timeline-list">
            @forelse($recentUpdates as $update)
                <div class="timeline-item glass-panel">
                    <div class="update-status-indicator">
                        <span class="badge-status badge-{{ strtolower($update->status) }}">{{ $update->status }}</span>
                        <span class="water-badge">{{ $update->water_level }} cm</span>
                    </div>
                    <div class="update-content">
                        <div class="update-header">
                            <h3 class="update-title">{{ $update->location_name }}</h3>
                            <span class="update-time">{{ $update->reported_at->diffForHumans() }} ({{ $update->reported_at->format('d M Y H:i') }})</span>
                        </div>
                        <p class="update-desc">{{ $update->description }}</p>
                        <div class="update-details">
                            <div class="detail-item">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polygon points="12 6 12 12 16 14"/></svg>
                                <span>Wilayah: <strong>{{ $update->region->name }}</strong></span>
                            </div>
                            <div class="detail-item">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/></svg>
                                <span>Cuaca: <strong>{{ $update->weather ?? 'N/A' }}</strong></span>
                            </div>
                            <div class="detail-item">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                                <span>Penduduk Terdampak: <strong>{{ number_format($update->affected_population) }} Jiwa</strong></span>
                            </div>
                            <div class="detail-item">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="10" r="3"/><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                <span>Koordinat: <strong>{{ $update->latitude }}, {{ $update->longitude }}</strong></span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="glass-panel" style="padding: 3rem; text-align: center; color: var(--text-secondary);">
                    Belum ada data laporan kejadian banjir terdaftar saat ini.
                </div>
            @endforelse
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="brand">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: var(--primary-yellow);"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                <span>FloodWatch<span>.</span></span>
            </div>
            <p class="footer-text">Sistem Informasi Geografis (SIG) Pemetaan Banjir Lampung terintegrasi, memberikan transparansi informasi bencana guna meningkatkan mitigasi dan keamanan sipil.</p>
            <p class="footer-copyright">&copy; {{ date('Y') }} FloodWatch Lampung. Hak Cipta Dilindungi.</p>
        </div>
    </footer>
@endsection
