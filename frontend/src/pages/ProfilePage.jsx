import React, { useState, useEffect } from 'react'
import { useForm } from 'react-hook-form'
import { useAuth } from '../hooks/useAuth'
import LoadingSpinner from '../components/ui/LoadingSpinner'
import toast from 'react-hot-toast'

const ProfilePage = () => {
  const { user, isLoading, updateProfile, changePassword, uploadAvatar } = useAuth()
  const { register, handleSubmit, setValue, formState: { errors } } = useForm()
  const { register: registerPassword, handleSubmit: handleSubmitPassword, formState: { errors: errorsPassword }, watch, setError } = useForm()

  const [isProfileUpdating, setIsProfileUpdating] = useState(false)
  const [isPasswordUpdating, setIsPasswordUpdating] = useState(false)
  const [isAvatarUploading, setIsAvatarUploading] = useState(false)

  const newPassword = watch('new_password', '')

  useEffect(() => {
    if (user) {
      setValue('name', user.name || '')
      setValue('email', user.email || '')
      setValue('phone', user.phone || '')
      setValue('date_of_birth', user.date_of_birth || '')
      setValue('gender', user.gender || '')
    }
  }, [user, setValue])

  const onProfileSubmit = async (data) => {
    setIsProfileUpdating(true)
    try {
      await updateProfile(data)
      toast.success('Profil muvaffaqiyatli yangilandi!')
    } catch (error) {
      toast.error('Profilni yangilashda xatolik yuz berdi.')
      console.error(error)
    } finally {
      setIsProfileUpdating(false)
    }
  }

  const onPasswordSubmit = async (data) => {
    setIsPasswordUpdating(true)
    try {
      await changePassword(data.current_password, data.new_password, data.new_password_confirmation)
      toast.success('Parol muvaffaqiyatli o\'zgartirildi!')
    } catch (error) {
      if (error.response?.data?.errors) {
        Object.keys(error.response.data.errors).forEach(key => {
          setError(key, { type: 'server', message: error.response.data.errors[key][0] })
        })
      } else {
        toast.error('Parolni o\'zgartirishda xatolik yuz berdi.')
      }
      console.error(error)
    } finally {
      setIsPasswordUpdating(false)
    }
  }

  const handleAvatarUpload = async (event) => {
    const file = event.target.files[0]
    if (!file) return

    setIsAvatarUploading(true)
    try {
      await uploadAvatar(file)
      toast.success('Avatar muvaffaqiyatli yuklandi!')
    } catch (error) {
      toast.error('Avatarni yuklashda xatolik yuz berdi.')
      console.error(error)
    } finally {
      setIsAvatarUploading(false)
    }
  }

  if (isLoading) {
    return (
      <div className="flex justify-center items-center min-h-[calc(100vh-150px)]">
        <LoadingSpinner size="lg" />
      </div>
    )
  }

  if (!user) {
    return (
      <div className="text-center text-error mt-8">
        <p>Foydalanuvchi ma'lumotlari yuklanmadi yoki siz tizimga kirmagansiz.</p>
      </div>
    )
  }

  return (
    <div className="container mx-auto px-4 py-8">
      <h1 className="text-3xl font-bold text-gray-900 mb-6">Profil</h1>

      {/* Profil ma'lumotlari */}
      <div className="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 className="text-2xl font-bold text-gray-900 mb-4">Shaxsiy ma'lumotlar</h2>
        <div className="flex items-center mb-6">
          <div className="relative w-24 h-24 rounded-full overflow-hidden mr-4">
            <img
              src={user.avatar_url || 'https://placehold.co/96x96'}
              alt="Avatar"
              className="w-full h-full object-cover"
            />
            <label htmlFor="avatar-upload" className="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 text-white cursor-pointer opacity-0 hover:opacity-100 transition-opacity">
              {isAvatarUploading ? <LoadingSpinner size="sm" color="white" /> : 'Yuklash'}
            </label>
            <input
              id="avatar-upload"
              type="file"
              accept="image/*"
              className="hidden"
              onChange={handleAvatarUpload}
              disabled={isAvatarUploading}
            />
          </div>
          <div>
            <p className="text-xl font-semibold text-gray-900">{user.name}</p>
            <p className="text-gray-600">{user.email}</p>
          </div>
        </div>

        <form onSubmit={handleSubmit(onProfileSubmit)} className="space-y-4">
          <div>
            <label htmlFor="name" className="block text-sm font-medium text-gray-700">Ism</label>
            <input
              type="text"
              id="name"
              className="input mt-1"
              {...register('name', { required: 'Ism kiritish majburiy' })}
            />
            {errors.name && <p className="text-error text-sm mt-1">{errors.name.message}</p>}
          </div>
          <div>
            <label htmlFor="phone" className="block text-sm font-medium text-gray-700">Telefon raqami</label>
            <input
              type="text"
              id="phone"
              className="input mt-1"
              {...register('phone')}
            />
          </div>
          <div>
            <label htmlFor="date_of_birth" className="block text-sm font-medium text-gray-700">Tug'ilgan sana</label>
            <input
              type="date"
              id="date_of_birth"
              className="input mt-1"
              {...register('date_of_birth')}
            />
          </div>
          <div>
            <label htmlFor="gender" className="block text-sm font-medium text-gray-700">Jinsi</label>
            <select
              id="gender"
              className="input mt-1"
              {...register('gender')}
            >
              <option value="">Tanlang</option>
              <option value="male">Erkak</option>
              <option value="female">Ayol</option>
            </select>
          </div>
          <button
            type="submit"
            className="btn btn-primary"
            disabled={isProfileUpdating}
          >
            {isProfileUpdating ? <LoadingSpinner size="sm" color="white" /> : 'Saqlash'}
          </button>
        </form>
      </div>

      {/* Parolni o'zgartirish */}
      <div className="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 className="text-2xl font-bold text-gray-900 mb-4">Parolni o'zgartirish</h2>
        <form onSubmit={handleSubmitPassword(onPasswordSubmit)} className="space-y-4">
          <div>
            <label htmlFor="current_password" className="block text-sm font-medium text-gray-700">Joriy parol</label>
            <input
              type="password"
              id="current_password"
              className="input mt-1"
              {...registerPassword('current_password', { required: 'Joriy parol kiritish majburiy' })}
            />
            {errorsPassword.current_password && <p className="text-error text-sm mt-1">{errorsPassword.current_password.message}</p>}
          </div>
          <div>
            <label htmlFor="new_password" className="block text-sm font-medium text-gray-700">Yangi parol</label>
            <input
              type="password"
              id="new_password"
              className="input mt-1"
              {...registerPassword('new_password', {
                required: 'Yangi parol kiritish majburiy',
                minLength: { value: 6, message: 'Parol kamida 6 ta belgidan iborat bo\'lishi kerak' }
              })}
            />
            {errorsPassword.new_password && <p className="text-error text-sm mt-1">{errorsPassword.new_password.message}</p>}
          </div>
          <div>
            <label htmlFor="new_password_confirmation" className="block text-sm font-medium text-gray-700">Parolni tasdiqlash</label>
            <input
              type="password"
              id="new_password_confirmation"
              className="input mt-1"
              {...registerPassword('new_password_confirmation', {
                required: 'Parolni tasdiqlash majburiy',
                validate: value =>
                  value === newPassword || 'Parollar mos kelmadi'
              })}
            />
            {errorsPassword.new_password_confirmation && <p className="text-error text-sm mt-1">{errorsPassword.new_password_confirmation.message}</p>}
          </div>
          <button
            type="submit"
            className="btn btn-primary"
            disabled={isPasswordUpdating}
          >
            {isPasswordUpdating ? <LoadingSpinner size="sm" color="white" /> : 'Parolni o\'zgartirish'}
          </button>
        </form>
      </div>

      {/* Til sozlamalari (keyinchalik qo'shiladi) */}
      <div className="bg-white rounded-lg shadow-md p-6">
        <h2 className="text-2xl font-bold text-gray-900 mb-4">Til sozlamalari</h2>
        <p className="text-gray-600">Bu qism keyinchalik qo'shiladi.</p>
      </div>
    </div>
  )
}

export default ProfilePage
