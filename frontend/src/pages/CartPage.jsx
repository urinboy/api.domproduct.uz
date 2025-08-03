import React from 'react'
import { useCart } from '../hooks/useCart'
import LoadingSpinner from '../components/ui/LoadingSpinner'
import { Link } from 'react-router-dom'
import toast from 'react-hot-toast'

const CartPage = () => {
  const { items, total, isLoading, updateCartItem, removeFromCart, clearCart } = useCart()

  const handleUpdateQuantity = async (itemId, newQuantity) => {
    if (newQuantity < 1) return
    try {
      await updateCartItem(itemId, newQuantity)
      toast.success('Miqdor yangilandi!')
    } catch (error) {
      toast.error('Miqdorni yangilashda xatolik yuz berdi.')
      console.error(error)
    }
  }

  const handleRemoveItem = async (itemId) => {
    try {
      await removeFromCart(itemId)
      toast.success('Mahsulot savatdan o\'chirildi!')
    } catch (error) {
      toast.error('Mahsulotni o\'chirishda xatolik yuz berdi.')
      console.error(error)
    }
  }

  const handleClearCart = async () => {
    try {
      await clearCart()
      toast.success('Savat tozalandi!')
    } catch (error) {
      toast.error('Savatni tozalashda xatolik yuz berdi.')
      console.error(error)
    }
  }

  if (isLoading) {
    return (
      <div className="flex justify-center items-center min-h-[calc(100vh-150px)]">
        <LoadingSpinner size="lg" />
      </div>
    )
  }

  if (items.length === 0) {
    return (
      <div className="min-h-screen flex flex-col items-center justify-center text-center">
        <h1 className="text-2xl font-bold text-gray-900 mb-4">Savat bo'sh</h1>
        <p className="text-gray-600 mb-6">Sizning savatingizda hozircha mahsulotlar yo'q.</p>
        <Link to="/products" className="btn btn-primary">
          Mahsulotlarni ko'rish
        </Link>
      </div>
    )
  }

  return (
    <div className="container mx-auto px-4 py-8">
      <h1 className="text-3xl font-bold text-gray-900 mb-6">Savat</h1>

      <div className="bg-white rounded-lg shadow-md overflow-hidden">
        {items.map((item) => (
          <div key={item.id} className="flex items-center p-4 border-b border-gray-200 last:border-b-0">
            <img
              src={item.product?.images?.[0]?.url || 'https://placehold.co/80x80'}
              alt={item.product?.translations?.[0]?.name}
              className="w-20 h-20 object-cover rounded-md mr-4"
            />
            <div className="flex-1">
              <h2 className="text-lg font-semibold text-gray-900">{item.product?.translations?.[0]?.name}</h2>
              <p className="text-gray-600 text-sm">${item.product?.sale_price || item.product?.price}</p>
            </div>
            <div className="flex items-center">
              <button
                onClick={() => handleUpdateQuantity(item.id, item.quantity - 1)}
                className="px-3 py-1 border border-gray-300 rounded-l-md text-gray-600 hover:bg-gray-100"
              >
                -
              </button>
              <input
                type="number"
                value={item.quantity}
                onChange={(e) => handleUpdateQuantity(item.id, parseInt(e.target.value) || 1)}
                className="w-16 text-center border-y border-gray-300 focus:outline-none focus:ring-0 focus:border-gray-300"
              />
              <button
                onClick={() => handleUpdateQuantity(item.id, item.quantity + 1)}
                className="px-3 py-1 border border-gray-300 rounded-r-md text-gray-600 hover:bg-gray-100"
              >
                +
              </button>
            </div>
            <button
              onClick={() => handleRemoveItem(item.id)}
              className="ml-4 text-error hover:text-error-dark"
            >
              O'chirish
            </button>
          </div>
        ))}
      </div>

      <div className="mt-6 bg-white rounded-lg shadow-md p-4">
        <div className="flex justify-between items-center mb-4">
          <span className="text-xl font-bold text-gray-900">Jami:</span>
          <span className="text-xl font-bold text-primary">${total.toFixed(2)}</span>
        </div>
        <div className="flex justify-between space-x-4">
          <button onClick={handleClearCart} className="btn btn-secondary flex-1">
            Savatni tozalash
          </button>
          <Link to="/checkout" className="btn btn-primary flex-1 text-center">
            Buyurtma berish
          </Link>
        </div>
      </div>
    </div>
  )
}

export default CartPage
