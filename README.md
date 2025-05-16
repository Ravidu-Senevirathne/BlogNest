<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

---

# BlogNest 📝

A modern multi-user blogging platform built with Laravel 10.  
Supports role-based access: **Admin**, **Editor**, and **Reader**, with a dynamic dashboard and rich blog features.

---

## 🔐 Seeded User Accounts

Use these credentials after seeding:

| Role   | Email              | Password           |
|--------|--------------------|--------------------|
| Admin  | admin@example.com  | StrongPassword123  |
| Editor | editor@example.com | password           |
| Reader | reader@example.com | password           |

---

## ⚙️ Local Setup

### Step-by-Step

1. **Clone the repository**
   ```bash
   git clone https://github.com/Ravidu-Senevirathne/BlogNest.git
   cd BlogNest
   ```

2. **Install backend and frontend dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Create your `.env` file**
   ```bash
   cp .env.example .env
   ```

4. **Set your `.env` values:**

   - Database:
     ```env
     DB_DATABASE=your_database_name
     DB_USERNAME=your_db_user
     DB_PASSWORD=your_db_password
     ```

   - Seeder admin credentials:
     ```env
     ADMIN_EMAIL=admin@example.com
     ADMIN_PASSWORD=StrongPassword123
     ```

   - Queue (optional):
     ```env
     QUEUE_CONNECTION=database
     ```

5. **Generate app key**
   ```bash
   php artisan key:generate
   ```

6. **Run migrations and seeders**
   ```bash
   php artisan migrate:fresh --seed
   ```

7. **Build frontend assets**
   ```bash
   npm run dev
   ```

8. **Start development server**
   ```bash
   php artisan serve
   ```

Visit [http://localhost:8000](http://localhost:8000) and log in using any of the accounts above.

---

## 🧵 Queue Processing (for image uploads, etc.)

BlogNest uses Laravel's queue system for background tasks like image processing.

### Setup

- Ensure `.env` has:
  ```env
  QUEUE_CONNECTION=database
  ```

- Run:
  ```bash
  php artisan queue:work
  ```
live on : blognest-production.up.railway.app

## 💬 Credits

Developed by **Ravidu Senevirathne**  
Email: ravidu.dilruk@gmail.com  
GitHub: [Ravidu-Senevirathne](https://github.com/Ravidu-Senevirathne)  
LinkedIn: [Ravidu Senevirathne](https://www.linkedin.com/in/ravidu-senevirathne/)
