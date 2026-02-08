'use client'

import React from 'react'
import BookCard from './BookCard'
import { BookOpen } from 'lucide-react'

interface Resource {
  id: string
  title: string
  author?: {
    name: string
  } | null
  description?: string | null
  resource_type: string
  available_copies?: number | null
  cover_image?: string | null
  isbn?: string | null
  published_year?: number | null
  category?: {
    name: string
  } | null
}

interface BookGridProps {
  resources: Resource[]
  isLoading?: boolean
}

export default function BookGrid({ resources, isLoading = false }: BookGridProps) {
  if (isLoading) {
    return (
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        {[1, 2, 3, 4, 5, 6, 7, 8].map((i) => (
          <div key={i} className="bg-white border border-slate-200 rounded-xl overflow-hidden">
            <div className="aspect-[4/3] bg-slate-100 animate-pulse" />
            <div className="p-4">
              <div className="h-5 w-24 bg-slate-100 rounded animate-pulse mb-2" />
              <div className="h-4 w-full bg-slate-100 rounded animate-pulse mb-2" />
              <div className="h-4 w-2/3 bg-slate-100 rounded animate-pulse mb-2" />
              <div className="h-4 w-full bg-slate-100 rounded animate-pulse mb-4" />
              <div className="h-10 w-full bg-slate-100 rounded-lg animate-pulse" />
            </div>
          </div>
        ))}
      </div>
    )
  }

  if (resources.length === 0) {
    return (
      <div className="text-center py-16">
        <div className="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
          <BookOpen className="w-8 h-8 text-slate-300" />
        </div>
        <h3 className="text-lg font-semibold text-slate-900 mb-2">No resources found</h3>
        <p className="text-slate-500 text-sm">
          Try adjusting your search terms or filters to find what you're looking for.
        </p>
      </div>
    )
  }

  return (
    <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
      {resources.map((resource) => (
        <BookCard
          key={resource.id}
          id={resource.id}
          title={resource.title}
          author={resource.author}
          description={resource.description}
          resourceType={resource.resource_type}
          availableCopies={resource.available_copies}
          coverImage={resource.cover_image}
          isbn={resource.isbn}
          publishedYear={resource.published_year}
          category={resource.category}
        />
      ))}
    </div>
  )
}
