# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

Clone of [Niwa Food](https://niwa-food.vercel.app/) — a dark-themed fast-food restaurant website built with Laravel 12, Blade, Tailwind CSS v4, Alpine.js, and GSAP. Includes a custom admin panel.

## Commands

```bash
composer dev          # Start dev server (artisan serve + vite + queue + pail)
npm run dev           # Vite dev server only
npm run build         # Production build
php artisan serve     # Laravel dev server only
php artisan migrate:fresh --seed  # Reset DB with all seed data
```

## Architecture

- **Public pages:** PageController serves 4 routes: `/` (home), `/commande` (menu), `/a-propos` (about), `/contact`
- **Admin panel:** Custom-built under `/admin`, protected by `auth` middleware. Dark theme with inline CSS (no Tailwind). CRUD controllers in `App\Http\Controllers\Admin\` for products, categories, testimonials, locations, hero slides, site settings
- **Auth:** Custom LoginController (no Breeze/Fortify). Login at `/login`, admin credentials: `admin@niwa.local` / `password`
- **Database:** SQLite. SiteSetting model uses static `get(key)`/`set(key, value)` helpers for key-value site config
- **Assets:** Vite + Tailwind v4 (`@tailwindcss/vite`). Alpine.js for interactivity, GSAP + ScrollTrigger for scroll animations and counter animations
- **All models** use `$guarded = []`, `is_active` boolean + `sort_order` integer pattern, with `scopeActive()` and `scopeOrdered()` scopes
- **Images:** Stored via `public` disk (`storage/app/public`), served through `/storage` symlink

## Key Patterns

- Blade views: `layouts/public.blade.php` (Tailwind) and `layouts/admin.blade.php` (inline CSS)
- Animation: Add `fade-up` class for scroll-triggered fade. Add `data-counter="NUMBER"` for animated counters
- Hero carousel: Elements with class `hero-slide` auto-cycle. First slide gets both `hero-slide active`
- Product variants: Separate `product_variants` table with `type` field (size/meat) and own price
- Site text editable via admin Settings page (SiteSetting key-value store)
