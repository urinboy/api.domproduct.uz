import React from 'react'

const LoadingSpinner = ({
  size = 'md',
  color = 'primary',
  text = null
}) => {
  const sizeClasses = {
    sm: 'h-4 w-4',
    md: 'h-6 w-6',
    lg: 'h-8 w-8',
    xl: 'h-12 w-12'
  }

  const colorClasses = {
    primary: 'border-primary-600',
    secondary: 'border-gray-600',
    white: 'border-white'
  }

  return (
    <div className="flex flex-col items-center justify-center">
      <div
        className={`
          animate-spin rounded-full border-2 border-t-transparent
          ${sizeClasses[size]}
          ${colorClasses[color]}
        `}
      />
      {text && (
        <p className="mt-2 text-sm text-gray-600">{text}</p>
      )}
    </div>
  )
}

export default LoadingSpinner
