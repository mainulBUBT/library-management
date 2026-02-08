'use client'

import { useState, useEffect } from 'react'
import { useRouter, useSearchParams } from 'next/navigation'
import Link from 'next/link'
import Image from 'next/image'
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query'
import api from '@/lib/api'
import { useAuthStore } from '@/stores/authStore'
import LibraryHeader from '@/components/layout/LibraryHeader'
import LibraryFooter from '@/components/layout/LibraryFooter'
import {
  Bookmark,
  BookOpen,
  Calendar,
  Clock,
  User,
  Check,
  X,
  Loader2,
  AlertCircle,
  LogOut,
} from 'lucide-react'

type TabType = 'reservations' | 'loans' | 'profile'

export default function DashboardPage() {
  const router = useRouter()
  const searchParams = useSearchParams()
  const queryClient = useQueryClient()
  const isAuthenticated = useAuthStore((state) => state.isAuthenticated)
  const user = useAuthStore((state) => state.user)
  const logout = useAuthStore((state) => state.logout)

  const [activeTab, setActiveTab] = useState<TabType>(
    (searchParams.get('tab') as TabType) || 'reservations'
  )
  const [message, setMessage] = useState<{ type: 'success' | 'error'; text: string } | null>(null)

  // Redirect if not authenticated
  useEffect(() => {
    if (!isAuthenticated) {
      router.push('/login?redirect=/dashboard')
    }
  }, [isAuthenticated, router])

  // Fetch user reservations
  const { data: reservationsData, isLoading: reservationsLoading, refetch: refetchReservations } = useQuery({
    queryKey: ['my-reservations'],
    queryFn: async () => {
      const response = await api.get('/my-reservations')
      return response.data
    },
    enabled: isAuthenticated,
  })

  // Fetch user loans
  const { data: loansData, isLoading: loansLoading, refetch: refetchLoans } = useQuery({
    queryKey: ['my-loans'],
    queryFn: async () => {
      const response = await api.get('/my-loans')
      return response.data
    },
    enabled: isAuthenticated,
  })

  // Cancel reservation mutation
  const cancelMutation = useMutation({
    mutationFn: async (id: string) => {
      const response = await api.delete(`/reservations/${id}`)
      return response.data
    },
    onSuccess: (data) => {
      refetchReservations()
      setMessage({ type: 'success', text: data.message || 'Reservation cancelled successfully.' })
      setTimeout(() => setMessage(null), 3000)
    },
    onError: (err: any) => {
      setMessage({ type: 'error', text: err.response?.data?.message || 'Failed to cancel reservation.' })
    },
  })

  // Renew loan mutation
  const renewMutation = useMutation({
    mutationFn: async (loanId: string) => {
      const response = await api.post(`/loans/${loanId}/renew`)
      return response.data
    },
    onSuccess: (data) => {
      refetchLoans()
      setMessage({ type: 'success', text: data.message || 'Loan renewed successfully.' })
      setTimeout(() => setMessage(null), 3000)
    },
    onError: (err: any) => {
      setMessage({ type: 'error', text: err.response?.data?.message || 'Failed to renew loan.' })
    },
  })

  const handleCancelReservation = (id: string) => {
    if (confirm('Are you sure you want to cancel this reservation?')) {
      cancelMutation.mutate(id)
    }
  }

  const handleRenewLoan = (loanId: string) => {
    renewMutation.mutate(loanId)
  }

  const handleLogout = () => {
    logout()
    router.push('/')
  }

  if (!isAuthenticated) {
    return (
      <div className="min-h-screen flex flex-col bg-[var(--color-background)]">
        <LibraryHeader />
        <main className="flex-1 flex items-center justify-center">
          <Loader2 className="w-8 h-8 animate-spin text-slate-400" />
        </main>
      </div>
    )
  }

  const reservations = reservationsData?.data || []
  const loans = loansData?.data || []
  const activeLoans = loans.filter((l: any) => l.status === 'active')
  const pendingReservations = reservations.filter((r: any) => r.status === 'pending' || r.status === 'ready')

  const getStatusBadge = (status: string) => {
    const statusMap: Record<string, { color: string; bg: string; label: string }> = {
      pending: { color: 'text-amber-700', bg: 'bg-amber-50', label: 'Pending' },
      ready: { color: 'text-blue-700', bg: 'bg-blue-50', label: 'Ready to Pick Up' },
      fulfilled: { color: 'text-emerald-700', bg: 'bg-emerald-50', label: 'Fulfilled' },
      cancelled: { color: 'text-slate-500', bg: 'bg-slate-100', label: 'Cancelled' },
      expired: { color: 'text-red-700', bg: 'bg-red-50', label: 'Expired' },
      active: { color: 'text-emerald-700', bg: 'bg-emerald-50', label: 'Active' },
      returned: { color: 'text-slate-500', bg: 'bg-slate-100', label: 'Returned' },
      overdue: { color: 'text-red-700', bg: 'bg-red-50', label: 'Overdue' },
    }
    const s = statusMap[status] || { color: 'text-slate-700', bg: 'bg-slate-100', label: status }
    return (
      <span className={`inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-medium ${s.bg} ${s.color}`}>
        {s.label}
      </span>
    )
  }

  const getImageUrl = (imagePath: string | null | undefined) => {
    if (!imagePath) return null
    if (imagePath.startsWith('http://') || imagePath.startsWith('https://')) {
      return imagePath
    }
    const baseUrl = process.env.NEXT_PUBLIC_API_URL?.replace('/api/v1', '') || 'http://localhost:8000'
    return `${baseUrl}/${imagePath.startsWith('/') ? imagePath.slice(1) : imagePath}`
  }

  return (
    <div className="min-h-screen flex flex-col bg-[var(--color-background)]">
      <LibraryHeader />

      <main className="flex-1">
        {/* Dashboard Header */}
        <div className="bg-white border-b border-slate-200">
          <div className="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 py-6">
            <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
              <div>
                <h1 className="text-2xl font-semibold text-slate-900">My Dashboard</h1>
                <p className="text-slate-500 text-sm">Welcome back, {user?.name}</p>
              </div>
              <button
                onClick={handleLogout}
                className="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-900 border border-slate-200 rounded-lg hover:bg-slate-50"
              >
                <LogOut className="w-4 h-4" />
                Sign Out
              </button>
            </div>
          </div>
        </div>

        <div className="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 py-8">
          {/* Alert Message */}
          {message && (
            <div className={`mb-6 p-4 rounded-lg flex items-start gap-3 ${
              message.type === 'success'
                ? 'bg-emerald-50 text-emerald-800 border border-emerald-200'
                : 'bg-red-50 text-red-800 border border-red-200'
            }`}>
              {message.type === 'success' ? (
                <Check className="w-5 h-5 flex-shrink-0 mt-0.5" />
              ) : (
                <AlertCircle className="w-5 h-5 flex-shrink-0 mt-0.5" />
              )}
              <p className="text-sm">{message.text}</p>
            </div>
          )}

          {/* Stats */}
          <div className="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
            <div className="bg-white border border-slate-200 rounded-lg p-4">
              <div className="flex items-center gap-3">
                <div className="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                  <BookOpen className="w-5 h-5 text-emerald-600" />
                </div>
                <div>
                  <p className="text-2xl font-semibold text-slate-900">{activeLoans.length}</p>
                  <p className="text-xs text-slate-500">Active Loans</p>
                </div>
              </div>
            </div>
            <div className="bg-white border border-slate-200 rounded-lg p-4">
              <div className="flex items-center gap-3">
                <div className="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                  <Bookmark className="w-5 h-5 text-blue-600" />
                </div>
                <div>
                  <p className="text-2xl font-semibold text-slate-900">{pendingReservations.length}</p>
                  <p className="text-xs text-slate-500">Reservations</p>
                </div>
              </div>
            </div>
          </div>

          {/* Tabs */}
          <div className="flex gap-2 border-b border-slate-200 mb-6">
            <button
              onClick={() => setActiveTab('reservations')}
              className={`px-4 py-3 text-sm font-medium border-b-2 transition-colors ${
                activeTab === 'reservations'
                  ? 'border-slate-900 text-slate-900'
                  : 'border-transparent text-slate-500 hover:text-slate-700'
              }`}
            >
              My Reservations
            </button>
            <button
              onClick={() => setActiveTab('loans')}
              className={`px-4 py-3 text-sm font-medium border-b-2 transition-colors ${
                activeTab === 'loans'
                  ? 'border-slate-900 text-slate-900'
                  : 'border-transparent text-slate-500 hover:text-slate-700'
              }`}
            >
              My Loans
            </button>
            <button
              onClick={() => setActiveTab('profile')}
              className={`px-4 py-3 text-sm font-medium border-b-2 transition-colors ${
                activeTab === 'profile'
                  ? 'border-slate-900 text-slate-900'
                  : 'border-transparent text-slate-500 hover:text-slate-700'
              }`}
            >
              Profile
            </button>
          </div>

          {/* Reservations Tab */}
          {activeTab === 'reservations' && (
            <div>
              {reservationsLoading ? (
                <div className="flex items-center justify-center py-12">
                  <Loader2 className="w-6 h-6 animate-spin text-slate-400" />
                </div>
              ) : reservations.length === 0 ? (
                <div className="text-center py-12 bg-white rounded-lg border border-slate-200">
                  <Bookmark className="w-12 h-12 text-slate-300 mx-auto mb-3" />
                  <h3 className="text-lg font-medium text-slate-900 mb-1">No Reservations Yet</h3>
                  <p className="text-slate-500 text-sm mb-4">
                    Browse our catalog and reserve books you'd like to borrow.
                  </p>
                  <Link
                    href="/catalog"
                    className="inline-flex items-center gap-2 px-4 py-2 bg-slate-900 text-white rounded-lg text-sm font-medium hover:bg-slate-800"
                  >
                    Browse Catalog
                  </Link>
                </div>
              ) : (
                <div className="grid gap-4">
                  {reservations.map((reservation: any) => (
                    <div
                      key={reservation.id}
                      className="bg-white border border-slate-200 rounded-lg p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4"
                    >
                      <div className="flex items-center gap-4">
                        {/* Book Cover Thumbnail */}
                        <div className="w-16 h-20 bg-slate-100 rounded flex-shrink-0 flex items-center justify-center overflow-hidden">
                          {reservation.resource?.cover_image ? (
                            <Image
                              src={getImageUrl(reservation.resource.cover_image) || ''}
                              alt={reservation.resource?.title}
                              width={64}
                              height={80}
                              className="object-cover w-full h-full"
                            />
                          ) : (
                            <BookOpen className="w-6 h-6 text-slate-300" />
                          )}
                        </div>

                        <div>
                          <Link
                            href={`/catalog/${reservation.resource?.id}`}
                            className="font-medium text-slate-900 hover:text-slate-700"
                          >
                            {reservation.resource?.title}
                          </Link>
                          {reservation.resource?.author && (
                            <p className="text-sm text-slate-500">{reservation.resource.author.name}</p>
                          )}
                          <div className="flex items-center gap-3 mt-2">
                            {getStatusBadge(reservation.status)}
                            {reservation.expires_at && (
                              <span className="flex items-center gap-1 text-xs text-slate-400">
                                <Clock className="w-3 h-3" />
                                Expires: {new Date(reservation.expires_at).toLocaleDateString()}
                              </span>
                            )}
                          </div>
                        </div>
                      </div>

                      {/* Actions */}
                      {(reservation.status === 'pending' || reservation.status === 'ready') && (
                        <div className="flex sm:flex-col gap-2">
                          <button
                            onClick={() => handleCancelReservation(reservation.id)}
                            disabled={cancelMutation.isPending}
                            className="px-3 py-1.5 text-xs font-medium text-red-600 hover:text-red-700 border border-red-200 rounded hover:bg-red-50 disabled:opacity-50"
                          >
                            Cancel
                          </button>
                        </div>
                      )}
                    </div>
                  ))}
                </div>
              )}
            </div>
          )}

          {/* Loans Tab */}
          {activeTab === 'loans' && (
            <div>
              {loansLoading ? (
                <div className="flex items-center justify-center py-12">
                  <Loader2 className="w-6 h-6 animate-spin text-slate-400" />
                </div>
              ) : loans.length === 0 ? (
                <div className="text-center py-12 bg-white rounded-lg border border-slate-200">
                  <BookOpen className="w-12 h-12 text-slate-300 mx-auto mb-3" />
                  <h3 className="text-lg font-medium text-slate-900 mb-1">No Loans Yet</h3>
                  <p className="text-slate-500 text-sm mb-4">
                    Browse our catalog and borrow books to read.
                  </p>
                  <Link
                    href="/catalog"
                    className="inline-flex items-center gap-2 px-4 py-2 bg-slate-900 text-white rounded-lg text-sm font-medium hover:bg-slate-800"
                  >
                    Browse Catalog
                  </Link>
                </div>
              ) : (
                <div className="grid gap-4">
                  {loans.map((loan: any) => (
                    <div
                      key={loan.id}
                      className="bg-white border border-slate-200 rounded-lg p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4"
                    >
                      <div className="flex items-center gap-4">
                        {/* Book Cover Thumbnail */}
                        <div className="w-16 h-20 bg-slate-100 rounded flex-shrink-0 flex items-center justify-center overflow-hidden">
                          {loan.copy?.resource?.cover_image ? (
                            <Image
                              src={getImageUrl(loan.copy.resource.cover_image) || ''}
                              alt={loan.copy?.resource?.title}
                              width={64}
                              height={80}
                              className="object-cover w-full h-full"
                            />
                          ) : (
                            <BookOpen className="w-6 h-6 text-slate-300" />
                          )}
                        </div>

                        <div>
                          <Link
                            href={`/catalog/${loan.copy?.resource?.id}`}
                            className="font-medium text-slate-900 hover:text-slate-700"
                          >
                            {loan.copy?.resource?.title}
                          </Link>
                          {loan.copy?.resource?.author && (
                            <p className="text-sm text-slate-500">{loan.copy.resource.author.name}</p>
                          )}
                          <div className="flex items-center gap-3 mt-2">
                            {getStatusBadge(loan.status)}
                            {loan.due_date && (
                              <span className="flex items-center gap-1 text-xs text-slate-400">
                                <Calendar className="w-3 h-3" />
                                Due: {new Date(loan.due_date).toLocaleDateString()}
                              </span>
                            )}
                          </div>
                        </div>
                      </div>

                      {/* Actions */}
                      {loan.status === 'active' && (
                        <button
                          onClick={() => handleRenewLoan(loan.id)}
                          disabled={renewMutation.isPending}
                          className="px-3 py-1.5 text-xs font-medium text-slate-600 hover:text-slate-900 border border-slate-300 rounded hover:bg-slate-50 disabled:opacity-50"
                        >
                          Renew
                        </button>
                      )}
                    </div>
                  ))}
                </div>
              )}
            </div>
          )}

          {/* Profile Tab */}
          {activeTab === 'profile' && (
            <div className="max-w-2xl">
              <div className="bg-white border border-slate-200 rounded-lg p-6">
                <div className="flex items-center gap-4 mb-6">
                  <div className="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center">
                    <User className="w-8 h-8 text-slate-400" />
                  </div>
                  <div>
                    <h2 className="text-lg font-semibold text-slate-900">{user?.name}</h2>
                    <p className="text-slate-500 text-sm">{user?.email}</p>
                  </div>
                </div>

                <div className="grid gap-4 text-sm">
                  {user?.phone && (
                    <div className="flex items-center gap-3 py-3 border-b border-slate-100">
                      <span className="text-slate-500 w-28">Phone</span>
                      <span className="text-slate-900">{user.phone}</span>
                    </div>
                  )}
                  {user?.address && (
                    <div className="flex items-center gap-3 py-3 border-b border-slate-100">
                      <span className="text-slate-500 w-28">Address</span>
                      <span className="text-slate-900">{user.address}</span>
                    </div>
                  )}
                  <div className="flex items-center gap-3 py-3 border-b border-slate-100">
                    <span className="text-slate-500 w-28">Member Type</span>
                    <span className="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700 capitalize">
                      {user?.member_type}
                    </span>
                  </div>
                  {user?.created_at && (
                    <div className="flex items-center gap-3 py-3">
                      <span className="text-slate-500 w-28">Member Since</span>
                      <span className="text-slate-900">{new Date(user.created_at).toLocaleDateString()}</span>
                    </div>
                  )}
                </div>
              </div>
            </div>
          )}
        </div>
      </main>

      <LibraryFooter />
    </div>
  )
}
