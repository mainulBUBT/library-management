'use client'

import { QueryClient, QueryClientProvider as QueryClientProviderBase } from '@tanstack/react-query'
import { useState } from 'react'

export function QueryClientProvider({ children }: { children: React.ReactNode }) {
  const [queryClient] = useState(
    () =>
      new QueryClient({
        defaultOptions: {
          queries: {
            staleTime: 5 * 60 * 1000, // 5 minutes
            retry: 1,
          },
        },
      })
  )

  return <QueryClientProviderBase client={queryClient}>{children}</QueryClientProviderBase>
}
