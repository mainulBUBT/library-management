// Common Types
export interface PaginatedResponse<T> {
  data: T[]
  current_page: number
  last_page: number
  per_page: number
  total: number
}

// Resource Types
export interface Resource {
  id: number
  title: string
  subtitle?: string
  description?: string
  isbn?: string
  publication_year?: number
  resource_type: 'book' | 'journal' | 'magazine' | 'newspaper' | 'digital_media'
  cover_image?: string
  category?: Category
  author?: Author
  publisher?: Publisher
  copies_count?: number
  available_copies?: number
  created_at?: string
}

export interface Category {
  id: number
  name: string
  description?: string
}

export interface Author {
  id: number
  name: string
  biography?: string
}

export interface Publisher {
  id: number
  name: string
}

// Loan Types
export interface Loan {
  id: number
  loan_date: string
  due_date: string
  return_date?: string
  status: 'active' | 'returned' | 'overdue'
  renewal_count: number
  is_overdue: boolean
  days_until_due: number | null
  copy: {
    id: number
    barcode: string
    call_number?: string
    resource: {
      id: number
      title: string
      cover_image?: string
    }
  }
  fine?: {
    id: number
    amount: number
    status: string
  }
}

// Reservation Types
export interface Reservation {
  id: number
  status: 'pending' | 'fulfilled' | 'cancelled' | 'expired'
  reserved_date: string
  expires_at: string
  is_expired: boolean
  resource: {
    id: number
    title: string
    cover_image?: string
    author?: string
  }
}

// Fine Types
export interface Fine {
  id: number
  amount: number
  amount_paid: number
  balance: number
  status: 'pending' | 'paid' | 'waived'
  reason: string
  fine_date: string
  loan: {
    id: number
    due_date: string
    resource: {
      id: number
      title: string
    }
  }
}
