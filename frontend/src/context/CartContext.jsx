import React, { createContext, useReducer, useEffect } from 'react'
import { cartService } from '../services'

// Cart reducer
const cartReducer = (state, action) => {
  switch (action.type) {
    case 'SET_CART':
      return {
        ...state,
        items: action.payload.items || [],
        total: action.payload.total || 0,
        itemCount: action.payload.items?.length || 0,
        isLoading: false
      }
    case 'SET_LOADING':
      return {
        ...state,
        isLoading: action.payload
      }
    case 'ADD_ITEM': {
      const existingItemIndex = state.items.findIndex(
        item => item.product_id === action.payload.product_id
      )

      let newItems
      if (existingItemIndex !== -1) {
        // Mavjud element miqdorini yangilash
        newItems = state.items.map((item, index) =>
          index === existingItemIndex
            ? { ...item, quantity: item.quantity + action.payload.quantity }
            : item
        )
      } else {
        // Yangi element qo'shish
        newItems = [...state.items, action.payload]
      }

      return {
        ...state,
        items: newItems,
        itemCount: newItems.length,
        total: calculateTotal(newItems)
      }
    }
    case 'UPDATE_ITEM': {
      const updatedItems = state.items.map(item =>
        item.id === action.payload.id
          ? { ...item, quantity: action.payload.quantity }
          : item
      )
      return {
        ...state,
        items: updatedItems,
        total: calculateTotal(updatedItems)
      }
    }
    case 'REMOVE_ITEM': {
      const filteredItems = state.items.filter(item => item.id !== action.payload)
      return {
        ...state,
        items: filteredItems,
        itemCount: filteredItems.length,
        total: calculateTotal(filteredItems)
      }
    }
    case 'CLEAR_CART':
      return {
        ...state,
        items: [],
        total: 0,
        itemCount: 0
      }
    default:
      return state
  }
}

// Jami summani hisoblash
const calculateTotal = (items) => {
  return items.reduce((total, item) => {
    const price = item.product?.discounted_price || item.product?.price || 0
    return total + (price * item.quantity)
  }, 0)
}

// Boshlang'ich holat
const initialState = {
  items: [],
  total: 0,
  itemCount: 0,
  isLoading: true
}

export const CartContext = createContext()

export const CartProvider = ({ children }) => {
  const [state, dispatch] = useReducer(cartReducer, initialState)

  // Savatchani yuklash
  const loadCart = async () => {
    try {
      dispatch({ type: 'SET_LOADING', payload: true })
      const response = await cartService.getCart()
      dispatch({ type: 'SET_CART', payload: response.data })
    } catch (error) {
      console.error('Savatcha yuklash xatosi:', error)
      dispatch({ type: 'SET_CART', payload: { items: [], total: 0 } })
    }
  }

  // Savatchaga mahsulot qo'shish
  const addToCart = async (productId, quantity = 1) => {
    const response = await cartService.addToCart(productId, quantity)
    dispatch({ type: 'ADD_ITEM', payload: response.data.item })
    return response.data
  }

  // Savatcha elementini yangilash
  const updateCartItem = async (itemId, quantity) => {
    await cartService.updateCartItem(itemId, quantity)
    dispatch({ type: 'UPDATE_ITEM', payload: { id: itemId, quantity } })
  }

  // Savatcha elementini o'chirish
  const removeFromCart = async (itemId) => {
    await cartService.removeFromCart(itemId)
    dispatch({ type: 'REMOVE_ITEM', payload: itemId })
  }

  // Savatchani tozalash
  const clearCart = async () => {
    await cartService.clearCart()
    dispatch({ type: 'CLEAR_CART' })
  }

  // Mahsulot savatchada borligini tekshirish
  const isInCart = (productId) => {
    return state.items.some(item => item.product_id === productId)
  }

  // Mahsulot miqdorini olish
  const getItemQuantity = (productId) => {
    const item = state.items.find(item => item.product_id === productId)
    return item ? item.quantity : 0
  }

  // Component yuklanganda savatchani yuklash
  useEffect(() => {
    const token = localStorage.getItem('token')
    if (token) {
      loadCart()
    } else {
      dispatch({ type: 'SET_LOADING', payload: false })
    }
  }, [])

  const value = {
    ...state,
    addToCart,
    updateCartItem,
    removeFromCart,
    clearCart,
    loadCart,
    isInCart,
    getItemQuantity
  }

  return (
    <CartContext.Provider value={value}>
      {children}
    </CartContext.Provider>
  )
}
