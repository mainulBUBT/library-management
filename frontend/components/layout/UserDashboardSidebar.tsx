'use client'

import { useState } from 'react'
import Link from 'next/link'
import { usePathname, useRouter } from 'next/navigation'
import { useAuthStore } from '@/stores/authStore'
import {
  LayoutDashboard,
  Bookmark,
  BookOpen,
  DollarSign,
  User,
  Settings,
  LogOut,
  X,
  Menu,
  Home,
} from 'lucide-react'

interface SidebarProps {
  isOpen: boolean
  onClose: () => void
}

export default function UserDashboardSidebar({ isOpen, onClose }: SidebarProps) {
  const pathname = usePathname()
  const router = useRouter()
  const { user, logout } = useAuthStore()

  const handleLogout = () => {
    logout()
    router.push('/login')
  }

  const navigation = [
    {
      section: 'Overview',
      items: [
        {
          name: 'Dashboard',
          href: '/dashboard?tab=reservations',
          icon: LayoutDashboard,
          active: pathname === '/dashboard',
        },
        {
          name: 'Browse Catalog',
          href: '/',
          icon: Home,
          active: pathname === '/',
        },
      ],
    },
    {
      section: 'My Library',
      items: [
        {
          name: 'Reservations',
          href: '/dashboard?tab=reservations',
          icon: Bookmark,
          active: pathname === '/dashboard',
        },
        {
          name: 'Loans',
          href: '/dashboard?tab=loans',
          icon: BookOpen,
          active: pathname === '/dashboard',
        },
        {
          name: 'Fines',
          href: '/dashboard?tab=fines',
          icon: DollarSign,
          active: pathname === '/dashboard',
        },
      ],
    },
    {
      section: 'Account',
      items: [
        {
          name: 'Profile',
          href: '/dashboard?tab=profile',
          icon: User,
          active: pathname === '/dashboard',
        },
        {
          name: 'Settings',
          href: '/dashboard?tab=profile',
          icon: Settings,
          active: pathname === '/dashboard',
        },
      ],
    },
  ]

  return (
    <>
      {/* Mobile Overlay */}
      {isOpen && (
        <div
          className="fixed inset-0 bg-black/50 z-40 lg:hidden"
          onClick={onClose}
        />
      )}

      {/* Sidebar */}
      <aside
        className={`
          fixed lg:static inset-y-0 left-0 z-50
          w-72 bg-white border-r border-slate-200
          transform transition-transform duration-300 ease-in-out
          ${isOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'}
        `}
      >
        <div className="flex flex-col h-full">
          {/* Header */}
          <div className="flex items-center justify-between px-6 py-4 border-b border-slate-200">
            <Link href="/" className="flex items-center gap-2" onClick={onClose}>
              <div className="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white">
                <BookOpen className="w-5 h-5" />
              </div>
              <span className="text-xl font-bold text-slate-900">Library</span>
            </Link>
            <button
              onClick={onClose}
              className="lg:hidden p-2 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100"
            >
              <X className="w-5 h-5" />
            </button>
          </div>

          {/* Scrollable Content */}
          <div className="flex-1 overflow-y-auto py-4 px-4">
            {navigation.map((group) => (
              <div key={group.section} className="mb-6">
                <h3 className="px-3 mb-2 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                  {group.section}
                </h3>
                <ul className="space-y-1">
                  {group.items.map((item) => {
                    const Icon = item.icon
                    // For dashboard tab detection, we'd need to parse search params
                    // Using a simpler approach for now
                    const isTabActive = item.href.includes('?tab=')
                      ? pathname === '/dashboard' && typeof window !== 'undefined' &&
                        window.location.search === item.href.split('/dashboard')[1]
                      : item.active

                    return (
                      <li key={item.name}>
                        <Link
                          href={item.href}
                          onClick={onClose}
                          className={`
                            flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                            transition-colors duration-200
                            ${isTabActive
                              ? 'bg-indigo-50 text-indigo-700'
                              : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'
                            }
                          `}
                        >
                          <Icon className="w-4 h-4 flex-shrink-0" />
                          {item.name}
                        </Link>
                      </li>
                    )
                  })}
                </ul>
              </div>
            ))}
          </div>

          {/* User Section */}
          <div className="p-4 border-t border-slate-200">
            <div className="flex items-center gap-3 mb-3">
              <div className="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                <span className="text-indigo-600 font-semibold text-sm">
                  {user?.name?.[0]?.toUpperCase() || 'U'}
                </span>
              </div>
              <div className="flex-1 min-w-0">
                <p className="text-sm font-medium text-slate-900 truncate">
                  {user?.name}
                </p>
                <p className="text-xs text-slate-500 capitalize truncate">
                  {user?.role || 'Member'}
                </p>
              </div>
            </div>
            <button
              onClick={handleLogout}
              className="w-full flex items-center gap-3 px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors"
            >
              <LogOut className="w-4 h-4" />
              Sign Out
            </button>
          </div>
        </div>
      </aside>
    </>
  )
}
