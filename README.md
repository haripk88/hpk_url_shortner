# URL Shortener

A URL shortener application built with **Laravel** as the backend and **Vue.js** as the frontend. The Vue.js frontend is integrated within the Laravel project inside the `resources/js` folder, using a single-page application (SPA) approach with Vue Router and Ant Design Vue for UI components.

## Installation

1. Clone the repository

```bash
git clone https://github.com/haripk88/hpk_url_shortner
cd hpk_url_shortner
```

2. Install dependencies

```bash
composer install
npm install
npm run build
```

3. Setup environment

```bash
cp .env.example .env
php artisan key:generate
```

4. Update `.env` file with your MySQL credentials

```
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. Run migrations and seed the database

```bash
php artisan migrate
php artisan db:seed
```

> **Note:** If `APP_ENV=production` in your `.env` file, the seeder will only create the superadmin company with the following credentials:
>
> - **Email:** superadmin@gmail.com
> - **Password:** 12345678

6. Configure mail settings in `.env` for sending invitation emails (you can use https://mailtrap.io for testing)

```
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
```

## Running the Project

```bash
php artisan serve
```

Open the URL shown in terminal (e.g. http://127.0.0.1:8000) in your browser.
