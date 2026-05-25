@extends('layouts.admin')

@section('title', 'Tambah Titik Banjir Baru — FloodWatch')

@section('content')
    <!-- Page Header -->
    <div class="admin-page-header">
        <div>
            <h1 class="admin-page-title">Tambah Titik Banjir</h1>
            <p class="admin-page-subtitle">Petakan lokasi baru kejadian banjir di wilayah Lampung</p>
        </div>
        <div>
            <a href="{{ route('admin.floods.index') }}" class="btn-secondary" style="padding: 0.6rem 1.2rem; font-size: 0.88rem; border-radius: 10px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Kembali ke Daftar
            </a>
        </div>
    </div>

    <!-- Form Panel Card -->
    <div class="panel-card glass-panel" style="max-width: 900px;">
        <h3 class="panel-card-title">Formulir Laporan Titik Banjir</h3>
        
        <form action="{{ route('admin.floods.store') }}" method="POST" autocomplete="off">
            @csrf

            <div class="form-grid">
                
                <!-- Location Name -->
                <div class="form-group">
                    <label for="location_name" class="form-label">Nama Detail Lokasi <span style="color: var(--status-awas);">*</span></label>
                    <input type="text" name="location_name" id="location_name" class="form-control" placeholder="Contoh: Rajabasa (Jalan Kepayang RT 03)" value="{{ old('location_name') }}" required>
                    @error('location_name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Region Focus -->
                <div class="form-group">
                    <label for="region_id" class="form-label">Wilayah Fokus <span style="color: var(--status-awas);">*</span></label>
                    <select name="region_id" id="region_id" class="form-control" style="appearance: auto;" required>
                        <option value="">-- Pilih Wilayah Fokus --</option>
                        @foreach($regions as $region)
                            <option value="{{ $region->id }}" {{ old('region_id') == $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
                        @endforeach
                    </select>
                    @error('region_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Status Severity -->
                <div class="form-group">
                    <label for="status" class="form-label">Status Kerawanan <span style="color: var(--status-awas);">*</span></label>
                    <select name="status" id="status" class="form-control" style="appearance: auto;" required>
                        <option value="Aman" {{ old('status') == 'Aman' ? 'selected' : '' }}>AMAN (Surut / Kondusif)</option>
                        <option value="Waspada" {{ old('status') == 'Waspada' ? 'selected' : '' }}>WASPADA (Tinggi air rendah/genangan)</option>
                        <option value="Siaga" {{ old('status') == 'Siaga' ? 'selected' : '' }}>SIAGA (Tinggi air sedang/masuk pekarangan)</option>
                        <option value="Awas" {{ old('status') == 'Awas' ? 'selected' : '' }}>AWAS (Tinggi air ekstrem/perlu evakuasi)</option>
                    </select>
                    @error('status')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Water Level -->
                <div class="form-group">
                    <label for="water_level" class="form-label">Tinggi Muka Air (cm) <span style="color: var(--status-awas);">*</span></label>
                    <input type="number" name="water_level" id="water_level" class="form-control" placeholder="Tinggi air dalam centimeter, contoh: 80" value="{{ old('water_level', 0) }}" min="0" required>
                    @error('water_level')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Latitude -->
                <div class="form-group">
                    <label for="latitude" class="form-label">Latitude Koordinat (Y) <span style="color: var(--status-awas);">*</span></label>
                    <input type="number" step="any" name="latitude" id="latitude" class="form-control" placeholder="Contoh: -5.37250000" value="{{ old('latitude') }}" required>
                    @error('latitude')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Longitude -->
                <div class="form-group">
                    <label for="longitude" class="form-label">Longitude Koordinat (X) <span style="color: var(--status-awas);">*</span></label>
                    <input type="number" step="any" name="longitude" id="longitude" class="form-control" placeholder="Contoh: 105.22810000" value="{{ old('longitude') }}" required>
                    @error('longitude')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Weather -->
                <div class="form-group">
                    <label for="weather" class="form-label">Kondisi Cuaca</label>
                    <input type="text" name="weather" id="weather" class="form-control" placeholder="Contoh: Hujan Lebat, Hujan Deras, Gerimis, Mendung" value="{{ old('weather') }}">
                    @error('weather')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Affected Population -->
                <div class="form-group">
                    <label for="affected_population" class="form-label">Jumlah Penduduk Terdampak (Jiwa) <span style="color: var(--status-awas);">*</span></label>
                    <input type="number" name="affected_population" id="affected_population" class="form-control" placeholder="Contoh: 150" value="{{ old('affected_population', 0) }}" min="0" required>
                    @error('affected_population')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Reported Date/Time -->
                <div class="form-group form-group-full">
                    <label for="reported_at" class="form-label">Tanggal & Waktu Laporan Kejadian <span style="color: var(--status-awas);">*</span></label>
                    <input type="datetime-local" name="reported_at" id="reported_at" class="form-control" value="{{ old('reported_at', now()->format('Y-m-d\TH:i')) }}" required>
                    @error('reported_at')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Description details -->
                <div class="form-group form-group-full">
                    <label for="description" class="form-label">Deskripsi Kejadian & Penanganan Dampak</label>
                    <textarea name="description" id="description" class="form-control form-control-textarea" placeholder="Tuliskan detail kondisi di lapangan, tindak lanjut tim reaksi cepat BPBD, ketersediaan pengungsian, bantuan logistik, dll...">{{ old('description') }}</textarea>
                    @error('description')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="{{ route('admin.floods.index') }}" class="btn-secondary" style="border-radius: 8px; padding: 0.6rem 1.4rem;">Batal</a>
                <button type="submit" class="btn-primary" style="border-radius: 8px; padding: 0.6rem 1.6rem; box-shadow: none;">Simpan Laporan</button>
            </div>
            
        </form>
    </div>
@endsection
