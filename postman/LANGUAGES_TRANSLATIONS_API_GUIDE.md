# Languages & Translations API Test qilish uchun qo'llanma

## 1. Postman Collection

`postman_languages_translations_collection.json` faylini Postman'ga import qiling.

## 2. API Endpoint'lar ro'yxati

### 🌐 PUBLIC API'lar (Token kerak emas)

#### Languages
```
GET  /api/v1/languages                    - Barcha tillar
GET  /api/v1/languages?active_only=true   - Faqat faol tillar
GET  /api/v1/languages/default            - Asosiy til
GET  /api/v1/languages/{id}               - Bitta til
```

#### Translations
```
GET  /api/v1/translations/{languageCode}         - Til bo'yicha barcha tarjimalar
GET  /api/v1/translations/{languageCode}/{group} - Guruh bo'yicha tarjimalar
```

**Mavjud til kodlari:** `uz`, `en`, `ru`
**Mavjud guruhlar:** `general`, `menu`, `auth`, `product`, `order`, `address`

### 🔐 ADMIN API'lar (Token kerak)

#### Languages Management
```
POST    /admin/languages        - Yangi til qo'shish
PUT     /admin/languages/{id}   - Tilni yangilash
DELETE  /admin/languages/{id}   - Tilni o'chirish
```

#### Translations Management
```
GET     /admin/translations                - Barcha tarjimalar (sahifalash)
POST    /admin/translations               - Yangi tarjima qo'shish
GET     /admin/translations/{id}          - Bitta tarjima
PUT     /admin/translations/{id}          - Tarjimani yangilash
DELETE  /admin/translations/{id}          - Tarjimani o'chirish
POST    /admin/translations/bulk-upsert   - Ko'p tarjima qo'shish/yangilash
```

## 3. CURL misollari

### 3.1 Public API'lar

#### Barcha tillarni olish
```bash
curl -X GET "http://127.0.0.1:8000/api/v1/languages" \
  -H "Accept: application/json"
```

#### O'zbek tilida barcha tarjimalar
```bash
curl -X GET "http://127.0.0.1:8000/api/v1/translations/uz" \
  -H "Accept: application/json"
```

#### Ingliz tilida menu guruhi
```bash
curl -X GET "http://127.0.0.1:8000/api/v1/translations/en/menu" \
  -H "Accept: application/json"
```

#### Rus tilida auth guruhi
```bash
curl -X GET "http://127.0.0.1:8000/api/v1/translations/ru/auth" \
  -H "Accept: application/json"
```

### 3.2 Admin API'lar (Token kerak)

#### Yangi til qo'shish
```bash
curl -X POST "http://127.0.0.1:8000/admin/languages" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN" \
  -d '{
    "name": "Türkçe",
    "code": "tr",
    "flag": "🇹🇷",
    "is_active": true,
    "is_default": false,
    "sort_order": 4
  }'
```

#### Yangi tarjima qo'shish
```bash
curl -X POST "http://127.0.0.1:8000/admin/translations" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN" \
  -d '{
    "key": "app.title",
    "value": "Mahsulot yetkazib berish",
    "language_id": 1,
    "group": "general"
  }'
```

#### Ko'p tarjima qo'shish (Bulk upsert)
```bash
curl -X POST "http://127.0.0.1:8000/admin/translations/bulk-upsert" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN" \
  -d '{
    "language_id": 1,
    "translations": [
      {
        "key": "app.name",
        "value": "Taom yetkazish",
        "group": "general"
      },
      {
        "key": "button.submit",
        "value": "Yuborish",
        "group": "forms"
      }
    ]
  }'
```

## 4. Frontend uchun tarjima olish namunasi

### JavaScript/React da foydalanish
```javascript
// Barcha tarjimalarni olish
const getTranslations = async (languageCode = 'uz') => {
  const response = await fetch(`/api/v1/translations/${languageCode}`);
  const data = await response.json();
  return data.translations;
};

// Guruh bo'yicha tarjimalar
const getMenuTranslations = async (languageCode = 'uz') => {
  const response = await fetch(`/api/v1/translations/${languageCode}/menu`);
  const data = await response.json();
  return data.translations;
};

// Foydalanish
const translations = await getTranslations('uz');
console.log(translations['menu.home']); // "Bosh sahifa"
```

## 5. Mavjud tarjimalar ro'yxati

### General (umumiy)
- `welcome`, `hello`, `goodbye`, `yes`, `no`, `save`, `cancel`, `delete`, `edit`, `add`

### Menu
- `menu.home`, `menu.categories`, `menu.products`, `menu.cart`, `menu.orders`, `menu.profile`

### Auth
- `auth.login`, `auth.register`, `auth.logout`, `auth.email`, `auth.password`, `auth.first_name`, `auth.last_name`, `auth.phone`

### Product
- `product.breakfast`, `product.lunch`, `product.dinner`, `product.pizza`, `product.burger`, `product.drinks`, `product.desserts`

### Order
- `order.total`, `order.delivery`, `order.pickup`, `order.status`, `order.pending`, `order.confirmed`, `order.preparing`, `order.delivered`

### Address
- `address.city`, `address.district`, `address.street`, `address.house`

## 6. Muhim eslatmalar

- **Admin API'lar**: Faqat admin token bilan ishlatiladi
- **Public API'lar**: Hech qanday autentifikatsiya talab qilmaydi
- **Caching**: Frontend'da tarjimalarni cache qilib saqlash tavsiya etiladi
- **Language codes**: ISO 639-1 standartiga muvofiq (`uz`, `en`, `ru`)
- **Bulk operations**: Ko'p tarjimani bir vaqtda qo'shish/yangilash uchun bulk-upsert ishlatiladi
