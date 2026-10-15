<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Destination;
use App\Models\Spot;
use Illuminate\Database\Seeder;

class TripySeeder extends Seeder
{
    /**
     * Run the database seeds for TRIPY module.
     */
    public function run(): void
    {
        // ----------------------------------------------------
        // 1. KOTA JAKARTA
        // ----------------------------------------------------
        $jakarta = City::updateOrCreate(
            ['slug' => 'jakarta'],
            [
                'name' => 'DKI Jakarta',
                'latitude' => -6.20880000,
                'longitude' => 106.84560000,
            ]
        );

        // Monas
        $monas = Destination::updateOrCreate(
            ['slug' => 'monumen-nasional-monas'],
            [
                'city_id' => $jakarta->id,
                'name' => 'Monumen Nasional (Monas)',
                'category' => 'Budaya',
                'type' => 'landmark',
                'short_description' => 'Ikon kebanggaan ibu kota setinggi 132 meter dengan pelataran puncak berpemandangan panorama 360 derajat.',
                'city' => 'Jakarta Pusat',
                'province' => 'DKI Jakarta',
                'address' => 'Gambir, Kecamatan Gambir, Kota Jakarta Pusat, DKI Jakarta',
                'latitude' => -6.17539240,
                'longitude' => 106.82715280,
                'description' => 'Monumen Nasional atau yang disingkat Monas adalah monumen peringatan setinggi 132 meter yang didirikan untuk mengenang perlawanan dan perjuangan rakyat Indonesia dalam merebut kemerdekaan dari pemerintahan kolonial Hindia Belanda. Pengunjung dapat menikmati museum sejarah nasional di bagian cawan serta naik ke pelataran puncak untuk menyaksikan panorama kota Jakarta yang megah.',
                'operating_hours' => ['open' => '08:00', 'close' => '16:00'],
                'official_ticket_price' => 15000,
                'parking_fee_motor' => 2000,
                'parking_fee_car' => 5000,
                'nearest_transit_info' => 'Stasiun Gambir (KRL/KA), Halte TransJakarta Monas (Koridor 1).',
                'cover_image' => 'https://images.unsplash.com/photo-1555899434-94d1368aa7af?auto=format&fit=crop&w=1200&q=80',
            ]
        );

        Spot::updateOrCreate(
            ['name' => 'Lenggang Jakarta Monas', 'destination_id' => $monas->id],
            [
                'category' => 'kuliner',
                'short_description' => 'Pusat kuliner legendaris Betawi dengan hidangan kerak telor, soto Betawi, & es selendang mayang.',
                'latitude' => -6.17750000,
                'longitude' => 106.82850000,
                'points_reward' => 15,
                'photo_url' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=600&q=80',
            ]
        );

        Spot::updateOrCreate(
            ['name' => 'Sentra Oleh-Oleh Khas Betawi Bang Jampang', 'destination_id' => $monas->id],
            [
                'category' => 'oleh_oleh',
                'short_description' => 'Aneka roti buaya mini, dodol Betawi asli, dan bir pletok segar rempah khas Jakarta.',
                'latitude' => -6.17620000,
                'longitude' => 106.82980000,
                'points_reward' => 10,
                'photo_url' => 'https://images.unsplash.com/photo-1513519245088-0e12902e5a38?auto=format&fit=crop&w=600&q=80',
            ]
        );

        Spot::updateOrCreate(
            ['name' => 'Spot Foto Api Kemerdekaan Pelataran Cawan', 'destination_id' => $monas->id],
            [
                'category' => 'spot_foto',
                'short_description' => 'Sudut foto spektakuler dengan latar megah lidah api berlapis emas murni.',
                'latitude' => -6.17510000,
                'longitude' => 106.82680000,
                'points_reward' => 10,
                'photo_url' => 'https://images.unsplash.com/photo-1542051841857-5f90071e7989?auto=format&fit=crop&w=600&q=80',
            ]
        );

        // Kota Tua Jakarta
        $kotaTua = Destination::updateOrCreate(
            ['slug' => 'kota-tua-jakarta'],
            [
                'city_id' => $jakarta->id,
                'name' => 'Kawasan Kota Tua & Museum Fatahillah',
                'category' => 'Budaya',
                'type' => 'sejarah',
                'short_description' => 'Kawasan cagar budaya kolonial dengan bangunan megah abad ke-17 dan atmosfer tempo doeloe.',
                'city' => 'Jakarta Barat',
                'province' => 'DKI Jakarta',
                'address' => 'Kawasan Taman Fatahillah, Pinangsia, Tamansari, Jakarta Barat',
                'latitude' => -6.13520000,
                'longitude' => 106.81330000,
                'description' => 'Kota Tua Jakarta, juga dikenal sebagai Batavia Lama, adalah sebuah wilayah khusus di Jakarta yang memiliki luas 1,3 kilometer persegi melintasi Jakarta Utara dan Barat. Pusat sejarah ini mempertahankan arsitektur kolonial Belanda dengan Museum Fatahillah sebagai pusat pelataran.',
                'operating_hours' => ['open' => '08:00', 'close' => '18:00'],
                'official_ticket_price' => 5000,
                'parking_fee_motor' => 3000,
                'parking_fee_car' => 5000,
                'nearest_transit_info' => 'Stasiun Jakarta Kota (KRL Commuter Line), Halte TransJakarta Kota.',
                'cover_image' => 'https://images.unsplash.com/photo-1584810359583-96fc3448beaa?auto=format&fit=crop&w=1200&q=80',
            ]
        );

        Spot::updateOrCreate(
            ['name' => 'Cafe Batavia Heritage', 'destination_id' => $kotaTua->id],
            [
                'category' => 'kuliner',
                'short_description' => 'Kafe vintage kolonial berkelas dengan sajian kopi hangat & kue tradisional menghadap alun-alun.',
                'latitude' => -6.13480000,
                'longitude' => 106.81300000,
                'points_reward' => 15,
                'photo_url' => 'https://images.unsplash.com/photo-1554118811-1e0d58224f24?auto=format&fit=crop&w=600&q=80',
            ]
        );

        Spot::updateOrCreate(
            ['name' => 'Pusat Suvenir Sunda Kelapa Kota Tua', 'destination_id' => $kotaTua->id],
            [
                'category' => 'oleh_oleh',
                'short_description' => 'Miniatur kapal pinisi, gantungan kunci khas Batavia tempo doeloe, dan kaos heritage.',
                'latitude' => -6.13600000,
                'longitude' => 106.81420000,
                'points_reward' => 10,
                'photo_url' => 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?auto=format&fit=crop&w=600&q=80',
            ]
        );

        Spot::updateOrCreate(
            ['name' => 'Spot Foto Sepeda Onthel Warna-Warni', 'destination_id' => $kotaTua->id],
            [
                'category' => 'spot_foto',
                'short_description' => 'Spot foto estetik menyewa sepeda onthel klasik lengkap dengan topi laksamana ala kolonial.',
                'latitude' => -6.13540000,
                'longitude' => 106.81350000,
                'points_reward' => 10,
                'photo_url' => 'https://images.unsplash.com/photo-1485965120184-e220f721d03e?auto=format&fit=crop&w=600&q=80',
            ]
        );

        // Gelora Bung Karno
        $gbk = Destination::updateOrCreate(
            ['slug' => 'gelora-bung-karno-senayan'],
            [
                'city_id' => $jakarta->id,
                'name' => 'Kompleks Gelora Bung Karno (GBK Senayan)',
                'category' => 'Alam',
                'type' => 'ruang_publik',
                'short_description' => 'Pusat olahraga dan ruang terbuka hijau multifungsi favorit warga untuk rekreasi dan jogging.',
                'city' => 'Jakarta Pusat',
                'province' => 'DKI Jakarta',
                'address' => 'Jl. Pintu Satu Senayan, Gelora, Kecamatan Tanah Abang, Kota Jakarta Pusat',
                'latitude' => -6.21850000,
                'longitude' => 106.80260000,
                'description' => 'Kompleks olahraga multifungsi terbesar di Indonesia yang dilengkapi fasilitas bertaraf internasional, taman kota rindang, hutan kota modern, dan jalur pedestrian yang ramah pejalan kaki.',
                'operating_hours' => ['open' => '05:00', 'close' => '21:00'],
                'official_ticket_price' => 0,
                'parking_fee_motor' => 2000,
                'parking_fee_car' => 5000,
                'nearest_transit_info' => 'Stasiun MRT Istora Mandiri, Halte TransJakarta GBK.',
                'cover_image' => 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?auto=format&fit=crop&w=1200&q=80',
            ]
        );

        Spot::updateOrCreate(
            ['name' => 'Hutan Kota by Plataran', 'destination_id' => $gbk->id],
            [
                'category' => 'kuliner',
                'short_description' => 'Sensasi santap kuliner nusantara di tengah taman rindang dengan panorama gedung pencakar langit SCBD.',
                'latitude' => -6.22010000,
                'longitude' => 106.80400000,
                'points_reward' => 15,
                'photo_url' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=600&q=80',
            ]
        );

        Spot::updateOrCreate(
            ['name' => 'Spot Foto Pelataran Stadion Ring Road GBK', 'destination_id' => $gbk->id],
            [
                'category' => 'spot_foto',
                'short_description' => 'Jalur lingkar stadion megah dengan pencahayaan warna-warni artistik saat senja dan malam hari.',
                'latitude' => -6.21820000,
                'longitude' => 106.80200000,
                'points_reward' => 10,
                'photo_url' => 'https://images.unsplash.com/photo-1508098682722-e99c43a406b2?auto=format&fit=crop&w=600&q=80',
            ]
        );

        // ----------------------------------------------------
        // 2. KOTA BANDUNG
        // ----------------------------------------------------
        $bandung = City::updateOrCreate(
            ['slug' => 'bandung'],
            [
                'name' => 'Kota Bandung',
                'latitude' => -6.91750000,
                'longitude' => 107.61910000,
            ]
        );

        $gedungSate = Destination::updateOrCreate(
            ['slug' => 'gedung-sate-bandung'],
            [
                'city_id' => $bandung->id,
                'name' => 'Gedung Sate & Museum Gedung Sate',
                'category' => 'Budaya',
                'type' => 'landmark',
                'short_description' => 'Ikon arsitektur neo-klasik berpadu elemen nusantara dengan menara ornamen tusuk sate legendaris.',
                'city' => 'Bandung',
                'province' => 'Jawa Barat',
                'address' => 'Jl. Diponegoro No.22, Citarum, Kec. Bandung Wetan, Kota Bandung',
                'latitude' => -6.90250000,
                'longitude' => 107.61860000,
                'description' => 'Gedung Sate adalah bangunan bersejarah di Kota Bandung yang dirancang oleh arsitek J. Gerber. Saat ini berfungsi sebagai pusat pemerintahan Provinsi Jawa Barat dan memiliki museum interaktif berteknologi canggih.',
                'operating_hours' => ['open' => '08:00', 'close' => '16:00'],
                'official_ticket_price' => 5000,
                'parking_fee_motor' => 2000,
                'parking_fee_car' => 4000,
                'nearest_transit_info' => 'Stasiun Bandung (10 menit berkendara), Angkot Cicaheum-Ciroyom.',
                'cover_image' => 'https://images.unsplash.com/photo-1589394815804-964ed0be2eb5?auto=format&fit=crop&w=1200&q=80',
            ]
        );

        Spot::updateOrCreate(
            ['name' => 'Sate Jando Gasibu Heritage', 'destination_id' => $gedungSate->id],
            [
                'category' => 'kuliner',
                'short_description' => 'Kuliner sate payudara sapi dengan bumbu kacang kental gurih legendaris tepat di belakang Gedung Sate.',
                'latitude' => -6.90180000,
                'longitude' => 107.61920000,
                'points_reward' => 15,
                'photo_url' => 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?auto=format&fit=crop&w=600&q=80',
            ]
        );

        Spot::updateOrCreate(
            ['name' => 'Kartika Sari Dago Heritage', 'destination_id' => $gedungSate->id],
            [
                'category' => 'oleh_oleh',
                'short_description' => 'Pusat oleh-oleh pisang bollen renyah, brownies kukus, dan camilan kering khas Parahyangan.',
                'latitude' => -6.90100000,
                'longitude' => 107.61500000,
                'points_reward' => 10,
                'photo_url' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=600&q=80',
            ]
        );

        // Jalan Braga
        $braga = Destination::updateOrCreate(
            ['slug' => 'jalan-braga-heritage'],
            [
                'city_id' => $bandung->id,
                'name' => 'Kawasan Heritage Jalan Braga',
                'category' => 'Budaya',
                'type' => 'sejarah',
                'short_description' => 'Jalanan bersejarah Paris van Java dengan deretan kafe indie, galeri lukisan jalanan, dan gedung art deco.',
                'city' => 'Bandung',
                'province' => 'Jawa Barat',
                'address' => 'Jl. Braga, Sumur Bandung, Kota Bandung, Jawa Barat',
                'latitude' => -6.91770000,
                'longitude' => 107.60960000,
                'description' => 'Jalan Braga adalah jalan utama di kota Bandung yang terkenal sejak zaman Hindia Belanda sebagai pusat perbelanjaan dan hiburan bergengsi.',
                'operating_hours' => ['open' => '00:00', 'close' => '23:59'],
                'official_ticket_price' => 0,
                'parking_fee_motor' => 3000,
                'parking_fee_car' => 5000,
                'nearest_transit_info' => 'Stasiun Bandung (500 meter jalan kaki).',
                'cover_image' => 'https://images.unsplash.com/photo-1518684079-3c830dcef090?auto=format&fit=crop&w=1200&q=80',
            ]
        );

        Spot::updateOrCreate(
            ['name' => 'Warung Kopi Purnama 1930', 'destination_id' => $braga->id],
            [
                'category' => 'kuliner',
                'short_description' => 'Kedai kopi tertua di Bandung yang terkenal dengan roti bakar selai srikaya buatan rumahan.',
                'latitude' => -6.91850000,
                'longitude' => 107.60900000,
                'points_reward' => 15,
                'photo_url' => 'https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?auto=format&fit=crop&w=600&q=80',
            ]
        );

        Spot::updateOrCreate(
            ['name' => 'Spot Foto Mural Seni Trotoar Braga', 'destination_id' => $braga->id],
            [
                'category' => 'spot_foto',
                'short_description' => 'Deretan lukisan kanvas pelukis jalanan Bandung dengan latar tiang lampu klasik khas Eropa.',
                'latitude' => -6.91700000,
                'longitude' => 107.61020000,
                'points_reward' => 10,
                'photo_url' => 'https://images.unsplash.com/photo-1499781350541-7783f6c6a0c8?auto=format&fit=crop&w=600&q=80',
            ]
        );

        // ----------------------------------------------------
        // 3. KOTA YOGYAKARTA
        // ----------------------------------------------------
        $jogja = City::updateOrCreate(
            ['slug' => 'yogyakarta'],
            [
                'name' => 'DI Yogyakarta',
                'latitude' => -7.79560000,
                'longitude' => 110.36950000,
            ]
        );

        $keraton = Destination::updateOrCreate(
            ['slug' => 'keraton-ngayogyakarta-hadiningrat'],
            [
                'city_id' => $jogja->id,
                'name' => 'Keraton Ngayogyakarta Hadiningrat',
                'category' => 'Budaya',
                'type' => 'sejarah',
                'short_description' => 'Istana megah Kasultanan Ngayogyakarta dengan tradisi adiluhung, pusaka keraton, dan pertunjukan gamelan.',
                'city' => 'Yogyakarta',
                'province' => 'DI Yogyakarta',
                'address' => 'Jl. Rotowijayan Blok No. 1, Panembahan, Kecamatan Kraton, Kota Yogyakarta',
                'latitude' => -7.80530000,
                'longitude' => 110.36420000,
                'description' => 'Keraton Yogyakarta merupakan istana resmi Kesultanan Ngayogyakarta Hadiningrat yang didirikan oleh Sultan Hamengkubuwana I pada tahun 1755.',
                'operating_hours' => ['open' => '08:30', 'close' => '14:00'],
                'official_ticket_price' => 15000,
                'parking_fee_motor' => 2000,
                'parking_fee_car' => 5000,
                'nearest_transit_info' => 'Stasiun Tugu Yogyakarta, Trans Jogja Halte Malioboro.',
                'cover_image' => 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&w=1200&q=80',
            ]
        );

        Spot::updateOrCreate(
            ['name' => 'Gudeg Yu Djum Wijilan 167', 'destination_id' => $keraton->id],
            [
                'category' => 'kuliner',
                'short_description' => 'Gudeg kering tradisional rasa manis gurih otentik yang dimasak memakai kuali tanah liat & kayu bakar.',
                'latitude' => -7.80400000,
                'longitude' => 110.36600000,
                'points_reward' => 15,
                'photo_url' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=600&q=80',
            ]
        );

        Spot::updateOrCreate(
            ['name' => 'Sentra Bakpia Pathok 25', 'destination_id' => $keraton->id],
            [
                'category' => 'oleh_oleh',
                'short_description' => 'Pusat oleh-oleh bakpia basah isi kacang hijau, keju, dan cokelat hangat langsung dari oven dapur.',
                'latitude' => -7.80100000,
                'longitude' => 110.35800000,
                'points_reward' => 10,
                'photo_url' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=600&q=80',
            ]
        );

        Spot::updateOrCreate(
            ['name' => 'Spot Foto Bangsal Pagelaran & Pelataran Keben', 'destination_id' => $keraton->id],
            [
                'category' => 'spot_foto',
                'short_description' => 'Arsitektur tiang kayu jati berukir emas dan ornamen khas Mataram Islam yang anggun memukau.',
                'latitude' => -7.80480000,
                'longitude' => 110.36400000,
                'points_reward' => 10,
                'photo_url' => 'https://images.unsplash.com/photo-1579783902614-a3fb3927b675?auto=format&fit=crop&w=600&q=80',
            ]
        );
    }
}
