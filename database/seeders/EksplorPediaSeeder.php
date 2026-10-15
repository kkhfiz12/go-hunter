<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizCategory;
use App\Models\Reward;
use App\Models\User;
use Illuminate\Database\Seeder;

class EksplorPediaSeeder extends Seeder
{
    /**
     * Run the database seeds for Eksplor Pedia module.
     */
    public function run(): void
    {
        // Pastikan users memiliki total_points dan badge_level tersinkronisasi
        $users = User::all();
        foreach ($users as $u) {
            if ($u->total_points === 0 && $u->reputation_points > 0) {
                $u->total_points = $u->reputation_points;
            }
            $u->syncBadgeLevel();
        }

        // Dapatkan Kota
        $jakarta = City::firstOrCreate(
            ['slug' => 'jakarta'],
            ['name' => 'DKI Jakarta', 'latitude' => -6.2088, 'longitude' => 106.8456]
        );

        $jogja = City::firstOrCreate(
            ['slug' => 'yogyakarta'],
            ['name' => 'DI Yogyakarta', 'latitude' => -7.7956, 'longitude' => 110.3695]
        );

        $bandung = City::firstOrCreate(
            ['slug' => 'bandung'],
            ['name' => 'Kota Bandung', 'latitude' => -6.9175, 'longitude' => 107.6191]
        );

        // ====================================================
        // 1. KATEGORI JAKARTA: Eksplorasi Sejarah Batavia & Monas
        // ====================================================
        $catJakarta = QuizCategory::updateOrCreate(
            ['slug' => 'eksplorasi-sejarah-jakarta-vol-1'],
            [
                'city_id' => $jakarta->id,
                'title' => 'Eksplorasi Sejarah Jakarta Vol. 1',
                'description' => 'Telusuri jejak transformasi Sunda Kelapa, kemegahan era kolonial Batavia, hingga lahirnya simbol kemerdekaan Indonesia di Monas.',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1555899434-94d1368aa7af?auto=format&fit=crop&w=800&q=80',
                'total_attempts' => 142,
            ]
        );

        // Kuis Jakarta: Paket 1 (Easy - 10 XP)
        $qJktEasy = Quiz::updateOrCreate(
            ['slug' => 'paket-1-ikon-monas-simbol-kemerdekaan'],
            [
                'category_id' => $catJakarta->id,
                'title' => 'Paket 1: Ikon Monas & Simbol Kemerdekaan',
                'difficulty' => 'easy',
                'points_per_question' => 10,
                'time_limit_minutes' => 10,
                'total_questions' => 3,
            ]
        );

        Question::updateOrCreate(
            ['quiz_id' => $qJktEasy->id, 'question_text' => 'Berapa tinggi total Monumen Nasional (Monas) dari permukaan tanah hingga puncak lidah api?'],
            [
                'options_json' => ['112 Meter', '125 Meter', '132 Meter', '150 Meter'],
                'correct_answer_index' => 2,
                'explanation' => 'Monumen Nasional memiliki tinggi tepat 132 meter (433 kaki) dengan pelataran cawan setinggi 17 meter dan ruang museum sejarah di dasarnya.',
            ]
        );

        Question::updateOrCreate(
            ['quiz_id' => $qJktEasy->id, 'question_text' => 'Lidah api di puncak Monas yang menyimbolkan semangat perjuangan bangsa Indonesia dilapisi oleh material apa?'],
            [
                'options_json' => ['Perunggu murni berukir', 'Lembaran emas murni', 'Baja tahan karat (stainless)', 'Tembaga berlapis titanium'],
                'correct_answer_index' => 1,
                'explanation' => 'Lidah api kemerdekaan seberat 14,5 ton tersebut terbuat dari perunggu dan dilapisi lembaran emas murni seberat puluhan kilogram.',
            ]
        );

        Question::updateOrCreate(
            ['quiz_id' => $qJktEasy->id, 'question_text' => 'Siapakah arsitek utama yang merancang rancangan awal Monumen Nasional atas instruksi Presiden Soekarno?'],
            [
                'options_json' => ['Frederich Silaban dan R.M. Soedarsono', 'Han Awal', 'Soejoedi Wirjoatmodjo', 'Ir. Ciputra'],
                'correct_answer_index' => 0,
                'explanation' => 'Rancangan Monas merupakan kolaborasi arsitek legendaris Frederich Silaban dan disempurnakan oleh R.M. Soedarsono.',
            ]
        );

        // Kuis Jakarta: Paket 2 (Medium - 20 XP)
        $qJktMed = Quiz::updateOrCreate(
            ['slug' => 'paket-2-sejarah-kota-tua-batavia'],
            [
                'category_id' => $catJakarta->id,
                'title' => 'Paket 2: Kejayaan Sunda Kelapa & Kota Tua Batavia',
                'difficulty' => 'medium',
                'points_per_question' => 20,
                'time_limit_minutes' => 12,
                'total_questions' => 3,
            ]
        );

        Question::updateOrCreate(
            ['quiz_id' => $qJktMed->id, 'question_text' => 'Gedung Museum Fatahillah di Jakarta Barat pada masa kolonial Belanda awalnya berfungsi sebagai apa?'],
            [
                'options_json' => ['Benteng Pertahanan VOC', 'Stadhuis (Balai Kota Batavia)', 'Gudang Rempah Pelabuhan', 'Kantor Pengadilan Tinggi Kolonial'],
                'correct_answer_index' => 1,
                'explanation' => 'Museum Fatahillah didirikan pada tahun 1707–1710 sebagai Stadhuis (Balai Kota Batavia) yang juga memiliki ruang bawah tanah sebagai penjara.',
            ]
        );

        Question::updateOrCreate(
            ['quiz_id' => $qJktMed->id, 'question_text' => 'Nama "Jayakarta" yang berarti kota kejayaan diberikan oleh Fatahillah pada tanggal 22 Juni 1527 setelah mengalahkan armada mana?'],
            [
                'options_json' => ['Pasukan Portugis', 'Pasukan Inggris (EIC)', 'Pasukan Spanyol', 'Armada Perompak Selat Malaka'],
                'correct_answer_index' => 0,
                'explanation' => 'Fatahillah dari Kesultanan Demak merebut Sunda Kelapa dari kekuasaan Portugis pada 22 Juni 1527 dan menamainya Jayakarta, yang kini diperingati sebagai HUT Jakarta.',
            ]
        );

        Question::updateOrCreate(
            ['quiz_id' => $qJktMed->id, 'question_text' => 'Jembatan tertua di kawasan Kota Tua Jakarta yang dibangun tahun 1628 dan dapat terangkat bila kapal lewat adalah?'],
            [
                'options_json' => ['Jembatan Merah', 'Jembatan Gantung Kalijodo', 'Jembatan Kota Intan', 'Jembatan Pasar Ikan'],
                'correct_answer_index' => 2,
                'explanation' => 'Jembatan Kota Intan (Engelse Brug) di Kali Besar adalah jembatan jungkat-jungkit peninggalan VOC satu-satunya yang masih bertahan hingga hari ini.',
            ]
        );

        // Kuis Jakarta: Paket 3 (Hard - 35 XP)
        $qJktHard = Quiz::updateOrCreate(
            ['slug' => 'paket-3-peristiwa-monumental-pra-kemerdekaan'],
            [
                'category_id' => $catJakarta->id,
                'title' => 'Paket 3: Gejolak Politik & Konspirasi Batavia Lama',
                'difficulty' => 'hard',
                'points_per_question' => 35,
                'time_limit_minutes' => 15,
                'total_questions' => 3,
            ]
        );

        Question::updateOrCreate(
            ['quiz_id' => $qJktHard->id, 'question_text' => 'Peristiwa pembantaian etnis Tionghoa yang dikenal dengan "Geger Pacinan" terjadi di Batavia pada tahun berapa?'],
            [
                'options_json' => ['1680', '1740', '1789', '1825'],
                'correct_answer_index' => 1,
                'explanation' => 'Tragedi Geger Pacinan (Chineesche Moord) terjadi pada Oktober 1740 di bawah kepemimpinan Gubernur Jenderal VOC Adriaan Valckenier.',
            ]
        );

        Question::updateOrCreate(
            ['quiz_id' => $qJktHard->id, 'question_text' => 'Gubernur Jenderal Hindia Belanda yang memindahkan pusat administrasi dari Batavia Lama (Kota Tua) ke Weltevreden (sekitar Lapangan Banteng) adalah?'],
            [
                'options_json' => ['Jan Pieterszoon Coen', 'Herman Willem Daendels', 'Thomas Stamford Raffles', 'Johannes van den Bosch'],
                'correct_answer_index' => 1,
                'explanation' => 'Herman Willem Daendels (1808–1811) memindahkan ibu kota ke Weltevreden yang lebih sehat dan membangun Istana Daendels (sekarang Gedung Kemenkeu).',
            ]
        );

        Question::updateOrCreate(
            ['quiz_id' => $qJktHard->id, 'question_text' => 'Sebelum bernama Lapangan Merdeka (Monas), lapangan terluas di dunia pada masanya ini dinamai apa oleh pemerintah kolonial?'],
            [
                'options_json' => ['Waterlooplein', 'Koningsplein', 'Burgemeester Plein', 'Wilhelmina Park'],
                'correct_answer_index' => 1,
                'explanation' => 'Sebelum dinamai Lapangan Merdeka pasca proklamasi, lapangan tersebut dinamai Koningsplein (Lapangan Raja) dan pada masa Jepang bernama Lapangan Ikada.',
            ]
        );

        // ====================================================
        // 2. KATEGORI YOGYAKARTA: Jejak Keraton & Filosofi Mataram
        // ====================================================
        $catJogja = QuizCategory::updateOrCreate(
            ['slug' => 'jejak-keraton-filosofi-mataram-yogyakarta'],
            [
                'city_id' => $jogja->id,
                'title' => 'Jejak Keraton & Filosofi Mataram Yogyakarta',
                'description' => 'Eksplorasi garis imajiner kosmologis Yogyakarta, keagungan arsitektur Tamansari, dan kisah heroik Serangan Umum 1 Maret.',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&w=800&q=80',
                'total_attempts' => 218,
            ]
        );

        // Kuis Jogja: Easy (10 XP)
        $qJgEasy = Quiz::updateOrCreate(
            ['slug' => 'paket-1-garis-imajiner-malioboro'],
            [
                'category_id' => $catJogja->id,
                'title' => 'Paket 1: Seputar Malioboro & Keraton Ngayogyakarta',
                'difficulty' => 'easy',
                'points_per_question' => 10,
                'time_limit_minutes' => 10,
                'total_questions' => 3,
            ]
        );

        Question::updateOrCreate(
            ['quiz_id' => $qJgEasy->id, 'question_text' => 'Garis lurus filosofis sumbu imajiner Yogyakarta membentang menghubungkan tiga titik sakral utama, yaitu?'],
            [
                'options_json' => ['Gunung Merapi - Keraton Jogja - Laut Selatan', 'Candi Borobudur - Keraton - Candi Prambanan', 'Tugu Jogja - Alun-Alun Utara - Kali Code', 'Gunung Kidul - Keraton - Kulon Progo'],
                'correct_answer_index' => 0,
                'explanation' => 'Sumbu Filosofi Yogyakarta yang telah diakui UNESCO menghubungkan Gunung Merapi di utara, Keraton di tengah, dan Pantai Parangkusumo (Laut Selatan) di selatan.',
            ]
        );

        Question::updateOrCreate(
            ['quiz_id' => $qJgEasy->id, 'question_text' => 'Kompleks pemandian peraduan putri keraton yang memiliki arsitektur perpaduan Jawa, Portugis, dan Tiongkok adalah?'],
            [
                'options_json' => ['Candi Sari', 'Tamansari Water Castle', 'Situs Warungboto', 'Panggung Krapyak'],
                'correct_answer_index' => 1,
                'explanation' => 'Tamansari dibangun pada masa Sultan Hamengkubuwono I pada tahun 1758 sebagai istana air, tempat rekreasi, dan benteng pertahanan keraton.',
            ]
        );

        Question::updateOrCreate(
            ['quiz_id' => $qJgEasy->id, 'question_text' => 'Pohon kembar legendaris yang menjadi lokasi tradisi "Masangin" (berjalan mata tertutup) terletak di mana?'],
            [
                'options_json' => ['Alun-Alun Utara', 'Alun-Alun Kidul (Selatan)', 'Halaman Pagelaran Keraton', 'Pelataran Tugu Pal Putih'],
                'correct_answer_index' => 1,
                'explanation' => 'Tradisi Masangin dilakukan di antara dua pohon beringin kembar (ringin kurung) di Alun-Alun Kidul Yogyakarta.',
            ]
        );

        // Kuis Jogja: Medium (20 XP)
        $qJgMed = Quiz::updateOrCreate(
            ['slug' => 'paket-2-perjanjian-giyanti-arsitektur-candi'],
            [
                'category_id' => $catJogja->id,
                'title' => 'Paket 2: Perjanjian Giyanti & Warisan Mataram',
                'difficulty' => 'medium',
                'points_per_question' => 20,
                'time_limit_minutes' => 12,
                'total_questions' => 3,
            ]
        );

        Question::updateOrCreate(
            ['quiz_id' => $qJgMed->id, 'question_text' => 'Perjanjian bersejarah tahun 1755 yang membagi Kerajaan Mataram Islam menjadi Kasunanan Surakarta dan Kasultanan Ngayogyakarta adalah?'],
            [
                'options_json' => ['Perjanjian Salatiga', 'Perjanjian Giyanti', 'Perjanjian Bongaya', 'Perjanjian Linggarjati'],
                'correct_answer_index' => 1,
                'explanation' => 'Perjanjian Giyanti disepakati pada 13 Februari 1755 di Desa Giyanti (Karanganyar) yang melahirkan Kesultanan Yogyakarta di bawah Sultan Hamengkubuwono I.',
            ]
        );

        Question::updateOrCreate(
            ['quiz_id' => $qJgMed->id, 'question_text' => 'Nama tugu ikonik di pusat kota Yogyakarta sebelum dipugar Belanda pada tahun 1889 dengan bentuk kerucut adalah?'],
            [
                'options_json' => ['Tugu Golong Gilig', 'Tugu Jam Nol Kilometer', 'Tugu Merdeka', 'Tugu Bintang Kejora'],
                'correct_answer_index' => 0,
                'explanation' => 'Awalnya tugu tersebut bernama Tugu Golong Gilig dengan bentuk tiang silinder (gilig) dan puncak bulat (golong), melambangkan persatuan raja dan rakyat.',
            ]
        );

        Question::updateOrCreate(
            ['quiz_id' => $qJgMed->id, 'question_text' => 'Kompleks candi Hindu terbesar di Indonesia yang terletak di perbatasan Yogyakarta dan Jawa Tengah adalah?'],
            [
                'options_json' => ['Candi Plaosan', 'Candi Sambisari', 'Candi Prambanan', 'Candi Ratu Boko'],
                'correct_answer_index' => 2,
                'explanation' => 'Candi Prambanan (Loro Jonggrang) dibangun pada abad ke-9 Masehi oleh Rakai Pikatan dari Dinasti Sanjaya dan dipersembahkan untuk Trimurti.',
            ]
        );

        // ====================================================
        // 3. KATEGORI BANDUNG: Pesona Paris van Java & Heritage
        // ====================================================
        $catBandung = QuizCategory::updateOrCreate(
            ['slug' => 'pesona-heritage-kolonial-bandung'],
            [
                'city_id' => $bandung->id,
                'title' => 'Pesona Heritage Kolonial & Bandung Lautan Api',
                'description' => 'Uji pengetahuan seputar karya agung Art Deco Gedung Sate, Konferensi Asia Afrika 1955, dan strategi heroik pembumihangusan Bandung.',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1584810359583-96fc3448beaa?auto=format&fit=crop&w=800&q=80',
                'total_attempts' => 97,
            ]
        );

        // Kuis Bandung: Easy (10 XP)
        $qBdgEasy = Quiz::updateOrCreate(
            ['slug' => 'paket-1-gedung-sate-ikon-priangan'],
            [
                'category_id' => $catBandung->id,
                'title' => 'Paket 1: Gedung Sate & Ikon Tanah Priangan',
                'difficulty' => 'easy',
                'points_per_question' => 10,
                'time_limit_minutes' => 10,
                'total_questions' => 3,
            ]
        );

        Question::updateOrCreate(
            ['quiz_id' => $qBdgEasy->id, 'question_text' => 'Ornamen menyerupai tusuk sate di puncak menara Gedung Sate Bandung sebenarnya berjumlah berapa buah dan menyimbolkan apa?'],
            [
                'options_json' => ['5 buah (Pancasila)', '6 buah (Biaya pembangunan 6 juta gulden)', '7 buah (Tujuh kabupaten Jawa Barat)', '9 buah (Wali Songo)'],
                'correct_answer_index' => 1,
                'explanation' => 'Ornamen jambu air berbentuk tusuk sate tersebut berjumlah 6 butir, melambangkan 6 juta gulden yang dihabiskan untuk membangun gedung karya Ir. J. Gerber ini.',
            ]
        );

        Question::updateOrCreate(
            ['quiz_id' => $qBdgEasy->id, 'question_text' => 'Konferensi internasional bersejarah yang dihadiri oleh para pemimpin 29 negara Asia dan Afrika pada tahun 1955 diselenggarakan di gedung mana?'],
            [
                'options_json' => ['Gedung Merdeka (Societeit Concordia)', 'Gedung Pakuan', 'Gedung Landmark', 'Gedung Villa Isola'],
                'correct_answer_index' => 0,
                'explanation' => 'KAA 1955 digelar di Gedung Merdeka yang berada di Jalan Asia Afrika, yang dulunya merupakan tempat perkumpulan Societeit Concordia.',
            ]
        );

        Question::updateOrCreate(
            ['quiz_id' => $qBdgEasy->id, 'question_text' => 'Pahlawan nasional yang gugur dalam meledakkan gudang mesiu Sekutu di Dayeuhkolot pada peristiwa Bandung Lautan Api adalah?'],
            [
                'options_json' => ['Mohammad Toha', 'Otto Iskandardinata', 'Djuanda Kartawidjaja', 'Aruji Kartawinata'],
                'correct_answer_index' => 0,
                'explanation' => 'Mohammad Toha bersama Mohammad Ramdan gugur sebagai martir dalam misi heroik meledakkan gudang mesiu milik tentara Sekutu di Dayeuhkolot.',
            ]
        );

        // Kuis Bandung: Hard (35 XP)
        $qBdgHard = Quiz::updateOrCreate(
            ['slug' => 'paket-2-arsitektur-art-deco-bandung'],
            [
                'category_id' => $catBandung->id,
                'title' => 'Paket 2: Mahakarya Art Deco & Paris van Java',
                'difficulty' => 'hard',
                'points_per_question' => 35,
                'time_limit_minutes' => 15,
                'total_questions' => 3,
            ]
        );

        Question::updateOrCreate(
            ['quiz_id' => $qBdgHard->id, 'question_text' => 'Gedung megah berlanggam Art Deco Nieuwe Bouwen di Bandung utara yang dibangun oleh jutawan D.W. Berretty dan kini menjadi Rektorat UPI bernama?'],
            [
                'options_json' => ['Grand Hotel Preanger', 'Hotel Savoy Homann', 'Villa Isola (Bumi Siliwangi)', 'Gedung Driekleur'],
                'correct_answer_index' => 2,
                'explanation' => 'Villa Isola dirancang oleh arsitek terkemuka C.P. Wolff Schoemaker pada tahun 1932 untuk Dominique Willem Berretty dengan pemandangan lembah Bandung.',
            ]
        );

        Question::updateOrCreate(
            ['quiz_id' => $qBdgHard->id, 'question_text' => 'Titik Nol Kilometer Bandung (0 Km) diresmikan oleh Gubernur Jenderal Daendels ketika menancapkan tongkatnya di tepi jalan apa?'],
            [
                'options_json' => ['Grote Postweg (Jalan Raya Pos)', 'Bragaweg (Jalan Braga)', 'Dagoweg (Jalan Ir. H. Djuanda)', 'Riouwweg (Jalan Riau)'],
                'correct_answer_index' => 0,
                'explanation' => 'Titik Nol Kilometer Bandung terletak di Jalan Asia Afrika (dulu bagian dari Jalan Raya Pos Anyer-Panarukan sepanjang 1.000 km).',
            ]
        );

        Question::updateOrCreate(
            ['quiz_id' => $qBdgHard->id, 'question_text' => 'Gaya arsitektur tropis modern yang diterapkan Prof. Charles Prosper Wolff Schoemaker dan Ir. Maclaine Pont di Bandung sering disebut sebagai aliran?'],
            [
                'options_json' => ['Indo-Europeesche Architectuurstijl', 'Gothic Revival', 'Brutalism', 'Art Nouveau Murni'],
                'correct_answer_index' => 0,
                'explanation' => 'Aliran Indo-Eropa memadukan teknik konstruksi barat modern dengan filosofi atap tropis nusantara (seperti aula barat & timur ITB).',
            ]
        );

        // ====================================================
        // 4. KATALOG REWARD / HADIAH PENUKARAN POIN
        // ====================================================
        Reward::updateOrCreate(
            ['voucher_code' => 'GH-MONAS-FREE'],
            [
                'title' => 'Voucher Tiket Masuk Monumen Nasional (Monas)',
                'description' => 'Gratis tiket masuk area cawan dan pelataran museum sejarah Monas untuk 1 orang pengunjung.',
                'points_required' => 50,
                'stock' => 25,
                'image_url' => 'https://images.unsplash.com/photo-1555899434-94d1368aa7af?auto=format&fit=crop&w=400&q=80',
                'category' => 'Tiket Wisata',
            ]
        );

        Reward::updateOrCreate(
            ['voucher_code' => 'GH-TAMANSARI-PASS'],
            [
                'title' => 'Tiket Masuk Tamansari Water Castle Jogja',
                'description' => 'Akses terusan gratis menjelajahi istana air Tamansari dan lorong bawah tanah Masjid Sumur Gumuling.',
                'points_required' => 65,
                'stock' => 20,
                'image_url' => 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&w=400&q=80',
                'category' => 'Tiket Wisata',
            ]
        );

        Reward::updateOrCreate(
            ['voucher_code' => 'GH-KOPI-JOS-25K'],
            [
                'title' => 'Voucher Kopi Jos & Angkringan Malioboro Rp 25.000',
                'description' => 'Potongan langsung Rp 25.000 untuk kulineran malam di sentra angkringan khas Lik Man Malioboro.',
                'points_required' => 40,
                'stock' => 50,
                'image_url' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?auto=format&fit=crop&w=400&q=80',
                'category' => 'Kuliner',
            ]
        );

        Reward::updateOrCreate(
            ['voucher_code' => 'GH-BRAGA-HERITAGE'],
            [
                'title' => 'Voucher Diskon 30% Heritage Walking Tour Bandung',
                'description' => 'Tur jalan kaki berpemandu mengeksplorasi bangunan Art Deco legendaris di sepanjang Jalan Braga & Asia Afrika.',
                'points_required' => 100,
                'stock' => 15,
                'image_url' => 'https://images.unsplash.com/photo-1584810359583-96fc3448beaa?auto=format&fit=crop&w=400&q=80',
                'category' => 'Tur Wisata',
            ]
        );

        Reward::updateOrCreate(
            ['voucher_code' => 'GH-MERCH-TOTEBAG'],
            [
                'title' => 'Official Go Hunter Explorer Canvas Tote Bag',
                'description' => 'Tas kanvas tebal eksklusif edisi traveler pejuang anti getok harga dengan sablon tahan air.',
                'points_required' => 150,
                'stock' => 10,
                'image_url' => 'https://images.unsplash.com/photo-1544816155-12df9643f363?auto=format&fit=crop&w=400&q=80',
                'category' => 'Merchandise',
            ]
        );
    }
}
