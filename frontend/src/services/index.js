import apiClient from './api'

// Auth xizmatlari
export const authService = {
  // Foydalanuvchi ro'yxatdan o'tkazish
  register: async (userData) => {
    const response = await apiClient.post('/auth/register', userData)
    if (response.data.token) {
      localStorage.setItem('token', response.data.token)
      localStorage.setItem('user', JSON.stringify(response.data.user))
    }
    return response.data
  },

  // Foydalanuvchi kirishi
  login: async (credentials) => {
    const response = await apiClient.post('/auth/login', credentials)
    if (response.data.token) {
      localStorage.setItem('token', response.data.token)
      localStorage.setItem('user', JSON.stringify(response.data.user))
    }
    return response.data
  },

  // Foydalanuvchi chiqishi
  logout: async () => {
    try {
      await apiClient.post('/auth/logout')
    } finally {
      localStorage.removeItem('token')
      localStorage.removeItem('user')
    }
  },

  // Email tasdiqlash
  verifyEmail: async (token) => {
    const response = await apiClient.post('/auth/email/verify', { token })
    return response.data
  },

  // Parolni tiklash so'rovi
  forgotPassword: async (email) => {
    const response = await apiClient.post('/auth/forgot-password', { email })
    return response.data
  },

  // Parolni tiklash
  resetPassword: async (token, password, confirmPassword) => {
    const response = await apiClient.post('/auth/reset-password', {
      token,
      password,
      password_confirmation: confirmPassword
    })
    return response.data
  },

  // Foydalanuvchi ma'lumotlarini olish
  getCurrentUser: async () => {
    const response = await apiClient.get('/auth/user')
    return response.data
  },

  // Profilni yangilash
  updateProfile: async (data) => {
    const response = await apiClient.put('/user/profile', data)
    return response.data
  },

  // Parolni o'zgartirish
  changePassword: async (currentPassword, newPassword, confirmPassword) => {
    const response = await apiClient.put('/user/password', {
      current_password: currentPassword,
      password: newPassword,
      password_confirmation: confirmPassword
    })
    return response.data
  },

  // Avatar yuklash
  uploadAvatar: async (avatarFile) => {
    const formData = new FormData()
    formData.append('avatar', avatarFile)

    const response = await apiClient.post('/user/avatar', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })
    return response.data
  }
}

// Mahsulot xizmatlari
export const productService = {
  // Barcha mahsulotlarni olish
  getProducts: async (params = {}) => {
    const response = await apiClient.get('/v1/products', { params })
    return response.data
  },

  // Mahsulot bo'yicha qidirish
  searchProducts: async (query, params = {}) => {
    const response = await apiClient.get('/v1/products/search', {
      params: { query, ...params }
    })
    return response.data
  },

  // Bitta mahsulotni olish
  getProduct: async (id) => {
    const response = await apiClient.get(`/v1/products/${id}`)
    return response.data
  },

  // Kategoriya bo'yicha mahsulotlar
  getProductsByCategory: async (categoryId, params = {}) => {
    const response = await apiClient.get(`/v1/categories/${categoryId}/products`, { params })
    return response.data
  },

  // Ommabop mahsulotlar
  getPopularProducts: async () => {
    const response = await apiClient.get('/v1/products/popular')
    return response.data
  },

  // Chegirmadagi mahsulotlar
  getDiscountedProducts: async () => {
    const response = await apiClient.get('/v1/products/discounted')
    return response.data
  },

  // Tavsiya etilgan mahsulotlar
  getRecommendedProducts: async () => {
    const response = await apiClient.get('/v1/products/recommended')
    return response.data
  }
}

// Kategoriya xizmatlari
export const categoryService = {
  // Barcha kategoriyalarni olish
  getCategories: async () => {
    const response = await apiClient.get('/v1/categories')
    return response.data
  },

  // Bitta kategoriyani olish
  getCategory: async (id) => {
    const response = await apiClient.get(`/v1/categories/${id}`)
    return response.data
  }
}

// Savatcha xizmatlari
export const cartService = {
  // Savatchani olish
  getCart: async () => {
    const response = await apiClient.get('/user/cart')
    return response.data
  },

  // Savatchaga mahsulot qo'shish
  addToCart: async (productId, quantity = 1) => {
    const response = await apiClient.post('/user/cart', {
      product_id: productId,
      quantity
    })
    return response.data
  },

  // Savatcha elementini yangilash
  updateCartItem: async (itemId, quantity) => {
    const response = await apiClient.put(`/user/cart/${itemId}`, { quantity })
    return response.data
  },

  // Savatcha elementini o'chirish
  removeFromCart: async (itemId) => {
    const response = await apiClient.delete(`/user/cart/${itemId}`)
    return response.data
  },

  // Savatchani tozalash
  clearCart: async () => {
    const response = await apiClient.delete('/user/cart')
    return response.data
  }
}

// Buyurtma xizmatlari
export const orderService = {
  // Buyurtma berish
  createOrder: async (orderData) => {
    const response = await apiClient.post('/user/orders', orderData)
    return response.data
  },

  // Foydalanuvchi buyurtmalarini olish
  getOrders: async () => {
    const response = await apiClient.get('/user/orders')
    return response.data
  },

  // Bitta buyurtmani olish
  getOrder: async (id) => {
    const response = await apiClient.get(`/user/orders/${id}`)
    return response.data
  },

  // Buyurtmani bekor qilish
  cancelOrder: async (id) => {
    const response = await apiClient.put(`/user/orders/${id}/cancel`)
    return response.data
  }
}

// Manzil xizmatlari
export const addressService = {
  // Foydalanuvchi manzillarini olish
  getAddresses: async () => {
    const response = await apiClient.get('/user/addresses')
    return response.data
  },

  // Yangi manzil qo'shish
  createAddress: async (addressData) => {
    const response = await apiClient.post('/user/addresses', addressData)
    return response.data
  },

  // Manzilni yangilash
  updateAddress: async (id, addressData) => {
    const response = await apiClient.put(`/user/addresses/${id}`, addressData)
    return response.data
  },

  // Manzilni o'chirish
  deleteAddress: async (id) => {
    const response = await apiClient.delete(`/user/addresses/${id}`)
    return response.data
  },

  // Asosiy manzilni belgilash
  setDefaultAddress: async (id) => {
    const response = await apiClient.put(`/user/addresses/${id}/default`)
    return response.data
  }
}

// Til xizmatlari
export const languageService = {
  // Barcha tillarni olish
  getLanguages: async () => {
    const response = await apiClient.get('/v1/languages')
    return response.data
  },

  // Tilni o'zgartirish
  switchLanguage: async (languageCode) => {
    const response = await apiClient.post('/user/language', { language: languageCode })
    return response.data
  },

  // Tarjimalarni olish
  getTranslations: async (languageCode) => {
    const response = await apiClient.get(`/v1/translations/${languageCode}`)
    return response.data
  }
}
