# 🔐 Sistem Role & Authorization - USER vs ADMIN

## Fitur yang Diimplementasikan

### **1. Kolom Role di Users Table**
- **user** - Akun pelanggan biasa
- **admin** - Akun administrator

### **2. Akun Admin Bawaan**
```
Email: admin@thetazine.com
Password: admin123
Role: admin
```

### **3. Pembedaan Akses**

#### **A. Regular Users (role = 'user')**
✅ Bisa register
✅ Bisa login
✅ Bisa lihat menu produk (catalog)
✅ Bisa membuat pesanan sendiri
✅ Bisa lihat pesanan mereka sendiri saja
❌ Tidak bisa mengubah status pesanan
❌ Tidak bisa menghapus pesanan
❌ Tidak bisa manage produk

#### **B. Admin Users (role = 'admin')**
✅ Semua akses user
✅ Bisa lihat SEMUA pesanan dari semua user
✅ Bisa mengubah status pesanan (pending → processing → completed/cancelled)
✅ Bisa menghapus pesanan
✅ Bisa manage produk (create, update, delete)
✅ Bisa filter pesanan berdasarkan user_id

---

## API Endpoints

### **1. Order Management - User & Admin**
```
GET    /api/orders
- User: Melihat pesanan mereka sendiri
- Admin: Melihat semua pesanan
- Query: ?status=pending, ?user_id=1

POST   /api/orders
- User & Admin: Bisa membuat pesanan
- Validasi stock otomatis
- Mengurangi stock produk

GET    /api/orders/{uuid}
- User: Hanya pesanan mereka
- Admin: Pesanan apapun
```

### **2. Order Management - Admin Only**
```
PUT    /api/orders/{uuid}
- ADMIN ONLY ✅
- Update status pesanan
- Auto restore stock jika cancelled

DELETE /api/orders/{uuid}
- ADMIN ONLY ✅
- Hapus pesanan pending
- Auto restore stock
```

### **3. Product Management - Admin Only**
```
POST   /api/products
- ADMIN ONLY ✅
- Buat produk baru

PUT    /api/products/{identifier}
- ADMIN ONLY ✅
- Update produk

DELETE /api/products/{identifier}
- ADMIN ONLY ✅
- Hapus produk
```

---

## Contoh API Calls

### **Login sebagai User**
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "user@example.com",
    "password": "password"
  }'
```

### **Login sebagai Admin**
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@thetazine.com",
    "password": "admin123"
  }'
```

### **User: Lihat Pesanan Sendiri**
```bash
curl -X GET http://localhost:8000/api/orders \
  -H "Authorization: Bearer {USER_TOKEN}"
```
Response: Hanya pesanan user tersebut

### **Admin: Lihat Semua Pesanan**
```bash
curl -X GET http://localhost:8000/api/orders \
  -H "Authorization: Bearer {ADMIN_TOKEN}"
```
Response: Semua pesanan dari semua user

### **Admin: Update Status Pesanan**
```bash
curl -X PUT http://localhost:8000/api/orders/{uuid} \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer {ADMIN_TOKEN}" \
  -d '{
    "status": "processing"
  }'
```

### **Admin: Hapus Pesanan**
```bash
curl -X DELETE http://localhost:8000/api/orders/{uuid} \
  -H "Authorization: Bearer {ADMIN_TOKEN}"
```

---

## Middleware & Authorization

### **IsAdmin Middleware** (`app/Http/Middleware/IsAdmin.php`)
Mengecek apakah user memiliki role `admin`
Jika bukan admin, return 403 Forbidden

### **Routes Protection** (`routes/api.php`)
```php
// Untuk semua user (user + admin)
Route::middleware(['auth:api'])->group(function () {
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::post('/', [OrderController::class, 'store']);
        Route::get('/{uuid}', [OrderController::class, 'show']);
        // Update dan Delete hanya untuk Admin (checked in controller)
        Route::put('/{uuid}', [OrderController::class, 'update']);
        Route::delete('/{uuid}', [OrderController::class, 'destroy']);
    });
});

// Hanya untuk Admin
Route::middleware(['auth:api', 'admin'])->group(function () {
    Route::prefix('products')->group(function () {
        Route::post('/', [ProductController::class, 'store']);
        Route::put('/{identifier}', [ProductController::class, 'update']);
        Route::delete('/{identifier}', [ProductController::class, 'destroy']);
    });
});
```

---

## Helper Methods di User Model

```php
// Check apakah admin
$user->isAdmin() // return true/false

// Check apakah regular user
$user->isUser() // return true/false
```

---

## Flow Akses

### **User Regular:**
```
Login → Home → Browse Menu → Add to Cart → Checkout 
→ Create Order → View Own Orders Only
```

### **Admin:**
```
Login → Admin Dashboard (tidak ada di frontend saat ini)
→ View All Orders → Update Order Status → Delete Orders
→ Manage Products (create, update, delete)
```

---

## Security Notes

✅ **Authorization Checks:**
- User hanya bisa lihat pesanan mereka sendiri
- Admin bisa lihat semua pesanan
- Update/Delete pesanan hanya admin
- Product management hanya admin

✅ **Data Protection:**
- Semua route dilindungi JWT authentication
- Role checking di middleware
- Authorization checks di controller

✅ **Stock Management:**
- Stock otomatis berkurang saat order dibuat
- Stock otomatis kembali saat order dibatalkan/dihapus
- Atomic transaction (semua atau tidak sama sekali)

---

## Cara Upgrade User ke Admin (Manual)

Jika perlu upgrade user menjadi admin:

```bash
# Login ke database
php artisan tinker

# Lalu jalankan:
$user = User::find(1);
$user->update(['role' => 'admin']);
```

Atau buat endpoint API untuk admin management (optional):

```php
Route::middleware(['auth:api', 'admin'])->group(function () {
    Route::put('/users/{id}/role', [UserController::class, 'updateRole']);
});
```
