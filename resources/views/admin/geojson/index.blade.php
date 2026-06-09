@extends('layouts.admin')

@section('title', 'Manajemen Layer GeoJSON — FloodWatch')

@section('content')
    <!-- Page Header -->
    <div class="admin-page-header">
        <div>
            <h1 class="admin-page-title">Manajemen Layer GeoJSON</h1>
            <p class="admin-page-subtitle">Unggah dan kelola batas spasial wilayah (GeoJSON) hasil ekspor QGIS untuk di-render di peta</p>
        </div>
    </div>

    <div class="admin-split-grid" style="grid-template-columns: 1.2fr 1.8fr; gap: 2rem;">
        
        <!-- Left Column: Upload New File -->
        <div class="panel-card glass-panel" style="align-self: flex-start;">
            <h3 class="panel-card-title">Unggah Berkas Spasial Baru</h3>
            
            <form action="{{ route('admin.geojson.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                    <!-- Layer Name -->
                    <div class="form-group">
                        <label for="name" class="form-label">Nama Identitas Layer <span style="color: var(--status-awas);">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Contoh: Batas Kota Bandar Lampung" value="{{ old('name') }}" required>
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- File input -->
                    <div class="form-group">
                        <label class="form-label">Berkas Spasial (.geojson / .json) <span style="color: var(--status-awas);">*</span></label>
                        
                        <div class="upload-dropzone" onclick="document.getElementById('geojson_file').click()">
                            <div class="upload-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                            </div>
                            <div class="upload-title">Pilih Berkas Komputer</div>
                            <p class="upload-desc">Seret berkas ke sini atau klik untuk mencari. Format harus berupa format JSON/GeoJSON yang valid (Max 10MB).</p>
                            <input type="file" name="geojson_file" id="geojson_file" style="display: none;" accept=".geojson,.json" onchange="updateFileName(this)" required>
                            <strong id="file-name-display" style="font-size: 0.85rem; color: var(--primary-blue); display: none; word-break: break-all; margin-top: 0.5rem;"></strong>
                        </div>
                        
                        @error('geojson_file')
                            <span class="text-danger" style="display: block; margin-top: 0.4rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Visual tips about integration -->
                    <div style="background: var(--primary-cream); border: 1px solid rgba(139, 111, 56, 0.15); padding: 1rem; border-radius: 10px; font-size: 0.82rem; color: var(--text-secondary); display: flex; flex-direction: column; gap: 0.4rem;">
                        <span style="font-weight: 700; color: var(--primary-blue); display: flex; align-items: center; gap: 0.3rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                            Tips Ekspor Spasial QGIS
                        </span>
                        <p style="line-height: 1.4;">
                            1. Buka Layer di <strong>QGIS Desktop</strong>.<br>
                            2. Klik kanan Layer &rarr; <strong>Export</strong> &rarr; <strong>Save Features As...</strong><br>
                            3. Pilih Format: <strong>GeoJSON</strong>.<br>
                            4. Pilih CRS: <strong>EPSG:4326 - WGS 84</strong> (sangat penting untuk keselarasan koordinat GPS Web).
                        </p>
                    </div>

                    <button type="submit" class="btn-primary" style="justify-content: center; width: 100%; border-radius: 8px; box-shadow: none;">Unggah Layer Spasial</button>
                </div>
            </form>
        </div>

        <!-- Right Column: Listing Table -->
        <div class="panel-card glass-panel">
            <h3 class="panel-card-title">Daftar Layer Terdaftar</h3>
            
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="width: 8%;">No</th>
                            <th style="width: 35%;">Nama Layer</th>
                            <th style="width: 25%;">Nama Berkas Fisik</th>
                            <th style="width: 15%;">Peta Publik</th>
                            <th style="width: 17%; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($layers as $index => $layer)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <strong style="color: var(--text-primary);">{{ $layer->name }}</strong>
                                    <span style="display: block; font-size: 0.75rem; color: var(--text-muted); margin-top: 0.1rem;">Diunggah: {{ $layer->created_at->format('d M Y H:i') }}</span>
                                </td>
                                <td style="font-family: monospace; font-size: 0.8rem; color: var(--text-secondary); word-break: break-all;">
                                    {{ $layer->filename }}
                                </td>
                                <td>
                                    <span class="badge-status badge-{{ $layer->is_active ? 'aman' : 'waspada' }}" style="font-size: 0.72rem; padding: 0.2rem 0.5rem;">
                                        {{ $layer->is_active ? 'AKTIF' : 'NONAKTIF' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-btn-group" style="justify-content: center;">
                                        <form action="{{ route('admin.geojson.toggle', $layer->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn-action btn-action-toggle" title="{{ $layer->is_active ? 'Sembunyikan dari Peta' : 'Tampilkan di Peta' }}">
                                                @if($layer->is_active)
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                                @endif
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.geojson.destroy', $layer->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus layer spasial {{ $layer->name }}? Berkas fisik akan dihapus dari server.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-action-delete" title="Hapus Layer">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 3rem; color: var(--text-secondary);">
                                    Belum ada berkas spasial GeoJSON Lampung terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script>
        // Visual helper to display selected filename in the dropzone
        function updateFileName(input) {
            const displayEl = document.getElementById('file-name-display');
            if (input.files && input.files[0]) {
                displayEl.innerText = `Terpilih: ${input.files[0].name}`;
                displayEl.style.display = 'block';
            } else {
                displayEl.style.display = 'none';
            }
        }
    </script>
@endsection
