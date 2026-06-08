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
            'role' => 'admin',
        ]);

        // 2. Create All 15 Regions of Lampung Province
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

        $lampungTimur = Region::create([
            'name' => 'Lampung Timur',
            'description' => 'Kabupaten dataran rendah sebelah timur Lampung yang dilalui banyak sungai besar, rawan luapan Way Sekampung dan genangan sawah.'
        ]);

        $lampungTengah = Region::create([
            'name' => 'Lampung Tengah',
            'description' => 'Kabupaten terluas di Lampung dengan aliran sungai Way Terusan dan Way Pengubuan, rawan luapan daerah persawahan dan perkebunan.'
        ]);

        $lampungUtara = Region::create([
            'name' => 'Lampung Utara',
            'description' => 'Wilayah dataran tinggi berbukit, dilalui Way Rarem dan Way Abung yang berpotensi banjir luapan bandang saat curah hujan hulu tinggi.'
        ]);

        $lampungBarat = Region::create([
            'name' => 'Lampung Barat',
            'description' => 'Kawasan berbukit/pegunungan (TNBBS), memiliki risiko banjir bandang dari lereng bukit dan tanah longsor yang menutup jalan arteri.'
        ]);

        $pringsewu = Region::create([
            'name' => 'Pringsewu',
            'description' => 'Kabupaten perkotaan/pertanian dataran rendah, rawan luapan sungai Way Sekampung hilir dan sistem irigasi teknis bendungan.'
        ]);

        $tanggamus = Region::create([
            'name' => 'Tanggamus',
            'description' => 'Kabupaten pesisir teluk dan pegunungan, rawan banjir bandang luapan sungai Way Semaka serta banjir rob pesisir pantai.'
        ]);

        $tulangBawang = Region::create([
            'name' => 'Tulang Bawang',
            'description' => 'Wilayah dataran rendah rawa, dialiri Way Tulang Bawang yang lebar, sering mengalami banjir musiman luapan sungai yang merendam pemukiman terapung.'
        ]);

        $tulangBawangBarat = Region::create([
            'name' => 'Tulang Bawang Barat',
            'description' => 'Kawasan perkebunan bergelombang rendah dengan sungai Way Kiri dan Way Kanan, rawan genangan musiman.'
        ]);

        $wayKanan = Region::create([
            'name' => 'Way Kanan',
            'description' => 'Daerah berbukit dialiri banyak sungai Way Umpu, Way Besai, Way Kanan, rawan banjir kiriman bandang dari pegunungan hulu.'
        ]);

        $mesuji = Region::create([
            'name' => 'Mesuji',
            'description' => 'Kabupaten perbatasan timur laut Lampung berawa-rawa, rawan banjir genangan luapan sungai Way Mesuji yang berlangsung lama.'
        ]);

        $pesisirBarat = Region::create([
            'name' => 'Pesisir Barat',
            'description' => 'Daerah pesisir samudera Hindia terluar Lampung dengan curah hujan tinggi, rawan banjir rob laut dan luapan aliran sungai pegunungan.'
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

        // Lampung Timur Floods
        Flood::create([
            'region_id' => $lampungTimur->id,
            'location_name' => 'Labuhan Maringgai (Desa Muara Gading Mas)',
            'latitude' => -5.31000000,
            'longitude' => 105.80000000,
            'status' => 'Waspada',
            'water_level' => 60,
            'affected_population' => 240,
            'weather' => 'Hujan Ringan',
            'description' => 'Kombinasi banjir rob laut pasang dengan luapan sungai merendam desa nelayan di Labuhan Maringgai setinggi lutut kaki.',
            'reported_at' => now()->subHours(3),
        ]);

        // Tanggamus Floods
        Flood::create([
            'region_id' => $tanggamus->id,
            'location_name' => 'Semaka (Pemukiman Way Semaka)',
            'latitude' => -5.51000000,
            'longitude' => 104.53000000,
            'status' => 'Awas',
            'water_level' => 140,
            'affected_population' => 950,
            'weather' => 'Hujan Lebat',
            'description' => 'Luapan Sungai Way Semaka merendam pemukiman warga di Kecamatan Semaka hingga ketinggian 1,4 meter. Tim SAR gabungan bersiap mengevakuasi korban.',
            'reported_at' => now()->subHours(4),
        ]);

        // Tulang Bawang Floods
        Flood::create([
            'region_id' => $tulangBawang->id,
            'location_name' => 'Menggala (Bantaran Way Tulang Bawang)',
            'latitude' => -4.48000000,
            'longitude' => 105.25000000,
            'status' => 'Siaga',
            'water_level' => 100,
            'affected_population' => 520,
            'weather' => 'Hujan Sedang',
            'description' => 'Luapan Sungai Way Tulang Bawang merendam kawasan pemukiman pinggir sungai di Menggala. Akses dermaga tradisional terganggu genangan air setinggi 1 meter.',
            'reported_at' => now()->subHours(5),
        ]);

        // Pesisir Barat Floods
        Flood::create([
            'region_id' => $pesisirBarat->id,
            'location_name' => 'Krui (Jalur Pantai Labuhan Jukung)',
            'latitude' => -5.19000000,
            'longitude' => 103.93000000,
            'status' => 'Aman',
            'water_level' => 5,
            'affected_population' => 0,
            'weather' => 'Cerah Berawan',
            'description' => 'Pascagenangan akibat pasang air laut di Krui, air dilaporkan telah surut sepenuhnya dan jalan lintas barat Sumatera kembali aman dilalui kendaraan.',
            'reported_at' => now()->subHours(10),
        ]);
    }
}
