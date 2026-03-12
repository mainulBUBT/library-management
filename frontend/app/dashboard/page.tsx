'use client'

import { useState } from 'react'
import Link from 'next/link'
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query'
import api from '@/lib/api'
import { useAuthStore } from '@/stores/authStore'
import {
  Bookmark,
  BookOpen,
  Calendar,
  Clock,
  User,
  Check,
  Loader2,
  AlertCircle,
  Edit,
  Key,
} from 'lucide-react'

import LoanCard from '@/components/dashboard/LoanCard'
import ReservationCard from '@/components/dashboard/ReservationCard'
import FinesList from '@/components/dashboard/FinesList'
import ProfileEditForm from '@/components/dashboard/ProfileEditForm'
import PasswordChangeForm from '@/components/dashboard/PasswordChangeForm'

type ProfileTabType = 'view' | 'edit' | 'password'

export default function DashboardPage() {
  const queryClient = useQueryClient()
  const isAuthenticated = useAuthStore((state) => state.isAuthenticated)
  const user = useAuthStore((state) => state.user)
  const [profileTab, setProfileTab] = useState<ProfileTabType>('view')
  const [message, setMessage] = useState<{ type: 'success' | 'error'; text: string } | null>(null)

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

  // Fetch user fines
  const { data: finesData } = useQuery({
    queryKey: ['my-fines'],
    queryFn: async () => {
      const response = await api.get('/my-fines')
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

  const reservations = reservationsData?.data || []
  const loans = loansData?.data || []
  const fines = finesData?.data || []
  const activeLoans = loans.filter((l: any) => l.status === 'active')
  const pendingReservations = reservations.filter((r: any) => r.status === 'pending' || r.status === 'ready')
  const unpaidFines = fines.filter((f: any) => f.balance > 0)

  // Filter out loans/reservations without required data
  const validLoans = loans.filter((l: any) => l.copy?.resource)
  const validReservations = reservations.filter((r: any) => r.resource)

  return (
    <div className="min-h-screen">
      {/* Page Header */}
      <div className="bg-gradient-to-r from-indigo-50 to-white border-b border-slate-200">
        <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
          <h1 className="text-3xl sm:text-4xl font-bold text-slate-900">
            My Library
          </h1>
          <p className="text-slate-600 mt-2">
            Manage your loans, reservations, and account
          </p>
        </div>
      </div>

      <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
        {/* Alert Message */}
        {message && (
          <div className={`mb-6 p-4 rounded-xl flex items-start gap-3 shadow-sm ${
            message.type === 'success'
              ? 'bg-emerald-50 text-emerald-800 border border-emerald-200'
              : 'bg-red-50 text-red-800 border border-red-200'
          }`}>
            {message.type === 'success' ? (
              <Check className="w-5 h-5 flex-shrink-0 mt-0.5" />
            ) : (
              <AlertCircle className="w-5 h-5 flex-shrink-0 mt-0.5" />
            )}
            <p className="text-sm font-medium">{message.text}</p>
          </div>
        )}

        {/* Stats Overview */}
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
          <div className="group relative overflow-hidden bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl p-5 text-white hover:shadow-lg transition-all">
            <div className="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-300" />
            <div className="relative">
              <div className="flex items-center justify-between mb-3">
                <div className="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                  <BookOpen className="w-6 h-6" />
                </div>
              </div>
              <p className="text-3xl font-bold">{activeLoans.length}</p>
              <p className="text-emerald-100 text-sm font-medium">Active Loans</p>
            </div>
          </div>

          <div className="group relative overflow-hidden bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-5 text-white hover:shadow-lg transition-all">
            <div className="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-300" />
            <div className="relative">
              <div className="flex items-center justify-between mb-3">
                <div className="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                  <Bookmark className="w-6 h-6" />
                </div>
              </div>
              <p className="text-3xl font-bold">{pendingReservations.length}</p>
              <p className="text-blue-100 text-sm font-medium">Reservations</p>
            </div>
          </div>

          <div className="group relative overflow-hidden bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl p-5 text-white hover:shadow-lg transition-all">
            <div className="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-300" />
            <div className="relative">
              <div className="flex items-center justify-between mb-3">
                <div className="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                  <AlertCircle className="w-6 h-6" />
                </div>
              </div>
              <p className="text-3xl font-bold">{unpaidFines.length}</p>
              <p className="text-amber-100 text-sm font-medium">Unpaid Fines</p>
            </div>
          </div>

          <div className="group relative overflow-hidden bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl p-5 text-white hover:shadow-lg transition-all">
            <div className="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-300" />
            <div className="relative">
              <div className="flex items-center justify-between mb-3">
                <div className="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                  <User className="w-6 h-6" />
                </div>
              </div>
              <p className="text-sm font-bold capitalize">{user?.member_type || 'Standard'}</p>
              <p className="text-indigo-100 text-sm font-medium">Member Type</p>
            </div>
          </div>
        </div>

        {/* Main Content Grid */}
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
          {/* Current Loans Section */}
          <section className="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div className="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
              <h2 className="text-lg font-bold text-slate-900">Current Loans</h2>
              <Link href="/catalog" className="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                Browse More →
              </Link>
            </div>
            <div className="p-4">
              {loansLoading ? (
                <div className="flex items-center justify-center py-8">
                  <Loader2 className="w-6 h-6 animate-spin text-indigo-600" />
                </div>
              ) : validLoans.length === 0 ? (
                <div className="text-center py-8">
                  <div className="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <BookOpen className="w-6 h-6 text-slate-300" />
                  </div>
                  <p className="text-sm text-slate-500">No loans yet</p>
                </div>
              ) : (
                <div className="space-y-3">
                  {validLoans.slice(0, 3).map((loan: any) => (
                    <LoanCard
                      key={loan.id}
                      loan={loan}
                      onRenew={handleRenewLoan}
                      isRenewing={renewMutation.isPending}
                    />
                  ))}
                  {validLoans.length > 3 && (
                    <p className="text-xs text-slate-500 text-center pt-2">
                      And {validLoans.length - 3} more loan{validLoans.length - 3 > 1 ? 's' : ''}
                    </p>
                  )}
                </div>
              )}
            </div>
          </section>

          {/* Active Reservations Section */}
          <section className="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div className="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
              <h2 className="text-lg font-bold text-slate-900">Reservations</h2>
              <Link href="/catalog" className="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                Browse More →
              </Link>
            </div>
            <div className="p-4">
              {reservationsLoading ? (
                <div className="flex items-center justify-center py-8">
                  <Loader2 className="w-6 h-6 animate-spin text-indigo-600" />
                </div>
              ) : validReservations.length === 0 ? (
                <div className="text-center py-8">
                  <div className="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <Bookmark className="w-6 h-6 text-slate-300" />
                  </div>
                  <p className="text-sm text-slate-500">No reservations yet</p>
                </div>
              ) : (
                <div className="space-y-3">
                  {validReservations.slice(0, 3).map((reservation: any) => (
                    <ReservationCard
                      key={reservation.id}
                      reservation={reservation}
                      onCancel={handleCancelReservation}
                      isCancelling={cancelMutation.isPending}
                    />
                  ))}
                  {validReservations.length > 3 && (
                    <p className="text-xs text-slate-500 text-center pt-2">
                      And {validReservations.length - 3} more reservation{validReservations.length - 3 > 1 ? 's' : ''}
                    </p>
                  )}
                </div>
              )}
            </div>
          </section>

          {/* Fines Section - Full Width */}
          <section className="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div className="px-6 py-4 border-b border-slate-200">
              <h2 className="text-lg font-bold text-slate-900">Fines</h2>
            </div>
            <div className="p-4">
              <FinesList />
            </div>
          </section>
        </div>

        {/* Profile Section */}
        <section className="mt-6 bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
          <div className="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <h2 className="text-lg font-bold text-slate-900">Profile</h2>
          </div>

          {/* Profile Sub-Tabs */}
          <div className="flex gap-2 px-6 pt-4 border-b border-slate-200 overflow-x-auto">
            {[
              { key: 'view', label: 'View Profile', icon: User },
              { key: 'edit', label: 'Edit Profile', icon: Edit },
              { key: 'password', label: 'Change Password', icon: Key },
            ].map((tab) => {
              const Icon = tab.icon
              return (
                <button
                  key={tab.key}
                  onClick={() => setProfileTab(tab.key as ProfileTabType)}
                  className={`
                    flex items-center gap-2 px-4 py-3 text-sm font-semibold border-b-2 whitespace-nowrap transition-colors
                    ${profileTab === tab.key
                      ? 'border-indigo-600 text-indigo-600'
                      : 'border-transparent text-slate-500 hover:text-slate-700'
                    }
                  `}
                >
                  <Icon className="w-4 h-4" />
                  {tab.label}
                </button>
              )
            })}
          </div>

          <div className="p-6">
            {/* View Profile */}
            {profileTab === 'view' && (
              <div className="bg-slate-50 rounded-xl p-6">
                <div className="flex items-center gap-4 mb-6">
                  <div className="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center">
                    <User className="w-8 h-8 text-indigo-600" />
                  </div>
                  <div>
                    <h3 className="text-xl font-bold text-slate-900">{user?.name}</h3>
                    <p className="text-slate-500">{user?.email}</p>
                  </div>
                </div>

                <div className="grid gap-1 sm:grid-cols-2">
                  {user?.phone && (
                    <div className="flex items-center gap-3 py-3 border-b border-slate-200">
                      <span className="text-sm font-semibold text-slate-500">Phone</span>
                      <span className="text-sm text-slate-900">{user.phone}</span>
                    </div>
                  )}
                  {user?.address && (
                    <div className="flex items-center gap-3 py-3 border-b border-slate-200">
                      <span className="text-sm font-semibold text-slate-500">Address</span>
                      <span className="text-sm text-slate-900">{user.address}</span>
                    </div>
                  )}
                  <div className="flex items-center gap-3 py-3 border-b border-slate-200">
                    <span className="text-sm font-semibold text-slate-500">Member Type</span>
                    <span className="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-100 text-indigo-700 capitalize">
                      {user?.member_type || 'Standard'}
                    </span>
                  </div>
                  {user?.created_at && (
                    <div className="flex items-center gap-3 py-3">
                      <span className="text-sm font-semibold text-slate-500">Member Since</span>
                      <span className="text-sm text-slate-900">{new Date(user.created_at).toLocaleDateString()}</span>
                    </div>
                  )}
                </div>
              </div>
            )}

            {/* Edit Profile */}
            {profileTab === 'edit' && (
              <div className="bg-slate-50 rounded-xl p-6">
                <h3 className="text-lg font-bold text-slate-900 mb-4">Edit Profile</h3>
                <ProfileEditForm />
              </div>
            )}

            {/* Change Password */}
            {profileTab === 'password' && (
              <div className="bg-slate-50 rounded-xl p-6">
                <h3 className="text-lg font-bold text-slate-900 mb-4">Change Password</h3>
                <PasswordChangeForm />
              </div>
            )}
          </div>
        </section>
      </div>
    </div>
  )
}
