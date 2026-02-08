import React from 'react'

interface StatsCardProps {
  value: string | number
  label: string
  icon?: React.ReactNode
  trend?: {
    value: string
    positive: boolean
  }
  className?: string
}

export default function StatsCard({ value, label, icon, trend, className = '' }: StatsCardProps) {
  return (
    <div className={`stats-card ${className}`}>
      <div className="flex items-start justify-between">
        <div>
          <div className="stats-number">{value}</div>
          <div className="stats-label mt-1">{label}</div>
        </div>
        {icon && (
          <div className="p-3 rounded-lg bg-[var(--library-cream)] text-[var(--library-primary)]">
            {icon}
          </div>
        )}
      </div>
      {trend && (
        <div className={`mt-4 text-sm font-medium flex items-center gap-1 ${
          trend.positive ? 'text-green-600' : 'text-red-600'
        }`}>
          <span>{trend.positive ? '↑' : '↓'}</span>
          <span>{trend.value}</span>
        </div>
      )}
    </div>
  )
}
