# DESIGN SYSTEM & UI/UX SPECIFICATION (DESAIN.MD) — GO HUNTER

> **Project:** Go Hunter  
> **Tech Stack:** Laravel 13, Blade Components, Tailwind CSS v3/v4, Alpine.js / Livewire, MySQL  
> **Type:** Web-based Super-App (Direktori Wisata, Crowd-sourced Price Checker / Anti-Getok, Q&A Forum, & Travel Buddy)  
> **Design Philosophy:** Clean, Trustworthy, Modern Travel Directory, Community-driven, Mobile-First.

---

## 1. Brand Identity & Design Direction

### 1.1 Brand Essence
* **Brand Name:** Go Hunter
* **Tagline:** *Jelajah Tanpa Ragu, Info Nyata Tanpa Tipu.*
* **Karakteristik Visual:** Terinspirasi dari gaya modern travel portal (seperti referensi visual Wanderly) yang dipadukan dengan kepraktisan crowd-sourced directory. Bersih (*crisp*), ramah (*approachable*), transparan, dan dapat diandalkan (*high trust factor*).
* **Nuansa Emosi:** Rasa aman bagi solo traveler, kejelasan informasi biaya lokal, serta semangat eksplorasi komunitas.

---

## 2. Color Palette & Token System

Menggunakan sistem token Tailwind CSS yang disematkan pada `tailwind.config.js` atau root CSS.

### 2.1 Primary & Accent Palette
* **Primary (Ocean Azure):** `#0284C7` (Tailwind `sky-600`) — Mewakili petualangan, laut, langit terbuka, dan ketenangan.
* **Primary Dark (Deep Sea):** `#0369A1` (Tailwind `sky-700`) — State hover & active CTA.
* **Primary Light (Sky Tint):** `#E0F2FE` (Tailwind `sky-100`) — Background badge, selected tabs, pill icon wrappers.
* **Brand Accent (Sunrise Amber / Trust):** `#F59E0B` (Tailwind `amber-500`) — Bintang rating, badge rekomendasi, penanda highlight harga wajar.
* **Secondary Brand (Emerald Compass):** `#10B981` (Tailwind `emerald-500`) — Indikator status "Open" travel buddy, harga normal/murah (*fair price*).

### 2.2 Semantic & Anti-Getok System Colors
Khusus untuk modul **Crowdsourced Price Checker (Anti-Getok)**:
* **Fair / Normal Price:** `#10B981` (`emerald-500`), Bg: `#ECFDF5` (`emerald-50`)
* **Reasonable / Tolerable Price:** `#F59E0B` (`amber-500`), Bg: `#FFFBEB` (`amber-50`)
* **Overpriced / Getok Alert:** `#EF4444` (`red-500`), Bg: `#FEF2F2` (`red-50`)

### 2.3 Neutral & Surface Hierarchy
* **App Canvas Background:** `#F8FAFC` (`slate-50`)
* **Surface / Card Background:** `#FFFFFF` (`white`)
* **Surface Subdued / Inactive:** `#F1F5F9` (`slate-100`)
* **Border & Dividers:** `#E2E8F0` (`slate-200`)
* **Text Main / Headings:** `#0F172A` (`slate-900`)
* **Text Body / Standard:** `#334155` (`slate-700`)
* **Text Muted / Subtitle:** `#64748B` (`slate-500`)

---

## 3. Typography Hierarchy

Sistem tipografi menggunakan kombinasi modern sans-serif dengan aksen display serif/script opsional untuk heading hero:

* **Primary Font Family:** `Inter`, `Plus Jakarta Sans`, atau `ui-sans-serif, system-ui` (Legibility tinggi untuk angka tiket, list harga, dan forum Q&A).
* **Accent/Display Font (Opsional untuk Sub-heading Hero):** `Caveat` atau font script halus untuk aksentuasi kata kunci seperti *"Jelajah Bersama"*.

### Skala Tipografi (Tailwind Equivalents):
1. **Hero Title (Display):** `text-4xl lg:text-5xl font-extrabold tracking-tight text-slate-900 leading-tight`
2. **Section Heading (H2):** `text-2xl lg:text-3xl font-bold text-slate-900 tracking-tight`
3. **Card Title / Sub-section (H3):** `text-lg lg:text-xl font-semibold text-slate-900`
4. **Body Default:** `text-sm sm:text-base font-normal text-slate-700 leading-relaxed`
5. **Caption / Meta Data / Badge:** `text-xs font-medium text-slate-500 uppercase tracking-wider`
6. **Price & Numerical Focus:** `text-base sm:text-lg font-bold text-sky-600`

---

## 4. Layout Architecture & Master Shell

### 4.1 Global Navigation (Navbar)
* **Top Header:** Clean white sticky header (`bg-white/95 backdrop-blur shadow-sm border-b border-slate-100`).
* **Logo:** *Go Hunter* (Icon kompas / penanda lokasi bernuansa dinamis + wordmark tebal).
* **Nav Links:** 
  * Destinasi (*Comprehensive Directory*)
  * Cek Harga Wajar (*Anti-Getok Index*)
  * Teman Jalan (*Travel Buddy Board*)
  * Diskusi (*Community Q&A Feed*)
* **Right Section:** Tombol CTA pencarian cepat, Tombol "+ Buat Postingan / Lapor Harga", notifikasi, dan Avatar Profil pengguna / Auth Buttons (`Masuk` & `Daftar`).
* **Mobile Navigation:** Responsive hamburger drawer dengan tab bar mengambang di bawah (*bottom floating nav*) untuk akses cepat 4 pilar utama di smartphone.

### 4.2 Hero Section (Berdasarkan Referensi Visual)
* **Visual:** Full-width photographic backdrop destinasi lokal Indonesia (misal: Raja Ampat, Bromo, atau Labuan Bajo) dengan overlay gradasi halus.
* **Tagline & CTA:** *"Temukan Destinasi Nyata, Rencanakan Rute Hemat & Aman"*.
* **Floating Super-Search Card:**
  * Tab Selector: `Destinasi`, `Cek Harga Warung/Parkir`, `Cari Travel Buddy`, `Tanya Komunitas`.
  * Field Filter: Destinasi/Kota, Kategori Wisata, Rentang Bujet, Tanggal Perjalanan.
  * CTA Button: `Cari Sekarang` dengan icon search tebal.

### 4.3 Footer
* Background: `#0B192C` atau `#0F172A` (Navy slate gelap).
* Kolom: Brand info & misi transparansi wisata, Quick Links Direktori, Edukasi Anti-Getok & Form Lapor, Social Links & Newsletter Subscribe.

---

## 5. Spesifikasi Komponen UI Berdasarkan Fitur PRD

### 5.1 Comprehensive Directory (Katalog Wisata)
* **Card Destinasi:**
  * Rasio foto `4:3` atau `16:9` dengan border-radius `rounded-2xl`, overflow-hidden, dan zoom hover effect.
  * Badges di atas foto: Kategori (e.g. `Alam`, `Bahari`), Status Tiket Terverifikasi.
  * Meta Info di bawah foto: Nama Tempat, Kota/Kabupaten, Estimasi Tiket Masuk Resmi (`Rp XX.XXX`), dan icon parkir motor/mobil resmi.
  * Rating bintang & jumlah ulasan.

### 5.2 Crowdsourced Price Checker (Modul Anti-Getok)
* **Price Meter / Fairness Score Widget:**
  * Barometer visual persentase laporan wajar vs kemahalan di halaman detail destinasi.
  * Indikator status besar: e.g. *"94% Pengunjung Melaporkan Harga Wajar"*.
* **Tabel / Feed Katalog Harga Lokal:**
  * Item Name (e.g. "Kelapa Muda", "Sewa Tikar", "Parkir Mobil Resmi vs Liar").
  * Rata-rata Harga yang Dilaporkan (`Rp 15.000`).
  * Tag status: `Wajar` (badge hijau) atau `Waspada Getok` (badge merah).
  * Tombol modal: `+ Laporkan Biaya yang Kamu Bayar` (Form upload struk, tanggal, dan tingkat kewajaran).

### 5.3 Travel Buddy Board (Papan Teman Jalan)
* **Buddy Card Layout:**
  * Header kartu: Avatar creator, nama, reputasi poin, dan waktu posting.
  * Rute / Destinasi & Tanggal Keberangkatan (`14 - 16 Des 2025`).
  * Slot Meter: `Sisa 2 dari 4 Kursi` (Progress bar interaktif).
  * Bujet Patungan per Orang: Highlighted badge (e.g. `Estimasi Rp 350.000 / orang`).
  * Tag Kriteria: `Bisa Nyetir Gantian`, `Non-Smoking`, `Backpacker Style`.
  * Action CTA: Tombol `Join via WhatsApp` atau `Hubungi Penggagas`.

### 5.4 Community Q&A Feed (Forum Situasional)
* **Thread List Item:**
  * Kategori Pill: `[Kondisi Terkini]`, `[Transportasi]`, `[Tips & Jalur]`.
  * Judul diskusi tebal dengan preview isi teks 2 baris.
  * Status label: Badge *"Terkonfirmasi Warga Lokal"* atau *"Ada Jawaban Solusi"*.
  * Upvote Counter button di sebelah kiri (model Reddit/StackOverflow).
  * Counter respons dan view.

---

## 6. Laravel 13 & Blade Component Architecture

Struktur komponen Blade yang direkomendasikan pada project:

```text
resources/views/
├── components/
│   ├── layouts/
│   │   ├── app.blade.php             # Main wrapper (Meta, Navbar, Footer, Flash alerts)
│   │   └── auth.blade.php            # Wrapper login/register
│   ├── navigation/
│   │   ├── navbar.blade.php          # Sticky top bar
│   │   ├── mobile-nav.blade.php      # Bottom drawer / floating bar
│   │   └── footer.blade.php          # Footer links
│   ├── cards/
│   │   ├── destination-card.blade.php
│   │   ├── price-report-card.blade.php
│   │   ├── buddy-card.blade.php
│   │   └── thread-card.blade.php
│   ├── ui/
│   │   ├── badge.blade.php           # Status pills (Wajar, Getok, Open, Full)
│   │   ├── button.blade.php          # Primary, Secondary, Outline, Danger
│   │   ├── input.blade.php           # Form text field with error handling
│   │   ├── modal.blade.php           # Alpine.js powered modal dialog
│   │   └── rating-stars.blade.php    # Dynamic star renderer
│   └── destination/
│       ├── price-fairness-meter.blade.php
│       └── transit-info-box.blade.php
```

### Contoh Blade Component Syntax (Contoh: `badge.blade.php`):
```blade
@props(['type' => 'info'])

@php
$classes = match($type) {
    'fair' => 'bg-emerald-50 text-emerald-700 border-emerald-200 ring-emerald-600/20',
    'warning' => 'bg-amber-50 text-amber-700 border-amber-200 ring-amber-600/20',
    'overpriced' => 'bg-red-50 text-red-700 border-red-200 ring-red-600/20',
    'primary' => 'bg-sky-50 text-sky-700 border-sky-200 ring-sky-600/20',
    default => 'bg-slate-50 text-slate-700 border-slate-200 ring-slate-600/20',
};
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-x-1.5 rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset border $classes"]) }}>
    {{ $slot }}
</span>
```

---

## 7. Responsive Breakpoints & Interaction States

* **Mobile (`< 640px`):** Single column feed, horizontal scrolling tags filter, bottom fixed navigation bar, modal form drawer dari bawah (*bottom-sheet style*).
* **Tablet (`640px - 1024px`):** 2-column grid untuk katalog wisata & travel buddy cards, sidebar filter collapsible.
* **Desktop (`≥ 1024px`):** 3 atau 4-column card grid, dual layout (main content + right sticky sidebar untuk trending diskusi dan pengumuman travel buddy terkini).
* **Hover & Micro-interactions:** Smooth transition (`transition-all duration-200 ease-in-out`), subtle shadow elevation (`hover:shadow-md hover:-translate-y-0.5`), dan feedback visual instan saat tombol upvote / bookmark diklik menggunakan Alpine.js.
