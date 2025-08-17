import React, { useState, useEffect } from 'react'
import { Link, useNavigate, useSearchParams } from 'react-router-dom'
import { useQuery } from '@tanstack/react-query'
import { productService, categoryService } from '../services'
import { useCart } from '../hooks/useCart'
import { useAuth } from '../hooks/useAuth'
import toast from 'react-hot-toast'

const ProductsPage = () => {
  const navigate = useNavigate()
  const [searchParams, setSearchParams] = useSearchParams()
  const { addToCart, items: cartItems } = useCart()
  const { user } = useAuth()

  // State variables
  const [showMobileSearch, setShowMobileSearch] = useState(false)
  const [showFilters, setShowFilters] = useState(false)
  const [viewMode, setViewMode] = useState('grid') // 'grid' or 'list'
  const [sortBy, setSortBy] = useState('default')
  const [currentPage, setCurrentPage] = useState(1)
  const [searchQuery, setSearchQuery] = useState('')

  // Filter states
  const [filters, setFilters] = useState({
    minPrice: '',
    maxPrice: '',
    categories: [],
    rating: '',
    inStock: false,
    onSale: false,
    search: searchParams.get('search') || ''
  })

  // Get products with filters
  const { data: productsData, isLoading, error } = useQuery({
    queryKey: ['products', currentPage, sortBy, filters],
    queryFn: () => productService.getAll({
      page: currentPage,
      limit: 20,
      sort: sortBy,
      ...filters
    })
  })

  // Get categories for filter
  const { data: categories } = useQuery({
    queryKey: ['categories'],
    queryFn: () => categoryService.getAll()
  })

  const products = productsData?.data || []
  const totalProducts = productsData?.total || 0
  const cartCount = cartItems?.length || 0

  // Handle mobile search toggle
  const toggleMobileSearch = () => {
    setShowMobileSearch(!showMobileSearch)
  }

  // Handle filters toggle
  const toggleFilters = () => {
    setShowFilters(!showFilters)
  }

  // Handle mobile search
  const handleMobileSearch = () => {
    const searchValue = document.getElementById('mobile-search-input')?.value
    setFilters(prev => ({ ...prev, search: searchValue }))
    setShowMobileSearch(false)
  }

  // Handle price range
  const setPriceRange = (min, max) => {
    setFilters(prev => ({
      ...prev,
      minPrice: min,
      maxPrice: max
    }))
  }

  // Handle sort change
  const handleSort = (e) => {
    setSortBy(e.target.value)
  }

  // Handle view change
  const setView = (mode) => {
    setViewMode(mode)
  }

  // Apply filters
  const applyFilters = () => {
    setCurrentPage(1) // Reset to first page
    setShowFilters(false)
  }

  // Clear filters
  const clearFilters = () => {
    setFilters({
      minPrice: '',
      maxPrice: '',
      categories: [],
      brands: [],
      rating: '',
      inStock: false,
      onSale: false,
      search: ''
    })
    setCurrentPage(1)
    setShowFilters(false)
  }

  // Handle add to cart
  const handleAddToCart = async (product) => {
    try {
      await addToCart(product.id, 1)
      toast.success('Mahsulot savatga qo\'shildi!')
    } catch (error) {
      toast.error('Xatolik yuz berdi. Qayta urinib ko\'ring.')
    }
  }

  // Go back function
  const goBack = () => {
    navigate(-1)
  }

  return (
    <div className="bg-gray-50 min-h-screen">
      {/* Navigation Header */}
      <header className="gradient-bg text-white sticky top-0 z-50 shadow-lg">
        <div className="container mx-auto px-4">
          <div className="flex items-center justify-between h-16">
            {/* Back Button & Title */}
            <div className="flex items-center space-x-3">
              <button
                onClick={goBack}
                className="p-2 hover:bg-white/20 rounded-lg transition-colors"
              >
                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 19l-7-7 7-7"/>
                </svg>
              </button>
              <div>
                <h1 className="text-xl font-bold">Mahsulotlar</h1>
                <p className="text-xs text-green-200">{totalProducts} ta mahsulot</p>
              </div>
            </div>

            {/* Search & Actions */}
            <div className="flex items-center space-x-4">
              {/* Search */}
              <button
                onClick={toggleMobileSearch}
                className="p-2 hover:bg-white/20 rounded-lg transition-colors md:hidden"
              >
                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="m21 21-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
              </button>

              {/* Filter */}
              <button
                onClick={toggleFilters}
                className="p-2 hover:bg-white/20 rounded-lg transition-colors"
              >
                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"/>
                </svg>
              </button>

              {/* Cart */}
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
            </div>
          </div>

          {/* Mobile Search */}
          {showMobileSearch && (
            <div className="md:hidden pb-4">
              <div className="relative">
                <input
                  type="text"
                  id="mobile-search-input"
                  placeholder="Mahsulotlarni qidiring..."
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
          )}
        </div>
      </header>

      {/* Filters Sidebar */}
      <div className={`fixed inset-y-0 right-0 w-full md:w-80 bg-white shadow-2xl transform transition-transform duration-300 z-40 ${
        showFilters ? 'translate-x-0' : 'translate-x-full'
      }`}>
        <div className="flex flex-col h-full">
          {/* Filter Header */}
          <div className="flex items-center justify-between p-4 border-b">
            <h3 className="text-lg font-semibold">Filtrlar</h3>
            <button
              onClick={toggleFilters}
              className="p-2 hover:bg-gray-100 rounded-lg"
            >
              <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>

          {/* Filter Content */}
          <div className="flex-1 overflow-y-auto p-4 space-y-6">
            {/* Price Range */}
            <div>
              <h4 className="font-semibold mb-3">Narx oralig'i</h4>
              <div className="space-y-3">
                <div className="flex space-x-3">
                  <input
                    type="number"
                    placeholder="Dan"
                    value={filters.minPrice}
                    onChange={(e) => setFilters(prev => ({...prev, minPrice: e.target.value}))}
                    className="flex-1 form-input"
                  />
                  <input
                    type="number"
                    placeholder="Gacha"
                    value={filters.maxPrice}
                    onChange={(e) => setFilters(prev => ({...prev, maxPrice: e.target.value}))}
                    className="flex-1 form-input"
                  />
                </div>
                <div className="flex flex-wrap gap-2">
                  <button
                    onClick={() => setPriceRange(0, 10000)}
                    className="px-3 py-1 text-sm border border-gray-300 rounded-lg hover:bg-gray-50"
                  >
                    0 - 10,000
                  </button>
                  <button
                    onClick={() => setPriceRange(10000, 50000)}
                    className="px-3 py-1 text-sm border border-gray-300 rounded-lg hover:bg-gray-50"
                  >
                    10,000 - 50,000
                  </button>
                  <button
                    onClick={() => setPriceRange(50000, 100000)}
                    className="px-3 py-1 text-sm border border-gray-300 rounded-lg hover:bg-gray-50"
                  >
                    50,000 - 100,000
                  </button>
                </div>
              </div>
            </div>

            {/* Categories */}
            <div>
              <h4 className="font-semibold mb-3">Kategoriyalar</h4>
              <div className="space-y-2">
                {categories?.map((category) => (
                  <label key={category.id} className="flex items-center">
                    <input
                      type="checkbox"
                      className="rounded border-gray-300 text-primary"
                      checked={filters.categories.includes(category.id)}
                      onChange={(e) => {
                        if (e.target.checked) {
                          setFilters(prev => ({
                            ...prev,
                            categories: [...prev.categories, category.id]
                          }))
                        } else {
                          setFilters(prev => ({
                            ...prev,
                            categories: prev.categories.filter(id => id !== category.id)
                          }))
                        }
                      }}
                    />
                    <span className="ml-2 text-sm">{category.name}</span>
                  </label>
                ))}
              </div>
            </div>

            {/* Brands */}
            <div>
              <h4 className="font-semibold mb-3">Brendlar</h4>
              <div className="space-y-2">
                <label className="flex items-center">
                  <input
                    type="checkbox"
                    className="rounded border-gray-300 text-primary"
                    checked={filters.brands.includes('dom-product')}
                    onChange={(e) => {
                      if (e.target.checked) {
                        setFilters(prev => ({...prev, brands: [...prev.brands, 'dom-product']}))
                      } else {
                        setFilters(prev => ({...prev, brands: prev.brands.filter(b => b !== 'dom-product')}))
                      }
                    }}
                  />
                  <span className="ml-2 text-sm">DOM PRODUCT</span>
                </label>
                <label className="flex items-center">
                  <input
                    type="checkbox"
                    className="rounded border-gray-300 text-primary"
                    checked={filters.brands.includes('local-brand')}
                    onChange={(e) => {
                      if (e.target.checked) {
                        setFilters(prev => ({...prev, brands: [...prev.brands, 'local-brand']}))
                      } else {
                        setFilters(prev => ({...prev, brands: prev.brands.filter(b => b !== 'local-brand')}))
                      }
                    }}
                  />
                  <span className="ml-2 text-sm">Mahalliy brend</span>
                </label>
                <label className="flex items-center">
                  <input
                    type="checkbox"
                    className="rounded border-gray-300 text-primary"
                    checked={filters.brands.includes('imported')}
                    onChange={(e) => {
                      if (e.target.checked) {
                        setFilters(prev => ({...prev, brands: [...prev.brands, 'imported']}))
                      } else {
                        setFilters(prev => ({...prev, brands: prev.brands.filter(b => b !== 'imported')}))
                      }
                    }}
                  />
                  <span className="ml-2 text-sm">Import</span>
                </label>
              </div>
            </div>

            {/* Availability */}
            <div>
              <h4 className="font-semibold mb-3">Mavjudlik</h4>
              <div className="space-y-2">
                <label className="flex items-center">
                  <input
                    type="checkbox"
                    className="rounded border-gray-300 text-primary"
                    checked={filters.inStock}
                    onChange={(e) => setFilters(prev => ({...prev, inStock: e.target.checked}))}
                  />
                  <span className="ml-2 text-sm">Faqat mavjud mahsulotlar</span>
                </label>
                <label className="flex items-center">
                  <input
                    type="checkbox"
                    className="rounded border-gray-300 text-primary"
                    checked={filters.onSale}
                    onChange={(e) => setFilters(prev => ({...prev, onSale: e.target.checked}))}
                  />
                  <span className="ml-2 text-sm">Chegirmadagi mahsulotlar</span>
                </label>
              </div>
            </div>
          </div>

          {/* Filter Actions */}
          <div className="p-4 border-t space-y-3">
            <button
              onClick={applyFilters}
              className="w-full btn btn-primary"
            >
              Filtrlarni qo'llash
            </button>
            <button
              onClick={clearFilters}
              className="w-full btn btn-secondary"
            >
              Tozalash
            </button>
          </div>
        </div>
      </div>

      {/* Main Content */}
      <main className="container mx-auto px-4 py-6">
        {/* Breadcrumb */}
        <nav className="mb-6">
          <ol className="flex items-center space-x-2 text-sm">
            <li><Link to="/" className="text-gray-500 hover:text-primary">Bosh sahifa</Link></li>
            <li><span className="text-gray-400">/</span></li>
            <li><span className="text-gray-700">Mahsulotlar</span></li>
          </ol>
        </nav>

        {/* Sort & View Options */}
        <div className="flex items-center justify-between mb-6">
          {/* Sort */}
          <div className="flex items-center space-x-4">
            <span className="text-sm text-gray-600">Saralash:</span>
            <select
              value={sortBy}
              onChange={handleSort}
              className="form-input text-sm"
            >
              <option value="default">Standart</option>
              <option value="price-asc">Narx: Arzondan qimmatge</option>
              <option value="price-desc">Narx: Qimmatdan arzonga</option>
              <option value="name-asc">Nomi: A-Z</option>
              <option value="name-desc">Nomi: Z-A</option>
              <option value="rating">Reyting bo'yicha</option>
              <option value="newest">Yangi mahsulotlar</option>
            </select>
          </div>

          {/* View Toggle */}
          <div className="flex items-center space-x-2">
            <button
              onClick={() => setView('grid')}
              className={`p-2 rounded-lg ${viewMode === 'grid' ? 'bg-primary text-white' : 'bg-gray-200 text-gray-600'}`}
            >
              <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M3 3h8v8H3V3zm10 0h8v8h-8V3zM3 13h8v8H3v-8zm10 0h8v8h-8v-8z"/>
              </svg>
            </button>
            <button
              onClick={() => setView('list')}
              className={`p-2 rounded-lg ${viewMode === 'list' ? 'bg-primary text-white' : 'bg-gray-200 text-gray-600'}`}
            >
              <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M3 4h18v2H3V4zm0 7h18v2H3v-2zm0 7h18v2H3v-2z"/>
              </svg>
            </button>
          </div>
        </div>

        {/* Products Container */}
        <div>
          {/* Loading State */}
          {isLoading && (
            <div className="text-center py-12">
              <div className="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
              <p className="mt-2 text-gray-600">Yuklanmoqda...</p>
            </div>
          )}

          {/* Products Grid */}
          {!isLoading && products.length > 0 && viewMode === 'grid' && (
            <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
              {products.map((product) => (
                <ProductCard
                  key={product.id}
                  product={product}
                  onAddToCart={() => handleAddToCart(product)}
                />
              ))}
            </div>
          )}

          {/* Products List */}
          {!isLoading && products.length > 0 && viewMode === 'list' && (
            <div className="space-y-4">
              {products.map((product) => (
                <ProductListItem
                  key={product.id}
                  product={product}
                  onAddToCart={() => handleAddToCart(product)}
                />
              ))}
            </div>
          )}

          {/* Empty State */}
          {!isLoading && products.length === 0 && (
            <div className="text-center py-12">
              <svg className="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
              </svg>
              <h3 className="text-lg font-semibold text-gray-700 mb-2">Mahsulot topilmadi</h3>
              <p className="text-gray-500 mb-4">Qidiruv parametrlarini o'zgartirib ko'ring</p>
              <button
                onClick={clearFilters}
                className="btn btn-primary"
              >
                Filtrlarni tozalash
              </button>
            </div>
          )}
        </div>
      </main>

      {/* Filter Overlay */}
      {showFilters && (
        <div
          className="fixed inset-0 bg-black bg-opacity-50 z-30"
          onClick={toggleFilters}
        ></div>
      )}

      {/* Bottom Navigation (Mobile) */}
      <nav className="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-50">
        <div className="flex items-center justify-around py-2">
          <Link to="/" className="flex flex-col items-center py-2 px-3 text-gray-500">
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
          <Link to="/products" className="flex flex-col items-center py-2 px-3 text-primary">
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
const ProductCard = ({ product, onAddToCart }) => {
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
        onClick={onAddToCart}
        className="w-full btn btn-primary btn-sm"
      >
        Savatga qo'shish
      </button>
    </div>
  )
}

// Product List Item Component
const ProductListItem = ({ product, onAddToCart }) => {
  return (
    <div className="bg-white rounded-2xl p-4 shadow-sm hover:shadow-md transition-shadow">
      <div className="flex space-x-4">
        <Link to={`/products/${product.id}`} className="flex-shrink-0">
          <div className="w-24 h-24 bg-gray-100 rounded-xl overflow-hidden">
            <img
              src={product.image_url || '/images/placeholder.jpg'}
              alt={product.name}
              className="w-full h-full object-cover"
            />
          </div>
        </Link>
        <div className="flex-1 min-w-0">
          <Link to={`/products/${product.id}`}>
            <h3 className="font-semibold mb-1 line-clamp-2">{product.name}</h3>
            <p className="text-gray-600 text-sm mb-2 line-clamp-2">{product.description}</p>
            <div className="flex items-center justify-between">
              {product.sale_price ? (
                <div className="flex items-center space-x-2">
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
              <button
                onClick={onAddToCart}
                className="btn btn-primary btn-sm"
              >
                Savatga qo'shish
              </button>
            </div>
          </Link>
        </div>
      </div>
    </div>
  )
}

export default ProductsPage
