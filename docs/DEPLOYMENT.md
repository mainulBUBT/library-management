# Deployment Guide

Detailed server requirements, installation steps, and production configuration for the Library Management System.

## Server Requirements

- **PHP**: 8.2 or higher
- **MySQL**: 8.0 or higher
- **Node.js**: 18.x or higher
- **Nginx** or **Apache**
- **SSL Certificate**

## Quick Start (Production)

1. **Clone & Setup**:
   ```bash
   git clone <repo-url>
   cd backend && composer install --optimize-autoloader --no-dev
   cp .env.example .env && php artisan key:generate
   ```

2. **Database**:
   ```bash
   php artisan migrate --force
   ```

3. **Assets**:
   ```bash
   npm install && npm run build
   ```

4. **Queue & Scheduler**:
   Setup Supervisor for `queue:work` and Cron for `schedule:run`.

Refer to [Implementation Plan](PLAN.md) for more details.
