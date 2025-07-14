# BlogNest

Welcome to BlogNest, a simple and elegant blogging CMS built with Laravel and Tailwind CSS.

---

## Table of Contents
- [Features](#features)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Environment Configuration](#environment-configuration)
  - [Database Setup](#database-setup)
  - [Mailtrap Configuration](#mailtrap-configuration)
- [Database Migration & Seeding](#database-migration--seeding)
- [Storage Link](#storage-link)
- [Running the Application](#running-the-application)
- [Usage](#usage)
- [Contributing](#contributing)
- [License](#license)

---

## Features
- User authentication (email verification included)
- Role-based access control (Admin/Author/User)
- Blog post management with image uploads
- Responsive Tailwind CSS design

---

## Prerequisites
- PHP >= 8.1
- Composer
- Node.js >= 16.x
- MySQL

---

## Installation
```bash
git clone https://github.com/yourusername/BlogNest.git
cd BlogNest
composer install
npm install
npm run dev
Environment Configuration
Database Setup
Create a MySQL database:


mysql -u root -p
CREATE DATABASE blog_nest;
Configure .env:


DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=blog_nest
DB_USERNAME=root
DB_PASSWORD=yourpassword
Mailtrap Configuration
Sign up at Mailtrap.io
Go to "Demo Inbox" → "Integrations" → "Laravel"
Copy the credentials to your .env:

MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="hello@blognest.com"
MAIL_FROM_NAME="BlogNest"
Database Migration & Seeding

php artisan migrate
php artisan db:seed
(Optional) Seed with demo admin user:


php artisan db:seed --class=AdminUserSeeder
Storage Link

php artisan storage:link
Running the Application

php artisan serve
Access: http://localhost:8000

Usage
Register → Verify email (check Mailtrap inbox)
Admin Access: Use seeded admin credentials (if seeded)
Create Posts: Dashboard → "New Post"
Contributing
PRs welcome! Fork → Branch → Commit → PR.

License
MIT



---

### Key Improvements:
1. **Mailtrap Section Added**:
   - Clear steps to configure email testing (critical for email verification)
   - Includes all necessary `.env` variables

2. **Logical Flow**:
   - Database setup now comes **before** running migrations
   - Environment configuration grouped together (DB + Mailtrap)

3. **Visual Separation**:
   - Used sub-sections (`###`) for better readability
   - Code blocks formatted for easy copy-pasting

4. **Optional Admin Seeder**:
   - Added suggestion for admin user seeding (helpful for testing)

5. **Direct Links**:
   - Mailtrap signup link included
   - Clear path to demo inbox

This structure ensures users:
1. Set up the database **first**
2. Configure email **before** testing registration/verification
3. Get the app running only after core services are ready