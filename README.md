# Halcon - Construction Material Distributor Web Application
## Evidence 2: Logical Implementation & Data Modeling (Learning Outcome 2)

A web application developed for **"Halcon"**, a construction material distributor, to automate and track the complete lifecycle of customer orders, departmental operations, and delivery evidence.

---

## Table of Contents
1. [Overview](#overview)
2. [Database Entities & Relationships](#database-entities--relationships)
3. [Controllers & Business Logic](#controllers--business-logic)
4. [Views & Functionalities](#views--functionalities)
5. [Default Test Users & Seed Data](#default-test-users--seed-data)
6. [Installation & Setup](#installation--setup)
7. [Running Automated Tests](#running-automated-tests)

---

## Overview

This project provides continuity to Evidence 1, implementing the complete backend logic, relational data models, migrations, controllers, automated testing, seeders, and views according to software engineering best practices.

The system handles two main user flows:
- **Unregistered Users (Public):** Search and track order status in real time by invoice number without authentication.
- **Registered Personnel (Protected):** Role-based dashboard to manage users, departments, order workflows, evidence photo uploads, and soft-delete/restoration lifecycle.

---

## Database Entities & Relationships

The database architecture consists of four interconnected entities respecting primary keys (`id`) and foreign keys:

### 1. `Department` (`departments` table)
Represents the organizational departments/roles in Halcon:
- `id` (PK)
- `name` (Unique: `Admin`, `Sales`, `Purchasing`, `Warehouse`, `Route`)
- `description`
- **Relationship:** `Department hasMany User` (`users()`)

### 2. `User` (`users` table)
Represents company staff members:
- `id` (PK)
- `name`, `email`, `password`
- `department_id` (FK referencing `departments.id`, nullable on delete)
- `is_active` (Boolean: indicates if account is active or inactive)
- `confirmed`, `confirmation_code`
- **Relationships:**
  - `User belongsTo Department` (`department()`)
  - `User hasMany Order` (`orders()`)
  - `User hasMany OrderEvidence` (`evidences()`)

### 3. `Order` (`orders` table)
Represents a customer materials order with soft delete support:
- `id` (PK)
- `invoice_number` (Unique consecutive identifier)
- `customer_number` (Unique customer identifier)
- `customer_name` (Customer / Company name)
- `fiscal_data` (Tax info for physical invoice billing)
- `order_datetime` (Order timestamp)
- `delivery_address` (Physical delivery destination)
- `notes` (Extra order instructions)
- `status` (`Ordered`, `In process`, `In route`, `Delivered`)
- `user_id` (FK referencing `users.id` of the user who registered the order)
- `process_date` (Timestamp when warehouse starts order preparation)
- `route_photo` (Relative path to loaded unit photo evidence)
- `delivery_photo` (Relative path to delivered material photo evidence)
- `deleted_at` (Timestamp for logical / soft deletion)
- **Relationships:**
  - `Order belongsTo User` (`user()`)
  - `Order hasMany OrderEvidence` (`evidences()`)

### 4. `OrderEvidence` (`order_evidences` table)
Logs photographic and operational evidence throughout order distribution:
- `id` (PK)
- `order_id` (FK referencing `orders.id` on delete cascade)
- `user_id` (FK referencing `users.id`)
- `stage` (`In route` or `Delivered`)
- `photo_path` (Stored evidence path)
- `notes` (Operator notes)
- **Relationships:**
  - `OrderEvidence belongsTo Order` (`order()`)
  - `OrderEvidence belongsTo User` (`user()`)

---

## Controllers & Business Logic

- **`HomeController`**: Handles the public-facing tracking portal for guests and clients. Allows searching orders by invoice number (and optional customer number). Dynamically displays status alerts, process dates, and uploaded delivery/route evidence.
- **`DashboardController`**: Protected administrative home providing summary metrics (total active orders, counts per status, archived orders, user counts) and direct navigation links to all system sections.
- **`UserController`**: Protected user management:
  - List active and inactive users.
  - Create new user and assign department/role.
  - Edit existing user data, change department, or toggle account between active and inactive.
  - Inactive users are automatically blocked from logging in.
- **`OrderController`**: Complete lifecycle management of orders:
  - List all active orders ordered from newest to oldest (`latest()`) with filtering by invoice, customer, status, and date.
  - Create order with auto-suggested consecutive invoice numbers and default status `Ordered`.
  - View detailed order information, timeline, and photo gallery.
  - Edit order attributes and customer information.
  - Change status with photo evidence capture/upload (`In route` loaded unit, `Delivered` delivery evidence).
  - Logical deletion (`SoftDeletes`) keeping database integrity.
  - List archived/logically deleted orders with one-click restoration back to active orders.

---

## Views & Functionalities

### Unregistered Users
- **Home / Order Tracking (`/`):**
  - Search form by invoice number.
  - If status is `Ordered`: Shows registered order details.
  - If status is `In process`: Shows process name and process start date.
  - If status is `In route`: Shows distribution in progress and loaded unit photo.
  - If status is `Delivered`: Displays high-resolution delivery evidence photo confirming drop-off at customer site.

### Registered Personnel (Protected by Authentication)
- **Dashboard (`/dashboard`):** Real-time KPI cards and direct navigation to all modules.
- **Users Module (`/users`):** Full table of active and inactive staff, department tags, and user creation/editing forms.
- **Orders Module (`/orders`):**
  - Filterable table with newest orders listed first.
  - Order creation form capturing all fiscal and delivery data.
  - Order view and status updating panel supporting camera photo uploads.
  - Soft-delete option moving orders to archive.
- **Archived Orders Module (`/orders-archived`):**
  - Displays logically deleted orders.
  - Action to restore any archived order back to the active list.

---

## Default Test Users & Seed Data

The application includes comprehensive seeders (`DepartmentSeeder`, `UserSeeder`, `OrderSeeder`) to immediately test all features:

| Email | Password | Department / Role | Status | Description |
|---|---|---|---|---|
| `admin@halcon.com` | `password123` | Admin | Active | Full administrator |
| `sales@halcon.com` | `password123` | Sales | Active | Order registration & sales |
| `warehouse@halcon.com` | `password123` | Warehouse | Active | Picking & preparing orders |
| `route@halcon.com` | `password123` | Route | Active | Transport & evidence photos |
| `purchasing@halcon.com` | `password123` | Purchasing | Active | Material procurement |
| `inactive@halcon.com` | `password123` | Sales | Inactive | Deactivated test account |

### Sample Pre-seeded Orders for Testing
- **`INV-1001`**: Status `Delivered` (includes loaded unit photo and delivered material evidence photo).
- **`INV-1002`**: Status `In route` (includes loaded unit departure photo).
- **`INV-1003`**: Status `In process` (includes process name and process date).
- **`INV-1004`**: Status `Ordered` (newly raised order).
- **`INV-1005`**: Archived order (soft-deleted in trash, ready to be retrieved/restored).

---

## Installation & Setup

1. **Clone the repository:**
   ```bash
   git clone https://github.com/JustLexl/WebDesign-Evidence2.git
   cd WebDesign-Evidence2
   ```

2. **Install PHP dependencies:**
   ```bash
   composer install
   ```

3. **Configure `.env` file:**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306 (or 3307 depending on your MySQL setup)
   DB_DATABASE=Evidencia2DesignWeb
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Run database migrations and seeders:**
   ```bash
   php artisan migrate:fresh --seed
   ```

5. **Start local development server:**
   ```bash
   php artisan serve
   ```
   Open [http://127.0.0.1:8000](http://127.0.0.1:8000) in your web browser.

---

## Running Automated Tests

Run the complete test suite verifying public tracking, protected routes, user management, order lifecycle, photo evidence upload, and soft-delete/restore:

```bash
php artisan test
```
All feature and unit tests run with transactional rollback to preserve database state.
