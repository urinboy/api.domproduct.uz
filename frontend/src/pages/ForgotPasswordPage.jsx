import React, { useState } from 'react'
import { useForm } from 'react-hook-form'
import { Link } from 'react-router-dom'
import { authService } from '../services'
import LoadingSpinner from '../components/ui/LoadingSpinner'

const ForgotPasswordPage = () => {
  const { register, handleSubmit, formState: { errors }, setError } = useForm()
  const [isLoading, setIsLoading] = useState(false)
  const [message, setMessage] = useState(null)
  const [isSuccess, setIsSuccess] = useState(false)

  const onSubmit = async (data) => {
    setIsLoading(true)
    setMessage(null)
    setIsSuccess(false)
    try {
      const response = await authService.forgotPassword(data)
      setMessage(response.message || 'Parolni tiklash havolasi emailingizga yuborildi.')
      setIsSuccess(true)
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

  return (
    <div className="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
      <div className="max-w-md w-full space-y-8 p-8 bg-white rounded-lg shadow-md">
        <div>
          <h2 className="mt-6 text-center text-3xl font-extrabold text-gray-900">
            Parolni tiklash
          </h2>
          <p className="mt-2 text-center text-sm text-gray-600">
            Email manzilingizni kiriting va biz sizga parolni tiklash havolasini yuboramiz.
          </p>
        </div>
        <form className="mt-8 space-y-6" onSubmit={handleSubmit(onSubmit)}>
          <div className="rounded-md shadow-sm -space-y-px">
            <div>
              <label htmlFor="email-address" className="sr-only">Email manzil</label>
              <input
                id="email-address"
                name="email"
                type="email"
                autoComplete="email"
                required
                className="input rounded-md"
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
              {isLoading ? <LoadingSpinner /> : 'Havolani yuborish'}
            </button>
          </div>
        </form>
        <div className="text-sm text-center">
          <Link to="/login" className="font-medium text-primary hover:text-primary-dark">
            Kirish sahifasiga qaytish
          </Link>
        </div>
      </div>
    </div>
  )
}

export default ForgotPasswordPage
