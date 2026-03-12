'use client'

import { useState } from 'react'
import { useMutation } from '@tanstack/react-query'
import api from '@/lib/api'
import { Lock, Eye, EyeOff, Loader2, Check, AlertCircle } from 'lucide-react'

export default function PasswordChangeForm() {
  const [formData, setFormData] = useState({
    current_password: '',
    password: '',
    password_confirmation: '',
  })
  const [showPasswords, setShowPasswords] = useState({
    current: false,
    new: false,
    confirm: false,
  })
  const [message, setMessage] = useState<{ type: 'success' | 'error'; text: string } | null>(null)

  const changePasswordMutation = useMutation({
    mutationFn: async (data: typeof formData) => {
      const response = await api.post('/change-password', data)
      return response.data
    },
    onSuccess: (data) => {
      setMessage({ type: 'success', text: data.message || 'Password changed successfully!' })
      setFormData({ current_password: '', password: '', password_confirmation: '' })
      setTimeout(() => setMessage(null), 3000)
    },
    onError: (err: any) => {
      const errors = err.response?.data?.errors
      if (errors?.current_password) {
        setMessage({ type: 'error', text: errors.current_password[0] })
      } else {
        setMessage({ type: 'error', text: err.response?.data?.message || 'Password change failed. Please try again.' })
      }
    },
  })

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault()
    setMessage(null)

    if (formData.password !== formData.password_confirmation) {
      setMessage({ type: 'error', text: 'New passwords do not match.' })
      return
    }

    if (formData.password.length < 8) {
      setMessage({ type: 'error', text: 'Password must be at least 8 characters.' })
      return
    }

    changePasswordMutation.mutate(formData)
  }

  const togglePassword = (field: 'current' | 'new' | 'confirm') => {
    setShowPasswords(prev => ({ ...prev, [field]: !prev[field] }))
  }

  const getPasswordStrength = (password: string) => {
    if (password.length < 8) return { level: 0, color: 'bg-slate-200', text: 'Use 8+ characters' }
    if (password.length < 12) return { level: 1, color: 'bg-red-500', text: 'Weak password' }
    if (password.length < 14) return { level: 2, color: 'bg-amber-500', text: 'Fair password' }
    return { level: 3, color: 'bg-emerald-500', text: 'Strong password' }
  }

  const strength = getPasswordStrength(formData.password)

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

      {/* Current Password */}
      <div>
        <label htmlFor="current_password" className="block text-sm font-semibold text-slate-900 mb-2">
          Current Password
        </label>
        <div className="relative">
          <Lock className="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" />
          <input
            type={showPasswords.current ? 'text' : 'password'}
            id="current_password"
            value={formData.current_password}
            onChange={(e) => setFormData({ ...formData, current_password: e.target.value })}
            className="input-field pl-12 pr-12"
            required
          />
          <button
            type="button"
            onClick={() => togglePassword('current')}
            className="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors"
          >
            {showPasswords.current ? <EyeOff className="w-5 h-5" /> : <Eye className="w-5 h-5" />}
          </button>
        </div>
      </div>

      {/* New Password */}
      <div>
        <label htmlFor="password" className="block text-sm font-semibold text-slate-900 mb-2">
          New Password
        </label>
        <div className="relative">
          <Lock className="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" />
          <input
            type={showPasswords.new ? 'text' : 'password'}
            id="password"
            value={formData.password}
            onChange={(e) => setFormData({ ...formData, password: e.target.value })}
            className="input-field pl-12 pr-12"
            placeholder="Minimum 8 characters"
            required
            minLength={8}
          />
          <button
            type="button"
            onClick={() => togglePassword('new')}
            className="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors"
          >
            {showPasswords.new ? <EyeOff className="w-5 h-5" /> : <Eye className="w-5 h-5" />}
          </button>
        </div>

        {/* Password Strength Indicator */}
        {formData.password && (
          <div className="mt-3">
            <div className="flex gap-1 mb-1">
              {[1, 2, 3, 4].map((i) => (
                <div
                  key={i}
                  className={`h-1.5 flex-1 rounded-full transition-colors ${
                    formData.password.length >= i * 2
                      ? strength.level >= 3
                        ? 'bg-emerald-500'
                        : strength.level >= 2
                        ? 'bg-amber-500'
                        : strength.level >= 1
                        ? 'bg-red-500'
                        : 'bg-slate-200'
                      : 'bg-slate-200'
                  }`}
                />
              ))}
            </div>
            <p className={`text-xs ${strength.level >= 3 ? 'text-emerald-600' : strength.level >= 2 ? 'text-amber-600' : 'text-slate-500'}`}>
              {strength.text}
            </p>
          </div>
        )}
      </div>

      {/* Confirm Password */}
      <div>
        <label htmlFor="password_confirmation" className="block text-sm font-semibold text-slate-900 mb-2">
          Confirm New Password
        </label>
        <div className="relative">
          <Lock className="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" />
          <input
            type={showPasswords.confirm ? 'text' : 'password'}
            id="password_confirmation"
            value={formData.password_confirmation}
            onChange={(e) => setFormData({ ...formData, password_confirmation: e.target.value })}
            className="input-field pl-12 pr-12"
            placeholder="Confirm your password"
            required
          />
          <button
            type="button"
            onClick={() => togglePassword('confirm')}
            className="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors"
          >
            {showPasswords.confirm ? <EyeOff className="w-5 h-5" /> : <Eye className="w-5 h-5" />}
          </button>
        </div>
        {formData.password_confirmation && formData.password !== formData.password_confirmation && (
          <p className="text-xs text-red-500 mt-1 flex items-center gap-1">
            <AlertCircle className="w-3 h-3" />
            Passwords do not match
          </p>
        )}
      </div>

      {/* Submit Button */}
      <button
        type="submit"
        disabled={changePasswordMutation.isPending}
        className="w-full btn-primary py-3 flex items-center justify-center gap-2"
      >
        {changePasswordMutation.isPending ? (
          <>
            <Loader2 className="w-4 h-4 animate-spin" />
            Changing Password...
          </>
        ) : (
          'Change Password'
        )}
      </button>
    </form>
  )
}
