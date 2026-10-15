<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Destination;
use App\Models\Trail;
use Illuminate\Database\Seeder;

class UrbanRallySeeder extends Seeder
{
    /**
     * Run the database seeds for Urban Rally trails & transit checkpoints.
     */
    public function run(): void
    {
        $jakarta = City::where('slug', 'jakarta')->first();
        $jogja = City::where('slug', 'yogyakarta')->first();
        $bandung = City::where('slug', 'bandung')->first();

        // Fallbacks if cities don't exist yet
        if (! $jakarta) {
            $jakarta = City::create([
                'name' => 'DKI Jakarta',
                'slug' => 'jakarta',
                'latitude' => -6.20880000,
                'longitude' => 106.84560000,
            ]);
        }
        if (! $jogja) {
            $jogja = City::create([
                'name' => 'DI Yogyakarta',
                'slug' => 'yogyakarta',
                'latitude' => -7.79560000,
                'longitude' => 110.36950000,
            ]);
        }
        if (! $bandung) {
            $bandung = City::create([
                'name' => 'Kota Bandung',
                'slug' => 'bandung',
                'latitude' => -6.91750000,
                'longitude' => 107.61910000,
            ]);
        }

        $monas = Destination::where('slug', 'monumen-nasional-monas')->first();
        $kotaTua = Destination::where('slug', 'kawasan-kota-tua-jakarta')->first();
        $prambanan = Destination::where('slug', 'candi-prambanan')->first();
        $gedungSate = Destination::where('slug', 'gedung-sate-bandung')->first();

        // -------------------------------------------------------------------
        // 1. Trail Jakarta: Jelajah Monas via MRT & TransJakarta (Recommended)
        // -------------------------------------------------------------------
        $trailMonas = Trail::updateOrCreate(
            ['slug' => 'jelajah-monas-via-mrt-transjakarta'],
            [
                'city_id' => $jakarta->id,
                'destination_id' => $monas?->id,
                'title' => 'Jelajah Monas via MRT & TransJakarta Koridor 1',
                'transport_modes' => ['mrt', 'transjakarta', 'walking'],
                'is_recommended' => true,
                'recommendation_reason' => 'Menuju Monas: Paling efektif naik MRT ke Bundaran HI, lanjut TransJakarta Koridor 1 lalu jalan kaki 3 menit ke gerbang.',
                'estimated_time' => '35 Menit',
                'total_distance' => '4.8 km',
                'estimated_cost' => 'Rp 7.000',
                'description' => 'Kombinasi rute transit primadona Jakarta yang memadukan kenyamanan subway MRT bawah tanah dengan ketepatan bus rapid transit TransJakarta di jalur steril. Bebas kemacetan jam sibuk dan sangat ramah pedestrian.',
            ]
        );

        $trailMonas->checkpoints()->delete();
        $trailMonas->checkpoints()->createMany([
            [
                'step_order' => 1,
                'mode' => 'mrt',
                'stop_name' => 'Stasiun MRT Senayan / Bundaran HI',
                'instruction' => 'Tap-in kartu multi-trip atau e-money di gate MRT, naik kereta arah Bundaran HI (peron utara).',
                'duration_minutes' => 7,
                'latitude' => -6.19312500,
                'longitude' => 106.82343800,
            ],
            [
                'step_order' => 2,
                'mode' => 'walking',
                'stop_name' => 'Skybridge Integrasi Bundaran HI',
                'instruction' => 'Turun di Stasiun Bundaran HI, lewati lorong concourse lalu naik eskalator langsung menuju halte integrasi TransJakarta.',
                'duration_minutes' => 3,
                'latitude' => -6.19250000,
                'longitude' => 106.82360000,
            ],
            [
                'step_order' => 3,
                'mode' => 'transjakarta',
                'stop_name' => 'Halte TransJakarta Bundaran HI Astra',
                'instruction' => 'Tap-in di Halte TJ Bundaran HI, naik Bus Koridor 1 jurusan Kota / Kali Besar.',
                'duration_minutes' => 14,
                'latitude' => -6.18200000,
                'longitude' => 106.82500000,
            ],
            [
                'step_order' => 4,
                'mode' => 'transjakarta',
                'stop_name' => 'Halte TransJakarta Monas',
                'instruction' => 'Turun di Halte Monas, tap-out di gate keluar sayap barat Daya.',
                'duration_minutes' => 3,
                'latitude' => -6.17540000,
                'longitude' => 106.82700000,
            ],
            [
                'step_order' => 5,
                'mode' => 'walking',
                'stop_name' => 'Pelataran Barat Cawan Monas',
                'instruction' => 'Jalan kaki santai 200m menyusuri jalur pedestrian teduh menuju loket cawan & museum bawah tanah Monas.',
                'duration_minutes' => 5,
                'latitude' => -6.17539240,
                'longitude' => 106.82715280,
            ],
        ]);

        // -------------------------------------------------------------------
        // 2. Trail Jakarta: Heritage Walk Kota Tua via KRL & Jalur Pedestrian
        // -------------------------------------------------------------------
        $trailKotaTua = Trail::updateOrCreate(
            ['slug' => 'heritage-walk-kota-tua-via-krl'],
            [
                'city_id' => $jakarta->id,
                'destination_id' => $kotaTua?->id,
                'title' => 'Heritage Walk Kota Tua via KRL & Jalur Pedestrian',
                'transport_modes' => ['krl', 'walking'],
                'is_recommended' => true,
                'recommendation_reason' => 'Menuju Kota Tua: Naik KRL Commuter Line langsung turun di Stasiun Beos tanpa hambatan lampu merah, lanjut underpass pedestrian.',
                'estimated_time' => '28 Menit',
                'total_distance' => '7.2 km',
                'estimated_cost' => 'Rp 4.000',
                'description' => 'Rute klasik paling hemat dan bersejarah. Mengantarkan Anda langsung dari pusat kota ke gerbang Batavia kuno tanpa risiko terjebak kemacetan lalu lintas Jakarta Utara.',
            ]
        );

        $trailKotaTua->checkpoints()->delete();
        $trailKotaTua->checkpoints()->createMany([
            [
                'step_order' => 1,
                'mode' => 'krl',
                'stop_name' => 'Stasiun KRL Manggarai / Juanda',
                'instruction' => 'Tap-in di peron KRL arah Jakarta Kota (Jalur Elevated Blue Line).',
                'duration_minutes' => 5,
                'latitude' => -6.17000000,
                'longitude' => 106.83000000,
            ],
            [
                'step_order' => 2,
                'mode' => 'krl',
                'stop_name' => 'Stasiun Jakarta Kota (Beos)',
                'instruction' => 'Turun di stasiun akhir Jakarta Kota (Stasiun Beos cagar budaya 1929). Tap-out di pintu keluar barat.',
                'duration_minutes' => 16,
                'latitude' => -6.13760000,
                'longitude' => 106.81450000,
            ],
            [
                'step_order' => 3,
                'mode' => 'walking',
                'stop_name' => 'Terowongan Penyeberangan Bawah Tanah (Underpass)',
                'instruction' => 'Masuk terowongan pedestrian bawah tanah yang menghubungkan Stasiun Kota dengan Halte Museum Fatahillah.',
                'duration_minutes' => 3,
                'latitude' => -6.13600000,
                'longitude' => 106.81380000,
            ],
            [
                'step_order' => 4,
                'mode' => 'walking',
                'stop_name' => 'Plaza Museum Fatahillah',
                'instruction' => 'Tiba di kawasan bebas kendaraan bermotor Taman Fatahillah. Anda siap menyewa sepeda ontel atau mengunjungi Museum Wayang.',
                'duration_minutes' => 4,
                'latitude' => -6.13520000,
                'longitude' => 106.81330000,
            ],
        ]);

        // -------------------------------------------------------------------
        // 3. Trail Jakarta: GBK Senayan via MRT & Bus Feeder
        // -------------------------------------------------------------------
        $trailGBK = Trail::updateOrCreate(
            ['slug' => 'sport-chill-gbk-senayan-via-mrt-feeder'],
            [
                'city_id' => $jakarta->id,
                'destination_id' => null,
                'title' => 'Sport & Chill GBK Senayan via MRT & Bus Feeder',
                'transport_modes' => ['mrt', 'bus', 'walking'],
                'is_recommended' => false,
                'recommendation_reason' => 'Pilihan akses santai ke Hutan Kota GBK saat Car Free Day atau weekend.',
                'estimated_time' => '24 Menit',
                'total_distance' => '3.5 km',
                'estimated_cost' => 'Rp 8.500',
                'description' => 'Konektivitas modern menyusuri kawasan SCBD dan Kompleks Gelora Bung Karno. Sangat ideal untuk jogging sore atau piknik di taman kota.',
            ]
        );

        $trailGBK->checkpoints()->delete();
        $trailGBK->checkpoints()->createMany([
            [
                'step_order' => 1,
                'mode' => 'mrt',
                'stop_name' => 'Stasiun MRT Dukuh Atas BNI',
                'instruction' => 'Naik MRT Jakarta arah Lebak Bulus Grab dari peron 2.',
                'duration_minutes' => 6,
                'latitude' => -6.20080000,
                'longitude' => 106.82270000,
            ],
            [
                'step_order' => 2,
                'mode' => 'mrt',
                'stop_name' => 'Stasiun MRT Istora Mandiri',
                'instruction' => 'Turun di Stasiun Istora Mandiri. Ambil Pintu Keluar B ke arah Gate 7 GBK.',
                'duration_minutes' => 6,
                'latitude' => -6.22290000,
                'longitude' => 106.80800000,
            ],
            [
                'step_order' => 3,
                'mode' => 'bus',
                'stop_name' => 'Halte Bus Feeder Senayan FX',
                'instruction' => 'Naik shuttle bus listrik keliling GBK menuju pintu lingkar barat.',
                'duration_minutes' => 7,
                'latitude' => -6.22150000,
                'longitude' => 106.80400000,
            ],
            [
                'step_order' => 4,
                'mode' => 'walking',
                'stop_name' => 'Plaza Timur & Hutan Kota GBK',
                'instruction' => 'Jalan kaki 150m melintasi jogging track karet teduh menuju area rumput terbuka Hutan Kota GBK.',
                'duration_minutes' => 5,
                'latitude' => -6.21850000,
                'longitude' => 106.80250000,
            ],
        ]);

        // -------------------------------------------------------------------
        // 4. Trail Jakarta: Koridor Terintegrasi LRT Jabodebek + TransJakarta
        // -------------------------------------------------------------------
        $trailLRT = Trail::updateOrCreate(
            ['slug' => 'lintas-koridor-bisnis-lrt-tj'],
            [
                'city_id' => $jakarta->id,
                'destination_id' => null,
                'title' => 'Lintas Segitiga Emas via LRT Jabodebek & TJ 9',
                'transport_modes' => ['lrt', 'transjakarta', 'walking'],
                'is_recommended' => false,
                'recommendation_reason' => 'Rute transit elevated LRT modern melintasi Rasuna Said & Gatot Subroto.',
                'estimated_time' => '38 Menit',
                'total_distance' => '8.0 km',
                'estimated_cost' => 'Rp 10.500',
                'description' => 'Pengalaman menaiki armada LRT tanpa masinis (driverless) Jabodebek melintasi gedung pencakar langit Rasuna Said dengan integrasi langsung halte TransJakarta Koridor 9.',
            ]
        );

        $trailLRT->checkpoints()->delete();
        $trailLRT->checkpoints()->createMany([
            [
                'step_order' => 1,
                'mode' => 'lrt',
                'stop_name' => 'Stasiun LRT Cawang',
                'instruction' => 'Tap-in di Stasiun LRT Cawang, naik kereta LRT line Cibubur/Bekasi menuju Dukuh Atas.',
                'duration_minutes' => 12,
                'latitude' => -6.24290000,
                'longitude' => 106.86600000,
            ],
            [
                'step_order' => 2,
                'mode' => 'lrt',
                'stop_name' => 'Stasiun LRT Rasuna Said',
                'instruction' => 'Turun di Stasiun Rasuna Said, lewati jembatan skybridge integrasi.',
                'duration_minutes' => 4,
                'latitude' => -6.21800000,
                'longitude' => 106.83200000,
            ],
            [
                'step_order' => 3,
                'mode' => 'transjakarta',
                'stop_name' => 'Halte TransJakarta Kuningan Madya',
                'instruction' => 'Naik TransJakarta Koridor 6 arah Ragunan atau Dukuh Atas.',
                'duration_minutes' => 15,
                'latitude' => -6.21950000,
                'longitude' => 106.83350000,
            ],
            [
                'step_order' => 4,
                'mode' => 'walking',
                'stop_name' => 'Trotoar Lebar HR Rasuna Said',
                'instruction' => 'Jalan santai di trotoar pedestrian pohon rindang ke sentra kuliner Pasar Festival.',
                'duration_minutes' => 7,
                'latitude' => -6.22050000,
                'longitude' => 106.83500000,
            ],
        ]);

        // -------------------------------------------------------------------
        // 5. Trail Yogyakarta: Candi Prambanan via KRL Jogja-Solo (Recommended)
        // -------------------------------------------------------------------
        $trailPrambanan = Trail::updateOrCreate(
            ['slug' => 'napak-prambanan-via-krl-jogja-solo'],
            [
                'city_id' => $jogja->id,
                'destination_id' => $prambanan?->id,
                'title' => 'Napak Candi Prambanan via KRL Jogja-Solo & Jalan Rindang',
                'transport_modes' => ['krl', 'walking'],
                'is_recommended' => true,
                'recommendation_reason' => 'Menuju Candi Prambanan: Naik KRL Commuter Line dari Stasiun Tugu, turun di Brambanan lalu jalan santai 800m bebas debu jalan raya.',
                'estimated_time' => '32 Menit',
                'total_distance' => '16.5 km',
                'estimated_cost' => 'Rp 8.000',
                'description' => 'Alternatif terbaik menuju kompleks Candi Prambanan menghindari macetnya Jalan Raya Solo-Yogya. Nyaman ber-AC dengan tarif KRL bersubsidi.',
            ]
        );

        $trailPrambanan->checkpoints()->delete();
        $trailPrambanan->checkpoints()->createMany([
            [
                'step_order' => 1,
                'mode' => 'krl',
                'stop_name' => 'Stasiun Tugu Yogyakarta (YK)',
                'instruction' => 'Tap-in kartu KMT / GoTransit di pintu timur Stasiun Tugu, naik KRL tujuan Solo Palur.',
                'duration_minutes' => 5,
                'latitude' => -7.78920000,
                'longitude' => 110.36380000,
            ],
            [
                'step_order' => 2,
                'mode' => 'krl',
                'stop_name' => 'Stasiun Brambanan (BBN)',
                'instruction' => 'Menikmati perjalanan 20 menit melewati persawahan Kalasan, lalu turun di Stasiun Brambanan.',
                'duration_minutes' => 20,
                'latitude' => -7.75620000,
                'longitude' => 110.49120000,
            ],
            [
                'step_order' => 3,
                'mode' => 'walking',
                'stop_name' => 'Jalur Pedestrian Rindang Jl. Raya Prambanan',
                'instruction' => 'Keluar stasiun ke arah utara, ikuti trotoar berkanopi pohon trembesi sejauh 700 meter.',
                'duration_minutes' => 7,
                'latitude' => -7.75400000,
                'longitude' => 110.49140000,
            ],
            [
                'step_order' => 4,
                'mode' => 'walking',
                'stop_name' => 'Gerbang Tiket Utama Candi Prambanan',
                'instruction' => 'Tiba di loket resmi taman wisata Candi Prambanan & pelataran Candi Sewu.',
                'duration_minutes' => 3,
                'latitude' => -7.75200000,
                'longitude' => 110.49150000,
            ],
        ]);

        // -------------------------------------------------------------------
        // 6. Trail Bandung: Heritage Tour Gedung Sate via Trans Metro (Recommended)
        // -------------------------------------------------------------------
        $trailBandung = Trail::updateOrCreate(
            ['slug' => 'jelajah-heritage-bandung-trans-metro-walking'],
            [
                'city_id' => $bandung->id,
                'destination_id' => $gedungSate?->id,
                'title' => 'Jelajah Heritage Bandung via Bus Trans Metro & Trotoar Asam',
                'transport_modes' => ['bus', 'walking'],
                'is_recommended' => true,
                'recommendation_reason' => 'Menuju Gedung Sate: Paling sejuk naik Trans Metro Pasundan Koridor 2 turun di Banda, lanjut jalan kaki di bawah rindangnya pohon asam.',
                'estimated_time' => '30 Menit',
                'total_distance' => '4.2 km',
                'estimated_cost' => 'Rp 4.900',
                'description' => 'Nikmati udara sejuk kota kembang dengan bus kota ramah lingkungan melintasi kawasan cagar budaya heritage kolonial dari Alun-alun menuju mahkota Gedung Sate.',
            ]
        );

        $trailBandung->checkpoints()->delete();
        $trailBandung->checkpoints()->createMany([
            [
                'step_order' => 1,
                'mode' => 'bus',
                'stop_name' => 'Halte Alun-Alun Bandung',
                'instruction' => 'Naik Bus Trans Metro Pasundan (TMP) Koridor 2 arah Cicaheum.',
                'duration_minutes' => 6,
                'latitude' => -6.92180000,
                'longitude' => 107.60700000,
            ],
            [
                'step_order' => 2,
                'mode' => 'bus',
                'stop_name' => 'Koridor Jl. Braga & Jl. Riau (LLRE Martadinata)',
                'instruction' => 'Menyusuri jalanan estetik Bandung kolonial, persiapkan turun di halte perempatan Banda.',
                'duration_minutes' => 15,
                'latitude' => -6.91000000,
                'longitude' => 107.61500000,
            ],
            [
                'step_order' => 3,
                'mode' => 'walking',
                'stop_name' => 'Halte TMP Banda / Museum Geologi',
                'instruction' => 'Turun di halte Banda, menyeberang di zebra cross menuju Jl. Diponegoro.',
                'duration_minutes' => 4,
                'latitude' => -6.90300000,
                'longitude' => 107.61850000,
            ],
            [
                'step_order' => 4,
                'mode' => 'walking',
                'stop_name' => 'Pelataran Depan Gedung Sate & Lapangan Gasibu',
                'instruction' => 'Jalan kaki 300m melintasi trotoar granit rindang menuju lapangan air mancur Gedung Sate.',
                'duration_minutes' => 5,
                'latitude' => -6.90250000,
                'longitude' => 107.61870000,
            ],
        ]);
    }
}
