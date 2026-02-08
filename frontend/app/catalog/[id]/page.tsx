'use client'

import { useState, useEffect, use } from 'react'
import { useRouter } from 'next/navigation'
import Image from 'next/image'
import Link from 'next/link'
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query'
import api from '@/lib/api'
import { useAuthStore } from '@/stores/authStore'
import LibraryHeader from '@/components/layout/LibraryHeader'
import LibraryFooter from '@/components/layout/LibraryFooter'
import { TypeBadge } from '@/components/ui/Badge'
import {
  BookOpen,
  Calendar,
  User,
  Barcode,
  Building2,
  ArrowLeft,
  Bookmark,
  Check,
  Loader2,
  AlertCircle,
} from 'lucide-react'

interface BookDetailsProps {
  params: { id: string }
}

export default function BookDetailsPage({ params }: BookDetailsProps) {
  const router = useRouter()
  const queryClient = useQueryClient()
  const { id } = use(params)
  const isAuthenticated = useAuthStore((state) => state.isAuthenticated)

  // Fetch book details
  const { data: bookData, isLoading } = useQuery({
    queryKey: ['book', id],
    queryFn: async () => {
      const response = await api.get(`/catalog/${id}`)
      return response.data
    },
  })

  // Reserve mutation
  const reserveMutation = useMutation({
    mutationFn: async () => {
      const response = await api.post('/reservations', { resource_id: id })
      return response.data
    },
    onSuccess: (data) => {
      queryClient.invalidateQueries({ queryKey: ['my-reservations'] })
      setMessage({ type: 'success', text: data.message || 'Reservation created successfully!' })
    },
    onError: (err: any) => {
      const errorMsg = err.response?.data?.message || 'Failed to create reservation. Please try again.'
      setMessage({ type: 'error', text: errorMsg })
    },
  })

  const [message, setMessage] = useState<{ type: 'success' | 'error'; text: string } | null>(null)

  const handleReserve = () => {
    if (!isAuthenticated) {
      router.push(`/login?redirect=/catalog/${id}`)
      return
    }

    if (bookData?.available_copies === 0) {
      setMessage({ type: 'error', text: 'This item is currently unavailable.' })
      return
    }

    reserveMutation.mutate()
  }

  const book = bookData?.data

  if (isLoading) {
    return (
      <div className="min-h-screen flex flex-col bg-[var(--color-background)]">
        <LibraryHeader />
        <main className="flex-1 flex items-center justify-center">
          <Loader2 className="w-8 h-8 animate-spin text-slate-400" />
        </main>
      </div>
    )
  }

  if (!book) {
    return (
      <div className="min-h-screen flex flex-col bg-[var(--color-background)]">
        <LibraryHeader />
        <main className="flex-1 flex items-center justify-center">
          <div className="text-center">
            <AlertCircle className="w-16 h-16 text-slate-300 mx-auto mb-4" />
            <h2 className="text-xl font-semibold text-slate-900 mb-2">Book Not Found</h2>
            <Link href="/catalog" className="text-slate-600 hover:text-slate-900">
              ← Back to Catalog
            </Link>
          </div>
        </main>
      </div>
    )
  }

  const getImageUrl = (imagePath: string | null) => {
    if (!imagePath) return null
    if (imagePath.startsWith('http://') || imagePath.startsWith('https://')) {
      return imagePath
    }
    const baseUrl = process.env.NEXT_PUBLIC_API_URL?.replace('/api/v1', '') || 'http://localhost:8000'
    return `${baseUrl}/${imagePath.startsWith('/') ? imagePath.slice(1) : imagePath}`
  }

  const imageUrl = getImageUrl(book.cover_image)
  const isAvailable = book.available_copies > 0

  return (
    <div className="min-h-screen flex flex-col bg-[var(--color-background)]">
      <LibraryHeader />

      <main className="flex-1">
        {/* Breadcrumb */}
        <div className="bg-white border-b border-slate-200">
          <div className="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 py-3">
            <Link
              href="/catalog"
              className="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-900"
            >
              <ArrowLeft className="w-4 h-4" />
              Back to Catalog
            </Link>
          </div>
        </div>

        <div className="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 py-8">
          <div className="grid grid-cols-1 md:grid-cols-[300px_1fr] gap-8">
            {/* Book Cover */}
            <div className="flex flex-col items-center">
              <div className="relative aspect-[2/3] w-full max-w-[280px] bg-slate-100 rounded-lg overflow-hidden shadow-lg">
                {imageUrl ? (
                  <Image
                    src={imageUrl}
                    alt={book.title}
                    fill
                    className="object-contain"
                    sizes="(max-width: 768px) 280px, 280px"
                  />
                ) : (
                  <div className="absolute inset-0 flex flex-col items-center justify-center p-6 bg-slate-50">
                    <BookOpen className="w-16 h-16 text-slate-300 mb-2" />
                    <span className="text-sm text-slate-400">No Cover Available</span>
                  </div>
                )}
              </div>

              {/* Availability Badge */}
              <div className={`mt-4 inline-flex items-center gap-2 px-4 py-2 rounded-lg border ${
                isAvailable
                  ? 'bg-emerald-50 border-emerald-200 text-emerald-700'
                  : 'bg-amber-50 border-amber-200 text-amber-700'
              }`}>
                <span className={`w-2 h-2 rounded-full ${
                  isAvailable ? 'bg-emerald-500' : 'bg-amber-500'
                }`} />
                <span className="text-sm font-medium">
                  {isAvailable
                    ? `${book.available_copies} ${book.available_copies === 1 ? 'Copy' : 'Copies'} Available`
                    : 'Currently Unavailable'
                  }
                </span>
              </div>
            </div>

            {/* Book Details */}
            <div>
              {/* Type Badge */}
              <div className="mb-3">
                <TypeBadge type={book.resource_type} />
              </div>

              {/* Title */}
              <h1 className="text-2xl sm:text-3xl font-semibold text-slate-900 mb-2">
                {book.title}
              </h1>

              {book.subtitle && (
                <p className="text-lg text-slate-600 mb-4">{book.subtitle}</p>
              )}

              {/* Author & Category */}
              <div className="flex flex-wrap items-center gap-4 mb-6 text-sm text-slate-600">
                {book.author && (
                  <div className="flex items-center gap-1.5">
                    <User className="w-4 h-4" />
                    <span>{book.author.name}</span>
                  </div>
                )}
                {book.category && (
                  <>
                    <span>•</span>
                    <span>{book.category.name}</span>
                  </>
                )}
              </div>

              {/* Alert Message */}
              {message && (
                <div className={`mb-6 p-4 rounded-lg flex items-start gap-3 ${
                  message.type === 'success'
                    ? 'bg-emerald-50 text-emerald-800 border border-emerald-200'
                    : 'bg-red-50 text-red-800 border border-red-200'
                }`}>
                  {message.type === 'success' ? (
                    <Check className="w-5 h-5 flex-shrink-0 mt-0.5" />
                  ) : (
                    <AlertCircle className="w-5 h-5 flex-shrink-0 mt-0.5" />
                  )}
                  <p className="text-sm">{message.text}</p>
                </div>
              )}

              {/* Description */}
              {book.description && (
                <div className="mb-8">
                  <h2 className="text-sm font-semibold text-slate-900 uppercase tracking-wide mb-2">
                    Description
                  </h2>
                  <p className="text-slate-600 leading-relaxed">{book.description}</p>
                </div>
              )}

              {/* Details Grid */}
              <div className="grid grid-cols-2 gap-4 mb-8">
                {book.isbn && (
                  <div className="flex items-center gap-2 text-sm">
                    <Barcode className="w-4 h-4 text-slate-400" />
                    <div>
                      <span className="text-slate-500">ISBN</span>
                      <p className="text-slate-900 font-medium">{book.isbn}</p>
                    </div>
                  </div>
                )}
                {book.published_year && (
                  <div className="flex items-center gap-2 text-sm">
                    <Calendar className="w-4 h-4 text-slate-400" />
                    <div>
                      <span className="text-slate-500">Published</span>
                      <p className="text-slate-900 font-medium">{book.published_year}</p>
                    </div>
                  </div>
                )}
                {book.publisher && (
                  <div className="flex items-center gap-2 text-sm">
                    <Building2 className="w-4 h-4 text-slate-400" />
                    <div>
                      <span className="text-slate-500">Publisher</span>
                      <p className="text-slate-900 font-medium">{book.publisher.name}</p>
                    </div>
                  </div>
                )}
                {book.resource_type && (
                  <div className="flex items-center gap-2 text-sm">
                    <Bookmark className="w-4 h-4 text-slate-400" />
                    <div>
                      <span className="text-slate-500">Type</span>
                      <p className="text-slate-900 font-medium capitalize">{book.resource_type.replace('_', ' ')}</p>
                    </div>
                  </div>
                )}
              </div>

              {/* Action Buttons */}
              <div className="flex flex-col sm:flex-row gap-3">
                <button
                  onClick={handleReserve}
                  disabled={reserveMutation.isPending || !isAvailable}
                  className={`flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg font-medium transition-all ${
                    !isAvailable
                      ? 'bg-slate-100 text-slate-400 cursor-not-allowed'
                      : 'bg-slate-900 text-white hover:bg-slate-800'
                  }`}
                >
                  {reserveMutation.isPending ? (
                    <Loader2 className="w-4 h-4 animate-spin" />
                  ) : (
                    <>
                      <Bookmark className="w-4 h-4" />
                      {isAvailable ? 'Reserve This Book' : 'Not Available'}
                    </>
                  )}
                </button>

                {isAuthenticated && (
                  <Link
                    href="/dashboard?tab=reservations"
                    className="px-6 py-3 rounded-lg font-medium border border-slate-300 text-slate-700 hover:bg-slate-50 text-center transition-all"
                  >
                    My Reservations
                  </Link>
                )}
              </div>

              {!isAuthenticated && (
                <p className="text-sm text-slate-500 mt-3">
                  Please{' '}
                  <Link href={`/login?redirect=/catalog/${id}`} className="text-slate-900 font-medium hover:underline">
                    sign in
                  </Link>{' '}
                  to reserve this book.
                </p>
              )}
            </div>
          </div>

          {/* All Authors Section */}
          {book.authors && book.authors.length > 0 && (
            <div className="mt-12 pt-8 border-t border-slate-200">
              <h2 className="text-lg font-semibold text-slate-900 mb-4">Author{book.authors.length > 1 ? 's' : ''}</h2>
              <div className="flex flex-wrap gap-3">
                {book.authors.map((author: any) => (
                  <Link
                    key={author.id}
                    href={`/catalog?author=${author.id}`}
                    className="px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm text-slate-700 hover:border-slate-300 hover:text-slate-900 transition-all"
                  >
                    {author.name}
                  </Link>
                ))}
              </div>
            </div>
          )}
        </div>
      </main>

      <LibraryFooter />
    </div>
  )
}
