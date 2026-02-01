'use client'

import { useState } from 'react'
import { useRouter } from 'next/navigation'
import Link from 'next/link'
import api from '@/lib/api'
import { useAuthStore } from '@/stores/authStore'

export default function RegisterPage() {
  const router = useRouter()
  const setAuth = useAuthStore((state) => state.setAuth)

  const [formData, setFormData] = useState({
    name: '',
    email: '',
    phone: '',
    address: '',
    member_type: '',
    password: '',
    password_confirmation: '',
  })
  const [error, setError] = useState('')
  const [loading, setLoading] = useState(false)

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setError('')

    if (formData.password !== formData.password_confirmation) {
      setError('Passwords do not match.')
      return
    }

    setLoading(true)

    try {
      const response = await api.post('/register', formData)
      const { user, token } = response.data

      setAuth(user, token)
      router.push('/dashboard')
    } catch (err: any) {
      setError(err.response?.data?.message || 'Registration failed. Please try again.')
    } finally {
      setLoading(false)
    }
  }

  return (
    <div className="flex min-h-screen items-center justify-center bg-gray-100 px-4 py-8">
      <div className="w-full max-w-md">
        <div className="bg-white rounded-lg shadow-md overflow-hidden">
          <div className="p-6 w-full sm:p-8">
            {/* Logo & Header */}
            <div className="mb-6">
              <div className="flex items-center justify-center mb-4">
                <svg className="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253m0-13C6.832 5.477 5.754 18 4.5 1.253v13" />
                </svg>
              </div>
              <h1 className="text-2xl font-bold text-gray-800 text-center">Create Account</h1>
              <p className="text-gray-500 text-sm text-center mt-2">Join our library today</p>
            </div>

            {/* Error Alert */}
            {error && (
              <div className="mb-4 p-3 bg-red-50 border border-red-200 rounded text-sm text-red-800">
                {error}
              </div>
            )}

            <form onSubmit={handleSubmit}>
              {/* Name */}
              <div className="mb-3">
                <label htmlFor="name" className="inline-block mb-2 text-sm font-medium text-gray-700">
                  Full Name
                </label>
                <input
                  type="text"
                  id="name"
                  value={formData.name}
                  onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                  className="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 outline-none"
                  placeholder="Your full name"
                  required
                />
              </div>

              {/* Email */}
              <div className="mb-3">
                <label htmlFor="email" className="inline-block mb-2 text-sm font-medium text-gray-700">
                  Email address
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

              {/* Phone */}
              <div className="mb-3">
                <label htmlFor="phone" className="inline-block mb-2 text-sm font-medium text-gray-700">
                  Phone (optional)
                </label>
                <input
                  type="tel"
                  id="phone"
                  value={formData.phone}
                  onChange={(e) => setFormData({ ...formData, phone: e.target.value })}
                  className="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 outline-none"
                  placeholder="Phone number"
                />
              </div>

              {/* Address */}
              <div className="mb-3">
                <label htmlFor="address" className="inline-block mb-2 text-sm font-medium text-gray-700">
                  Address (optional)
                </label>
                <input
                  type="text"
                  id="address"
                  value={formData.address}
                  onChange={(e) => setFormData({ ...formData, address: e.target.value })}
                  className="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 outline-none"
                  placeholder="Your address"
                />
              </div>

              {/* Member Type */}
              <div className="mb-3">
                <label htmlFor="member_type" className="inline-block mb-2 text-sm font-medium text-gray-700">
                  I am a
                </label>
                <select
                  id="member_type"
                  value={formData.member_type}
                  onChange={(e) => setFormData({ ...formData, member_type: e.target.value })}
                  className="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 outline-none"
                  required
                >
                  <option value="">Select member type</option>
                  <option value="student">Student</option>
                  <option value="teacher">Teacher</option>
                  <option value="staff">Staff</option>
                  <option value="public">Public Member</option>
                </select>
              </div>

              {/* Password */}
              <div className="mb-3">
                <label htmlFor="password" className="inline-block mb-2 text-sm font-medium text-gray-700">
                  Password
                </label>
                <input
                  type="password"
                  id="password"
                  value={formData.password}
                  onChange={(e) => setFormData({ ...formData, password: e.target.value })}
                  className="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 outline-none"
                  placeholder="Minimum 8 characters"
                  required
                  minLength={8}
                />
              </div>

              {/* Confirm Password */}
              <div className="mb-4">
                <label htmlFor="password_confirmation" className="inline-block mb-2 text-sm font-medium text-gray-700">
                  Confirm Password
                </label>
                <input
                  type="password"
                  id="password_confirmation"
                  value={formData.password_confirmation}
                  onChange={(e) => setFormData({ ...formData, password_confirmation: e.target.value })}
                  className="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 outline-none"
                  placeholder="Confirm your password"
                  required
                />
              </div>

              {/* Terms */}
              <div className="flex items-start gap-2 mb-4">
                <input
                  type="checkbox"
                  id="terms"
                  required
                  className="w-4 h-4 text-indigo-600 bg-white border-gray-300 rounded focus:ring-2 focus:ring-indigo-600 mt-0.5"
                />
                <label htmlFor="terms" className="text-sm text-gray-600">
                  I agree to the <a href="#" className="text-indigo-600 hover:text-indigo-700 font-medium">Terms of Service</a> and <a href="#" className="text-indigo-600 hover:text-indigo-700 font-medium">Privacy Policy</a>
                </label>
              </div>

              {/* Submit Button */}
              <div>
                <button
                  type="submit"
                  disabled={loading}
                  className="w-full bg-indigo-600 text-white border border-indigo-600 hover:bg-indigo-700 hover:border-indigo-700 active:bg-indigo-800 active:border-indigo-800 focus:outline-none focus:ring-4 focus:ring-indigo-300 rounded-md font-semibold py-2 px-4 transition disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  {loading ? 'Creating account...' : 'Create Account'}
                </button>

                <div className="text-center mt-4">
                  <span className="text-sm text-gray-600">Already have an account?</span>
                  <Link href="/login" className="text-indigo-600 hover:text-indigo-700 font-medium ml-1">
                    Sign in
                  </Link>
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
