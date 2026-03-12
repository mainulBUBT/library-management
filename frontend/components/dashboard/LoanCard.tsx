'use client'

import Image from 'next/image'
import Link from 'next/link'
import { Calendar, BookOpen, AlertCircle } from 'lucide-react'

interface LoanCardProps {
  loan: {
    id: string
    status: string
    due_date?: string
    copy?: {
      resource?: {
        id: string
        title: string
        cover_image?: string
        author?: {
          name: string
        }
      }
    }
  }
  onRenew?: (loanId: string) => void
  isRenewing?: boolean
}

export default function LoanCard({ loan, onRenew, isRenewing }: LoanCardProps) {
  const getImageUrl = (imagePath: string | null | undefined) => {
    if (!imagePath) return null
    if (imagePath.startsWith('http://') || imagePath.startsWith('https://')) {
      return imagePath
    }
    const baseUrl = process.env.NEXT_PUBLIC_API_URL?.replace('/api/v1', '') || 'http://localhost:8000'
    return `${baseUrl}/${imagePath.startsWith('/') ? imagePath.slice(1) : imagePath}`
  }

  const getStatusBadge = (status: string) => {
    const statusMap: Record<string, { color: string; bg: string; label: string }> = {
      active: { color: 'text-emerald-700', bg: 'bg-emerald-50', label: 'Active' },
      returned: { color: 'text-slate-500', bg: 'bg-slate-100', label: 'Returned' },
      overdue: { color: 'text-red-700', bg: 'bg-red-50', label: 'Overdue' },
    }
    const s = statusMap[status] || { color: 'text-slate-700', bg: 'bg-slate-100', label: status }
    return (
      <span className={`inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold ${s.bg} ${s.color}`}>
        {s.label}
      </span>
    )
  }

  const imageUrl = getImageUrl(loan.copy?.resource?.cover_image)
  const isOverdue = loan.status === 'overdue'
  const isActive = loan.status === 'active'
  const resource = loan.copy?.resource

  if (!resource) {
    return null
  }

  return (
    <div className="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md hover:-translate-y-0.5 transition-all">
      <div className="p-4">
        <div className="flex gap-4">
          {/* Book Cover */}
          <div className="w-16 h-20 bg-slate-100 rounded-lg flex-shrink-0 flex items-center justify-center overflow-hidden border border-slate-200">
            {imageUrl ? (
              <Image
                src={imageUrl}
                alt={resource.title}
                width={64}
                height={80}
                className="object-cover w-full h-full"
              />
            ) : (
              <BookOpen className="w-6 h-6 text-slate-300" />
            )}
          </div>

          {/* Content */}
          <div className="flex-1 min-w-0">
            <Link
              href={`/catalog/${resource.id}`}
              className="font-semibold text-slate-900 hover:text-indigo-600 transition-colors line-clamp-1"
            >
              {resource.title}
            </Link>
            {resource.author && (
              <p className="text-sm text-slate-500 mt-1 line-clamp-1">{resource.author.name}</p>
            )}

            <div className="flex items-center gap-3 mt-2 flex-wrap">
              {getStatusBadge(loan.status)}
              {loan.due_date && (
                <span className={`flex items-center gap-1 text-xs ${isOverdue ? 'text-red-600 font-semibold' : 'text-slate-400'}`}>
                  <Calendar className="w-3 h-3" />
                  Due: {new Date(loan.due_date).toLocaleDateString()}
                </span>
              )}
            </div>
          </div>
        </div>

        {/* Action Button */}
        {isActive && onRenew && (
          <div className="mt-3 pt-3 border-t border-slate-100">
            <button
              onClick={() => onRenew(loan.id)}
              disabled={isRenewing}
              className="w-full px-4 py-2 text-sm font-semibold text-indigo-600 hover:text-indigo-700 border-2 border-indigo-200 rounded-lg hover:bg-indigo-50 disabled:opacity-50 transition-all text-center"
            >
              {isRenewing ? 'Renewing...' : 'Renew Loan'}
            </button>
          </div>
        )}

        {isOverdue && (
          <div className="mt-3 pt-3 border-t border-slate-100">
            <div className="flex items-center gap-2 text-xs text-red-600 bg-red-50 px-3 py-2 rounded-lg">
              <AlertCircle className="w-4 h-4 flex-shrink-0" />
              <span>This loan is overdue. Please return it as soon as possible.</span>
            </div>
          </div>
        )}
      </div>
    </div>
  )
}
