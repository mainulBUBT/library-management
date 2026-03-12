'use client'

import { useEffect, useState } from 'react'
import { useRouter } from 'next/navigation'
import { useAuthStore } from '@/stores/authStore'
import LibraryHeader from '@/components/layout/LibraryHeader'
import UserDashboardSidebar from '@/components/layout/UserDashboardSidebar'
import LibraryFooter from '@/components/layout/LibraryFooter'
import { Loader2, Menu } from 'lucide-react'

export default function DashboardLayout({
  children,
}: {
  children: React.ReactNode
}) {
  const router = useRouter()
  const { isAuthenticated, loadFromStorage } = useAuthStore()
  const [sidebarOpen, setSidebarOpen] = useState(false)
  const [isLoaded, setIsLoaded] = useState(false)

  useEffect(() => {
    loadFromStorage()
    setIsLoaded(true)
  }, [loadFromStorage])

  useEffect(() => {
    if (isLoaded && !isAuthenticated) {
      router.push('/login?redirect=/dashboard')
    }
  }, [isLoaded, isAuthenticated, router])

  if (!isLoaded) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-slate-50">
        <Loader2 className="w-8 h-8 animate-spin text-indigo-600" />
      </div>
    )
  }

  if (!isAuthenticated) {
    return null
  }

  return (
    <div className="min-h-screen flex flex-col bg-[var(--color-background)]">
      {/* Top Header - Same as catalog */}
      <LibraryHeader />

      <div className="flex flex-1 relative">
        {/* Sidebar Navigation */}
        <UserDashboardSidebar isOpen={sidebarOpen} onClose={() => setSidebarOpen(false)} />

        {/* Mobile Menu Toggle */}
        <button
          onClick={() => setSidebarOpen(true)}
          className="lg:hidden fixed bottom-6 right-6 z-40 w-14 h-14 bg-indigo-600 text-white rounded-full shadow-lg flex items-center justify-center hover:bg-indigo-700 transition-colors"
          aria-label="Open menu"
        >
          <Menu className="w-6 h-6" />
        </button>

        {/* Main Content Area */}
        <div className="flex-1 lg:ml-72">
          {children}
          <LibraryFooter />
        </div>
      </div>
    </div>
  )
}
