import React, { useState, useEffect } from 'react'
import { useForm } from 'react-hook-form'
import { Link, useNavigate, useSearchParams } from 'react-router-dom'
import { authService } from '../services'
import LoadingSpinner from '../components/ui/LoadingSpinner'

const ResetPasswordPage = () => {
  const navigate = useNavigate()
  const [searchParams] = useSearchParams()
  const { register, handleSubmit, formState: { errors }, watch, setError } = useForm()
  const [isLoading, setIsLoading] = useState(false)
  const [message, setMessage] = useState(null)
  const [isSuccess, setIsSuccess] = useState(false)

  const token = searchParams.get('token')
  const email = searchParams.get('email')

  const password = watch('password', '')

  useEffect(() => {
    if (!token || !email) {
      setMessage('Parolni tiklash havolasi noto\'g\'ri yoki muddati o\'tgan.')
      setIsSuccess(false)
    }
  }, [token, email])

  const onSubmit = async (data) => {
    setIsLoading(true)
    setMessage(null)
    setIsSuccess(false)
    try {
      const response = await authService.resetPassword({ ...data, token, email })
      setMessage(response.message || 'Parolingiz muvaffaqiyatli tiklandi.')
      setIsSuccess(true)
      setTimeout(() => {
        navigate('/login')
      }, 3000)
    } catch (error) {
      if (error.response && error.response.data && error.response.data.errors) {
        Object.keys(error.response.data.errors).forEach(key => {
          setError(key, { type: 'server', message: error.response.data.errors[key][0] })
        })
      } else {
        setMessage(error.message || 'Parolni tiklashda xatolik yuz berdi.')
        setIsSuccess(false)
      }
    } finally {
      setIsLoading(false)
    }
  }

  if (!token || !email) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div className="max-w-md w-full space-y-8 p-8 bg-white rounded-lg shadow-md">
          <h2 className="mt-6 text-center text-3xl font-extrabold text-gray-900">
            Xatolik
          </h2>
          <p className="text-center text-error text-sm">{message}</p>
          <div className="text-sm text-center">
            <Link to="/forgot-password" className="font-medium text-primary hover:text-primary-dark">
              Parolni tiklash sahifasiga qaytish
            </Link>
          </div>
        </div>
      </div>
    )
  }

  return (
    <div className="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
      <div className="max-w-md w-full space-y-8 p-8 bg-white rounded-lg shadow-md">
        <div>
          <h2 className="mt-6 text-center text-3xl font-extrabold text-gray-900">
            Parolni tiklash
          </h2>
          <p className="mt-2 text-center text-sm text-gray-600">
            Yangi parolingizni kiriting.
          </p>
        </div>
        <form className="mt-8 space-y-6" onSubmit={handleSubmit(onSubmit)}>
          <div className="rounded-md shadow-sm -space-y-px">
            <div>
              <label htmlFor="password" className="sr-only">Yangi parol</label>
              <input
                id="password"
                name="password"
                type="password"
                autoComplete="new-password"
                required
                className="input rounded-t-md"
                placeholder="Yangi parol"
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

          {message && (
            <div className={`p-3 rounded-md text-sm ${isSuccess ? 'bg-success-100 text-success-700' : 'bg-error-100 text-error-700'}`}>
              {message}
            </div>
          )}

          <div>
            <button
              type="submit"
              className="btn btn-primary w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
              disabled={isLoading}
            >
              {isLoading ? <LoadingSpinner /> : 'Parolni tiklash'}
            </button>
          </div>
        </form>
      </div>
    </div>
  )
}

export default ResetPasswordPage
