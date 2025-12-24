# Thetazine Coffee - Coffee Shop Management System

## 📋 Deskripsi Proyek

Sistem Manajemen Coffee Shop berbasis web yang dibangun dengan **Laravel 12** dan **JWT Authentication**. Aplikasi ini menyediakan dashboard untuk mengelola produk dan pesanan dengan fitur CRUD lengkap.

## 🎯 Fitur Utama

### 1. **Autentikasi & Authorization**
- ✅ Register pengguna baru
- ✅ Login dengan JWT (JSON Web Token)
- ✅ Logout dan invalidasi token
- ✅ Proteksi route menggunakan JWT middleware

### 2. **Manajemen Produk (CRUD)**
- ✅ Tambah produk baru (Create)
- ✅ Lihat daftar produk (Read)
- ✅ Edit produk (Update)
- ✅ Hapus produk (Delete)
- ✅ UUID & Slug sebagai identifier unik
- ✅ Kategori produk (Coffee, Pastry, Snack, dll)
- ✅ Manajemen stok produk

### 3. **Manajemen Pesanan (CRUD)**
- ✅ Buat pesanan baru (Create)
- ✅ Lihat daftar pesanan (Read)
- ✅ Update status pesanan (Update)
- ✅ Hapus pesanan (Delete)
- ✅ UUID sebagai identifier unik
- ✅ Relasi dengan tabel Products (Many-to-Many through OrderItems)
- ✅ **Pengurangan stok otomatis** saat pesanan dibuat
- ✅ **Pengembalian stok otomatis** saat pesanan dibatalkan/dihapus

### 4. **Dashboard Interaktif**
- ✅ Statistik real-time (Total Produk, Pesanan, Revenue)
- ✅ Tampilan tabel dengan Tailwind CSS
- ✅ Modal untuk form Create/Edit
- ✅ Responsive design

## 🛠️ Teknologi yang Digunakan

### Backend:
- **Laravel 12** - PHP Framework
- **JWT Auth (tymon/jwt-auth)** - JSON Web Token untuk autentikasi
- **SQLite** - Database (dapat diganti MySQL/PostgreSQL)

### Frontend:
- **Laravel Blade** - Template engine
- **Tailwind CSS** - CSS Framework
- **Alpine.js** - JavaScript framework untuk interaktivitas
- **Axios** - HTTP client untuk API calls
- **Lucide Icons** - Icon library

## 📦 Struktur Database

### Tabel: users
- `id` (Primary Key)
- `name`
- `email` (Unique)
- `password` (Hashed)
- `timestamps`

### Tabel: products
- `id` (Primary Key)
- `uuid` (Unique) - **Identifier untuk URL**
- `slug` (Unique) - **Identifier untuk SEO-friendly URL**
- `name`
- `description`
- `price`
- `stock`
- `category`
- `image_url`
- `is_available`
- `timestamps`
- `soft_deletes`

### Tabel: orders
- `id` (Primary Key)
- `uuid` (Unique) - **Identifier untuk URL**
- `user_id` (Foreign Key → users)
- `customer_name`
- `customer_email`
- `customer_phone`
- `total_amount`
- `status` (pending, processing, completed, cancelled)
- `notes`
- `timestamps`
- `soft_deletes`

### Tabel: order_items
- `id` (Primary Key)
- `order_id` (Foreign Key → orders)
- `product_id` (Foreign Key → products)
- `quantity`
- `price` (harga saat pesanan dibuat)
- `subtotal`
- `timestamps`

## 🚀 Instalasi & Setup

### Prasyarat
- PHP >= 8.2
- Composer
- Node.js & NPM
- XAMPP (atau web server lain)

### Langkah-langkah Instalasi:

1. **Clone/Navigate ke direktori proyek**
```bash
cd c:\xampp\htdocs\tubescek\coffee-shop-backend
```

2. **Install dependencies PHP**
```bash
composer install
```

3. **Install dependencies JavaScript**
```bash
npm install
```

4. **Generate Application Key**
```bash
php artisan key:generate
```

5. **Generate JWT Secret**
```bash
php artisan jwt:secret
```

6. **Buat database SQLite**
```bash
php artisan migrate
```

7. **Seed database dengan data sample**
```bash
php artisan db:seed
```

8. **Jalankan server Laravel**
```bash
php artisan serve
```

9. **Akses aplikasi**
- Web Dashboard: http://localhost:8000
- API Endpoint: http://localhost:8000/api

## 👤 Default Login Credentials

```
Email: admin@thetazine.com
Password: password123
```

## 📡 API Endpoints

### Public Endpoints (Tidak memerlukan autentikasi)

#### Authentication
```
POST   /api/auth/register     - Register user baru
POST   /api/auth/login        - Login dan dapatkan token JWT
```

#### Products (Public View)
```
GET    /api/products          - Lihat semua produk
GET    /api/products/{uuid}   - Lihat detail produk
```

### Protected Endpoints (Memerlukan JWT Token)

#### Authentication
```
GET    /api/auth/profile      - Lihat profile user
POST   /api/auth/refresh      - Refresh JWT token
POST   /api/auth/logout       - Logout (invalidate token)
```

#### Products Management
```
POST   /api/products          - Tambah produk baru
PUT    /api/products/{uuid}   - Update produk
DELETE /api/products/{uuid}   - Hapus produk
```

#### Orders Management
```
GET    /api/orders            - Lihat semua pesanan user
POST   /api/orders            - Buat pesanan baru
GET    /api/orders/{uuid}     - Lihat detail pesanan
PUT    /api/orders/{uuid}     - Update status pesanan
DELETE /api/orders/{uuid}     - Hapus pesanan
```

### Cara Menggunakan API dengan JWT

1. **Login untuk mendapatkan token**
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@thetazine.com","password":"password123"}'
```

2. **Gunakan token untuk request berikutnya**
```bash
curl -X GET http://localhost:8000/api/products \
  -H "Authorization: Bearer YOUR_JWT_TOKEN_HERE"
```

## 📝 Contoh Request API

### Register User
```json
POST /api/auth/register
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

### Create Product
```json
POST /api/products
Authorization: Bearer {token}
{
    "name": "Cappuccino",
    "description": "Italian coffee with foam",
    "price": 45000,
    "stock": 100,
    "category": "coffee",
    "image_url": "https://example.com/image.jpg",
    "is_available": true
}
```

### Create Order (Stok akan berkurang otomatis)
```json
POST /api/orders
Authorization: Bearer {token}
{
    "customer_name": "Jane Doe",
    "customer_email": "jane@example.com",
    "customer_phone": "08123456789",
    "items": [
        {
            "product_id": 1,
            "quantity": 2
        },
        {
            "product_id": 3,
            "quantity": 1
        }
    ],
    "notes": "Extra hot please"
}
```

## 🎨 Fitur Khusus - Identifiers

### UUID (Universally Unique Identifier)
- Digunakan untuk: **Orders**
- Contoh: `550e8400-e29b-41d4-a716-446655440000`
- Keuntungan: Unik secara global, tidak dapat ditebak

### Slug
- Digunakan untuk: **Products**
- Contoh: `cappuccino-abc123`
- Keuntungan: SEO-friendly, mudah dibaca manusia

### Akses URL:
```
http://localhost:8000/api/products/cappuccino-abc123
http://localhost:8000/api/orders/550e8400-e29b-41d4-a716-446655440000
```

## 🔒 Keamanan

1. **JWT Authentication** - Semua route CRUD dilindungi dengan JWT
2. **Password Hashing** - Menggunakan bcrypt
3. **CORS Configuration** - Dikonfigurasi untuk keamanan API
4. **Validation** - Input validation pada semua form
5. **Soft Deletes** - Data tidak benar-benar dihapus dari database

## 🎯 Fitur Bisnis Logic

### Pengurangan Stok Otomatis
Ketika pesanan dibuat:
1. Sistem mengecek ketersediaan stok
2. Jika stok cukup, pesanan dibuat
3. Stok produk berkurang sesuai quantity
4. Jika stok tidak cukup, pesanan ditolak

### Pengembalian Stok
Ketika pesanan dibatalkan atau dihapus:
1. Sistem mengembalikan stok produk
2. Stock bertambah sesuai quantity pesanan

## 📱 Web Dashboard Routes

```
GET    /                      - Redirect ke login
GET    /login                 - Halaman login
GET    /register              - Halaman register
GET    /dashboard             - Dashboard utama (protected)
```

## 🧪 Testing

Untuk testing API, Anda dapat menggunakan:
- **Postman** - Import collection dari dokumentasi API
- **Thunder Client** (VS Code Extension)
- **cURL** (Command line)

## 📂 Struktur Folder Penting

```
coffee-shop-backend/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── AuthController.php       # Autentikasi
│   │       ├── ProductController.php    # CRUD Produk
│   │       ├── OrderController.php      # CRUD Pesanan
│   │       └── DashboardController.php  # Dashboard Web
│   └── Models/
│       ├── User.php                     # Model User + JWT
│       ├── Product.php                  # Model Product + UUID/Slug
│       ├── Order.php                    # Model Order + UUID
│       └── OrderItem.php                # Model OrderItem
├── database/
│   ├── migrations/                      # Database schema
│   └── seeders/
│       └── DatabaseSeeder.php           # Sample data
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php            # Main layout
│       ├── auth/
│       │   ├── login.blade.php          # Login page
│       │   └── register.blade.php       # Register page
│       └── dashboard/
│           └── index.blade.php          # Dashboard page
└── routes/
    ├── api.php                          # API routes
    └── web.php                          # Web routes
```

## 🎓 Catatan Pembelajaran

### Konsep Yang Diimplementasikan:
1. ✅ **MVC Pattern** (Model-View-Controller)
2. ✅ **RESTful API Design**
3. ✅ **JWT Authentication**
4. ✅ **Database Relationships** (One-to-Many, Many-to-Many)
5. ✅ **CRUD Operations**
6. ✅ **Business Logic** (Stock management)
7. ✅ **Frontend-Backend Integration**
8. ✅ **Responsive Design**

### Perbedaan UUID vs Slug:
- **UUID**: Identifier unik universal, cocok untuk data sensitif (Orders, Transactions)
- **Slug**: URL-friendly identifier, cocok untuk konten publik (Products, Articles)

## 🐛 Troubleshooting

### Error: "Token not provided"
- Pastikan Anda mengirim header `Authorization: Bearer {token}`
- Cek apakah token masih valid (belum expired)

### Error: "Insufficient stock"
- Stok produk habis atau tidak mencukupi
- Update stok produk terlebih dahulu

### Database tidak ada
```bash
php artisan migrate:fresh --seed
```

## 👨‍💻 Developer

Dibuat untuk memenuhi persyaratan tugas:
- Backend: Laravel 12 dengan JWT
- Frontend: Tailwind CSS dengan Laravel Blade
- Database: SQLite dengan UUID dan Slug identifiers
- Fitur: Full CRUD dengan JWT protection

## 📄 License

This project is for educational purposes.

---

**Happy Coding! ☕**
