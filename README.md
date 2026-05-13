# CRM SaaS

A Laravel CRM for managing clients, SEO/Suivi subscriptions, reminder notifications, and automatic email reminders before subscription expiration.

## Features

- Client, subscription, and notification CRUD
- Reminder emails 5 days before expiration by default
- Laravel Mail SMTP support with Gmail App Passwords
- Daily scheduler command for due reminders
- Dashboard with KPI cards, Chart.js charts, upcoming expirations, recent notifications, and activity feed
- Authentication: login, register, logout, forgot password, remember me
- Role management:
  - `admin`: full access
  - `employee`: clients and subscriptions only
- Activity log for create, update, delete, and reminder-send actions
- Admin settings for company name, reminder delay, logo, and SMTP metadata
- CSV export for subscriptions
- REST API with bearer token authentication
- Feature tests for auth, authorization, subscriptions, and API login

## Requirements

- PHP 8.3+
- Composer
- MySQL or SQLite
- A configured mail provider for production email

## Installation

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Default seeded admin:

```text
Email: admin@example.com
Password: password
```

Change this account immediately in production.

## Mail Setup

For Gmail SMTP:

```env
MAIL_MAILER=smtp
MAIL_SCHEME=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-address@gmail.com
MAIL_PASSWORD=your_google_app_password
MAIL_FROM_ADDRESS=your-address@gmail.com
MAIL_FROM_NAME="CRM System"
```

Then clear cached config:

```bash
php artisan optimize:clear
```

Test SMTP:

```bash
php artisan crm:test-email your-address@gmail.com
```

## Scheduler

Run Laravel's scheduler every minute in production:

```cron
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

The CRM reminder command runs daily at 08:00:

```bash
php artisan crm:send-subscription-reminders --debug --dry-run
php artisan crm:send-subscription-reminders --debug
```

Reminder criteria:

- `subscriptions.reminder_date = today`
- `status = active`
- `service_type` is `seo` or `suivi`
- notification has not already been sent

## API

Login:

```http
POST /api/login
Content-Type: application/json

{
  "email": "admin@example.com",
  "password": "password",
  "device_name": "integration"
}
```

Use the returned token:

```http
Authorization: Bearer <token>
```

Endpoints:

- `GET /api/me`
- `GET /api/clients`
- `POST /api/clients`
- `GET /api/clients/{id}`
- `PUT /api/clients/{id}`
- `DELETE /api/clients/{id}`
- `GET /api/subscriptions`
- `POST /api/subscriptions`
- `GET /api/subscriptions/{id}`
- `PUT /api/subscriptions/{id}`
- `DELETE /api/subscriptions/{id}`
- `GET /api/notifications` admin only

The token table follows Sanctum's `personal_access_tokens` shape. Composer failed locally before dependency resolution with a Windows cwd error, so the project includes an internal bearer-token middleware. When Composer is healthy, you can install official Sanctum with:

```bash
composer require laravel/sanctum
```

Then replace `api.token` middleware with Sanctum's `auth:sanctum`.

## Tests

```bash
php artisan test
```

or on Windows:

```bash
.\vendor\bin\phpunit --do-not-cache-result
```

## Production Checklist

- Set `APP_ENV=production`
- Set `APP_DEBUG=false`
- Configure HTTPS
- Configure real SMTP credentials
- Run `php artisan config:cache`
- Run `php artisan route:cache`
- Configure scheduler cron
- Configure backups for database and uploaded logos
- Replace default admin credentials
