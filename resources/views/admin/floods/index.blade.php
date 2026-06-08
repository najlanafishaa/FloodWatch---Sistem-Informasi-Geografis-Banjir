@extends('layouts.admin')

@section('title', 'Kelola Data Banjir — FloodWatch')

@section('content')
    <!-- Page Header -->
    <div class="admin-page-header">
        <div>
            <h1 class="admin-page-title">Data Titik Banjir</h1>
            <p class="admin-page-subtitle">Kelola semua marker lokasi dan kondisi banjir Provinsi Lampung</p>
        </div>
        <div>
            <a href="{{ route('admin.floods.create') }}" class="btn-primary" style="padding: 0.6rem 1.4rem; font-size: 0.9rem; border-radius: 10px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Tambah Titik Baru
            </a>
        </div>
    </div>

    <!-- Filter & Search Controls -->
    <div class="panel-card glass-panel" style="padding: 1.2rem 2rem;">
        <form action="{{ route('admin.floods.index') }}" method="GET" style="display: flex; gap: 1rem; width: 100%; align-items: center; justify-content: space-between; flex-wrap: wrap;">
            <div style="position: relative; flex-grow: 1; max-width: 500px; width: 100%;">
                <input type="text" name="search" class="form-control" style="padding-left: 2.8rem; padding-top: 0.7rem; padding-bottom: 0.7rem;" placeholder="Cari nama lokasi, deskripsi atau wilayah..." value="{{ $search }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            </div>
            <div style="display: flex; gap: 0.8rem; align-items: center;">
                @if(!empty($search))
                    <a href="{{ route('admin.floods.index') }}" class="btn-secondary" style="padding: 0.6rem 1.2rem; font-size: 0.85rem; border-radius: 8px;">Reset Pencarian</a>
                @endif
                <button type="submit" class="btn-primary" style="padding: 0.6rem 1.4rem; font-size: 0.85rem; border-radius: 8px; box-shadow: none;">Cari Data</button>
            </div>
        </form>
    </div>

    <!-- Data Table Panel -->
    <div class="panel-card glass-panel">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 20%;">Lokasi Kejadian</th>
                        <th style="width: 15%;">Wilayah Fokus</th>
                        <th style="width: 12%;">Status</th>
                        <th style="width: 10%;">Tinggi Air</th>
                        <th style="width: 18%;">Koordinat</th>
                        <th style="width: 10%;">Pelaporan</th>
                        <th style="width: 10%; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($floods as $index => $flood)
                        <tr>
                            <td>{{ $floods->firstItem() + $index }}</td>
                            <td>
                                <strong style="color: var(--text-primary);">{{ $flood->location_name }}</strong>
                                <span style="display: block; font-size: 0.8rem; color: var(--text-muted); text-overflow: ellipsis; overflow: hidden; white-space: nowrap; max-width: 250px; margin-top: 0.2rem;">{{ $flood->description ?? '-' }}</span>
                            </td>
                            <td style="font-weight: 500;">{{ $flood->region->name }}</td>
                            <td>
                                <span class="badge-status badge-{{ strtolower($flood->status) }}">{{ $flood->status }}</span>
                            </td>
                            <td style="color: var(--accent-blue); font-weight: 700;">{{ $flood->water_level }} cm</td>
                            <td style="font-family: monospace; font-size: 0.82rem; color: var(--text-secondary);">
                                {{ $flood->latitude }},<br>{{ $flood->longitude }}
                            </td>
                            <td style="font-size: 0.82rem; color: var(--text-secondary);">
                                {{ $flood->reported_at->format('d/m/Y') }}<br>
                                <span style="color: var(--text-muted);">{{ $flood->reported_at->format('H:i') }} WIB</span>
                            </td>
                            <td>
                                <div class="action-btn-group" style="justify-content: center;">
                                    <a href="{{ route('admin.floods.edit', $flood->id) }}" class="btn-action btn-action-edit" title="Edit Data">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                                    </a>
                                    
                                    <form action="{{ route('admin.floods.destroy', $flood->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data lokasi {{ $flood->location_name }} ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-action-delete" title="Hapus Data">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 3rem; color: var(--text-secondary);">
                                Belum ada data banjir terdaftar atau pencarian tidak ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Custom Styled Pagination -->
        @if($floods->hasPages())
            <div class="pagination-container">
                <span style="font-size: 0.85rem; color: var(--text-muted);">
                    Menampilkan {{ $floods->firstItem() ?? 0 }} - {{ $floods->lastItem() ?? 0 }} dari {{ $floods->total() }} Laporan
                </span>
                <div class="pagination-links">
                    {{ $floods->links() }}
                </div>
            </div>
        @endif

    </div>
@endsection
