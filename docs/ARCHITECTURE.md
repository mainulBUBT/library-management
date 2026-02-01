# Architecture & Database Schema

## Overview

The Library Management System is built on a decoupled architecture with a Laravel 12 backend serving both a web-based admin panel (using Dash UI) and a Next.js 14+ frontend for library members.

### Technical Stack

- **Backend**: Laravel 12
- **Admin Panel**: Dash UI (TailwindCSS + Blade)
- **User Frontend**: Next.js 14+ (App Router)
- **Database**: MySQL 8.0+
- **Auth**: Laravel Breeze (Admin) / Laravel Sanctum (User API)

## Database Schema (ER Diagram)

```mermaid
erDiagram
    USERS ||--o{ MEMBERS : "is"
    USERS ||--o{ STAFF : "is"
    MEMBERS ||--o{ LOANS : "borrows"
    MEMBERS ||--o{ RESERVATIONS : "reserves"
    MEMBERS ||--o{ FINES : "owes"
    RESOURCES ||--o{ COPIES : "has"
    COPIES ||--o{ LOANS : "lent_in"
    COPIES ||--o{ RESERVATIONS : "reserved_in"
    RESOURCES ||--o{ CATEGORIES : "belongs_to"
    RESOURCES ||--o{ AUTHORS : "written_by"
    FINES ||--o{ PAYMENTS : "paid_by"
    
    USERS {
        id bigint PK
        name string
        email string
        password string
        role enum
        created_at timestamp
    }
    
    MEMBERS {
        id bigint PK
        user_id bigint FK
        member_code string
        member_type enum
        status enum
        phone string
        address text
        joined_date date
    }
    
    RESOURCES {
        id bigint PK
        title string
        isbn string
        resource_type enum
        description text
        publication_year int
        language string
        pages int
        cover_image string
        created_at timestamp
    }
    
    COPIES {
        id bigint PK
        resource_id bigint FK
        copy_number string
        barcode string
        qr_code string
        status enum
        location string
        condition enum
    }
    
    LOANS {
        id bigint PK
        copy_id bigint FK
        member_id bigint FK
        borrowed_date date
        due_date date
        return_date date
        status enum
        renewed_count int
    }
    
    RESERVATIONS {
        id bigint PK
        resource_id bigint FK
        member_id bigint FK
        reserved_date date
        status enum
        expires_at timestamp
    }
    
    FINES {
        id bigint PK
        member_id bigint FK
        loan_id bigint FK
        fine_type enum
        amount decimal
        paid_amount decimal
        status enum
        created_at timestamp
    }
```

## System Components

1. **Cataloging Service**: Manages books, e-books, and digital resources.
2. **Circulation Engine**: Handles check-outs, check-ins, and automated due date calculations.
3. **Fine Management**: Tracks and processes offline payments for late items.
4. **Notification Engine**: Dispatches emails and SMS for reminders.
5. **Analytics Service**: Aggregates data for library performance reporting.
