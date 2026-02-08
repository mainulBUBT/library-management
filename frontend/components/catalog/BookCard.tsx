'use client'

import Image from 'next/image'
import Link from 'next/link'
import { BookOpen, Calendar, User } from 'lucide-react'

interface BookCardProps {
  id: string
  title: string
  author?: {
    name: string
  } | null
  description?: string | null
  resourceType: string
  availableCopies?: number | null
  coverImage?: string | null
  isbn?: string | null
  publishedYear?: number | null
  category?: {
    name: string
  } | null
}

export default function BookCard({
  id,
  title,
  author,
  resourceType,
  availableCopies,
  coverImage,
  publishedYear,
}: BookCardProps) {
  const isAvailable = availableCopies !== null && availableCopies !== undefined && availableCopies > 0

  // Build full image URL for backend-hosted images
  const getImageUrl = (imagePath: string | null | undefined) => {
    if (!imagePath) return null
    if (imagePath.startsWith('http://') || imagePath.startsWith('https://')) {
      return imagePath
    }
    const baseUrl = process.env.NEXT_PUBLIC_API_URL?.replace('/api/v1', '') || 'http://localhost:8000'
    return `${baseUrl}/${imagePath.startsWith('/') ? imagePath.slice(1) : imagePath}`
  }

  const imageUrl = getImageUrl(coverImage)

  const formatType = (type: string) => {
    return type.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())
  }

  return (
    <div className="bg-white border border-slate-200 rounded-lg overflow-hidden hover:shadow-md hover:border-slate-300 transition-all duration-200 flex flex-col">
      {/* Image Container with Type Badge */}
      <div className="relative p-3 bg-slate-50">
        {/* Type Badge */}
        <span className="absolute top-3 left-3 z-10 inline-flex items-center rounded-md bg-slate-900/90 backdrop-blur-sm px-1.5 py-0.5 text-[9px] font-semibold uppercase tracking-wide text-white">
          {formatType(resourceType)}
        </span>

        {/* Book Cover - Full image visible */}
        <div className="relative aspect-[2/3] mx-auto max-w-[140px]">
          {imageUrl ? (
            <Image
              src={imageUrl}
              alt={title}
              fill
              className="object-contain rounded"
              sizes="(max-width: 640px) 140px, 140px"
            />
          ) : (
            <div className="absolute inset-0 flex flex-col items-center justify-center p-3 bg-white rounded border border-slate-200">
              <BookOpen className="w-8 h-8 text-slate-300 mb-1" />
              <span className="text-[10px] text-slate-400 font-medium">No Cover</span>
            </div>
          )}
        </div>
      </div>

      {/* Book Info - Compact */}
      <div className="px-3 pb-3 flex-1 flex flex-col">
        {/* Title - Small */}
        <h3 className="font-medium text-slate-900 text-[10px] leading-snug mb-1 line-clamp-2 min-h-[1.5rem]">
          {title}
        </h3>

        {/* Author - Extra small */}
        {author && (
          <div className="flex items-center gap-1 text-[10px] text-slate-500 mb-1">
            <User className="w-2.5 h-2.5" />
            <span className="truncate">{author.name}</span>
          </div>
        )}

        {/* Availability - Extra small */}
        <div className="flex items-center gap-1 mb-1">
          <span className={`w-1.5 h-1.5 rounded-full ${
            isAvailable ? 'bg-emerald-500' : 'bg-amber-500'
          }`} />
          <span className={`text-[10px] ${isAvailable ? 'text-emerald-600' : 'text-amber-600'}`}>
            {isAvailable
              ? `${availableCopies} available`
              : 'Unavailable'
            }
          </span>
        </div>

        {/* Published Date - Tiny */}
        {publishedYear && (
          <div className="flex items-center gap-1 text-[9px] text-slate-400 mb-2">
            <Calendar className="w-2.5 h-2.5" />
            <span>{publishedYear}</span>
          </div>
        )}

        {/* Spacer to push button to bottom */}
        <div className="flex-1" />

        {/* Action Button - Small */}
        <Link
          href={`/catalog/${id}`}
          className="w-full inline-flex items-center justify-center px-3 py-1.5 text-[10px] font-medium rounded bg-slate-900 text-white hover:bg-slate-800 transition-colors"
        >
          View Details
        </Link>
      </div>
    </div>
  )
}
