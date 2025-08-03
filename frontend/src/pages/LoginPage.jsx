import React, { useState } from 'react'
import { Link, useNavigate } from 'react-router-dom'
import { useForm } from 'react-hook-form'
import { EyeIcon, EyeSlashIcon } from '@heroicons/react/24/outline'
import { useAuth } from '../hooks/useAuth'
import LoadingSpinner from '../components/ui/LoadingSpinner'

const LoginPage = () => {
  const [showPassword, setShowPassword] = useState(false)
  const [isSubmitting, setIsSubmitting] = useState(false)
  const { login } = useAuth()
  const navigate = useNavigate()

  const {
    register,
    handleSubmit,
    formState: { errors },
    setError
  } = useForm()

  const onSubmit = async (data) => {
    setIsSubmitting(true)
    try {
      await login({
        email: data.email,
        password: data.password
      })
      navigate('/', { replace: true })
    } catch (error) {
      if (error.response?.data?.errors) {
        // Laravel validation xatoliklari
        Object.keys(error.response.data.errors).forEach(field => {
          setError(field, {
            type: 'server',
            message: error.response.data.errors[field][0]
          })
        })
      } else {
        setError('root', {
          type: 'server',
          message: error.response?.data?.message || 'Login jarayonida xatolik yuz berdi'
        })
      }
    } finally {
      setIsSubmitting(false)
    }
  }

  return (
    <div className="min-h-screen flex flex-col justify-center py-12 px-4">
      <div className="sm:mx-auto sm:w-full sm:max-w-md">
        {/* Logo */}
        <div className="text-center">
          <h1 className="text-3xl font-bold text-primary-600 mb-2">DomProduct</h1>
          <h2 className="text-2xl font-semibold text-gray-900">Hisobingizga kiring</h2>
          <p className="mt-2 text-gray-600">
            Yoki{' '}
            <Link to="/register" className="text-primary-600 hover:text-primary-700 font-medium">
              yangi hisob yarating
            </Link>
          </p>
        </div>

        {/* Login Form */}
        <div className="mt-8">
          <form onSubmit={handleSubmit(onSubmit)} className="space-y-6">
            {/* Email */}
            <div>
              <label htmlFor="email" className="block text-sm font-medium text-gray-700 mb-2">
                Email manzil
              </label>
              <input
                id="email"
                type="email"
                autoComplete="email"
                className={`input ${errors.email ? 'input-error' : ''}`}
                placeholder="email@example.com"
                {...register('email', {
                  required: 'Email manzil kiritish majburiy',
                  pattern: {
                    value: /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}$/i,
                    message: 'Noto\'g\'ri email format'
                  }
                })}
              />
              {errors.email && (
                <p className="mt-1 text-sm text-error-600">{errors.email.message}</p>
              )}
            </div>

            {/* Password */}
            <div>
              <label htmlFor="password" className="block text-sm font-medium text-gray-700 mb-2">
                Parol
              </label>
              <div className="relative">
                <input
                  id="password"
                  type={showPassword ? 'text' : 'password'}
                  autoComplete="current-password"
                  className={`input pr-10 ${errors.password ? 'input-error' : ''}`}
                  placeholder="Parolingizni kiriting"
                  {...register('password', {
                    required: 'Parol kiritish majburiy',
                    minLength: {
                      value: 6,
                      message: 'Parol kamida 6 ta belgidan iborat bo\'lishi kerak'
                    }
                  })}
                />
                <button
                  type="button"
                  className="absolute inset-y-0 right-0 pr-3 flex items-center"
                  onClick={() => setShowPassword(!showPassword)}
                >
                  {showPassword ? (
                    <EyeSlashIcon className="h-5 w-5 text-gray-400" />
                  ) : (
                    <EyeIcon className="h-5 w-5 text-gray-400" />
                  )}
                </button>
              </div>
              {errors.password && (
                <p className="mt-1 text-sm text-error-600">{errors.password.message}</p>
              )}
            </div>

            {/* Parolni unutdim link */}
            <div className="text-right">
              <Link
                to="/forgot-password"
                className="text-sm text-primary-600 hover:text-primary-700"
              >
                Parolni unutdingizmi?
              </Link>
            </div>

            {/* Server xatoligi */}
            {errors.root && (
              <div className="bg-error-50 border border-error-200 rounded-lg p-3">
                <p className="text-sm text-error-600">{errors.root.message}</p>
              </div>
            )}

            {/* Submit tugmasi */}
            <button
              type="submit"
              disabled={isSubmitting}
              className="btn-primary w-full py-3 text-lg"
            >
              {isSubmitting ? (
                <div className="flex items-center justify-center">
                  <LoadingSpinner size="sm" color="white" />
                  <span className="ml-2">Kirilmoqda...</span>
                </div>
              ) : (
                'Kirish'
              )}
            </button>
          </form>

          {/* Social login (kelajakda) */}
          <div className="mt-6">
            <div className="relative">
              <div className="absolute inset-0 flex items-center">
                <div className="w-full border-t border-gray-300" />
              </div>
              <div className="relative flex justify-center text-sm">
                <span className="px-2 bg-gray-50 text-gray-500">Yoki</span>
              </div>
            </div>

            <div className="mt-6 text-center">
              <p className="text-sm text-gray-600">
                Hali hisobingiz yo'qmi?{' '}
                <Link to="/register" className="text-primary-600 hover:text-primary-700 font-medium">
                  Ro'yxatdan o'ting
                </Link>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  )
}

export default LoginPage
