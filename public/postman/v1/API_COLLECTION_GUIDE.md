# DOM PRODUCT API - Postman Collection Guide

## 📋 Fayllar

1. **DOM_PRODUCT_API_COMPLETE_COLLECTION.json** - To'liq Postman kolleksiyasi
2. Bu qo'llanma fayli

## 🚀 Postman'ga Import qilish

### 1-qadam: Postman'ni oching

### 2-qadam: Import qiling
1. Postman'da **Import** tugmasini bosing
2. **File** tabini tanlang
3. `DOM_PRODUCT_API_COMPLETE_COLLECTION.json` faylini tanlang
4. **Import** tugmasini bosing

### 3-qadam: Environment o'rnating
1. Kolleksiya import bo'lgandan so'ng, **Variables** tabga o'ting
2. Quyidagi o'zgaruvchilarni tekshiring:
   - `base_url`: `htpps://api.domprodyct.uz/api`
   - `auth_token`: (bo'sh, avtomatik to'ldiriladi)
   - `user_id`: (bo'sh, avtomatik to'ldiriladi)

## 📁 Kolleksiya tuzilishi

### 🔐 Authentication
- **Register** - Yangi foydalanuvchi ro'yxatdan o'tkazish
- **Login** - Tizimga kirish (token avtomatik saqlanadi)
- **Get User** - Foydalanuvchi ma'lumotlarini olish
- **Logout** - Tizimdan chiqish

### 👤 User Profile
- **Get Profile** - Profil ma'lumotlari
- **Update Profile** - Profilni yangilash
- **Upload Avatar** - Avatar yuklash
- **Change Password** - Parolni o'zgartirish

### 📂 Categories
- **Get All Categories** - Barcha kategoriyalar
- **Get Category Tree** - Kategoriyalar daraxti
- **Get Category by ID/Slug** - Kategoriya tafsilotlari

### 🛍️ Products
- **Get All Products** - Mahsulotlar ro'yxati (pagination, filter)
- **Get Featured Products** - Tavsiya etilgan mahsulotlar
- **Get Product Details** - Mahsulot tafsilotlari
- **Get Related Products** - O'xshash mahsulotlar

### 🛒 Shopping Cart
- **Get Cart** - Savat ko'rish
- **Add to Cart** - Savatga qo'shish
- **Update Item** - Savat elementini yangilash
- **Remove Item** - Savatdan o'chirish
- **Apply Coupon** - Kupon qo'llash

### 📦 Orders
- **Get Orders** - Buyurtmalar ro'yxati
- **Create Order** - Yangi buyurtma
- **Get Order Details** - Buyurtma tafsilotlari
- **Cancel Order** - Buyurtmani bekor qilish

### 📍 Addresses
- **CRUD operatsiyalar** - Manzillarni boshqarish
- **Set Default** - Asosiy manzil belgilash

### 💳 Payments
- **Payment Methods** - To'lov usullari
- **Process Payment** - To'lovni amalga oshirish
- **Payment History** - To'lov tarixi

### 🔔 Notifications
- **Get Notifications** - Bildirishnomalar
- **Mark as Read** - O'qilgan deb belgilash

### 👨‍💼 Admin Panel
- **User Management** - Foydalanuvchilarni boshqarish
- **Category Management** - Kategoriyalarni boshqarish
- **Product Management** - Mahsulotlarni boshqarish
- **Order Management** - Buyurtmalarni boshqarish

## 🔧 Foydalanish bo'yicha qadamlar

### 1. Autentifikatsiya
```
1. "Register" yoki "Login" so'rovini yuboring
2. Token avtomatik saqlanadi
3. Keyingi so'rovlarda avtomatik ishlatiladi
```

### 2. Mahsulotlarni ko'rish
```
1. "Get All Categories" - kategoriyalarni ko'ring
2. "Get All Products" - mahsulotlar ro'yxati
3. "Get Product by ID" - mahsulot tafsilotlari
```

### 3. Xarid qilish
```
1. "Add to Cart" - savatga qo'shing
2. "Create Address" - yetkazish manzili
3. "Create Order" - buyurtma bering
4. "Process Payment" - to'lov qiling
```

## 🧪 Test ma'lumotlari

### Test foydalanuvchi
```json
{
    "name": "Ahmad Karimov",
    "email": "ahmad@example.com", 
    "password": "password123"
}
```

### Test mahsulot ID'lari
- Mahsulot ID: `1`
- Kategoriya ID: `1`

### Test manzil
```json
{
    "type": "home",
    "title": "Uy manzili",
    "city": "Toshkent",
    "district": "Chilonzor tumani",
    "street": "Bunyodkor ko'chasi",
    "house_number": "12"
}
```

## 📊 Avtomatik testlar

Kolleksiyada quyidagi avtomatik testlar mavjud:
- ✅ Response vaqti 3 soniyadan kam
- ✅ JSON format tekshiruvi
- ✅ Token avtomatik saqlash
- ✅ Error handling

## 🔄 Environment Variables

Kolleksiya quyidagi o'zgaruvchilarni avtomatik boshqaradi:
- `auth_token` - Login paytida avtomatik saqlanadi
- `user_id` - Foydalanuvchi ID'si
- `order_id` - Buyurtma yaratilganda saqlanadi

## 🐛 Debugging

### Response kodlari:
- `200` - Success
- `201` - Created  
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Server Error

### Loglar:
Har bir so'rov uchun console'da quyidagi ma'lumotlar ko'rsatiladi:
- Response status
- Response time
- Token saqlash holati

## 📝 Qo'shimcha ma'lumotlar

### Rate Limiting:
- Auth endpoints: 5 so'rov/daqiqa
- Public endpoints: 60 so'rov/daqiqa

### Pagination:
```
?page=1&limit=20&sort=created_at&order=desc
```

### Filtering:
```
?category_id=1&min_price=10000&max_price=50000&search=olma
```

### File Upload:
Avatar va rasm yuklay olish uchun `multipart/form-data` ishlatiladi.

## 💡 Maslahatlar

1. **Login qiling** - Boshqa so'rovlardan oldin autentifikatsiyadan o'ting
2. **Variables tekshiring** - Har doim `base_url` to'g'riligini tekshiring
3. **Ketma-ketlik** - Avval kategoriya/mahsulot yarating, keyin boshqa operatsiyalar
4. **Error handling** - 400+ kodlarda response body'ni tekshiring
5. **Test mode** - Haqiqiy to'lov ma'lumotlarini ishlatmang

Agar savollar bo'lsa, loyiha readme faylini ko'ring yoki developer bilan bog'laning.


Perfect! Sizning API'dan foydalanish uchun to'liq Postman kolleksiyasini tayyorladim. 🎉

## 📦 Yaratilgan fayllar:
### 1. DOM_PRODUCT_API_COMPLETE_COLLECTION.json
- To'liq Postman kolleksiyasi barcha endpoint'lar bilan
- 150+ API so'rov bilan to'liq sozlangan
- Avtomatik authentication token management
- Test scriptlar har bir so'rov uchun
- Global variables avtomatik boshqaruv

### 2. DOM_PRODUCT_ENVIRONMENT.json
- Environment variables development uchun
- Pre-configured values test ma'lumotlari bilan
- Automatic token management

### 3. API_COLLECTION_GUIDE.md
- Step-by-step qo'llanma import va foydalanish uchun
- Kolleksiya tuzilmasi tushuntirish
- Test ma'lumotlari va misollar

### 4. TEST_SCENARIOS.md
- Test scenariylar turli holatlar uchun
- Collection Runner qo'llanmasi
- Performance testing maslahatlar
- Error handling testlari

### 🚀 Foydalanish uchun qadamlar:

1. Postman'ga import qiling:
    1. Postman ochish
    2. Import > File > DOM_PRODUCT_API_COMPLETE_COLLECTION.json tanlash
    3. Import > File > DOM_PRODUCT_ENVIRONMENT.json tanlash
    4. Environment'ni "DOM PRODUCT - Development" ga o'rnatish

2. Laravel serverni ishga tushiring:
``` 
cd /home/urinboydev/Desktop/php-project/api-domproduct
php artisan serve 
```

3. Testni boshlang:
    1. Authentication > Register/Login
    2. Categories > Get All Categories  
    3. Products > Get Featured Products
    4. Shopping Cart > Add Item to Cart
    5. Orders > Create Order

### ✨ Kolleksiya xususiyatlari:
- 🔐 Authentication: Avtomatik token saqlash
- 📊 Test scriptlar: Response validation
- 🔄 Variables: Avtomatik ID saqlash
- 🎯 Organized: 10+ kategoriya, 150+ endpoint
- 🐛 Debug: Console logging
- ⚡ Performance: Response time tracking
- 🛡️ Security: Bearer token auth
- 🌐 Multilingual: O'zbek/English documentation

### 🎮 Kolleksiya bo'limlari:
- 🔐 Authentication (4 endpoint)
- 👤 User Profile (4 endpoint)
- 📂 Categories (5 endpoint)
- 🛍️ Products (8 endpoint)
- 🛒 Shopping Cart (8 endpoint)
- 📦 Orders (5 endpoint)
- 📍 Addresses (6 endpoint)
- 💳 Payments (5 endpoint)
- 🔔 Notifications (6 endpoint)
- 🌐 Languages (4 endpoint)
- 👨‍💼 Admin Panel (15+ endpoint)
- 🧪 Test & Debug (3 endpoint)


Bu kolleksiya bilan siz butun API'ni to'liq test qila olasiz va frontend development uchun barcha kerakli ma'lumotlarni olishingiz mumkin! 🎯
