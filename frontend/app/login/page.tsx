'use client'

import { useState } from 'react'
import { useRouter } from 'next/navigation'
import Link from 'next/link'
import api from '@/lib/api'
import { useAuthStore } from '@/stores/authStore'

export default function LoginPage() {
  const router = useRouter()
  const setAuth = useAuthStore((state) => state.setAuth)

  const [formData, setFormData] = useState({
    email: '',
    password: '',
  })
  const [error, setError] = useState('')
  const [loading, setLoading] = useState(false)

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setError('')
    setLoading(true)

    try {
      const response = await api.post('/login', formData)
      const { user, token } = response.data

      setAuth(user, token)
      router.push('/dashboard')
    } catch (err: any) {
      setError(err.response?.data?.message || 'Login failed. Please try again.')
    } finally {
      setLoading(false)
    }
  }

  return (
    <div className="flex min-h-screen items-center justify-center bg-gray-100 px-4">
      <div className="w-full max-w-md">
        {/* Card */}
        <div className="bg-white rounded-lg shadow-md overflow-hidden">
          <div className="p-6 sm:p-8">
            {/* Logo & Header */}
            <div className="mb-6">
              <div className="flex items-center justify-center mb-4">
                <svg className="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253m0-13C6.832 5.477 5.754 18 4.5 1.253v13" />
                </svg>
              </div>
              <h1 className="text-2xl font-bold text-gray-800 text-center">Library Management</h1>
              <p className="text-gray-500 text-sm text-center mt-2">Please enter your user information.</p>
            </div>

            {/* Error Alert */}
            {error && (
              <div className="mb-4 p-3 bg-red-50 border border-red-200 rounded text-sm text-red-800">
                {error}
              </div>
            )}

            {/* Form */}
            <form onSubmit={handleSubmit}>
              {/* Email */}
              <div className="mb-3">
                <label htmlFor="email" className="inline-block mb-2 text-sm font-medium text-gray-700">
                  Username or email
                </label>
                <input
                  type="email"
                  id="email"
                  value={formData.email}
                  onChange={(e) => setFormData({ ...formData, email: e.target.value })}
                  className="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 outline-none"
                  placeholder="Email address here"
                  required
                />
              </div>

              {/* Password */}
              <div className="mb-5">
                <label htmlFor="password" className="inline-block mb-2 text-sm font-medium text-gray-700">
                  Password
                </label>
                <input
                  type="password"
                  id="password"
                  value={formData.password}
                  onChange={(e) => setFormData({ ...formData, password: e.target.value })}
                  className="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 outline-none"
                  placeholder="**************"
                  required
                />
              </div>

              {/* Remember Me */}
              <div className="flex justify-between items-center mb-4">
                <div className="flex items-center">
                  <input
                    type="checkbox"
                    id="remember"
                    className="w-4 h-4 text-indigo-600 bg-white border-gray-300 rounded focus:ring-2 focus:ring-indigo-600"
                  />
                  <label htmlFor="remember" className="inline-block ml-2 text-sm text-gray-600">
                    Remember me
                  </label>
                </div>
              </div>

              {/* Submit Button */}
              <div>
                <button
                  type="submit"
                  disabled={loading}
                  className="w-full bg-indigo-600 text-white border border-indigo-600 hover:bg-indigo-700 hover:border-indigo-700 active:bg-indigo-800 active:border-indigo-800 focus:outline-none focus:ring-4 focus:ring-indigo-300 rounded-md font-semibold py-2 px-4 transition disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  {loading ? 'Signing in...' : 'Sign in'}
                </button>

                <div className="flex justify-between mt-4">
                  <div>
                    <Link href="/register" className="text-indigo-600 hover:text-indigo-700 text-sm font-medium">
                      Create An Account
                    </Link>
                  </div>
                  <div>
                    <a href="#" className="text-indigo-600 hover:text-indigo-700 text-sm font-medium">
                      Forgot your password?
                    </a>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>

        {/* Admin Login Link */}
        <p className="text-gray-400 text-xs mt-4 text-center">
          <Link href="/admin/login" className="text-gray-400 hover:text-gray-600">
            Admin Login
          </Link>
        </p>
      </div>
    </div>
  )
}
