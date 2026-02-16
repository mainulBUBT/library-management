'use client'

import { useState, useEffect } from 'react'
import Link from 'next/link'
import { useRouter, usePathname } from 'next/navigation'
import { useAuthStore } from '@/stores/authStore'
import { BookOpen, User, LogOut, LayoutDashboard, Menu, X, Search } from 'lucide-react'

export default function LibraryHeader() {
    const router = useRouter()
    const pathname = usePathname()
    const { user, isAuthenticated, logout } = useAuthStore()
    const [isMenuOpen, setIsMenuOpen] = useState(false)
    const [isScrolled, setIsScrolled] = useState(false)

    // Handle scroll effect
    useEffect(() => {
        const handleScroll = () => {
            setIsScrolled(window.scrollY > 10)
        }
        window.addEventListener('scroll', handleScroll)
        return () => window.removeEventListener('scroll', handleScroll)
    }, [])

    const handleLogout = async () => {
        await logout()
        router.push('/login')
    }

    const isActive = (path: string) => pathname === path || pathname?.startsWith(`${path}/`)

    return (
        <header
            className={`sticky top-0 z-50 transition-all duration-200 border-b ${isScrolled
                ? 'bg-white/80 backdrop-blur-md border-slate-200 shadow-sm'
                : 'bg-white border-transparent'
                }`}
        >
            <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div className="flex h-16 items-center justify-between gap-4">

                    {/* Logo */}
                    <Link href="/" className="flex items-center gap-2 group">
                        <div className="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white transition-transform group-hover:scale-110">
                            <BookOpen className="w-5 h-5" />
                        </div>
                        <div>
                            <span className="block font-bold text-slate-900 leading-none">LMS</span>
                            <span className="text-[10px] text-slate-500 font-medium tracking-wider uppercase">Library</span>
                        </div>
                    </Link>

                    {/* Desktop Navigation */}
                    <nav className="hidden md:flex items-center gap-1">
                        {isAuthenticated && (
                            <Link
                                href="/dashboard"
                                className={`px-3 py-2 rounded-md text-sm font-medium transition-colors ${isActive('/dashboard')
                                    ? 'bg-indigo-50 text-indigo-700'
                                    : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'
                                    }`}
                            >
                                Dashboard
                            </Link>
                        )}
                    </nav>

                    {/* Search Bar (Placeholder) */}
                    <div className="hidden md:flex flex-1 max-w-md mx-4">
                        <div className="relative w-full group">
                            <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors" />
                            <input
                                type="text"
                                placeholder="Search books, authors, ISBN..."
                                className="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all"
                                onKeyDown={(e) => {
                                    if (e.key === 'Enter') {
                                        router.push(`/catalog?search=${(e.target as HTMLInputElement).value}`)
                                    }
                                }}
                            />
                        </div>
                    </div>

                    {/* User Menu */}
                    <div className="hidden md:flex items-center gap-3">
                        {isAuthenticated ? (
                            <div className="flex items-center gap-3 pl-3 border-l border-slate-200">
                                <div className="flex flex-col items-end">
                                    <span className="text-sm font-medium text-slate-900">{user?.name}</span>
                                    <span className="text-xs text-slate-500 capitalize">{user?.role}</span>
                                </div>
                                <button
                                    onClick={handleLogout}
                                    className="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                    title="Logout"
                                >
                                    <LogOut className="w-5 h-5" />
                                </button>
                            </div>
                        ) : (
                            <div className="flex items-center gap-2">
                                <Link
                                    href="/login"
                                    className="px-4 py-2 text-sm font-medium text-slate-700 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-colors"
                                >
                                    Sign In
                                </Link>
                                <Link
                                    href="/register"
                                    className="px-4 py-2 text-sm font-medium text-white bg-slate-900 hover:bg-slate-800 rounded-lg transition-colors shadow-sm"
                                >
                                    Get Started
                                </Link>
                            </div>
                        )}
                    </div>

                    {/* Mobile Menu Button */}
                    <button
                        className="md:hidden p-2 text-slate-600 hover:bg-slate-50 rounded-lg"
                        onClick={() => setIsMenuOpen(!isMenuOpen)}
                    >
                        {isMenuOpen ? <X className="w-6 h-6" /> : <Menu className="w-6 h-6" />}
                    </button>
                </div>
            </div>

            {/* Mobile Menu */}
            {isMenuOpen && (
                <div className="md:hidden border-t border-slate-200 bg-white shadow-lg absolute w-full left-0 top-16">
                    <div className="p-4 space-y-4">
                        <div className="relative">
                            <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
                            <input
                                type="text"
                                placeholder="Search..."
                                className="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm"
                            />
                        </div>

                        <nav className="space-y-1">
                            {isAuthenticated && (
                                <Link
                                    href="/dashboard"
                                    className="flex items-center gap-2 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 rounded-lg"
                                    onClick={() => setIsMenuOpen(false)}
                                >
                                    <LayoutDashboard className="w-4 h-4" />
                                    Dashboard
                                </Link>
                            )}
                        </nav>

                        <div className="pt-4 border-t border-slate-200">
                            {isAuthenticated ? (
                                <div className="space-y-3">
                                    <div className="flex items-center gap-3 px-3">
                                        <div className="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-700 font-medium">
                                            {user?.name?.[0] || 'U'}
                                        </div>
                                        <div>
                                            <div className="text-sm font-medium text-slate-900">{user?.name}</div>
                                            <div className="text-xs text-slate-500">{user?.email}</div>
                                        </div>
                                    </div>
                                    <button
                                        onClick={() => {
                                            handleLogout()
                                            setIsMenuOpen(false)
                                        }}
                                        className="w-full flex items-center gap-2 px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg"
                                    >
                                        <LogOut className="w-4 h-4" />
                                        Sign Out
                                    </button>
                                </div>
                            ) : (
                                <div className="grid grid-cols-2 gap-2">
                                    <Link
                                        href="/login"
                                        className="flex items-center justify-center px-4 py-2 text-sm font-medium text-slate-700 bg-slate-50 hover:bg-slate-100 rounded-lg"
                                        onClick={() => setIsMenuOpen(false)}
                                    >
                                        Sign In
                                    </Link>
                                    <Link
                                        href="/register"
                                        className="flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-slate-900 hover:bg-slate-800 rounded-lg"
                                        onClick={() => setIsMenuOpen(false)}
                                    >
                                        Register
                                    </Link>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            )}
        </header>
    )
}
