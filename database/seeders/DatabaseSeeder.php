<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Region;
use App\Models\Flood;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Default Admin
        User::create([
            'name' => 'Admin FloodWatch',
            'email' => 'admin@floodwatch.id',
            'password' => Hash::make('admin123'),
        ]);

        // 2. Create Regions
        $bandarLampung = Region::create([
            'name' => 'Bandar Lampung',
            'description' => 'Ibu kota Provinsi Lampung yang padat penduduk dengan tingkat kerawanan banjir tinggi akibat sistem drainase perkotaan dan luapan sungai.'
        ]);

        $metro = Region::create([
            'name' => 'Metro',
            'description' => 'Kota administratif yang dikelilingi daerah persawahan dan aliran irigasi, rawan banjir luapan irigasi saat curah hujan tinggi.'
        ]);

        $lampungSelatan = Region::create([
            'name' => 'Lampung Selatan',
            'description' => 'Kabupaten pesisir dengan potensi banjir rob (pasang laut) serta banjir bandang dari area pegunungan dan perbukitan.'
        ]);

        $pesawaran = Region::create([
            'name' => 'Pesawaran',
            'description' => 'Wilayah kabupaten yang dilintasi beberapa sungai besar, sering terdampak banjir luapan sungai Way Sekampung dan genangan di wilayah dataran rendah.'
        ]);

        // 3. Create Simulated Flood Incidents
        // Bandar Lampung Floods
        Flood::create([
            'region_id' => $bandarLampung->id,
            'location_name' => 'Rajabasa (Perumahan Ragom Gawi)',
            'latitude' => -5.37250000,
            'longitude' => 105.22810000,
            'status' => 'Siaga',
            'water_level' => 80,
            'affected_population' => 450,
            'weather' => 'Hujan Lebat',
            'description' => 'Luapan sungai Rajabasa menggenangi perumahan warga Ragom Gawi setinggi lutut orang dewasa. Petugas BPBD telah disiagakan di lokasi dengan perahu karet.',
            'reported_at' => now()->subHours(2),
        ]);

        Flood::create([
            'region_id' => $bandarLampung->id,
            'location_name' => 'Kedamaian (Bantaran Kali Balau)',
            'latitude' => -5.41240000,
            'longitude' => 105.28120000,
            'status' => 'Awas',
            'water_level' => 130,
            'affected_population' => 850,
            'weather' => 'Hujan Deras',
            'description' => 'Debit air sungai Kali Balau meluap ekstrem melebihi tanggul penahan. Air memasuki rumah warga hingga setinggi dada. Evakuasi darurat sedang berlangsung untuk kelompok lansia dan anak-anak.',
            'reported_at' => now()->subHours(4),
        ]);

        Flood::create([
            'region_id' => $bandarLampung->id,
            'location_name' => 'Panjang (Kampung Teluk Harapan)',
            'latitude' => -5.47450000,
            'longitude' => 105.31820000,
            'status' => 'Waspada',
            'water_level' => 40,
            'affected_population' => 120,
            'weather' => 'Hujan Ringan',
            'description' => 'Genangan air pasang laut (rob) bercampur limpasan air hujan di daerah pesisir Kampung Teluk Harapan. Air menggenangi jalan lingkungan namun belum banyak masuk ke dalam rumah panggung warga.',
            'reported_at' => now()->subHours(1),
        ]);

        Flood::create([
            'region_id' => $bandarLampung->id,
            'location_name' => 'Teluk Betung Selatan (Kali Belik)',
            'latitude' => -5.44520000,
            'longitude' => 105.25740000,
            'status' => 'Aman',
            'water_level' => 10,
            'affected_population' => 0,
            'weather' => 'Cerah Berawan',
            'description' => 'Genangan air akibat luapan parit pada malam hari telah surut sepenuhnya. Warga bergotong royong membersihkan sisa lumpur dan sampah tersumbat. Kondisi kondusif.',
            'reported_at' => now()->subHours(12),
        ]);

        // Metro Floods
        Flood::create([
            'region_id' => $metro->id,
            'location_name' => 'Metro Timur (Jalan Kamboja)',
            'latitude' => -5.12120000,
            'longitude' => 105.31950000,
            'status' => 'Waspada',
            'water_level' => 30,
            'affected_population' => 50,
            'weather' => 'Mendung',
            'description' => 'Saluran irigasi persawahan meluap akibat tersumbat tumpukan jerami dan sampah. Air membasahi badan jalan kamboja setinggi semata kaki, arus lalu lintas kendaraan roda dua diimbau berhati-hati.',
            'reported_at' => now()->subHours(3),
        ]);

        Flood::create([
            'region_id' => $metro->id,
            'location_name' => 'Metro Pusat (Hadimulyo Barat)',
            'latitude' => -5.11180000,
            'longitude' => 105.30150000,
            'status' => 'Aman',
            'water_level' => 0,
            'affected_population' => 0,
            'weather' => 'Cerah',
            'description' => 'Saluran drainase perkotaan berjalan lancar. Debit air sungai utama terpantau di bawah batas aman. Tidak ada indikasi genangan air.',
            'reported_at' => now()->subHours(24),
        ]);

        // Lampung Selatan Floods
        Flood::create([
            'region_id' => $lampungSelatan->id,
            'location_name' => 'Kalianda (Desa Canggu)',
            'latitude' => -5.73140000,
            'longitude' => 105.59220000,
            'status' => 'Siaga',
            'water_level' => 70,
            'affected_population' => 320,
            'weather' => 'Hujan Deras',
            'description' => 'Aliran Sungai Way Canggu di Kalianda meluap cepat ke pemukiman di dataran rendah akibat curah hujan tinggi di wilayah lereng Gunung Rajabasa. Tinggi air mencapai 70cm di halaman rumah warga.',
            'reported_at' => now()->subHours(5),
        ]);

        Flood::create([
            'region_id' => $lampungSelatan->id,
            'location_name' => 'Natar (Jembatan Dusun Indah)',
            'latitude' => -5.29740000,
            'longitude' => 105.18240000,
            'status' => 'Awas',
            'water_level' => 150,
            'affected_population' => 1200,
            'weather' => 'Hujan Lebat',
            'description' => 'Banjir bandang kiriman dari hulu sungai Way Sekampung menggenangi jalan lintas Sumatera (Jalinsum) dekat perbatasan Natar. Arus air sangat deras setinggi 1,5 meter merendam puluhan rumah. Jalur transportasi dialihkan sementara.',
            'reported_at' => now()->subHours(6),
        ]);

        // Pesawaran Floods
        Flood::create([
            'region_id' => $pesawaran->id,
            'location_name' => 'Gedong Tataan (Desa Dusun Baru)',
            'latitude' => -5.37340000,
            'longitude' => 105.07920000,
            'status' => 'Siaga',
            'water_level' => 90,
            'affected_population' => 600,
            'weather' => 'Hujan Lebat',
            'description' => 'Luapan Sungai Way Sekampung bagian hilir merendam wilayah pemukiman padat di Desa Dusun Baru, Gedong Tataan. Ketinggian air merangkak naik sejak dini hari. Posko pengungsian di balai desa telah diaktifkan.',
            'reported_at' => now()->subHours(3),
        ]);

        Flood::create([
            'region_id' => $pesawaran->id,
            'location_name' => 'Padang Cermin (Kecamatan Padang Cermin)',
            'latitude' => -5.61240000,
            'longitude' => 105.12340000,
            'status' => 'Waspada',
            'water_level' => 50,
            'affected_population' => 180,
            'weather' => 'Gerimis',
            'description' => 'Hujan sedang yang merata memicu banjir genangan di kawasan pertanian sawah dan jalan penghubung antar desa. Aktivitas warga terhambat namun belum ada pengungsian skala besar.',
            'reported_at' => now()->subHours(2),
        ]);
    }
}
