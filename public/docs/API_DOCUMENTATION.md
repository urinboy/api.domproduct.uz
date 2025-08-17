# DOM PRODUCT API Documentation

## 🌐 Base URLs

- **Development**: `http://localhost:8000`
- **Production**: `https://api.domproduct.uz`

## 📋 API Endpoints Overview

### Authentication Endpoints (`/auth/`)
- `POST /auth/register` - User registration
- `POST /auth/login` - User login  
- `GET /auth/user` - Get authenticated user profile
- `POST /auth/logout` - Logout user
- `POST /auth/logout-all` - Logout from all devices

### Public API Endpoints (`/v1/`)

#### Languages
- `GET /v1/languages` - Get all languages
- `GET /v1/languages/default` - Get default language
- `GET /v1/languages/{id}` - Get specific language

#### Translations
- `GET /v1/translations/{languageCode}` - Get translations by language
- `GET /v1/translations/{languageCode}/{group}` - Get translations by language and group

#### Categories
- `GET /v1/categories` - Get all categories (paginated)
- `GET /v1/categories/tree` - Get category tree structure
- `GET /v1/categories/{id}` - Get category by ID
- `GET /v1/categories/slug/{slug}` - Get category by slug
- `GET /v1/categories/{id}/breadcrumbs` - Get category breadcrumbs

#### Products
- `GET /v1/products` - Get all products (with filtering, search, pagination)
- `GET /v1/products/featured` - Get featured products
- `GET /v1/products/{id}` - Get product by ID
- `GET /v1/products/slug/{slug}` - Get product by slug
- `GET /v1/products/{id}/related` - Get related products
- `GET /v1/products/category/{categoryId}` - Get products by category

### Admin API Endpoints (`/admin/`) - Auth Required

#### User Management
- `GET /admin/users` - Get all users
- `GET /admin/users/statistics` - Get user statistics
- `GET /admin/users/{id}` - Get user by ID
- `PUT /admin/users/{id}` - Update user
- `DELETE /admin/users/{id}` - Delete user (admin only)

#### Category Management
- `GET /admin/categories` - Get admin categories
- `POST /admin/categories` - Create category
- `GET /admin/categories/statistics` - Get category statistics
- `GET /admin/categories/{id}` - Get category details
- `PUT /admin/categories/{id}` - Update category
- `DELETE /admin/categories/{id}` - Delete category
- `POST /admin/categories/{id}/image` - Upload category image
- `DELETE /admin/categories/{id}/image` - Delete category image

#### Product Management
- `GET /admin/products` - Get admin products
- `POST /admin/products` - Create product
- `GET /admin/products/statistics` - Get product statistics
- `GET /admin/products/{id}` - Get product details
- `PUT /admin/products/{id}` - Update product
- `DELETE /admin/products/{id}` - Delete product
- `POST /admin/products/{id}/images` - Upload product image
- `DELETE /admin/products/{id}/images/{imageId}` - Delete product image
- `PUT /admin/products/{id}/images/{imageId}/primary` - Set primary image

## 🔐 Authentication

Use Bearer token in Authorization header:
```
Authorization: Bearer {your_token}
```

## 🌍 Multi-language Support

Use `Accept-Language` header:
```
Accept-Language: uz  # Uzbek (default)
Accept-Language: en  # English
Accept-Language: ru  # Russian
```

## 📊 Query Parameters

### Products API Query Parameters

#### Filtering
- `category_id` - Filter by category ID
- `category_slug` - Filter by category slug
- `featured` - Filter featured products (boolean)
- `in_stock` - Filter in-stock products (boolean)
- `min_price` - Minimum price filter
- `max_price` - Maximum price filter

#### Search
- `search` - Search in product name, short_description, description

#### Sorting
- `sort_by` - Sort field: `created_at`, `price`, `name`, `rating`, `popularity`
- `sort_order` - Sort direction: `asc`, `desc`

#### Pagination
- `per_page` - Items per page (max 50 for public, 100 for admin)
- `page` - Page number

### Example Product Search Request
```
GET /v1/products?search=pomidor&category_id=1&min_price=10000&max_price=20000&sort_by=price&sort_order=asc&per_page=15
```

## 📄 Response Format

### Success Response
```json
{
  "success": true,
  "message": "Operation completed successfully",
  "data": { ... }
}
```

### Error Response
```json
{
  "success": false,
  "error": "Error description",
  "errors": { ... }  // Validation errors
}
```

### Pagination Response
```json
{
  "data": [...],
  "meta": {
    "total": 100,
    "per_page": 15,
    "current_page": 1,
    "last_page": 7,
    "from": 1,
    "to": 15,
    "has_more_pages": true
  },
  "links": {
    "first": "...",
    "last": "...", 
    "prev": null,
    "next": "..."
  }
}
```

## 🏷️ Data Models

### Category
```json
{
  "id": 1,
  "parent_id": null,
  "name": "Sabzavotlar",
  "slug": "sabzavotlar", 
  "description": "...",
  "icon": "🥕",
  "sort_order": 1,
  "image_urls": {
    "thumbnail": "...",
    "small": "...",
    "medium": "...",
    "large": "...",
    "original": "..."
  },
  "children": [...],
  "path": ["Sabzavotlar"]
}
```

### Product
```json
{
  "id": 1,
  "sku": "TOM-001",
  "name": "Yangi pomidor",
  "slug": "yangi-pomidor",
  "short_description": "...",
  "description": "...",
  "price": 15000.00,
  "sale_price": 12000.00,
  "effective_price": 12000.00,
  "discount_percentage": 20.00,
  "is_on_sale": true,
  "stock_status": "in_stock",
  "is_in_stock": true,
  "weight": 0.5,
  "dimensions": {
    "length": 10.0,
    "width": 8.0,
    "height": 5.0
  },
  "is_featured": true,
  "rating": 4.5,
  "review_count": 25,
  "primary_image": {
    "id": 1,
    "alt_text": "...",
    "urls": {
      "thumbnail": "...",
      "small": "...",
      "medium": "...",
      "large": "...",
      "original": "..."
    }
  },
  "category": { ... }
}
```

## 🚀 Getting Started

1. **Import Postman Collection**: Use `docs/API_COLLECTION.json`
2. **Set Environment Variables**:
   - `base_url`: `http://localhost:8000` (development)
   - `production_url`: `https://api.domproduct.uz` (production)
3. **Authenticate**: Use login endpoint to get auth token
4. **Test Endpoints**: Try public endpoints first, then admin endpoints

## 🔧 Development Setup

1. Clone repository
2. Install dependencies: `composer install`
3. Setup environment: Copy `.env.example` to `.env`
4. Generate key: `php artisan key:generate`
5. Run migrations: `php artisan migrate`
6. Seed database: `php artisan db:seed`
7. Start server: `php artisan serve`

## 🏗️ Architecture

- **Laravel 8.x** - Main framework
- **Sanctum** - API authentication
- **Multi-language support** - Uzbek, English, Russian
- **File upload system** - Multiple image sizes
- **Permission-based access** - Role-based authorization
- **RESTful API design** - Standard HTTP methods and status codes

## 📞 Support

For API support and questions, contact the development team.
