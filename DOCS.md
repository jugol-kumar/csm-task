# CSN Bangladesh – Shop Manager

A small shop manager built around **one shared product catalog**.

- **Admin** creates shops, shop owners and products.
- **Shop owners** pick products from the shared catalog and set their own price, details and thumbnail. They can also create new products that every other shop can use.
- **Customers** browse shops and see only the products that a shop has put on its shelf.

**Tech stack:** Laravel 13 (API), Vue 3 and Tailwind CSS 4.

---

## Table of Contents

1. [Requirements](#1-requirements)
2. [Installation](#2-installation-about-5-minutes)
3. [Demo Account](#3-demo-account)
4. [Admin Guide](#4-admin-guide)
5. [Shop Owner Guide](#5-shop-owner-guide)
6. [Customer Guide](#6-customer-guide)
7. [Troubleshooting](#7-troubleshooting)
8. [Support and Contact](#8-support-and-contact)

---

## 1. Requirements

| Tool     | Version              | Check with          |
| -------- | -------------------- | ------------------- |
| PHP      | 8.3 or newer         | `php -v`            |
| Composer | 2.x                  | `composer -V`       |
| Node.js  | 20 or newer          | `node -v`           |
| MySQL    | 8 or MariaDB 10.6+   | `mysql --version`   |

PHP must have these extensions enabled: `pdo_mysql`, `mbstring`, `xml`, `fileinfo`, `curl`, `zip`.
XAMPP, Laragon, Herd and most Docker setups already include them.

---

## 2. Installation (about 5 minutes)

### Step 1. Get the code

```bash
git clone https://github.com/jugol-kumar/csm-task.git csn_bangladesh
cd csn_bangladesh
```

### Step 2. Install PHP packages

```bash
composer install
```

### Step 3. Create your environment file

```bash
cp .env.example .env
php artisan key:generate
```

On Windows Command Prompt, use `copy .env.example .env` instead of `cp`.

Then open `.env` and make sure these values match your MySQL setup:

```env
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=csn_bangladesh
DB_USERNAME=root
DB_PASSWORD=
```

### Step 5. Create tables and demo data

```bash
php artisan migrate --seed
php artisan storage:link
```

> `storage:link` makes uploaded thumbnails visible in the browser. If you skip it, images will not show.

### Step 6. Install frontend packages

```bash
npm install
```

### Step 7. Start the app (two terminals)

**Terminal 1:**

```bash
php artisan serve
```

**Terminal 2:**

```bash
npm run dev
```

Open **http://localhost:8000** in your browser. Keep both terminals running while you work.

---

## 3. Demo Account

| Role  | Login page     | Email             | Password    |
| ----- | -------------- | ----------------- | ----------- |
| Admin | `/admin/login` | `admin@csn_bangladesh.test` | `pa$$w0rd`  |

Shop owners and customers do not have demo accounts. See the guides below for how they are created.

---

## 4. Admin Guide

- Log in at `/admin/login` using the demo admin email and password above.
- Only the admin can create shop owner users and shops, from the **Store** module.
- The admin can create, modify and delete products, including products created by shop owners.
- The admin can edit or delete any user account from the **User** module.

---

## 5. Shop Owner Guide

| Role       | Login page | Account                      |
| ---------- | ---------- | ---------------------------- |
| Shop owner | `/login`   | Created by the admin         |

After logging in, a shop owner can:

- Create their own products.
- Add products created by the admin or by other owners to their own shop.
- Remove any product (including products created by the admin or other owners) from their own shop's catalog.
- Edit or delete **only** the products they created themselves.

---

## 6. Customer Guide

| Role     | Registration page | Login page |
| -------- | ----------------- | ---------- |
| Customer | `/register`       | `/login`   |

- Customers create their own account on the registration page and log in at `/login`.
- When a customer clicks on a shop, they see **only the products that this shop has listed in its catalog**.

---

## 7. Troubleshooting

| Problem | What to check |
| ------- | ------------- |
| Uploaded images do not show | Run `php artisan storage:link`. |
| Database connection error | Confirm MySQL is running and the `DB_*` values in `.env` are correct. |
| `composer install` fails | Confirm your PHP version is 8.3 or newer and the required extensions are enabled. |
| Frontend does not load or styles are missing | Make sure `npm run dev` is running in its own terminal. |

If the problem is not listed here, please contact me using the details below.

---

## 8. Support and Contact

If you need help with installation, or you run into any issue while running the project, please contact me directly.

- **Email:** [jugolkumar23@gmail.com](mailto:jugolkumar23@gmail.com)
- **WhatsApp:** [+8801723717933](https://wa.me/8801723717933)
- **Phone:** 01723717933
