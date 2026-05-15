# 🧠 Smart Tasks Planner — Student Productivity Platform

A complete Laravel MVP for students: Pomodoro, Notes, Flashcards, Streaks, Gamification, Task Management, and more.

---

## ⚙️ Requirements

- PHP 8.2+
- Composer
- XAMPP (Apache + MySQL)
- Node.js + npm

---

## 🚀 Installation (XAMPP)

### Step 1 — Install Laravel & dependencies

```bash
# In your XAMPP htdocs folder (or any folder served by Apache):
composer create-project laravel/laravel smart-tasks-planner
cd smart-tasks-planner

# Install Breeze (auth scaffolding)
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install && npm run build
```

### Step 2 — Copy all provided files into the project

Copy each file from this archive into the corresponding path inside `smart-tasks-planner/`.

### Step 3 — Configure .env

```env
APP_NAME="Smart Tasks Planner"
APP_URL=http://localhost/smart-tasks-planner/public

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smart_tasks_planner
DB_USERNAME=root
DB_PASSWORD=
```

### Step 4 — Create database

Open phpMyAdmin → create a database named:

```text
smart_tasks_planner
```

### Step 5 — Run migrations & seeders

```bash
php artisan migrate --seed
php artisan storage:link
```

### Step 6 — Run the app

### Option A — Laravel dev server

```bash
php artisan serve
```

Visit:

```text
http://127.0.0.1:8000
```

### Option B — XAMPP Apache

Set this in `.env`:

```env
APP_URL=http://localhost/smart-tasks-planner/public
```

Visit:

```text
http://localhost/smart-tasks-planner/public
```

---

## 🗂️ Features

| Feature | Route |
|---|---|
| Dashboard | `/dashboard` |
| Study Tasks | `/tasks` |
| Notes | `/notes` |
| Pomodoro Timer | `/pomodoro` |
| Flashcards | `/flashcards` |
| Exams | `/exams` |
| Subjects | `/subjects` |
| User Profile | `/profile` |
| Gamification & Streaks | `/dashboard` |

---

## 🎯 Project Goal

Smart Tasks Planner helps students organize their academic workflow through:

- Task management
- Smart study planning
- Pomodoro productivity sessions
- Flashcards for memorization
- Exam tracking
- Notes management
- Gamification and streak motivation

---

## 🛠️ Built With

- Laravel
- Blade
- MySQL
- Bootstrap / Tailwind CSS
- Laravel Breeze
- JavaScript

---

## 👨‍💻 Author

Developed as a student productivity and task management platform using Laravel.