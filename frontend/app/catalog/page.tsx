'use client'

import { useState } from 'react'
import { useQuery } from '@tanstack/react-query'
import api from '@/lib/api'
import Link from 'next/link'
import { useAuthStore } from '@/stores/authStore'

export default function CatalogPage() {
  const isAuthenticated = useAuthStore((state) => state.isAuthenticated)
  const [searchTerm, setSearchTerm] = useState('')
  const [selectedCategory, setSelectedCategory] = useState('')
  const [selectedType, setSelectedType] = useState('')

  // Fetch categories
  const { data: categoriesData } = useQuery({
    queryKey: ['categories'],
    queryFn: async () => {
      const response = await api.get('/categories')
      return response.data
    },
  })

  // Fetch resources
  const { data: resourcesData, isLoading } = useQuery({
    queryKey: ['catalog', searchTerm, selectedCategory, selectedType],
    queryFn: async () => {
      const params = new URLSearchParams()
      if (searchTerm) params.append('search', searchTerm)
      if (selectedCategory) params.append('category_id', selectedCategory)
      if (selectedType) params.append('type', selectedType)

      const response = await api.get(`/catalog?${params.toString()}`)
      return response.data
    },
  })

  const categories = categoriesData?.categories || []
  const resources = resourcesData?.data || []

  return (
    <div className="min-h-screen bg-gray-50">
      {/* Header */}
      <header className="bg-white border-b border-gray-200">
        <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <div className="flex h-16 items-center justify-between">
            <Link href="/" className="flex items-center gap-3">
              <svg className="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253m0-13C6.832 5.477 5.754 18 4.5 1.253v13" />
              </svg>
              <span className="text-xl font-bold text-gray-900">Library Catalog</span>
            </Link>
            <div className="flex items-center gap-4">
              {isAuthenticated ? (
                <Link href="/dashboard" className="text-sm font-medium text-gray-600 hover:text-indigo-600">
                  Dashboard
                </Link>
              ) : (
                <>
                  <Link href="/login" className="text-sm font-medium text-gray-600 hover:text-indigo-600">
                    Sign In
                  </Link>
                  <Link href="/register" className="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                    Get Started
                  </Link>
                </>
              )}
            </div>
          </div>
        </div>
      </header>

      {/* Main Content */}
      <main className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
        <div className="flex flex-col lg:flex-row gap-8">
          {/* Filters Sidebar */}
          <aside className="lg:w-64 flex-shrink-0">
            <div className="bg-white rounded-lg shadow-sm p-6 sticky top-24">
              <h3 className="font-semibold text-gray-900 mb-4">Filters</h3>

              {/* Search */}
              <div className="mb-6">
                <label className="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input
                  type="text"
                  value={searchTerm}
                  onChange={(e) => setSearchTerm(e.target.value)}
                  placeholder="Title, ISBN, author..."
                  className="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 outline-none"
                />
              </div>

              {/* Category Filter */}
              <div className="mb-6">
                <label className="block text-sm font-medium text-gray-700 mb-2">Category</label>
                <select
                  value={selectedCategory}
                  onChange={(e) => setSelectedCategory(e.target.value)}
                  className="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 outline-none"
                >
                  <option value="">All Categories</option>
                  {categories.map((cat: any) => (
                    <option key={cat.id} value={cat.id}>{cat.name}</option>
                  ))}
                </select>
              </div>

              {/* Type Filter */}
              <div className="mb-6">
                <label className="block text-sm font-medium text-gray-700 mb-2">Resource Type</label>
                <select
                  value={selectedType}
                  onChange={(e) => setSelectedType(e.target.value)}
                  className="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 outline-none"
                >
                  <option value="">All Types</option>
                  <option value="book">Books</option>
                  <option value="journal">Journals</option>
                  <option value="magazine">Magazines</option>
                  <option value="newspaper">Newspapers</option>
                  <option value="digital_media">Digital Media</option>
                </select>
              </div>

              <button
                onClick={() => {
                  setSearchTerm('')
                  setSelectedCategory('')
                  setSelectedType('')
                }}
                className="w-full text-sm text-indigo-600 hover:text-indigo-700 font-medium"
              >
                Clear all filters
              </button>
            </div>
          </aside>

          {/* Resources Grid */}
          <div className="flex-1">
            <div className="mb-6 flex items-center justify-between">
              <h1 className="text-2xl font-bold text-gray-900">
                {isLoading ? 'Searching...' : `${resources.length} items found`}
              </h1>
            </div>

            {isLoading ? (
              <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                {[1, 2, 3, 4, 5, 6].map((i) => (
                  <div key={i} className="bg-white rounded-lg shadow-sm p-4 animate-pulse">
                    <div className="w-full h-48 bg-gray-200 rounded mb-4"></div>
                    <div className="h-4 bg-gray-200 rounded w-3/4 mb-2"></div>
                    <div className="h-3 bg-gray-200 rounded w-1/2"></div>
                  </div>
                ))}
              </div>
            ) : resources.length === 0 ? (
              <div className="text-center py-12">
                <svg className="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <h3 className="mt-2 text-sm font-semibold text-gray-900">No results found</h3>
                <p className="mt-1 text-sm text-gray-500">Try adjusting your search or filters.</p>
              </div>
            ) : (
              <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                {resources.map((resource: any) => (
                  <div key={resource.id} className="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                    <div className="h-48 bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center">
                      <svg className="w-16 h-16 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253m0-13C6.832 5.477 5.754 18 4.5 1.253v13" />
                      </svg>
                    </div>
                    <div className="p-4">
                      <span className="inline-block px-2 py-1 text-xs font-medium rounded bg-indigo-100 text-indigo-800 mb-2">
                        {resource.resource_type?.replace('_', ' ')}
                      </span>
                      <h3 className="font-semibold text-gray-900 mb-1 line-clamp-1">{resource.title}</h3>
                      <p className="text-sm text-gray-500 mb-2">{resource.author?.name || 'Unknown Author'}</p>
                      <p className="text-sm text-gray-600 line-clamp-2 mb-3">{resource.description || 'No description available.'}</p>
                      <div className="flex items-center justify-between">
                        <span className={`text-sm font-medium ${resource.available_copies && resource.available_copies > 0 ? 'text-green-600' : 'text-red-600'}`}>
                          {resource.available_copies && resource.available_copies > 0
                            ? `${resource.available_copies} available`
                            : 'Not available'}
                        </span>
                        {isAuthenticated && (
                          <button className="text-indigo-600 hover:text-indigo-700 text-sm font-medium">
                            Reserve
                          </button>
                        )}
                      </div>
                    </div>
                  </div>
                ))}
              </div>
            )}
          </div>
        </div>
      </main>
    </div>
  )
}
