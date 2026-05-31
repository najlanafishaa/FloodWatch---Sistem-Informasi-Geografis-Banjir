<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Flood;
use App\Models\Region;
use Carbon\Carbon;

class FetchFloodData extends Command
{
    // Nama command yang dipanggil di terminal
    protected $signature = 'flood:fetch';
    protected $description = 'Ambil data banjir real-time dari PetaBencana.id untuk wilayah Lampung';

    public function handle()
    {
        $this->info('Memulai pengambilan data banjir...');

        // 1. Ambil data dari API PetaBencana.id
        $response = Http::get('https://api.petabencana.id/homepage/reports');

        if ($response->failed()) {
            $this->error('Gagal menghubungi API PetaBencana.id');
            return;
        }

        $reports = $response->json()['result']['objects']['output']['geometries'] ?? [];
        $insertedCount = 0;

        foreach ($reports as $report) {
            $props = $report['properties'];
            $coords = $report['coordinates'];

            // 2. Filter laporan khusus untuk daerah di Lampung
            // PetaBencana biasanya menyertakan nama daerah di detail laporan
            $desc = $props['text'] ?? '';
            $title = $props['title'] ?? 'Genangan Air';
            
            // Cari tahu apakah laporan ini berlokasi di Lampung
            if (stripos($desc, 'Lampung') !== false || stripos($title, 'Lampung') !== false) {
                
                // Cari region_id yang cocok berdasarkan nama kabupaten/kota di deskripsi
                $matchedRegion = null;
                $regions = Region::all();
                foreach ($regions as $region) {
                    if (stripos($desc, $region->name) !== false || stripos($title, $region->name) !== false) {
                        $matchedRegion = $region;
                        break;
                    }
                }

                // Jika tidak ada region spesifik yang cocok, default ke Bandar Lampung (atau region general)
                $regionId = $matchedRegion ? $matchedRegion->id : Region::first()->id;

                // Tentukan status berdasarkan tinggi air (jika ada data tinggi air di API)
                $depth = $props['depth'] ?? 0;
                $status = 'Waspada';
                if ($depth > 100) $status = 'Awas';
                elseif ($depth > 50) $status = 'Siaga';
                elseif ($depth <= 0) $status = 'Aman';

                // 3. Simpan atau Update ke database local
                Flood::updateOrCreate(
                    [
                        // Unik berdasarkan koordinat untuk menghindari duplikasi
                        'latitude' => $coords[1],
                        'longitude' => $coords[0],
                    ],
                    [
                        'region_id' => $regionId,
                        'location_name' => $title,
                        'status' => $status,
                        'water_level' => $depth > 0 ? $depth : 30, // Default 30cm jika data kosong
                        'affected_population' => 150, // Estimasi awal
                        'weather' => 'Hujan Ringan',
                        'description' => $desc ?: 'Laporan banjir masuk dari sistem PetaBencana.id',
                        'reported_at' => Carbon::now(),
                    ]
                );

                $insertedCount++;
            }
        }

        $this->info("Sukses mengintegrasikan {$insertedCount} titik banjir real-time baru!");
    }
}
