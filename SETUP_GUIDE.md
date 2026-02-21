# TaskFlow - Complete Setup Guide

> A Laravel 12 Task Management System with Redis, Pusher, Vue.js & Tailwind CSS

---

## Table of Contents

1. [Prerequisites](#1-prerequisites)
2. [Install & Enable Redis on Windows](#2-install--enable-redis-on-windows)
3. [Setup Pusher Account (Free)](#3-setup-pusher-account-free)
4. [Configure the Project](#4-configure-the-project)
5. [Run the Application](#5-run-the-application)
6. [Demo Login Credentials](#6-demo-login-credentials)
7. [Project Architecture Overview](#7-project-architecture-overview)
8. [What Each Technology Does](#8-what-each-technology-does)
9. [Key Files to Study](#9-key-files-to-study)
10. [Common Issues & Fixes](#10-common-issues--fixes)
11. [Learning Exercises](#11-learning-exercises)

---

## 1. Prerequisites

Make sure you have these installed:

| Tool     | Version  | Check Command          |
| -------- | -------- | ---------------------- |
| PHP      | >= 8.2   | `php -v`               |
| Composer | >= 2.x   | `composer -V`          |
| Node.js  | >= 18.x  | `node -v`              |
| NPM      | >= 9.x   | `npm -v`               |
| MySQL    | >= 8.x   | `mysql --version`      |
| XAMPP    | Latest   | (You already have this) |

---

## 2. Install & Enable Redis on Windows

Redis doesn't officially support Windows, but you have **3 options**:

### Option A: Memurai (Recommended for Windows - Easiest)

1. Download Memurai from: https://www.memurai.com/get-memurai
2. Install it (just click Next, Next, Finish)
3. It runs automatically as a Windows service on port `6379`
4. **That's it!** Redis is now available

### Option B: Redis via WSL (Windows Subsystem for Linux)

If you have WSL installed:

```bash
# In WSL terminal:
sudo apt update
sudo apt install redis-server
sudo service redis-server start

# Test it:
redis-cli ping
# Should respond: PONG
```

### Option C: Redis for Windows (Community Build)

1. Download from: https://github.com/tporadowski/redis/releases
2. Download the `.msi` installer (e.g., `Redis-x64-5.0.14.1.msi`)
3. Install and it will run as a Windows service
4. Default port: `6379`

### Verify Redis is Running

```bash
# If using Memurai or Redis for Windows, open CMD:
redis-cli ping
# Expected response: PONG

# Or test from PHP:
php artisan tinker
>>> Illuminate\Support\Facades\Redis::ping()
# Expected: "PONG" or true
```

### What if I can't install Redis?

No worries! Change these in your `.env` file to use the database instead:

```env
CACHE_STORE=database
SESSION_DRIVER=database
QUEUE_CONNECTION=database
```

The app will still work, but the Redis Demo page won't function. Everything else (tasks, auth, Vue components) will work fine.

---

## 3. Setup Pusher Account (Free)

Pusher provides real-time WebSocket communication. The free tier is generous (200K messages/day).

### Step-by-Step:

1. Go to https://pusher.com and click **Sign Up** (free)
2. After logging in, click **"Create app"** (or "Channels" → "Create app")
3. Give your app a name: `taskflow`
4. Select your nearest cluster (e.g., `ap2` for Asia, `us2` for US, `eu` for Europe)
5. Click **Create**
6. Go to **App Keys** tab
7. You'll see:
   - `app_id` → e.g., `1234567`
   - `key` → e.g., `abc123def456`
   - `secret` → e.g., `xyz789secret`
   - `cluster` → e.g., `ap2`

### Update your `.env` file with these values:

```env
PUSHER_APP_ID=1234567
PUSHER_APP_KEY=abc123def456
PUSHER_APP_SECRET=xyz789secret
PUSHER_APP_CLUSTER=ap2

VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```

### What if I skip Pusher?

The app still works perfectly! You just won't get real-time toast notifications when tasks are created/updated. All CRUD operations, Redis caching, and Vue components will still work.

---

## 4. Configure the Project

### 4.1 Create MySQL Database

Open phpMyAdmin (http://localhost/phpmyadmin) or MySQL CLI:

```sql
CREATE DATABASE laravel_redis;
```

### 4.2 Install Dependencies (if not already done)

```bash
# PHP dependencies
composer install

# JavaScript dependencies
npm install
```

### 4.3 Update `.env` File

Open the `.env` file and verify/update these settings:

```env
# App
APP_NAME="TaskFlow"
APP_URL=http://localhost:8000

# Database (XAMPP defaults)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_redis
DB_USERNAME=root
DB_PASSWORD=

# Redis (keep as-is if Redis is installed)
REDIS_CLIENT=predis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Pusher (replace with your real keys from Step 3)
BROADCAST_CONNECTION=pusher
PUSHER_APP_ID=your-pusher-app-id
PUSHER_APP_KEY=your-pusher-app-key
PUSHER_APP_SECRET=your-pusher-app-secret
PUSHER_APP_CLUSTER=ap2
```

### 4.4 Run Migrations & Seed Demo Data

```bash
php artisan migrate:fresh --seed
```

This creates:
- 2 demo users
- 6 categories (Work, Personal, Shopping, Health, Learning, Finance)
- 18 sample tasks with various statuses and priorities

### 4.5 Build Frontend Assets

```bash
# For development (with hot reload):
npm run dev

# For production build:
npm run build
```

---

## 5. Run the Application

You need **3 terminals** open for full functionality:

### Terminal 1: Laravel Server
```bash
php artisan serve
```
App available at: **http://localhost:8000**

### Terminal 2: Vite Dev Server (for hot reload during development)
```bash
npm run dev
```
This enables live CSS/JS reloading when you edit files.

### Terminal 3: Queue Worker (for broadcasting events)
```bash
php artisan queue:listen
```
This processes queued broadcast events (real-time notifications).

### Quick Start (Production Mode)
If you just want to see the app without development features:
```bash
npm run build
php artisan serve
```
Then visit http://localhost:8000

---

## 6. Demo Login Credentials

| User       | Email               | Password   |
| ---------- | ------------------- | ---------- |
| Demo User  | demo@taskflow.com   | `password` |
| Jane Smith | jane@taskflow.com   | `password` |

---

## 7. Project Architecture Overview

```
laravel_redis/
├── app/
│   ├── Events/                    ← Broadcast events (real-time)
│   │   ├── TaskCreated.php        ← Fired when task is created
│   │   └── TaskStatusChanged.php  ← Fired when status changes
│   ├── Http/Controllers/
│   │   ├── AuthController.php     ← Login, Register, Logout
│   │   ├── DashboardController.php← Dashboard with Redis caching
│   │   ├── TaskController.php     ← Full CRUD + API endpoint
│   │   ├── CategoryController.php ← Category management
│   │   └── RedisDemoController.php← Interactive Redis playground
│   └── Models/
│       ├── User.php               ← User with task relationships
│       ├── Task.php               ← Core model with scopes & helpers
│       └── Category.php           ← Category with auto-slug
├── resources/
│   ├── js/
│   │   ├── app.js                 ← Vue 3 app initialization
│   │   ├── bootstrap.js           ← Axios + Laravel Echo setup
│   │   └── components/
│   │       ├── TaskBoard.vue      ← Interactive task list with filters
│   │       ├── NotificationToast.vue← Real-time notifications
│   │       ├── RedisDemo.vue      ← Redis playground UI
│   │       └── StatsCards.vue     ← Animated dashboard cards
│   └── views/
│       ├── layouts/app.blade.php  ← Main layout (nav, footer)
│       ├── auth/                  ← Login & Register pages
│       ├── dashboard.blade.php    ← Dashboard page
│       ├── tasks/                 ← Task CRUD views
│       ├── categories/            ← Category management
│       └── redis/demo.blade.php   ← Redis demo page
├── routes/
│   ├── web.php                    ← Web routes (31 routes)
│   ├── api.php                    ← API route for Vue AJAX
│   └── channels.php              ← Broadcast channel auth
├── database/
│   ├── migrations/                ← Database schema
│   ├── factories/                 ← Test data generators
│   └── seeders/                   ← Demo data seeders
└── config/                        ← Laravel configuration
```

---

## 8. What Each Technology Does

### Redis (Cache, Session, Queue)
- **What**: An in-memory data store (like a super-fast database that lives in RAM)
- **Where used**:
  - `DashboardController.php` → Caches dashboard stats for 5 minutes instead of querying DB every time
  - `TaskController.php` → Caches task lists, clears cache when tasks change
  - `RedisDemoController.php` → Interactive demos of Redis data types (strings, lists, hashes, counters)
  - `Session` → User login sessions stored in Redis (faster than file/database)
  - `Queue` → Background job processing via Redis queues
- **Why**: Makes the app much faster by avoiding repeated database queries

### Pusher (Real-time Broadcasting)
- **What**: WebSocket service that pushes events from server to browser instantly
- **Where used**:
  - `TaskCreated.php` event → Broadcasts when someone creates a task
  - `TaskStatusChanged.php` event → Broadcasts when task status changes
  - `NotificationToast.vue` → Listens for events and shows toast notifications
  - `channels.php` → Authorizes which users can receive which events
- **Why**: Users see updates in real-time without refreshing the page

### Vue.js 3 (Interactive UI Components)
- **What**: JavaScript framework for building interactive UI components
- **Where used**:
  - `TaskBoard.vue` → Filter and update tasks without page reload
  - `StatsCards.vue` → Animated counting-up stat cards
  - `RedisDemo.vue` → Interactive Redis command playground
  - `NotificationToast.vue` → Auto-dismissing notification popups
- **Why**: Makes the UI dynamic and responsive to user actions

### Tailwind CSS 4 (Styling)
- **What**: Utility-first CSS framework
- **Where used**: Every Blade view and Vue component
- **Why**: Build beautiful UIs quickly with utility classes instead of writing custom CSS

### Laravel Echo (Frontend WebSocket Client)
- **What**: JavaScript library that connects to Pusher and listens for events
- **Where used**: `bootstrap.js` (setup) and `NotificationToast.vue` (listening)
- **Why**: Makes it easy to subscribe to channels and react to real-time events

---

## 9. Key Files to Study

### For Learning Redis:
1. `app/Http/Controllers/RedisDemoController.php` - All Redis operations demonstrated
2. `app/Http/Controllers/DashboardController.php` - Real-world Cache::remember pattern
3. `app/Http/Controllers/TaskController.php` - Cache invalidation strategy
4. `resources/js/components/RedisDemo.vue` - Frontend for Redis playground

### For Learning Pusher/Broadcasting:
1. `app/Events/TaskCreated.php` - How to create broadcast events
2. `app/Events/TaskStatusChanged.php` - Event with old/new data
3. `routes/channels.php` - Channel authorization
4. `resources/js/bootstrap.js` - Echo/Pusher client setup
5. `resources/js/components/NotificationToast.vue` - Listening to events

### For Learning Vue.js:
1. `resources/js/app.js` - Vue 3 app creation & component registration
2. `resources/js/components/TaskBoard.vue` - AJAX calls, reactive state, filters
3. `resources/js/components/StatsCards.vue` - Props, lifecycle hooks, animations
4. `resources/js/components/RedisDemo.vue` - Complex component with multiple features

### For Learning Laravel Patterns:
1. `app/Models/Task.php` - Scopes, accessors, relationships, helpers
2. `app/Http/Controllers/AuthController.php` - Authentication flow
3. `app/Http/Controllers/TaskController.php` - Resource controller with caching
4. `database/seeders/DatabaseSeeder.php` - Seeding strategies

---

## 10. Common Issues & Fixes

### "Connection refused" for Redis
**Problem**: Redis server is not running
**Fix**: Start Redis/Memurai service, or change `.env` to use `database` driver:
```env
CACHE_STORE=database
SESSION_DRIVER=database
QUEUE_CONNECTION=database
```

### "Class Redis not found"
**Problem**: PHP Redis extension not installed
**Fix**: We use `predis/predis` (pure PHP client), so this shouldn't happen. Verify `.env` has:
```env
REDIS_CLIENT=predis
```

### Pusher notifications not working
**Problem**: Wrong API keys or Pusher not configured
**Fix**:
1. Check `.env` has correct Pusher keys
2. Make sure `npm run dev` or `npm run build` was run AFTER setting the keys
3. Make sure `php artisan queue:listen` is running
4. Check browser console for WebSocket errors

### Vue components not rendering
**Problem**: Assets not built
**Fix**: Run `npm run build` or `npm run dev`

### CSS looks broken / no styling
**Problem**: Tailwind not processing
**Fix**: Run `npm run build` and hard-refresh browser (Ctrl+Shift+R)

### "SQLSTATE: Table doesn't exist"
**Problem**: Migrations haven't been run
**Fix**: `php artisan migrate:fresh --seed`

### Login not working
**Problem**: User doesn't exist
**Fix**: Run `php artisan migrate:fresh --seed` to create demo users

### CSRF token mismatch
**Problem**: Session expired
**Fix**: Refresh the page. Sessions are stored in Redis; make sure Redis is running.

### Queue jobs not processing
**Problem**: Queue worker not running
**Fix**: Run `php artisan queue:listen` in a separate terminal

---

## 11. Learning Exercises

Try these after exploring the project:

### Beginner:
1. **Add a "Notes" field** to tasks (migration + form + display)
2. **Change category colors** and see them update on tasks
3. **Add sorting** to the task list (by date, priority, status)

### Intermediate:
4. **Add task comments** (new model, migration, controller, views)
5. **Add email notifications** when a task is assigned
6. **Create a task search** with Redis-cached results
7. **Add task attachments** using Laravel file storage

### Advanced:
8. **Add team/project support** (users belong to teams)
9. **Build a Kanban board** with drag-and-drop using Vue
10. **Add API authentication** with Laravel Sanctum
11. **Deploy to production** with Redis on a cloud server

---

## Quick Commands Reference

```bash
# Start the app
php artisan serve                    # Start Laravel server
npm run dev                          # Start Vite dev server
php artisan queue:listen             # Start queue worker

# Database
php artisan migrate:fresh --seed     # Reset & seed database
php artisan migrate                  # Run pending migrations
php artisan db:seed                  # Seed data only

# Cache
php artisan cache:clear              # Clear application cache
php artisan config:clear             # Clear config cache
php artisan view:clear               # Clear view cache
php artisan optimize:clear           # Clear all caches

# Development
php artisan make:model Name -mcf     # Create model + migration + controller + factory
php artisan make:event EventName     # Create broadcast event
php artisan route:list               # List all routes
php artisan tinker                   # Interactive PHP shell

# Build
npm run build                        # Production build
npm run dev                          # Development with hot reload
```

---

**Happy Learning! 🚀**

Built with Laravel 12 + Redis + Pusher + Vue.js 3 + Tailwind CSS 4
