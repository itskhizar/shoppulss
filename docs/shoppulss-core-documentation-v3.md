# ShopPulss — Core Project Documentation (v3, Simplified)
**Status:** Finalized for Development
**Philosophy:** Start simple, build it right, extend later.
**Tech Stack:** Laravel 12+, PHP 8.3+, MySQL 8+, Blade + Tailwind CSS
**Primary Market:** Pakistan (PKR) — architecture stays open to future currencies/regions.

> This document replaces earlier complexity with a leaner model based directly on the founder's notes. Nothing here blocks future growth — every module is built so it can be extended (more roles, more payment gateways, more countries) without a rewrite.

---

## TABLE OF CONTENTS
1. [Vision](#1-vision)
2. [User Roles (Simplified)](#2-user-roles-simplified)
3. [Category Module](#3-category-module)
4. [Product Module (Dynamic Attributes)](#4-product-module-dynamic-attributes)
5. [Cart & Checkout](#5-cart--checkout)
6. [Order Lifecycle](#6-order-lifecycle)
7. [Admin Dashboard](#7-admin-dashboard)
8. [Functional Requirements](#8-functional-requirements)
9. [Non-Functional Requirements](#9-non-functional-requirements)
10. [Simplified Database Schema](#10-simplified-database-schema)
11. [File & Module Structure](#11-file--module-structure)
12. [Build Roadmap](#12-build-roadmap)
13. [AI Agent Rules](#13-ai-agent-rules)

---

## 1. VISION

ShopPulss is a **multi-category online store**, built to launch lean and grow into a long-term ecommerce brand. The founding principle, in the founder's own words:

> *"Not too complex — with time we will extend it."*

**What that means in practice:**
- Every feature ships in its simplest correct form first (e.g., one warehouse, one currency, COD + one gateway).
- The database and code are structured so new features (multi-vendor, multi-currency, loyalty points) can be **added**, not bolted on with hacks.
- The admin experience is treated as a first-class product — the person managing categories and products every day should find it fast and self-explanatory, not a chore.

**Long-term direction (not built now, but not blocked either):**
- More payment gateways and couriers
- More admin roles as the team grows
- Reviews, coupons, returns
- Possible multi-vendor mode far down the line

---

## 2. USER ROLES (SIMPLIFIED)

Three roles only, at launch. This directly mirrors the founder's notes.

### 2.1 Guest
- Browses the store, searches, filters.
- **Can place an order and pay without registering or logging in.**
- Receives the order confirmation by email/SMS using the contact info given at checkout.
- Can track that one order later using the order number + phone/email (no account needed).

### 2.2 Purchaser (Customer)
- Same as Guest, plus:
- **Registers and logs in.**
- Gets a **personal dashboard** showing:
  - Full order history
  - Live status of every order
  - Saved addresses
  - Profile settings
- A guest can convert to a Purchaser at any time (their past guest orders can be linked to their account by matching email/phone on signup — a nice trust-building touch, optional to implement in Phase 1).

### 2.3 Admin
- Logs in through a separate `/admin` area.
- **Can create other admin/staff accounts and assign them a role.**
- Has a dashboard to manage *everything*:
  - Categories & subcategories
  - Products & attributes
  - Orders (view, update status, confirm delivery)
  - Customers
  - Staff accounts & their permissions
  - Store settings

**Built-in extensibility:** Even though there's only one full "Admin" role at launch, the permission system (`roles` + `permissions` tables, using **Spatie Laravel-Permission**, a widely-used, well-tested package) is in place from day one. This means adding a limited-access role later (e.g., "Order Packer" who can only see and update orders) is a config change, not a rebuild.

```
roles table (seeded at launch):
  - super-admin   → full access, only role that can create other admins
  - admin         → manage catalog, orders, customers (default for staff Admin creates)

Future roles (add anytime, no code change needed):
  - catalog-manager, order-manager, support-agent, etc.
```

---

## 3. CATEGORY MODULE

Directly from the notes: **Parent category → Subcategories**, with a clean, friendly admin screen.

### 3.1 Structure

```
Parent Category          Subcategories
─────────────────────────────────────────
Electronics         →    Mobile Phones, Watches, Headphones...
Fashion              →   Men's Clothing, Shoes, Watches...
Home & Living        →   Kitchen, Bedding, Décor...
```

- Two levels is enough for launch (Parent → Sub). The `categories` table supports unlimited nesting via `parent_id` (self-referential), so a third level (e.g., Mobile Phones → Smartphones → Android) can be turned on later just by adding rows — no schema change.

### 3.2 Admin UI/UX for Categories

Best-practice, friendly design (per the sketch in your notes — a two-panel "Category / Subcategory" view):

```
┌─────────────────────────────────────────────┐
│  Categories                    [+ Add Category] │
├───────────────────┬───────────────────────────┤
│  PARENT CATEGORIES │  SUBCATEGORIES (of selected)│
│                    │                            │
│  ▸ Electronics  ●  │   Mobile Phones      [Edit] │
│    Fashion         │   Watches            [Edit] │
│    Home & Living   │   Headphones         [Edit] │
│    Beauty          │   [+ Add Subcategory]       │
│                    │                            │
└───────────────────┴───────────────────────────┘
```

**Behavior:**
- Click a parent category on the left → its subcategories load on the right (no page reload — simple Livewire or Alpine.js interaction).
- "Add Category" opens a small form: Name, Slug (auto-generated, editable), Image, Display Order, Status (Active/Inactive).
- Drag-to-reorder is a nice-to-have for Phase 2, not required at launch.
- Deleting a category with existing products is blocked — admin must reassign products first (prevents orphaned data).

### 3.3 Category Fields

| Field | Required | Notes |
|---|---|---|
| Name | Yes | e.g., "Mobile Phones" |
| Slug | Auto | URL-friendly, editable |
| Parent | No | Null = top-level category |
| Image | No | Shown on homepage/category cards |
| Description | No | For SEO/category page |
| Display Order | No | Controls sort order in menus |
| Status | Yes | Active / Inactive |

---

## 4. PRODUCT MODULE (DYNAMIC ATTRIBUTES)

This is the most important simplification from your notes, and it's the right call: **products in different categories need different fields, and those fields should be dynamic, not hardcoded.**

### 4.1 The Core Idea

> *"iPhone 15 Max → quantity or single, because each phone has its own specification. Then: more attributes/fields — color, display, weight, size, etc. These fields are dynamic based on product/category."*

**Translation into a simple, scalable system:**

1. Every product belongs to a **Category** (e.g., Electronics → Mobile Phones).
2. Each category can have a set of **Attributes** attached to it (e.g., Mobile Phones → Color, Storage, RAM, Display Size; Shoes → Color, Size, Material).
3. When an admin adds a product in a category, the form **automatically shows the relevant attribute fields** for that category.
4. Admin decides per-product whether it's sold as a **simple product** (one price, one stock count — e.g., a single-spec accessory) or a **variant product** (multiple buyable combinations — e.g., iPhone in Black/128GB vs Silver/256GB, each with its own price and stock).

### 4.2 How It Works (Plain English)

```
Step 1: Admin creates Attribute "Color" with values: Black, White, Blue, Gold
Step 2: Admin creates Attribute "Storage" with values: 128GB, 256GB, 512GB
Step 3: Admin links "Color" and "Storage" to the "Mobile Phones" category

Step 4: Admin adds product "iPhone 15 Pro Max" under Mobile Phones
        → Form shows Color and Storage as selectable options (because
          they're linked to this category)
        → Admin picks which values apply to this product: 
          Color: Black, Blue | Storage: 128GB, 256GB
        → System generates the combinations (variants):
          Black + 128GB, Black + 256GB, Blue + 128GB, Blue + 256GB
        → Admin sets price & stock for each combination

Step 5: On the product page, customer picks Color → picks Storage →
        sees the exact price and stock for that exact combination
```

For a product that doesn't need variants (e.g., a single-spec phone case), the admin simply skips variant creation and sets one price/stock on the product itself — **simple products stay simple.**

### 4.3 Product Types

| Type | When to use | Example |
|---|---|---|
| **Simple** | One price, one stock count, no options | Phone case, power bank |
| **Variant** | Multiple buyable combinations | iPhone (color × storage), Shoes (color × size) |

### 4.4 Product Fields (Core)

| Field | Notes |
|---|---|
| Name, Slug, SKU | Standard |
| Category | Required — drives which attributes are available |
| Description (short + full) | |
| Images | Multiple, one marked featured |
| Price / Sale Price | On product (simple) or per variant |
| Stock Quantity | On product (simple) or per variant |
| Dynamic Attributes | Auto-loaded based on category |
| Status | Draft / Published / Archived |

### 4.5 Why This Design Is the Right Level of Simplicity

- Admin never has to see irrelevant fields (a watch product doesn't show "RAM").
- New product types (e.g., adding "Furniture" category) just need attributes created once — no code changes.
- Keeps the database clean: one `attributes` + `attribute_values` + `product_variants` structure handles *every* category, instead of a custom table per category.

---

## 5. CART & CHECKOUT

Kept intentionally simple for launch:

- **Guest cart:** stored in session, works without login.
- **Purchaser cart:** stored in database, persists across devices, merges with any guest cart on login.
- **Checkout steps (single page, not multi-step wizard, to reduce friction):**
  1. Contact info (name, phone, email)
  2. Delivery address
  3. Payment method (Cash on Delivery to start; Bank Transfer / EasyPaisa added once merchant accounts are ready)
  4. Review & Place Order
- Stock is re-checked at the moment of order placement — never trust what was shown when the item was added to cart minutes/hours earlier.

---

## 6. ORDER LIFECYCLE

Directly from your notes: **"Orders need to be properly tracked with status until reviewed & delivery confirmed."**

### 6.1 Status Flow

```
Pending            → Order placed, awaiting confirmation
   ↓
Confirmed          → Admin has reviewed and accepted the order
   ↓
Processing         → Being packed / prepared
   ↓
Shipped            → Handed to courier, tracking info added
   ↓
Delivered          → Confirmed received by customer (final happy path)

Side branches (from any stage before Delivered):
   → Cancelled      → Order cancelled (by admin or customer request)
   → Return Requested → Customer requests return (Phase 2)
```

**Key rule from your notes — "reviewed & delivery confirmed" — is enforced by:**
- No order skips a status silently. Every change is logged (`order_status_histories` table: old status, new status, who changed it, when, optional reason).
- "Delivered" is only set once — either by admin confirmation or (later) automatically via courier tracking webhook. It is the definitive end of the active order lifecycle and it's timestamped.
- Admin dashboard always shows **Pending** and **Confirmed** orders at the top — these need action first.

### 6.2 Order Fields

| Field | Notes |
|---|---|
| Order Number | Unique, human-readable (e.g., `SP-20260927-0001`) |
| Customer info | Name, phone, email (works for guest or purchaser) |
| Items | Snapshot of product name, price, quantity at time of order (never recalculated from live product data later) |
| Delivery address | |
| Status | Per lifecycle above |
| Status history | Full audit trail |
| Payment method & status | Separate from order status (an order can be "Processing" while payment is still "Pending" for COD) |

---

## 7. ADMIN DASHBOARD

A single, clean dashboard covering everything the admin needs day-to-day:

```
┌─────────────────────────────────────────────┐
│  Dashboard                                    │
│  ─────────────                                │
│  Today's Orders: 12   Pending Review: 4       │
│  Low Stock Items: 3   Revenue Today: Rs 45,200│
│                                                │
│  [Recent Orders — needs action first]         │
│  [Low Stock Alert list]                       │
└─────────────────────────────────────────────┘

Sidebar:
  📊 Dashboard
  📦 Products         (list, add, edit, attributes)
  🗂  Categories        (parent/sub manager)
  🛒 Orders            (list, filter by status, update status)
  👥 Customers         (view purchaser accounts & their orders)
  🔑 Staff & Roles      (Admin only — add/manage other admin accounts)
  ⚙️  Settings          (store info, shipping, payment methods)
```

**Design principle:** every screen answers "what needs my attention right now?" first (pending orders, low stock), then supports deeper management below.

---

## 8. FUNCTIONAL REQUIREMENTS

### 8.1 Authentication
- Guest checkout (no account required)
- Purchaser registration/login (email + password)
- Password reset via email
- Admin login (separate guard, separate `/admin` route group)
- Admin can create additional staff/admin accounts with a role

### 8.2 Catalog
- Parent/subcategory management (admin UI described in Section 3)
- Product creation — simple or variant type
- Dynamic attributes per category (Section 4)
- Stock tracking per product or per variant
- Product listing with filter (by category, price, attribute) and sort

### 8.3 Shopping
- Add to cart (guest or purchaser)
- Cart merge on login
- Single-page checkout
- Order placement with stock re-validation
- Order confirmation (email)

### 8.4 Orders
- Full status lifecycle (Section 6)
- Status history/audit trail
- Purchaser order history dashboard
- Guest order tracking by order number + phone/email
- Admin order management (filter, update status, add notes)

### 8.5 Admin
- Dashboard with key metrics (today's orders, pending, low stock, revenue)
- Category management
- Product management
- Order management
- Customer list (read access to purchaser accounts)
- Staff/role management

---

## 9. NON-FUNCTIONAL REQUIREMENTS

Kept practical for a launch-stage store — enterprise-grade complexity (read replicas, multi-region, microservices) is deliberately **not** in scope now, but nothing here prevents adding it later.

| Area | Requirement |
|---|---|
| **Performance** | Pages load under 3s on typical mobile connection. Images optimized and lazy-loaded. |
| **Security** | HTTPS everywhere, CSRF protection (Laravel default), hashed passwords, admin routes protected by middleware, rate-limiting on login/checkout. |
| **Reliability** | Order creation wrapped in a database transaction — an order is never left half-created. Daily database backup. |
| **Usability** | Mobile-first responsive design. Guest checkout always available — never force registration. |
| **Maintainability** | Clean, modular Laravel structure (Section 11). PSR-12 coding style. Meaningful naming. |
| **Scalability path** | Single server + MySQL + Redis is enough for launch. Caching and queueing (via Redis) built in from day one so growth doesn't require a rewrite — just more server resources. |

---

## 10. SIMPLIFIED DATABASE SCHEMA

Core tables only — enough for everything in this document, structured to extend cleanly.

```sql
-- Users & Roles (Spatie Laravel-Permission handles roles/permissions tables)
users (id, name, email, phone, password, email_verified_at, timestamps)
roles (id, name)              -- super-admin, admin, (future: catalog-manager, etc.)
model_has_roles (role_id, model_id, model_type)

-- Addresses
addresses (id, user_id NULLABLE, full_name, phone, city, area, street_address, is_default, timestamps)
-- user_id nullable so guest addresses can be stored against the order only

-- Categories
categories (id, parent_id NULLABLE, name, slug, image, description, display_order, status, timestamps)

-- Attributes (dynamic product fields)
attributes (id, name, type ENUM('select','text','color'), timestamps)          -- e.g., Color, Storage
attribute_values (id, attribute_id, value, timestamps)                          -- e.g., Black, 128GB
category_attributes (category_id, attribute_id)                                -- which attributes apply to which category

-- Products
products (id, category_id, name, slug, sku, description, type ENUM('simple','variant'),
          price, sale_price, stock_quantity, status ENUM('draft','published','archived'), timestamps)
product_images (id, product_id, image_url, display_order, is_featured)
product_variants (id, product_id, sku, price, sale_price, stock_quantity, image, timestamps)
product_variant_attribute_values (product_variant_id, attribute_value_id)       -- links variant to its Color/Storage combo

-- Cart
carts (id, user_id NULLABLE, session_id NULLABLE, timestamps)
cart_items (id, cart_id, product_id, product_variant_id NULLABLE, quantity, unit_price, timestamps)

-- Orders
orders (id, order_number, user_id NULLABLE, customer_name, customer_phone, customer_email,
        delivery_address_id, status, payment_method, payment_status,
        subtotal, shipping_amount, total_amount, timestamps)
order_items (id, order_id, product_name, variant_details JSON, sku, unit_price, quantity, total_price)
order_status_histories (id, order_id, from_status, to_status, changed_by, reason, created_at)

-- Settings (store-level config, key-value)
settings (id, `key`, value, timestamps)
```

**Why this is enough:** every requirement in Sections 2–8 maps to a table above. Nothing is speculative — no empty tables "for later." When Phase 2 features (reviews, coupons, returns) are needed, new tables are added; existing ones don't change shape.

---

## 11. FILE & MODULE STRUCTURE

Simple, modular, Laravel-idiomatic — no over-engineering. Domain folders group business logic; Laravel's own structure handles the rest.

```
app/
├── Models/
│   ├── User.php, Category.php, Product.php, ProductVariant.php
│   ├── Attribute.php, AttributeValue.php
│   ├── Cart.php, CartItem.php
│   ├── Order.php, OrderItem.php, OrderStatusHistory.php
│   └── Address.php, Setting.php
│
├── Domains/                          # Business logic, grouped by feature
│   ├── Catalog/
│   │   ├── Services/ProductService.php
│   │   ├── Services/CategoryService.php
│   │   └── Services/AttributeService.php
│   ├── Cart/
│   │   └── Services/CartService.php
│   ├── Checkout/
│   │   └── Services/CheckoutService.php
│   └── Orders/
│       └── Services/OrderService.php
│
├── Http/
│   ├── Controllers/
│   │   ├── Storefront/  (Home, Shop, Product, Cart, Checkout, TrackOrder)
│   │   ├── Auth/        (Register, Login, PasswordReset)
│   │   └── Admin/       (Dashboard, Category, Product, Order, Staff)
│   ├── Requests/         (Form validation classes)
│   └── Middleware/       (IsAdmin, etc.)
│
└── Policies/              (ProductPolicy, OrderPolicy — who can do what)

resources/views/
├── layouts/  (app.blade.php, admin.blade.php)
├── storefront/  (home, shop, category, product, cart, checkout)
├── admin/       (dashboard, categories, products, orders, staff)
└── components/  (product-card, navbar, footer, category-manager)

database/
├── migrations/   (one file per table above)
└── seeders/      (roles, sample categories, sample products)
```

**Rule of thumb for extending later:** new feature = new folder under `Domains/`, new migration, new controller. Existing modules are never modified just to bolt something on.

---

## 12. BUILD ROADMAP

### Phase 1 — Launch (this build)
- Categories (parent/sub) + admin UI
- Products (simple + variant) with dynamic attributes
- Guest + Purchaser accounts
- Cart, single-page checkout
- Orders with full status lifecycle
- Admin dashboard (categories, products, orders, staff)
- COD payment (Bank Transfer / EasyPaisa as soon as merchant accounts are ready)

### Phase 2 — Growth
- Reviews & ratings
- Coupons/discounts
- Returns workflow
- SMS/WhatsApp order updates
- Additional payment gateways (JazzCash)

### Phase 3 — Scale
- Multi-currency
- Multiple admin roles with fine-grained permissions
- Advanced search
- Possible multi-vendor mode

---

## 13. AI AGENT RULES

When generating code for this project:

1. **Follow the schema in Section 10 exactly** — don't add speculative tables/columns not listed here or in an approved Phase 2/3 addition.
2. **Keep controllers thin** — business logic goes in `Domains/*/Services/`.
3. **Never trust frontend price/stock values** — always recalculate from the database at checkout and order placement.
4. **Category → Attribute → Product flow must stay dynamic** — never hardcode attribute fields per category in a Blade template; pull them from `category_attributes`.
5. **Every order status change must be logged** to `order_status_histories` — no silent updates.
6. **Guest orders must work fully without an account** — don't require `user_id` anywhere in the checkout flow.
7. **Use Spatie Laravel-Permission** for roles rather than a hand-rolled role column — it's the standard, well-supported approach and keeps the door open for more roles later.
8. **Write a test for every service method**, especially `OrderService` (status transitions) and `CheckoutService` (stock validation, total calculation).

---

## SUMMARY

This is intentionally a **smaller, sharper** document than a full enterprise spec. It captures exactly what you described:

✅ Guest checkout, no forced registration
✅ Purchaser dashboard with order history
✅ Admin who can create other admin/staff roles
✅ Friendly parent/subcategory manager
✅ Dynamic, category-driven product attributes (the iPhone/color/storage example)
✅ Orders tracked through a clear status lifecycle until delivery is confirmed
✅ Simple now, but every piece (roles, attributes, schema) is built to extend without rewriting

**This document is the source of truth going forward — the earlier, more elaborate documentation can be treated as a Phase 2/3 reference for features not yet needed.**
