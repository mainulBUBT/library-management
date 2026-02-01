import { create } from 'zustand'

export interface User {
  id: number
  name: string
  email: string
  role: string
  phone?: string
  address?: string
  member?: {
    id: number
    member_code: string
    member_type: string
    status: string
  }
}

interface AuthState {
  user: User | null
  token: string | null
  isAuthenticated: boolean
  setAuth: (user: User, token: string) => void
  logout: () => void
  loadFromStorage: () => void
}

export const useAuthStore = create<AuthState>((set) => ({
  user: null,
  token: null,
  isAuthenticated: false,

  setAuth: (user, token) => {
    localStorage.setItem('user', JSON.stringify(user))
    localStorage.setItem('token', token)
    set({ user, token, isAuthenticated: true })
  },

  logout: () => {
    localStorage.removeItem('user')
    localStorage.removeItem('token')
    set({ user: null, token: null, isAuthenticated: false })
  },

  loadFromStorage: () => {
    const user = localStorage.getItem('user')
    const token = localStorage.getItem('token')
    if (user && token) {
      set({
        user: JSON.parse(user),
        token,
        isAuthenticated: true,
      })
    }
  },
}))
