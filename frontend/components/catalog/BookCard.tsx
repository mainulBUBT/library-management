'use client'

import React, { useState } from 'react'
import Image from 'next/image'
import Link from 'next/link'
import { BookOpen, Eye } from 'lucide-react'

interface BookCardProps {
  id: string
  title: string
  author?: {
    name: string
  } | null
  resourceType: string
  availableCopies?: number | null
  coverImage?: string | null
}

export default function BookCard({
  id,
  title,
  author,
  resourceType,
  availableCopies = 0,
  coverImage,
}: BookCardProps) {
  const [isImageLoaded, setIsImageLoaded] = useState(false)

  const getImageUrl = (imagePath: string | null | undefined) => {
    if (!imagePath) return null
    if (imagePath.startsWith('http://') || imagePath.startsWith('https://')) {
      return imagePath
    }
    const baseUrl = process.env.NEXT_PUBLIC_API_URL?.replace('/api/v1', '') || 'http://localhost:8000'
    return `${baseUrl}/${imagePath.startsWith('/') ? imagePath.slice(1) : imagePath}`
  }

  const imageUrl = getImageUrl(coverImage)
  const isAvailable = availableCopies !== null && availableCopies > 0

  const getTypeColor = (type: string) => {
    const typeMap: Record<string, string> = {
      book: 'bg-indigo-100 text-indigo-700',
      ebook: 'bg-purple-100 text-purple-700',
      journal: 'bg-blue-100 text-blue-700',
      magazine: 'bg-emerald-100 text-emerald-700',
      dvd: 'bg-orange-100 text-orange-700',
      cd: 'bg-pink-100 text-pink-700',
      research_paper: 'bg-cyan-100 text-cyan-700',
      audiobook: 'bg-violet-100 text-violet-700',
    }
    return typeMap[type] || 'bg-slate-100 text-slate-700'
  }

  return (
    <div className="group relative bg-white rounded-xl overflow-hidden shadow-sm border border-slate-100 transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5">
      {/* Cover Image */}
      <div className="relative aspect-[2/3] overflow-hidden bg-slate-100">
        {imageUrl ? (
          <>
            <Image
              src={imageUrl}
              alt={title}
              fill
              className={`object-cover transition-transform duration-300 group-hover:scale-105 ${
                isImageLoaded ? 'opacity-100' : 'opacity-0'
              }`}
              onLoad={() => setIsImageLoaded(true)}
              sizes="(max-width: 640px) 50vw, (max-width: 1024px) 33vw, 16vw"
            />
            {!isImageLoaded && (
              <div className="absolute inset-0 flex items-center justify-center bg-slate-100">
                <div className="w-6 h-6 border-2 border-slate-200 border-t-indigo-600 rounded-full animate-spin" />
              </div>
            )}
          </>
        ) : (
          <div className="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-slate-50 to-slate-100">
            <BookOpen className="w-12 h-12 text-slate-300" />
          </div>
        )}

        {/* Quick Actions Overlay */}
        <div className="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
          <Link
            href={`/catalog/${id}`}
            className="p-2.5 bg-white rounded-full text-indigo-600 hover:bg-indigo-50 transition-all hover:scale-110 shadow-lg"
            title="View details"
          >
            <Eye className="w-4 h-4" />
          </Link>
        </div>

        {/* Type Badge */}
        <div className="absolute top-2 left-2">
          <span className={`px-2 py-0.5 rounded-md text-[10px] font-semibold capitalize ${getTypeColor(resourceType)}`}>
            {resourceType.replace('_', ' ')}
          </span>
        </div>

        {/* Availability Dot */}
        <div className="absolute top-2 right-2">
          <div className={`w-2.5 h-2.5 rounded-full ${
            isAvailable
              ? 'bg-emerald-500 shadow-lg shadow-emerald-500/50'
              : 'bg-amber-500 shadow-lg shadow-amber-500/50'
          }`} />
        </div>
      </div>

      {/* Content */}
      <div className="p-3">
        {/* Title */}
        <Link
          href={`/catalog/${id}`}
          className="block font-medium text-sm text-slate-900 line-clamp-1 hover:text-indigo-600 transition-colors leading-tight"
        >
          {title}
        </Link>

        {/* Author */}
        {author && (
          <p className="text-xs text-slate-500 truncate mt-1">{author.name}</p>
        )}
      </div>

      {/* Shimmer Effect on Hover */}
      <div className="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:animate-shimmer pointer-events-none rounded-xl" />
    </div>
  )
}
