import React from 'react'

interface ButtonProps extends React.ButtonHTMLAttributes<HTMLButtonElement> {
  variant?: 'primary' | 'secondary' | 'accent' | 'outline'
  size?: 'sm' | 'md' | 'lg'
  children: React.ReactNode
}

export default function Button({
  variant = 'primary',
  size = 'md',
  children,
  className = '',
  ...props
}: ButtonProps) {
  const baseStyles = 'inline-flex items-center justify-center font-semibold transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed'

  const variantStyles = {
    primary: 'bg-[var(--library-primary)] text-white hover:bg-[var(--library-primary-light)] hover:shadow-lg',
    secondary: 'bg-white text-[var(--library-primary)] border-2 border-[var(--library-border)] hover:border-[var(--library-primary)] hover:bg-[var(--library-cream)]',
    accent: 'bg-[var(--library-accent)] text-[var(--library-primary-dark)] hover:bg-[var(--library-accent-light)] hover:shadow-lg',
    outline: 'bg-transparent text-[var(--library-primary)] border border-[var(--library-primary)] hover:bg-[var(--library-primary)] hover:text-white'
  }

  const sizeStyles = {
    sm: 'px-3 py-1.5 text-sm rounded-md',
    md: 'px-6 py-2.5 text-base rounded-md',
    lg: 'px-8 py-3 text-lg rounded-lg'
  }

  return (
    <button
      className={`${baseStyles} ${variantStyles[variant]} ${sizeStyles[size]} ${className}`}
      {...props}
    >
      {children}
    </button>
  )
}
