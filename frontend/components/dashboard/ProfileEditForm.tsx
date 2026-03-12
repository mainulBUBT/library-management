'use client'

import { useState } from 'react'
import { useMutation, useQueryClient } from '@tanstack/react-query'
import api from '@/lib/api'
import { useAuthStore } from '@/stores/authStore'
import { User, Mail, Phone, MapPin, Loader2, Check, AlertCircle } from 'lucide-react'

export default function ProfileEditForm() {
  const { user, setAuth } = useAuthStore()
  const queryClient = useQueryClient()

  const [formData, setFormData] = useState({
    name: user?.name || '',
    phone: user?.phone || '',
    address: user?.address || '',
  })

  const [message, setMessage] = useState<{ type: 'success' | 'error'; text: string } | null>(null)

  const updateMutation = useMutation({
    mutationFn: async (data: typeof formData) => {
      const response = await api.put('/profile', data)
      return response.data
    },
    onSuccess: (data) => {
      // Update auth store with new user data
      setAuth(data.data || data.user, localStorage.getItem('token')!)
      setMessage({ type: 'success', text: 'Profile updated successfully!' })
      setTimeout(() => setMessage(null), 3000)
    },
    onError: (err: any) => {
      const errorMsg = err.response?.data?.message || 'Failed to update profile. Please try again.'
      setMessage({ type: 'error', text: errorMsg })
    },
  })

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault()
    setMessage(null)
    updateMutation.mutate(formData)
  }

  return (
    <form onSubmit={handleSubmit} className="space-y-6">
      {/* Message Alert */}
      {message && (
        <div className={`p-4 rounded-lg flex items-start gap-3 ${
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

      {/* Name Field */}
      <div>
        <label htmlFor="name" className="block text-sm font-semibold text-slate-900 mb-2">
          Full Name
        </label>
        <div className="relative">
          <User className="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" />
          <input
            type="text"
            id="name"
            value={formData.name}
            onChange={(e) => setFormData({ ...formData, name: e.target.value })}
            className="input-field pl-12"
            placeholder="John Doe"
            required
          />
        </div>
      </div>

      {/* Email (Read-only) */}
      <div>
        <label htmlFor="email" className="block text-sm font-semibold text-slate-900 mb-2">
          Email Address
        </label>
        <div className="relative">
          <Mail className="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" />
          <input
            type="email"
            id="email"
            value={user?.email || ''}
            disabled
            className="input-field pl-12 bg-slate-50 cursor-not-allowed text-slate-500"
          />
        </div>
        <p className="text-xs text-slate-500 mt-1">Email cannot be changed</p>
      </div>

      {/* Phone Field */}
      <div>
        <label htmlFor="phone" className="block text-sm font-semibold text-slate-900 mb-2">
          Phone Number <span className="text-slate-500 font-normal">(optional)</span>
        </label>
        <div className="relative">
          <Phone className="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" />
          <input
            type="tel"
            id="phone"
            value={formData.phone}
            onChange={(e) => setFormData({ ...formData, phone: e.target.value })}
            className="input-field pl-12"
            placeholder="+1 (555) 000-0000"
          />
        </div>
      </div>

      {/* Address Field */}
      <div>
        <label htmlFor="address" className="block text-sm font-semibold text-slate-900 mb-2">
          Address <span className="text-slate-500 font-normal">(optional)</span>
        </label>
        <div className="relative">
          <MapPin className="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" />
          <input
            type="text"
            id="address"
            value={formData.address}
            onChange={(e) => setFormData({ ...formData, address: e.target.value })}
            className="input-field pl-12"
            placeholder="123 Library St, City"
          />
        </div>
      </div>

      {/* Submit Button */}
      <div className="flex gap-3">
        <button
          type="submit"
          disabled={updateMutation.isPending}
          className="flex-1 btn-primary py-3 flex items-center justify-center gap-2"
        >
          {updateMutation.isPending ? (
            <>
              <Loader2 className="w-4 h-4 animate-spin" />
              Saving...
            </>
          ) : (
            'Save Changes'
          )}
        </button>
      </div>
    </form>
  )
}
