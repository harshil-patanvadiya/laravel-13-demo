# Laravel Practical Demo

This project is a small Laravel 13 backend demonstrating:

- Token-based API authentication (Sanctum)
- Role-based access control (admin / user) using Spatie Permissions
- Category management (admin only)
- Product management with tags and variants (admin only)
- Simple Blade-based admin panel that consumes the APIs

## Requirements

- PHP 8.5
- Composer
- Node.js & npm
- MySQL (or another configured database)

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate

# Configure your database in .env, then:
php artisan migrate --seed

# Frontend assets (Tailwind + Blade)
npm install
npm run dev
```

## Authentication

- Login API: `POST /api/login`
  - Body: `email`, `password`
  - Returns: user data + `token`
- Logout API: `POST /api/logout` (requires Bearer token)

The login Blade page (`/login`) calls `/api/login`, stores the token in `localStorage`, and redirects to the admin category listing.

## Roles

- Roles: `admin`, `user`
- Admin seeder creates an `admin@example.com` user and assigns the admin role.
- Protected APIs use:
  - `auth:api` guard (Sanctum)
  - Custom `admin` middleware to ensure the user has the admin role

## APIs

All protected endpoints require `Authorization: Bearer <token>` and admin role.

- `GET /api/categories` – list categories (paginated)
- `POST /api/categories` – create category
- `GET /api/products` – list products with category, tags, variants (paginated)
- `POST /api/products` – create product with:
  - `name`, `description`, `status`, `category_id`
  - `tag_ids` (array of tag IDs)
  - `variants` (array of `{ size, color, price }`, at least one required)

## Admin Panel

The admin panel is built with Blade and Tailwind and consumes the APIs via JavaScript (using the stored token).

- `/admin/categories`
  - Lists all categories (via `GET /api/categories`)
  - Header buttons: **Categories**, **Products**
- `/admin/products`
  - Lists all products with tags and variant summary (via `GET /api/products`)
- `/admin/products/{id}`
  - Shows product details, full tag list, and variant table

## Notes

- Only admins can access the category and product APIs and the admin pages.
- Products and categories use enum-backed `status` fields (`CategoryStatus`, `ProductStatus`) with human-readable `display_status` accessors.

