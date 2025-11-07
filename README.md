# MEDWELL - Digital Healthcare Platform

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11.x-red" alt="Laravel 11">
  <img src="https://img.shields.io/badge/PHP-8.2+-blue" alt="PHP 8.2+">
  <img src="https://img.shields.io/badge/MySQL-8.0+-orange" alt="MySQL 8.0+">
  <img src="https://img.shields.io/badge/Status-Beta-yellow" alt="Status">
</p>

## 📋 About MEDWELL

**MEDWELL** is a comprehensive digital healthcare platform designed to revolutionize patient care through integrated health data management, wearable device connectivity, and seamless clinician-patient interactions.

### ✨ Key Features

- 🏥 **Multi-Role Management** - Super Admin, Admin, Clinician, Patient, Support, Manager
- 👥 **Patient Profile Management** - Complete health records with metrics
- ⌚ **Wearable Integration** - Fitbit, Apple Health, Samsung Health, Huawei Health
- 💊 **Medication Tracking** - Prescriptions, consent management, schedules
- 📊 **Vital Signs Monitoring** - Blood Pressure, Glucose, Temperature, SpO2, Weight
- 🚨 **Health Alerts System** - Real-time notifications for critical conditions
- 🔐 **Role-Based Access Control** - Granular permissions management
- 📝 **Activity Logging** - Complete audit trails for compliance

## 🚀 Quick Start

```bash
# Clone and install
cd D:\AI\medwell\backend_2
composer install

# Setup environment
cp .env.example .env
php artisan key:generate

# Configure database
# Edit .env: DB_DATABASE=medwell

# Migrate and seed
php artisan migrate:fresh --seed

# Start server
php artisan serve
```

**Access:** http://localhost:8000

## 🔐 Default Credentials

| Role | Email | Password | Access |
|------|-------|----------|--------|
| **Super Admin** | superadmin@medwell.id | password123 | Full system |
| **Admin** | admin@biofarma.co.id | password123 | Organization |
| **Clinician** | sarah.cardio@biofarma.co.id | password123 | Patient care |
| **Patient** | john.doe@email.com | password123 | Personal health |

## 📚 Documentation

- **[Full Documentation](DOCUMENTATION.md)** - Complete system guide
- **[Quick Start Guide](QUICK_START.md)** - Get running in 5 minutes
- **[API Reference](DOCUMENTATION.md#api-endpoints)** - All endpoints
- **[Testing Guide](DOCUMENTATION.md#testing-guide)** - Manual & automated tests

## 🏗️ Tech Stack

- **Backend Framework:** Laravel 11.x
- **Frontend:** Blade Templates + Alpine.js + Tailwind CSS
- **Database:** MySQL 8.0+
- **Authentication:** Laravel Sanctum
- **Activity Logging:** Spatie Laravel Activity Log
- **PHP Version:** 8.2+

## 📁 Project Structure

```
backend_2/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/          # Admin controllers
│   │   ├── Patient/        # Patient controllers
│   │   └── Clinician/      # Clinician controllers
│   └── Models/             # Eloquent models
├── database/
│   ├── migrations/         # 34 migration files
│   └── seeders/            # Sample data
├── resources/
│   └── views/
│       ├── admin/          # Admin views
│       ├── patient/        # Patient views
│       ├── clinician/      # Clinician views
│       └── dashboards/     # Role-specific dashboards
├── routes/
│   └── web.php            # All web routes
├── DOCUMENTATION.md        # Full documentation
├── QUICK_START.md         # Quick reference
└── README.md              # This file
```

## ✅ Completed Features

### Super Admin
- ✅ User Management (CRUD with soft deletes)
- ✅ Role & Permission Management
- ✅ Organization oversight
- ✅ System-wide analytics

### Admin
- ✅ Patient Management (CRUD)
- ✅ User Management (org-scoped)
- ✅ Clinician assignment
- ✅ Organization reports

### Clinician
- ✅ View assigned patients
- ✅ Patient health overview
- ✅ Vital signs tracking
- ✅ Medication prescriptions (Coming Soon)
- ✅ Health alerts management (Coming Soon)

### Patient
- ✅ Profile management
- ✅ Record vital signs
- ✅ View medications
- ✅ Medication consent
- ✅ Wearable device connection
- ✅ Health data visualization

## 🚧 Coming Soon

- [ ] Organizations full CRUD
- [ ] Facilities & Departments management
- [ ] Appointment scheduling
- [ ] Video consultation
- [ ] PDF report generation
- [ ] Email & SMS notifications
- [ ] Real-time wearable sync
- [ ] Advanced analytics dashboard
- [ ] Mobile app (React Native)

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Reset database
php artisan migrate:fresh --seed
```

## 🐛 Troubleshooting

### Common Issues

**Buttons not visible:** Clear view cache with `php artisan view:clear`

**Username not showing:** Check Blade templates use `{{ '@' . auth()->user()->username }}`

**Database errors:** Reset with `php artisan migrate:fresh --seed`

**Route errors:** Clear route cache with `php artisan route:clear`

See [DOCUMENTATION.md](DOCUMENTATION.md#troubleshooting) for complete troubleshooting guide.

## 📊 Database Schema

### Core Tables
- **users** - Authentication and user data
- **patient_profiles** - Extended patient information
- **vital_signs** - Health measurements
- **medications** - Medication master data
- **patient_medications** - Prescriptions
- **health_alerts** - Patient alerts
- **roles** - Custom role definitions
- **wearable_daily_summaries** - Aggregated wearable data

See [DOCUMENTATION.md](DOCUMENTATION.md#database-structure) for complete schema.

## 🔒 Security

- ✅ CSRF protection on all forms
- ✅ Password hashing with bcrypt
- ✅ SQL injection protection via Eloquent
- ✅ XSS protection via Blade escaping
- ✅ Role-based authorization
- ✅ Encrypted sensitive tokens
- ✅ Activity audit logs

## 📄 License

Proprietary - All rights reserved.

**© 2025 MEDWELL - Digital Healthcare Platform**

## 👨‍💻 About Laravel

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
