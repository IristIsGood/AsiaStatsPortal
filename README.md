# 🌏 Asia Stats Portal

A full-stack statistical data portal inspired by the Asian Development Bank's **Key Indicators Database (KIDB)**. Built with PHP/Laravel 11 and Vue 3, featuring a public REST API that supports multiple output formats, role-based access control, and a data validation workflow.

---

## 🚀 Live Demo

> Coming soon — Azure deployment in progress

---

## ✨ Features

- **REST API** — Query statistical data with JSON, CSV, and XML output formats
- **Data Explorer** — Filter by country, indicator, and year with real-time results
- **Data Validation Workflow** — Submit data points for admin review (pending → approved/rejected)
- **Role-Based Access Control** — Public read access; admin/editor token required for write operations
- **Sub-national Data Support** — Regional breakdowns within countries (e.g. Philippine regions)
- **Audit Logging** — All data changes tracked with user, action, and timestamp
- **Interactive Dashboard** — GDP comparison chart powered by Chart.js

---

## 🛠 Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | PHP 8.3 + Laravel 11 |
| Frontend | Vue 3 + TypeScript + Pinia |
| Database | MySQL / MariaDB |
| Auth | Laravel Sanctum (token-based) |
| Styling | Tailwind CSS v4 |
| Charts | Chart.js + vue-chartjs |
| Build Tool | Vite 8 |
| Cloud | Microsoft Azure (App Service + Azure DB) |

---

## 📁 Project Structure

```
asia-stats-portal/
├── app/
│   ├── Http/
│   │   ├── Controllers/Api/
│   │   │   ├── AuthController.php
│   │   │   ├── DataPointController.php
│   │   │   ├── DataValidationController.php
│   │   │   ├── IndicatorController.php
│   │   │   └── RegionController.php
│   │   └── Traits/
│   │       └── FormatsApiResponse.php   # JSON / CSV / XML output
│   └── Models/
│       ├── AuditLog.php
│       ├── DataPoint.php
│       ├── Indicator.php
│       ├── Region.php
│       └── User.php
├── database/
│   ├── migrations/
│   └── seeders/                         # 8 countries, 10 indicators, real data
├── resources/js/
│   ├── views/
│   │   ├── Dashboard.vue
│   │   ├── DataExplorer.vue
│   │   ├── Indicators.vue
│   │   ├── Admin.vue
│   │   └── Login.vue
│   ├── stores/
│   │   └── auth.js                      # Pinia auth store
│   └── router.js                        # Vue Router with navigation guards
└── routes/
    └── api.php                          # REST API routes
```

---

## 🗄 Database Schema

```
indicators     — Statistical indicators (GDP, Population, CO2, etc.)
regions        — Countries and sub-national regions
data_points    — Actual data values (indicator × region × year)
users          — System users with roles (admin / editor / viewer)
audit_logs     — Immutable log of all data changes
```


---

## ⚙️ Local Setup

### Prerequisites

- PHP 8.3+
- Composer
- MySQL 8.0+
- Node.js 18+

### Installation

```bash
# 1. Clone the repository
git clone https://github.com/YOUR_USERNAME/asia-stats-portal.git
cd asia-stats-portal

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies
npm install

# 4. Copy environment file
cp .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. Configure database in .env
DB_DATABASE=asia_stats_portal
DB_USERNAME=root
DB_PASSWORD=your_password

# 7. Run migrations and seed data
php artisan migrate
php artisan db:seed

# 8. Start development servers (two terminals)
php artisan serve       # Terminal 1 — Laravel on :8000
npm run dev             # Terminal 2 — Vite on :5173
```

### Default Admin Account

```
Email:    admin@test.com
Password: password123
Role:     admin
```


---

## 🔐 User Roles

| Role | Read | Create/Update | Validate Data | Delete |
|------|------|---------------|---------------|--------|
| viewer | ✅ | ❌ | ❌ | ❌ |
| admin | ✅ | ✅ | ✅ | ✅ |

---

## 📊 Sample Data

The database is seeded with real statistical data for 8 Asian economies:

| Country | GDP 2023 (USD bn) | Population 2023 (M) |
|---------|-------------------|----------------------|
| China | 17,794 | 1,409 |
| India | 3,732 | 1,428 |
| Indonesia | 1,417 | 281 |
| Thailand | 512 | 70 |
| Philippines | 464 | 114 |
| Malaysia | 431 | 34 |
| Viet Nam | 430 | 99 |
| Singapore | 501 | 5.9 |

Sources: World Bank, UN Population Division

---

---

🛡️ License
MIT

👤 Developer
Irist – Building tools that turn raw market data into trading intelligence.