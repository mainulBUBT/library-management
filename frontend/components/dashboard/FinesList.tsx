'use client'

import { useQuery } from '@tanstack/react-query'
import api from '@/lib/api'
import { DollarSign, Calendar, Check, AlertCircle, BookOpen, Loader2 } from 'lucide-react'
import Image from 'next/image'

interface Fine {
  id: string
  amount: number
  balance: number
  amount_paid: number
  status: string
  created_at: string
  loan: {
    copy: {
      resource: {
        title: string
        cover_image?: string
      }
    }
  }
}

export default function FinesList() {
  const { data: finesData, isLoading, error } = useQuery({
    queryKey: ['my-fines'],
    queryFn: async () => {
      const response = await api.get('/my-fines')
      return response.data
    },
  })

  const fines = finesData?.data || []

  // Calculate total outstanding
  const totalOutstanding = fines
    .filter((f: Fine) => f.balance > 0)
    .reduce((sum: number, f: Fine) => sum + f.balance, 0)

  const unpaidCount = fines.filter((f: Fine) => f.balance > 0).length

  if (isLoading) {
    return <FinesListSkeleton />
  }

  if (error) {
    return (
      <div className="bg-red-50 border-2 border-red-200 rounded-xl p-6">
        <div className="flex items-start gap-3">
          <AlertCircle className="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" />
          <div>
            <h3 className="font-bold text-red-900">Error Loading Fines</h3>
            <p className="text-sm text-red-700 mt-1">Unable to load your fines. Please try again later.</p>
          </div>
        </div>
      </div>
    )
  }

  if (fines.length === 0) {
    return <FinesEmptyState />
  }

  return (
    <div className="space-y-6">
      {/* Summary Card */}
      <div className="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl p-6 text-white relative overflow-hidden">
        <div className="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2" />
        <div className="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-1/2 -translate-x-1/2" />
        <div className="relative">
          <div className="flex items-center justify-between">
            <div>
              <p className="text-indigo-100 text-sm font-medium mb-1">Total Outstanding</p>
              <p className="text-3xl font-bold">${totalOutstanding.toFixed(2)}</p>
              {unpaidCount > 0 && (
                <p className="text-indigo-200 text-sm mt-1">{unpaidCount} unpaid fine{unpaidCount > 1 ? 's' : ''}</p>
              )}
            </div>
            <div className="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
              <DollarSign className="w-7 h-7" />
            </div>
          </div>
        </div>
      </div>

      {/* Fines List */}
      <div className="space-y-3 stagger-children">
        {fines.map((fine: Fine, index: number) => (
          <FineCard key={fine.id} fine={fine} index={index} />
        ))}
      </div>
    </div>
  )
}

function FineCard({ fine, index }: { fine: Fine; index: number }) {
  const isPaid = fine.balance === 0

  return (
    <div
      className="bg-slate-50 rounded-xl p-4 flex items-center gap-4 hover:bg-slate-100 transition-colors"
    >
      {/* Book Cover */}
      <div className="w-14 h-18 bg-white rounded-lg flex-shrink-0 flex items-center justify-center overflow-hidden border border-slate-200">
        {fine.loan?.copy?.resource?.cover_image ? (
          <Image
            src={getImageUrl(fine.loan.copy.resource.cover_image)}
            alt={fine.loan.copy.resource.title}
            width={56}
            height={72}
            className="object-cover w-full h-full"
          />
        ) : (
          <BookOpen className="w-5 h-5 text-slate-300" />
        )}
      </div>

      {/* Details */}
      <div className="flex-1 min-w-0">
        <h4 className="font-semibold text-slate-900 truncate">
          {fine.loan?.copy?.resource?.title || 'Unknown Resource'}
        </h4>
        <div className="flex items-center gap-3 mt-1 text-sm text-slate-500">
          <span className="flex items-center gap-1">
            <Calendar className="w-3.5 h-3.5" />
            {new Date(fine.created_at).toLocaleDateString()}
          </span>
        </div>
      </div>

      {/* Amount */}
      <div className="text-right">
        <p className={`text-xl font-bold ${isPaid ? 'text-emerald-600' : 'text-slate-900'}`}>
          ${fine.balance.toFixed(2)}
        </p>
        {isPaid ? (
          <span className="inline-flex items-center gap-1 text-xs font-semibold text-emerald-700 bg-emerald-50 px-2 py-1 rounded-full">
            <Check className="w-3 h-3" />
            Paid
          </span>
        ) : (
          <span className="inline-flex items-center px-2 py-1 text-xs font-semibold text-amber-700 bg-amber-50 rounded-full">
            Unpaid
          </span>
        )}
      </div>
    </div>
  )
}

function FinesListSkeleton() {
  return (
    <div className="space-y-6">
      <div className="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl p-6 text-white animate-pulse relative overflow-hidden">
        <div className="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2" />
        <div className="flex items-center justify-between relative">
          <div>
            <div className="h-4 bg-white/30 rounded w-32 mb-2" />
            <div className="h-8 bg-white/30 rounded w-24" />
          </div>
          <div className="w-14 h-14 bg-white/20 rounded-xl" />
        </div>
      </div>
      <div className="space-y-3">
        {[1, 2, 3].map((i) => (
          <div key={i} className="bg-slate-50 rounded-xl p-4 flex items-center gap-4">
            <div className="w-14 h-18 bg-white rounded-lg animate-pulse" />
            <div className="flex-1">
              <div className="h-4 bg-slate-200 rounded w-48 mb-2" />
              <div className="h-3 bg-slate-200 rounded w-24" />
            </div>
            <div className="w-16 h-6 bg-slate-200 rounded animate-pulse" />
          </div>
        ))}
      </div>
    </div>
  )
}

function FinesEmptyState() {
  return (
    <div className="text-center py-16">
      <div className="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <Check className="w-10 h-10 text-emerald-500" />
      </div>
      <h3 className="text-xl font-bold text-slate-900 mb-2">No Fines</h3>
      <p className="text-slate-500 max-w-sm mx-auto">
        Great job! You don't have any outstanding fines. Keep returning your books on time.
      </p>
    </div>
  )
}

function getImageUrl(imagePath: string | null | undefined) {
  if (!imagePath) return null
  if (imagePath.startsWith('http://') || imagePath.startsWith('https://')) {
    return imagePath
  }
  const baseUrl = process.env.NEXT_PUBLIC_API_URL?.replace('/api/v1', '') || 'http://localhost:8000'
  return `${baseUrl}/${imagePath.startsWith('/') ? imagePath.slice(1) : imagePath}`
}
