'use client'

import { useState } from 'react'
import { useQuery } from '@tanstack/react-query'
import api from '@/lib/api'
import LibraryHeader from '@/components/layout/LibraryHeader'
import LibraryFooter from '@/components/layout/LibraryFooter'
import BookGrid from '@/components/catalog/BookGrid'
import { Search, SlidersHorizontal, X, Loader2 } from 'lucide-react'

export default function Home() {
    const [searchTerm, setSearchTerm] = useState('')
    const [selectedCategory, setSelectedCategory] = useState('')
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
        queryKey: ['catalog', searchTerm, selectedCategory],
        queryFn: async () => {
            const params = new URLSearchParams()
            if (searchTerm) params.append('search', searchTerm)
            if (selectedCategory) params.append('category_id', selectedCategory)

            const response = await api.get(`/catalog?${params.toString()}`)
            return response.data
        },
    })

    const categories = categoriesData?.categories || []
    const resources = resourcesData?.data || []

    return (
        <div className="min-h-screen flex flex-col bg-slate-50">
            <LibraryHeader />

            <main className="flex-1 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 w-full">
                <div className="flex flex-col lg:flex-row gap-8">

                    {/* Sidebar / Filters */}
                    <aside className={`lg:w-64 flex-shrink-0 ${showFilters ? 'block' : 'hidden lg:block'}`}>
                        <div className="bg-white rounded-lg shadow-sm p-6 sticky top-24 border border-slate-100">
                            <div className="flex items-center justify-between mb-6">
                                <h2 className="font-bold text-slate-900">Catalog</h2>
                                {(searchTerm || selectedCategory) && (
                                    <button
                                        onClick={() => { setSearchTerm(''); setSelectedCategory('') }}
                                        className="text-xs text-red-500 hover:text-red-600 font-medium"
                                    >
                                        Clear All
                                    </button>
                                )}
                            </div>

                            {/* Search */}
                            <div className="mb-6">
                                <label className="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Search</label>
                                <div className="relative">
                                    <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
                                    <input
                                        type="text"
                                        placeholder="Keywords..."
                                        value={searchTerm}
                                        onChange={(e) => setSearchTerm(e.target.value)}
                                        className="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded text-sm focus:outline-none focus:border-indigo-500 transition-colors"
                                    />
                                </div>
                            </div>

                            {/* Categories */}
                            <div>
                                <label className="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Categories</label>
                                <div className="space-y-1">
                                    <button
                                        onClick={() => setSelectedCategory('')}
                                        className={`w-full text-left px-3 py-2 rounded text-sm transition-colors ${selectedCategory === ''
                                                ? 'bg-indigo-50 text-indigo-700 font-medium'
                                                : 'text-slate-600 hover:bg-slate-50'
                                            }`}
                                    >
                                        All Categories
                                    </button>
                                    {categories.map((cat: any) => (
                                        <button
                                            key={cat.id}
                                            onClick={() => setSelectedCategory(cat.id.toString())}
                                            className={`w-full text-left px-3 py-2 rounded text-sm transition-colors ${selectedCategory === cat.id.toString()
                                                    ? 'bg-indigo-50 text-indigo-700 font-medium'
                                                    : 'text-slate-600 hover:bg-slate-50'
                                                }`}
                                        >
                                            {cat.name}
                                        </button>
                                    ))}
                                </div>
                            </div>
                        </div>
                    </aside>

                    {/* Main Content */}
                    <div className="flex-1">
                        {/* Mobile Filter Toggle */}
                        <div className="lg:hidden mb-4">
                            <button
                                onClick={() => setShowFilters(!showFilters)}
                                className="w-full flex items-center justify-center gap-2 bg-white border border-slate-200 p-3 rounded-lg text-slate-700 font-medium"
                            >
                                <SlidersHorizontal className="w-4 h-4" />
                                {showFilters ? 'Hide Filters' : 'Show Filters'}
                            </button>
                        </div>

                        <BookGrid resources={resources} isLoading={isLoading} />
                    </div>
                </div>
            </main>

            <LibraryFooter />
        </div>
    )
}
