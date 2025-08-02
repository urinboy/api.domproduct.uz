import React from 'react'
import { Link } from 'react-router-dom'
import {
  FireIcon,
  TagIcon,
  StarIcon,
  ChevronRightIcon
} from '@heroicons/react/24/outline'

const HomePage = () => {
  return (
    <div className="space-y-6">
      {/* Hero Banner */}
      <section className="bg-gradient-to-r from-primary-600 to-primary-700 rounded-xl p-6 text-white">
        <h2 className="text-2xl font-bold mb-2">Xush kelibsiz!</h2>
        <p className="text-primary-100 mb-4">
          Eng zo'r mahsulotlarni topish va xarid qilish uchun eng yaxshi joy
        </p>
        <Link
          to="/products"
          className="inline-flex items-center bg-white text-primary-600 px-4 py-2 rounded-lg font-medium hover:bg-gray-50 transition-colors"
        >
          Xarid qilishni boshlash
          <ChevronRightIcon className="ml-2 h-4 w-4" />
        </Link>
      </section>

      {/* Kategoriyalar */}
      <section>
        <div className="flex items-center justify-between mb-4">
          <h3 className="text-lg font-semibold text-gray-900">Kategoriyalar</h3>
          <Link
            to="/products"
            className="text-primary-600 text-sm font-medium hover:text-primary-700"
          >
            Barchasini ko'rish
          </Link>
        </div>

        <div className="grid grid-cols-2 gap-3">
          {/* Kategoriya kartlari */}
          <CategoryCard
            title="Elektronika"
            icon="📱"
            count="1.2k+ mahsulot"
            color="bg-blue-50 border-blue-200"
          />
          <CategoryCard
            title="Kiyim"
            icon="👕"
            count="800+ mahsulot"
            color="bg-pink-50 border-pink-200"
          />
          <CategoryCard
            title="Uy-ro'zg'or"
            icon="🏠"
            count="650+ mahsulot"
            color="bg-green-50 border-green-200"
          />
          <CategoryCard
            title="Sport"
            icon="⚽"
            count="420+ mahsulot"
            color="bg-orange-50 border-orange-200"
          />
        </div>
      </section>

      {/* Ommabop mahsulotlar */}
      <section>
        <div className="flex items-center justify-between mb-4">
          <div className="flex items-center">
            <FireIcon className="h-5 w-5 text-orange-500 mr-2" />
            <h3 className="text-lg font-semibold text-gray-900">Ommabop</h3>
          </div>
          <Link
            to="/products?sort=popular"
            className="text-primary-600 text-sm font-medium hover:text-primary-700"
          >
            Barchasini ko'rish
          </Link>
        </div>

        {/* Mahsulot kartlari loading skeleton */}
        <div className="space-y-3">
          <ProductCardSkeleton />
          <ProductCardSkeleton />
          <ProductCardSkeleton />
        </div>
      </section>

      {/* Chegirmalar */}
      <section>
        <div className="flex items-center justify-between mb-4">
          <div className="flex items-center">
            <TagIcon className="h-5 w-5 text-red-500 mr-2" />
            <h3 className="text-lg font-semibold text-gray-900">Chegirmalar</h3>
          </div>
          <Link
            to="/products?discount=true"
            className="text-primary-600 text-sm font-medium hover:text-primary-700"
          >
            Barchasini ko'rish
          </Link>
        </div>

        <div className="space-y-3">
          <ProductCardSkeleton />
          <ProductCardSkeleton />
        </div>
      </section>
    </div>
  )
}

// Kategoriya kart komponenti
const CategoryCard = ({ title, icon, count, color }) => {
  return (
    <Link
      to={`/products?category=${title.toLowerCase()}`}
      className={`${color} border rounded-xl p-4 hover:shadow-md transition-shadow`}
    >
      <div className="text-2xl mb-2">{icon}</div>
      <h4 className="font-medium text-gray-900 mb-1">{title}</h4>
      <p className="text-sm text-gray-600">{count}</p>
    </Link>
  )
}

// Mahsulot kart skeleton
const ProductCardSkeleton = () => {
  return (
    <div className="bg-white rounded-xl border border-gray-200 p-4 animate-pulse">
      <div className="flex space-x-3">
        <div className="skeleton h-16 w-16 rounded-lg"></div>
        <div className="flex-1 space-y-2">
          <div className="skeleton h-4 w-3/4"></div>
          <div className="skeleton h-3 w-1/2"></div>
          <div className="flex items-center space-x-2">
            <div className="skeleton h-4 w-16"></div>
            <div className="skeleton h-3 w-12"></div>
          </div>
        </div>
      </div>
    </div>
  )
}

export default HomePage
