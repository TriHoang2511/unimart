# Unimart: Copilot Instructions for AI Agents

## Project Overview
Unimart is a Laravel 12 e-commerce platform with role-based access control (RBAC) using Spatie Laravel-Permission. It manages products, categories, posts, pages, and user administration with a Blade-templated admin dashboard.

## Core Architecture

### Key Directories
- **`app/Models/`** – Eloquent models (User with roles/permissions via Spatie, ProductCategory, Page, Post, Product variants)
- **`app/Http/Controllers/Admin/`** – CRUD controllers for admin resources (UserController, ProductController, RoleController, etc.)
- **`resources/views/`** – Blade templates (admin layout extends `layouts.admin`; feature-specific views in subdirectories)
- **`routes/web.php`** – Main route definitions; auth middleware and permission gates required for admin routes
- **`config/permission.php`** – Spatie permission config; custom App\Models\Permission/Role classes with description field

### Permission & Authorization Pattern
- **Gate-based checks**: `AppServiceProvider::boot()` uses `Gate::before()` with Spatie's `hasPermissionTo()` – NO manual loops over permissions
- **Route-level guards**: Admin routes use `can()` middleware (e.g., `->can('user.view')`)
- **Custom models**: Permission and Role extend Spatie's base classes and add `description` field to fillable
- **User soft deletes**: Users table has soft delete support; queries filter active/trash via `onlyTrashed()`

### Frontend Stack
- **Vite build** with Laravel plugin; entry: `resources/css/app.css`, `resources/js/app.js`
- **Tailwind CSS 3.x** with @tailwindcss/vite plugin
- **Alpine.js** for interactive components
- **Admin UI**: Custom Bootstrap-based admin template (not Livewire/Inertia)
- **Flash messages**: Use `session('status')` for redirect feedback in views

## Critical Developer Workflows

### Setup & Development
```bash
# Initial setup with all dependencies
composer run setup

# Concurrent dev server (Vite, queue, logs, app server)
composer run dev

# Build frontend for production
npm run build
```

### Testing
- Framework: **Pest 3.x** (PHPUnit wrapper with fluent syntax)
- Config: `phpunit.xml` uses in-memory SQLite for tests
- Feature tests in `tests/Feature/`; Pest auto-configures `RefreshDatabase` trait
- Run: `./vendor/bin/pest`

### Database
- Migrations in `database/migrations/` with timestamp naming
- Key tables: `users`, `roles`, `permissions`, `role_has_permissions`, `model_has_permissions`, `products`, `product_categories`, `pages`, `posts`, `post_categories`
- Product variants and category hierarchies (parent_id for nested categories) established

### Linting & Code Quality
- **Pint** (Laravel code style): `./vendor/bin/pint`
- **Tinker** REPL: `php artisan tinker` for interactive testing

## Project-Specific Conventions

### Naming & Route Patterns
- **Soft delete queries**: Use `onlyTrashed()` for trash view; normal queries exclude soft-deleted records
- **Route naming**: `admin.{resource}.{action}` (e.g., `admin.user.index`, `admin.user.create`)
- **Controller actions**: Follow RESTful (index, create, store, edit, update, destroy)
- **View hierarchy**: `resources/views/admin/{resource}/{action}.blade.php`

### Model Relationships
- ProductCategory: `hasMany('children')` and `belongsTo('parent')` for hierarchical categories
- User: `HasRoles` trait from Spatie; query via `user->roles`, `user->hasPermissionTo()`
- Page & Post models use `SoftDeletes` for archive functionality

### Form Handling & Validation
- Controllers validate via `Request` objects in `app/Http/Requests/`
- Pagination: Laravel's default `->paginate(10)` in index controllers
- Keyword search: LIKE-based filtering on name/email fields (e.g., UserController)

### Admin Dashboard Conventions
- Views extend `@extends('layouts.admin')`
- Table headers with search/filter forms in `@section('content')`
- Bulk actions dropdown with status checks (active vs trash)
- Flash message pattern: Check `session('status')` alert in views

## Integration Points & Dependencies

### External Libraries
- **Spatie Laravel-Permission 6.24**: Permission/role management – always use `Gate::before()` + `hasPermissionTo()` pattern
- **Laravel Breeze 2.3**: Auth scaffolding (login/register routes in `routes/auth.php`)
- **Laravel Tinker**: Interactive shell for debugging
- **Faker & Factory**: UserFactory in `database/factories/` for seeding test data

### Cross-Component Communication
- Permission checks flow through Gate middleware → Spatie queries → database
- Controllers handle requests → validate via Requests → update models → return views with session feedback
- Frontend form submissions POST to controller actions with CSRF protection (Blade @csrf)

## File Modifications Guidelines

### When Adding Features
1. Create/update migration in `database/migrations/`
2. Define model with relationships in `app/Models/`
3. Add controller with CRUD methods in `app/Http/Controllers/Admin/`
4. Add routes in `routes/web.php` with `->can('permission.name')` guards
5. Create Blade views following `resources/views/admin/{resource}/{action}.blade.php` pattern
6. Assign permissions to roles via seeder or permission middleware

### Common Patterns to Replicate
- **Soft delete listing**: Check `$request->query('status')` → branch `onlyTrashed()` vs normal query
- **Paginated search**: `User::where('name', 'LIKE', "%{$keyword}%")->paginate(10)`
- **Permission gates**: Always use Gate::before in AppServiceProvider, never manual loops
- **View sections**: Use `@section('content')` blocks; card-based layouts with Bootstrap utilities

## Quick Command Reference
- Start dev: `composer run dev`
- Run tests: `./vendor/bin/pest`
- Generate key: `php artisan key:generate`
- Migrate DB: `php artisan migrate`
- Tinker shell: `php artisan tinker`
- Code style: `./vendor/bin/pint`
- Route list: `php artisan route:list | grep admin`
