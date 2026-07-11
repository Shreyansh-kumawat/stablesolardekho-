# CP (Channel Partner) Module — Stable Solar Dekho

## Overview
Complete Channel Partner management system integrated into the admin Ecommerce section.
Flow: User applies via CP Interest form → Admin approves → User becomes Channel Partner → CP can place orders, manage inventory.

---

## Step 1: CP Interest Requests

### Public Form (`/CpInterest`)
- **Login required** — `auth` middleware, redirects to login if not authenticated
- **Auto-fill** — name, email, mobile, state, city pulled from `auth()->user()`
- **Email locked** — readonly field with "(verified)" label, cannot be changed
- **Other fields editable** — company name, contact person, mobile, state, city, message
- **Stores `user_id`** — links request to the logged-in user account

### Admin Panel (`/admin/cp-interest-list`)
- DataTable with columns: #, Company, Contact, Email, Mobile, City, Date, Status, Action
- Status badges: Pending (yellow), Approved (green), Rejected (red)
- Pending/Approved count badges in header

### Approve Flow
On approval, automatically:
1. Creates `ChannelPartner` record (cp_name, contact_person, email, phone, city, state)
2. Updates user's `role_id` → 4 (channel_partner)
3. Sets `cp_id` on user → links to new ChannelPartner
4. Assigns default `cp_permissions`: `new_request`, `view_requests`, `product_pricing`, `view_inventory`

### Reject Flow
- Sets `status` → `rejected` on the interest record

### Files
- Route: `GET /CpInterest`, `POST /QueryCpInterest` (public, auth middleware)
- Route: `GET /admin/cp-interest-list`, `POST /admin/cp-interest/{id}/approve`, `POST /admin/cp-interest/{id}/reject`
- Controller: `UserController@CpInterest`, `QueryCpInterest`, `cpInterestList`, `approveCpInterest`, `rejectCpInterest`
- View: `publicPages/channelPartnerEnrollment.blade.php`
- Admin View: `Admin/cpSetting/cpInterestList.blade.php`
- Model: `CpInterest` (table: `cp_interests`)
- Migration: `2026_07_10_202924_add_user_id_to_cp_interests_table.php`

---

## Step 2: CP Partners Management

### CP List (`/admin/cpList`)
- DataTable: Company Name, Contact Person, Email, Mobile, City, CP Type, Users count, Wallet Balance, Status, Action
- **View** button → CP Detail page
- **Edit** button → Edit CP form
- **Delete** button → AJAX POST, reverts associated users to normal (role_id=3, cp_id=null)
- **Status toggle** — Active/Inactive badge, click to toggle `is_active`
- Success messages displayed
- Export: Excel, CSV, Print

### Add New CP (`/admin/addNewCp`)
- Form: Company Name, Contact Person, Email, Mobile, CP Role (Select2), State, City, Full Address, Pin Code
- Redirects to CP List on success

### Edit CP (`/admin/edit_cp/{id}`)
- Same form as Add, pre-filled with existing data
- Cancel button goes to CP List

### Delete CP (`POST /admin/cp/{id}/delete`)
- Deletes ChannelPartner record
- Reverts all associated users: `role_id` → 3, `cp_id` → null, `cp_permissions` → null

### Toggle Status (`POST /admin/cp/{id}/toggle-status`)
- Toggles `is_active` on ChannelPartner

### Files
- Controller: `UserController@cpList`, `addNewCp`, `storeNewCp`, `edit_cp`, `editCpQuery`, `deleteCp`, `toggleCpStatus`
- Views: `Admin/cpSetting/cpList.blade.php`, `addNewCp.blade.php`, `editCp.blade.php`
- Model: `ChannelPartner` (table: `channel_partners`)

---

## Step 3: CP Partner Detail Page

### Detail View (`/admin/cp/{id}/detail`)
- **Profile card**: Name, role badge, active/inactive status, contact person, email, phone, city, state, address, pin code, joined date, edit button
- **4 stat cards**: Wallet Balance, Total Orders (with pending count), Associated Users, Inventory Items
- **3 tabs** (vanilla JS):
  - **Orders**: Order ID, date, product count, grand total, status badge
  - **Wallet**: Date, txn ID, type (credit/debit), amount, opening/closing balance, source, remarks
  - **Users**: Name, email, mobile, permissions (as chips), joined date

### Files
- Controller: `UserController@cpDetail`
- View: `Admin/cpSetting/cpDetail.blade.php`

---

## Step 4: CP Orders in Ecommerce

### Already existing — added to Ecommerce sidebar

### Pending Orders (`/admin/pendingOrders`)
- Lists all pending CP inventory requests
- Approve/Cancel with admin remarks

### All Orders (`/admin/manageOrdersAdmin`)
- Lists all CP orders (all statuses)

### View Single Order (`/admin/viewSingleOrder/{id}`)
- Detailed order view with pricing, products, quote generation

### Approve/Cancel
- `POST /admin/inventory-request/{id}/approve` — sets status=completed, saves admin_remarks
- `POST /admin/inventory-request/{id}/cancel` — sets status=cancelled, saves admin_remarks
- `POST /admin/saveOrderPricing` — saves product pricing, quote amount, quote date, marks as completed/rejected

### Files
- Controller: `OrderController@pendingOrders`, `manageOrdersAdmin`, `viewSingleOrder`, `saveOrderPricing`, `approveInventoryRequest`, `cancelInventoryRequest`
- Views: `Admin/orders/pendingOrders.blade.php`, `manageOrderAdmin.blade.php`, `viewSIngleOrderAdmin.blade.php`
- Model: `CpOrder` (table: `cp_orders`)

---

## CP Permissions (RBAC)

### Admin Page (`/admin/cp-permissions`)
- Lists all CP users with their current permissions
- Toggle individual permissions per user

### 7 Permissions
| Permission Key | Controls |
|---|---|
| `new_request` | Submit new inventory requests |
| `view_requests` | View order history |
| `product_pricing` | View product pricing page |
| `view_inventory` | View inventory stock |
| `transfer_inventory` | Transfer inventory between warehouses |
| `inventory_transactions` | View inventory transaction logs |
| `manual_installation` | Access manual installation feature |

### How it works
- Stored as JSON array in `users.cp_permissions`
- `ChannelPartnerMiddleware` checks permission: `Route::middleware('channel_partner:new_request')`
- Sidebar items show/hide based on permissions via `@if(in_array('permission_key', ...))`

### Files
- Controller: `UserController@manageCpPermissions`, `updateCpPermissions`
- View: `Admin/cpSetting/cpPermissions.blade.php`
- Middleware: `ChannelPartnerMiddleware`

---

## Ecommerce Sidebar Menu Items
All under Ecommerce section in admin sidebar (`menuEcommercePartials.blade.php`):
1. Home Banners
2. Products
3. Categories
4. Orders (Customer)
5. Customers
6. Secondary Admin (master_admin only)
7. **CP Interest Requests** ← new
8. **CP Partners** ← new
9. **CP Orders** ← new
10. View Shop

---

## Database Tables

| Table | Key Columns |
|---|---|
| `cp_interests` | id, user_id, company_name, contact_person, email, mobile, state, city, message, status |
| `channel_partners` | id, cp_name, contact_person, email, phone_number, full_address, city, state, zip_code, cp_role, is_active |
| `channel_partner_roles` | id, role_name |
| `cp_orders` | id, cp_id, order_id, products (JSON), status, order_date, quote_amount, quote_date, admin_remarks |
| `cp_wallets` | id, cp_id, balance |
| `cp_wallet_transactions` | id, cp_id, amount, transaction_type, opening_balance, closing_balance, txn_id, source, remarks |
| `cp_product_inventories` | id, cp_id, product_id, available_qty |
| `users` | role_id (4=CP), cp_id (FK→channel_partners), cp_permissions (JSON) |

---

## Production Deployment SQL

Run these in phpMyAdmin on `u137518836_stableSolar1`:

```sql
-- Add user_id to cp_interests (for linking interest to user account)
ALTER TABLE cp_interests ADD COLUMN user_id BIGINT UNSIGNED NULL AFTER id;
ALTER TABLE cp_interests ADD CONSTRAINT fk_cp_interests_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL;

-- Verify cp_permissions column exists on users (should already be there)
-- ALTER TABLE users ADD COLUMN cp_permissions JSON NULL AFTER admin_permissions;
```

After SQL, on Hostinger SSH:
```bash
ssh -p 65002 u137518836@46.202.161.165
cd domains/stablesolardekho.com/public_html
git pull
php artisan view:clear
php artisan route:clear
php artisan cache:clear
php artisan config:clear
```

---

## Role System Reference
| role_id | Name | Description |
|---|---|---|
| 1 | master_admin | Full admin access |
| 2 | secondary_admin | Limited admin (permission-based) |
| 3 | user | Normal customer |
| 4 | channel_partner | CP user (permission-based sidebar) |
