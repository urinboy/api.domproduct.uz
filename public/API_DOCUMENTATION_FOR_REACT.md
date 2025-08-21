# DOM Product API Documentation - React Frontend uchun

Ushbu hujjat React frontend loyihasi uchun tayyor bo'lgan barcha API endpointlar va ularning to'liq ma'lumotlarini o'z ichiga oladi.

## Base URL
```
https://api.domproduct.uz/api
```

## Authentication
Barcha auth kerak bo'lgan so'rovlar uchun header:
```javascript
Authorization: Bearer {token}
```

---

## 1. AUTHENTICATION API

### 1.1 Register
**POST** `/auth/register`

**Request:**
```javascript
{
  "name": "John Doe",
  "email": "john@example.com", 
  "phone": "+998901234567",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Response:**
```javascript
{
  "success": true,
  "message": "User registered successfully",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "phone": "+998901234567"
    },
    "token": "1|abc123...",
    "token_type": "Bearer"
  }
}
```

### 1.2 Login
**POST** `/auth/login`

**Request:**
```javascript
{
  "email": "john@example.com",
  "password": "password123"
}
```

**Response:**
```javascript
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com"
    },
    "token": "1|abc123...",
    "token_type": "Bearer"
  }
}
```

### 1.3 Get Current User
**GET** `/auth/user` (Auth required)

**Response:**
```javascript
{
  "id": 1,
  "name": "John Doe",
  "email": "john@example.com",
  "phone": "+998901234567",
  "avatar": "avatars/user1.jpg"
}
```

### 1.4 Logout
**POST** `/auth/logout` (Auth required)

**Response:**
```javascript
{
  "message": "Logged out successfully"
}
```

---

## 2. PRODUCTS API

### 2.1 Get Products List
**GET** `/v1/products`

**Query Parameters:**
- `language` (string): uz, ru, en (default: uz)
- `category_id` (integer): Category filter
- `search` (string): Search term
- `min_price` (number): Minimum price
- `max_price` (number): Maximum price  
- `sort_by` (string): name, price, created_at
- `sort_order` (string): asc, desc
- `page` (integer): Page number
- `per_page` (integer): Items per page (max 50)
- `is_featured` (boolean): Featured products filter

**Response:**
```javascript
{
  "data": [
    {
      "id": 1,
      "sku": "PROD001", 
      "price": 150000,
      "sale_price": 120000,
      "is_active": true,
      "is_featured": true,
      "stock_status": "in_stock",
      "quantity": 50,
      "category": {
        "id": 1,
        "name": "Electronics",
        "slug": "electronics"
      },
      "translations": [
        {
          "language": "uz",
          "name": "Smartphone",
          "description": "Zamonaviy smartphone",
          "slug": "smartphone"
        }
      ],
      "images": [
        {
          "id": 1,
          "file_path": "/images/products/phone1.jpg",
          "is_primary": true
        }
      ]
    }
  ],
  "meta": {
    "current_page": 1,
    "per_page": 10,
    "total": 25,
    "last_page": 3,
    "from": 1,
    "to": 10
  }
}
```

### 2.2 Get Product Details
**GET** `/v1/products/{id}`

**Response:**
```javascript
{
  "id": 1,
  "sku": "PROD001",
  "price": 150000,
  "sale_price": 120000,
  "quantity": 50,
  "unit_type": "piece",
  "unit_value": 1,
  "weight": 0.5,
  "dimensions": {
    "length": 15,
    "width": 8,
    "height": 1
  },
  "specifications": {
    "brand": "Samsung",
    "model": "Galaxy S21",
    "color": "Black"
  },
  "category": {
    "id": 1,
    "name": "Electronics"
  },
  "translations": [
    {
      "language": "uz", 
      "name": "Samsung Galaxy S21",
      "description": "Zamonaviy smartphone...",
      "short_description": "Eng yaxshi telefon",
      "features": ["5G", "Triple Camera", "120Hz Display"],
      "specifications": {...},
      "meta_title": "Samsung Galaxy S21 - Sotib oling",
      "meta_description": "..."
    }
  ],
  "images": [
    {
      "id": 1,
      "file_path": "/images/products/phone1.jpg", 
      "alt_text": "Front view",
      "is_primary": true,
      "sort_order": 1
    }
  ]
}
```

### 2.3 Get Featured Products
**GET** `/v1/products/featured`

**Query Parameters:**
- `language` (string): uz, ru, en
- `limit` (integer): Max items (default: 6, max: 20)

**Response:**
```javascript
{
  "data": [
    {
      "id": 1,
      "name": "Product Name",
      "price": 150000,
      "sale_price": 120000,
      "image": "/images/products/product1.jpg",
      "rating": 4.5,
      "reviews_count": 23
    }
  ]
}
```

### 2.4 Get Related Products
**GET** `/v1/products/{id}/related`

**Query Parameters:**
- `language` (string): Language code
- `limit` (integer): Max items (default: 6)

**Response:**
```javascript
{
  "data": [
    {
      "id": 2,
      "name": "Related Product",
      "price": 100000,
      "image": "/images/products/product2.jpg"
    }
  ]
}
```

### 2.5 Get Products by Category
**GET** `/v1/products/category/{categoryId}`

**Query Parameters:**
- Same as products list
- `include_subcategories` (boolean): Include child categories

**Response:** Same format as products list

---

## 3. CATEGORIES API

### 3.1 Get Categories List
**GET** `/v1/categories`

**Query Parameters:**
- `language` (string): Language code
- `parent_id` (integer): Parent category filter
- `include_children` (boolean): Include subcategories

**Response:**
```javascript
{
  "data": [
    {
      "id": 1,
      "name": "Electronics",
      "slug": "electronics", 
      "image": "/images/categories/electronics.jpg",
      "description": "Electronic devices",
      "parent_id": null,
      "sort_order": 1,
      "products_count": 25,
      "children": [
        {
          "id": 2,
          "name": "Smartphones",
          "slug": "smartphones",
          "parent_id": 1
        }
      ]
    }
  ]
}
```

### 3.2 Get Category Tree
**GET** `/v1/categories/tree`

**Response:**
```javascript
{
  "data": [
    {
      "id": 1,
      "name": "Electronics",
      "children": [
        {
          "id": 2, 
          "name": "Smartphones",
          "children": []
        }
      ]
    }
  ]
}
```

### 3.3 Get Category Details
**GET** `/v1/categories/{id}`

**Response:**
```javascript
{
  "id": 1,
  "name": "Electronics",
  "slug": "electronics",
  "description": "Electronic devices and gadgets",
  "image": "/images/categories/electronics.jpg",
  "parent_id": null,
  "products_count": 25,
  "children": [...],
  "breadcrumbs": [
    {"id": 1, "name": "Electronics", "slug": "electronics"}
  ]
}
```

---

## 4. SHOPPING CART API

### 4.1 Get Cart Contents
**GET** `/cart`

**Response:**
```javascript
{
  "success": true,
  "data": {
    "items": [
      {
        "id": 1,
        "product_id": 5,
        "quantity": 2,
        "unit_price": 150000,
        "total_price": 300000,
        "product": {
          "id": 5,
          "name": "Product Name",
          "image": "/images/products/product5.jpg",
          "stock_quantity": 10
        }
      }
    ],
    "summary": {
      "subtotal": 300000,
      "tax": 0,
      "shipping": 25000,
      "discount": 0,
      "total": 325000,
      "items_count": 2
    }
  }
}
```

### 4.2 Add Item to Cart
**POST** `/cart/add`

**Request:**
```javascript
{
  "product_id": 5,
  "quantity": 2,
  "session_id": "abc123..." // Agar user login qilmagan bo'lsa
}
```

**Response:**
```javascript
{
  "success": true,
  "message": "Product added to cart",
  "data": {
    "item": {
      "id": 1,
      "product_id": 5,
      "quantity": 2,
      "unit_price": 150000,
      "total_price": 300000
    },
    "cart_summary": {
      "items_count": 3,
      "total": 450000
    }
  }
}
```

### 4.3 Update Cart Item
**PUT** `/cart/items/{itemId}`

**Request:**
```javascript
{
  "quantity": 3
}
```

**Response:**
```javascript
{
  "success": true,
  "message": "Cart updated",
  "data": {
    "item": {
      "id": 1,
      "quantity": 3,
      "total_price": 450000
    },
    "cart_summary": {
      "items_count": 3,
      "total": 600000
    }
  }
}
```

### 4.4 Remove Item from Cart
**DELETE** `/cart/items/{itemId}`

**Response:**
```javascript
{
  "success": true,
  "message": "Item removed from cart"
}
```

### 4.5 Clear Cart
**DELETE** `/cart/clear`

**Response:**
```javascript
{
  "success": true,
  "message": "Cart cleared"
}
```

---

## 5. ORDERS API (Auth Required)

### 5.1 Get User Orders
**GET** `/orders`

**Query Parameters:**
- `status` (string): pending, processing, shipped, delivered, cancelled
- `page` (integer): Page number
- `per_page` (integer): Items per page

**Response:**
```javascript
{
  "data": [
    {
      "id": 1,
      "order_number": "ORD-001",
      "status": "processing",
      "total": 325000,
      "created_at": "2023-08-20T10:30:00Z",
      "items_count": 2,
      "shipping_address": {
        "city": "Tashkent",
        "street_address": "Amir Temur 1"
      }
    }
  ],
  "meta": {
    "current_page": 1,
    "total": 5,
    "last_page": 1
  }
}
```

### 5.2 Create Order
**POST** `/orders`

**Request:**
```javascript
{
  "shipping_address_id": 1,
  "billing_address_id": 1,
  "payment_method": "cash",
  "notes": "Call before delivery"
}
```

**Response:**
```javascript
{
  "success": true,
  "message": "Order created successfully",
  "data": {
    "order": {
      "id": 1,
      "order_number": "ORD-001",
      "status": "pending",
      "total": 325000,
      "payment_status": "pending",
      "items": [...]
    }
  }
}
```

### 5.3 Get Order Details
**GET** `/orders/{id}`

**Response:**
```javascript
{
  "id": 1,
  "order_number": "ORD-001",
  "status": "processing",
  "payment_status": "paid",
  "total": 325000,
  "subtotal": 300000,
  "tax": 0,
  "shipping_cost": 25000,
  "created_at": "2023-08-20T10:30:00Z",
  "items": [
    {
      "id": 1,
      "product_id": 5,
      "product_name": "Product Name",
      "quantity": 2,
      "unit_price": 150000,
      "total_price": 300000,
      "product": {
        "image": "/images/products/product5.jpg"
      }
    }
  ],
  "shipping_address": {
    "full_name": "John Doe",
    "phone": "+998901234567",
    "city": "Tashkent",
    "street_address": "Amir Temur 1",
    "formatted_address": "John Doe, +998901234567, Tashkent, Amir Temur 1"
  },
  "status_history": [
    {
      "status": "pending",
      "created_at": "2023-08-20T10:30:00Z",
      "note": "Order created"
    }
  ]
}
```

---

## 6. USER PROFILE API (Auth Required)

### 6.1 Get User Profile
**GET** `/user/profile`

**Response:**
```javascript
{
  "id": 1,
  "name": "John Doe",
  "first_name": "John",
  "last_name": "Doe", 
  "email": "john@example.com",
  "phone": "+998901234567",
  "avatar": "/avatars/user1.jpg",
  "birth_date": "1990-01-15",
  "gender": "male",
  "address": "Tashkent, Uzbekistan",
  "city": "Tashkent",
  "district": "Yunusabad",
  "postal_code": "100000",
  "preferred_language": "uz",
  "created_at": "2023-01-01T00:00:00Z"
}
```

### 6.2 Update Profile
**PUT** `/user/profile`

**Request:**
```javascript
{
  "name": "John Doe Updated",
  "first_name": "John",
  "last_name": "Doe", 
  "phone": "+998901234568",
  "birth_date": "1990-01-15",
  "gender": "male",
  "address": "New Address",
  "city": "Tashkent",
  "preferred_language": "en"
}
```

**Response:**
```javascript
{
  "success": true,
  "message": "Profile updated successfully",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe Updated",
      // ... updated fields
    }
  }
}
```

### 6.3 Upload Avatar
**POST** `/user/avatar`

**Request:**
```javascript
// Form data with 'avatar' file field
FormData: {
  avatar: File
}
```

**Response:**
```javascript
{
  "success": true,
  "message": "Avatar uploaded successfully", 
  "data": {
    "avatar_url": "/avatars/user1_123456.jpg"
  }
}
```

### 6.4 Change Password
**PUT** `/user/password`

**Request:**
```javascript
{
  "current_password": "oldpassword",
  "password": "newpassword123",
  "password_confirmation": "newpassword123"
}
```

**Response:**
```javascript
{
  "success": true,
  "message": "Password changed successfully"
}
```

---

## 7. ADDRESS MANAGEMENT API (Auth Required)

### 7.1 Get User Addresses
**GET** `/addresses`

**Response:**
```javascript
{
  "success": true,
  "data": {
    "addresses": [
      {
        "id": 1,
        "type": "both",
        "first_name": "John",
        "last_name": "Doe",
        "full_name": "John Doe",
        "company": null,
        "phone": "+998901234567",
        "email": "john@example.com",
        "country": "Uzbekistan", 
        "region": "Tashkent",
        "city": "Tashkent",
        "district": "Yunusabad",
        "street_address": "Amir Temur street 1",
        "apartment": "12A",
        "postal_code": "100000",
        "coordinates": null,
        "delivery_instructions": "Call before delivery",
        "is_default": true,
        "formatted_address": "John Doe, Tashkent, Amir Temur street 1, 12A"
      }
    ]
  }
}
```

### 7.2 Add New Address
**POST** `/addresses`

**Request:**
```javascript
{
  "type": "both", // shipping, billing, both
  "first_name": "John",
  "last_name": "Doe",
  "phone": "+998901234567",
  "email": "john@example.com",
  "country": "Uzbekistan",
  "region": "Tashkent", 
  "city": "Tashkent",
  "district": "Yunusabad",
  "street_address": "Amir Temur street 1",
  "apartment": "12A",
  "postal_code": "100000",
  "delivery_instructions": "Call before delivery",
  "is_default": true
}
```

**Response:**
```javascript
{
  "success": true,
  "message": "Address added successfully",
  "data": {
    "address": {
      "id": 1,
      // ... address data
    }
  }
}
```

### 7.3 Update Address
**PUT** `/addresses/{id}`

**Request:** Same as add address

**Response:**
```javascript
{
  "success": true,
  "message": "Address updated successfully"
}
```

### 7.4 Delete Address
**DELETE** `/addresses/{id}`

**Response:**
```javascript
{
  "success": true,
  "message": "Address deleted successfully"
}
```

### 7.5 Set Default Address
**POST** `/addresses/{id}/set-default`

**Response:**
```javascript
{
  "success": true,
  "message": "Default address updated"
}
```

---

## 8. PAYMENT API (Auth Required)

### 8.1 Get Payment Methods
**GET** `/payments/methods`

**Response:**
```javascript
{
  "data": [
    {
      "id": "cash",
      "name": "Naqd to'lov",
      "description": "Mahsulot yetkazib berilganda naqd to'lov",
      "icon": "cash",
      "is_active": true,
      "min_amount": 0,
      "max_amount": 10000000
    },
    {
      "id": "card", 
      "name": "Plastik karta",
      "description": "Visa, MasterCard, UzCard orqali to'lov",
      "icon": "credit-card",
      "is_active": true,
      "min_amount": 1000,
      "max_amount": 50000000
    }
  ]
}
```

### 8.2 Process Payment
**POST** `/payments/process`

**Request:**
```javascript
{
  "order_id": 1,
  "payment_method": "card",
  "return_url": "https://yoursite.com/payment/success"
}
```

**Response:**
```javascript
{
  "success": true,
  "data": {
    "payment_id": "pay_123",
    "status": "pending",
    "redirect_url": "https://payment-gateway.com/pay/123", // Card payments uchun
    "message": "Payment initiated"
  }
}
```

---

## 9. NOTIFICATIONS API (Auth Required)

### 9.1 Get Notifications
**GET** `/notifications`

**Query Parameters:**
- `page` (integer): Page number
- `per_page` (integer): Items per page
- `unread_only` (boolean): Only unread notifications

**Response:**
```javascript
{
  "data": [
    {
      "id": 1,
      "type": "order_status",
      "title": "Order Status Update",
      "message": "Your order #ORD-001 has been shipped",
      "data": {
        "order_id": 1,
        "order_number": "ORD-001"
      },
      "read_at": null,
      "created_at": "2023-08-20T10:30:00Z"
    }
  ],
  "meta": {
    "current_page": 1,
    "total": 10,
    "unread_count": 3
  }
}
```

### 9.2 Get Unread Count
**GET** `/notifications/unread-count`

**Response:**
```javascript
{
  "unread_count": 5
}
```

### 9.3 Mark as Read
**POST** `/notifications/{id}/mark-read`

**Response:**
```javascript
{
  "success": true,
  "message": "Notification marked as read"
}
```

### 9.4 Mark All as Read
**POST** `/notifications/mark-all-read`

**Response:**
```javascript
{
  "success": true,
  "message": "All notifications marked as read"
}
```

---

## 10. LANGUAGES API

### Newsletter Subscription
Подписка и управление рассылкой новостей.

#### Subscribe to Newsletter
```http
POST /api/v1/languages/subscribe
Content-Type: application/json

{
    "email": "user@example.com",
    "name": "User Name" // optional
}
```

**Success Response (201 Created - New subscription):**
```json
{
    "success": true,
    "message": "Muvaffaqiyatli obuna bo'ldingiz!",
    "data": {
        "email": "user@example.com",
        "status": "active",
        "subscribed_at": "2025-08-21T03:00:10.000000Z"
    }
}
```

**Success Response (200 OK - Reactivated subscription):**
```json
{
    "success": true,
    "message": "Siz yana obuna bo'ldingiz!",
    "data": {
        "email": "user@example.com",
        "status": "active",
        "subscribed_at": "2025-08-21T03:02:33.000000Z"
    }
}
```

**Error Response (409 Conflict - Already subscribed):**
```json
{
    "success": false,
    "message": "Siz allaqachon obuna bo'lgansiz!",
    "errors": {
        "email": ["Bu email allaqachon obuna bo'lgan."]
    }
}
```

#### Unsubscribe from Newsletter
```http
POST /api/v1/languages/unsubscribe
Content-Type: application/json

{
    "email": "user@example.com"
}
```

**Success Response (200 OK):**
```json
{
    "success": true,
    "message": "Siz muvaffaqiyatli obunani bekor qildingiz.",
    "data": {
        "email": "user@example.com",
        "status": "unsubscribed",
        "unsubscribed_at": "2025-08-21T03:01:12.000000Z"
    }
}
```

**Error Response (404 Not Found):**
```json
{
    "success": false,
    "message": "Bu email bilan obuna topilmadi.",
    "errors": {
        "email": ["Bu email bilan obuna mavjud emas."]
    }
}
```

#### Check Subscription Status
```http
POST /api/v1/languages/status
Content-Type: application/json

{
    "email": "user@example.com"
}
```

**Success Response (200 OK - Active subscription):**
```json
{
    "success": true,
    "message": "Obuna statusi olindi.",
    "data": {
        "email": "user@example.com",
        "subscribed": true,
        "status": "active",
        "subscribed_at": "2025-08-21T03:02:33.000000Z",
        "unsubscribed_at": null
    }
}
```

**Response (404 Not Found - No subscription):**
```json
{
    "success": false,
    "message": "Bu email bilan obuna topilmadi.",
    "data": {
        "subscribed": false,
        "status": null
    }
}
```

#### React Hook Example - Newsletter
```jsx
// hooks/useLanguages.js
import { useState } from 'react';
import { toast } from 'react-toastify';

export const useLanguages = () => {
    const [loading, setLoading] = useState(false);

    const subscribe = async (email, name = '') => {
        setLoading(true);
        try {
            const response = await fetch('/api/v1/languages/subscribe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ email, name }),
            });

            const result = await response.json();

            if (result.success) {
                toast.success(result.message);
                return result.data;
            } else {
                toast.error(result.message);
                return null;
            }
        } catch (error) {
            toast.error('Xatolik yuz berdi. Qaytadan urinib ko\'ring.');
            return null;
        } finally {
            setLoading(false);
        }
    };

    const unsubscribe = async (email) => {
        setLoading(true);
        try {
            const response = await fetch('/api/v1/languages/unsubscribe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ email }),
            });

            const result = await response.json();

            if (result.success) {
                toast.success(result.message);
                return result.data;
            } else {
                toast.error(result.message);
                return null;
            }
        } catch (error) {
            toast.error('Xatolik yuz berdi. Qaytadan urinib ko'ring.');
            return null;
        } finally {
            setLoading(false);
        }
    };

    const checkStatus = async (email) => {
        try {
            const response = await fetch('/api/v1/languages/status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ email }),
            });

            const result = await response.json();
            return result.success ? result.data : null;
        } catch (error) {
            console.error('Languages status check error:', error);
            return null;
        }
    };

    return { subscribe, unsubscribe, checkStatus, loading };
};

// components/LanguagesForm.jsx
import React, { useState } from 'react';
import { useLanguages } from '../hooks/useLanguages';

const LanguagesForm = () => {
    const [email, setEmail] = useState('');
    const [name, setName] = useState('');
    const { subscribe, loading } = useLanguages();

    const handleSubmit = async (e) => {
        e.preventDefault();
        if (!email) {
            alert('Email manzilini kiriting');
            return;
        }

        const result = await subscribe(email, name);
        if (result) {
            setEmail('');
            setName('');
        }
    };

    return (
        <form onSubmit={handleSubmit} className="languages-form">
            <div className="form-group">
                <input
                    type="email"
                    value={email}
                    onChange={(e) => setEmail(e.target.value)}
                    placeholder="Email manzilingiz"
                    required
                    className="form-control"
                />
            </div>
            <div className="form-group">
                <input
                    type="text"
                    value={name}
                    onChange={(e) => setName(e.target.value)}
                    placeholder="Ismingiz (ixtiyoriy)"
                    className="form-control"
                />
            </div>
            <button 
                type="submit" 
                disabled={loading}
                className="btn btn-primary"
            >
                {loading ? 'Kuting...' : 'Obuna bo'lish'}
            </button>
        </form>
    );
};

export default LanguagesForm;

### 10.1 Get Languages
**GET** `/v1/languages`

**Response:**
```javascript
{
  "data": [
    {
      "id": 1,
      "code": "uz",
      "name": "O'zbekcha",
      "native_name": "O'zbekcha",
      "flag": "🇺🇿",
      "is_active": true,
      "is_default": true
    },
    {
      "id": 2,
      "code": "ru", 
      "name": "Русский",
      "native_name": "Русский",
      "flag": "🇷🇺",
      "is_active": true,
      "is_default": false
    }
  ]
}
```

---

## Error Handling

### Standard Error Response Format:
```javascript
{
  "success": false,
  "error": "Error Type",
  "message": "Human readable error message",
  "errors": {
    "field_name": ["Validation error message"]
  }
}
```

### Common HTTP Status Codes:
- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden  
- `404` - Not Found
- `422` - Validation Error
- `429` - Too Many Requests
- `500` - Internal Server Error

---

## Rate Limiting

- **Auth endpoints:** 5 requests per minute
- **Public API:** 60 requests per minute
- **Authenticated API:** Default Laravel rate limiting

---

## Usage Examples for React

### 1. Axios Setup
```javascript
import axios from 'axios';

const api = axios.create({
  baseURL: 'http://localhost:8000/api',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

// Add auth token
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('auth_token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});
```

### 2. Products Hook Example
```javascript
import { useState, useEffect } from 'react';
import api from './api';

export const useProducts = (filters = {}) => {
  const [products, setProducts] = useState([]);
  const [loading, setLoading] = useState(false);
  const [meta, setMeta] = useState({});

  useEffect(() => {
    const fetchProducts = async () => {
      setLoading(true);
      try {
        const response = await api.get('/v1/products', { params: filters });
        setProducts(response.data.data);
        setMeta(response.data.meta);
      } catch (error) {
        console.error('Error fetching products:', error);
      } finally {
        setLoading(false);
      }
    };

    fetchProducts();
  }, [JSON.stringify(filters)]);

  return { products, loading, meta };
};
```

### 3. Cart Context Example
```javascript
import { createContext, useContext, useReducer } from 'react';
import api from './api';

const CartContext = createContext();

const cartReducer = (state, action) => {
  switch (action.type) {
    case 'SET_CART':
      return action.payload;
    case 'ADD_ITEM':
      return {
        ...state,
        items: [...state.items, action.payload],
        summary: action.summary
      };
    // ... other cases
  }
};

export const CartProvider = ({ children }) => {
  const [cart, dispatch] = useReducer(cartReducer, { items: [], summary: {} });

  const addToCart = async (productId, quantity) => {
    try {
      const response = await api.post('/cart/add', { product_id: productId, quantity });
      dispatch({ type: 'ADD_ITEM', payload: response.data.data.item, summary: response.data.data.cart_summary });
    } catch (error) {
      console.error('Error adding to cart:', error);
    }
  };

  return (
    <CartContext.Provider value={{ cart, addToCart }}>
      {children}
    </CartContext.Provider>
  );
};

export const useCart = () => useContext(CartContext);
```

Bu API documentation React loyihangiz uchun to'liq va tayyor. Har bir endpoint uchun request/response format, validatsiya qoidalari va usage example'lar kiritilgan.
