import React from 'react'

interface BadgeProps {
  children: React.ReactNode
  variant?: 'available' | 'borrowed' | 'reserved' | 'overdue' | 'default'
  className?: string
}

export default function Badge({ children, variant = 'default', className = '' }: BadgeProps) {
  const variantStyles = {
    available: 'status-badge-available',
    borrowed: 'status-badge-borrowed',
    reserved: 'status-badge-reserved',
    overdue: 'status-badge-overdue',
    default: 'bg-gray-100 text-gray-700'
  }

  return (
    <span className={`status-badge ${variantStyles[variant]} ${className}`}>
      {children}
    </span>
  )
}

interface TypeBadgeProps {
  type: string
  className?: string
}

export function TypeBadge({ type, className = '' }: TypeBadgeProps) {
  const formatType = (type: string) => {
    return type.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())
  }

  return (
    <span className={`inline-flex items-center gap-1 rounded bg-slate-900 px-1.5 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-white ${className}`}>
      {formatType(type)}
    </span>
  )
}
