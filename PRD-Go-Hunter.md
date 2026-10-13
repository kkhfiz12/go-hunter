# Product Requirement Document (PRD) — Go Hunter

## 1. Executive Summary
* **Nama Produk:** Go Hunter
* **Tipe Produk:** Web-based Super-App (Direktori Wisata, Forum Komunitas, & Travel Buddy)
* **Teknologi Utama:** Laravel (MVC / Blade / Eloquent ORM), MySQL, Tailwind CSS, Alpine.js / Livewire.
* **Tujuan:** Menyediakan platform terpadu penyedia panduan logistik independen, proteksi transparansi harga lokal (*anti-getok*), forum Q&A situasional real-time, serta pencarian rekan perjalanan (*travel buddy*).

---

## 2. User Persona & Problem Statement
1. **Solo / Budget Backpacker:** Kesulitan mencari info transportasi lokal terkini dan sering ragu bepergian sendiri karena biaya sewa armada/guide yang mahal jika tidak patungan.
2. **First-time Traveler:** Khawatir terkena pemerasan harga (*getok harga*) di parkir atau warung sekitar destinasi wisata.
3. **Local Explorer / Kontributor:** Ingin membagikan tips jalur aman, kondisi cuaca riil, serta membantu sesama traveler.

---

## 3. Fitur Utama & Functional Requirements

### 3.1. Comprehensive Directory
* Menampilkan daftar destinasi dengan filter kategori (Alam, Budaya, Kuliner, Bahari), lokasi/kabupaten, dan rentang tiket.
* Informasi terverifikasi: Jam operasional, tarif resmi parkir (motor/mobil/bus), titik pemberhentian transportasi umum terdekat (stasiun/halte/angkot).
* Integrasi peta statis / embed koordinat GPS.

### 3.2. Crowdsourced Price Checker (Anti-Getok)
* Modul sub-halaman di tiap destinasi: Katalog harga wajar (makanan sekitar, sewa perahu, jasa foto, retribusi tidak resmi).
* Form pelaporan harga oleh pengguna:
  * Input item/layanan, harga yang dibayar, tanggal transaksi, bukti struk/foto nota (opsional), status kewajaran (Normal / Wajar / Pemerasan).
* Sistem rating kewajaran harga destinasi berdasarkan akumulasi laporan pengguna.

### 3.3. Community Q&A Feed (Forum)
* Pola diskusi berbasis *thread* per destinasi atau per regional.
* Fitur *upvote/downvote* atau *helpful counter* untuk jawaban terpercaya.
* Kategori thread: "Kondisi Terkini" (banjir, longsor, ramai/sepi), "Transportasi & Jalur", "Tips & Trik".

### 3.4. Travel Buddy Board
* Papan pengumuman pencarian teman jalan / sewa kendaraan bersama (*cost-sharing*).
* Atribut pencarian:
  * Destinasi tujuan, tanggal keberangkatan, slot orang yang dibutuhkan, estimasi bujet patungan per orang, kriteria teman (misal: "wajib bisa nyetir ganti-gantian").
* Status postingan: `Open`, `Full`, `Completed`, `Cancelled`.
* Direct Connect: Tombol kontak via WhatsApp API link atau direct messaging internal.

### 3.5. Verified Visit Reviews & Trip Documentation
* Pengguna dapat memberikan rating (1-5) dan ulasan pengalaman berkunjung.
* Pengguna dapat mengunggah bukti kunjungan (foto tiket fisik/gelang masuk atau foto dokumentasi di lokasi).
* Ulasan yang menyertakan bukti valid akan mendapatkan badge "Verified Visitor" dan menambah reputation_points akun pengguna.

---

## 4. Skema Database & Relasi Model (Laravel Architecture)

### 4.1. Tabel `users`
* `id` (PK - BigIncrements)
* `name` (string)
* `email` (string, unique)
* `password` (string)
* `avatar` (string, nullable)
* `bio` (text, nullable)
* `phone_number` (string, nullable)
* `reputation_points` (integer, default: 0)
* `timestamps`

### 4.2. Tabel `destinations`
* `id` (PK - BigIncrements)
* `name` (string)
* `slug` (string, unique)
* `city` (string)
* `province` (string)
* `address` (text)
* `latitude` (decimal, 10, 7, nullable)
* `longitude` (decimal, 10, 7, nullable)
* `description` (longText)
* `operating_hours` (json, nullable)
* `official_ticket_price` (decimal, 12, 2, default: 0)
* `parking_fee_motor` (decimal, 10, 2, default: 0)
* `parking_fee_car` (decimal, 10, 2, default: 0)
* `nearest_transit_info` (text, nullable)
* `cover_image` (string, nullable)
* `timestamps`

### 4.3. Tabel `price_reports`
* `id` (PK - BigIncrements)
* `destination_id` (FK -> destinations.id, onDelete cascade)
* `user_id` (FK -> users.id, onDelete cascade)
* `item_name` (string)
* `price_paid` (decimal, 12, 2)
* `fairness_level` (enum: 'cheap', 'fair', 'overpriced')
* `proof_image` (string, nullable)
* `notes` (text, nullable)
* `timestamps`

### 4.4. Tabel `threads` & `comments`
* **`threads`**: 
  * `id` (PK - BigIncrements)
  * `user_id` (FK -> users.id, onDelete cascade)
  * `destination_id` (FK -> destinations.id, nullable, onDelete set null)
  * `title` (string)
  * `slug` (string, unique)
  * `content` (longText)
  * `category` (enum: 'general', 'traffic_transit', 'weather_update', 'tips')
  * `views_count` (unsignedInteger, default: 0)
  * `timestamps`
* **`comments`**: 
  * `id` (PK - BigIncrements)
  * `thread_id` (FK -> threads.id, onDelete cascade)
  * `user_id` (FK -> users.id, onDelete cascade)
  * `parent_id` (FK -> comments.id, nullable, onDelete cascade)
  * `content` (text)
  * `is_solution` (boolean, default: false)
  * `timestamps`

### 4.5. Tabel `buddy_requests`
* `id` (PK - BigIncrements)
* `user_id` (FK -> users.id, onDelete cascade)
* `destination_id` (FK -> destinations.id, onDelete cascade)
* `title` (string)
* `description` (text)
* `departure_date` (date)
* `return_date` (date, nullable)
* `slots_needed` (integer)
* `slots_filled` (integer, default: 1)
* `estimated_budget_per_person` (decimal, 12, 2)
* `status` (enum: 'open', 'full', 'departed', 'cancelled', default: 'open')
* `contact_link` (string)
* `timestamps`

### 4.6. Tabel `reviews`
* `id` (PK - BigIncrements)
* `destination_id` (FK -> destinations.id, onDelete cascade)
* `user_id` (FK -> users.id, onDelete cascade)
* `rating` (tinyInteger, 1-5)
* `review_text` (text)
* `visit_date` (date, nullable)
* `proof_image` (string, nullable) — foto bukti tiket masuk, foto di lokasi, atau dokumentasi kunjungan
* `is_verified_visit` (boolean, default: false) — penanda ulasan terverifikasi jika menyertakan bukti
* `timestamps`

---

## 5. Non-Functional Requirements & Security
* **Rate Limiting:** Rate limiting pada submission form forum dan laporan harga (`throttle:6,1` untuk create post).
* **SEO Friendly:** Slug otomatis menggunakan Laravel Eloquent Sluggable.
* **Mobile-First Responsive:** Desain responsif Tailwind CSS yang optimal di layar smartphone.
* **Storage Optimization:** Resize & compress gambar bukti struk / foto sebelum disimpan ke storage.
