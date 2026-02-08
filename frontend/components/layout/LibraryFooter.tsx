import React from 'react'
import Link from 'next/link'
import { BookOpen, Mail, Phone, MapPin, Clock } from 'lucide-react'

export default function LibraryFooter() {
  const currentYear = new Date().getFullYear()

  return (
    <footer className="bg-slate-900 text-white">
      {/* Main Footer */}
      <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          {/* About */}
          <div>
            <div className="flex items-center gap-2 mb-4">
              <div className="w-10 h-10 bg-slate-800 rounded-lg flex items-center justify-center border border-white/10">
                <BookOpen className="w-5 h-5 text-white" />
              </div>
              <div>
                <h3 className="font-semibold text-base">City Library</h3>
                <p className="text-xs text-slate-400">Knowledge Awaits</p>
              </div>
            </div>
            <p className="text-slate-400 text-sm leading-relaxed">
              Your gateway to knowledge, culture, and community. Explore our vast collection of books, journals, and digital resources.
            </p>
          </div>

          {/* Quick Links */}
          <div>
            <h4 className="font-medium mb-4 text-slate-300 text-sm">Quick Links</h4>
            <ul className="space-y-2">
              <li>
                <Link href="/catalog" className="text-slate-400 hover:text-white text-sm transition-colors">
                  Browse Catalog
                </Link>
              </li>
              <li>
                <Link href="/dashboard" className="text-slate-400 hover:text-white text-sm transition-colors">
                  My Dashboard
                </Link>
              </li>
              <li>
                <Link href="/register" className="text-slate-400 hover:text-white text-sm transition-colors">
                  Become a Member
                </Link>
              </li>
              <li>
                <a href="#" className="text-slate-400 hover:text-white text-sm transition-colors">
                  Library Rules
                </a>
              </li>
            </ul>
          </div>

          {/* Resources */}
          <div>
            <h4 className="font-medium mb-4 text-slate-300 text-sm">Resources</h4>
            <ul className="space-y-2">
              <li>
                <a href="#" className="text-slate-400 hover:text-white text-sm transition-colors">
                  E-Books & Audiobooks
                </a>
              </li>
              <li>
                <a href="#" className="text-slate-400 hover:text-white text-sm transition-colors">
                  Academic Journals
                </a>
              </li>
              <li>
                <a href="#" className="text-slate-400 hover:text-white text-sm transition-colors">
                  Research Databases
                </a>
              </li>
              <li>
                <a href="#" className="text-slate-400 hover:text-white text-sm transition-colors">
                  Events & Workshops
                </a>
              </li>
            </ul>
          </div>

          {/* Contact */}
          <div>
            <h4 className="font-medium mb-4 text-slate-300 text-sm">Contact Us</h4>
            <ul className="space-y-3">
              <li className="flex items-start gap-3 text-slate-400 text-sm">
                <MapPin className="w-4 h-4 mt-0.5 flex-shrink-0 text-slate-500" />
                <span>123 Library Street<br />City, State 12345</span>
              </li>
              <li className="flex items-center gap-3 text-slate-400 text-sm">
                <Phone className="w-4 h-4 flex-shrink-0 text-slate-500" />
                <span>(555) 123-4567</span>
              </li>
              <li className="flex items-center gap-3 text-slate-400 text-sm">
                <Mail className="w-4 h-4 flex-shrink-0 text-slate-500" />
                <span>info@citylibrary.edu</span>
              </li>
              <li className="flex items-start gap-3 text-slate-400 text-sm">
                <Clock className="w-4 h-4 mt-0.5 flex-shrink-0 text-slate-500" />
                <span>Mon-Fri: 9AM - 9PM<br />Sat-Sun: 10AM - 6PM</span>
              </li>
            </ul>
          </div>
        </div>
      </div>

      {/* Bottom Bar */}
      <div className="border-t border-white/10">
        <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-4">
          <div className="flex flex-col sm:flex-row items-center justify-between gap-4">
            <p className="text-white/50 text-sm">
              © {currentYear} City Library. All rights reserved.
            </p>
            <div className="flex items-center gap-6">
              <a href="#" className="text-white/50 hover:text-white text-sm transition-colors">
                Privacy Policy
              </a>
              <a href="#" className="text-white/50 hover:text-white text-sm transition-colors">
                Terms of Service
              </a>
              <a href="#" className="text-white/50 hover:text-white text-sm transition-colors">
                Accessibility
              </a>
            </div>
          </div>
        </div>
      </div>
    </footer>
  )
}
