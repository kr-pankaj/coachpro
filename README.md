# CoachPro — Coaching Management System

> A production-ready, multi-tenant SaaS platform for coaching institutes and tutors across India.

---

## 🎯 What is CoachPro?

CoachPro is a comprehensive coaching management system built for small to mid-sized coaching centres and private tutors. It allows institutes to manage students, batches, attendance, and fees — while giving students their own self-service portal to track their progress.

Institutes subscribe via Razorpay, and you (the SaaS owner) manage everything from a Super Admin panel.

---

## ✨ Features

### 🏫 Institute (Admin) Portal
- **Multi-tenant architecture** — each institute sees only their own data (Eloquent Global Scopes)
- **Rich Admin Dashboard** — 4 KPI cards (students, batches, today's attendance %, pending fees), quick actions, recent students, batch overview, announcements widget
- **Profile completion tracker** — circular progress indicator; prompts admins to complete missing info
- **Institute profile** — full details: description (with help text), full address with all Indian states, phone, email, website, logo URL, brand color picker, established year
- **Batch management** — create batches with name, subject, and time slot
- **Student management** — full profiles: name, email, phone, parent phone, DOB, gender, school/class, address, notes, status (active/inactive), photo URL
- **Attendance tracking** — mark Present / Absent / Late per student per day
- **Fee management** — record fees by month (paid/pending) with ₹ totals; collected vs. pending summary
- **Announcements / Notice Board** — post Info/Warning/Success notices with optional expiry; shown to students
- **Profile update approvals** — students submit change requests; admin approves or rejects
- **Role-based navigation** — menus adapt to admin, student, and super admin roles
- **Mobile bottom tab bar** — persistent Home/Students/Attendance/Fees/Settings tabs on mobile

### 🎓 Student Portal
- **Institute-branded registration page** — shows logo, description, contact info, city; brand color applied; "Powered by CoachPro" footer
- **Personal dashboard** — enrolled batch, fee history (paid/pending with ₹ amounts), recent 30-day attendance
- **Announcements** — active institute notices shown on student dashboard
- **Profile update requests** — submit changes to phone, parent phone, address for admin approval

### 💳 Subscriptions & Billing (Razorpay)
- **14-day free trial** — full access on registration, no credit card required
- **Persistent trial countdown banner** — orange gradient banner with exact days remaining on every page; dismissible per session
- **Auto-gate on expiry** — after trial ends, any route redirects to subscription page
- **One-time payment** — ₹1,999 via Razorpay Standard Checkout (no Plan ID needed, works in test mode)
- **HMAC-SHA256 signature verification** — server-side payment verification
- **Lifetime Free toggle** — Super Admin grants permanent free access to any institute

### 🛡️ Super Admin Panel
- View all registered institutes with subscription and trial status
- Grant or revoke Lifetime Free access per institute
- Separate seeded account, no institute association needed

---

## 🛠 Tech Stack

| Layer        | Technology                     |
|--------------|-------------------------------|
| Backend      | Laravel 12 (PHP 8.2)          |
| Database     | SQLite (dev) / MySQL (prod)   |
| Frontend     | Blade + Tailwind CSS (Vite)   |
| Auth         | Laravel Breeze                |
| Payments     | Razorpay Standard Checkout    |
| Fonts        | Inter (Google Fonts)          |

---

## 🚀 Getting Started

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & npm
- A [Razorpay](https://razorpay.com) account (test or live)

### Installation

```bash
# Clone the repo
git clone <your-repo-url>
cd coaching-mgmt-system

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# Seed the Super Admin account
php artisan db:seed --class=SuperAdminSeeder

# Start development servers
php artisan serve
npm run dev
```

### Environment Variables

Add the following to your `.env`:

```env
RAZORPAY_KEY_ID=rzp_test_xxxxxxxxxxxx
RAZORPAY_KEY_SECRET=xxxxxxxxxxxxxxxxxxxxxxxx
RAZORPAY_PLAN_ID=plan_xxxxxxxxxxxxx   # Only needed for recurring subscriptions
```

---

## 👤 User Roles

| Role         | How to access                                              |
|--------------|------------------------------------------------------------|
| **Super Admin** | Seeded account: `superadmin@coachpro.app` / `superadmin123` — **change after first login!** |
| **Institute Admin** | Registers at `/register` — creates institute + admin account |
| **Student**  | Registers via unique link `/register/student/{institute-slug}` (if institute enables it) |

All roles use the same login page at `/login`. The system auto-detects the role.

---

## 🧪 Testing Payments (Razorpay Test Mode)

Use these credentials in the Razorpay checkout popup:

| Field      | Value                    |
|------------|--------------------------|
| Card No.   | `4718 6008 1099 0683`    |
| Expiry     | Any future date (12/26)  |
| CVV        | `100`                    |
| OTP        | `1234`                   |

> 💡 **Fastest:** Select **UPI** and enter `success@razorpay` — instant success, no card needed.

---

## 📁 Project Structure (Key Files)

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/RegisteredUserController.php   # Institute registration
│   │   ├── BatchController.php
│   │   ├── StudentController.php
│   │   ├── AttendanceController.php
│   │   ├── FeeController.php
│   │   ├── InstituteController.php             # Settings
│   │   ├── SubscriptionController.php          # Razorpay integration
│   │   ├── StudentRegistrationController.php   # Student self-registration
│   │   ├── ProfileUpdateRequestController.php  # Approval workflow
│   │   └── SuperAdminController.php            # Super admin panel
│   └── Middleware/
│       └── CheckSubscription.php               # Trial & subscription gate
├── Models/
│   ├── Institute.php                           # profileCompletion() helper
│   ├── User.php
│   ├── Student.php
│   ├── Batch.php
│   ├── Attendance.php
│   ├── Fee.php
│   ├── Announcement.php
│   └── ProfileUpdateRequest.php
└── Traits/
    └── BelongsToInstitute.php                  # Global scope for multi-tenancy
```

---

## 🔒 Security

- All Razorpay payments are verified server-side using HMAC-SHA256 signature
- Multi-tenancy enforced via Eloquent Global Scopes — institutes can never access each other's data
- CSRF protection on all forms
- Role-based access control on every route

---

## 📋 Roadmap

- [ ] Recurring Razorpay subscriptions (auto-debit monthly)
- [ ] Email notifications (fee reminders, trial expiry warnings, welcome email)
- [ ] Attendance reports (CSV/PDF export)
- [ ] Fee receipt PDF generation
- [ ] Student photo upload (file storage)
- [ ] Attendance bulk import (CSV)
- [ ] Mobile-responsive PWA for students
- [ ] SMS notifications via Fast2SMS
- [ ] Test result / marks entry module
- [ ] Parent portal (read-only view of child's progress)

---

## 📄 License

Proprietary — All rights reserved. Built for [your company name].
