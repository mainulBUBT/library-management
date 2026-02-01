'use client'

import { useQuery } from '@tanstack/react-query'
import api from '@/lib/api'
import { formatCurrency, formatDate } from '@/lib/utils'
import Link from 'next/link'

export default function DashboardPage() {
  // Fetch user's loans
  const { data: loansData } = useQuery({
    queryKey: ['my-loans'],
    queryFn: async () => {
      const response = await api.get('/my-loans')
      return response.data
    },
  })

  // Fetch user's reservations
  const { data: reservationsData } = useQuery({
    queryKey: ['my-reservations'],
    queryFn: async () => {
      const response = await api.get('/my-reservations')
      return response.data
    },
  })

  // Fetch user's fines
  const { data: finesData } = useQuery({
    queryKey: ['my-fines'],
    queryFn: async () => {
      const response = await api.get('/my-fines')
      return response.data
    },
  })

  const activeLoans = loansData?.data?.filter((loan: any) => loan.status === 'active') || []
  const overdueLoans = activeLoans.filter((loan: any) => loan.is_overdue)
  const pendingReservations = reservationsData?.data?.filter((r: any) => r.status === 'pending') || []
  const unpaidFines = finesData?.data?.filter((fine: any) => fine.balance > 0) || []
  const totalFines = unpaidFines.reduce((sum: number, fine: any) => sum + fine.balance, 0)

  return (
    <div>
      <h1 className="text-2xl font-bold text-gray-900 mb-6">Dashboard</h1>

      {/* Stats Cards */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div className="bg-white rounded-lg shadow-sm p-6">
          <div className="flex items-center justify-between">
            <div>
              <p className="text-sm font-medium text-gray-500">Active Loans</p>
              <p className="text-2xl font-bold text-gray-900 mt-1">{activeLoans.length}</p>
            </div>
            <div className="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center">
              <svg className="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253m0-13C6.832 5.477 5.754 18 4.5 1.253v13" />
              </svg>
            </div>
          </div>
        </div>

        <div className="bg-white rounded-lg shadow-sm p-6">
          <div className="flex items-center justify-between">
            <div>
              <p className="text-sm font-medium text-gray-500">Overdue</p>
              <p className="text-2xl font-bold text-red-600 mt-1">{overdueLoans.length}</p>
            </div>
            <div className="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
              <svg className="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
            </div>
          </div>
        </div>

        <div className="bg-white rounded-lg shadow-sm p-6">
          <div className="flex items-center justify-between">
            <div>
              <p className="text-sm font-medium text-gray-500">Reservations</p>
              <p className="text-2xl font-bold text-gray-900 mt-1">{pendingReservations.length}</p>
            </div>
            <div className="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center">
              <svg className="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
              </svg>
            </div>
          </div>
        </div>

        <div className="bg-white rounded-lg shadow-sm p-6">
          <div className="flex items-center justify-between">
            <div>
              <p className="text-sm font-medium text-gray-500">Outstanding Fines</p>
              <p className="text-2xl font-bold text-gray-900 mt-1">{formatCurrency(totalFines)}</p>
            </div>
            <div className="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
              <svg className="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
        </div>
      </div>

      {/* Active Loans Section */}
      <div className="bg-white rounded-lg shadow-sm mb-6">
        <div className="p-6 border-b border-gray-100">
          <div className="flex items-center justify-between">
            <h2 className="text-lg font-semibold text-gray-900">Current Loans</h2>
            <Link href="/dashboard/loans" className="text-indigo-600 hover:text-indigo-700 text-sm font-medium">
              View all
            </Link>
          </div>
        </div>
        <div className="p-6">
          {activeLoans.length === 0 ? (
            <p className="text-gray-500 text-center py-8">You have no active loans.</p>
          ) : (
            <div className="space-y-4">
              {activeLoans.slice(0, 3).map((loan: any) => (
                <div key={loan.id} className="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                  <div className="flex items-center gap-4">
                    <div className="w-12 h-16 bg-gray-200 rounded flex items-center justify-center">
                      <svg className="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253m0-13C6.832 5.477 5.754 18 4.5 1.253v13" />
                      </svg>
                    </div>
                    <div>
                      <p className="font-medium text-gray-900">{loan.copy?.resource?.title}</p>
                      <p className="text-sm text-gray-500">Due: {formatDate(loan.due_date)}</p>
                    </div>
                  </div>
                  <span className={`px-2 py-1 text-xs font-medium rounded-full ${
                    loan.is_overdue
                      ? 'bg-red-100 text-red-800'
                      : loan.days_until_due && loan.days_until_due <= 3
                      ? 'bg-yellow-100 text-yellow-800'
                      : 'bg-green-100 text-green-800'
                  }`}>
                    {loan.is_overdue ? 'Overdue' : `${loan.days_until_due} days left`}
                  </span>
                </div>
              ))}
            </div>
          )}
        </div>
      </div>

      {/* Pending Reservations Section */}
      <div className="bg-white rounded-lg shadow-sm">
        <div className="p-6 border-b border-gray-100">
          <div className="flex items-center justify-between">
            <h2 className="text-lg font-semibold text-gray-900">Pending Reservations</h2>
            <Link href="/dashboard/reservations" className="text-indigo-600 hover:text-indigo-700 text-sm font-medium">
              View all
            </Link>
          </div>
        </div>
        <div className="p-6">
          {pendingReservations.length === 0 ? (
            <p className="text-gray-500 text-center py-8">You have no pending reservations.</p>
          ) : (
            <div className="space-y-4">
              {pendingReservations.slice(0, 3).map((reservation: any) => (
                <div key={reservation.id} className="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                  <div className="flex items-center gap-4">
                    <div className="w-12 h-16 bg-gray-200 rounded flex items-center justify-center">
                      <svg className="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253m0-13C6.832 5.477 5.754 18 4.5 1.253v13" />
                      </svg>
                    </div>
                    <div>
                      <p className="font-medium text-gray-900">{reservation.resource?.title}</p>
                      <p className="text-sm text-gray-500">Expires: {formatDate(reservation.expires_at)}</p>
                    </div>
                  </div>
                  <span className="px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800">
                    Pending
                  </span>
                </div>
              ))}
            </div>
          )}
        </div>
      </div>
    </div>
  )
}
