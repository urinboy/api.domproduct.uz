import React, { createContext, useState, useEffect } from 'react'
import { authService } from '../services'
import { setAuthToken } from '../services/api'

export const AuthContext = createContext()

export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(null)
  const [isLoading, setIsLoading] = useState(true)
  const [isAuthenticated, setIsAuthenticated] = useState(false)

  // Sahifa yuklanganda foydalanuvchini tekshirish
  useEffect(() => {
    const initAuth = async () => {
      console.log('initAuth started.')
      const token = localStorage.getItem('token')
      const savedUser = localStorage.getItem('user')
      console.log('Token from localStorage:', token)
      console.log('Saved user from localStorage:', savedUser)

      if (token && savedUser) {
        setAuthToken(token)
        console.log('Token and user found in localStorage. Attempting to get current user from API.')
        try {
          // Tokenni serverda tekshirish
          const userData = await authService.getCurrentUser()
          console.log('getCurrentUser response:', userData)
          setUser(userData.data.user)
          setIsAuthenticated(true)
          console.log('User authenticated successfully.')
                } catch (error) {
          console.error('Auth initialization error:', error)
          localStorage.removeItem('token')
          localStorage.removeItem('user')
          setUser(null)
          setIsAuthenticated(false)
          console.log('Auth failed. Token and user removed from localStorage.')
        }
      } else {
        console.log('No token or user found in localStorage. Not authenticating.')
      }
      setIsLoading(false)
      console.log('initAuth finished. isLoading set to false.')
    }

    initAuth()
  }, [])

  // Login funksiyasi
  const login = async (credentials) => {
    const response = await authService.login(credentials)
    setUser(response.user)
    setIsAuthenticated(true)
    return response
  }

  // Register funksiyasi
  const register = async (userData) => {
    const response = await authService.register(userData)
    setUser(response.user)
    setIsAuthenticated(true)
    return response
  }

  // Logout funksiyasi
  const logout = async () => {
    try {
      await authService.logout()
    } catch (error) {
      console.error('Logout xatosi:', error)
    } finally {
      setUser(null)
      setIsAuthenticated(false)
    }
  }

  // Profilni yangilash
  const updateProfile = async (data) => {
    const response = await authService.updateProfile(data)
    setUser(response.user)
    return response
  }

  const value = {
    user,
    isAuthenticated,
    isLoading,
    login,
    register,
    logout,
    updateProfile
  }

  return (
    <AuthContext.Provider value={value}>
      {children}
    </AuthContext.Provider>
  )
}
