import React, { useState } from 'react'
import { useParams } from 'react-router-dom'
import { useQuery } from '@tanstack/react-query'
import { productService } from '../services'
import LoadingSpinner from '../components/ui/LoadingSpinner'
import { useCart } from '../hooks/useCart'
import { ShoppingCartIcon } from '@heroicons/react/24/outline'
import toast from 'react-hot-toast'

const ProductDetailPage = () => {
  const { id } = useParams()
  const { addToCart, getItemQuantity, isInCart } = useCart()
  const [quantity, setQuantity] = useState(1)

  const { data: productData, isLoading, isError, error } = useQuery({
    queryKey: ['product', id],
    queryFn: () => productService.getProduct(id),
  })

  const product = productData?.data

  const handleAddToCart = async () => {
    try {
      await addToCart(product.id, quantity)
      toast.success('Mahsulot savatga qo\'shildi!')
    } catch (err) {
      toast.error('Mahsulotni savatga qo\'shishda xatolik yuz berdi.')
      console.error(err)
    }
  }

  if (isLoading) {
    return (
      <div className="flex justify-center items-center min-h-[calc(100vh-150px)]">
        <LoadingSpinner size="lg" />
      </div>
    )
  }

  if (isError) {
    return (
      <div className="text-center text-error mt-8">
        <p>Mahsulotni yuklashda xatolik yuz berdi: {error.message}</p>
      </div>
    )
  }

  if (!product) {
    return (
      <div className="text-center text-gray-600 mt-8">
        <p>Mahsulot topilmadi.</p>
      </div>
    )
  }

  const mainImage = product.images?.[0]?.url || 'https://placehold.co/400x300'
  const productName = product.translations?.[0]?.name || 'Nomsiz mahsulot'
  const productDescription = product.translations?.[0]?.description || 'Tavsif mavjud emas.'
  const productPrice = product.price
  const productSalePrice = product.sale_price
  const productCategory = product.category?.translations?.[0]?.name || 'Kategoriya'

  return (
    <div className="container mx-auto px-4 py-8">
      <div className="bg-white rounded-lg shadow-md overflow-hidden md:flex">
        <div className="md:w-1/2">
          <img src={mainImage} alt={productName} className="w-full h-64 md:h-auto object-cover" />
        </div>
        <div className="md:w-1/2 p-6">
          <h1 className="text-3xl font-bold text-gray-900 mb-2">{productName}</h1>
          <p className="text-gray-600 text-sm mb-4">Kategoriya: {productCategory}</p>

          <div className="flex items-center mb-4">
            {productSalePrice ? (
              <>
                <p className="text-2xl font-bold text-primary mr-2">${productSalePrice}</p>
                <p className="text-lg text-gray-500 line-through">${productPrice}</p>
              </>
            ) : (
              <p className="text-2xl font-bold text-gray-900">${productPrice}</p>
            )}
          </div>

          <p className="text-gray-700 mb-6">{productDescription}</p>

          <div className="flex items-center space-x-4 mb-6">
            <div className="flex items-center border border-gray-300 rounded-md">
              <button
                onClick={() => setQuantity(prev => Math.max(1, prev - 1))}
                className="px-3 py-1 text-gray-600 hover:bg-gray-100 rounded-l-md"
              >
                -
              </button>
              <input
                type="number"
                value={quantity}
                onChange={(e) => setQuantity(Math.max(1, parseInt(e.target.value) || 1))}
                className="w-16 text-center border-x border-gray-300 focus:outline-none focus:ring-0 focus:border-gray-300"
              />
              <button
                onClick={() => setQuantity(prev => prev + 1)}
                className="px-3 py-1 text-gray-600 hover:bg-gray-100 rounded-r-md"
              >
                +
              </button>
            </div>
            <button
              onClick={handleAddToCart}
              className="btn btn-primary flex items-center space-x-2"
              disabled={isInCart(product.id)}
            >
              <ShoppingCartIcon className="h-5 w-5" />
              <span>{isInCart(product.id) ? 'Savatda' : 'Savatga qo\'shish'}</span>
            </button>
          </div>

          {/* Qo'shimcha ma'lumotlar, masalan, xususiyatlar */}
          {product.translations?.[0]?.specifications && (
            <div className="mt-6">
              <h3 className="text-xl font-bold text-gray-900 mb-2">Xususiyatlar</h3>
              <ul className="list-disc list-inside text-gray-700">
                {Object.entries(product.translations?.[0].specifications).map(([key, value]) => (
                  <li key={key}>
                    <span className="font-semibold capitalize">{key}:</span> {String(value)}
                  </li>
                ))}
              </ul>
            </div>
          )}
        </div>
      </div>

      {/* Boshqa mahsulotlar taklifi (keyinchalik qo'shiladi) */}
      <div className="mt-12">
        <h2 className="text-2xl font-bold text-gray-900 mb-4">Boshqa mahsulotlar</h2>
        <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
          {/* Placeholder for related products */}
          <div className="product-card">
            <img src="https://placehold.co/150x150" alt="Related Product" className="w-full h-32 object-cover rounded-t-lg" />
            <div className="p-3">
              <h3 className="text-sm font-semibold text-gray-800 truncate">Tegishli mahsulot</h3>
              <p className="text-xs text-gray-500">$10.00</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  )
}

export default ProductDetailPage
