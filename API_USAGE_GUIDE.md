# 🚀 DOM PRODUCT API FOYDALANISH QO'LLANMASI

## 📡 API BASE URL
```
Base URL: https://api.domproduct.uz/api
```

## 🛣️ ENDPOINT'LAR RO'YXATI

### 🔐 AUTENTIFIKATSIYA (`/auth/`)
Barcha autentifikatsiya endpoint'lari `/api/auth/` prefiksi bilan ishlaydi:

```bash
# Ro'yxatdan o'tish
POST /api/auth/register
Content-Type: application/json
{
  "name": "Foydalanuvchi nomi",
  "email": "email@example.com", 
  "password": "password123",
  "password_confirmation": "password123",
  "phone": "998901234567", // ixtiyoriy
  "preferred_language": "uz" // uz, en, ru
}

# Tizimga kirish
POST /api/auth/login
Content-Type: application/json
{
  "email": "email@example.com",
  "password": "password123"
}

# Foydalanuvchi ma'lumotlari (token talab qilinadi)
GET /api/auth/user
Authorization: Bearer YOUR_TOKEN

# Chiqish (token talab qilinadi)
POST /api/auth/logout
Authorization: Bearer YOUR_TOKEN

# Barcha qurilmalardan chiqish (token talab qilinadi)
POST /api/auth/logout-all
Authorization: Bearer YOUR_TOKEN
```

### 🌐 OMMAVIY API (`/v1/`)
Ommaviy API endpoint'lari `/api/v1/` prefiksi bilan ishlaydi:

#### **Tillar**
```bash
# Barcha tillar
GET /api/v1/languages

# Standart til
GET /api/v1/languages/default

# Bitta til
GET /api/v1/languages/{id}

# Tarjimalar
GET /api/v1/translations/{languageCode}   # uz, en, ru
GET /api/v1/translations/{languageCode}/{group}
```

#### **Kategoriyalar**
```bash
# Kategoriyalar ro'yxati
GET /api/v1/categories

# Kategoriyalar darakti
GET /api/v1/categories/tree

# Bitta kategoriya
GET /api/v1/categories/{id}

# Slug bo'yicha kategoriya
GET /api/v1/categories/slug/{slug}

# Kategoriya yo'li (breadcrumbs)
GET /api/v1/categories/{id}/breadcrumbs
```

#### **Mahsulotlar**
```bash
# Mahsulotlar ro'yxati (filtrlash bilan)
GET /api/v1/products
Query params: 
  - page=1
  - per_page=10
  - category_id=1
  - search=pomidor
  - min_price=1000
  - max_price=50000
  - featured=true
  - sort_by=price (price, created_at, name)
  - sort_order=asc (asc, desc)

# Tavsiya etilgan mahsulotlar
GET /api/v1/products/featured

# Bitta mahsulot
GET /api/v1/products/{id}

# Slug bo'yicha mahsulot
GET /api/v1/products/slug/{slug}

# O'xshash mahsulotlar
GET /api/v1/products/{id}/related

# Kategoriya bo'yicha mahsulotlar
GET /api/v1/products/category/{categoryId}
```

### 👤 FOYDALANUVCHI API (`/user/`)
Token talab qilinadi (`Authorization: Bearer YOUR_TOKEN`):

```bash
# Profil ko'rish
GET /api/user/profile

# Profil yangilash
PUT /api/user/profile
Content-Type: application/json
{
  "name": "Yangi ism",
  "first_name": "Ism",
  "last_name": "Familiya", 
  "phone": "998901234567",
  "city": "Toshkent",
  "district": "Yunusobod",
  "address": "Ko'cha manzili"
}

# Joylashuv yangilash
PUT /api/user/location
Content-Type: application/json
{
  "latitude": 41.2995,
  "longitude": 69.2401,
  "address": "Yangi manzil"
}

# Parol o'zgartirish
PUT /api/user/password
Content-Type: application/json
{
  "current_password": "eski_parol",
  "password": "yangi_parol",
  "password_confirmation": "yangi_parol"
}

# Avatar yuklash
POST /api/user/avatar
Content-Type: multipart/form-data
avatar: [file]

# Avatar o'chirish
DELETE /api/user/avatar

# Akkauntni o'chirish
DELETE /api/user/account
```

### 🛒 SAVAT API (`/cart/`)
Session yoki User asosida ishlaydi:

```bash
# Savat ko'rish
GET /api/cart

# Mahsulot qo'shish
POST /api/cart/add
Content-Type: application/json
{
  "product_id": 1,
  "quantity": 2,
  "options": {} // ixtiyoriy
}

# Savat elementini yangilash
PUT /api/cart/items/{itemId}
Content-Type: application/json
{
  "quantity": 3
}

# Savat elementini o'chirish
DELETE /api/cart/items/{itemId}

# Savatni tozalash
DELETE /api/cart/clear

# Kupon qo'llash
POST /api/cart/coupon/apply
Content-Type: application/json
{
  "coupon_code": "DISCOUNT10"
}

# Kuponni o'chirish
DELETE /api/cart/coupon

# Savat xulosasi
GET /api/cart/summary
```

### 📦 BUYURTMALAR API (`/orders/`)
Token talab qilinadi:

```bash
# Buyurtmalar ro'yxati
GET /api/orders

# Yangi buyurtma
POST /api/orders
Content-Type: application/json
{
  "delivery_address_id": 1,
  "payment_method": "cash", // cash, card, online
  "notes": "Izoh"
}

# Buyurtma ko'rish
GET /api/orders/{id}

# Buyurtmani bekor qilish
POST /api/orders/{id}/cancel

# Buyurtma status tarixi
GET /api/orders/{id}/status-history
```

### 🏠 MANZILLAR API (`/addresses/`)
Token talab qilinadi:

```bash
# Manzillar ro'yxati
GET /api/addresses

# Yangi manzil qo'shish
POST /api/addresses
Content-Type: application/json
{
  "title": "Uy",
  "type": "home", // home, work, other
  "city": "Toshkent",
  "district": "Yunusobod",
  "address": "Ko'cha, uy raqami",
  "latitude": 41.2995,
  "longitude": 69.2401,
  "phone": "998901234567",
  "is_default": true
}

# Manzil ko'rish
GET /api/addresses/{id}

# Manzil yangilash
PUT /api/addresses/{id}

# Manzil o'chirish
DELETE /api/addresses/{id}

# Standart manzil qilish
POST /api/addresses/{id}/set-default

# Yetkazib berish narxini hisoblash
POST /api/addresses/{id}/delivery-fee
```

### 💳 TO'LOV API (`/payments/`)
Token talab qilinadi:

```bash
# To'lov usullari
GET /api/payments/methods

# To'lovni amalga oshirish
POST /api/payments/process
Content-Type: application/json
{
  "order_id": 1,
  "payment_method": "card",
  "amount": 25000
}

# To'lov tarixi
GET /api/payments/history

# To'lov holatini tekshirish
GET /api/payments/{id}/status

# To'lovni tasdiqlash
POST /api/payments/{id}/confirm
```

### 🔔 BILDIRISHNOMALAR API (`/notifications/`)
Token talab qilinadi:

```bash
# Bildirishnomalar
GET /api/notifications

# O'qilmagan bildirishnomalar soni
GET /api/notifications/unread-count

# Bildirishnomani o'qilgan deb belgilash
POST /api/notifications/{id}/mark-read

# Barcha bildirishnomalarni o'qilgan deb belgilash
POST /api/notifications/mark-all-read

# Bildirishnomani o'chirish
DELETE /api/notifications/{id}

# Test bildirishnoma yuborish
POST /api/notifications/test
```

### 🔧 ADMIN API (`/admin/`)
Admin/Manager roli va token talab qilinadi:

#### **Foydalanuvchilar boshqaruvi**
```bash
# Foydalanuvchilar ro'yxati
GET /api/admin/users
Query params: page, per_page, search, role

# Foydalanuvchi statistikasi
GET /api/admin/users/statistics

# Foydalanuvchi ko'rish
GET /api/admin/users/{id}

# Foydalanuvchi yangilash
PUT /api/admin/users/{id}

# Foydalanuvchi o'chirish (faqat admin)
DELETE /api/admin/users/{id}
```

#### **Kategoriya boshqaruvi**
```bash
# Kategoriyalar (admin)
GET /api/admin/categories

# Yangi kategoriya
POST /api/admin/categories

# Kategoriya statistikasi
GET /api/admin/categories/statistics

# Kategoriya ko'rish
GET /api/admin/categories/{id}

# Kategoriya yangilash
PUT /api/admin/categories/{id}

# Kategoriya o'chirish
DELETE /api/admin/categories/{id}

# Kategoriya rasmini yuklash
POST /api/admin/categories/{id}/image

# Kategoriya rasmini o'chirish
DELETE /api/admin/categories/{id}/image
```

#### **Mahsulot boshqaruvi**
```bash
# Mahsulotlar (admin)
GET /api/admin/products

# Yangi mahsulot
POST /api/admin/products

# Mahsulot statistikasi
GET /api/admin/products/statistics

# Kam qolgan mahsulotlar
GET /api/admin/products/low-stock

# Analytics
GET /api/admin/products/analytics

# Mahsulot ko'rish
GET /api/admin/products/{id}

# Mahsulot yangilash
PUT /api/admin/products/{id}

# Mahsulot o'chirish
DELETE /api/admin/products/{id}

# Zaxira yangilash
PUT /api/admin/products/{id}/stock

# Zaxirani ommaviy yangilash
POST /api/admin/products/bulk-stock

# Mahsulot rasmini yuklash
POST /api/admin/products/{id}/images

# Rasm o'chirish
DELETE /api/admin/products/{id}/images/{imageId}

# Asosiy rasm qilish
PUT /api/admin/products/{id}/images/{imageId}/primary
```

#### **Buyurtma boshqaruvi**
```bash
# Buyurtmalar (admin)
GET /api/admin/orders

# Buyurtma statistikasi
GET /api/admin/orders/statistics

# Buyurtma ko'rish
GET /api/admin/orders/{id}

# Buyurtma statusini yangilash
PUT /api/admin/orders/{id}/status
Content-Type: application/json
{
  "status": "processing", // pending, processing, shipped, delivered, cancelled
  "notes": "Izoh"
}
```

#### **Til va tarjima boshqaruvi**
```bash
# Tillar CRUD
POST /api/admin/languages
PUT /api/admin/languages/{id}
DELETE /api/admin/languages/{id}

# Tarjimalar CRUD
GET /api/admin/translations
POST /api/admin/translations
PUT /api/admin/translations/{id}
DELETE /api/admin/translations/{id}

# Ommaviy tarjima yangilash
POST /api/admin/translations/bulk-upsert
```

## 🔒 AUTENTIFIKATSIYA

### Token olish
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@example.com",
    "password": "password"
  }'
```

### Token ishlatish
```bash
curl -X GET http://localhost:8000/api/user/profile \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json"
```

## 🚫 RATE LIMITING

- **Auth endpoints**: 5 so'rov/daqiqa
- **Public endpoints**: 60 so'rov/daqiqa  
- **API endpoints**: 100 so'rov/daqiqa

## 🌐 CORS

CORS barcha origin'lar uchun yoqilgan:
- `Access-Control-Allow-Origin: *`
- `Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS`
- `Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Accept, Origin`

## 📊 RESPONSE FORMAT

### Muvaffaqiyatli javob:
```json
{
  "success": true,
  "message": "Data retrieved successfully",
  "data": { ... },
  "meta": { 
    "current_page": 1,
    "per_page": 10,
    "total": 100,
    "last_page": 10
  }
}
```

### Xatolik javobi:
```json
{
  "success": false,
  "message": "Error message",
  "error_code": "ERROR_CODE",
  "errors": { ... }
}
```

## 🔧 ENVIRONMENT

Development server:
```bash
cd /path/to/project
php artisan serve --host=0.0.0.0 --port=8000
```

API localhost manzili: `http://localhost:8000/api`

## 📝 MISOLLAR

### JavaScript fetch bilan:
```javascript
// GET so'rov
const response = await fetch('http://localhost:8000/api/v1/products', {
  headers: {
    'Accept': 'application/json'
  }
});
const data = await response.json();

// POST so'rov (token bilan)
const response = await fetch('http://localhost:8000/api/cart/add', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Authorization': 'Bearer ' + token,
    'Accept': 'application/json'
  },
  body: JSON.stringify({
    product_id: 1,
    quantity: 2
  })
});
```

### axios bilan:
```javascript
// Axios instance
const api = axios.create({
  baseURL: 'http://localhost:8000/api',
  headers: {
    'Accept': 'application/json'
  }
});

// Token qo'shish
api.defaults.headers.common['Authorization'] = `Bearer ${token}`;

// Ishlatish
const products = await api.get('/v1/products');
const cartAdd = await api.post('/cart/add', { product_id: 1, quantity: 2 });
```

## ✅ API HOLATNI TEKSHIRISH

```bash
# Server ishlayotganini tekshirish
curl http://localhost:8000/api/v1/languages

# Rate limiting'ni tekshirish
curl -v http://localhost:8000/api/auth/register

# CORS'ni tekshirish  
curl -X OPTIONS http://localhost:8000/api/v1/products \
  -H "Origin: http://localhost:3000" \
  -H "Access-Control-Request-Method: GET"
```

🎉 **API `/api` prefiksi bilan to'liq ishlaydi va ishlatishga tayyor!**
