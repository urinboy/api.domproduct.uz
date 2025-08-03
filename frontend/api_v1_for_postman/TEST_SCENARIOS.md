# DOM PRODUCT API - Test Scenarios

## 🎯 Test Scenarios uchun Qo'llanma

### Scenario 1: Yangi foydalanuvchi ro'yxatdan o'tish va xarid qilish

#### Qadam 1: Ro'yxatdan o'tish
```
1. Authentication > Register - Ro'yxatdan o'tish
2. Response'da token va user_id avtomatik saqlanadi
```

#### Qadam 2: Kategoriya va mahsulotlarni ko'rish  
```
1. Categories > Get All Categories
2. Products > Get Featured Products
3. Products > Get Product by ID (ID: 1)
```

#### Qadam 3: Manzil qo'shish
```
1. Addresses > Create Address
2. Response'da address_id avtomatik saqlanadi
```

#### Qadam 4: Savatga qo'shish va buyurtma berish
```
1. Shopping Cart > Add Item to Cart (product_id: 1, quantity: 2)
2. Shopping Cart > Get Cart Summary  
3. Orders > Create Order
4. Response'da order_id avtomatik saqlanadi
```

#### Qadam 5: To'lov
```
1. Payments > Get Payment Methods
2. Payments > Process Payment
3. Orders > Get Order Details
```

### Scenario 2: Mavjud foydalanuvchi login va profil yangilash

#### Qadam 1: Login
```
1. Authentication > Login
2. Email: ahmad@example.com, Password: password123
```

#### Qadam 2: Profil boshqaruvi
```
1. User Profile > Get Profile
2. User Profile > Update Profile  
3. User Profile > Upload Avatar (ixtiyoriy)
4. User Profile > Change Password
```

#### Qadam 3: Buyurtmalar tarixi
```
1. Orders > Get User Orders
2. Har bir buyurtma uchun > Get Order Details
```

### Scenario 3: Admin panel (Admin foydalanuvchi kerak)

#### Qadam 1: Admin login
```
1. Admin sifatida login qiling
2. auth_token admin_token'ga ko'chiring
```

#### Qadam 2: Mahsulot boshqaruvi
```
1. Admin Panel > Product Management > Get Product Statistics
2. Admin Panel > Product Management > Get Low Stock Products
3. Admin Panel > Product Management > Create Product
```

#### Qadam 3: Buyurtmalar boshqaruvi
```
1. Admin Panel > Order Management > Get All Orders
2. Admin Panel > Order Management > Update Order Status
```

### Scenario 4: Error Handling Test

#### Qadam 1: Noto'g'ri ma'lumotlar bilan test
```
1. Authentication > Login (noto'g'ri email/password)
2. Products > Get Product by ID (mavjud bo'lmagan ID: 999)
3. Shopping Cart > Add Item (mavjud bo'lmagan product_id)
```

#### Qadam 2: Authorization testlari
```
1. Token o'chiring (Variables > auth_token = "")
2. Orders > Get User Orders (401 error kutiladi)
3. Admin Panel > har qanday endpoint (403 error kutiladi)
```

## 🔄 Collection Runner uchun

### To'liq test uchun:
1. Postman'da kolleksiyani tanlang
2. **Run collection** tugmasini bosing
3. Quyidagi tartibda run qiling:

```
1. Authentication
   - Register
   - Login

2. Public APIs  
   - Categories > Get All Categories
   - Products > Get Featured Products
   - Products > Get Product by ID

3. User APIs
   - User Profile > Get Profile
   - Addresses > Create Address
   - Shopping Cart > Add Item to Cart
   - Orders > Create Order

4. Admin APIs (Admin token kerak)
   - Admin Panel > User Management > Get All Users
   - Admin Panel > Product Management > Get Product Statistics
```

### Environment setup:
1. **DOM_PRODUCT_ENVIRONMENT.json** import qiling
2. Yoki manual tarzda variables o'rnating:
   - base_url: http://localhost:8000/api
   - test_email: ahmad@example.com  
   - test_password: password123

## 📊 Performance Testing

### Load testing uchun:
1. **Runner** da **Iterations** ni 10-50 ga o'rnating
2. **Delay** ni 1000ms ga o'rnating
3. Quyidagi endpoint'larni test qiling:
   - Products > Get All Products
   - Categories > Get All Categories
   - Shopping Cart > Get Cart

### Stress testing endpoint'lari:
- `/api/v1/products` (public)
- `/api/cart` (session-based)
- `/api/orders` (authenticated)

## 🐛 Debug Ma'lumotlari

### Console log'lari:
Har bir so'rov uchun quyidagi ma'lumotlar console'da ko'rsatiladi:
```javascript
console.log('Request starting for:', pm.request.url);
console.log('Response status:', pm.response.status);
console.log('Response time:', pm.response.responseTime + 'ms');
```

### Variables tracking:
```javascript
console.log('Current auth_token:', pm.collectionVariables.get('auth_token'));
console.log('Current user_id:', pm.collectionVariables.get('user_id'));
```

## 📝 Test Reports

### Newman bilan CLI test:
```bash
npm install -g newman
newman run DOM_PRODUCT_API_COMPLETE_COLLECTION.json \
  -e DOM_PRODUCT_ENVIRONMENT.json \
  --reporters html,cli \
  --reporter-html-export test-report.html
```

### CI/CD uchun:
```yaml
# GitHub Actions example
- name: Run API Tests
  run: |
    newman run postman/DOM_PRODUCT_API_COMPLETE_COLLECTION.json \
      -e postman/DOM_PRODUCT_ENVIRONMENT.json \
      --reporters json \
      --reporter-json-export test-results.json
```

## 💡 Best Practices

1. **Test data consistency**: Har doim test ma'lumotlarini tozalang
2. **Environment variables**: Haqiqiy ma'lumotlarni hard-code qilmang  
3. **Error assertions**: Har bir test uchun kutilgan error'larni tekshiring
4. **Cleanup**: Test tugagandan so'ng yaratilgan ma'lumotlarni tozalang
5. **Parallel execution**: Auth-required testlarni parallel ishlatmang

## 🚨 Muhim eslatmalar

- **Production'da ishlatmang**: Bu test ma'lumotlari faqat development uchun
- **Rate limiting**: Tez-tez so'rov yubormaslik uchun delay qo'shing
- **Security**: Haqiqiy email/password ishlatmang
- **Database**: Test paytida database backup oling
