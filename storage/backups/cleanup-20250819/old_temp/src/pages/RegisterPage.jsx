import React, { useState } from 'react'
import { useForm } from 'react-hook-form'
import { Link, useNavigate } from 'react-router-dom'
import { useAuth } from '../hooks/useAuth'
import LoadingSpinner from '../components/ui/LoadingSpinner'

const RegisterPage = () => {
  const { register: authRegister } = useAuth()
  const navigate = useNavigate()
  const { register, handleSubmit, formState: { errors }, watch, setError } = useForm()
  const [isLoading, setIsLoading] = useState(false)

  const password = watch('password', '')

  const onSubmit = async (data) => {
    setIsLoading(true)
    try {
      await authRegister(data)
      navigate('/profile') // Ro'yxatdan o'tgandan so'ng profil sahifasiga yo'naltirish
    } catch (error) {
      if (error.response && error.response.data && error.response.data.errors) {
        // Backend validatsiya xatolarini ko'rsatish
        Object.keys(error.response.data.errors).forEach(key => {
          setError(key, { type: 'server', message: error.response.data.errors[key][0] })
        })
      } else {
        // Umumiy xatolarni ko'rsatish
        setError('general', { type: 'server', message: error.message || 'Ro\'yxatdan o\'tishda xatolik yuz berdi.' })
      }
    } finally {
      setIsLoading(false)
    }
  }

  return (
    <div className="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
      <div className="max-w-md w-full space-y-8 p-8 bg-white rounded-lg shadow-md">
        <div>
          <h2 className="mt-6 text-center text-3xl font-extrabold text-gray-900">
            Ro'yxatdan o'tish
          </h2>
        </div>
        <form className="mt-8 space-y-6" onSubmit={handleSubmit(onSubmit)}>
          <div className="rounded-md shadow-sm -space-y-px">
            <div>
              <label htmlFor="name" className="sr-only">Ism</label>
              <input
                id="name"
                name="name"
                type="text"
                autoComplete="name"
                required
                className="input rounded-t-md"
                placeholder="Ism"
                {...register('name', { required: 'Ism kiritish majburiy' })}
              />
              {errors.name && <p className="text-error text-sm mt-1">{errors.name.message}</p>}
            </div>
            <div>
              <label htmlFor="email-address" className="sr-only">Email manzil</label>
              <input
                id="email-address"
                name="email"
                type="email"
                autoComplete="email"
                required
                className="input"
                placeholder="Email manzil"
                {...register('email', {
                  required: 'Email kiritish majburiy',
                  pattern: {
                    value: /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}$/i,
                    message: 'Noto\'g\'ri email manzil'
                  }
                })}
              />
              {errors.email && <p className="text-error text-sm mt-1">{errors.email.message}</p>}
            </div>
            <div>
              <label htmlFor="password" className="sr-only">Parol</label>
              <input
                id="password"
                name="password"
                type="password"
                autoComplete="new-password"
                required
                className="input"
                placeholder="Parol"
                {...register('password', {
                  required: 'Parol kiritish majburiy',
                  minLength: { value: 6, message: 'Parol kamida 6 ta belgidan iborat bo\'lishi kerak' }
                })}
              />
              {errors.password && <p className="text-error text-sm mt-1">{errors.password.message}</p>}
            </div>
            <div>
              <label htmlFor="password-confirm" className="sr-only">Parolni takrorlash</label>
              <input
                id="password-confirm"
                name="password_confirmation"
                type="password"
                autoComplete="new-password"
                required
                className="input rounded-b-md"
                placeholder="Parolni takrorlash"
                {...register('password_confirmation', {
                  required: 'Parolni takrorlash majburiy',
                  validate: value =>
                    value === password || 'Parollar mos kelmadi'
                })}
              />
              {errors.password_confirmation && <p className="text-error text-sm mt-1">{errors.password_confirmation.message}</p>}
            </div>
          </div>

          {errors.general && <p className="text-error text-sm text-center">{errors.general.message}</p>}

          <div>
            <button
              type="submit"
              className="btn btn-primary w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
              disabled={isLoading}
            >
              {isLoading ? <LoadingSpinner /> : 'Ro\'yxatdan o\'tish'}
            </button>
          </div>
        </form>
        <div className="text-sm text-center">
          <Link to="/login" className="font-medium text-primary hover:text-primary-dark">
            Hisobingiz bormi? Kirish
          </Link>
        </div>
      </div>
    </div>
  )
}

export default RegisterPage
