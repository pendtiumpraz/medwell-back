# MEDWELL - Digital Healthcare Platform Documentation

## Table of Contents
1. [Project Overview](#project-overview)
2. [System Requirements](#system-requirements)
3. [Installation Guide](#installation-guide)
4. [Database Structure](#database-structure)
5. [User Roles & Permissions](#user-roles--permissions)
6. [Features by Role](#features-by-role)
7. [API Endpoints](#api-endpoints)
8. [Testing Guide](#testing-guide)
9. [Troubleshooting](#troubleshooting)

---

## Project Overview

**MEDWELL** is a comprehensive digital healthcare platform designed to manage patient health data, wearable device integration, medication tracking, and clinician-patient interactions.

### Tech Stack
- **Backend**: Laravel 11.x (PHP 8.2+)
- **Frontend**: Blade Templates + Alpine.js + Tailwind CSS
- **Database**: MySQL 8.0+
- **Authentication**: Laravel Sanctum
- **Activity Logging**: Spatie Laravel Activity Log

### Key Features
- ✅ Multi-role user management (Super Admin, Admin, Clinician, Patient, Support, Manager)
- ✅ Patient profile management with health metrics
- ✅ Wearable device integration (Fitbit, Apple Health, Samsung Health, Huawei Health)
- ✅ Medication management with consent tracking
- ✅ Vital signs monitoring (Blood Pressure, Glucose, Temperature, SpO2, Weight)
- ✅ Health alerts system
- ✅ Role-based access control (RBAC)
- ✅ Soft deletes for data recovery
- ✅ Activity logging for audit trails

---

## System Requirements

### Minimum Requirements
- **PHP**: 8.2 or higher
- **Composer**: 2.6+
- **MySQL**: 8.0+
- **Node.js**: 18.x+ (for frontend assets)
- **Web Server**: Apache 2.4+ / Nginx 1.18+

### PHP Extensions Required
```
- BCMath
- Ctype
- Fileinfo
- JSON
- Mbstring
- OpenSSL
- PDO
- PDO_MySQL
- Tokenizer
- XML
```

---

## Installation Guide

### 1. Clone & Setup
```bash
cd D:\AI\medwell\backend_2
composer install
cp .env.example .env
php artisan key:generate
```

### 2. Database Configuration
Edit `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=medwell
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Run Migrations & Seeders
```bash
php artisan migrate:fresh --seed
```

### 4. Install Frontend Assets
```bash
npm install
npm run build
```

### 5. Start Development Server
```bash
php artisan serve
```

Access at: `http://localhost:8000`

---

## Database Structure

### Core Tables

#### 1. **users**
Main user authentication table
- `id` - Primary key
- `organization_id` - FK to organizations
- `username` - Unique username
- `email` - Unique email
- `password` - Hashed password
- `role` - Enum: super_admin, admin, clinician, patient, support, manager
- `status` - Enum: active, inactive, suspended
- `phone` - Contact number
- `avatar` - Profile picture path
- `email_verified_at` - Verification timestamp
- Soft deletes enabled

#### 2. **patient_profiles**
Extended patient information
- `id` - Primary key
- `user_id` - FK to users (unique)
- `full_name` - Patient full name
- `date_of_birth` - DOB
- `gender` - Enum: male, female, other
- `phone`, `address`, `racial_origin`
- `height`, `weight`, `blood_type`
- `wearable_type` - Enum: fitbit, apple, samsung, huawei, none
- Wearable tokens (fitbit_*, huawei_*, apple_*, samsung_*)
- `onboarding_completed` - Boolean flag
- Soft deletes enabled

#### 3. **vital_signs**
Patient vital signs records
- `id` - Primary key
- `patient_id` - FK to patient_profiles
- `vital_type` - Enum: blood_pressure, glucose, temperature, spo2, weight, heart_rate
- Type-specific fields (systolic, diastolic, glucose_level, etc.)
- `recorded_at` - Measurement timestamp
- `source` - Enum: manual, wearable, clinic
- Soft deletes enabled

#### 4. **medications**
Medication master data
- `id` - Primary key
- `name`, `generic_name`, `brand_name`
- `form` - Enum: tablet, capsule, syrup, injection, etc.
- `strength`, `unit`
- `description`, `side_effects`, `contraindications`

#### 5. **patient_medications**
Patient prescriptions
- `id` - Primary key
- `patient_id` - FK to patient_profiles
- `medication_id` - FK to medications
- `prescriber_id` - FK to users (clinician)
- Dosage information (dosage, frequency, route)
- `start_date`, `end_date`
- `consent_status` - Enum: pending, accepted, declined
- `status` - Enum: active, completed, discontinued
- Soft deletes enabled

#### 6. **health_alerts**
Patient health alerts
- `id` - Primary key
- `patient_id` - FK to patient_profiles
- `alert_type` - Enum: critical_vital, medication_reminder, appointment, etc.
- `severity` - Enum: low, medium, high, critical
- `title`, `message`
- `status` - Enum: unread, read, acknowledged, resolved
- `triggered_at`, `acknowledged_at`, `resolved_at`

#### 7. **roles**
Custom role definitions
- `id` - Primary key
- `name` - Unique role slug
- `display_name` - Human-readable name
- `description`
- `level` - Hierarchy level (0-10, lower = higher authority)
- `is_system` - System role flag (cannot be deleted)
- `permissions` - JSON array of permission strings

#### 8. **wearable_daily_summaries**
Aggregated wearable data
- `id` - Primary key
- `patient_id` - FK to patient_profiles
- `date` - Summary date
- Activity metrics (steps, distance, floors_climbed, active_minutes, calories_burned)
- Heart metrics (resting_heart_rate, avg_heart_rate, max_heart_rate)
- Sleep metrics (sleep_duration, deep_sleep, light_sleep, rem_sleep)
- `device_type`, `last_synced_at`

### Relationships Overview
```
users (1) ----< (M) patient_profiles
patient_profiles (1) ----< (M) vital_signs
patient_profiles (1) ----< (M) patient_medications
medications (1) ----< (M) patient_medications
users (clinician) (1) ----< (M) patient_medications (prescriber)
patient_profiles (M) ----< (M) users (clinician) via patient_clinician pivot
patient_profiles (1) ----< (M) health_alerts
patient_profiles (1) ----< (M) wearable_daily_summaries
```

---

## User Roles & Permissions

### 1. **Super Admin** (Level 0)
**Full system access**
- ✅ Manage all users, roles, and permissions
- ✅ Manage organizations
- ✅ View system-wide analytics
- ✅ Access audit logs
- ✅ Override all permissions

**Default Account:**
- Email: `superadmin@medwell.id`
- Password: `password123`
- Username: `superadmin`

### 2. **Admin** (Level 1)
**Organization-level management**
- ✅ Manage users within organization
- ✅ Manage patients
- ✅ View organization reports
- ✅ Assign clinicians to patients
- ❌ Cannot manage roles/permissions
- ❌ Cannot access other organizations

**Default Account:**
- Email: `admin@biofarma.co.id`
- Password: `password123`
- Username: `admin_biofarma`

### 3. **Clinician** (Level 2)
**Patient care provider**
- ✅ View assigned patients
- ✅ Record vital signs
- ✅ Prescribe medications
- ✅ View patient health data
- ✅ Manage health alerts
- ❌ Cannot access other clinicians' patients (unless assigned)
- ❌ Cannot manage users

**Default Accounts:**
- **Dr. Sarah (Cardiologist)**
  - Email: `sarah.cardio@biofarma.co.id`
  - Password: `password123`
  - Username: `dr_sarah`
  
- **Dr. Budi (General Practitioner)**
  - Email: `budi.gp@biofarma.co.id`
  - Password: `password123`
  - Username: `dr_budi`

### 4. **Patient** (Level 5)
**Self-service health management**
- ✅ View own health profile
- ✅ Record vital signs
- ✅ View medications
- ✅ Accept/decline medication consent
- ✅ Connect wearable devices
- ✅ View health alerts
- ❌ Cannot access other patients' data
- ❌ Cannot prescribe medications

**Default Accounts:**
- **John Doe**
  - Email: `john.doe@email.com`
  - Password: `password123`
  - Username: `john_doe`
  
- **Jane Smith**
  - Email: `jane.smith@email.com`
  - Password: `password123`
  - Username: `jane_smith`

### 5. **Support** (Level 8)
**Customer support team**
- ✅ View patient profiles (read-only)
- ✅ Access support tickets
- ✅ View system notifications
- ❌ Cannot modify patient data
- ❌ Cannot access admin functions

### 6. **Manager** (Level 3)
**Business operations management**
- ✅ View analytics and reports
- ✅ Monitor system performance
- ✅ Access aggregated data
- ❌ Cannot modify user data
- ❌ Cannot access clinical functions

---

## Features by Role

### Super Admin Dashboard
**Location:** `/super-admin/dashboard`

**Features:**
1. **User Management** (`/admin/users`)
   - Create/Edit/Delete users
   - Change passwords
   - Assign roles
   - Filter by role/status
   - Soft delete with restore

2. **Roles & Permissions** (`/admin/roles`)
   - Create custom roles
   - Assign permissions
   - Set hierarchy levels
   - Cannot delete system roles

3. **Organizations** (Coming Soon)
   - Multi-tenant management
   - Organization settings

4. **Audit Logs** (Coming Soon)
   - Activity log viewer
   - User action tracking

### Admin Dashboard
**Location:** `/admin/dashboard`

**Features:**
1. **Patient Management** (`/admin/patients`)
   - Add new patients
   - Edit patient profiles
   - View patient details
   - Assign clinicians
   - Track onboarding status
   - Filter by wearable/status

2. **User Management** (Limited)
   - View organization users
   - Cannot create super admins

3. **Reports** (Coming Soon)
   - Patient statistics
   - Health metrics overview

### Clinician Dashboard
**Location:** `/clinician/dashboard`

**Features:**
1. **My Patients** (`/clinician/patients`)
   - View assigned patients
   - Patient health overview
   - Quick vital stats
   - Wearable status

2. **Patient Details** (`/clinician/patients/{id}`)
   - Complete patient profile
   - Vital signs history
   - Medication list
   - Active health alerts

3. **Vital Signs** (Coming Soon)
   - Record patient vitals
   - View vital trends

4. **Medications** (Coming Soon)
   - Prescribe medications
   - View prescription history

5. **Alerts** (Coming Soon)
   - Manage patient alerts
   - Acknowledge critical alerts

### Patient Dashboard
**Location:** `/patient/dashboard`

**Features:**
1. **My Profile** (`/patient/profile`)
   - View personal info
   - Update profile
   - Change password
   - Upload avatar

2. **Vital Signs** (`/patient/vitals`)
   - View vital history
   - Record new vitals:
     - Blood pressure
     - Glucose
     - Temperature
     - SpO2
     - Weight

3. **Medications** (`/patient/medications`)
   - View prescriptions
   - Accept/decline consent
   - Medication schedule
   - Mark as taken

4. **Wearable Devices** (`/patient/wearables`)
   - Connect devices:
     - Fitbit
     - Apple Health
     - Samsung Health
     - Huawei Health
   - Manual sync
   - Disconnect device
   - View sync status

5. **Appointments** (Coming Soon)
6. **Health Alerts** (Coming Soon)

---

## API Endpoints

### Authentication
```
POST   /login           - User login
POST   /logout          - User logout
POST   /register        - New user registration (patients only)
POST   /password/email  - Password reset request
POST   /password/reset  - Password reset confirmation
```

### Super Admin Routes
**Prefix:** `/super-admin`

```
GET    /dashboard                    - Super admin dashboard
GET    /settings                     - System settings (Coming Soon)
GET    /audit-logs                   - Audit logs (Coming Soon)
```

### Admin Routes
**Prefix:** `/admin`

```
# Users
GET    /users                        - List users
GET    /users/create                 - Create user form
POST   /users                        - Store new user
GET    /users/{id}                   - View user
GET    /users/{id}/edit              - Edit user form
PUT    /users/{id}                   - Update user
DELETE /users/{id}                   - Delete user
PUT    /users/{id}/restore           - Restore deleted user
DELETE /users/{id}/force             - Permanently delete user
PUT    /users/{id}/change-password   - Change user password

# Roles
GET    /roles                        - List roles
GET    /roles/create                 - Create role form
POST   /roles                        - Store new role
GET    /roles/{id}                   - View role
GET    /roles/{id}/edit              - Edit role form
PUT    /roles/{id}                   - Update role
DELETE /roles/{id}                   - Delete role

# Patients
GET    /patients                     - List patients
GET    /patients/create              - Create patient form
POST   /patients                     - Store new patient
GET    /patients/{id}                - View patient
GET    /patients/{id}/edit           - Edit patient form
PUT    /patients/{id}                - Update patient
DELETE /patients/{id}                - Delete patient
```

### Clinician Routes
**Prefix:** `/clinician`

```
GET    /dashboard                    - Clinician dashboard
GET    /patients                     - List assigned patients
GET    /patients/{id}                - View patient details
```

### Patient Routes
**Prefix:** `/patient`

```
GET    /dashboard                    - Patient dashboard

# Profile
GET    /profile                      - View profile
PUT    /profile                      - Update profile
POST   /profile/avatar               - Upload avatar
POST   /profile/password             - Change password

# Vital Signs
GET    /vitals                       - List vitals
GET    /vitals/create                - Record vitals form
POST   /vitals/blood-pressure        - Store blood pressure
POST   /vitals/glucose               - Store glucose
POST   /vitals/temperature           - Store temperature
POST   /vitals/spo2                  - Store SpO2
POST   /vitals/weight                - Store weight

# Medications
GET    /medications                  - List medications
POST   /medications/{id}/consent     - Accept/decline medication
GET    /medications/schedule         - View schedule
POST   /medications/log              - Log medication intake
POST   /medications/{id}/mark-taken  - Mark as taken

# Wearables
GET    /wearables                    - View wearable status
GET    /wearables/connect/{type}     - Connect wearable device
GET    /wearables/{type}/callback    - OAuth callback
POST   /wearables/sync               - Manual sync
DELETE /wearables/disconnect         - Disconnect device
```

---

## Testing Guide

### Manual Testing Checklist

#### 1. **Authentication Tests**
- [ ] Login with each role
- [ ] Logout
- [ ] Password reset flow
- [ ] Invalid credentials handling

#### 2. **Super Admin Tests**
- [ ] Create new user
- [ ] Edit existing user
- [ ] Delete user (soft delete)
- [ ] Restore deleted user
- [ ] Force delete user
- [ ] Change user password
- [ ] Create custom role
- [ ] Edit role permissions
- [ ] Delete role (check system role protection)

#### 3. **Admin Tests**
- [ ] Add new patient
- [ ] Edit patient profile
- [ ] View patient details
- [ ] Search patients
- [ ] Filter patients (wearable, onboarding status)
- [ ] View soft deleted patients

#### 4. **Clinician Tests**
- [ ] View assigned patients
- [ ] View patient health details
- [ ] Check wearable connection status
- [ ] View patient vital trends

#### 5. **Patient Tests**
- [ ] View profile
- [ ] Update profile information
- [ ] Upload avatar
- [ ] Change password
- [ ] Record blood pressure
- [ ] Record glucose level
- [ ] Record temperature
- [ ] Record SpO2
- [ ] Record weight
- [ ] View vital history
- [ ] View medications
- [ ] Accept medication consent
- [ ] Decline medication consent
- [ ] Connect Huawei Health (simulated)
- [ ] Connect Samsung Health (simulated)
- [ ] Manual wearable sync
- [ ] Disconnect wearable

#### 6. **UI/UX Tests**
- [ ] All buttons visible in sidebar forms
- [ ] Submit buttons work (sticky at bottom)
- [ ] Validation messages display
- [ ] Success messages display after actions
- [ ] Error messages display on failure
- [ ] Username displays correctly (no @ escaping issues)
- [ ] Role toggle permissions work
- [ ] Sidebar menu changes per role
- [ ] "Coming Soon" features disabled properly

### Automated Testing
```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature

# Run with coverage
php artisan test --coverage
```

---

## Troubleshooting

### Common Issues

#### 1. **Button text not visible in sidebar forms**
**Symptom:** Submit/Cancel buttons appear blank

**Solution:**
```blade
<!-- Wrong -->
<button type="submit" class="py-3 px-6">
    Save
</button>

<!-- Correct -->
<button type="submit" class="py-2 px-4 text-sm sticky bottom-0">
    <i class="fas fa-save mr-1"></i> Save
</button>
```

#### 2. **Username not displaying (shows literal `{{ auth()->user()->username }}`)**
**Symptom:** Username appears as `@{{ auth()->user()->username }}`

**Solution:**
```blade
<!-- Wrong -->
@{{ auth()->user()->username }}

<!-- Correct -->
{{ '@' . auth()->user()->username }}
```

#### 3. **Undefined variable errors in views**
**Symptom:** `Undefined variable $latestVital` or `$pendingConsentMeds`

**Solution:** Ensure controller passes all required variables:
```php
public function index() {
    $patient = auth()->user()->patientProfile;
    
    $latestVital = VitalSign::where('patient_id', $patient->id)->latest()->first();
    $pendingConsentMeds = PatientMedication::where('patient_id', $patient->id)
        ->where('consent_status', 'pending')
        ->get();
    
    return view('patient.profile.index', compact('patient', 'latestVital', 'pendingConsentMeds'));
}
```

#### 4. **Permission toggle not working**
**Symptom:** "Toggle All" button doesn't check/uncheck permissions

**Solution:** Ensure JavaScript function exists:
```javascript
<script>
function toggleGroup(group) {
    const checkboxes = document.querySelectorAll(`input[data-group="${group}"]`);
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    checkboxes.forEach(cb => cb.checked = !allChecked);
}
</script>
```

#### 5. **Route not found errors**
**Symptom:** `Route [clinician.vitals.create] not defined`

**Solution:** Comment out incomplete routes and replace with placeholders:
```blade
<!-- Wrong -->
<a href="{{ route('clinician.vitals.create', $patient->id) }}">

<!-- Correct -->
<a href="#" class="opacity-50 cursor-not-allowed" title="Coming Soon">
    Record Vitals (Coming Soon)
</a>
```

#### 6. **SQL Ambiguous column error**
**Symptom:** `Column 'id' in field list is ambiguous`

**Solution:** Specify table in select:
```php
// Wrong
return $this->belongsToMany(PatientProfile::class, 'patient_clinician');

// Correct
return $this->belongsToMany(PatientProfile::class, 'patient_clinician')
            ->select('patient_profiles.*');
```

#### 7. **Patient creation fails silently**
**Symptom:** Form submits but no success/error message

**Solution:** 
1. Check controller returns redirect with message:
```php
return redirect()->route('admin.patients.show', $patient->id)
    ->with('success', 'Patient created successfully.');
```

2. Ensure view displays session messages:
```blade
@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
    {{ session('success') }}
</div>
@endif
```

### Database Issues

#### Reset database
```bash
php artisan migrate:fresh --seed
```

#### Clear all caches
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

#### Check database connection
```bash
php artisan tinker
> DB::connection()->getPdo();
```

---

## Development Notes

### Code Standards
- Follow PSR-12 coding standards
- Use Laravel best practices
- Always validate user input
- Use soft deletes for user-facing data
- Log important activities
- Never expose sensitive data in responses

### Security Checklist
- ✅ All routes protected by authentication
- ✅ Role-based middleware on all admin routes
- ✅ CSRF protection on all forms
- ✅ Password hashing with bcrypt
- ✅ SQL injection protection via Eloquent
- ✅ XSS protection via Blade escaping
- ✅ Sensitive tokens encrypted in database

### Performance Tips
- Use eager loading to prevent N+1 queries
- Cache frequently accessed data
- Paginate large datasets
- Optimize database indexes
- Use queue for heavy operations

---

## Future Enhancements

### Planned Features
- [ ] Organizations full CRUD
- [ ] Facilities management
- [ ] Departments management
- [ ] Medications admin CRUD
- [ ] Appointment scheduling
- [ ] Video consultation
- [ ] Prescription PDF generation
- [ ] Email notifications
- [ ] SMS alerts
- [ ] Push notifications
- [ ] Real-time wearable sync
- [ ] Advanced analytics dashboard
- [ ] Export reports (PDF, Excel)
- [ ] Multi-language support
- [ ] Mobile app (React Native)

---

## Support & Contact

**Project Status:** Development Phase  
**Version:** 1.0.0-beta  
**Last Updated:** 2025-11-06

For issues and questions, refer to this documentation or check the codebase comments.

---

## License

Proprietary - All rights reserved.

**© 2025 MEDWELL - Digital Healthcare Platform**
