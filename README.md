<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

---

## 🤖 AI Agent Instructions & Guardrails
> **IMPORTANT:** Read and follow these rules strictly before modifying any files in this project. You have full autonomy to code, but you must operate within these boundaries.

### 1. Project Stack Context
* **Backend:** Laravel (PHP) dengan standard MVC/Api architecture.
* **Frontend:** Blade templates, Tailwind CSS, Alpine.js (Client-side interactivity).
* **Database:** Eloquent ORM, Migrations, standard relations.

### 2. Autonomous Action Rules (Do This Without Asking)
* **Consistency First:** If modifying a view, check how similar views are written. If a view uses Alpine.js for rendering lists (e.g. `dashboard.blade.php`), you **MUST** use Alpine.js (`<template x-for="...">`) for similar views (e.g. `tag_notes.blade.php`). Do not mix Blade loops with Alpine rendering unless explicitly requested.
* **Refactoring:** Clean up duplicate code, optimize Eloquent queries (avoid N+1 problems by using `with()`), and ensure proper formatting.
* **Error Handling:** Always include `.catch()` blocks in JavaScript `fetch()` calls and implement standard Laravel validation rules for backend endpoints.
* **Tailwind UI:** Reuse existing custom palette tokens (e.g., `#1B5E20` for Forest Green, `#4CAF50` for Nature Green, `#F5F5DC` for Beige). Keep the "Nature/Alam" design consistency.

### 3. Strict Boundaries (STOP & ASK Before Proceeding)
* **Destructive Changes:** Stop if a change requires dropping database columns or tables without a new migration.
* **Package Installation:** Stop before running `composer require` or `npm install` for third-party packages not listed in the default stack (excluding `laravel/boost`).
* **Breaking Routing Changes:** Stop if you need to rename existing API endpoints or Web Named Routes that might break other components.

---

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install