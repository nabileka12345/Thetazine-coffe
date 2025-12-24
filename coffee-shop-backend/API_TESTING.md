# API Testing Collection - Thetazine Coffee

## Variables
```
BASE_URL = http://localhost:8000/api
TOKEN = [Your JWT token after login]
```

## 1. Authentication

### Register
```http
POST {{BASE_URL}}/auth/register
Content-Type: application/json

{
  "name": "Test User",
  "email": "test@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

### Login
```http
POST {{BASE_URL}}/auth/login
Content-Type: application/json

{
  "email": "admin@thetazine.com",
  "password": "password123"
}
```

**Response:** Copy the `token` from response and use it in subsequent requests

### Get Profile (Protected)
```http
GET {{BASE_URL}}/auth/profile
Authorization: Bearer {{TOKEN}}
```

### Refresh Token
```http
POST {{BASE_URL}}/auth/refresh
Authorization: Bearer {{TOKEN}}
```

### Logout
```http
POST {{BASE_URL}}/auth/logout
Authorization: Bearer {{TOKEN}}
```

## 2. Products (Public Read)

### Get All Products
```http
GET {{BASE_URL}}/products
```

### Get Product by UUID
```http
GET {{BASE_URL}}/products/[UUID]
```

### Get Product by Slug
```http
GET {{BASE_URL}}/products/espresso-abc123
```

## 3. Products Management (Protected)

### Create Product
```http
POST {{BASE_URL}}/products
Authorization: Bearer {{TOKEN}}
Content-Type: application/json

{
  "name": "Cold Brew",
  "description": "Smooth cold coffee brewed for 12 hours",
  "price": 40000,
  "stock": 50,
  "category": "coffee",
  "image_url": "https://example.com/coldbrew.jpg",
  "is_available": true
}
```

### Update Product (by UUID)
```http
PUT {{BASE_URL}}/products/[UUID]
Authorization: Bearer {{TOKEN}}
Content-Type: application/json

{
  "name": "Cold Brew Premium",
  "price": 45000,
  "stock": 60
}
```

### Update Product (by Slug)
```http
PUT {{BASE_URL}}/products/cold-brew-abc123
Authorization: Bearer {{TOKEN}}
Content-Type: application/json

{
  "stock": 70,
  "is_available": true
}
```

### Delete Product
```http
DELETE {{BASE_URL}}/products/[UUID]
Authorization: Bearer {{TOKEN}}
```

## 4. Orders Management (Protected)

### Get All Orders (User's orders)
```http
GET {{BASE_URL}}/orders
Authorization: Bearer {{TOKEN}}
```

### Get Order by UUID
```http
GET {{BASE_URL}}/orders/[UUID]
Authorization: Bearer {{TOKEN}}
```

### Create Order (Stock akan berkurang otomatis)
```http
POST {{BASE_URL}}/orders
Authorization: Bearer {{TOKEN}}
Content-Type: application/json

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
  "notes": "Extra hot, please. No sugar."
}
```

**Note:** Stock dari produk ID 1 akan berkurang 2, produk ID 3 berkurang 1

### Update Order Status
```http
PUT {{BASE_URL}}/orders/[UUID]
Authorization: Bearer {{TOKEN}}
Content-Type: application/json

{
  "status": "processing"
}
```

**Status options:** `pending`, `processing`, `completed`, `cancelled`

**Note:** Jika status diubah ke `cancelled`, stock akan dikembalikan

### Delete Order (Stock akan dikembalikan)
```http
DELETE {{BASE_URL}}/orders/[UUID]
Authorization: Bearer {{TOKEN}}
```

**Note:** Stock dari semua items dalam order akan dikembalikan

## 5. Testing Scenarios

### Scenario 1: Full Product CRUD Flow
1. Login to get token
2. Get all products (see initial stock)
3. Create new product
4. Update the product
5. Delete the product

### Scenario 2: Stock Management Flow
1. Login to get token
2. Get product stock (e.g., product ID 1 has 100 stock)
3. Create order with 2 items of product ID 1
4. Get product again → stock should be 98
5. Cancel/delete the order
6. Get product again → stock should be back to 100

### Scenario 3: Order Status Flow
1. Login to get token
2. Create new order (status: pending)
3. Update status to "processing"
4. Update status to "completed"
5. Try to delete → should fail (only pending can be deleted)

## 6. Expected Responses

### Success Response
```json
{
  "status": "success",
  "message": "Operation successful",
  "data": { ... }
}
```

### Error Response
```json
{
  "status": "error",
  "message": "Error message",
  "errors": { ... }
}
```

### Validation Error Response
```json
{
  "status": "error",
  "message": "Validation failed",
  "errors": {
    "field_name": ["Error message"]
  }
}
```

## 7. Common Error Codes

- **401 Unauthorized** - Token tidak valid/expired
- **403 Forbidden** - Tidak memiliki akses
- **404 Not Found** - Resource tidak ditemukan
- **422 Unprocessable Entity** - Validation error
- **500 Internal Server Error** - Server error

## 8. Tips Testing

1. **Selalu simpan token** dari response login
2. **Cek stock** sebelum dan sesudah create/delete order
3. **Test validation** dengan data yang salah
4. **Test authorization** dengan dan tanpa token
5. **Test UUID vs Slug** untuk mengakses produk
6. **Test business logic** stock management

## 9. Quick Copy-Paste Commands (cURL)

### Login
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@thetazine.com","password":"password123"}'
```

### Get Products
```bash
curl -X GET http://localhost:8000/api/products
```

### Create Order (Replace TOKEN)
```bash
curl -X POST http://localhost:8000/api/orders \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{"customer_name":"John Doe","customer_email":"john@example.com","items":[{"product_id":1,"quantity":2}]}'
```

---

**Happy Testing! 🚀**
