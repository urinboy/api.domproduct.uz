import React, { useState, useEffect } from 'react'
import { Link, useNavigate } from 'react-router-dom'
import { useCart } from '../hooks/useCart'
import { useAuth } from '../hooks/useAuth'
import { orderService, addressService } from '../services'
import toast from 'react-hot-toast'

function CheckoutPage() {
  const navigate = useNavigate()
  const { user } = useAuth()
  const { items: cartItems, getCartTotal, clearCart } = useCart()
  const [currentStep, setCurrentStep] = useState(1)
  const [loading, setLoading] = useState(false)

  // Form states
  const [deliveryAddress, setDeliveryAddress] = useState(null)
  const [paymentMethod, setPaymentMethod] = useState('cash')
  const [orderNotes, setOrderNotes] = useState('')
  const [useUserAddress, setUseUserAddress] = useState(true)
  const [customAddress, setCustomAddress] = useState({
    name: '',
    phone: '',
    address: '',
    city: 'Tashkent',
    district: ''
  })

  const cartCount = cartItems?.length || 0
  const cartTotal = getCartTotal?.() || 0
  const deliveryFee = cartTotal >= 50000 ? 0 : 15000
  const totalAmount = cartTotal + deliveryFee

  useEffect(() => {
    if (!user) {
      navigate('/login')
      return
    }
    if (cartItems?.length === 0) {
      navigate('/cart')
      return
    }
  }, [user, cartItems, navigate])

  const handleStepChange = (step) => {
    if (step <= currentStep + 1) {
      setCurrentStep(step)
    }
  }

  const handlePlaceOrder = async () => {
    if (!deliveryAddress && !customAddress.address) {
      toast.error('Yetkazib berish manzilini tanlang')
      return
    }

    setLoading(true)
    try {
      const orderData = {
        items: cartItems.map(item => ({
          product_id: item.product_id,
          quantity: item.quantity,
          price: item.price
        })),
        delivery_address: useUserAddress ? deliveryAddress : customAddress,
        payment_method: paymentMethod,
        notes: orderNotes,
        total_amount: totalAmount,
        delivery_fee: deliveryFee
      }

      const order = await orderService.create(orderData)
      await clearCart()

      toast.success('Buyurtma muvaffaqiyatli yaratildi!')
      navigate(`/orders/${order.id}`, {
        state: { orderCreated: true }
      })
    } catch (error) {
      toast.error('Buyurtma yaratishda xatolik yuz berdi')
      console.error('Order creation error:', error)
    } finally {
      setLoading(false)
    }
  }

  if (!user || cartItems?.length === 0) {
    return null // Loading or redirecting
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
              <h1 className="text-lg font-semibold">Buyurtmani rasmiylashtirish</h1>
            </div>
          </div>
        </div>
      </header>

      {/* Progress Steps */}
      <div className="bg-white border-b">
        <div className="container mx-auto px-4 py-4">
          <div className="flex items-center justify-center space-x-4">
            {[
              { step: 1, label: 'Manzil', icon: '📍' },
              { step: 2, label: 'Tolov', icon: '💳' },
              { step: 3, label: 'Tasdiqlash', icon: '✅' }
            ].map((item) => (
              <div key={item.step} className="flex items-center">
                <button
                  onClick={() => handleStepChange(item.step)}
                  className={`w-10 h-10 rounded-full flex items-center justify-center text-sm font-medium transition-colors ${
                    currentStep >= item.step
                      ? 'bg-primary text-white'
                      : 'bg-gray-200 text-gray-600'
                  }`}
                >
                  {item.icon}
                </button>
                <span className={`ml-2 text-sm font-medium ${
                  currentStep >= item.step ? 'text-primary' : 'text-gray-500'
                }`}>
                  {item.label}
                </span>
                {item.step < 3 && (
                  <div className={`w-8 h-0.5 mx-4 ${
                    currentStep > item.step ? 'bg-primary' : 'bg-gray-200'
                  }`}></div>
                )}
              </div>
            ))}
          </div>
        </div>
      </div>

      <main className="container mx-auto px-4 py-6 space-y-6">
        {/* Step 1: Delivery Address */}
        {currentStep === 1 && (
          <div className="bg-white rounded-2xl p-6 shadow-sm">
            <h2 className="text-xl font-bold text-gray-900 mb-4">Yetkazib berish manzili</h2>

            <div className="space-y-4">
              {/* Use User Address */}
              <div className="border rounded-lg p-4">
                <label className="flex items-start space-x-3">
                  <input
                    type="radio"
                    name="addressType"
                    checked={useUserAddress}
                    onChange={() => setUseUserAddress(true)}
                    className="mt-1"
                  />
                  <div className="flex-1">
                    <h3 className="font-medium text-gray-900">Mening manzilim</h3>
                    <p className="text-sm text-gray-600 mt-1">
                      {user.name} • {user.phone}
                    </p>
                    <p className="text-sm text-gray-600">
                      {user.address || 'Manzil kiritilmagan'}
                    </p>
                    {!user.address && (
                      <Link to="/profile" className="text-primary text-sm hover:underline">
                        Manzilni tahrirlash
                      </Link>
                    )}
                  </div>
                </label>
              </div>

              {/* Custom Address */}
              <div className="border rounded-lg p-4">
                <label className="flex items-start space-x-3 mb-4">
                  <input
                    type="radio"
                    name="addressType"
                    checked={!useUserAddress}
                    onChange={() => setUseUserAddress(false)}
                    className="mt-1"
                  />
                  <h3 className="font-medium text-gray-900">Boshqa manzil</h3>
                </label>

                {!useUserAddress && (
                  <div className="ml-6 space-y-4">
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                      <input
                        type="text"
                        placeholder="Ism familiya"
                        value={customAddress.name}
                        onChange={(e) => setCustomAddress({...customAddress, name: e.target.value})}
                        className="input"
                      />
                      <input
                        type="tel"
                        placeholder="Telefon raqam"
                        value={customAddress.phone}
                        onChange={(e) => setCustomAddress({...customAddress, phone: e.target.value})}
                        className="input"
                      />
                    </div>
                    <input
                      type="text"
                      placeholder="Manzil (kocha, uy raqami)"
                      value={customAddress.address}
                      onChange={(e) => setCustomAddress({...customAddress, address: e.target.value})}
                      className="input w-full"
                    />
                    <div className="grid grid-cols-2 gap-4">
                      <select
                        value={customAddress.city}
                        onChange={(e) => setCustomAddress({...customAddress, city: e.target.value})}
                        className="input"
                      >
                        <option value="Tashkent">Toshkent</option>
                        <option value="Samarkand">Samarqand</option>
                        <option value="Bukhara">Buxoro</option>
                      </select>
                      <input
                        type="text"
                        placeholder="Tuman"
                        value={customAddress.district}
                        onChange={(e) => setCustomAddress({...customAddress, district: e.target.value})}
                        className="input"
                      />
                    </div>
                  </div>
                )}
              </div>
            </div>

            <button
              onClick={() => setCurrentStep(2)}
              disabled={!useUserAddress && !customAddress.address}
              className="w-full btn btn-primary mt-6"
            >
              Keyingisi
            </button>
          </div>
        )}

        {/* Step 2: Payment Method */}
        {currentStep === 2 && (
          <div className="bg-white rounded-2xl p-6 shadow-sm">
            <h2 className="text-xl font-bold text-gray-900 mb-4">Tolov usuli</h2>

            <div className="space-y-3">
              {[
                { id: 'cash', label: 'Naqd pul', desc: 'Yetkazib berganda tolash', icon: '💵' },
                { id: 'card', label: 'Plastik karta', desc: 'Online tolov orqali', icon: '💳' },
                { id: 'payme', label: 'Payme', desc: 'Payme orqali tolov', icon: '📱' },
                { id: 'click', label: 'Click', desc: 'Click orqali tolov', icon: '📱' }
              ].map((method) => (
                <label key={method.id} className="flex items-center space-x-3 p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                  <input
                    type="radio"
                    name="paymentMethod"
                    value={method.id}
                    checked={paymentMethod === method.id}
                    onChange={(e) => setPaymentMethod(e.target.value)}
                  />
                  <span className="text-2xl">{method.icon}</span>
                  <div className="flex-1">
                    <h3 className="font-medium text-gray-900">{method.label}</h3>
                    <p className="text-sm text-gray-600">{method.desc}</p>
                  </div>
                </label>
              ))}
            </div>

            <div className="mt-6">
              <label className="block text-sm font-medium text-gray-700 mb-2">
                Buyurtma haqida qoshimcha malumot
              </label>
              <textarea
                rows={3}
                value={orderNotes}
                onChange={(e) => setOrderNotes(e.target.value)}
                placeholder="Maxsus talablar, izohlar..."
                className="input w-full"
              />
            </div>

            <div className="flex space-x-3 mt-6">
              <button
                onClick={() => setCurrentStep(1)}
                className="flex-1 btn btn-outline"
              >
                Orqaga
              </button>
              <button
                onClick={() => setCurrentStep(3)}
                className="flex-1 btn btn-primary"
              >
                Keyingisi
              </button>
            </div>
          </div>
        )}

        {/* Step 3: Order Confirmation */}
        {currentStep === 3 && (
          <div className="space-y-4">
            {/* Order Summary */}
            <div className="bg-white rounded-2xl p-6 shadow-sm">
              <h2 className="text-xl font-bold text-gray-900 mb-4">Buyurtma xulosasi</h2>

              <div className="space-y-3">
                {cartItems.map((item) => (
                  <div key={item.id} className="flex items-center space-x-3 py-3 border-b border-gray-100 last:border-0">
                    <img
                      src={item.image_url || '/images/placeholder.jpg'}
                      alt={item.name}
                      className="w-12 h-12 object-cover rounded-lg"
                    />
                    <div className="flex-1">
                      <h3 className="font-medium text-gray-900 text-sm">{item.name}</h3>
                      <p className="text-sm text-gray-600">{item.quantity} x {Number(item.price).toLocaleString()} som</p>
                    </div>
                    <span className="font-semibold text-primary">
                      {Number(item.price * item.quantity).toLocaleString()} som
                    </span>
                  </div>
                ))}
              </div>

              <div className="border-t pt-4 mt-4 space-y-2">
                <div className="flex justify-between text-sm">
                  <span className="text-gray-600">Mahsulotlar:</span>
                  <span>{cartTotal.toLocaleString()} som</span>
                </div>
                <div className="flex justify-between text-sm">
                  <span className="text-gray-600">Yetkazib berish:</span>
                  <span className={deliveryFee === 0 ? 'text-green-600' : ''}>
                    {deliveryFee === 0 ? 'Bepul' : `${deliveryFee.toLocaleString()} som`}
                  </span>
                </div>
                <div className="flex justify-between text-lg font-bold text-primary pt-2 border-t">
                  <span>Jami:</span>
                  <span>{totalAmount.toLocaleString()} som</span>
                </div>
              </div>
            </div>

            {/* Delivery Details */}
            <div className="bg-white rounded-2xl p-6 shadow-sm">
              <h3 className="font-semibold text-gray-900 mb-3">Yetkazib berish manzili</h3>
              <div className="text-sm text-gray-600">
                {useUserAddress ? (
                  <>
                    <p className="font-medium">{user.name}</p>
                    <p>{user.phone}</p>
                    <p>{user.address}</p>
                  </>
                ) : (
                  <>
                    <p className="font-medium">{customAddress.name}</p>
                    <p>{customAddress.phone}</p>
                    <p>{customAddress.address}, {customAddress.district}, {customAddress.city}</p>
                  </>
                )}
              </div>
            </div>

            {/* Payment Method */}
            <div className="bg-white rounded-2xl p-6 shadow-sm">
              <h3 className="font-semibold text-gray-900 mb-2">Tolov usuli</h3>
              <p className="text-sm text-gray-600">
                {paymentMethod === 'cash' && '💵 Naqd pul (yetkazib berganda)'}
                {paymentMethod === 'card' && '💳 Plastik karta'}
                {paymentMethod === 'payme' && '📱 Payme'}
                {paymentMethod === 'click' && '📱 Click'}
              </p>
            </div>

            {/* Action Buttons */}
            <div className="flex space-x-3">
              <button
                onClick={() => setCurrentStep(2)}
                className="flex-1 btn btn-outline"
              >
                Orqaga
              </button>
              <button
                onClick={handlePlaceOrder}
                disabled={loading}
                className="flex-1 btn btn-primary"
              >
                {loading ? 'Yuborilmoqda...' : 'Buyurtmani tasdiqlash'}
              </button>
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
          <Link to="/cart" className="flex flex-col items-center py-2 px-3 text-primary relative">
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

export default CheckoutPage
