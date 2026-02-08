'use client'

import { useState } from 'react'
import { useQuery } from '@tanstack/react-query'
import api from '@/lib/api'
import LibraryHeader from '@/components/layout/LibraryHeader'
import LibraryFooter from '@/components/layout/LibraryFooter'
import BookGrid from '@/components/catalog/BookGrid'
import { Search, SlidersHorizontal, X } from 'lucide-react'

export default function CatalogPage() {
  const [searchTerm, setSearchTerm] = useState('')
  const [selectedCategory, setSelectedCategory] = useState('')
  const [selectedType, setSelectedType] = useState('')
  const [showFilters, setShowFilters] = useState(false)

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

  const resourceTypes = [
    { value: 'book', label: 'Books' },
    { value: 'journal', label: 'Journals' },
    { value: 'magazine', label: 'Magazines' },
    { value: 'newspaper', label: 'Newspapers' },
    { value: 'digital_media', label: 'Digital Media' },
  ]

  const hasActiveFilters = searchTerm || selectedCategory || selectedType

  const clearFilters = () => {
    setSearchTerm('')
    setSelectedCategory('')
    setSelectedType('')
  }

  return (
    <div className="min-h-screen flex flex-col bg-[var(--color-background)]">
      <LibraryHeader />

      {/* Page Header */}
      <section className="bg-white border-b border-gray-200">
        <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
          <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
              <h1 className="text-2xl sm:text-3xl font-bold text-gray-900">Library Catalog</h1>
              <p className="text-gray-600 mt-1">
                {isLoading ? 'Searching...' : `${resources.length} items found`}
              </p>
            </div>

            {/* Mobile Filter Toggle */}
            <button
              onClick={() => setShowFilters(!showFilters)}
              className="md:hidden flex items-center gap-2 px-4 py-2 bg-gray-100 rounded-lg text-blue-600 font-medium"
            >
              <SlidersHorizontal className="w-5 h-5" />
              Filters
            </button>
          </div>

          {/* Search Bar */}
          <div className="mt-6 search-input-wrapper max-w-2xl">
            <Search className="w-5 h-5" />
            <input
              type="text"
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
              placeholder="Search by title, author, ISBN..."
              className="input-field pl-12"
            />
          </div>
        </div>
      </section>

      {/* Main Content */}
      <main className="flex-1">
        <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
          <div className="flex flex-col lg:flex-row gap-8">
            {/* Filters Sidebar */}
            <aside
              className={`lg:w-64 flex-shrink-0 ${showFilters ? 'block' : 'hidden lg:block'}`}
            >
              <div className="card sticky top-24">
                <div className="flex items-center justify-between mb-6">
                  <h3 className="font-semibold text-gray-900">Filters</h3>
                  {hasActiveFilters && (
                    <button
                      onClick={clearFilters}
                      className="text-sm text-blue-600 hover:text-blue-700 font-medium flex items-center gap-1"
                    >
                      <X className="w-3 h-3" />
                      Clear all
                    </button>
                  )}
                </div>

                {/* Resource Type Filter */}
                <div className="mb-6">
                  <label className="block text-sm font-semibold text-gray-900 mb-3">
                    Resource Type
                  </label>
                  <div className="space-y-2">
                    {resourceTypes.map((type) => (
                      <label key={type.value} className="flex items-center gap-3 cursor-pointer group">
                        <input
                          type="radio"
                          name="resourceType"
                          value={type.value}
                          checked={selectedType === type.value}
                          onChange={(e) => setSelectedType(e.target.value)}
                          className="w-4 h-4 accent-blue-600"
                        />
                        <span className="text-sm text-gray-600 group-hover:text-gray-900 transition-colors">
                          {type.label}
                        </span>
                      </label>
                    ))}
                  </div>
                </div>

                {/* Category Filter */}
                {categories.length > 0 && (
                  <div className="mb-6">
                    <label className="block text-sm font-semibold text-gray-900 mb-3">
                      Category
                    </label>
                    <select
                      value={selectedCategory}
                      onChange={(e) => setSelectedCategory(e.target.value)}
                      className="input-field"
                    >
                      <option value="">All Categories</option>
                      {categories.map((cat: any) => (
                        <option key={cat.id} value={cat.id}>{cat.name}</option>
                      ))}
                    </select>
                  </div>
                )}

                {/* Active Filters Display */}
                {hasActiveFilters && (
                  <div className="pt-4 border-t border-gray-200">
                    <p className="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">
                      Active Filters
                    </p>
                    <div className="flex flex-wrap gap-2">
                      {searchTerm && (
                        <span className="inline-flex items-center gap-1 px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded-md">
                          Search: "{searchTerm}"
                          <button onClick={() => setSearchTerm('')} className="hover:text-blue-900">
                            <X className="w-3 h-3" />
                          </button>
                        </span>
                      )}
                      {selectedType && (
                        <span className="inline-flex items-center gap-1 px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded-md">
                          {resourceTypes.find(t => t.value === selectedType)?.label}
                          <button onClick={() => setSelectedType('')} className="hover:text-blue-900">
                            <X className="w-3 h-3" />
                          </button>
                        </span>
                      )}
                      {selectedCategory && (
                        <span className="inline-flex items-center gap-1 px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded-md">
                          {categories.find((c: any) => c.id === selectedCategory)?.name}
                          <button onClick={() => setSelectedCategory('')} className="hover:text-blue-900">
                            <X className="w-3 h-3" />
                          </button>
                        </span>
                      )}
                    </div>
                  </div>
                )}
              </div>
            </aside>

            {/* Results Grid */}
            <div className="flex-1">
              <BookGrid resources={resources} isLoading={isLoading} />
            </div>
          </div>
        </div>
      </main>

      <LibraryFooter />
    </div>
  )
}
