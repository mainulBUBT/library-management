'use client'

import { useEffect, useState } from 'react'
import { useRouter, usePathname } from 'next/navigation'
import Link from 'next/link'
import { useAuthStore } from '@/stores/authStore'
import {
  Home,
  BookOpen,
  Bookmark,
  DollarSign,
  User,
  LogOut,
  Menu,
  X,
  Search
} from 'lucide-react'

export default function DashboardLayout({
  children,
}: {
  children: React.ReactNode
}) {
  const router = useRouter()
  const pathname = usePathname()
  const { isAuthenticated, user, logout, loadFromStorage } = useAuthStore()
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false)

  useEffect(() => {
    loadFromStorage()
    if (!isAuthenticated) {
      router.push('/login')
    }
  }, [isAuthenticated, router, loadFromStorage])

  const handleLogout = () => {
    logout()
    router.push('/')
  }

  const navItems = [
    { name: 'Overview', href: '/dashboard', icon: Home },
    { name: 'My Loans', href: '/dashboard/loans', icon: BookOpen },
    { name: 'Reservations', href: '/dashboard/reservations', icon: Bookmark },
    { name: 'Fines & Payments', href: '/dashboard/fines', icon: DollarSign },
    { name: 'Profile', href: '/dashboard/profile', icon: User },
  ]

  if (!isAuthenticated) {
    return null
  }

  return (
    <div className="min-h-screen bg-[var(--color-background)] flex flex-col">
      {/* Top Navbar */}
      <header className="library-header sticky top-0 z-50">
        <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <div className="flex h-20 items-center justify-between relative">
            {/* Logo */}
            <Link href="/dashboard" className="flex items-center gap-3">
              <div className="w-10 h-10 bg-[var(--library-accent)] rounded-lg flex items-center justify-center shadow-lg">
                <BookOpen className="w-6 h-6 text-[var(--library-primary-dark)]" />
              </div>
              <div className="hidden sm:block">
                <h1 className="text-lg font-bold text-white leading-tight">City Library</h1>
                <p className="text-xs text-[var(--library-accent)]">Member Dashboard</p>
              </div>
            </Link>

            {/* Right Nav Items */}
            <div className="flex items-center gap-4">
              <Link
                href="/catalog"
                className="hidden sm:flex items-center gap-2 px-4 py-2 text-white/80 hover:text-white hover:bg-white/10 rounded-lg transition-all font-medium"
              >
                <Search className="w-4 h-4" />
                Browse Catalog
              </Link>

              <div className="flex items-center gap-3">
                <div className="w-10 h-10 rounded-full bg-[var(--library-accent)] flex items-center justify-center shadow-md">
                  <span className="text-[var(--library-primary-dark)] font-bold">
                    {user?.name?.charAt(0).toUpperCase()}
                  </span>
                </div>
                <div className="hidden md:block">
                  <p className="text-sm font-semibold text-white">{user?.name}</p>
                  <p className="text-xs text-white/60">Member</p>
                </div>
                <button
                  onClick={handleLogout}
                  className="p-2 text-white/80 hover:text-white hover:bg-white/10 rounded-lg transition-colors hidden sm:block"
                  title="Sign out"
                >
                  <LogOut className="w-5 h-5" />
                </button>
              </div>

              {/* Mobile Menu Button */}
              <button
                onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
                className="lg:hidden p-2 text-white hover:bg-white/10 rounded-lg transition-colors"
                aria-label="Toggle menu"
              >
                {mobileMenuOpen ? <X className="w-6 h-6" /> : <Menu className="w-6 h-6" />}
              </button>
            </div>
          </div>
        </div>
      </header>

      {/* Mobile Navigation */}
      {mobileMenuOpen && (
        <div className="lg:hidden bg-white border-b border-[var(--library-border)]">
          <nav className="px-4 py-4 space-y-1">
            {navItems.map((item) => {
              const isActive = pathname === item.href
              return (
                <Link
                  key={item.name}
                  href={item.href}
                  onClick={() => setMobileMenuOpen(false)}
                  className={`flex items-center gap-3 px-4 py-3 rounded-lg font-medium transition-colors ${
                    isActive
                      ? 'bg-[var(--library-primary)] text-white'
                      : 'text-[var(--library-text-muted)] hover:bg-[var(--library-cream)] hover:text-[var(--library-primary)]'
                  }`}
                >
                  <item.icon className="w-5 h-5" />
                  {item.name}
                </Link>
              )
            })}
            <button
              onClick={() => {
                handleLogout()
                setMobileMenuOpen(false)
              }}
              className="w-full flex items-center gap-3 px-4 py-3 rounded-lg font-medium text-red-600 hover:bg-red-50 transition-colors"
            >
              <LogOut className="w-5 h-5" />
              Sign Out
            </button>
          </nav>
        </div>
      )}

      {/* Main Content Area */}
      <div className="flex-1">
        <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
          <div className="flex flex-col lg:flex-row gap-8">
            {/* Sidebar Navigation */}
            <aside className="hidden lg:block lg:w-64 flex-shrink-0">
              <nav className="card sticky top-24">
                <div className="mb-4 pb-4 border-b border-[var(--library-border)]">
                  <p className="text-xs font-semibold text-[var(--library-text-muted)] uppercase tracking-wider">
                    Menu
                  </p>
                </div>
                <ul className="space-y-1">
                  {navItems.map((item) => {
                    const isActive = pathname === item.href
                    return (
                      <li key={item.name}>
                        <Link
                          href={item.href}
                          className={`sidebar-link ${isActive ? 'active' : ''}`}
                        >
                          <item.icon className="w-5 h-5" />
                          {item.name}
                        </Link>
                      </li>
                    )
                  })}
                </ul>

                {/* Logout Button */}
                <div className="mt-6 pt-4 border-t border-[var(--library-border)]">
                  <button
                    onClick={handleLogout}
                    className="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg text-red-600 hover:bg-red-50 font-medium transition-colors"
                  >
                    <LogOut className="w-5 h-5" />
                    Sign Out
                  </button>
                </div>
              </nav>
            </aside>

            {/* Main Content */}
            <main className="flex-1 min-w-0">
              {children}
            </main>
          </div>
        </div>
      </div>
    </div>
  )
}
