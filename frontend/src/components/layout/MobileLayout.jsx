import React from 'react'
import { Link, useLocation } from 'react-router-dom'
import {
  HomeIcon,
  ShoppingBagIcon,
  ShoppingCartIcon,
  UserIcon,
  MagnifyingGlassIcon
} from '@heroicons/react/24/outline'
import {
  HomeIcon as HomeIconSolid,
  ShoppingBagIcon as ShoppingBagIconSolid,
  ShoppingCartIcon as ShoppingCartIconSolid,
  UserIcon as UserIconSolid
} from '@heroicons/react/24/solid'
import { useAuth } from '../../hooks/useAuth'
import { useCart } from '../../hooks/useCart'

// Header komponenti
const MobileHeader = () => {
  const { itemCount } = useCart()

  return (
    <header className="mobile-header flex items-center justify-between">
      <div className="flex items-center">
        <h1 className="text-xl font-bold text-primary-600">DomProduct</h1>
      </div>

      <div className="flex items-center space-x-3">
        {/* Qidiruv tugmasi */}
        <button className="p-2 rounded-lg hover:bg-gray-100">
          <MagnifyingGlassIcon className="h-5 w-5 text-gray-600" />
        </button>

        {/* Savatcha tugmasi */}
        <Link to="/cart" className="relative p-2 rounded-lg hover:bg-gray-100">
          <ShoppingCartIcon className="h-5 w-5 text-gray-600" />
          {itemCount > 0 && (
            <span className="absolute -top-1 -right-1 bg-primary-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
              {itemCount > 9 ? '9+' : itemCount}
            </span>
          )}
        </Link>
      </div>
    </header>
  )
}

// Bottom navigation komponenti
const BottomNavigation = () => {
  const location = useLocation()
  const { isAuthenticated } = useAuth()

  const navigationItems = [
    {
      name: 'Bosh sahifa',
      path: '/',
      icon: HomeIcon,
      activeIcon: HomeIconSolid
    },
    {
      name: 'Mahsulotlar',
      path: '/products',
      icon: ShoppingBagIcon,
      activeIcon: ShoppingBagIconSolid
    },
    {
      name: 'Savatcha',
      path: '/cart',
      icon: ShoppingCartIcon,
      activeIcon: ShoppingCartIconSolid,
      requireAuth: true
    },
    {
      name: 'Profil',
      path: isAuthenticated ? '/profile' : '/login',
      icon: UserIcon,
      activeIcon: UserIconSolid
    }
  ]

  return (
    <nav className="mobile-bottom-nav">
      <div className="flex justify-around">
        {navigationItems.map((item) => {
          const isActive = location.pathname === item.path
          const Icon = isActive ? item.activeIcon : item.icon

          return (
            <Link
              key={item.name}
              to={item.path}
              className={`
                flex flex-col items-center py-2 px-3 rounded-lg transition-colors
                ${isActive
                  ? 'text-primary-600'
                  : 'text-gray-500 hover:text-gray-700'
                }
              `}
            >
              <Icon className="h-6 w-6" />
              <span className="text-xs mt-1 font-medium">{item.name}</span>
            </Link>
          )
        })}
      </div>
    </nav>
  )
}

// Asosiy layout komponenti
const MobileLayout = ({ children }) => {
  return (
    <div className="mobile-container">
      <MobileHeader />
      <main className="mobile-content">
        {children}
      </main>
      <BottomNavigation />
    </div>
  )
}

export default MobileLayout
