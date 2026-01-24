# 🎫 Web Pemesanan Tiket Wisata (Wisata Booking)

![Status: Development](https://img.shields.io/badge/Status-Development-yellow)
![Laravel: 11](https://img.shields.io/badge/Laravel-11-red)
![Filament: 3](https://img.shields.io/badge/Filament-3-orange)
![PHP: 8.2](https://img.shields.io/badge/PHP-8.2-blue)

## 📌 Deskripsi Proyek

**Wisata Booking** adalah platform berbasis web `E-Ticketing` yang dirancang untuk memfasilitasi pemesanan tiket masuk objek wisata secara online. Sistem ini menghubungkan Pengelola Wisata (Admin) dengan Pengunjung (Visitor).

Pengunjung dapat mencari destinasi, memesan tiket dengan berbagai tipe (Dewasa/Anak), melakukan pembayaran online, dan mendapatkan E-Ticket dengan QR Code. Admin memiliki dashboard untuk mengelola data destinasi, memantau transaksi, serta memvalidasi tiket pengunjung melalui fitur Scan QR.

### Target Pengguna

1. **Visitor (Pengunjung):** Mencari wisata, booking tiket, cek status pesanan, reschedule.
2. **Admin/Staff:** Mengelola konten wisata, harga tiket, dan memindai tiket (scan QR) di pintu masuk.
3. **Super Admin:** Mengelola user admin lain dan konfigurasi sistem keseluruhan.

---

## 🚀 Fitur Utama

| Aktor          | Fitur                                                                                                                                                                                                                          |
| :------------- | :----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **Visitor**    | • Pencarian & Filter Destinasi (Kategori, Lokasi)<br>• Booking Tiket (Multi-qty & Multi-type)<br>• Integrasi Pembayaran (Midtrans)<br>• Cek Status Pesanan (Order Lookup)<br>• Reschedule Pesanan<br>• Download E-Ticket (PDF) |
| **Admin**      | • Dashboard Analitik (Total Transaksi, Pendapatan)<br>• Manajemen Destinasi (CRUD: Nama, Deskripsi, Foto, Jam Buka)<br>• Manajemen Tipe Tiket & Harga<br>• Manajemen Kategori Wisata<br>• Laporan Transaksi                    |
| **Gatekeeper** | • **QR Code Validation:** Scan tiket pengunjung.<br>• Validasi real-time (Tiket valid/kadaluarsa/sudah dipakai).                                                                                                               |

---

## 🛠 Tech Stack

| Komponen               | Teknologi                      | Versi |
| :--------------------- | :----------------------------- | :---- |
| **Backend Framework**  | Laravel                        | 11.31 |
| **Admin Panel**        | FilamentPHP                    | 3.2   |
| **Frontend (Visitor)** | Blade Templates + Tailwind CSS | 3.4   |
| **Database**           | MySQL / SQLite (Dev)           | -     |
| **Payment Gateway**    | Midtrans (Snap)                | ^2.6  |
| **QR Code**            | simple-qrcode                  | ^4.2  |
| **PDF Generation**     | laravel-dompdf                 | ^3.1  |
| **Server Requirement** | PHP                            | ^8.2  |

---

## 📂 Struktur Folder

Berikut adalah struktur folder penting dalam proyek ini:

```bash
wisata-booking/
├── app/
│   ├── Filament/           # Logika Admin Panel (Resources, Pages, Widgets)
│   │   ├── Resources/      # CRUD: Destinations, Categories, Transactions
│   │   └── Widgets/        # Dashboard Stats (PadStatsOverview, dll)
│   ├── Http/
│   │   ├── Controllers/    # Controller Visitor (Order, Destination, Lookup)
│   │   └── Middleware/     # Auth & Role checks
│   ├── Models/             # Eloquent Models (User, Destination, TicketTransaction)
│   └── Mail/               # Template Email Tiket
├── database/
│   ├── migrations/         # Definisi Skema Database
│   └── seeders/            # Data Dummy
├── resources/
│   ├── views/              # Tampilan Frontend (Blade)
│   └── css/                # Tailwind Config
├── routes/
│   ├── web.php             # Route Visitor & Callback
│   └── api.php             # (Opsional) API endpoint
└── .env.example            # Template Environment Variables
```

---

## 💾 Dokumentasi Database

### **ERD Ringkas (Entity Relationship Diagram)**

- **Destinations** memiliki banyak **Transaction Items** (melalui Ticket Types).
- **Users** (Admin) mengelola **Destinations**.
- **Ticket Transactions** (Order) memiliki banyak **Transaction Items** (Detail tiket yang dibeli).
- **Ticket Transactions** memiliki **Ticket Scans** (History scan QR).

### **Daftar Tabel Utama**

#### 1. `users` (Pengguna Sistem)

| Kolom      | Tipe   | Keterangan             |
| :--------- | :----- | :--------------------- |
| `id`       | BIGINT | PK                     |
| `name`     | STRING | Nama lengkap           |
| `email`    | STRING | Email (Login)          |
| `password` | STRING | Hashed Password        |
| `role`     | STRING | `admin`, `super_admin` |

#### 2. `destinations` (Objek Wisata)

| Kolom             | Tipe   | Keterangan                   |
| :---------------- | :----- | :--------------------------- |
| `id`              | BIGINT | PK                           |
| `category_id`     | FK     | Relasi ke tabel `categories` |
| `user_id`         | FK     | Admin pengelola              |
| `name`            | STRING | Nama Wisata                  |
| `slug`            | STRING | URL friendly name (Unique)   |
| `description`     | TEXT   | Deskripsi lengkap            |
| `location`        | STRING | Alamat / Lokasi              |
| `operating_hours` | STRING | Jam operasional              |

#### 3. `destination_ticket_types` (Variasi Harga Tiket)

| Kolom            | Tipe    | Keterangan                     |
| :--------------- | :------ | :----------------------------- |
| `id`             | BIGINT  | PK                             |
| `destination_id` | FK      | Relasi ke `destinations`       |
| `name`           | STRING  | Nama Paket (mis: Dewasa, Anak) |
| `price`          | DECIMAL | Harga satuan                   |

#### 4. `ticket_transactions` (Transaksi Utama)

| Kolom            | Tipe      | Keterangan                                |
| :--------------- | :-------- | :---------------------------------------- |
| `id`             | BIGINT    | PK                                        |
| `ticket_code`    | STRING    | Kode Unik Tiket (di QR)                   |
| `order_id`       | STRING    | ID Order Midtrans                         |
| `name`           | STRING    | Nama Pemesan                              |
| `email`          | STRING    | Email Pemesan                             |
| `visit_date`     | DATE      | Tanggal Kunjungan                         |
| `total_amount`   | DECIMAL   | Total Pembayaran                          |
| `payment_status` | ENUM      | `pending`, `succeeded`, `failed`          |
| `ticket_status`  | ENUM      | `new`, `used`                             |
| `qr_secret`      | STRING    | Secret key untuk validasi tanda tangan QR |
| `used_at`        | TIMESTAMP | Waktu tiket discan/dipakai                |
| `scanned_by`     | INT       | ID User yang melakukan scan               |

#### 5. `transaction_items` (Detail Item Transaksi)

| Kolom                        | Tipe    | Keterangan                      |
| :--------------------------- | :------ | :------------------------------ |
| `id`                         | BIGINT  | PK                              |
| `ticket_transaction_id`      | FK      | Relasi ke `ticket_transactions` |
| `destination_ticket_type_id` | FK      | Relasi ke tipe tiket            |
| `name`                       | STRING  | Snapshot Nama Tiket             |
| `price_per_unit`             | DECIMAL | Snapshot Harga saat beli        |
| `quantity`                   | INT     | Jumlah tiket                    |
| `total_price`                | DECIMAL | Subtotal (price \* qty)         |

---

## 🔗 Dokumentasi Routes (Visitor)

| Method | URI                        | Nama Route           | Controller/Fungsi                |
| :----- | :------------------------- | :------------------- | :------------------------------- |
| `GET`  | `/`                        | `home`               | `HomeController@index`           |
| `GET`  | `/destinasi`               | `destinations.index` | `DestinationController@index`    |
| `GET`  | `/destinasi/{slug}/detail` | `destinations.show`  | `DestinationController@show`     |
| `GET`  | `/order-form/{slug}`       | `order.form`         | `OrderController@showForm`       |
| `POST` | `/order/process`           | `order.processOrder` | `OrderController@processOrder`   |
| `GET`  | `/pesanan/cek`             | `orders.lookup.form` | `OrderLookupController@showForm` |
| `POST` | `/tickets/scan`            | `admin.tickets.scan` | `TicketScanController@scan`      |

_Catatan: Route Admin panel di-handle otomatis oleh Filament di `/admin`._

---

## 🔄 Alur Kerja Sistem (Flow)

### 1. Visitor Flow (Pemesanan & Download)

1. **Pilih Wisata:** Visitor membuka halaman `/destinasi`, memilih lokasi, dan melihat detail.
2. **Isi Form Order:** Klik "Beli Tiket", memilih tanggal kunjungan, memilih tipe tiket (Dewasa/Anak), dan memasukkan jumlah.
3. **Pembayaran:** Sistem memanggil **Midtrans Snap**.
    - Input Nama, Email, No HP.
    - Selesaikan pembayaran (VA/E-Wallet).
4. **Sukses:** Status pembayaran diupdate (via callback/cek manual).
5. **Dapat Tiket:** Visitor diarahkan ke halaman "Success".
    - Link download E-Ticket dikirim ke email.
    - Atau bisa cek manual di menu "Cek Pesanan" menggunakan email/No HP.

### 2. Admin Flow (Manajemen)

1. **Login:** Akses `/admin/login`.
2. **Manage Data:**
    - Tambah Destinasi Baru (Upload foto, set lokasi).
    - Atur `DestinationTicketType` (Misal: Reguler Rp50k, VIP Rp100k).
3. **Cek Transaksi:** Melihat daftar transaksi masuk di menu "Transactions".
4. **Analitik:** Melihat grafik pendapatan harian di Dashboard.

### 3. Validation Flow (Scan QR)

1. **Gatekeeper:** Membuka alat scanner (atau fitur scan di Admin Panel).
2. **Scan:** Scan QR Code tamu.
3. **Validasi System:**
    - Cek `ticket_code` di database.
    - Cek `qr_secret` Signature (Validitas QR).
    - Cek `visit_date` (Apakah hari ini?).
    - Cek `ticket_status` (Apakah sudah `used`?).
4. **Hasil:**
    - ✅ **Valid:** Akses diberikan, status update jadi `used`.
    - ❌ **Expired/Used:** Peringatan muncul "Tiket Sudah Digunakan".

---

## 💻 Instalasi & Setup

### Prasyarat

- PHP >= 8.2
- Composer
- Node.js & NPM
- Database (MySQL/MariaDB)

### Langkah Instalasi (Local)

1. **Clone Repository**

    ```bash
    git clone <REPO_URL>
    cd wisata-booking
    ```

2. **Install Dependencies**

    ```bash
    composer install
    npm install
    ```

3. **Setup Environment**
   Salin file `.env.example` ke `.env` dan konfigurasi database serta Midtrans.

    ```bash
    cp .env.example .env
    ```

    **Update .env:**

    ```ini
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_DATABASE=wisata_booking
    DB_USERNAME=root
    DB_PASSWORD=

    # Midtrans Config
    MIDTRANS_SERVER_KEY=SB-Mid-server-xxxx
    MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxx
    MIDTRANS_IS_PRODUCTION=false
    ```

4. **Generate Key & Storage Link**

    ```bash
    php artisan key:generate
    php artisan storage:link
    ```

5. **Migrasi Database**

    ```bash
    php artisan migrate --seed
    ```

    _(Pastikan seeder membuat user admin default, jika tidak buat manual via `php artisan make:filament-user`)_

6. **Build Assets**

    ```bash
    npm run build
    ```

7. **Jalankan Server**
    ```bash
    php artisan serve
    ```
    Akses di: `http://localhost:8000`

### Akun Default (Seeder)

Jika menjalankan `db:seed`, gunakan akun berikut untuk login:

- **Super Admin**: `superadmin@gmail.com` / `password`
- **Admin Wisata**: `admin1@gmail.com` / `password` (tersedia admin1 - admin46)

---

## 🔐 Security Notes

1.  **Midtrans Signature:** Jangan pernah mengekspos `MIDTRANS_SERVER_KEY` di frontend code.
2.  **QR Validation:** QR Code mengandung digital signature (`hash_hmac`) menggunakan `qr_secret` yang unik per transaksi. Ini mencegah pemalsuan tiket dengan membuat QR Code sendiri.
3.  **Authorization:** Akses halaman `/admin` dilindungi middleware Filament. Akses download tiket (`/ticket/download/...`) dilindungi Signed URL agar tidak bisa ditebak orang lain.

---

## ❓ Troubleshooting (FAQ)

| Masalah                          | Kemungkinan Penyebab               | Solusi                                                                                  |
| :------------------------------- | :--------------------------------- | :-------------------------------------------------------------------------------------- |
| **Error 500 saat Upload Gambar** | Permission folder storage salah    | Jalankan `php artisan storage:link` dan pastikan folder `storage/app/public` writeable. |
| **Midtrans Transaction Failed**  | Server Key salah / Mode Production | Cek `.env` pastikan Key sesuai mode (Sandbox/Production).                               |
| **QR Code Invalid Signature**    | Secret Key berubah / Data korup    | Pastikan `qr_secret` di DB tidak null. Regenerate jika perlu.                           |
| **Tampilan Admin Berantakan**    | Assets belum diload                | Jalankan `npm run build` atau `npm run dev`.                                            |

---

## 🤝 Kontribusi

Silakan buat **Pull Request** untuk fitur baru atau perbaikan bug.

1.  Fork repo ini.
2.  Buat branch fitur (`git checkout -b fitur-baru`).
3.  Commit perubahan (`git commit -m 'Menambah fitur X'`).
4.  Push ke branch (`git push origin fitur-baru`).
5.  Buat Pull Request.

---

## 📝 Checklist Data MISSING

_Bagian ini untuk pengembang selanjutnya melengkapi dokumentasi._

| Bagian          | Data yang Dibutuhkan | Cara Mendapatkan  | Status     |
| :-------------- | :------------------- | :---------------- | :--------- |
| **Demo URL**    | Link live website    | Deploy ke hosting | 🔴 MISSING |
| **Screenshots** | Gambar UI Aplikasi   | Screenshot manual | 🔴 MISSING |

---

**Author:** Tim Capstone Project
**Date:** Januari 2026
