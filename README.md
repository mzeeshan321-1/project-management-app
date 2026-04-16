
# Project Management App

A modern, multi-tenant project management platform for teams and companies to organize projects, tasks, users, and finances.

---

## Problem Statement

Many organizations struggle to efficiently manage multiple projects, users with different roles, and client interactions in one system—especially when working across different teams (tenants) and needing visibility into finances and team productivity. Manual tracking or fragmented tools lead to mistakes, data leaks, and project delays.

---

## Solution & Approach

We built **Project Management App** to bring all core management tasks into a single, secure web platform. Our solution focuses on:

- Segregating data and workflows per tenant (organization)
- Robust role-based access so the right users see the right data
- Comprehensive dashboards for at-a-glance project health
- Simple forms and navigation to ensure adoption by non-technical users
- Secure, modern authentication for all user actions
- File uploads, financial tracking, and user notifications all in one place

---

## Key Difficulties & How We Solved Them

- **Multi-Tenancy Isolation:**  
  We carefully designed our schema and queries so each tenant’s data remains private and fully isolated, avoiding data mix-ups.
- **Complex Role & Permission Logic:**  
  The app supports Super Admin, Tenant, Expert, and Client, each with unique needs. We used the excellent [spatie/laravel-permission](https://github.com/spatie/laravel-permission) package to centrally manage and assign flexible roles and granular permissions with minimal custom code.
- **File Management & Security:**  
  Storing and retrieving tenant-specific files securely was tackled by associating uploads to projects or tasks, verifying permissions on every action.
- **Profit & Financial Handling:**  
  We automated profit/loss calculations and displayed them with clear reporting for non-technical stakeholders.
- **Bug Prevention & Quality:**  
  We invested in automated testing for critical business logic and endpoints. This minimizes regressions and ensures the reliability of the app.

---

## Features

- **Multi-Tenancy:** Isolated data for each organization (tenant)
- **Role-Based Access:** Super Admin, Tenant, Expert, Client—with Spatie-backed permissions
- **Project & Task Management:** Create, assign, and track projects and their tasks
- **Client & Expert Management:** Add, manage, and relate users per tenant
- **File Uploads:** Attach files to projects and tasks
- **Profit Tracking:** Project budgets, costs, payments, and profit/loss reports
- **Dashboards:** Role-specific statistics and summaries
- **Notifications:** Flash messages keep the user informed
- **Secure Auth:** Registration, login/logout, email verification, password management
- **Responsive UI:** Clean design and mobile-friendly layout
- **Frontend Template:** One template for multiple roles/users

---

## Tech Stack

- **Backend:** PHP, Laravel
- **Frontend:** HTML, CSS, JS, Blade (Laravel templating), Bootstrap.
- **Database:** MySQL (via Laravel migrations)
- **Roles/Permissions:** [spatie/laravel-permission](https://github.com/spatie/laravel-permission)
- **Build Tools:** Vite, npm (for JS and CSS assets)
- **Testing:** PestPHP

---

## Folder Structure

```
app/                # Controllers, models, business logic
database/           # Migrations
resources/views/    # Blade templates (UI)
public/assets/      # JS, CSS, icons, TinyMCE
```

---

## Database Overview

- `users`      — User accounts (linked to tenants, experts, clients)
- `tenents`    — Organizations
- `clients`    — Clients per tenant
- `experts`    — Team members
- `projects`   — Projects with assignments and statuses
- `tasks`      — Project tasks (due date, priority, expert)
- `files`      — Uploaded files for tasks/projects
- `profits`    — Financial data per project

---

## How We Ensure Quality

- Automated tests for business logic and endpoints
- Manual testing for UI/UX workflows
- Code reviews to catch edge cases and potential data leaks

---

## Getting Started

1. **Clone:**  
   `git clone https://github.com/mzeeshan321-1/project-management-app.git`
2. **Install backend dependencies:**  
   `composer install`
3. **Install frontend dependencies:**  
   `npm install`
4. **Configure environment:**  
   Copy `.env.example` to `.env`, update DB credentials
5. **Run migrations:**  
   `php artisan migrate`
6. **Build assets:**  
   `npm run dev`
7. **Start server:**  
   `php artisan serve`
8. **Run tests (optional):**  
   `./vendor/bin/pest`

---

## Contributing

Pull requests and suggestions are welcome.  
For bugs or feature requests, please open an issue.

---


https://github.com/user-attachments/assets/32d6d104-f381-4257-8067-dd285ac8fe8f


**© 2026 Genixstack. All Rights Reserved.**
