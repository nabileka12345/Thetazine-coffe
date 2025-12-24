# 🔧 Fix: Register User - Role Harus 'user' Bukan 'admin'

## Problem yang Diperbaiki

**Masalah:** Saat user melakukan registrasi, mereka langsung menjadi admin.

**Penyebab:** 
- AuthController tidak explicitly set `role = 'user'` saat membuat user baru
- Response juga tidak menunjukkan role field

---

## Solusi yang Diimplementasikan

### **1. Update AuthController::register()**
✅ Explicitly set `role = 'user'` saat create user
```php
$user = User::create([
    'name' => $request->name,
    'email' => $request->email,
    'password' => Hash::make($request->password),
    'role' => 'user', // <-- ADDED
]);
```

✅ Improved response format dengan role field:
```json
{
  "status": "success",
  "message": "User registered successfully",
  "data": {
    "id": 1,
    "name": "John User",
    "email": "john@example.com",
    "role": "user"
  },
  "authorization": {
    "token": "eyJ0eXAiOiJKV1QiLC...",
    "type": "Bearer",
    "expires_in": 3600
  }
}
```

### **2. Update AuthController::login()**
✅ Response juga sekarang include role field:
```json
{
  "status": "success",
  "message": "Login successful",
  "data": {
    "id": 1,
    "name": "John User",
    "email": "john@example.com",
    "role": "user"
  },
  "authorization": {
    "token": "eyJ0eXAiOiJKV1QiLC...",
    "type": "Bearer",
    "expires_in": 3600
  }
}
```

### **3. Buat FixUserRoleSeeder**
✅ Fix semua user yang punya role salah di database:
```bash
php artisan db:seed --class=FixUserRoleSeeder
```

Output:
```
Fixed user: nabil@gmail.com -> role: user
Fixed user: arka@gmail.com -> role: user
Fixed admin: admin@thetazine.com -> role: admin
User roles fixed successfully!
```

---

## Testing

### **Test 1: Register User Baru**
```bash
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Budi Santoso",
    "email": "budi@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

Response (BENAR):
```json
{
  "status": "success",
  "data": {
    "role": "user"  // <-- Ini harus 'user', bukan 'admin'!
  }
}
```

### **Test 2: Login User Baru**
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "budi@example.com",
    "password": "password123"
  }'
```

Response (BENAR):
```json
{
  "status": "success",
  "data": {
    "id": 5,
    "name": "Budi Santoso",
    "email": "budi@example.com",
    "role": "user"  // <-- Harus 'user'!
  }
}
```

### **Test 3: Login Admin**
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@thetazine.com",
    "password": "admin123"
  }'
```

Response (BENAR):
```json
{
  "status": "success",
  "data": {
    "id": 1,
    "name": "Administrator",
    "email": "admin@thetazine.com",
    "role": "admin"  // <-- Harus 'admin'!
  }
}
```

---

## Verification

Untuk memverifikasi di database:

```bash
php artisan tinker

# Check user biasa
User::where('email', 'budi@example.com')->first();
# Output: role => 'user' ✅

# Check admin
User::where('email', 'admin@thetazine.com')->first();
# Output: role => 'admin' ✅
```

---

## Summary Perubahan

| File | Perubahan |
|------|-----------|
| `AuthController.php` | ✅ Set `role = 'user'` di register |
| `AuthController.php` | ✅ Include role di response |
| `FixUserRoleSeeder.php` | ✅ Created untuk fix role yang salah |
| Database | ✅ Semua user sudah punya role yang benar |

---

## Files Affected
1. `/app/Http/Controllers/AuthController.php` - Updated register & login methods
2. `/database/seeders/FixUserRoleSeeder.php` - Created to fix incorrect roles
3. `/tests/Feature/AuthTest.php` - Created for automated testing

---

Sekarang user yang registrasi akan **selalu** punya role `'user'`, bukan `'admin'`! ✅
