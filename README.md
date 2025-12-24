# Company Profile Thetazine Coffee Shop

Proyek Thetazine Coffee Shop adalah sistem e-commerce sederhana yang memungkinkan pengguna untuk menjelajah produk kopi, sementara admin dapat mengelola produk dan pesanan melalui dashboard admin. Proyek ini dibangun untuk memenuhi syarat tugas akhir mata kuliah pemrograman web dengan fitur autentikasi JWT, manajemen produk, sistem pemesanan, dan manajemen stok. Dengan user interface yang friendly dan responsif, katalog produk, sistem pemesanan, dan manajemen stok. Pengguna dapat mengakses platform yang user-friendly untuk menelusuri berbagai jenis kopi, melihat informasi detail tentang produk, serta melakukan pembelian dengan mudah. Proyek ini dirancang dengan teknologi web modern dan bertujuan untuk memberikan pengalaman yang menarik bagi pelanggan dalam memilih dan membeli produk kopi berkualitas tinggi.

## Tech Stack

**Client:** Laravel, Blade Templates, TailwindCSS, Alpine.js, Axios, Leaflet (OpenStreetMap), Lucide Icons

**Server:** Laravel 12, PHP 8.2+, MySQL

**Authentication:** JWT (tymon/jwt-auth v2.2)

## Run Locally

### Clone the project

```bash
git clone https://github.com/yourusername/thetazine-coffee-shop.git
```

### Go to the project directory

```bash
cd coffee-shop-backend
```

### Install dependencies

```bash
composer install
npm install
```

### Setup environment

```bash
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
```

### Configure database

Edit file `.env` dan sesuaikan dengan database Anda:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=coffee_shop
DB_USERNAME=root
DB_PASSWORD=
```

### Migrate database

```bash
php artisan migrate:fresh --seed
```

### Start the server

```bash
npm run dev
php artisan serve
```

Server akan berjalan di `http://localhost:8000`

### Cache Issues
Jika mengalami masalah routing atau view:
```bash
php artisan route:clear
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## Default Admin Credentials

Email: admin@thetazine.com
Password: password123
Role: admin

### JWT Token Issues
Jika JWT token tidak bekerja:
```bash
php artisan jwt:secret --force
php artisan config:cache
```

### Migration Issues
Reset database dan seed ulang:
```bash
php artisan migrate:fresh --seed
```



## Spesifikasi Projek

### 1. Landing Page
   - **Menampilkan produk unggulan** (kopi premium) yang dapat dijelajahi oleh pengguna
   - **Navigasi** yang **jelas** untuk memudahkan akses ke halaman produk, tentang kami, dan kontak
   - **Hero section** dengan desain menarik dan CTA button
   - **Galeri produk** dengan grid layout responsif

### 2. Halaman Produk
   - **Tambah Produk**: Admin dapat menambahkan produk baru (kopi premium) dengan informasi seperti nama, harga, stok, deskripsi, dan URL gambar
   - **Edit Produk**: Admin dapat mengedit informasi produk yang sudah ada, termasuk URL gambar produk
   - **Hapus Produk**: Admin dapat menghapus produk yang tidak relevan (soft delete)
   - **Tampilkan Produk**: Pengguna dapat melihat daftar produk yang tersedia dengan gambar
   - **Detail Produk**: Setiap produk memiliki informasi lengkap (nama, harga, stok, deskripsi, gambar)

### 3. CRUD Orders (Pesanan)
   - **Tambah Pesanan**: User dapat membuat pesanan dengan multiple items
   - **Lihat Pesanan**: User dapat melihat history pesanan mereka, Admin dapat melihat semua pesanan
   - **Detail Pesanan**: Informasi lengkap pesanan termasuk items, total harga, dan status
   - **Update Status**: Admin dapat mengubah status pesanan (pending/completed/cancelled)
   - **Hapus Pesanan**: User dapat membatalkan pesanan, Admin dapat menghapus pesanan

### 4. Registrasi dan Login
   - **Form Registrasi**: Pengguna baru dapat mendaftar dengan memasukkan nama, email, dan password
   - **Form Login**: Pengguna sudah terdaftar dapat login menggunakan email dan password
   - **Autentikasi JWT**: Sistem menggunakan JWT token untuk autentikasi yang aman
   - **Role-based Access**: Sistem membedakan akses antara user biasa dan admin

### 5. Fitur Pemesanan
   - **Create Order**: User dapat membuat pesanan dengan memilih produk dan jumlah
   - **Automatic Stock Reduction**: Stok produk otomatis berkurang saat pesanan dibuat
   - **Order Validation**: Sistem validasi quantity tidak boleh melebihi stok
   - **Transaction Safety**: Menggunakan database transaction untuk memastikan data consistency

### 6. Dashboard Admin
   - **Manajemen Produk**: Admin dapat melihat, menambahkan, mengedit, dan menghapus produk
   - **Manajemen Pesanan**: Admin dapat melihat dan mengelola semua pesanan pelanggan
   - **Manajemen Stok**: Admin dapat melihat dan mengelola stok produk
   - **Image Preview**: Tampilan thumbnail image di tabel produk dan form modal
   - **Role Check**: Hanya admin yang dapat mengakses dashboard dengan pengecekan role di backend dan frontend
