# Halcon - Material Distributor Web Application
## Evidence 2: Learning Outcome 2

Web application for **"Halcon"** construction materials distributor to manage orders, tracking, and evidence photos.

---

## Features

- **Public Order Tracking:** Unregistered users can track orders by invoice number (shows delivery photo for delivered orders, and process name/date for in-process orders).
- **Dashboard:** Simple private panel with direct links for authenticated users.
- **Users Management:** List active/inactive users, create new users with roles/departments, and edit details or toggle active status.
- **Orders Management:**
  - Orders list sorted from newest to oldest.
  - Create new orders with default `Ordered` status.
  - Edit order information.
  - Change order status (`Ordered` -> `In process` -> `In route` -> `Delivered`).
  - Upload photo evidence for loaded unit (`In route`) and delivered material (`Delivered`).
  - View order details.
  - Logically delete orders (soft delete).
- **Archived Orders:** View soft-deleted orders and retrieve/restore them back to active orders.

---

## Test Accounts

Default password for all seeded accounts: `password123`

| Email | Department / Role | Status |
|---|---|---|
| `admin@halcon.com` | Admin | Active |
| `sales@halcon.com` | Sales | Active |
| `warehouse@halcon.com` | Warehouse | Active |
| `route@halcon.com` | Route | Active |
| `purchasing@halcon.com` | Purchasing | Active |
| `inactive@halcon.com` | Sales | Inactive |

Sample test invoices:
- `INV-1001` (Delivered, with photo evidence)
- `INV-1002` (In route, with loaded unit photo)
- `INV-1003` (In process)
- `INV-1004` (Ordered)
- `INV-1005` (Archived in trash)

---

## Setup & Running

1. **Install dependencies:**
   ```bash
   composer install
   ```

2. **Run migrations and seed test data:**
   ```bash
   php artisan migrate:fresh --seed
   ```

3. **Start the local server:**
   ```bash
   php artisan serve
   ```
   Open `http://127.0.0.1:8000` in your browser.

4. **Run tests:**
   ```bash
   php artisan test
   ```
