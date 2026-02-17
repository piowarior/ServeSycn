# 🧠 Tools

> **Laravel 12 + React + REST API + Realtime + SSH + Server Production**

---

# 🧠 Arsitektur Sistem Global

```
React (Kasir + Dapur + Admin)
        ↓ REST API
Laravel 12 Backend (API Server)
        ↓
   MySQL / MariaDB
        ↓
   Realtime Server (WebSocket)
```

---

# 🗃️ Struktur Database (Professional & Scalable)

## 1️⃣ users

```sql
id
name
email
password
role ENUM('admin','kasir','dapur')
created_at
updated_at
```

---

## 2️⃣ categories

```sql
id
name
slug
created_at
updated_at
```

---

## 3️⃣ products

```sql
id
category_id (FK)
name
price
stock
image
status ENUM('active','inactive')
created_at
updated_at
```

---

## 4️⃣ orders

```sql
id
invoice_number
total_price
status ENUM('pending','processing','ready','done','cancel')
payment_status ENUM('unpaid','paid')
created_at
updated_at
```

---

## 5️⃣ order_items

```sql
id
order_id (FK)
product_id (FK)
qty
price
subtotal
note
```

---

## 6️⃣ payments

```sql
id
order_id (FK)
method ENUM('cash','qris','transfer','ewallet')
amount
paid_at
```

---

## 7️⃣ activity_logs (audit trail)

```sql
id
user_id
activity
ip_address
created_at
```

---

# 🔗 Relasi Database (ERD Singkat)

```
users 1---* orders
orders 1---* order_items
products 1---* order_items
orders 1---1 payments
categories 1---* products
```

---

# 🧩 RESTful API Structure (Laravel 12)

## Auth

```
POST   /api/login
POST   /api/logout
GET    /api/profile
```

---

## Products

```
GET    /api/products
POST   /api/products
PUT    /api/products/{id}
DELETE /api/products/{id}
```

---

## Orders

```
POST   /api/orders
GET    /api/orders
GET    /api/orders/{id}
PUT    /api/orders/{id}/status
```

---

## Kitchen

```
GET /api/kitchen/orders
PUT /api/kitchen/orders/{id}/process
PUT /api/kitchen/orders/{id}/done
```

---

## Payments

```
POST /api/payments
```

---

# ⚡ Realtime System (Laravel Reverb / Pusher)

## Flow Realtime:

```
Kasir buat order → Backend → Broadcast → Dapur auto update
```

## Teknologi:

* Laravel Reverb (realtime native Laravel 12)
* Atau Pusher
* Atau Socket.io

---

# 🔌 Realtime Event Flow

### Event:

```
OrderCreated
OrderStatusUpdated
```

### Listener:

* Kasir
* Dapur
* Admin

---

# 🔐 Authentication System

* Laravel Sanctum (API Token)
* Role-based access

```
Admin → all access
Kasir → orders + payments
Dapur → orders view + update status
```

---

# 🧱 Struktur Project Laravel 12 (API Mode)

```
app/
 ├── Http/
 │    ├── Controllers/
 │    │      ├── AuthController.php
 │    │      ├── ProductController.php
 │    │      ├── OrderController.php
 │    │      ├── KitchenController.php
 │    │      └── PaymentController.php
 │
 ├── Models/
 │    ├── User.php
 │    ├── Product.php
 │    ├── Order.php
 │    ├── OrderItem.php
 │    └── Payment.php
 │
 ├── Events/
 │    ├── OrderCreated.php
 │    └── OrderStatusUpdated.php
```

---

# ⚛️ Struktur React App

```
src/
 ├── pages/
 │    ├── Login.jsx
 │    ├── Kasir.jsx
 │    ├── Dapur.jsx
 │    └── Admin.jsx
 │
 ├── services/
 │    └── api.js
 │
 ├── components/
 │    ├── OrderList.jsx
 │    └── ProductCard.jsx
```

---

# 🔑 Realtime React Setup

Gunakan:

* Laravel Echo
* Pusher JS / Reverb

---

# 🌍 Deployment (Server + SSH + Docker)

## Server:

* Ubuntu 22.04
* Nginx
* PHP 8.3
* MySQL 8
* Redis

---

## SSH Flow:

```
Local → SSH → VPS
```

---

# 🐳 Docker Setup (Production Ready)

Service:

* nginx
* php-fpm
* mysql
* redis

---

# 🔐 Security Layer

* HTTPS (SSL)
* API Rate Limit
* Firewall
* Fail2Ban
* Env encryption

---

# 🚀 Development Roadmap

| Phase   | Fokus                |
| ------- | -------------------- |
| Phase 1 | Database + Auth      |
| Phase 2 | Kasir API            |
| Phase 3 | Dapur API + Realtime |
| Phase 4 | Admin Dashboard      |
| Phase 5 | Deployment + SSL     |

---
