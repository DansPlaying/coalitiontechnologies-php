# Task Manager

A Laravel 11 web application for managing tasks with drag-and-drop priority ordering and project organisation.

## Features

- **Create, edit, and delete tasks** — each task stores a name, priority, and timestamps.
- **Drag-and-drop reordering** — drag any row to reorder; priority numbers update instantly without a page reload.
- **Projects** — create named projects and assign tasks to them. A dropdown on the task list filters to a single project.
- **Priority auto-management** — new tasks are appended to the bottom of their project's list. Deleting or moving a task to a different project automatically resequences the remaining priorities.

## Requirements

| Tool | Minimum version |
|------|----------------|
| PHP | 8.3 |
| Composer | 2.x |
| Node.js | 20.x |
| npm | 10.x |
| MySQL | 8.0 |

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

Create the database if it does not exist yet:

```sql
CREATE DATABASE task_manager CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 5. Run migrations

```bash
php artisan migrate
```

Optionally load the sample data (3 projects, 15 tasks):

```bash
php artisan db:seed
```

### 6. Start the development servers

Open **two terminal tabs** in the project root:

**Tab 1 — PHP development server:**

```bash
php artisan serve
```

**Tab 2 — Vite asset compiler (with hot reload):**

```bash
npm run dev
```

Visit [http://localhost:8000](http://localhost:8000).

---

## Production Deployment

### 1. Set environment variables

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
```

### 2. Install production dependencies

```bash
composer install --no-dev --optimize-autoloader
```

### 3. Compile and fingerprint frontend assets

```bash
npm run build
```

This writes versioned files to `public/build/`. You do **not** need Node.js on the production server after this step.

### 4. Cache Laravel configuration and routes

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 5. Run migrations

```bash
php artisan migrate --force
```

### 6. Web server configuration

Set the document root to the `public/` directory.

**Apache** — the included `public/.htaccess` handles URL rewriting automatically (requires `mod_rewrite`).

**Nginx** — add the following `location` block to your server config:

```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

### 7. File permissions

The `storage/` and `bootstrap/cache/` directories must be writable by the web server process:

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## Project Structure

```
app/
  Http/
    Controllers/
      TaskController.php      — CRUD + reorder endpoint
      ProjectController.php   — CRUD for projects
    Requests/
      StoreTaskRequest.php    — validation for task creation
      UpdateTaskRequest.php   — validation for task edits
      ReorderTasksRequest.php — validation for drag-drop payload
      StoreProjectRequest.php
      UpdateProjectRequest.php
  Models/
    Task.php                  — belongs to Project; priority cast to int
    Project.php               — has many Tasks (ordered by priority)
database/
  migrations/
    …_create_projects_table.php
    …_create_tasks_table.php
  seeders/
    DatabaseSeeder.php        — 3 projects × 5 tasks sample data
resources/
  js/
    app.js                    — entry point
    task-sorter.js            — SortableJS drag-drop + AJAX sync
  css/app.css                 — Tailwind CSS entry
  views/
    layouts/app.blade.php     — shared HTML shell + nav
    components/alert.blade.php
    tasks/{index,create,edit}.blade.php
    projects/{index,create,edit}.blade.php
routes/
  web.php                     — resource routes + reorder endpoint
```

## Technology Choices

| Concern | Choice | Why |
|---------|--------|-----|
| Drag-and-drop | [SortableJS](https://sortablejs.github.io/Sortable/) | Lightweight, no jQuery dependency, excellent touch support |
| Styling | Tailwind CSS via Vite | Ships with Laravel 11; utility-first keeps markup readable |
| Form validation | Laravel Form Requests | Keeps controllers thin; validation logic is testable in isolation |
| Priority sync | AJAX POST to `/tasks/reorder` | Avoids a full page reload; wrapped in a DB transaction for atomicity |
