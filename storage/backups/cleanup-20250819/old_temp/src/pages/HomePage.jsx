import React, { useState } from 'react'
import { Link, useNavigate } from 'react-router-dom'
import { useQuery } from '@tanstack/react-query'
import { productService, categoryService } from '../services'
import { useCart } from '../hooks/useCart'
import { useAuth } from '../hooks/useAuth'
import toast from 'react-hot-toast'

function HomePage() {
  const navigate = useNavigate()
  const { user } = useAuth()
  const { items: cartItems } = useCart()
  const [searchQuery, setSearchQuery] = useState('')

  const cartCount = cartItems?.length || 0

  // Get featured products
  const { data: featuredProducts, isLoading: loadingFeatured } = useQuery({
    queryKey: ['products', 'featured'],
    queryFn: () => productService.getProducts({ featured: true, limit: 6 })
  })

  // Get popular products
  const { data: popularProducts, isLoading: loadingPopular } = useQuery({
    queryKey: ['products', 'popular'],
    queryFn: () => productService.getPopularProducts()
  })

  // Get categories
  const { data: categories, isLoading: loadingCategories } = useQuery({
    queryKey: ['categories'],
    queryFn: () => categoryService.getAll()
  })

  // Handle search
  const handleSearch = () => {
    if (searchQuery.trim()) {
      navigate(`/products?search=${encodeURIComponent(searchQuery)}`)
    }
  }

  const handleMobileSearch = () => {
    const mobileSearchInput = document.getElementById('mobile-search-input')
    if (mobileSearchInput?.value.trim()) {
      navigate(`/products?search=${encodeURIComponent(mobileSearchInput.value)}`)
    }
  }

  const searchFor = (query) => {
    navigate(`/products?search=${encodeURIComponent(query)}`)
  }

  return (
    <div className="bg-gray-50 min-h-screen">
      {/* Navigation Header */}
      <header className="gradient-bg text-white sticky top-0 z-50 shadow-lg">
        <div className="container mx-auto px-4">
          {/* Top Bar (Desktop) */}
          <div className="hidden md:flex items-center justify-between py-2 text-sm border-b border-white/20">
            <div className="flex items-center space-x-6">
              <span>📞 +998 78 150 15 01</span>
              <span>📧 info@domproduct.uz</span>
              <span>🕒 24/7 xizmat</span>
            </div>
            <div className="flex items-center space-x-4">
              <select className="bg-transparent text-white text-sm">
                <option value="uz">🇺🇿 O'zbek</option>
                <option value="ru">🇷🇺 Русский</option>
                <option value="en">🇺🇸 English</option>
              </select>
              {user ? (
                <Link to="/profile" className="hover:text-green-200">{user.name}</Link>
              ) : (
                <Link to="/login" className="hover:text-green-200">Kirish</Link>
              )}
            </div>
          </div>

          {/* Main Navigation */}
          <div className="flex items-center justify-between h-16">
            {/* Logo */}
            <div className="flex items-center space-x-3">
              <div className="w-10 h-10 bg-primary rounded-lg flex items-center justify-center">
                <svg className="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M7 4V2C7 1.45 7.45 1 8 1H16C16.55 1 17 1.45 17 2V4H20C20.55 4 21 4.45 21 5S20.55 6 20 6H19V19C19 20.1 18.1 21 17 21H7C5.9 21 5 20.1 5 19V6H4C3.45 6 3 5.55 3 5S3.45 4 4 4H7ZM9 3V4H15V3H9ZM7 6V19H17V6H7Z"/>
                </svg>
              </div>
              <div>
                <h1 className="text-xl font-bold">DOM PRODUCT</h1>
                <p className="text-xs text-green-200">Onlayn Market</p>
              </div>
            </div>

            {/* Search Bar (Desktop) */}
            <div className="hidden md:flex flex-1 max-w-2xl mx-8">
              <div className="relative w-full">
                <input
                  type="text"
                  value={searchQuery}
                  onChange={(e) => setSearchQuery(e.target.value)}
                  onKeyPress={(e) => e.key === 'Enter' && handleSearch()}
                  placeholder="Mahsulotlarni qidiring..."
                  className="w-full px-4 py-3 rounded-l-lg bg-white text-gray-900 placeholder-gray-500 focus:outline-none"
                />
                <button
                  onClick={handleSearch}
                  className="px-6 py-3 bg-accent text-white rounded-r-lg hover:bg-accent-dark transition-colors"
                >
                  Qidirish
                </button>
              </div>
            </div>

            {/* Actions (Desktop) */}
            <div className="hidden md:flex items-center space-x-4">
              <Link to="/cart" className="relative p-2 hover:bg-white/20 rounded-lg transition-colors">
                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.68 7.32a2 2 0 01-1.97 1.68H3m2-10l1-4H3"></path>
                </svg>
                {cartCount > 0 && (
                  <span className="absolute -top-1 -right-1 bg-accent text-xs text-white rounded-full w-5 h-5 flex items-center justify-center">
                    {cartCount}
                  </span>
                )}
              </Link>
              <Link to="/profile" className="p-2 hover:bg-white/20 rounded-lg transition-colors">
                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
              </Link>
            </div>

            {/* Mobile Menu Button */}
            <div className="md:hidden">
              <Link to="/cart" className="relative p-2 hover:bg-white/20 rounded-lg transition-colors">
                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.68 7.32a2 2 0 01-1.97 1.68H3m2-10l1-4H3"></path>
                </svg>
                {cartCount > 0 && (
                  <span className="absolute -top-1 -right-1 bg-accent text-xs text-white rounded-full w-5 h-5 flex items-center justify-center">
                    {cartCount}
                  </span>
                )}
              </Link>
            </div>
          </div>

          {/* Mobile Search */}
          <div className="md:hidden pb-4">
            <div className="relative">
              <input
                type="text"
                id="mobile-search-input"
                placeholder="Qidirish..."
                className="w-full px-4 py-2 rounded-lg bg-white/20 text-white placeholder-green-200 border border-white/30 focus:border-primary focus:outline-none"
              />
              <button
                onClick={handleMobileSearch}
                className="absolute right-2 top-2 text-green-200 hover:text-white"
              >
                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="m21 21-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
              </button>
            </div>
          </div>
        </div>
      </header>

      {/* Categories Bar */}
      <nav className="bg-white shadow-sm border-b sticky top-16 z-40">
        <div className="container mx-auto px-4">
          <div className="flex items-center space-x-6 py-3 overflow-x-auto scrollbar-hide">
            <Link
              to="/categories"
              className="flex items-center space-x-2 px-4 py-2 bg-primary text-white rounded-lg whitespace-nowrap hover:bg-primary-dark transition-colors"
            >
              <svg className="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                <path d="M4 6h16M4 12h16M4 18h16"/>
              </svg>
              <span>Kategoriyalar</span>
            </Link>
            <Link to="/products?category=fruits" className="text-gray-700 hover:text-primary whitespace-nowrap transition-colors">🍎 Mevalar</Link>
            <Link to="/products?category=vegetables" className="text-gray-700 hover:text-primary whitespace-nowrap transition-colors">🥬 Sabzavotlar</Link>
            <Link to="/products?category=meat" className="text-gray-700 hover:text-primary whitespace-nowrap transition-colors">🥩 Gosht</Link>
            <Link to="/products?category=dairy" className="text-gray-700 hover:text-primary whitespace-nowrap transition-colors">🥛 Sut mahsulotlari</Link>
            <Link to="/products?category=bakery" className="text-gray-700 hover:text-primary whitespace-nowrap transition-colors">🍞 Non va galla</Link>
            <Link to="/products?category=drinks" className="text-gray-700 hover:text-primary whitespace-nowrap transition-colors">🥤 Ichimliklar</Link>
          </div>
        </div>
      </nav>

      {/* Main Content */}
      <main className="container mx-auto px-4 py-6 space-y-8">
        {/* Hero Banner */}
        <div className="bg-gradient-to-r from-blue-600 to-purple-700 rounded-3xl p-8 text-white overflow-hidden relative">
          <div className="relative z-10">
            <div className="max-w-2xl">
              <h2 className="text-3xl md:text-4xl font-bold mb-4">
                Eng sifatli mahsulotlar biz bilan!
              </h2>
              <p className="text-xl mb-6 text-blue-100">
                1000+ dan ortiq mahsulot, tez yetkazib berish va qulay narxlar
              </p>
              <div className="flex flex-wrap gap-4">
                <Link to="/products" className="btn-white">
                  Mahsulotlarni korish
                </Link>
                <button
                  onClick={() => searchFor('yangi')}
                  className="btn btn-outline border-white text-white hover:bg-white hover:text-purple-700"
                >
                  Yangi kelganlar
                </button>
              </div>
            </div>
          </div>
          <div className="absolute -right-20 -top-20 w-80 h-80 bg-white opacity-10 rounded-full"></div>
          <div className="absolute -right-32 -bottom-32 w-96 h-96 bg-white opacity-5 rounded-full"></div>
        </div>

        {/* Quick Search */}
        <div className="bg-white rounded-2xl p-6 shadow-sm">
          <h3 className="text-xl font-bold text-gray-900 mb-4">Tez qidiruv</h3>
          <div className="grid grid-cols-2 md:grid-cols-4 gap-3">
            <button
              onClick={() => searchFor('meva')}
              className="p-4 bg-green-50 hover:bg-green-100 rounded-xl text-center transition-colors group"
            >
              <span className="text-2xl mb-2 block group-hover:scale-110 transition-transform">🍎</span>
              <span className="text-sm font-medium text-green-700">Mevalar</span>
            </button>
            <button
              onClick={() => searchFor('sabzavot')}
              className="p-4 bg-green-50 hover:bg-green-100 rounded-xl text-center transition-colors group"
            >
              <span className="text-2xl mb-2 block group-hover:scale-110 transition-transform">🥬</span>
              <span className="text-sm font-medium text-green-700">Sabzavotlar</span>
            </button>
            <button
              onClick={() => searchFor('gosht')}
              className="p-4 bg-red-50 hover:bg-red-100 rounded-xl text-center transition-colors group"
            >
              <span className="text-2xl mb-2 block group-hover:scale-110 transition-transform">🥩</span>
              <span className="text-sm font-medium text-red-700">Gosht</span>
            </button>
            <button
              onClick={() => searchFor('sut')}
              className="p-4 bg-blue-50 hover:bg-blue-100 rounded-xl text-center transition-colors group"
            >
              <span className="text-2xl mb-2 block group-hover:scale-110 transition-transform">🥛</span>
              <span className="text-sm font-medium text-blue-700">Sut mahsulotlari</span>
            </button>
          </div>
        </div>

        {/* Featured Products */}
        <section>
          <div className="flex items-center justify-between mb-6">
            <h2 className="text-2xl font-bold text-gray-900">Tavsiya etiladigan mahsulotlar</h2>
            <Link to="/products" className="text-primary hover:text-primary-dark font-medium">
              Barchasini korish →
            </Link>
          </div>

          {loadingFeatured ? (
            <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
              {[...Array(6)].map((_, i) => (
                <ProductCardSkeleton key={i} />
              ))}
            </div>
          ) : (
            <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
              {featuredProducts?.data?.slice(0, 6).map((product) => (
                <ProductCard key={product.id} product={product} />
              ))}
            </div>
          )}
        </section>

        {/* Categories */}
        <section>
          <div className="flex items-center justify-between mb-6">
            <h2 className="text-2xl font-bold text-gray-900">Kategoriyalar</h2>
            <Link to="/categories" className="text-primary hover:text-primary-dark font-medium">
              Barchasini korish →
            </Link>
          </div>

          {loadingCategories ? (
            <div className="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
              {[...Array(6)].map((_, i) => (
                <CategoryCardSkeleton key={i} />
              ))}
            </div>
          ) : (
            <div className="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
              {categories?.data?.slice(0, 6).map((category) => (
                <CategoryCard key={category.id} category={category} />
              ))}
            </div>
          )}
        </section>

        {/* Popular Products */}
        <section>
          <div className="flex items-center justify-between mb-6">
            <h2 className="text-2xl font-bold text-gray-900">Mashhur mahsulotlar</h2>
            <Link to="/products?sort=popular" className="text-primary hover:text-primary-dark font-medium">
              Barchasini korish →
            </Link>
          </div>

          {loadingPopular ? (
            <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
              {[...Array(6)].map((_, i) => (
                <ProductCardSkeleton key={i} />
              ))}
            </div>
          ) : (
            <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
              {popularProducts?.data?.slice(0, 6).map((product) => (
                <ProductCard key={product.id} product={product} />
              ))}
            </div>
          )}
        </section>

        {/* Features */}
        <section className="grid md:grid-cols-3 gap-6">
          <div className="bg-white rounded-2xl p-6 shadow-sm text-center">
            <div className="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg className="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7"></path>
              </svg>
            </div>
            <h3 className="text-lg font-semibold text-gray-900 mb-2">Sifatli mahsulotlar</h3>
            <p className="text-gray-600">Barcha mahsulotlarimiz yuqori sifat standartlariga javob beradi</p>
          </div>

          <div className="bg-white rounded-2xl p-6 shadow-sm text-center">
            <div className="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg className="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
            <h3 className="text-lg font-semibold text-gray-900 mb-2">Tez yetkazib berish</h3>
            <p className="text-gray-600">24 soat ichida buyurtmangizni yetkazib beramiz</p>
          </div>

          <div className="bg-white rounded-2xl p-6 shadow-sm text-center">
            <div className="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg className="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
            <h3 className="text-lg font-semibold text-gray-900 mb-2">Kafolatlangan xizmat</h3>
            <p className="text-gray-600">100% mijoz mamnuniyati kafolatlanadi</p>
          </div>
        </section>
      </main>

      {/* Bottom Navigation */}
      <nav className="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-50">
        <div className="flex items-center justify-around py-2">
          <Link to="/" className="flex flex-col items-center py-2 px-3 text-primary">
            <svg className="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
              <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
            </svg>
            <span className="text-xs mt-1">Bosh sahifa</span>
          </Link>
          <Link to="/categories" className="flex flex-col items-center py-2 px-3 text-gray-500">
            <svg className="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
              <path d="M4 6h16v2H4V6zM4 11h16v2H4v-2zM4 16h16v2H4v-2z"/>
            </svg>
            <span className="text-xs mt-1">Kategoriyalar</span>
          </Link>
          <Link to="/products" className="flex flex-col items-center py-2 px-3 text-gray-500">
            <svg className="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
              <path d="M7 4V2C7 1.45 7.45 1 8 1h8c.55 0 1 .45 1 1v2h5v2H2V4h5zM8 3v1h8V3H8zm-1 3v11c0 1.1.9 2 2 2h6c1.1 0 2-.9 2-2V6H7zm2 1h6v10H9V7z"/>
            </svg>
            <span className="text-xs mt-1">Mahsulotlar</span>
          </Link>
          <Link to="/cart" className="flex flex-col items-center py-2 px-3 text-gray-500 relative">
            <svg className="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
              <path d="M7 18c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12L8.1 13h7.45c.75 0 1.41-.41 1.75-1.03L21.7 4H5.21l-.94-2H1zm16 16c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
            </svg>
            {cartCount > 0 && (
              <span className="absolute -top-1 -right-1 bg-accent text-xs text-white rounded-full w-4 h-4 flex items-center justify-center">
                {cartCount}
              </span>
            )}
            <span className="text-xs mt-1">Savat</span>
          </Link>
          <Link to="/profile" className="flex flex-col items-center py-2 px-3 text-gray-500">
            <svg className="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
            </svg>
            <span className="text-xs mt-1">Profil</span>
          </Link>
        </div>
      </nav>
    </div>
  )
}

// Product Card Component
const ProductCard = ({ product }) => {
  const navigate = useNavigate()
  const { addToCart } = useCart()
  const { user } = useAuth()

  const handleAddToCart = async (e) => {
    e.preventDefault()
    e.stopPropagation()

    if (!user) {
      navigate('/login')
      return
    }

    try {
      await addToCart(product.id, 1)
      toast.success('Mahsulot savatga qoshildi!')
    } catch (error) {
      toast.error('Xatolik yuz berdi')
    }
  }

  return (
    <Link
      to={`/products/${product.id}`}
      className="bg-white rounded-2xl p-4 shadow-sm hover:shadow-md transition-shadow group"
    >
      <div className="aspect-square bg-gray-100 rounded-xl overflow-hidden mb-3">
        <img
          src={product.image_url || '/images/placeholder.jpg'}
          alt={product.name}
          className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200"
        />
      </div>
      <h3 className="font-semibold text-gray-900 text-sm line-clamp-2 mb-2 group-hover:text-primary transition-colors">
        {product.name}
      </h3>
      <div className="flex items-center justify-between">
        <div>
          <p className="text-primary font-bold">
            {Number(product.price).toLocaleString()} som
          </p>
          {product.old_price && (
            <p className="text-gray-500 text-xs line-through">
              {Number(product.old_price).toLocaleString()} som
            </p>
          )}
        </div>
        <button
          onClick={handleAddToCart}
          className="w-8 h-8 bg-primary text-white rounded-lg flex items-center justify-center hover:bg-primary-dark transition-colors"
        >
          <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
          </svg>
        </button>
      </div>
    </Link>
  )
}

// Category Card Component
const CategoryCard = ({ category }) => {
  return (
    <Link
      to={`/products?category=${category.id}`}
      className="bg-white rounded-2xl p-4 shadow-sm hover:shadow-md transition-shadow text-center"
    >
      <div className="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center mx-auto mb-3">
        <span className="text-2xl">{category.icon || '📦'}</span>
      </div>
      <h4 className="font-semibold text-gray-900 text-sm">{category.name}</h4>
      <p className="text-gray-500 text-xs">{category.products_count || 0} mahsulot</p>
    </Link>
  )
}

// Loading Skeletons
const ProductCardSkeleton = () => {
  return (
    <div className="bg-white rounded-2xl p-4 shadow-sm animate-pulse">
      <div className="aspect-square bg-gray-200 rounded-xl mb-3"></div>
      <div className="h-4 bg-gray-200 rounded mb-2"></div>
      <div className="h-3 bg-gray-200 rounded w-2/3 mb-2"></div>
      <div className="h-8 bg-gray-200 rounded"></div>
    </div>
  )
}

const CategoryCardSkeleton = () => {
  return (
    <div className="bg-white rounded-2xl p-4 shadow-sm animate-pulse text-center">
      <div className="w-12 h-12 bg-gray-200 rounded-xl mx-auto mb-3"></div>
      <div className="h-4 bg-gray-200 rounded mb-1"></div>
      <div className="h-3 bg-gray-200 rounded w-1/2 mx-auto"></div>
    </div>
  )
}

export default HomePage
