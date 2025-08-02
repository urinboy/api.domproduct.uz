# 👤 USER REGISTRATION & PROFILE SYSTEM - TO'LIQ REJA

## 📊 DATABASE STRUCTURE TAHLILI

### **USERS TABLE - 33 USTUN**

#### **🔸 Required Fields (Registration uchun minimal):**
1. `name` - varchar(255) - **REQUIRED**
2. `email` - varchar(255) - **REQUIRED** + UNIQUE
3. `password` - varchar(255) - **REQUIRED**
4. `role` - enum - **DEFAULT: customer**

#### **🔸 Basic Info Fields:**
5. `first_name` - varchar(100) - Optional
6. `last_name` - varchar(100) - Optional  
7. `phone` - varchar(20) - Optional + UNIQUE
8. `birth_date` - date - Optional
9. `gender` - enum('male', 'female', 'other') - Optional

#### **🔸 Address Fields:**
10. `address` - varchar(500) - Optional
11. `city` - varchar(100) - Optional
12. `district` - varchar(100) - Optional
13. `postal_code` - varchar(20) - Optional
14. `latitude` - decimal(10,8) - Optional
15. `longitude` - decimal(11,8) - Optional

#### **🔸 Avatar Fields:**
16. `avatar` - varchar(255) - Optional
17. `avatar_original` - text - Optional
18. `avatar_thumbnail` - text - Optional
19. `avatar_small` - text - Optional
20. `avatar_medium` - text - Optional
21. `avatar_large` - text - Optional
22. `avatar_path` - varchar(255) - Optional

#### **🔸 System Fields:**
23. `email_verified_at` - timestamp - System
24. `remember_token` - varchar(100) - System
25. `created_at` - timestamp - Auto
26. `updated_at` - timestamp - Auto
27. `preferred_language_id` - bigint(20) - Foreign Key
28. `email_verified` - tinyint(1) - Default: 0
29. `phone_verified` - tinyint(1) - Default: 0
30. `is_active` - tinyint(1) - Default: 1
31. `last_login_at` - timestamp - System
32. `preferences` - longtext (JSON) - Optional

---

## 🎯 REGISTRATION STRATEGY

### **STEP 1: Minimal Registration** 
```javascript
// Minimal required fields
{
  name: "John Doe",
  email: "john@example.com", 
  password: "password123",
  password_confirmation: "password123"
}
```

### **STEP 2: Extended Registration (Optional)**
```javascript
// Optional additional fields
{
  first_name: "John",
  last_name: "Doe",
  phone: "+998901234567",
  birth_date: "1990-01-01",
  gender: "male",
  preferred_language_id: 1
}
```

### **STEP 3: Address Information (Profile Edit)**
```javascript
// Address fields (post-registration)
{
  address: "Chilonzor 12-kvartal, 25-uy",
  city: "Toshkent", 
  district: "Chilonzor",
  postal_code: "100128"
}
```

---

## 📱 FRONTEND SAHIFA STRUKTURASI

### **1. REGISTRATION PAGE** (`/register`)
```
/register
├── Basic Info Step
│   ├── First Name
│   ├── Last Name  
│   ├── Email
│   ├── Phone
│   └── Password
├── Additional Info Step (Optional)
│   ├── Birth Date
│   ├── Gender
│   ├── Preferred Language
│   └── Avatar Upload
└── Address Step (Optional)
    ├── City
    ├── District
    ├── Address
    └── Postal Code
```

### **2. LOGIN PAGE** (`/login`)
```
/login
├── Email/Phone input
├── Password input
├── Remember Me checkbox
├── Login button
├── "Forgot Password?" link
└── "Register" link
```

### **3. PROFILE PAGE** (`/profile`)
```
/profile
├── Profile Overview
│   ├── Avatar
│   ├── Name & Email
│   ├── Basic Info
│   └── Quick Actions
├── Edit Profile Modal/Page
│   ├── Personal Info
│   ├── Contact Info  
│   ├── Address Info
│   └── Password Change
└── Settings
    ├── Language Preference
    ├── Notifications
    └── Privacy
```

---

## 🎨 DESIGN SYSTEM

### **Design Principles:**
1. **Consistent** - existing loyiha dizayni bilan mos
2. **Responsive** - mobile + desktop
3. **Progressive** - step-by-step form
4. **User-friendly** - clear validation va feedback
5. **Accessible** - keyboard navigation, screen readers

### **Color Scheme:** (Existing loyihadan)
```css
:root {
  --primary-color: #3498db;
  --secondary-color: #2c3e50;
  --success-color: #27ae60;
  --error-color: #e74c3c;
  --warning-color: #f39c12;
  --background-color: #ecf0f1;
  --text-color: #2c3e50;
  --border-color: #bdc3c7;
}
```

---

## 📋 IMPLEMENTATION PLAN

### **PHASE 1: Registration Page** 🔥

#### **1.1 Basic Registration Form**
```jsx
// src/pages/RegisterPage.jsx
const RegisterPage = () => {
  const [step, setStep] = useState(1);
  const [formData, setFormData] = useState({
    // Basic required
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    
    // Extended optional
    first_name: '',
    last_name: '',
    phone: '',
    birth_date: '',
    gender: '',
    
    // Address optional
    address: '',
    city: '',
    district: '',
    postal_code: ''
  });
};
```

#### **1.2 Multi-Step Form Logic**
```jsx
// Step 1: Basic Info (Required)
const BasicInfoStep = () => (
  <div className="registration-step">
    <h3>Asosiy ma'lumotlar</h3>
    <input name="first_name" placeholder="Ism" />
    <input name="last_name" placeholder="Familiya" />
    <input name="email" placeholder="Email" required />
    <input name="phone" placeholder="Telefon" />
    <input name="password" type="password" required />
    <input name="password_confirmation" type="password" required />
  </div>
);

// Step 2: Additional Info (Optional)
const AdditionalInfoStep = () => (
  <div className="registration-step">
    <h3>Qo'shimcha ma'lumotlar (ixtiyoriy)</h3>
    <input name="birth_date" type="date" />
    <select name="gender">
      <option value="">Jinsni tanlang</option>
      <option value="male">Erkak</option>
      <option value="female">Ayol</option>
      <option value="other">Boshqa</option>
    </select>
  </div>
);

// Step 3: Address (Optional)
const AddressStep = () => (
  <div className="registration-step">
    <h3>Manzil (ixtiyoriy)</h3>
    <input name="city" placeholder="Shahar" />
    <input name="district" placeholder="Tuman" />
    <input name="address" placeholder="To'liq manzil" />
    <input name="postal_code" placeholder="Pochta indeksi" />
  </div>
);
```

### **PHASE 2: Login Page** 🔥

#### **2.1 Enhanced Login Form**
```jsx
// src/pages/LoginPage.jsx
const LoginPage = () => {
  const [formData, setFormData] = useState({
    email: '',
    password: '',
    remember_me: false
  });
  
  const [loginMethod, setLoginMethod] = useState('email'); // email or phone
};
```

#### **2.2 Login Features**
```jsx
// Email/Phone toggle
const LoginMethodToggle = () => (
  <div className="login-method-toggle">
    <button 
      className={loginMethod === 'email' ? 'active' : ''}
      onClick={() => setLoginMethod('email')}
    >
      Email bilan
    </button>
    <button 
      className={loginMethod === 'phone' ? 'active' : ''}
      onClick={() => setLoginMethod('phone')}
    >
      Telefon bilan
    </button>
  </div>
);
```

### **PHASE 3: Profile System** 🔥

#### **3.1 Profile Data Fetching**
```jsx
// src/hooks/useProfile.js
export const useProfile = () => {
  const [profile, setProfile] = useState(null);
  const [loading, setLoading] = useState(true);
  
  useEffect(() => {
    const fetchProfile = async () => {
      try {
        const response = await userService.getProfile();
        setProfile(response.data);
      } catch (error) {
        console.error('Profile fetch error:', error);
      } finally {
        setLoading(false);
      }
    };
    
    fetchProfile();
  }, []);
  
  return { profile, loading, refetch: fetchProfile };
};
```

#### **3.2 Profile Display Component**
```jsx
// src/components/ProfileDisplay.jsx
const ProfileDisplay = () => {
  const { profile, loading } = useProfile();
  
  if (loading) return <ProfileSkeleton />;
  
  return (
    <div className="profile-display">
      <div className="profile-header">
        <Avatar user={profile} />
        <div className="profile-info">
          <h2>{profile.first_name} {profile.last_name}</h2>
          <p>{profile.email}</p>
          <span className="profile-role">{profile.role}</span>
        </div>
      </div>
      
      <div className="profile-details">
        <ProfileSection title="Shaxsiy ma'lumotlar">
          <ProfileField label="Telefon" value={profile.phone} />
          <ProfileField label="Tug'ilgan sana" value={profile.birth_date} />
          <ProfileField label="Jinsi" value={profile.gender} />
        </ProfileSection>
        
        <ProfileSection title="Manzil">
          <ProfileField label="Shahar" value={profile.city} />
          <ProfileField label="Tuman" value={profile.district} />
          <ProfileField label="Manzil" value={profile.address} />
        </ProfileSection>
      </div>
    </div>
  );
};
```

---

## 🔧 API INTEGRATION

### **Registration API Enhancement**
```javascript
// src/services/authService.js
export const authService = {
  async register(userData) {
    const response = await apiClient.post('/auth/register', {
      // Required fields
      name: userData.first_name + ' ' + userData.last_name,
      email: userData.email,
      password: userData.password,
      password_confirmation: userData.password_confirmation,
      
      // Optional fields
      first_name: userData.first_name,
      last_name: userData.last_name,
      phone: userData.phone,
      birth_date: userData.birth_date,
      gender: userData.gender,
      address: userData.address,
      city: userData.city,
      district: userData.district,
      postal_code: userData.postal_code,
      preferred_language_id: userData.preferred_language_id || 1
    });
    
    if (response.token) {
      apiClient.setToken(response.token);
    }
    
    return response;
  }
};
```

### **Profile API Service**
```javascript
// src/services/userService.js
export const userService = {
  async getProfile() {
    return apiClient.get('/user/profile');
  },
  
  async updateProfile(userData) {
    return apiClient.put('/user/profile', userData);
  },
  
  async uploadAvatar(file) {
    const formData = new FormData();
    formData.append('avatar', file);
    return apiClient.post('/user/avatar', formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });
  },
  
  async changePassword(passwordData) {
    return apiClient.post('/user/change-password', passwordData);
  }
};
```

---

## 🎯 ROUTING STRUCTURE

### **Route Configuration**
```jsx
// src/App.jsx routes
<Routes>
  <Route path="/" element={<HomePage />} />
  <Route path="/login" element={<LoginPage />} />
  <Route path="/register" element={<RegisterPage />} />
  <Route path="/profile" element={<ProfilePage />} />
  <Route path="/profile/edit" element={<EditProfilePage />} />
  <Route path="/forgot-password" element={<ForgotPasswordPage />} />
  <Route path="/products" element={<ProductsPage />} />
  {/* ... other routes */}
</Routes>
```

### **Protected Routes**
```jsx
// src/components/ProtectedRoute.jsx
const ProtectedRoute = ({ children }) => {
  const { isLoggedIn } = useAuth();
  
  if (!isLoggedIn) {
    return <Navigate to="/login" />;
  }
  
  return children;
};

// Usage
<Route path="/profile" element={
  <ProtectedRoute>
    <ProfilePage />
  </ProtectedRoute>
} />
```

---

## 📱 RESPONSIVE DESIGN

### **Mobile-First Approach**
```css
/* src/styles/auth.css */
.auth-page {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1rem;
}

.auth-container {
  background: white;
  border-radius: 8px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  width: 100%;
  max-width: 400px;
  padding: 2rem;
}

@media (min-width: 768px) {
  .auth-container {
    max-width: 500px;
    padding: 3rem;
  }
}

/* Multi-step registration */
.registration-steps {
  display: flex;
  justify-content: space-between;
  margin-bottom: 2rem;
}

.step {
  flex: 1;
  text-align: center;
  padding: 0.5rem;
  border-bottom: 2px solid #e0e0e0;
  transition: all 0.3s ease;
}

.step.active {
  border-bottom-color: var(--primary-color);
  color: var(--primary-color);
}
```

---

## 🧪 VALIDATION STRATEGY

### **Client-side Validation**
```javascript
// src/utils/validation.js
export const validateRegistration = (formData, step) => {
  const errors = {};
  
  if (step === 1) {
    // Required fields validation
    if (!formData.email) errors.email = 'Email kiritish majburiy';
    if (!formData.password) errors.password = 'Parol kiritish majburiy';
    if (formData.password !== formData.password_confirmation) {
      errors.password_confirmation = 'Parollar mos kelmaydi';
    }
    
    // Email format validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (formData.email && !emailRegex.test(formData.email)) {
      errors.email = 'Email formati noto\'g\'ri';
    }
    
    // Phone format validation (if provided)
    if (formData.phone) {
      const phoneRegex = /^(\+998)?[0-9]{9}$/;
      if (!phoneRegex.test(formData.phone.replace(/\s/g, ''))) {
        errors.phone = 'Telefon formati noto\'g\'ri';
      }
    }
  }
  
  return errors;
};
```

### **Real-time Validation**
```jsx
// Real-time field validation
const [fieldErrors, setFieldErrors] = useState({});

const handleFieldChange = (name, value) => {
  setFormData(prev => ({ ...prev, [name]: value }));
  
  // Clear error when user starts typing
  if (fieldErrors[name]) {
    setFieldErrors(prev => ({ ...prev, [name]: '' }));
  }
  
  // Real-time validation
  if (name === 'email' && value) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(value)) {
      setFieldErrors(prev => ({ ...prev, email: 'Email formati noto\'g\'ri' }));
    }
  }
};
```

---

## 🎯 IMPLEMENTATION PRIORITY

### **🔴 HIGH PRIORITY (Haftada):**
1. **RegisterPage.jsx** - Multi-step registration form
2. **LoginPage.jsx** - Enhanced login with email/phone
3. **ProfilePage.jsx** - Complete profile display
4. **API integration** - Extended registration fields
5. **Validation system** - Client + server validation

### **🟡 MEDIUM PRIORITY (2 hafta):**
1. **Avatar upload** system
2. **Address autocomplete** (Google Maps API)
3. **Email verification** flow
4. **Password reset** functionality
5. **Profile edit** modal/page

### **🟢 LOW PRIORITY (1 oy):**
1. **Social login** (Google, Facebook)
2. **Two-factor authentication**
3. **User preferences** advanced settings
4. **Activity log** system
5. **Data export** functionality

---

## 📊 SUCCESS METRICS

### **Registration Flow:**
- ✅ Multi-step completion rate > 80%
- ✅ Field validation accuracy > 95%
- ✅ Mobile responsiveness perfect
- ✅ API integration seamless

### **Login Flow:**
- ✅ Login success rate > 98%
- ✅ Remember me functionality
- ✅ Email/phone toggle working
- ✅ Error handling comprehensive

### **Profile System:**
- ✅ Profile data complete display
- ✅ Edit functionality working
- ✅ Avatar upload system
- ✅ Real-time updates

**KEYINGI QADAM: RegisterPage.jsx yaratishdan boshlaylik! 🚀**
