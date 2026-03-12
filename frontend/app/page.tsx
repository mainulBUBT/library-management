'use client'

import { useState, useEffect } from 'react'
import { useQuery } from '@tanstack/react-query'
import Link from 'next/link'
import api from '@/lib/api'
import { useAuthStore } from '@/stores/authStore'
import LibraryHeader from '@/components/layout/LibraryHeader'
import LibraryFooter from '@/components/layout/LibraryFooter'
import BookGrid from '@/components/catalog/BookGrid'
import Pagination from '@/components/catalog/Pagination'
import { Search, SlidersHorizontal, X, Loader2, BookOpen, ArrowRight } from 'lucide-react'

export default function Home() {
    const [searchTerm, setSearchTerm] = useState('')
    const [selectedCategory, setSelectedCategory] = useState('')
    const [showFilters, setShowFilters] = useState(false)
    const [currentPage, setCurrentPage] = useState(1)
    const perPage = 18

    // Reset page when filters change
    useEffect(() => {
        setCurrentPage(1)
    }, [searchTerm, selectedCategory])

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
        queryKey: ['catalog', searchTerm, selectedCategory, currentPage],
        queryFn: async () => {
            const params = new URLSearchParams()
            if (searchTerm) params.append('search', searchTerm)
            if (selectedCategory) params.append('category_id', selectedCategory)
            params.append('page', currentPage.toString())
            params.append('per_page', perPage.toString())

            const response = await api.get(`/catalog?${params.toString()}`)
            return response.data
        },
    })

    const categories = categoriesData?.categories || []
    const resources = resourcesData?.data || []
    const pagination = resourcesData?.meta || {}
    const { current_page = 1, last_page = 1, total = 0 } = pagination
    const isAuthenticated = useAuthStore((state) => state.isAuthenticated)

    return (
        <div className="min-h-screen flex flex-col bg-slate-50">
            <LibraryHeader />

            <main className="flex-1 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 w-full">
                {/* Hero Section */}
                <div className="bg-gradient-to-br from-indigo-600 via-indigo-700 to-indigo-800 rounded-2xl p-8 md:p-12 mb-8 text-white relative overflow-hidden shadow-xl">
                    <div className="absolute inset-0 opacity-10">
                        <div className="absolute inset-0" style={{
                            backgroundImage: `url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E")`
                        }} />
                    </div>
                    <div className="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2 blur-3xl" />
                    <div className="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/2 blur-3xl" />

                    <div className="relative z-10">
                        <div className="max-w-2xl">
                            <h1 className="text-3xl md:text-4xl font-bold mb-4">
                                Welcome to the Library
                            </h1>
                            <p className="text-lg text-indigo-100 mb-8">
                                Discover thousands of books, journals, and digital resources. Start exploring our collection today.
                            </p>
                            <div className="flex flex-wrap gap-4">
                                <Link
                                    href="/catalog"
                                    className="inline-flex items-center gap-2 bg-white text-indigo-700 px-6 py-3 rounded-lg font-semibold hover:bg-indigo-50 transition-all hover:shadow-lg hover:-translate-y-0.5"
                                >
                                    <BookOpen className="w-5 h-5" />
                                    Browse Catalog
                                </Link>
                                {!isAuthenticated && (
                                    <Link
                                        href="/register"
                                        className="inline-flex items-center gap-2 bg-indigo-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-indigo-400 transition-all border border-white/20 hover:shadow-lg hover:-translate-y-0.5"
                                    >
                                        Become a Member
                                        <ArrowRight className="w-4 h-4" />
                                    </Link>
                                )}
                            </div>
                        </div>
                    </div>
                </div>

                {/* Featured Categories */}
                {categories.length > 0 && (
                    <div className="mb-8">
                        <div className="flex items-center justify-between mb-4">
                            <h2 className="text-xl font-bold text-slate-900">Featured Categories</h2>
                            <Link
                                href="/catalog"
                                className="text-sm text-indigo-600 hover:text-indigo-700 font-medium flex items-center gap-1"
                            >
                                View all <ArrowRight className="w-4 h-4" />
                            </Link>
                        </div>
                        <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
                            {categories.slice(0, 4).map((cat: any) => (
                                <Link
                                    key={cat.id}
                                    href={`/catalog?category=${cat.id}`}
                                    className="group relative overflow-hidden rounded-xl bg-gradient-to-br from-indigo-50 to-indigo-100 p-5 hover:shadow-lg transition-all duration-300 hover:-translate-y-1 border border-indigo-100"
                                >
                                    <div className="relative z-10">
                                        <h3 className="font-semibold text-indigo-900 group-hover:text-indigo-700 transition-colors mb-1">
                                            {cat.name}
                                        </h3>
                                        <p className="text-sm text-indigo-600 flex items-center gap-1">
                                            Browse collection
                                            <ArrowRight className="w-3 h-3 group-hover:translate-x-1 transition-transform" />
                                        </p>
                                    </div>
                                    <div className="absolute -right-4 -bottom-4 w-20 h-20 bg-indigo-200/50 rounded-full group-hover:scale-150 transition-transform duration-300" />
                                </Link>
                            ))}
                        </div>
                    </div>
                )}
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

                        {/* Pagination */}
                        {last_page > 1 && (
                            <Pagination
                                currentPage={current_page}
                                totalPages={last_page}
                                total={total}
                                perPage={perPage}
                                onPageChange={setCurrentPage}
                            />
                        )}
                    </div>
                </div>
            </main>

            <LibraryFooter />
        </div>
    )
}
