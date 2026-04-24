# Task Manager

A Laravel 11 / PHP 8.3 web application for managing tasks organised into projects.  
Built with Tailwind CSS, Turbo Drive (SPA-like navigation), and cursor-based pagination.

---

## Features

- **Task CRUD** — create, edit, and delete tasks. Each task stores a name, priority label, and timestamps.
- **Categorical priority system** — four levels: Ultra High, High, Medium, Low. Each renders a distinct colour badge.
- **Projects** — create named projects and assign tasks to them. A dropdown on the task list filters by project.
- **Cursor-based pagination** — efficient pagination that stays stable even as rows are inserted or deleted.
- **Toast notifications** — slide-in success/error messages auto-dismiss after 4 seconds; can be closed manually.
- **Delete confirmation** — a modal dialog prevents accidental deletions.
- **SPA-like navigation** — Turbo Drive eliminates full page reloads for instant transitions.

---

## Requirements

| Tool | Minimum version |
|------|----------------|
| PHP | 8.3 |
| Composer | 2.x |
| Node.js | 20.x |
| npm | 10.x |
| MySQL | 8.0 |

---

## Local Development Setup

### 1. Clone or extract the project

```bash
cd /path/to/task-manager
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Install Node dependencies

```bash
npm install
```

### 4. Configure your environment

```bash
cp .env.example .env
php artisan key:generate
```

Open `.env` and set your database credentials:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_manager
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

Create the MySQL database if it does not exist yet:

```sql
CREATE DATABASE task_manager CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 5. Run migrations

```bash
php artisan migrate
```

Optionally seed sample data (3 projects, 15 tasks):

```bash
php artisan db:seed
```

### 6. Start the development servers

Open **two terminal tabs** in the project root.

**Tab 1 — PHP development server:**

```bash
php artisan serve
```

**Tab 2 — Vite (hot-reload asset compiler):**

```bash
npm run dev
```

Visit [http://localhost:8000](http://localhost:8000).

---

## Cloud Database (Aiven MySQL)

If you are using Aiven or another managed MySQL provider that requires SSL, add these to `.env`:

```dotenv
DB_HOST=your-host.aivencloud.com
DB_PORT=your_port
DB_DATABASE=defaultdb
DB_USERNAME=avnadmin
DB_PASSWORD="your_password"
DB_SSL_CA=/path/to/ca.pem
```

The `config/database.php` is already configured to disable server-cert verification for Aiven:

```php
Pdo\Mysql::ATTR_SSL_VERIFY_SERVER_CERT => false,
```

---

## Production Deployment

### Option A — Railway (recommended, easiest)

Railway provides a PHP runtime and auto-detects Laravel via the included `nixpacks.toml` and `Procfile`.

1. Push this repository to GitHub.
2. Go to [railway.app](https://railway.app) → **New Project → Deploy from GitHub repo**.
3. Select your repository — Railway will detect PHP and run the build automatically.
4. In the Railway dashboard, add **all** of your `.env` variables under **Variables**:

   ```dotenv
   APP_KEY=base64:...
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://your-app.up.railway.app
   DB_CONNECTION=mysql
   DB_HOST=...
   DB_PORT=...
   DB_DATABASE=...
   DB_USERNAME=...
   DB_PASSWORD=...
   ```

5. After the first deploy, run the migration once via the Railway shell:

   ```bash
   php artisan migrate --force
   ```

### Option B — Render

1. New **Web Service** → connect GitHub repo.
2. **Runtime**: PHP (or Docker).
3. **Build command**:
   ```bash
   composer install --no-dev --optimize-autoloader && npm ci && npm run build && php artisan config:cache && php artisan route:cache && php artisan view:cache
   ```
4. **Start command**:
   ```bash
   php artisan serve --host=0.0.0.0 --port=$PORT
   ```
5. Add environment variables in the Render dashboard, then run `php artisan migrate --force` from the shell tab.

### Option C — Traditional VPS / Shared Hosting

#### 1. Set production environment variables

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
```

#### 2. Install production dependencies

```bash
composer install --no-dev --optimize-autoloader
```

#### 3. Compile frontend assets

```bash
npm run build
```

This writes versioned files to `public/build/`. Node.js is **not** required on the server after this step.

#### 4. Cache Laravel config, routes, and views

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### 5. Run migrations

```bash
php artisan migrate --force
```

#### 6. Web server configuration

Point the document root to the `public/` directory.

**Apache** — the bundled `public/.htaccess` handles URL rewriting automatically (requires `mod_rewrite`).

**Nginx** — add this `location` block:

```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

#### 7. File permissions

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

> **Note:** Netlify, Vercel, GitHub Pages, and similar **static-only** hosts cannot run PHP and are not compatible with Laravel.

---

## Project Structure

```
app/
  Enums/
    Priority.php                — backed enum (UltraHigh=1 … Low=4) with label() and badgeClasses()
  Http/
    Controllers/
      TaskController.php        — CRUD for tasks
      ProjectController.php     — CRUD for projects
    Requests/
      StoreTaskRequest.php
      UpdateTaskRequest.php
      StoreProjectRequest.php
      UpdateProjectRequest.php
  Models/
    Task.php                    — belongs to Project; priority cast to Priority enum
    Project.php                 — has many Tasks
database/
  migrations/
    …_create_projects_table.php
    …_create_tasks_table.php
    …_convert_task_priority_to_levels.php
  seeders/
    DatabaseSeeder.php          — 3 projects × 5 tasks sample data
resources/
  js/
    app.js                      — entry point; wires up Turbo + toasts
    toast.js                    — flash toast display + delete confirmation dialog
  css/
    app.css                     — Tailwind CSS entry
  views/
    layouts/app.blade.php       — shared HTML shell + nav + delete-confirm dialog
    components/
      toast.blade.php           — slide-in flash toast component
      delete-confirm.blade.php  — confirmation modal
    vendor/pagination/
      cursor.blade.php          — custom Tailwind cursor pagination (Previous / Next)
    tasks/{index,create,edit}.blade.php
    projects/{index,create,edit}.blade.php
routes/
  web.php                       — resource routes for tasks and projects
```

---

## Technology Choices

| Concern | Choice | Reason |
|---------|--------|--------|
| Backend framework | Laravel 11 | Modern PHP conventions, Eloquent ORM, built-in Form Request validation |
| Priority system | PHP backed enum | Type-safe, self-documenting, no magic numbers |
| Styling | Tailwind CSS (via Vite) | Utility-first keeps markup readable; ships with the Laravel 11 starter |
| SPA navigation | Hotwire Turbo Drive | Zero-JS SPA feel without a separate frontend framework |
| Pagination | Cursor-based (`cursorPaginate`) | Stable under concurrent inserts/deletes; O(1) at any depth |
| Toasts & dialogs | Vanilla JS with event delegation | No library needed; survives Turbo body swaps |
| Form validation | Laravel Form Requests | Keeps controllers thin; reusable and independently testable |
