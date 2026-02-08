import type { Metadata } from "next";
import "./globals.css";
import { QueryClientProvider } from "@/lib/query-client";

export const metadata: Metadata = {
  title: "Library Management System",
  description: "Modern library management with catalog browsing and member dashboard",
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="en">
      <body className="font-sans antialiased">
        <QueryClientProvider>
          {children}
        </QueryClientProvider>
      </body>
    </html>
  );
}
