# Auth API Test qilish uchun qo'llanma

## 1. Postman Collection

`postman_auth_collection.json` faylini Postman'ga import qiling.

## 2. CURL orqali test qilish

### 2.1 Ro'yxatdan o'tish
```bash
curl -X POST http://127.0.0.1:8001/v1/register \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "first_name": "Urinboy",
    "last_name": "Raximov", 
    "email": "urinboy@example.com",
    "phone": "+998901234567",
    "password": "123456",
    "password_confirmation": "123456",
    "birth_date": "1995-01-15",
    "gender": "male",
    "address": "Toshkent shahri, Yunusobod tumani, 4-kvartal",
    "city": "Toshkent",
    "district": "Yunusobod",
    "postal_code": "100084",
    "latitude": 41.2995,
    "longitude": 69.2401,
    "preferred_language_id": 1
  }'
```

### 2.2 Login
```bash
curl -X POST http://127.0.0.1:8001/v1/login \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "email": "urinboy@example.com",
    "password": "123456"
  }'
```

### 2.3 Foydalanuvchi ma'lumotlari (token kerak)
```bash
curl -X GET http://127.0.0.1:8001/v1/me \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### 2.4 Location yangilash (token kerak)
```bash
curl -X POST http://127.0.0.1:8001/v1/update-location \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -d '{
    "latitude": 41.3111,
    "longitude": 69.2797,
    "address": "Toshkent shahri, Shayxontohur tumani, Bobur ko'chasi 12",
    "city": "Toshkent",
    "district": "Shayxontohur",
    "postal_code": "100060"
  }'
```

### 2.5 Logout (token kerak)
```bash
curl -X POST http://127.0.0.1:8001/v1/logout \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

## 3. API Endpoint'lar

| Method | URL | Auth | Tavsif |
|--------|-----|------|--------|
| POST | /v1/register | ❌ | Ro'yxatdan o'tish |
| POST | /v1/login | ❌ | Kirish |
| GET | /v1/me | ✅ | Foydalanuvchi ma'lumotlari |
| POST | /v1/update-location | ✅ | Joylashuvni yangilash |
| POST | /v1/logout | ✅ | Chiqish |

## 4. Muhim eslatmalar

- **Token saqlash**: Login/register dan olingan `token`ni keyingi requestlar uchun saqlang
- **Authorization header**: Token ishlatganda: `Authorization: Bearer YOUR_TOKEN`
- **Content-Type**: JSON yuborayotganda: `Content-Type: application/json`
- **Location**: Latitude va longitude majburiy, address ixtiyoriy

## 5. Xato holatlari

### Validatsiya xatoliklari (422)
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The email field is required."]
  }
}
```

### Authentication xatoliklari (401)
```json
{
  "success": false,
  "message": "Login yoki parol xato"
}
```
