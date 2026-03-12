'use client'

import Image from 'next/image'
import Link from 'next/link'
import { Clock, BookOpen } from 'lucide-react'

interface ReservationCardProps {
  reservation: {
    id: string
    status: string
    expires_at?: string
    resource?: {
      id: string
      title: string
      cover_image?: string
      author?: {
        name: string
      }
    }
  }
  onCancel?: (reservationId: string) => void
  isCancelling?: boolean
}

export default function ReservationCard({ reservation, onCancel, isCancelling }: ReservationCardProps) {
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
      pending: { color: 'text-amber-700', bg: 'bg-amber-50', label: 'Pending' },
      ready: { color: 'text-blue-700', bg: 'bg-blue-50', label: 'Ready to Pick Up' },
      fulfilled: { color: 'text-emerald-700', bg: 'bg-emerald-50', label: 'Fulfilled' },
      cancelled: { color: 'text-slate-500', bg: 'bg-slate-100', label: 'Cancelled' },
      expired: { color: 'text-red-700', bg: 'bg-red-50', label: 'Expired' },
    }
    const s = statusMap[status] || { color: 'text-slate-700', bg: 'bg-slate-100', label: status }
    return (
      <span className={`inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold ${s.bg} ${s.color}`}>
        {s.label}
      </span>
    )
  }

  const imageUrl = getImageUrl(reservation.resource?.cover_image)
  const canCancel = reservation.status === 'pending' || reservation.status === 'ready'
  const isReady = reservation.status === 'ready'
  const resource = reservation.resource

  if (!resource) {
    return null
  }

  return (
    <div className={`bg-white rounded-xl shadow-sm border overflow-hidden hover:shadow-md hover:-translate-y-0.5 transition-all ${
      isReady ? 'border-blue-200' : 'border-slate-200'
    }`}>
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
              {getStatusBadge(reservation.status)}
              {reservation.expires_at && (
                <span className="flex items-center gap-1 text-xs text-slate-400">
                  <Clock className="w-3 h-3" />
                  Expires: {new Date(reservation.expires_at).toLocaleDateString()}
                </span>
              )}
            </div>

            {isReady && (
              <p className="text-xs text-blue-600 mt-2 font-medium">
                📚 Your book is ready for pickup!
              </p>
            )}
          </div>
        </div>

        {/* Action Button */}
        {canCancel && onCancel && (
          <div className="mt-3 pt-3 border-t border-slate-100">
            <button
              onClick={() => onCancel(reservation.id)}
              disabled={isCancelling}
              className="w-full px-4 py-2 text-sm font-semibold text-red-600 hover:text-red-700 border-2 border-red-200 rounded-lg hover:bg-red-50 disabled:opacity-50 transition-all"
            >
              {isCancelling ? 'Cancelling...' : 'Cancel Reservation'}
            </button>
          </div>
        )}
      </div>
    </div>
  )
}
