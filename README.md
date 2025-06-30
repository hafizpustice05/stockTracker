# Store Management System

A **full-featured**, **role-based**, and **production-grade** inventory/requisition management system.

Built with:

- **Laravel 12** (PHP 8.3+)
- **Vue 3** + **Vite**
- **Pinia** (Vue Store)
- Solid OOP + Clean Architecture
- Real-time Notification System (Event Listener + Mail)

---

## 📋 Requirements

| Tool     | Version     |
| -------- | ----------- |
| PHP      | 8.3+        |
| Laravel  | 12.x        |
| Node.js  | 22.x+       |
| Composer | 2.x         |
| NPM      | 8.x or 10.x |
| Database | MySQL 8+    |

---

## 🚀 Getting Started

### 1. Clone the Repository

```bash
git clone https://github.com/hafizpustice05/stockTracker.git
cd stockTracker/stockSystemBackend
```

#### FOR BACKEND

### 2. Install Dependencies

```bash
composer install
```

### 3. Set Up Environment

```bash
cp .env.example .env
php artisan key:generate

```

Need to add mail configaration in .env

### 4. Setup database with seed file

```bash
php artisan migrate --seed

```

After seeding those users created with credential
| Role | Email | Password |
| ------------------ | ----------------------- | ---------- |
| 🛠 Admin | `admin@example.com` | `password` |
| 🏬 Store Executive | `executive@example.com` | `password` |
| 👤 Employee | `employee@example.com` | `password` |

### 5. run the backend

```bash
php artisan serve --port=8000

```

### 5. run queue service

```bash
php artisan queue:work
```

### Or

If we want to use Laravel horizon need to install redis and change `QUEUE_CONNECTION` value is redis

```bash
php artisan horizon

```

To run `php artisan queue:work` or `php artisan horizon` through `Supervisor`, you need to set up a Supervisor configuration file so the process can run in the background and restart on failure. This is the standard and production-ready way to manage queue workers in Laravel.

#### FOR FRONTEND

```bash
cd stockTracker/stockSystemFronend
npm install
```

### Set up environment

```bash
cp .env.example .env
```

### Run Fronend

```bash
npm run serve
```
