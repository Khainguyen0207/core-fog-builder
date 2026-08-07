# Motorbike Service Manager (Backend)

Motorbike repair appointment booking system (Backend API & Admin Panel). <br>
View system details: [Document](https://github.com/Khainguyen0207/moto-service-manager-be/blob/main/PROJECT_OVERVIEW.md)

## Requirements

### Docker Mode
- Docker & Docker Compose

### Manual Mode
- PHP >= 8.2
- Composer
- Node.js >= 20
- MySQL 8.0

---

## 1. Installation & Run (Docker)

### Environment Configuration

```bash
cp .env.example .env
```

Edit `.env` (important):

```env
APP_ENV=production
APP_DEBUG=false

# Database container connection
DB_HOST=mysql
DB_DATABASE=moto_service
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Build & Start

```bash
docker compose up --build -d
```

### Initial Setup

```bash
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed
docker compose exec app php artisan storage:link
```

### Common Docker Commands

```bash
# View logs
docker compose logs -f app

# Access container
docker compose exec app bash

# Restart services
docker compose restart
```

---

## 2. Installation & Run (Manual)

### Install dependencies

```bash
composer install
npm install
```

### Environment Configuration

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:
```env
DB_HOST=127.0.0.1
DB_DATABASE=moto_service
DB_USERNAME=root
DB_PASSWORD=your_local_password
```

### Database & Storage Setup

```bash
# Ensure MySQL is running and the database has been created
php artisan migrate --seed
php artisan storage:link
```

### Build Frontend

```bash
npm run build
```

### Run Server

```bash
# Terminal 1: Web Server
php artisan serve

# Terminal 2: Background Jobs (required)
php artisan queue:work

# Terminal 3: Scheduler (optional)
php artisan schedule:work
```

---

## 3. Setup Telegram Notifications

The system supports sending notifications via Telegram when a customer books an appointment. There are 2 ways to set this up:

### Method 1: Quick Setup (Recommended)
You just need to join the system's default Telegram group:
- Click the link: [Join group telegram notification here](https://t.me/+UkakVQvNAaI4YzM1) to join the group.
- The default Group ID and Bot Token are already configured in the source code.

### Method 2: Custom Setup (Custom Bot & Group)
If you want to use your own Telegram Bot and Group:
1. Refer to the guide on creating a Bot and getting the Chat ID at: [Guide to create Bot Token and Telegram Group ID](https://wiki.shost.vn/thu-thuat-wordpress/huong-dan-tao-bot-token-group-id-telegram-2025.html).
2. Once you have the information, access the **Admin Panel** -> **Settings** menu -> **Telegram** tab.
3. Update your `Bot Token` and `Chat ID` in the form and save.

---

## General Information

### Access

| URL                          | Description  |
|------------------------------|--------------|
| http://localhost:8080/admin  | Admin Panel  |
| http://localhost:8080/api/v1 | API          |
| http://localhost:8000        | Manual       |

### Default Accounts

| Email                  | Password |
|------------------------|----------|
| admin@admin.vn         | 123456   |
