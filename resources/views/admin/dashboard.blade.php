@extends('layouts.admin')

@section('title', 'Dashboard Admin — FloodWatch')

@section('content')
    <!-- Page Header -->
    <div class="admin-page-header">
        <div>
            <h1 class="admin-page-title">Ringkasan Sistem</h1>
            <p class="admin-page-subtitle">Ikhtisar data pemantauan banjir Provinsi Lampung</p>
        </div>
        <div style="display: flex; gap: 0.8rem;">
            <a href="{{ route('admin.floods.create') }}" class="btn-primary" style="padding: 0.6rem 1.4rem; font-size: 0.9rem; border-radius: 10px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Tambah Titik Banjir
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="admin-stats-grid">
        <!-- Card Total Regions -->
        <div class="admin-stat-card glass-panel">
            <div class="admin-stat-val" style="color: var(--accent-indigo);">{{ $totalRegions }}</div>
            <div class="admin-stat-label">Wilayah Fokus</div>
        </div>
        
        <!-- Card Total Incidents -->
        <div class="admin-stat-card glass-panel">
            <div class="admin-stat-val" style="color: var(--accent-blue);">{{ $totalFloods }}</div>
            <div class="admin-stat-label">Total Titik Banjir</div>
        </div>

        <!-- Card Active Warnings -->
        <div class="admin-stat-card glass-panel" style="border-color: hsla(24, 95%, 53%, 0.3);">
            <div class="admin-stat-val" style="color: var(--status-siaga);">{{ $activeAlerts }}</div>
            <div class="admin-stat-label">Titik Berstatus Aktif</div>
        </div>

        <!-- Card GeoJSON overlays -->
        <div class="admin-stat-card glass-panel">
            <div class="admin-stat-val" style="color: var(--accent-teal);">{{ $activeGeojsonLayers }}</div>
            <div class="admin-stat-label">Layer GeoJSON Aktif</div>
        </div>
    </div>

    <!-- Split Grid: Breakdown & Recent Activity -->
    <div class="admin-split-grid">
        
        <!-- Left: Status Breakdown -->
        <div style="display: flex; flex-direction: column; gap: 2rem;">
            <!-- Severity breakdown -->
            <div class="panel-card glass-panel">
                <h3 class="panel-card-title">Distribusi Status Kerawanan</h3>
                
                <div style="display: flex; flex-direction: column; gap: 1.2rem; margin-top: 0.5rem;">
                    <!-- Awas -->
                    <div>
                        <div style="display: flex; justify-content: space-between; font-size: 0.85rem; margin-bottom: 0.4rem;">
                            <span style="font-weight: 600; color: var(--status-awas);">AWAS (Tinggi)</span>
                            <span style="font-weight: 700;">{{ $statusBreakdown['Awas'] }} Titik</span>
                        </div>
                        <div style="background: hsla(0, 0%, 100%, 0.05); height: 8px; border-radius: 4px; overflow: hidden;">
                            <div style="background: var(--status-awas); height: 100%; width: {{ $totalFloods > 0 ? ($statusBreakdown['Awas'] / $totalFloods) * 100 : 0 }}%; border-radius: 4px; box-shadow: 0 0 10px var(--status-awas);"></div>
                        </div>
                    </div>

                    <!-- Siaga -->
                    <div>
                        <div style="display: flex; justify-content: space-between; font-size: 0.85rem; margin-bottom: 0.4rem;">
                            <span style="font-weight: 600; color: var(--status-siaga);">SIAGA (Sedang)</span>
                            <span style="font-weight: 700;">{{ $statusBreakdown['Siaga'] }} Titik</span>
                        </div>
                        <div style="background: hsla(0, 0%, 100%, 0.05); height: 8px; border-radius: 4px; overflow: hidden;">
                            <div style="background: var(--status-siaga); height: 100%; width: {{ $totalFloods > 0 ? ($statusBreakdown['Siaga'] / $totalFloods) * 100 : 0 }}%; border-radius: 4px; box-shadow: 0 0 10px var(--status-siaga);"></div>
                        </div>
                    </div>

                    <!-- Waspada -->
                    <div>
                        <div style="display: flex; justify-content: space-between; font-size: 0.85rem; margin-bottom: 0.4rem;">
                            <span style="font-weight: 600; color: var(--status-waspada);">WASPADA (Rendah)</span>
                            <span style="font-weight: 700;">{{ $statusBreakdown['Waspada'] }} Titik</span>
                        </div>
                        <div style="background: hsla(0, 0%, 100%, 0.05); height: 8px; border-radius: 4px; overflow: hidden;">
                            <div style="background: var(--status-waspada); height: 100%; width: {{ $totalFloods > 0 ? ($statusBreakdown['Waspada'] / $totalFloods) * 100 : 0 }}%; border-radius: 4px; box-shadow: 0 0 10px var(--status-waspada);"></div>
                        </div>
                    </div>

                    <!-- Aman -->
                    <div>
                        <div style="display: flex; justify-content: space-between; font-size: 0.85rem; margin-bottom: 0.4rem;">
                            <span style="font-weight: 600; color: var(--status-aman);">AMAN (Kondusif)</span>
                            <span style="font-weight: 700;">{{ $statusBreakdown['Aman'] }} Titik</span>
                        </div>
                        <div style="background: hsla(0, 0%, 100%, 0.05); height: 8px; border-radius: 4px; overflow: hidden;">
                            <div style="background: var(--status-aman); height: 100%; width: {{ $totalFloods > 0 ? ($statusBreakdown['Aman'] / $totalFloods) * 100 : 0 }}%; border-radius: 4px; box-shadow: 0 0 10px var(--status-aman);"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Regions Count -->
            <div class="panel-card glass-panel">
                <h3 class="panel-card-title">Sebaran Titik Per Wilayah</h3>
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Wilayah Fokus</th>
                                <th style="text-align: right;">Jumlah Laporan Banjir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($regions as $r)
                                <tr>
                                    <td style="font-weight: 600;">{{ $r->name }}</td>
                                    <td style="text-align: right; font-weight: 700; color: var(--accent-teal);">{{ $r->floods_count }} Titik</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right: Recent Activity -->
        <div class="panel-card glass-panel">
            <h3 class="panel-card-title">Pembaruan Aktivitas Terkini</h3>
            
            <div style="display: flex; flex-direction: column; gap: 1.2rem; margin-top: 0.5rem;">
                @forelse($recentFloods as $activity)
                    <div style="border-bottom: 1px solid var(--border-glass); padding-bottom: 1rem; display: flex; flex-direction: column; gap: 0.4rem;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 0.5rem; flex-wrap: wrap;">
                            <strong style="color: var(--text-primary); font-size: 0.95rem;">{{ $activity->location_name }}</strong>
                            <span class="badge-status badge-{{ strtolower($activity->status) }}" style="font-size: 0.65rem; padding: 0.15rem 0.4rem;">{{ $activity->status }}</span>
                        </div>
                        <div style="font-size: 0.8rem; color: var(--accent-teal); font-weight: 500;">
                            {{ $activity->region->name }} &bull; {{ $activity->water_level }} cm
                        </div>
                        <p style="font-size: 0.82rem; color: var(--text-secondary); line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ $activity->description }}
                        </p>
                        <span style="font-size: 0.75rem; color: var(--text-muted); align-self: flex-end; margin-top: 0.2rem;">
                            {{ $activity->updated_at->diffForHumans() }}
                        </span>
                    </div>
                @empty
                    <div style="padding: 3rem; text-align: center; color: var(--text-muted); font-size: 0.88rem;">
                        Belum ada aktivitas terdaftar.
                    </div>
                @endforelse
            </div>
        </div>

    </div>
@endsection
