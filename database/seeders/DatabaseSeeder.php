<?php

namespace Database\Seeders;

use App\Models\BuddyRequest;
use App\Models\Comment;
use App\Models\Destination;
use App\Models\PriceReport;
use App\Models\Review;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ==========================================
        // 1. DATA USER DUMMY (Termasuk Verified Guide)
        // ==========================================
        $guideUser = User::updateOrCreate(
            ['email' => 'guide.bromo@gohunter.id'],
            [
                'name' => 'Cak Joko Bromo (Guide Lokal Tengger)',
                'password' => bcrypt('password'),
                'avatar' => null,
                'bio' => 'Pemandu wisata bersertifikasi & warga asli Tengger. Aktif membagikan informasi rute aman, cuaca Bromo, dan perlindungan traveler dari getok harga.',
                'phone_number' => '081234567890',
                'reputation_points' => 480,
            ]
        );

        $travelerUser = User::updateOrCreate(
            ['email' => 'rara.traveler@gohunter.id'],
            [
                'name' => 'Rara Maharani (Solo Explorer)',
                'password' => bcrypt('password'),
                'avatar' => null,
                'bio' => 'Solo backpacker keliling Jawa-Bali. Penggemar sunrise, kuliner lokal hemat, dan pencari teman jalan patungan.',
                'phone_number' => '081398765432',
                'reputation_points' => 125,
            ]
        );

        $familyUser = User::updateOrCreate(
            ['email' => 'dimas.pratama@gohunter.id'],
            [
                'name' => 'Dimas Pratama',
                'password' => bcrypt('password'),
                'avatar' => null,
                'bio' => 'Weekend road-tripper bersama keluarga. Pengamat ketertiban tarif parkir dan kebersihan fasilitas wisata alam.',
                'phone_number' => '081712345678',
                'reputation_points' => 65,
            ]
        );

        $defaultUser = User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Budi Santoso',
                'password' => bcrypt('password'),
                'avatar' => null,
                'bio' => 'Traveler pemula yang haus petualangan dan tips rute hemat.',
                'phone_number' => '081987654321',
                'reputation_points' => 20,
            ]
        );

        // ==========================================
        // 2. DATA DESTINASI WISATA REALISTIS
        // ==========================================
        $destBromo = Destination::updateOrCreate(
            ['slug' => 'bromo-sunrise-penanjakan'],
            [
                'name' => 'Bromo Sunrise & Penanjakan (Kab. Pasuruan)',
                'category' => 'Alam',
                'city' => 'Pasuruan',
                'province' => 'Jawa Timur',
                'address' => 'Desa Wonokitri, Kec. Tosari, Kabupaten Pasuruan, Jawa Timur 67177',
                'latitude' => -7.9424936,
                'longitude' => 112.9530122,
                'description' => "Spot panorama sunrise legendaris berlatar kaldera purba Gunung Bromo, Batok, dan Semeru. Jalur masuk melalui Wonokitri Tosari merupakan akses paling mulus dengan aspal hotmix lebar dan rest area terpadu.\n\nTips Logistik: Wajib booking tiket online TNBTS (bookingbromo.bromotenggersemeru.org) sebelum kedatangan. Kendaraan pribadi roda empat dilarang turun ke Laut Pasir; wajib menggunakan Jeep 4WD paguyuban resmi desa wisata.",
                'operating_hours' => [
                    'buka' => '00:00',
                    'tutup' => '23:59',
                    'catatan' => 'Buka 24 jam untuk pengamatan sunrise dan astrofotografi (milky way).',
                ],
                'official_ticket_price' => 29000.00,
                'parking_fee_motor' => 5000.00,
                'parking_fee_car' => 10000.00,
                'nearest_transit_info' => 'Stasiun Kereta Api Pasuruan (42 km) atau Terminal Untung Suropati. Lanjut minibus L300 Pasuruan - Tosari turun di Rest Area Wonokitri, dilanjutkan sewa Jeep 4WD paguyuban lokal.',
                'cover_image' => 'https://images.unsplash.com/photo-1588668214407-6ea9a6d8c272?auto=format&fit=crop&w=1200&q=80',
            ]
        );

        $destKawahPutih = Destination::updateOrCreate(
            ['slug' => 'kawah-putih-ciwidey'],
            [
                'name' => 'Kawah Putih Ciwidey (Kab. Bandung)',
                'category' => 'Alam',
                'city' => 'Bandung',
                'province' => 'Jawa Barat',
                'address' => 'Jl. Raya Soreang Ciwidey Km 25, Sugihmukti, Kec. Pasirjambu, Kabupaten Bandung, Jawa Barat 40972',
                'latitude' => -7.1662057,
                'longitude' => 107.4021256,
                'description' => "Danau kawah vulkanik berwarna putih kehijauan yang eksotis di ketinggian 2.430 mdpl dengan udara sejuk pegunungan Patuha.\n\nTips Logistik: Tersedia shuttle ontang-anting resmi dari gerbang utama bawah menuju bibir kawah. Bawa masker karena aroma belerang pekat, dan patuhi batas maksimal 30 menit berada di dasar kawah.",
                'operating_hours' => [
                    'buka' => '07:30',
                    'tutup' => '17:00',
                    'catatan' => 'Loket tiket buka mulai 07:30 dan tutup 16:30 WIB.',
                ],
                'official_ticket_price' => 30000.00,
                'parking_fee_motor' => 6000.00,
                'parking_fee_car' => 15000.00,
                'nearest_transit_info' => 'Stasiun Whoosh Tegalluar / Stasiun KRL Bandung. Naik Bus Trans Metro Pasundan Koridor 1D ke Terminal Soreang, oper angkot kuning Soreang-Ciwidey turun tepat di gerbang Kawah Putih.',
                'cover_image' => 'https://images.unsplash.com/photo-1544644181-1484b3fdfc62?auto=format&fit=crop&w=1200&q=80',
            ]
        );

        $destAnyer = Destination::updateOrCreate(
            ['slug' => 'pantai-anyer'],
            [
                'name' => 'Pantai Anyer (Kab. Serang)',
                'category' => 'Bahari',
                'city' => 'Serang',
                'province' => 'Banten',
                'address' => 'Jl. Raya Anyer-Sirih, Sindanglaya, Kec. Anyar, Kabupaten Serang, Banten 42166',
                'latitude' => -6.0528140,
                'longitude' => 105.9221190,
                'description' => "Destinasi bahari favorit dengan hamparan pasir landai dan panorama matahari terbenam menghadap Selat Sunda serta siluet Gunung Krakatau.\n\nTips Logistik: Pilihlah pantai berkarang minimalis yang terkelola resmi pemda atau hotel terbuka untuk menghindari calo sewa tikar dan tarif parkir liar tanpa karcis.",
                'operating_hours' => [
                    'buka' => '06:00',
                    'tutup' => '18:00',
                    'catatan' => 'Area bibir pantai umum dibuka pagi hingga senja.',
                ],
                'official_ticket_price' => 15000.00,
                'parking_fee_motor' => 5000.00,
                'parking_fee_car' => 15000.00,
                'nearest_transit_info' => 'Stasiun KRL Rangkasbitung atau Stasiun Cilegon (KA Lokal Merak). Dari Stasiun Cilegon naik angkutan kota (angkot) trayek Cilegon - Anyer langsung turun di bibir pantai.',
                'cover_image' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1200&q=80',
            ]
        );

        $destCilember = Destination::updateOrCreate(
            ['slug' => 'curug-cilember'],
            [
                'name' => 'Curug Cilember (Kab. Bogor)',
                'category' => 'Alam',
                'city' => 'Bogor',
                'province' => 'Jawa Barat',
                'address' => 'Desa Megamendung, Kec. Megamendung, Puncak, Kabupaten Bogor, Jawa Barat 16770',
                'latitude' => -6.6575020,
                'longitude' => 106.9404280,
                'description' => "Kawasan ekowisata hutan pinus asri yang memiliki 7 tingkatan air terjun alami bertingkat, penangkaran kupu-kupu raksasa, dan area perkemahan keluarga.\n\nTips Logistik: Curug 7 adalah yang paling mudah diakses anak-anak (5 menit jalan santai), sedangkan Curug 1-3 membutuhkan trekking menanjak dengan sepatu anti-selip.",
                'operating_hours' => [
                    'buka' => '07:00',
                    'tutup' => '17:00',
                    'catatan' => 'Area camping ground dibuka 24 jam dengan izin pos jagawana.',
                ],
                'official_ticket_price' => 25000.00,
                'parking_fee_motor' => 5000.00,
                'parking_fee_car' => 10000.00,
                'nearest_transit_info' => 'Stasiun KRL Commuter Line Bogor. Dari stasiun naik Angkot 02/03 ke Sukasari, lanjut Angkot Puncak/Cianjur turun di simpang Cilember (Megamendung), lanjut ojek lokal 2 km ke gerbang masuk.',
                'cover_image' => 'https://images.unsplash.com/photo-1432405972618-c60b0225b8f9?auto=format&fit=crop&w=1200&q=80',
            ]
        );

        // ==========================================
        // 3. DATA PRICE REPORT (Normal vs Getok)
        // ==========================================
        // Bromo Reports
        PriceReport::firstOrCreate(
            ['destination_id' => $destBromo->id, 'item_name' => 'Sewa Tikar Sunrise Penanjakan'],
            [
                'user_id' => $travelerUser->id,
                'price_paid' => 15000.00,
                'fairness_level' => 'fair',
                'proof_image' => null,
                'notes' => 'Disewa dari warga lokal di gardu pandang, sangat lumayan untuk duduk nunggu fajar subuh.',
            ]
        );
        PriceReport::firstOrCreate(
            ['destination_id' => $destBromo->id, 'item_name' => 'Bakso Kuah Panas Rest Area Tosari'],
            [
                'user_id' => $familyUser->id,
                'price_paid' => 20000.00,
                'fairness_level' => 'cheap',
                'proof_image' => null,
                'notes' => 'Porsi mengenyangkan di warung resmi dekat loket tiket, harga tertera di spanduk.',
            ]
        );
        PriceReport::firstOrCreate(
            ['destination_id' => $destBromo->id, 'item_name' => 'Sewa Kuda Liar Laut Pasir Tanpa Tawar'],
            [
                'user_id' => $defaultUser->id,
                'price_paid' => 150000.00,
                'fairness_level' => 'overpriced',
                'proof_image' => null,
                'notes' => 'Waspada getok! Sempat dipatok 150rb sekali jalan karena tidak tanya di awal. Standar resmi paguyuban adalah 50rb-70rb PP kawah.',
            ]
        );
        PriceReport::firstOrCreate(
            ['destination_id' => $destBromo->id, 'item_name' => 'Sarung Tangan & Kupluk Rajut'],
            [
                'user_id' => $guideUser->id,
                'price_paid' => 20000.00,
                'fairness_level' => 'fair',
                'proof_image' => null,
                'notes' => 'Dijual pedagang asongan lokal, bahan tebal dan bisa ditawar wajar Rp 15.000 - Rp 20.000.',
            ]
        );

        // Kawah Putih Reports
        PriceReport::firstOrCreate(
            ['destination_id' => $destKawahPutih->id, 'item_name' => 'Shuttle Ontang-Anting Resmi PP'],
            [
                'user_id' => $familyUser->id,
                'price_paid' => 28000.00,
                'fairness_level' => 'fair',
                'proof_image' => null,
                'notes' => 'Tarif resmi tertera di karcis loket dari gerbang bawah ke puncak kawah.',
            ]
        );
        PriceReport::firstOrCreate(
            ['destination_id' => $destKawahPutih->id, 'item_name' => 'Masker Kain Anti Belerang'],
            [
                'user_id' => $travelerUser->id,
                'price_paid' => 5000.00,
                'fairness_level' => 'cheap',
                'proof_image' => null,
                'notes' => 'Sangat murah dan membantu saat bau belerang kawah sedang naik pekat.',
            ]
        );
        PriceReport::firstOrCreate(
            ['destination_id' => $destKawahPutih->id, 'item_name' => 'Jasa Cetak Foto Langsung Tanpa Izin'],
            [
                'user_id' => $defaultUser->id,
                'price_paid' => 50000.00,
                'fairness_level' => 'overpriced',
                'proof_image' => null,
                'notes' => 'Oknum fotografer liar memotret tanpa diminta lalu memaksa membeli cetakan seharga 50rb per lembar.',
            ]
        );
        PriceReport::firstOrCreate(
            ['destination_id' => $destKawahPutih->id, 'item_name' => 'Jagung Bakar Manis Pedas'],
            [
                'user_id' => $guideUser->id,
                'price_paid' => 12000.00,
                'fairness_level' => 'fair',
                'proof_image' => null,
                'notes' => 'Harga standar warung wisata di area parkir bawah Ciwidey.',
            ]
        );

        // Pantai Anyer Reports
        PriceReport::firstOrCreate(
            ['destination_id' => $destAnyer->id, 'item_name' => 'Kelapa Muda Ijo Utuh'],
            [
                'user_id' => $travelerUser->id,
                'price_paid' => 20000.00,
                'fairness_level' => 'fair',
                'proof_image' => null,
                'notes' => 'Harga wajar tepi pantai, kelapa segar disajikan dengan es dan gula aren asli.',
            ]
        );
        PriceReport::firstOrCreate(
            ['destination_id' => $destAnyer->id, 'item_name' => 'Sewa Tikar Liar Bawah Pohon'],
            [
                'user_id' => $familyUser->id,
                'price_paid' => 80000.00,
                'fairness_level' => 'overpriced',
                'proof_image' => null,
                'notes' => 'Waspada getok! Di awal dibilang gratis duduk di pasir bawah pohon, saat mau pulang ditagih sewa paksa 80rb.',
            ]
        );
        PriceReport::firstOrCreate(
            ['destination_id' => $destAnyer->id, 'item_name' => 'Parkir Mobil Liar Pinggir Jalan'],
            [
                'user_id' => $defaultUser->id,
                'price_paid' => 25000.00,
                'fairness_level' => 'overpriced',
                'proof_image' => null,
                'notes' => 'Diminta pemuda lokal tanpa karcis resmi pemda sebesar 25rb. Disarankan parkir di kantong resmi pengelola.',
            ]
        );
        PriceReport::firstOrCreate(
            ['destination_id' => $destAnyer->id, 'item_name' => 'Ikan Bakar Kakap Merah 1/2 kg'],
            [
                'user_id' => $guideUser->id,
                'price_paid' => 50000.00,
                'fairness_level' => 'fair',
                'proof_image' => null,
                'notes' => 'Menu tertera daftar harga per ons lengkap di rumah makan pinggir pantai Anyer.',
            ]
        );

        // Curug Cilember Reports
        PriceReport::firstOrCreate(
            ['destination_id' => $destCilember->id, 'item_name' => 'Sewa Tenda Dome 4 Orang'],
            [
                'user_id' => $travelerUser->id,
                'price_paid' => 120000.00,
                'fairness_level' => 'fair',
                'proof_image' => null,
                'notes' => 'Tenda sudah dipasangkan jagawana resmi di camping ground blok hutan pinus.',
            ]
        );
        PriceReport::firstOrCreate(
            ['destination_id' => $destCilember->id, 'item_name' => 'Kopi Tubruk & Pisang Goreng Keju'],
            [
                'user_id' => $defaultUser->id,
                'price_paid' => 15000.00,
                'fairness_level' => 'cheap',
                'proof_image' => null,
                'notes' => 'Warung dekat Curug 7 sangat ramah dan harga standar warung desa.',
            ]
        );
        PriceReport::firstOrCreate(
            ['destination_id' => $destCilember->id, 'item_name' => 'Jasa Pemandu Curug 5 Liar'],
            [
                'user_id' => $familyUser->id,
                'price_paid' => 100000.00,
                'fairness_level' => 'overpriced',
                'proof_image' => null,
                'notes' => 'Oknum menakut-nakuti jalur curug atas berbahaya dan memaksa sewa pemandu 100rb. Jalur sebenarnya ada penunjuk arah jelas.',
            ]
        );

        // ==========================================
        // 4. DATA BUDDY REQUEST (Aktif dengan WA Link)
        // ==========================================
        BuddyRequest::firstOrCreate(
            ['title' => 'Patungan Sewa Jeep Bromo Sunrise (Sisa 2 Kursi)'],
            [
                'user_id' => $travelerUser->id,
                'destination_id' => $destBromo->id,
                'description' => "Halo teman-teman! Saya solo traveler berencana sunrise-an di Penanjakan Bromo dan jelajah Pasir Berbisik. Sudah sewa Jeep 4WD resmi paguyuban Wonokitri Tosari kapasitas 6 orang, saat ini baru terisi 4 orang.\n\nKriteria: Santai, saling bantu foto, dan tepat waktu subuh. Titik kumpul di Rest Area Wonokitri pukul 02.00 WIB.",
                'departure_date' => now()->addDays(7)->toDateString(),
                'return_date' => now()->addDays(8)->toDateString(),
                'slots_needed' => 6,
                'slots_filled' => 4,
                'estimated_budget_per_person' => 175000.00,
                'status' => 'open',
                'contact_link' => 'https://wa.me/6281398765432',
            ]
        );

        BuddyRequest::firstOrCreate(
            ['title' => 'Road-Trip & Camping Ceria di Hutan Pinus Curug Cilember'],
            [
                'user_id' => $familyUser->id,
                'destination_id' => $destCilember->id,
                'description' => "Rencana camping akhir pekan Sabtu-Minggu di Camping Ground Curug Cilember Megamendung. Berangkat dari Jakarta Selatan naik Avanza, masih ada sisa 2 seat kosong untuk yang mau bareng hemat bensin dan tol.\n\nKriteria: Non-smoking dalam mobil, ramah, dan siap masak-masak santai di tepi api unggun.",
                'departure_date' => now()->addDays(5)->toDateString(),
                'return_date' => now()->addDays(6)->toDateString(),
                'slots_needed' => 5,
                'slots_filled' => 3,
                'estimated_budget_per_person' => 125000.00,
                'status' => 'open',
                'contact_link' => 'https://wa.me/6281712345678',
            ]
        );

        BuddyRequest::firstOrCreate(
            ['title' => 'Patungan Sewa Perahu Snorkeling & Sunset Pantai Anyer'],
            [
                'user_id' => $defaultUser->id,
                'destination_id' => $destAnyer->id,
                'description' => "Cari 3 orang teman jalan untuk patungan sewa perahu nelayan lokal berkeliling spot karang snorkeling dan nikmati golden hour sunset di perairan Anyer Banten.\n\nBiaya sewa perahu nelayan Rp 600.000 dibagi berempat jadi sangat hemat Rp 150.000 per orang!",
                'departure_date' => now()->addDays(10)->toDateString(),
                'return_date' => now()->addDays(11)->toDateString(),
                'slots_needed' => 4,
                'slots_filled' => 1,
                'estimated_budget_per_person' => 150000.00,
                'status' => 'open',
                'contact_link' => 'https://wa.me/6281987654321',
            ]
        );

        // ==========================================
        // 5. DATA THREAD Q&A BESERTA KOMENTAR BALASAN
        // ==========================================
        // Thread 1: Cuaca Bromo
        $threadBromo = Thread::updateOrCreate(
            ['slug' => 'update-cuaca-dan-kabut-bromo-subuh-ini'],
            [
                'user_id' => $travelerUser->id,
                'destination_id' => $destBromo->id,
                'title' => 'Update Cuaca & Kabut Bromo Subuh Ini: Apakah Sunrise Terlihat Jelas?',
                'category' => 'weather_update',
                'content' => 'Halo teman-teman dan warga lokal Tengger, saya rencana naik ke Penanjakan 1 subuh lusa. Mau tanya apakah akhir-akhir ini sering kabut tebal atau sunrise-nya terbuka cerah? Lalu kisaran suhu saat ini berapa derajat ya biar persiapin jaket yang pas.',
                'views_count' => 184,
            ]
        );

        // Solution comment by Guide Lokal
        $solBromo = Comment::firstOrCreate(
            ['thread_id' => $threadBromo->id, 'user_id' => $guideUser->id],
            [
                'content' => "Salam lestari Mbak Rara! Info terkonfirmasi langsung dari desa Wonokitri: 3 hari terakhir cuaca di kaldera Bromo sangat cerah (open sky) tanpa kabut tebal saat subuh jam 04.30 - 05.30 WIB.\n\nNamun suhu cukup ekstrem sekitar 5°C sampai 7°C, disarankan wajib membawa jaket windbreaker tebal, kupluk, dan sarung tangan polar. Hindari calo jaket di pinggir jalan, bawa perlengkapan dari rumah ya!",
                'is_solution' => true,
            ]
        );

        Comment::firstOrCreate(
            ['thread_id' => $threadBromo->id, 'parent_id' => $solBromo->id, 'user_id' => $travelerUser->id],
            [
                'content' => 'Terima kasih banyak Cak Joko infonya sangat membantu dan detail! Langsung saya siapkan sarung tangan tebal.',
                'is_solution' => false,
            ]
        );

        // Thread 2: Jalur Bus Kawah Putih
        $threadKawahPutih = Thread::updateOrCreate(
            ['slug' => 'apakah-bus-medium-bisa-naik-langsung-ke-kawah-putih'],
            [
                'user_id' => $familyUser->id,
                'destination_id' => $destKawahPutih->id,
                'title' => 'Apakah Bus Medium / Rombongan Bisa Langsung Parkir di Atas Kawah Putih?',
                'category' => 'traffic_transit',
                'content' => 'Rencana mau bawa rombongan keluarga besar naik bus medium 30 seat. Apakah jalurnya aman sampai ke bibir kawah atas, atau wajib berhenti di terminal bawah?',
                'views_count' => 142,
            ]
        );

        $solKP = Comment::firstOrCreate(
            ['thread_id' => $threadKawahPutih->id, 'user_id' => $guideUser->id],
            [
                'content' => 'Untuk bus medium dan bus besar regulasi Perhutani melarang naik ke kawah atas demi keselamatan karena tanjakan curam dan tikungan sempit. Bus wajib parkir di area parkir bawah yang sangat luas, kemudian rombongan berpindah naik armada shuttle resmi Ontang-Anting tarif Rp 28.000 PP per orang. Sistem ini jauh lebih aman dan nyaman.',
                'is_solution' => true,
            ]
        );

        Comment::firstOrCreate(
            ['thread_id' => $threadKawahPutih->id, 'user_id' => $defaultUser->id],
            [
                'content' => 'Betul sekali, kemarin rombongan kantor saya juga pakai ontang-anting, seru seperti naik wahana dan supirnya hapal medan.',
                'is_solution' => false,
            ]
        );

        // Thread 3: Tips Anyer Anti-Getok
        $threadAnyer = Thread::updateOrCreate(
            ['slug' => 'tips-hindari-getok-harga-sewa-tikar-dan-parkir-pantai-anyer'],
            [
                'user_id' => $defaultUser->id,
                'destination_id' => $destAnyer->id,
                'title' => 'Tips Hindari Getok Harga Sewa Tikar & Parkir Liar di Pantai Anyer',
                'category' => 'tips',
                'content' => 'Banyak cerita viral tentang wisatawan kena getok harga sewa tikar dan parkir di Anyer. Boleh bagikan tips aman pantai mana yang resmi dan bebas pungli liar?',
                'views_count' => 265,
            ]
        );

        $solAnyer = Comment::firstOrCreate(
            ['thread_id' => $threadAnyer->id, 'user_id' => $travelerUser->id],
            [
                'content' => 'Tips dari pengalaman saya: 1) Masuklah lewat gerbang pantai yang memiliki plang karcis resmi pemda seperti Pantai Pasir Putih Sirih atau Pantai Sambolo; 2) Bawa tikar lipat sendiri dari rumah dan tolak dengan sopan jika ada yang menawarkan tikar sewa; 3) Selalu minta struk berstempel sebelum membayar parkir; 4) Tanya dan sepakati harga kelapa / makan sebelum memesan.',
                'is_solution' => true,
            ]
        );

        Comment::firstOrCreate(
            ['thread_id' => $threadAnyer->id, 'user_id' => $familyUser->id],
            [
                'content' => 'Sangat setuju! Kalau mau makan seafood lebih baik di resto yang ada daftar harga jelas di meja daripada warung tenda tanpa menu tertulis.',
                'is_solution' => false,
            ]
        );

        // Thread 4: Rute Ganjil Genap Puncak Curug Cilember
        $threadCilember = Thread::updateOrCreate(
            ['slug' => 'jadwal-ganjil-genap-dan-one-way-jalur-puncak-ke-curug-cilember'],
            [
                'user_id' => $familyUser->id,
                'destination_id' => $destCilember->id,
                'title' => 'Jalur Puncak Akhir Pekan: Jadwal Ganjil Genap & One Way ke Megamendung',
                'category' => 'traffic_transit',
                'content' => 'Mau liburan sabtu pagi ke Curug Cilember naik mobil plat ganjil. Jam berapa biasanya rekayasa lalu lintas one way arah atas dimulai dari Simpang Gadog?',
                'views_count' => 310,
            ]
        );

        $solCilember = Comment::firstOrCreate(
            ['thread_id' => $threadCilember->id, 'user_id' => $guideUser->id],
            [
                'content' => "Jadwal rutin Satlantas Polres Bogor akhir pekan: Sistem One Way arah atas (ke Puncak) diberlakukan mulai pukul 07.30 - 11.30 WIB. Dan aturan Ganjil Genap sudah disaring tepat di exit Tol Ciawi sejak pukul 06.00 WIB.\n\nTips: Berangkat sebelum pukul 06.00 WIB dari Jakarta agar tidak terjebak antrean penyekatan di Gadog, keluar tol langsung lancar sampai Megamendung.",
                'is_solution' => true,
            ]
        );

        Comment::firstOrCreate(
            ['thread_id' => $threadCilember->id, 'parent_id' => $solCilember->id, 'user_id' => $familyUser->id],
            [
                'content' => 'Mantap, info jam 6 pagi sangat berharga! Terima kasih infonya pak guide.',
                'is_solution' => false,
            ]
        );

        // ==========================================
        // 6. ULASAN DESTINASI REALISTIS (Reviews)
        // ==========================================
        Review::firstOrCreate(
            ['destination_id' => $destBromo->id, 'user_id' => $travelerUser->id],
            [
                'rating' => 5,
                'review_text' => 'Pemandangan matahari terbit di Penanjakan Bromo benar-benar magis dan tiada duanya! Udara sangat dingin tapi terbayar lunas dengan lanskap kalderanya.',
                'visit_date' => now()->subDays(12)->toDateString(),
            ]
        );

        Review::firstOrCreate(
            ['destination_id' => $destKawahPutih->id, 'user_id' => $familyUser->id],
            [
                'rating' => 5,
                'review_text' => 'Air kawahnya sangat unik berwarna toska keputihan. Fasilitas ontang-anting sangat tertata rapi dan ramah keluarga.',
                'visit_date' => now()->subDays(20)->toDateString(),
            ]
        );

        Review::firstOrCreate(
            ['destination_id' => $destAnyer->id, 'user_id' => $defaultUser->id],
            [
                'rating' => 4,
                'review_text' => 'Pantainya landai dan ombak ramah untuk berenang santai sore hari. Tetap harus cermat memilih tempat makan seafood yang transparan.',
                'visit_date' => now()->subDays(15)->toDateString(),
            ]
        );

        Review::firstOrCreate(
            ['destination_id' => $destCilember->id, 'user_id' => $travelerUser->id],
            [
                'rating' => 5,
                'review_text' => 'Kawasan hutan pinusnya sangat tenang dan sejuk. Curug 7 dan penangkaran kupu-kupu sangat bersih serta tertata asri.',
                'visit_date' => now()->subDays(5)->toDateString(),
            ]
        );

        $this->call([
            TripySeeder::class,
            EksplorPediaSeeder::class,
            UrbanRallySeeder::class,
        ]);
    }
}
