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
      <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
        {[1, 2, 3, 4, 5, 6, 7, 8].map((i) => (
          <div key={i} className="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100 h-[420px] flex flex-col">
            <div className="aspect-[3/4] bg-slate-100 animate-pulse" />
            <div className="p-4 flex-1 flex flex-col">
              <div className="h-6 w-3/4 bg-slate-100 rounded animate-pulse mb-2" />
              <div className="h-4 w-1/2 bg-slate-100 rounded animate-pulse mb-6" />
              <div className="mt-auto">
                <div className="h-6 w-24 bg-slate-100 rounded-full animate-pulse mb-4" />
                <div className="grid grid-cols-2 gap-3">
                  <div className="h-9 bg-slate-100 rounded-lg animate-pulse" />
                  <div className="h-9 bg-slate-100 rounded-lg animate-pulse" />
                </div>
              </div>
            </div>
          </div>
        ))}
      </div>
    )
  }

  if (resources.length === 0) {
    return (
      <div className="text-center py-20 bg-white rounded-2xl border border-slate-100 border-dashed">
        <div className="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
          <BookOpen className="w-8 h-8 text-slate-300" />
        </div>
        <h3 className="text-lg font-semibold text-slate-900 mb-2">No resources found</h3>
        <p className="text-slate-500 text-sm max-w-sm mx-auto">
          We couldn't find any books matching your search. Try adjusting your filters or search terms.
        </p>
      </div>
    )
  }

  return (
    <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
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
