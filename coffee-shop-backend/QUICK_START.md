# 🚀 Quick Start Guide - Thetazine Coffee Management System

## ✅ Setup Sudah Selesai!

Server Laravel sudah berjalan di: **http://localhost:8000**

## 📱 Cara Menggunakan Aplikasi

### 1. Akses Web Dashboard

Buka browser dan kunjungi:
```
http://localhost:8000
```

Anda akan otomatis diarahkan ke halaman login.

### 2. Login ke Dashboard

Gunakan kredensial default:
```
Email: admin@thetazine.com
Password: password123
```

### 3. Fitur Dashboard

Setelah login, Anda dapat:

#### Tab Products (Manajemen Produk)
- ✅ **Lihat** semua produk coffee shop
- ✅ **Tambah** produk baru (klik tombol "Add Product")
- ✅ **Edit** produk yang ada (klik "Edit")
- ✅ **Hapus** produk (klik "Delete")
- ✅ Setiap produk memiliki **UUID dan Slug** sebagai identifier unik

#### Tab Orders (Manajemen Pesanan)
- ✅ **Lihat** semua pesanan
- ✅ **Buat** pesanan baru (klik tombol "Create Order")
  - Pilih produk yang tersedia
  - Masukkan jumlah quantity
  - **Stok otomatis berkurang** saat pesanan dibuat
- ✅ **Update** status pesanan (Pending → Processing → Completed → Cancelled)
- ✅ **Hapus** pesanan
  - **Stok otomatis dikembalikan** saat pesanan dihapus/dibatalkan
- ✅ Setiap pesanan memiliki **UUID** sebagai identifier unik

## 🔌 API Endpoints

### Base URL
```
http://localhost:8000/api
```

### Authentication (Public)
```
POST /api/auth/register    - Daftar akun baru
POST /api/auth/login       - Login & dapatkan JWT token
```

### Products (Public View)
```
GET  /api/products         - Lihat semua produk
GET  /api/products/{uuid}  - Lihat detail produk (by UUID atau Slug)
```

### Protected Routes (Memerlukan JWT Token)

#### Authentication
```
GET  /api/auth/profile     - Lihat profile
POST /api/auth/refresh     - Refresh token
POST /api/auth/logout      - Logout
```

#### Products Management
```
POST   /api/products          - Tambah produk
PUT    /api/products/{uuid}   - Update produk
DELETE /api/products/{uuid}   - Hapus produk
```

#### Orders Management
```
GET    /api/orders            - Lihat pesanan
POST   /api/orders            - Buat pesanan (stok berkurang otomatis)
GET    /api/orders/{uuid}     - Detail pesanan
PUT    /api/orders/{uuid}     - Update status pesanan
DELETE /api/orders/{uuid}     - Hapus pesanan (stok dikembalikan)
```

## 📝 Contoh Request API

### 1. Login untuk Mendapatkan Token
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d "{\"email\":\"admin@thetazine.com\",\"password\":\"password123\"}"
```

Response:
```json
{
  "status": "success",
  "message": "Login successful",
  "user": {
    "id": 1,
    "name": "Admin User",
    "email": "admin@thetazine.com"
  },
  "token": "eyJ0eXAiOiJKV1QiLCJhbGci..."
}
```

### 2. Lihat Semua Produk (dengan JWT)
```bash
curl -X GET http://localhost:8000/api/products \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### 3. Tambah Produk Baru (dengan JWT)
```bash
curl -X POST http://localhost:8000/api/products \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Cold Brew",
    "description": "Smooth cold coffee",
    "price": 40000,
    "stock": 50,
    "category": "coffee",
    "is_available": true
  }'
```

### 4. Buat Pesanan (Stok Berkurang Otomatis)
```bash
curl -X POST http://localhost:8000/api/orders \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "customer_name": "John Doe",
    "customer_email": "john@example.com",
    "customer_phone": "08123456789",
    "items": [
      {"product_id": 1, "quantity": 2},
      {"product_id": 3, "quantity": 1}
    ],
    "notes": "Extra hot"
  }'
```

## 🎯 Fitur Khusus Yang Sudah Diimplementasikan

### ✅ Backend (Laravel 12)
- JWT Authentication untuk semua route CRUD
- RESTful API dengan response JSON
- Database relasi (Users, Products, Orders, OrderItems)
- UUID & Slug sebagai identifier unik
- Soft deletes untuk data safety
- Input validation pada semua endpoint

### ✅ Frontend (Tailwind CSS + Blade)
- Responsive dashboard dengan Alpine.js
- Real-time statistics cards
- Interactive modals untuk form
- CRUD tanpa reload halaman (AJAX)
- Toast notifications

### ✅ Business Logic
- **Automatic stock deduction** saat order dibuat
- **Automatic stock restoration** saat order dibatalkan/dihapus
- Stock validation sebelum order diproses
- Order status tracking (pending → processing → completed → cancelled)

## 📂 Data Sample Yang Tersedia

### Products (8 produk)
- Espresso - Rp 35,000
- Cappuccino - Rp 45,000
- Latte - Rp 42,000
- Americano - Rp 38,000
- Mocha - Rp 48,000
- Croissant - Rp 25,000
- Chocolate Muffin - Rp 30,000
- Banana Bread - Rp 28,000

### User (1 admin)
- Email: admin@thetazine.com
- Password: password123

## 🛠️ Troubleshooting

### Server tidak jalan?
```bash
cd c:\xampp\htdocs\tubescek\coffee-shop-backend
php artisan serve
```

### Database error?
```bash
php artisan migrate:fresh --seed
```

### JWT error?
```bash
php artisan jwt:secret --force
```

### Cache issues?
```bash
php artisan config:clear
php artisan cache:clear
php artisan optimize:clear
```

## 📖 Dokumentasi Lengkap

Lihat file `README_THETAZINE.md` untuk dokumentasi lengkap termasuk:
- Struktur database detail
- Penjelasan UUID vs Slug
- Konsep yang diimplementasikan
- Testing guide

## ✅ Checklist Persyaratan Tugas

- ✅ Backend: Laravel 12
- ✅ Authentication: JWT (tymon/jwt-auth)
- ✅ Frontend: Tailwind CSS dengan Laravel Blade
- ✅ Data Identifier: UUID untuk Orders, Slug untuk Products
- ✅ Register & Login: Tersedia
- ✅ Proteksi CRUD: Semua route CRUD terproteksi JWT
- ✅ Relasi 2 Tabel: Products ↔ Orders (via OrderItems)
- ✅ CRUD Lengkap: Products dan Orders
- ✅ Business Logic: Stock management otomatis

## 🎓 Cara Presentasi/Demo

1. **Tampilkan Dashboard** - http://localhost:8000
2. **Login** dengan kredensial admin
3. **Demo CRUD Products**:
   - Tambah produk baru
   - Edit produk existing
   - Hapus produk
4. **Demo CRUD Orders**:
   - Buat order baru → tunjukkan stok berkurang
   - Update status order
   - Cancel/hapus order → tunjukkan stok kembali
5. **Tunjukkan API** menggunakan Postman/Thunder Client:
   - Login untuk dapat token
   - Hit protected endpoints dengan token
6. **Tunjukkan Database**:
   - UUID pada orders table
   - Slug pada products table

## 🎉 Selamat!

Aplikasi Thetazine Coffee Management System sudah siap digunakan!

---

**Made with ❤️ for Tugas Besar**
