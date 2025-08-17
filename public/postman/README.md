# Postman Collections / Postman Kolleksiyalari

Bu papkada **Mahsulot yetkazib berish API-si** uchun barcha Postman kolleksiyalari va qo'llanmalari joylashgan.

## 📁 Mavjud Fayllar / Available Files

### 1. 🔐 Authentication API
- **Kolleksiya**: `postman_auth_collection.json`
- **Qo'llanma**: `AUTH_API_TEST_GUIDE.md`
- **Tavsif**: Foydalanuvchi autentifikatsiyasi, ro'yxatdan o'tish, login/logout
- **Endpoint'lar**: 6 ta API endpoint (register, login, logout, user info, location update)

### 2. 🌐 Languages & Translations API  
- **Kolleksiya**: `postman_languages_translations_collection.json`
- **Qo'llanma**: `LANGUAGES_TRANSLATIONS_API_GUIDE.md`
- **Tavsif**: Ko'p tillilik tizimi - tillar va tarjimalar boshqaruvi
- **Endpoint'lar**: 20+ API endpoint (public va admin)

## 🔧 API Modullari Tafsiloti

### 🔐 Authentication Module
- ✅ **Ro'yxatdan o'tish** - to'liq profil ma'lumotlari bilan
- ✅ **Login/Logout** - token asosida
- ✅ **Foydalanuvchi ma'lumotlari** - profil ko'rish
- ✅ **Joylashuv yangilash** - GPS koordinatalar
- ✅ **Token validatsiya** - autentifikatsiya tekshirish

### 🌐 Languages & Translations Module
**Public API (token kerak emas):**
- ✅ Barcha tillarni olish (`uz`, `en`, `ru`)
- ✅ Asosiy tilni aniqlash
- ✅ Til bo'yicha barcha tarjimalarni olish
- ✅ Guruh bo'yicha tarjimalar (`menu`, `auth`, `product`, `order`, `address`)

**Admin API (Bearer token kerak):**
- ✅ Tillarni boshqarish (CRUD)
- ✅ Tarjimalarni boshqarish (CRUD)  
- ✅ Ko'p tarjimalarni bulk qo'shish/yangilash
- ✅ Paginatsiya va filter qo'llab-quvvatlash

## 📖 Foydalanish / Usage

### 1. **Postman-ga Import qilish**
1. Postman dasturini oching
2. `Import` tugmasini bosing
3. Kerakli JSON faylni tanlang:
   - `postman_auth_collection.json` - Autentifikatsiya API'lari
   - `postman_languages_translations_collection.json` - Tillar va tarjimalar API'lari
4. Kolleksiya avtomatik import bo'ladi

### 2. **Environment Variables sozlash**
Postman'da yangi Environment yarating va quyidagi o'zgaruvchilarni qo'shing:

```
base_url = http://127.0.0.1:8000
admin_token = (Admin login orqali olingan token)
user_token = (User login orqali olingan token)
```

### 3. **API'larni test qilish ketma-ketligi**
1. **Birinchi** - Authentication API orqali foydalanuvchi yarating
2. **Ikkinchi** - Login qilib token oling
3. **Uchinchi** - Token'ni environment variable sifatida saqlang
4. **To'rtinchi** - Languages API'larni test qiling
5. **Beshinchi** - Admin endpoint'lar uchun admin token kerak

### 4. **Batafsil qo'llanmalar**
- **Auth API**: `AUTH_API_TEST_GUIDE.md` - CURL misollari va frontend integratsiya
- **Languages API**: `LANGUAGES_TRANSLATIONS_API_GUIDE.md` - barcha endpoint'lar uchun misol

## 🚀 Keyingi Kolleksiyalar / Upcoming Collections

Ishlab chiqilayotgan API modullari:
- 🔄 **Categories** (Kategoriyalar) - parent-child struktura bilan
- 🔄 **Products** (Mahsulotlar) - mahsulotlar boshqaruvi
- 🔄 **Cart** (Savatcha) - xarid qilish savatchasi
- 🔄 **Orders** (Buyurtmalar) - buyurtma boshqaruvi
- 🔄 **Payments** (To'lovlar) - to'lov tizimi
- 🔄 **Delivery** (Yetkazib berish) - kuryer tizimi

## 🌍 Test Environment

### Local Development
- **Base URL**: `http://127.0.0.1:8000`
- **Laravel artisan serve**: Port 8000

### Production Environment  
- **API Subdomain**: `api.urinboydev.uz`
- **HTTPS**: Ha, SSL sertifikat bilan

### 🔗 API Endpoint Struktura
- Barcha API endpoint'lar `/api/` prefix-isiz ishlaydi
- Versiya: `/v1/` prefix bilan
- Format: JSON (Accept: application/json)

## 📝 Qo'shimcha Ma'lumotlar

### Database Seeding
API'larni test qilishdan oldin database'ga test ma'lumotlarini yuklang:
```bash
php artisan db:seed
```

### Tillar va Tarjimalar Test Data
- **O'zbek tili** (uz) - asosiy til
- **Ingliz tili** (en) - ikkinchi til  
- **Rus tili** (ru) - uchinchi til

Har bir til uchun 40+ tarjima kalit so'zlari mavjud.

## 📊 Kolleksiyalar Statistikasi

### 🔐 Authentication Collection
- **Request'lar soni**: 6 ta
- **Folder'lar**: 1 ta (Auth)
- **Environment variables**: 2 ta
- **Test cases**: Barcha endpoint'lar uchun

### 🌐 Languages & Translations Collection  
- **Request'lar soni**: 22 ta
- **Folder'lar**: 4 ta (Languages, Translations Public, Admin Languages, Admin Translations)
- **Environment variables**: 2 ta  
- **Test cases**: Response example'lar bilan

### 📁 Fayl Hajmlari
- `postman_auth_collection.json`: ~8KB
- `postman_languages_translations_collection.json`: ~25KB
- `AUTH_API_TEST_GUIDE.md`: ~4KB
- `LANGUAGES_TRANSLATIONS_API_GUIDE.md`: ~7KB

## 🛠️ Development Team uchun

### Yangi API Module qo'shish
1. Laravel'da Controller va Route'larni yarating
2. Postman collection JSON yarating  
3. API test qilish uchun qo'llanma yozing
4. Bu README'ga yangi module ma'lumotlarini qo'shing

### File Naming Convention
- Collection files: `postman_{module_name}_collection.json`
- Guide files: `{MODULE_NAME}_API_TEST_GUIDE.md`

---

**Oxirgi yangilanish**: 30 Iyul 2025
**API Versiyasi**: v1
**Laravel Versiyasi**: 8.x
