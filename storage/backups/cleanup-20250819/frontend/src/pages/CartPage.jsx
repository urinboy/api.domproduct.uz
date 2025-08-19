import React, { useState } from 'react'
import { Link, useNavigate } from 'react-router-dom'
import { useCart } from '../hooks/useCart'
import { useAuth } from '../hooks/useAuth'
import toast from 'react-hot-toast'

function CartPage() {
  const navigate = useNavigate()
  const { user } = useAuth()
  const {
    items,
    updateQuantity,
    removeFromCart,
    getTotalPrice,
    clearCart,
    isLoading
  } = useCart()

  const [promoCode, setPromoCode] = useState('')
  const [promoDiscount, setPromoDiscount] = useState(0)
  const [isApplyingPromo, setIsApplyingPromo] = useState(false)

  const deliveryFee = 15000 // 15,000 som
  const subtotal = getTotalPrice()
  const discount = Math.round(subtotal * (promoDiscount / 100))
  const total = subtotal - discount + deliveryFee

  const handleQuantityChange = async (productId, newQuantity) => {
    if (newQuantity < 1) return

    try {
      await updateQuantity(productId, newQuantity)
    } catch (error) {
      toast.error('Xatolik yuz berdi')
    }
  }

  const handleRemoveItem = async (productId) => {
    try {
      await removeFromCart(productId)
      toast.success('Mahsulot o\'chirildi')
    } catch (error) {
      toast.error('Xatolik yuz berdi')
    }
  }

  const handleApplyPromo = async () => {
    setIsApplyingPromo(true)

    // Promo code validation simulation
    setTimeout(() => {
      const validPromoCodes = {
        'CHEGIRMA10': 10,
        'YANGI20': 20,
        'SUMMER15': 15
      }

      if (validPromoCodes[promoCode.toUpperCase()]) {
        setPromoDiscount(validPromoCodes[promoCode.toUpperCase()])
        toast.success(`${validPromoCodes[promoCode.toUpperCase()]}% chegirma qo'llanildi!`)
      } else if (promoCode) {
        toast.error('Promokod noto\'g\'ri')
      }

      setIsApplyingPromo(false)
    }, 1000)
  }

  const handleProceedToCheckout = () => {
    if (!user) {
      navigate('/login', { state: { from: '/cart' } })
      return
    }

    if (items.length === 0) {
      toast.error('Savat bo\'sh')
      return
    }

    navigate('/checkout')
  }

  if (isLoading) {
    return (
      <div className="bg-gray-50 min-h-screen flex items-center justify-center">
        <div className="text-center">
          <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-primary mx-auto mb-4"></div>
          <p className="text-gray-600">Savat yuklanmoqda...</p>
        </div>
      </div>
    )
  }

  return (
    <div className="bg-gray-50 min-h-screen pb-20">
      {/* Header */}
      <header className="gradient-bg text-white sticky top-0 z-50 shadow-lg">
        <div className="container mx-auto px-4">
          <div className="flex items-center justify-between h-16">
            <div className="flex items-center space-x-3">
              <button
                onClick={() => navigate(-1)}
                className="p-2 hover:bg-white/20 rounded-lg transition-colors"
              >
                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 19l-7-7 7-7"></path>
                </svg>
              </button>
              <h1 className="text-lg font-semibold">Savat</h1>
            </div>
            <div className="flex items-center space-x-3">
              <span className="text-sm">{items.length} ta mahsulot</span>
              {items.length > 0 && (
                <button
                  onClick={clearCart}
                  className="text-sm text-red-300 hover:text-red-100 transition-colors"
                >
                  Tozalash
                </button>
              )}
            </div>
          </div>
        </div>
      </header>

      <main className="container mx-auto px-4 py-6">
        {items.length === 0 ? (
          // Empty Cart State
          <div className="bg-white rounded-2xl p-8 shadow-sm text-center">
            <div className="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
              <svg className="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.68 7.32a2 2 0 01-1.97 1.68H3m2-10l1-4H3"></path>
              </svg>
            </div>
            <h2 className="text-2xl font-bold text-gray-900 mb-2">Savat bo'sh</h2>
            <p className="text-gray-600 mb-6">
              Hozircha birorta ham mahsulot tanlanmagan
            </p>
            <Link to="/products" className="btn btn-primary">
              Mahsulotlarni ko'rish
            </Link>
          </div>
        ) : (
          <div className="space-y-6">
            {/* Cart Items */}
            <div className="bg-white rounded-2xl shadow-sm overflow-hidden">
              <div className="p-4 border-b border-gray-100">
                <h2 className="text-lg font-semibold text-gray-900">Tanlangan mahsulotlar</h2>
              </div>

              <div className="divide-y divide-gray-100">
                {items.map((item) => (
                  <div key={item.id} className="p-4 flex items-center space-x-4">
                    <div className="flex-shrink-0">
                      <img
                        src={item.product.image_url || '/images/placeholder.jpg'}
                        alt={item.product.name}
                        className="w-16 h-16 object-cover rounded-lg"
                      />
                    </div>

                    <div className="flex-1 min-w-0">
                      <h3 className="text-sm font-medium text-gray-900 line-clamp-2">
                        {item.product.name}
                      </h3>
                      <p className="text-sm text-gray-600 mt-1">
                        {Number(item.product.price).toLocaleString()} som
                      </p>
                    </div>

                    <div className="flex items-center space-x-3">
                      <div className="flex items-center space-x-2">
                        <button
                          onClick={() => handleQuantityChange(item.product_id, item.quantity - 1)}
                          disabled={item.quantity <= 1}
                          className="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                          <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M20 12H4"></path>
                          </svg>
                        </button>
                        <span className="text-sm font-medium w-8 text-center">
                          {item.quantity}
                        </span>
                        <button
                          onClick={() => handleQuantityChange(item.product_id, item.quantity + 1)}
                          className="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-50"
                        >
                          <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                          </svg>
                        </button>
                      </div>

                      <button
                        onClick={() => handleRemoveItem(item.product_id)}
                        className="w-8 h-8 rounded-full text-red-500 hover:bg-red-50 flex items-center justify-center transition-colors"
                      >
                        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                      </button>
                    </div>

                    <div className="text-right">
                      <div className="text-sm font-medium text-gray-900">
                        {Number(item.product.price * item.quantity).toLocaleString()} som
                      </div>
                    </div>
                  </div>
                ))}
              </div>
            </div>

            {/* Promo Code */}
            <div className="bg-white rounded-2xl p-6 shadow-sm">
              <h3 className="text-lg font-medium text-gray-900 mb-4">Promokod</h3>
              <div className="flex space-x-3">
                <input
                  type="text"
                  value={promoCode}
                  onChange={(e) => setPromoCode(e.target.value)}
                  placeholder="Promokodni kiriting"
                  className="flex-1 input"
                />
                <button
                  onClick={handleApplyPromo}
                  disabled={!promoCode || isApplyingPromo}
                  className="btn btn-outline disabled:opacity-50"
                >
                  {isApplyingPromo ? 'Tekshirilmoqda...' : 'Qo\'llash'}
                </button>
              </div>

              {promoDiscount > 0 && (
                <div className="mt-3 p-3 bg-green-50 rounded-lg flex items-center justify-between">
                  <span className="text-sm text-green-700">
                    Promokod qo'llanildi: {promoDiscount}% chegirma
                  </span>
                  <button
                    onClick={() => {
                      setPromoCode('')
                      setPromoDiscount(0)
                    }}
                    className="text-sm text-green-600 hover:text-green-800"
                  >
                    Bekor qilish
                  </button>
                </div>
              )}
            </div>

            {/* Order Summary */}
            <div className="bg-white rounded-2xl p-6 shadow-sm">
              <h3 className="text-lg font-medium text-gray-900 mb-4">Buyurtma xulosasi</h3>

              <div className="space-y-3">
                <div className="flex justify-between text-sm">
                  <span className="text-gray-600">Mahsulotlar ({items.length} ta):</span>
                  <span className="font-medium">{subtotal.toLocaleString()} som</span>
                </div>

                {promoDiscount > 0 && (
                  <div className="flex justify-between text-sm">
                    <span className="text-gray-600">Chegirma ({promoDiscount}%):</span>
                    <span className="font-medium text-green-600">-{discount.toLocaleString()} som</span>
                  </div>
                )}

                <div className="flex justify-between text-sm">
                  <span className="text-gray-600">Yetkazib berish:</span>
                  <span className="font-medium">{deliveryFee.toLocaleString()} som</span>
                </div>

                <div className="border-t border-gray-200 pt-3">
                  <div className="flex justify-between">
                    <span className="text-base font-semibold text-gray-900">Jami:</span>
                    <span className="text-lg font-bold text-primary">{total.toLocaleString()} som</span>
                  </div>
                </div>
              </div>

              <button
                onClick={handleProceedToCheckout}
                className="w-full mt-6 btn btn-primary"
              >
                Buyurtmani rasmiylashtirish
              </button>
            </div>

            {/* Security Notice */}
            <div className="bg-blue-50 rounded-2xl p-4">
              <div className="flex items-start space-x-3">
                <div className="flex-shrink-0">
                  <svg className="w-5 h-5 text-blue-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fillRule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clipRule="evenodd" />
                  </svg>
                </div>
                <div>
                  <h4 className="text-sm font-medium text-blue-900 mb-1">Xavfsiz to'lov</h4>
                  <p className="text-sm text-blue-700">
                    Barcha to'lovlar 256-bit SSL shifrlanishi bilan himoyalangan
                  </p>
                </div>
              </div>
            </div>
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
          <Link to="/cart" className="flex flex-col items-center py-2 px-3 text-primary">
            <svg className="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
              <path d="M7 18c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12L8.1 13h7.45c.75 0 1.41-.41 1.75-1.03L21.7 4H5.21l-.94-2H1zm16 16c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
            </svg>
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

export default CartPage
