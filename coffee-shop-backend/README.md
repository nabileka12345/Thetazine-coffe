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

## Fitur Utama

### 🔐 Autentikasi JWT
- Register dan Login dengan JWT token
- Role-based access control (Admin & User)
- Token refresh mechanism
- Secure password hashing dengan bcrypt
- Frontend localStorage untuk menyimpan token

### 📦 Manajemen Produk
- CRUD produk lengkap dengan URL gambar
- UUID dan Slug untuk URL-friendly routing
- Soft deletes untuk data recovery
- Support URL gambar eksternal (text field untuk URL panjang)
- Image preview di dashboard admin

### 🛒 Sistem Pemesanan
- Create order dengan multiple items dalam satu transaksi
- Automatic stock reduction saat order dibuat
- Stock restoration saat order dibatalkan
- Order history untuk user
- Admin dapat melihat dan manage semua orders
- Status tracking (pending, completed, cancelled)

### 📊 Dashboard Admin
- Modern UI dengan Tailwind CSS
- Real-time product management dengan modal
- Order management system
- Image preview untuk produk di tabel
- Responsive design untuk mobile

### 🎨 Frontend Modern
- Responsive design dengan Tailwind CSS
- Interactive UI dengan Alpine.js
- HTTP client dengan Axios untuk API calls
- Map integration dengan Leaflet (OpenStreetMap)
- Lucide icons untuk UI elements
- Custom CSS untuk styling tambahan

## API Endpoints

### Authentication
```
POST   /api/auth/register     - Register user baru
POST   /api/auth/login        - Login user (returns JWT token)
POST   /api/auth/logout       - Logout user (invalidate token)
POST   /api/auth/refresh      - Refresh JWT token
GET    /api/auth/profile      - Get user profile (requires token)
```

### Products
```
GET    /api/products          - Get all products
GET    /api/products/{uuid}   - Get product by UUID
POST   /api/products          - Create product (Admin only)
PUT    /api/products/{uuid}   - Update product (Admin only)
DELETE /api/products/{uuid}   - Delete product (Admin only)
```

### Orders
```
GET    /api/orders            - Get orders (User: own orders, Admin: all)
GET    /api/orders/{uuid}     - Get order detail by UUID
POST   /api/orders            - Create new order (requires token)
PUT    /api/orders/{uuid}     - Update order status (Admin only)
DELETE /api/orders/{uuid}     - Cancel order (User) or Delete (Admin)
```

## Database Schema

### Users Table
- `id` - Primary key (bigint)
- `name` - Nama pengguna (string)
- `email` - Email (string, unique)
- `password` - Hashed password (string)
- `role` - User role (enum: 'admin', 'user', default: 'user')
- `email_verified_at` - Email verification timestamp
- `remember_token` - Remember token
- `timestamps` - created_at, updated_at

### Products Table
- `id` - Primary key (bigint)
- `uuid` - UUID (string, unique, for routing)
- `slug` - URL slug (string, unique)
- `name` - Nama produk (string)
- `price` - Harga produk (decimal 10,2)
- `stock` - Stok tersedia (integer)
- `description` - Deskripsi produk (text)
- `image_url` - URL gambar (text field untuk URL panjang)
- `deleted_at` - Soft delete timestamp
- `timestamps` - created_at, updated_at

### Orders Table
- `id` - Primary key (bigint)
- `uuid` - UUID (string, unique, for routing)
- `user_id` - Foreign key to users
- `total_price` - Total harga (decimal 10,2)
- `status` - Status order (enum: 'pending', 'completed', 'cancelled', default: 'pending')
- `deleted_at` - Soft delete timestamp
- `timestamps` - created_at, updated_at

### Order Items Table
- `id` - Primary key (bigint)
- `order_id` - Foreign key to orders
- `product_id` - Foreign key to products
- `quantity` - Jumlah produk (integer)
- `price` - Harga per item saat order dibuat (decimal 10,2)
- `timestamps` - created_at, updated_at

## Default Admin Credentials

```
Email: admin@thetazine.com
Password: password123
Role: admin
```

Setelah seeding, Anda dapat login sebagai admin menggunakan credentials di atas.

## Project Structure

```
coffee-shop-backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php       # JWT Authentication
│   │   │   ├── ProductController.php    # Product CRUD
│   │   │   ├── OrderController.php      # Order Management
│   │   │   ├── DashboardController.php  # Admin Dashboard Views
│   │   │   └── HomeController.php       # Public Home View
│   │   └── Middleware/
│   │       ├── IsAdmin.php              # Admin role middleware
│   │       └── JwtMiddleware.php        # JWT auth middleware
│   └── Models/
│       ├── User.php                      # User model dengan role
│       ├── Product.php                   # Product dengan UUID & Slug
│       ├── Order.php                     # Order dengan UUID
│       └── OrderItem.php                 # Order items relasi
├── config/
│   ├── jwt.php                          # JWT configuration
│   └── cors.php                         # CORS configuration
├── database/
│   ├── migrations/
│   │   ├── create_users_table.php
│   │   ├── create_products_table.php
│   │   ├── create_orders_table.php
│   │   ├── create_order_items_table.php
│   │   └── add_role_to_users_table.php
│   └── seeders/
│       ├── DatabaseSeeder.php            # Main seeder (admin + products)
│       └── AdminSeeder.php               # Admin user seeder
├── public/
│   ├── css/
│   │   └── home.css                      # Custom styles
│   ├── js/
│   │   └── home.js                       # Home page logic & API calls
│   └── img/                              # Local images (optional)
├── resources/
│   └── views/
│       ├── home/
│       │   └── index.blade.php           # Public home page (coffee shop)
│       ├── dashboard/
│       │   └── index.blade.php           # Admin dashboard (CRUD)
│       └── auth/
│           ├── login.blade.php           # Login page with Alpine.js
│           └── register.blade.php        # Register page with Alpine.js
└── routes/
    ├── web.php                           # Web routes (views)
    └── api.php                           # API routes (JWT protected)
```

## Troubleshooting

### Cache Issues
Jika mengalami masalah routing atau view:
```bash
php artisan route:clear
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Permission Issues (Linux/Mac)
Pastikan folder storage dan bootstrap/cache memiliki permission yang tepat:
```bash
chmod -R 775 storage bootstrap/cache
```

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

### CORS Issues
Pastikan CORS sudah dikonfigurasi dengan benar di `config/cors.php`:
```php
'paths' => ['api/*'],
'allowed_origins' => ['*'],
'allowed_methods' => ['*'],
'allowed_headers' => ['*'],
```

### Image URL Not Showing
Jika gambar produk tidak muncul:
1. Pastikan URL gambar valid dan accessible
2. Periksa di `home.js` prioritas loading: `product.image_url` > fallback
3. Migration `image_url` sudah diubah dari `string(255)` ke `text()`

## Environment Variables

Variabel penting di `.env`:

```env
APP_NAME="Thetazine Coffee"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=coffee_shop
DB_USERNAME=root
DB_PASSWORD=

JWT_SECRET=your-generated-secret-key
JWT_TTL=60
JWT_REFRESH_TTL=20160
JWT_ALGO=HS256

MAIL_MAILER=smtp
```

## Migration Guide (XAMPP to Laragon)

Jika ingin pindah dari XAMPP ke Laragon:

1. **Backup Database**:
   ```bash
   mysqldump -u root -p coffee_shop > coffee_shop_backup.sql
   ```

2. **Copy Project Folder**:
   - Copy folder `coffee-shop-backend` ke `C:\laragon\www\`

3. **Import Database di Laragon**:
   - Buka HeidiSQL atau phpMyAdmin di Laragon
   - Create database baru `coffee_shop`
   - Import file `coffee_shop_backup.sql`

4. **Update .env**:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=coffee_shop
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Clear Cache & Run**:
   ```bash
   cd C:\laragon\www\coffee-shop-backend
   composer install
   php artisan cache:clear
   php artisan serve
   ```

## Browser Support

- ✅ Chrome (Latest)
- ✅ Firefox (Latest)
- ✅ Safari (Latest)
- ✅ Edge (Latest)
- ✅ Mobile browsers (responsive design)

## Requirements

### Server Requirements
- PHP >= 8.2
- Composer >= 2.0
- Node.js >= 16.x & NPM
- MySQL >= 5.7 atau MariaDB >= 10.3

### Development Environment
- XAMPP 8.2+ atau Laragon
- Visual Studio Code (recommended)
- Git (optional, untuk version control)

### PHP Extensions
- OpenSSL PHP Extension
- PDO PHP Extension
- Mbstring PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- Ctype PHP Extension
- JSON PHP Extension
- BCMath PHP Extension

## Testing

### Manual Testing
1. **Test Registration**: Register user baru dan pastikan redirect ke home
2. **Test Login**: Login sebagai admin dan user, pastikan redirect sesuai role
3. **Test Product CRUD**: Tambah, edit, hapus produk di dashboard admin
4. **Test Order**: Buat pesanan dan pastikan stok berkurang
5. **Test Order Cancel**: Cancel pesanan dan pastikan stok kembali

### API Testing
Gunakan Postman atau Thunder Client untuk test API endpoints:
1. Import collection dari `API_TESTING.md`
2. Test authentication endpoints
3. Test CRUD products (dengan admin token)
4. Test CRUD orders (dengan user token)

## Security

- ✅ JWT token authentication
- ✅ Password hashing dengan bcrypt
- ✅ CSRF protection (Laravel default)
- ✅ SQL injection protection (Eloquent ORM)
- ✅ XSS protection (Blade escaping)
- ✅ Role-based authorization
- ✅ Input validation on all forms
- ✅ Database transactions untuk data consistency

## Performance Optimization

- Eager loading untuk relationships (`with()`)
- Database indexing pada kolom sering di-query (uuid, slug, email)
- Soft deletes untuk historical data
- Efficient querying dengan Eloquent
- Asset optimization dengan Vite (future improvement)

## Future Enhancements

- [ ] Email verification system
- [ ] Password reset functionality
- [ ] Payment gateway integration
- [ ] Order tracking system
- [ ] Product categories/filtering
- [ ] Product reviews/ratings
- [ ] Shopping cart functionality
- [ ] Wishlist feature
- [ ] Admin analytics dashboard
- [ ] Multi-language support

## Credits

Built with:
- [Laravel 12](https://laravel.com) - PHP Framework
- [Tailwind CSS](https://tailwindcss.com) - Utility-first CSS
- [Alpine.js](https://alpinejs.dev) - Lightweight JS framework
- [JWT Auth](https://github.com/tymondesigns/jwt-auth) - JSON Web Token authentication
- [Leaflet](https://leafletjs.com) - Interactive maps
- [Lucide Icons](https://lucide.dev) - Beautiful icons
- [Axios](https://axios-http.com) - HTTP client

## Documentation Files

- `README.md` - Main documentation (this file)
- `API_TESTING.md` - API endpoints testing guide
- `QUICK_START.md` - Quick start guide
- `PROJECT_SUMMARY.md` - Project summary
- `ROLE_SYSTEM.md` - Role system documentation
- `FIX_USER_ROLE.md` - User role troubleshooting

## License

This project is for educational purposes only as part of web programming final project.

## Author

Developed by **Thetazine Team**

## Support

Untuk pertanyaan atau bantuan:
- Buka issue di repository ini
- Email: admin@thetazine.com
- Atau hubungi dosen pembimbing

---

**Happy Coding! ☕**

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
