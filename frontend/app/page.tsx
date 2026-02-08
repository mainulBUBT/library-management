'use client'

import { useState } from 'react'
import { useQuery } from '@tanstack/react-query'
import Link from 'next/link'
import { usePathname } from 'next/navigation'
import LibraryHeader from '@/components/layout/LibraryHeader'
import LibraryFooter from '@/components/layout/LibraryFooter'
import BookGrid from '@/components/catalog/BookGrid'
import {
  BookOpen,
  Search,
  Home as HomeIcon,
  Compass,
  LayoutDashboard,
  LogIn,
  UserPlus,
  SlidersHorizontal,
  X,
  Menu,
} from 'lucide-react'
import api from '@/lib/api'

export default function Home() {
  const pathname = usePathname()
  const [searchTerm, setSearchTerm] = useState('')
  const [selectedCategory, setSelectedCategory] = useState('')
  const [selectedType, setSelectedType] = useState('')
  const [showFilters, setShowFilters] = useState(false)
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false)

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
    { value: 'book', label: 'Books', icon: BookOpen },
    { value: 'journal', label: 'Journals', icon: BookOpen },
    { value: 'magazine', label: 'Magazines', icon: BookOpen },
    { value: 'newspaper', label: 'Newspapers', icon: BookOpen },
    { value: 'digital_media', label: 'Digital Media', icon: BookOpen },
  ]

  const hasActiveFilters = searchTerm || selectedCategory || selectedType

  const clearFilters = () => {
    setSearchTerm('')
    setSelectedCategory('')
    setSelectedType('')
  }

  const navLinks = [
    { href: '/', label: 'Home', icon: HomeIcon, active: pathname === '/' },
    { href: '/catalog', label: 'Catalog', icon: Compass, active: pathname === '/catalog' },
    { href: '/dashboard', label: 'Dashboard', icon: LayoutDashboard, active: pathname === '/dashboard' },
    { href: '/login', label: 'Sign In', icon: LogIn, active: pathname === '/login' },
    { href: '/register', label: 'Register', icon: UserPlus, active: pathname === '/register' },
  ]

  return (
    <div className="min-h-screen flex flex-col bg-[var(--color-background)]">
      <LibraryHeader />

      {/* Main Layout */}
      <main className="flex-1">
        <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
          <div className="flex flex-col lg:flex-row gap-8">
            {/* Sidebar */}
            <aside className="lg:w-64 flex-shrink-0">
              {/* Mobile Menu Toggle */}
              <button
                onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
                className="lg:hidden w-full flex items-center justify-center gap-2 px-4 py-3 bg-white rounded-lg border border-gray-200 text-gray-900 font-medium mb-4"
              >
                <Menu className="w-5 h-5" />
                {mobileMenuOpen ? 'Hide Menu' : 'Show Menu'}
              </button>

              {/* Sidebar Content */}
              <div className={`${mobileMenuOpen ? 'block' : 'hidden'} lg:block space-y-4`}>
                {/* Navigation */}
                <div className="card">
                  <h3 className="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <Menu className="w-4 h-4" />
                    Navigation
                  </h3>
                  <nav className="space-y-1">
                    {navLinks.map((link) => (
                      <Link
                        key={link.href}
                        href={link.href}
                        className={`flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors ${
                          link.active
                            ? 'bg-blue-50 text-blue-600 font-medium'
                            : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'
                        }`}
                      >
                        <link.icon className="w-5 h-5" />
                        {link.label}
                      </Link>
                    ))}
                  </nav>
                </div>

                {/* Filters */}
                <div className="card sticky top-24">
                  <div className="flex items-center justify-between mb-6">
                    <h3 className="font-semibold text-gray-900 flex items-center gap-2">
                      <SlidersHorizontal className="w-4 h-4" />
                      Filters
                    </h3>
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
                            Search: "{searchTerm.slice(0, 15)}{searchTerm.length > 15 ? '...' : ''}"
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
              </div>
            </aside>

            {/* Main Content */}
            <div className="flex-1 min-w-0">
              {/* Welcome Hero */}
              <div className="bg-gradient-to-r from-slate-800 to-slate-900 rounded-xl p-6 sm:p-8 mb-8 text-white">
                <div className="flex items-start justify-between">
                  <div>
                    <h1 className="text-2xl sm:text-3xl font-semibold mb-2">
                      Welcome to the Library
                    </h1>
                    <p className="text-slate-300 max-w-xl">
                      Browse our collection of books, journals, and digital resources. Use the filters to find exactly what you're looking for.
                    </p>
                  </div>
                  <div className="hidden sm:block">
                    <BookOpen className="w-16 h-16 text-white/10" />
                  </div>
                </div>
              </div>

              {/* Search Bar */}
              <div className="search-input-wrapper max-w-2xl mb-6">
                <Search className="w-5 h-5" />
                <input
                  type="text"
                  value={searchTerm}
                  onChange={(e) => setSearchTerm(e.target.value)}
                  placeholder="Search by title, author, ISBN..."
                  className="input-field pl-12"
                />
              </div>

              {/* Results Info */}
              <div className="flex items-center justify-between mb-6">
                <div>
                  <h2 className="text-xl font-semibold text-gray-900">
                    {isLoading ? 'Loading...' : hasActiveFilters ? 'Search Results' : 'All Resources'}
                  </h2>
                  <p className="text-gray-600 mt-1">
                    {isLoading ? 'Please wait...' : `${resources.length} items found`}
                  </p>
                </div>

                {/* Mobile Filter Toggle (below search) */}
                <button
                  onClick={() => setShowFilters(!showFilters)}
                  className="lg:hidden flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-lg text-blue-600 font-medium"
                >
                  <SlidersHorizontal className="w-5 h-5" />
                  Filters
                </button>
              </div>

              {/* Book Grid */}
              <BookGrid resources={resources} isLoading={isLoading} />
            </div>
          </div>
        </div>
      </main>

      <LibraryFooter />
    </div>
  )
}
