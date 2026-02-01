# Library Management System - Implementation Plan

A modern, future-proof library management system suitable for schools, social clubs, and public libraries.

## Design Decisions (Confirmed)

✅ **Admin Dashboard**: **Dash UI** (TailwindCSS-based HTML template)  
✅ **Tenancy**: **Single-tenancy** (each installation runs independently)  
✅ **Payment**: **Offline payment tracking** (cash/check recording, no gateway integration)

> [!NOTE]
> **Dash UI Integration Approach**: We'll integrate Dash UI's TailwindCSS components and layouts directly into Laravel Blade templates. The admin panel will be built with Laravel's native routing and Blade views, using Dash UI's pre-built components for a polished, modern interface.

> [!IMPORTANT]
> **Single-Tenancy Architecture**: Each school/library/organization will have their own separate installation. This simplifies:
> - Data isolation (no cross-tenant data leaks)
> - Performance (no tenant filtering overhead)
> - Customization (each org can customize independently)
> - Backup/restore (simple per-installation)
> - Deployment (standard Laravel deployment)

---

## Architecture Overview

### Technology Stack

#### Backend (Laravel 12)
- **Framework**: Laravel 12
- **Admin Panel**: Laravel Blade + Dash UI (TailwindCSS)
- **Authentication**: 
  - Admin/Staff: Laravel Breeze (session-based)
  - API: Laravel Sanctum (for Next.js frontend)
- **Database**: MySQL 8.0+
- **Cache**: Redis (optional for production)
- **Queue**: Database (simple) or Redis (production)
- **Search**: Database full-text search (MySQL) or Laravel Scout + Meilisearch (advanced)
- **PDF Generation**: DomPDF or Snappy (for receipts, reports, member cards)

#### Frontend (Next.js 14+)
- **Framework**: Next.js 14+ (App Router)
- **UI Library**: shadcn/ui + custom components inspired by Dash UI
- **Styling**: Tailwind CSS (matching Dash UI theme)
- **State Management**: React Query (server state) + Zustand (client state)
- **Forms**: React Hook Form + Zod validation
- **API Client**: Axios with interceptors
- **Icons**: Lucide React (modern icon library)
- **Charts**: Recharts or Chart.js (for analytics)

#### DevOps & Tools
- **API Documentation**: Scribe or L5-Swagger
- **Testing**: PHPUnit (Backend), Jest/Playwright (Frontend)
- **Code Quality**: PHP CS Fixer, ESLint, Prettier
- **Version Control**: Git

---

## Core Features & Modules

### 1. User Management & Roles

**Roles**:
- Super Admin (System configuration)
- Librarian (Full library operations)
- Assistant Librarian (Limited operations)
- Member (Borrower - Student/Staff/Public)

**Features**:
- Role-based access control (RBAC)
- Member registration & approval workflow
- Member card generation with QR code
- Profile management
- Activity history

### 2. Catalog Management

**Resource Types**:
- Books (physical & digital)
- Journals/Magazines
- DVDs/CDs
- Research Papers
- E-books/Audio books

**Features**:
- ISBN-based book addition
- Bulk import (CSV/Excel)
- Categories & tags
- Author management
- Publisher management
- Multiple copies tracking
- Cover image upload
- Digital asset storage (for e-books)

### 3. Circulation Management

**Features**:
- Check-out/Check-in
- Reservation system
- Renewal management
- Due date tracking
- Hold requests
- Inter-library loan (optional)
- QR/Barcode scanning for quick processing

**Business Rules**:
- Configurable borrowing limits per member type
- Configurable loan periods per resource type
- Maximum renewals allowed
- Grace periods

### 4. Fine & Payment Management

**Offline Payment System**:

This system tracks cash, check, and other offline payments made by members for library fines and fees.

**Features**:
- **Automated fine calculation**
  - Late return fines (configurable rate per day)
  - Damage fees (flat rate or custom amount)
  - Lost item fees (replacement cost + processing fee)
- **Payment recording**
  - Payment method (Cash, Check, Money Order, Bank Transfer)
  - Check number (if applicable)
  - Payment date and time
  - Received by (staff member)
  - Notes/remarks
- **Payment history & tracking**
  - All payments linked to member account
  - Transaction log with audit trail
  - Outstanding balance tracking
- **Fine management**
  - Fine waiver/adjustment (with reason & approval)
  - Partial payments allowed
  - Multiple payments per fine
  - Fine status: Pending, Partially Paid, Paid, Waived
- **Receipt generation**
  - Auto-generated PDF receipts
  - Customizable receipt templates
  - Receipt numbering system
  - Email receipt option
- **Reports**
  - Daily cash collection report
  - Outstanding fines report
  - Payment history by member
  - Revenue summary (by date range)

---

## Database Schema

Refer to [ARCHITECTURE.md](ARCHITECTURE.md) for detailed schema and ER diagrams.

---

## API Architecture

Refer to [API.md](API.md) for detailed endpoint documentation.

---

## Proposed Changes

Refer to [DASH_UI_GUIDE.md](DASH_UI_GUIDE.md) and [ADVANCED_FEATURES.md](ADVANCED_FEATURES.md) for implementation details.

---

## Next Steps

1. ✅ **Plan Approved** - Dash UI confirmed as admin dashboard
2. **Start Implementation** - Begin with backend setup
   - Install Laravel 12
   - Setup Dash UI with TailwindCSS
   - Create database structure
3. **Build Core Modules** - Implement features incrementally
   - Catalog management
   - Circulation system
   - Member management
4. **Frontend Development** - Build Next.js user portal
5. **Testing & Refinement** - Continuous testing and iteration
