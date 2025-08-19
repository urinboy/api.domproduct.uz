import React, { useState, useEffect } from 'react'
import { Link, useNavigate, useSearchParams } from 'react-router-dom'
import { useQuery } from '@tanstack/react-query'
import { categoryService, productService } from '../services'
import { useCart } from '../hooks/useCart'
import { useAuth } from '../hooks/useAuth'
import toast from 'react-hot-toast'

function CategoriesPage() {
  const navigate = useNavigate()
  const { user } = useAuth()
  const { items: cartItems } = useCart()
  const [searchParams] = useSearchParams()
  const [selectedCategory, setSelectedCategory] = useState(null)
  const [searchQuery, setSearchQuery] = useState('')

  const cartCount = cartItems?.length || 0

  // Get all categories
  const { data: categories, isLoading: loadingCategories } = useQuery({
    queryKey: ['categories'],
    queryFn: () => categoryService.getAll()
  })

  // Get category products when category is selected
  const { data: categoryProducts, isLoading: loadingProducts } = useQuery({
    queryKey: ['category-products', selectedCategory],
    queryFn: () => selectedCategory ? productService.getProducts({ category: selectedCategory, limit: 12 }) : null,
    enabled: !!selectedCategory
  })

  useEffect(() => {
    const categoryParam = searchParams.get('category')
    if (categoryParam) {
      setSelectedCategory(categoryParam)
    }
  }, [searchParams])

  const handleCategorySelect = (categoryId) => {
    setSelectedCategory(categoryId)
    navigate(`/categories?category=${categoryId}`)
  }

  const handleSearch = () => {
    if (searchQuery.trim()) {
      navigate(`/products?search=${encodeURIComponent(searchQuery)}`)
    }
  }

  const handleBackToCategories = () => {
    setSelectedCategory(null)
    navigate('/categories')
  }

  return (
    <div className="bg-gray-50 min-h-screen pb-20">
      {/* Header */}
      <header className="gradient-bg text-white sticky top-0 z-50 shadow-lg">
        <div className="container mx-auto px-4">
          <div className="flex items-center justify-between h-16">
            {/* Back Button or Logo */}
            <div className="flex items-center space-x-3">
              {selectedCategory ? (
                <button
                  onClick={handleBackToCategories}
                  className="p-2 hover:bg-white/20 rounded-lg transition-colors"
                >
                  <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 19l-7-7 7-7"></path>
                  </svg>
                </button>
              ) : (
                <Link to="/" className="flex items-center space-x-2">
                  <div className="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
                    <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M7 4V2C7 1.45 7.45 1 8 1H16C16.55 1 17 1.45 17 2V4H20C20.55 4 21 4.45 21 5S20.55 6 20 6H19V19C19 20.1 18.1 21 17 21H7C5.9 21 5 20.1 5 19V6H4C3.45 6 3 5.55 3 5S3.45 4 4 4H7ZM9 3V4H15V3H9ZM7 6V19H17V6H7Z"/>
                    </svg>
                  </div>
                  <div>
                    <h1 className="text-lg font-bold">DOM PRODUCT</h1>
                  </div>
                </Link>
              )}
            </div>

            {/* Search Bar */}
            <div className="flex-1 max-w-md mx-4">
              <div className="relative">
                <input
                  type="text"
                  value={searchQuery}
                  onChange={(e) => setSearchQuery(e.target.value)}
                  onKeyPress={(e) => e.key === 'Enter' && handleSearch()}
                  placeholder="Qidirish..."
                  className="w-full px-4 py-2 rounded-lg bg-white/20 text-white placeholder-green-200 border border-white/30 focus:outline-none"
                />
                <button
                  onClick={handleSearch}
                  className="absolute right-2 top-2 text-green-200 hover:text-white"
                >
                  <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="m21 21-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                  </svg>
                </button>
              </div>
            </div>

            {/* Cart and Profile */}
            <div className="flex items-center space-x-2">
              <Link to="/cart" className="relative p-2 hover:bg-white/20 rounded-lg transition-colors">
                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5 6m0 0h9"></path>
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
          </div>
        </div>
      </header>

      {/* Page Title */}
      <div className="bg-white border-b">
        <div className="container mx-auto px-4 py-4">
          <div className="flex items-center justify-between">
            <div>
              <h1 className="text-2xl font-bold text-gray-900">
                {selectedCategory ? 'Kategoriya mahsulotlari' : 'Barcha kategoriyalar'}
              </h1>
              <p className="text-gray-600 text-sm mt-1">
                {selectedCategory
                  ? `${categories?.find(c => c.id === parseInt(selectedCategory))?.name || 'Kategoriya'} bo'yicha mahsulotlar`
                  : 'O\'zingizga kerakli kategoriyani tanlang'
                }
              </p>
            </div>
            {!selectedCategory && (
              <div className="text-sm text-gray-500">
                {categories?.length || 0} kategoriya
              </div>
            )}
          </div>
        </div>
      </div>

      {/* Main Content */}
      <main className="container mx-auto px-4 py-6">
        {!selectedCategory ? (
          // Categories Grid
          <div>
            {loadingCategories ? (
              <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                {[...Array(8)].map((_, i) => (
                  <CategoryCardSkeleton key={i} />
                ))}
              </div>
            ) : (
              <>
                {/* Featured Categories */}
                <div className="mb-8">
                  <h2 className="text-xl font-bold text-gray-900 mb-4">Ommabop kategoriyalar</h2>
                  <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
                    {categories?.slice(0, 4).map((category) => (
                      <CategoryCard
                        key={category.id}
                        category={category}
                        onSelect={() => handleCategorySelect(category.id)}
                        featured={true}
                      />
                    ))}
                  </div>
                </div>

                {/* All Categories */}
                <div>
                  <h2 className="text-xl font-bold text-gray-900 mb-4">Barcha kategoriyalar</h2>
                  <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    {categories?.map((category) => (
                      <CategoryCard
                        key={category.id}
                        category={category}
                        onSelect={() => handleCategorySelect(category.id)}
                      />
                    ))}
                  </div>
                </div>
              </>
            )}
          </div>
        ) : (
          // Category Products
          <div>
            {/* Breadcrumb */}
            <div className="flex items-center space-x-2 text-sm text-gray-600 mb-6">
              <Link to="/" className="hover:text-primary">Bosh sahifa</Link>
              <span>/</span>
              <button onClick={handleBackToCategories} className="hover:text-primary">Kategoriyalar</button>
              <span>/</span>
              <span className="text-gray-900 font-medium">
                {categories?.find(c => c.id === parseInt(selectedCategory))?.name}
              </span>
            </div>

            {loadingProducts ? (
              <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                {[...Array(8)].map((_, i) => (
                  <ProductCardSkeleton key={i} />
                ))}
              </div>
            ) : categoryProducts?.data?.length > 0 ? (
              <>
                <div className="flex items-center justify-between mb-6">
                  <div className="text-sm text-gray-600">
                    {categoryProducts.data.length} ta mahsulot topildi
                  </div>
                  <Link
                    to={`/products?category=${selectedCategory}`}
                    className="text-primary hover:text-primary-dark font-medium text-sm"
                  >
                    Barchasini ko'rish →
                  </Link>
                </div>
                <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                  {categoryProducts.data.map((product) => (
                    <ProductCard key={product.id} product={product} />
                  ))}
                </div>
              </>
            ) : (
              <div className="text-center py-12">
                <div className="text-6xl mb-4">📦</div>
                <h3 className="text-xl font-semibold text-gray-900 mb-2">Bu kategoriyada mahsulot yo'q</h3>
                <p className="text-gray-600 mb-6">Boshqa kategoriyalarni ko'rib chiqing yoki keyinroq qayta urinib ko'ring</p>
                <button
                  onClick={handleBackToCategories}
                  className="btn btn-primary"
                >
                  Kategoriyalarga qaytish
                </button>
              </div>
            )}
          </div>
        )}
      </main>

      {/* Bottom Navigation */}
      <nav className="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-50">
        <div className="flex items-center justify-around py-2">
          <Link to="/" className="flex flex-col items-center py-2 px-3 text-gray-500">
            <svg className="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
              <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
            </svg>
            <span className="text-xs mt-1">Bosh sahifa</span>
          </Link>
          <Link to="/categories" className="flex flex-col items-center py-2 px-3 text-primary">
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

// Category Card Component
const CategoryCard = ({ category, onSelect, featured = false }) => {
  return (
    <button
      onClick={onSelect}
      className={`bg-white rounded-2xl p-4 shadow-sm hover:shadow-md transition-all text-center w-full ${
        featured ? 'border-2 border-primary/20' : ''
      }`}
    >
      <div className={`${featured ? 'w-16 h-16' : 'w-12 h-12'} bg-gray-100 rounded-xl flex items-center justify-center mx-auto mb-3`}>
        <span className={`${featured ? 'text-3xl' : 'text-2xl'}`}>{category.icon || '📦'}</span>
      </div>
      <h4 className="font-semibold text-gray-900 text-sm mb-1">{category.name}</h4>
      <p className="text-gray-500 text-xs">{category.products_count || 0} mahsulot</p>
      {featured && (
        <div className="mt-2">
          <span className="text-xs bg-primary/10 text-primary px-2 py-1 rounded-full">Ommabop</span>
        </div>
      )}
    </button>
  )
}

// Product Card Component
const ProductCard = ({ product }) => {
  const { addToCart } = useCart()

  const handleAddToCart = async () => {
    try {
      await addToCart(product.id, 1)
      toast.success('Mahsulot savatga qo\'shildi!')
    } catch (error) {
      toast.error('Xatolik yuz berdi')
    }
  }

  return (
    <div className="bg-white rounded-2xl p-4 shadow-sm hover:shadow-md transition-shadow">
      <Link to={`/products/${product.id}`}>
        <div className="aspect-square bg-gray-100 rounded-xl mb-3 overflow-hidden">
          <img
            src={product.image_url || '/images/placeholder.jpg'}
            alt={product.name}
            className="w-full h-full object-cover hover:scale-105 transition-transform"
          />
        </div>
        <h3 className="font-semibold text-sm mb-1 line-clamp-2">{product.name}</h3>
        <div className="flex items-center justify-between mb-2">
          {product.sale_price ? (
            <div className="flex items-center space-x-1">
              <span className="text-primary font-bold text-lg">
                {Number(product.sale_price).toLocaleString()} so'm
              </span>
              <span className="text-gray-400 line-through text-sm">
                {Number(product.price).toLocaleString()}
              </span>
            </div>
          ) : (
            <span className="text-primary font-bold text-lg">
              {Number(product.price).toLocaleString()} so'm
            </span>
          )}
        </div>
      </Link>
      <button
        onClick={handleAddToCart}
        className="w-full btn btn-primary btn-sm"
      >
        Savatga qo'shish
      </button>
    </div>
  )
}

// Loading Skeletons
const CategoryCardSkeleton = () => {
  return (
    <div className="bg-white rounded-2xl p-4 shadow-sm animate-pulse text-center">
      <div className="w-12 h-12 bg-gray-200 rounded-xl mx-auto mb-3"></div>
      <div className="h-4 bg-gray-200 rounded mb-1"></div>
      <div className="h-3 bg-gray-200 rounded w-1/2 mx-auto"></div>
    </div>
  )
}

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

export default CategoriesPage
