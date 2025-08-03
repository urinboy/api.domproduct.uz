import React from 'react'
import { useQuery } from '@tanstack/react-query'
import { productService } from '../services'
import LoadingSpinner from '../components/ui/LoadingSpinner'
import { Link } from 'react-router-dom'

const ProductsPage = () => {
  const { data: products, isLoading, isError, error } = useQuery({
    queryKey: ['products'],
    queryFn: productService.getProducts,
  })

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
        <p>Mahsulotlarni yuklashda xatolik yuz berdi: {error.message}</p>
      </div>
    )
  }

  return (
    <div className="container mx-auto px-4 py-8">
      <h1 className="text-3xl font-bold text-gray-900 mb-6">Mahsulotlar</h1>
      <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
        {products.data.map((product) => (
          <Link to={`/products/${product.id}`} key={product.id} className="product-card">
            <img
              src={product.images[0]?.url || 'https://placehold.co/150x150'}
              alt={product.translations[0]?.name}
              className="w-full h-32 object-cover rounded-t-lg"
            />
            <div className="p-3">
              <h2 className="text-sm font-semibold text-gray-800 truncate">{product.translations[0]?.name}</h2>
              <p className="text-xs text-gray-500">{product.category?.translations[0]?.name || 'Kategoriya'}</p>
              <div className="flex items-center mt-2">
                {product.sale_price ? (
                  <>
                    <p className="text-sm font-bold text-primary mr-2">${product.sale_price}</p>
                    <p className="text-xs text-gray-500 line-through">${product.price}</p>
                  </>
                ) : (
                  <p className="text-sm font-bold text-gray-900">${product.price}</p>
                )}
              </div>
            </div>
          </Link>
        ))}
      </div>
    </div>
  )
}

export default ProductsPage