# 📱 DOM PRODUCT FRONTEND - REACT + VITE MOBILE APP

## 🎯 LOYIHA MAQSADI
React + Vite texnologiyalari bilan mobile-first dizaynga asoslangan zamonaviy e-commerce frontend ilovasini yaratish.

---

## 🏗️ LOYIHA STRUKTURASI

```
frontend/
├── public/
│   ├── favicon.ico
│   ├── manifest.json
│   ├── icons/
│   └── images/
├── src/
│   ├── components/           # Qayta ishlatiladigan komponentlar
│   │   ├── common/          # Umumiy komponentlar
│   │   │   ├── Header/
│   │   │   ├── Footer/
│   │   │   ├── Sidebar/
│   │   │   ├── LoadingSpinner/
│   │   │   ├── ErrorBoundary/
│   │   │   └── Breadcrumb/
│   │   ├── forms/           # Form komponentlari
│   │   │   ├── Input/
│   │   │   ├── Button/
│   │   │   ├── Select/
│   │   │   ├── FileUpload/
│   │   │   └── SearchBox/
│   │   ├── product/         # Mahsulot komponentlari
│   │   │   ├── ProductCard/
│   │   │   ├── ProductList/
│   │   │   ├── ProductDetails/
│   │   │   ├── ProductGallery/
│   │   │   └── ProductFilters/
│   │   ├── cart/            # Savat komponentlari
│   │   │   ├── CartItem/
│   │   │   ├── CartSummary/
│   │   │   ├── CartIcon/
│   │   │   └── MiniCart/
│   │   ├── auth/            # Autentifikatsiya
│   │   │   ├── LoginForm/
│   │   │   ├── RegisterForm/
│   │   │   ├── ProfileForm/
│   │   │   └── PasswordForm/
│   │   ├── navigation/      # Navigatsiya
│   │   │   ├── BottomNavigation/
│   │   │   ├── TabBar/
│   │   │   ├── BackButton/
│   │   │   └── MenuButton/
│   │   └── ui/             # UI primitives
│   │       ├── Modal/
│   │       ├── Toast/
│   │       ├── Card/
│   │       ├── Badge/
│   │       ├── Skeleton/
│   │       └── SwipeableDrawer/
│   ├── pages/              # Sahifalar
│   │   ├── Home/
│   │   ├── Products/
│   │   ├── ProductDetail/
│   │   ├── Categories/
│   │   ├── Cart/
│   │   ├── Checkout/
│   │   ├── Orders/
│   │   ├── Profile/
│   │   ├── Auth/
│   │   ├── Search/
│   │   ├── Favorites/
│   │   └── NotFound/
│   ├── hooks/              # Custom Hooks
│   │   ├── useApi.js
│   │   ├── useAuth.js
│   │   ├── useCart.js
│   │   ├── useProducts.js
│   │   ├── useCategories.js
│   │   ├── useOrders.js
│   │   ├── useLocalStorage.js
│   │   ├── useInfiniteScroll.js
│   │   ├── useGeolocation.js
│   │   └── useSwipeGestures.js
│   ├── context/            # React Context
│   │   ├── AuthContext.js
│   │   ├── CartContext.js
│   │   ├── ThemeContext.js
│   │   ├── LanguageContext.js
│   │   └── NotificationContext.js
│   ├── services/           # API Services
│   │   ├── api.js          # Base API client
│   │   ├── auth.js
│   │   ├── products.js
│   │   ├── categories.js
│   │   ├── cart.js
│   │   ├── orders.js
│   │   ├── payments.js
│   │   ├── notifications.js
│   │   └── upload.js
│   ├── utils/              # Utility functions
│   │   ├── constants.js
│   │   ├── helpers.js
│   │   ├── formatters.js
│   │   ├── validators.js
│   │   ├── storage.js
│   │   └── debounce.js
│   ├── styles/             # Styles
│   │   ├── globals.css
│   │   ├── variables.css
│   │   ├── components.css
│   │   ├── responsive.css
│   │   └── animations.css
│   ├── assets/             # Static assets
│   │   ├── images/
│   │   ├── icons/
│   │   └── fonts/
│   ├── locales/            # Tarjimalar
│   │   ├── uz.json
│   │   ├── en.json
│   │   └── ru.json
│   ├── App.jsx
│   ├── main.jsx
│   └── index.css
├── package.json
├── vite.config.js
├── tailwind.config.js
├── postcss.config.js
├── eslint.config.js
├── .env
├── .env.local
└── README.md
```

---

## 🎨 DIZAYN TIZIMI (MOBILE-FIRST)

### **1. Rang Palitrasai**
```css
:root {
  /* Primary Colors */
  --primary-50: #f0f9ff;
  --primary-500: #3b82f6;
  --primary-600: #2563eb;
  --primary-700: #1d4ed8;
  
  /* Secondary Colors */
  --secondary-500: #10b981;
  --secondary-600: #059669;
  
  /* Neutral Colors */
  --gray-50: #f9fafb;
  --gray-100: #f3f4f6;
  --gray-500: #6b7280;
  --gray-800: #1f2937;
  --gray-900: #111827;
  
  /* Status Colors */
  --success: #10b981;
  --warning: #f59e0b;
  --error: #ef4444;
  --info: #3b82f6;
}
```

### **2. Typography**
```css
/* Font Families */
--font-primary: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
--font-secondary: 'Poppins', sans-serif;

/* Font Sizes (Mobile-first) */
--text-xs: 0.75rem;     /* 12px */
--text-sm: 0.875rem;    /* 14px */
--text-base: 1rem;      /* 16px */
--text-lg: 1.125rem;    /* 18px */
--text-xl: 1.25rem;     /* 20px */
--text-2xl: 1.5rem;     /* 24px */
--text-3xl: 1.875rem;   /* 30px */
```

### **3. Spacing System**
```css
/* Spacing (8px grid system) */
--space-1: 0.25rem;  /* 4px */
--space-2: 0.5rem;   /* 8px */
--space-3: 0.75rem;  /* 12px */
--space-4: 1rem;     /* 16px */
--space-5: 1.25rem;  /* 20px */
--space-6: 1.5rem;   /* 24px */
--space-8: 2rem;     /* 32px */
--space-10: 2.5rem;  /* 40px */
```

### **4. Breakpoints**
```css
/* Mobile-first responsive breakpoints */
--mobile: 320px;
--mobile-lg: 425px;
--tablet: 768px;
--tablet-lg: 1024px;
--desktop: 1200px;
--desktop-lg: 1440px;
```

---

## 📱 MOBILE UI KOMPONENTLARI

### **1. Bottom Navigation**
```jsx
// Mobile asosiy navigatsiya
<BottomNavigation>
  <NavItem icon="home" label="Bosh sahifa" />
  <NavItem icon="grid" label="Kategoriyalar" />
  <NavItem icon="search" label="Qidiruv" />
  <NavItem icon="heart" label="Sevimlilar" />
  <NavItem icon="user" label="Profil" />
</BottomNavigation>
```

### **2. Swipeable Cards**
```jsx
// Swipe gestures bilan product cards
<SwipeableProductCard
  onSwipeLeft={() => addToFavorites()}
  onSwipeRight={() => addToCart()}
  onTap={() => navigateToProduct()}
/>
```

### **3. Pull-to-Refresh**
```jsx
// Mobile uchun pull-to-refresh
<PullToRefresh onRefresh={fetchLatestData}>
  <ProductList />
</PullToRefresh>
```

### **4. Infinite Scroll**
```jsx
// Cheksiz scroll mahsulotlar uchun
<InfiniteScroll
  hasMore={hasMore}
  loadMore={loadMoreProducts}
  loader={<ProductSkeleton />}
>
  <ProductGrid />
</InfiniteScroll>
```

---

## 🔧 TEXNOLOGIYALAR STACK

### **Core Technologies**
- **React 18** - UI library
- **Vite** - Build tool va dev server
- **React Router 6** - Client-side routing
- **TypeScript** - Type safety (ixtiyoriy)

### **State Management**
- **React Context** - Global state
- **React Query / TanStack Query** - Server state management
- **Zustand** - Client state (yengil alternativa Redux'ga)

### **Styling**
- **Tailwind CSS** - Utility-first CSS framework
- **Headless UI** - Unstyled, accessible UI components
- **Framer Motion** - Animations
- **React Spring** - Advanced animations

### **Mobile Features**
- **React Touch Events** - Touch gestures
- **React Intersection Observer** - Lazy loading
- **React PWA** - Progressive Web App features
- **Workbox** - Service worker

### **Development Tools**
- **ESLint** - Code linting
- **Prettier** - Code formatting
- **Husky** - Git hooks
- **Vite PWA Plugin** - PWA generation

---

## 🚀 DEVELOPMENT PHASES

### **PHASE 1: PROJECT SETUP (1-2 kun)**
```bash
✅ Vite + React loyihasini yaratish
✅ Tailwind CSS sozlash
✅ React Router sozlash
✅ ESLint va Prettier sozlash
✅ Environment variables sozlash
✅ API client yaratish
✅ Basic folder structure
```

### **PHASE 2: CORE UI COMPONENTS (3-4 kun)**
```bash
✅ Design System asoslari
✅ Button, Input, Card komponentlari
✅ Loading va Error komponentlari
✅ Modal va Toast komponentlari
✅ Navigation komponentlari
✅ Layout komponentlari
```

### **PHASE 3: AUTHENTICATION (2-3 kun)**
```bash
✅ Login/Register formalar
✅ AuthContext yaratish
✅ Protected routes
✅ Token management
✅ User profile komponentlari
```

### **PHASE 4: PRODUCTS & CATEGORIES (4-5 kun)**
```bash
✅ Product listing
✅ Product details
✅ Category navigation
✅ Search functionality
✅ Filters va sorting
✅ Infinite scroll
```

### **PHASE 5: SHOPPING CART (3-4 kun)**
```bash
✅ Cart context
✅ Add/remove items
✅ Cart summary
✅ Mini cart
✅ Quantity management
```

### **PHASE 6: CHECKOUT & ORDERS (4-5 kun)**
```bash
✅ Checkout flow
✅ Address management
✅ Payment integration
✅ Order history
✅ Order tracking
```

### **PHASE 7: MOBILE OPTIMIZATION (3-4 kun)**
```bash
✅ Touch gestures
✅ Mobile animations
✅ PWA features
✅ Offline support
✅ Performance optimization
```

### **PHASE 8: ADMIN PANEL (5-6 kun)**
```bash
✅ Admin dashboard
✅ Product management
✅ Category management
✅ Order management
✅ User management
✅ Analytics
```

### **PHASE 9: TESTING & DEPLOYMENT (2-3 kun)**
```bash
✅ Unit tests
✅ Integration tests
✅ E2E tests
✅ Build optimization
✅ Production deployment
```

---

## 📱 MOBILE-FIRST FEATURES

### **1. Touch Interactions**
- Swipe gestures mahsulotlar uchun
- Pull-to-refresh
- Touch feedback
- Long press menus
- Pinch-to-zoom product images

### **2. Mobile Navigation**
- Bottom tab navigation
- Hamburger menu
- Breadcrumb navigation
- Back button
- Search overlay

### **3. Performance**
- Lazy loading images
- Virtual scrolling
- Image optimization
- Code splitting
- Service worker caching

### **4. User Experience**
- Skeleton loading states
- Optimistic updates
- Offline indicators
- Network status
- App-like transitions

### **5. PWA Features**
- Install prompt
- Push notifications
- Offline fallback
- App manifest
- Service worker

---

## 🔗 API INTEGRATION

### **Base API Configuration**
```javascript
// src/services/api.js
const API_BASE_URL = 'http://localhost:8000/api';

const apiClient = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  }
});

// Request interceptor - token qo'shish
apiClient.interceptors.request.use((config) => {
  const token = localStorage.getItem('auth_token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});
```

### **Service Layer Example**
```javascript
// src/services/products.js
export const productsService = {
  getAll: (params = {}) => 
    apiClient.get('/v1/products', { params }),
  
  getById: (id) => 
    apiClient.get(`/v1/products/${id}`),
  
  getByCategory: (categoryId, params = {}) => 
    apiClient.get(`/v1/products/category/${categoryId}`, { params }),
  
  getFeatured: () => 
    apiClient.get('/v1/products/featured'),
  
  search: (query, params = {}) => 
    apiClient.get('/v1/products', { params: { search: query, ...params } })
};
```

---

## 🎯 KEY FEATURES

### **User Features**
- ✅ Multi-language support (UZ, EN, RU)
- ✅ Dark/Light theme toggle
- ✅ Product catalog browsing
- ✅ Advanced search & filters
- ✅ Shopping cart management
- ✅ User authentication
- ✅ Order placement & tracking
- ✅ Favorites/Wishlist
- ✅ Address management
- ✅ Payment integration
- ✅ Push notifications
- ✅ Offline support

### **Admin Features**
- ✅ Product management (CRUD)
- ✅ Category management
- ✅ Order management
- ✅ User management
- ✅ Analytics dashboard
- ✅ Inventory tracking
- ✅ Sales reports

### **Mobile-Specific Features**
- ✅ Touch-optimized interface
- ✅ Swipe gestures
- ✅ Bottom navigation
- ✅ Pull-to-refresh
- ✅ Image gallery with zoom
- ✅ Camera integration (profile avatar)
- ✅ Geolocation for delivery
- ✅ Barcode scanner (future)

---

## 🛠️ DEVELOPMENT WORKFLOW

### **Git Workflow**
```
main
├── develop
├── feature/auth
├── feature/products
├── feature/cart
├── feature/checkout
├── feature/admin
└── hotfix/critical-bug
```

### **Development Commands**
```bash
# Development server
npm run dev

# Build for production
npm run build

# Preview production build
npm run preview

# Run tests
npm run test

# Lint code
npm run lint

# Format code
npm run format
```

### **Environment Variables**
```env
# .env.local
VITE_API_BASE_URL=http://localhost:8000/api
VITE_APP_NAME=DOM Product
VITE_ENABLE_PWA=true
VITE_SENTRY_DSN=your_sentry_dsn
VITE_GOOGLE_ANALYTICS_ID=your_ga_id
```

---

## 📊 SUCCESS METRICS

### **Performance Targets**
- ⚡ First Contentful Paint: < 1.5s
- ⚡ Largest Contentful Paint: < 2.5s
- ⚡ Time to Interactive: < 3.5s
- ⚡ Cumulative Layout Shift: < 0.1
- ⚡ Bundle size: < 500KB (gzipped)

### **User Experience Targets**
- 📱 Mobile-first design
- 🎯 Accessibility score: > 95
- 🌐 Multi-browser support
- 📶 Offline functionality
- 🔔 Push notification support

---

## 🎯 NEXT STEPS

1. **Project Setup** - Vite + React loyihasini yaratish
2. **Design System** - Tailwind bilan asosiy komponentlar
3. **API Integration** - Axios client va service layer
4. **Authentication** - Login/Register funksionallik
5. **Product Catalog** - Mahsulotlar ko'rish va filtrlash
6. **Shopping Cart** - Savat funksionallik
7. **Checkout Process** - Buyurtma berish jarayoni
8. **Mobile Optimization** - Touch gestures va PWA
9. **Admin Panel** - Boshqaruv paneli
10. **Testing & Deployment** - Test va production ga chiqarish

**Loyiha taxminan 4-5 hafta ichida yakunlanadi!** 🚀

---

📱 **Mobile-First React + Vite E-commerce Frontend** loyihasini boshlashga tayyormiz!
