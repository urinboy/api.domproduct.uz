import React, { createContext, useState, useEffect } from 'react'
import { authService } from '../services'

export const AuthContext = createContext()

export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(null)
  const [isLoading, setIsLoading] = useState(true)
  const [isAuthenticated, setIsAuthenticated] = useState(false)

  // Sahifa yuklanganda foydalanuvchini tekshirish
  useEffect(() => {
    const initAuth = async () => {
      const token = localStorage.getItem('token')
      const savedUser = localStorage.getItem('user')

      if (token && savedUser) {
        try {
          // Tokenni serverda tekshirish
          const userData = await authService.getCurrentUser()
          setUser(userData.user)
          setIsAuthenticated(true)
                } catch (_e) {
          // Token yaroqsiz
          localStorage.removeItem('token')
          localStorage.removeItem('user')
          setUser(null)
          setIsAuthenticated(false)
        }
      }
      setIsLoading(false)
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
