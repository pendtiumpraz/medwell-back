# MEDWELL - Quick Start Guide

## 🚀 Installation (5 Minutes)

```bash
# 1. Navigate to project
cd D:\AI\medwell\backend_2

# 2. Install dependencies
composer install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Configure database in .env
DB_DATABASE=medwell
DB_USERNAME=root
DB_PASSWORD=

# 5. Run migrations with seed data
php artisan migrate:fresh --seed

# 6. Start server
php artisan serve
```

**Access:** http://localhost:8000

---

## 🔐 Default Login Credentials

### Super Admin
- **Email:** `superadmin@medwell.id`
- **Password:** `password123`
- **Access:** Full system control

### Admin
- **Email:** `admin@biofarma.co.id`
- **Password:** `password123`
- **Access:** Organization management

### Clinician (Dr. Sarah)
- **Email:** `sarah.cardio@biofarma.co.id`
- **Password:** `password123`
- **Access:** Patient care

### Patient (John Doe)
- **Email:** `john.doe@email.com`
- **Password:** `password123`
- **Access:** Personal health data

---

## 📋 Quick Feature Test

### Test Super Admin Features (5 min)
1. Login as superadmin
2. Go to **Users** → Click **Add User**
3. Fill form, click **Create User**
4. Go to **Roles** → Click **Add Role**
5. Toggle permissions, click **Create Role**
6. ✅ Check success messages appear

### Test Admin Features (3 min)
1. Login as admin
2. Go to **Patients** → Click **Add Patient**
3. Fill all required fields (username, email, password, name, DOB, gender)
4. Click **Create Patient**
5. View patient details
6. ✅ Patient appears in list

### Test Patient Features (5 min)
1. Login as john.doe@email.com
2. Go to **My Profile** → Check username displays as `@john_doe`
3. Go to **Vital Signs** → Click **Record New**
4. Record blood pressure (e.g., 120/80, pulse 75)
5. Go to **Wearable Devices** → Click **Connect Huawei**
6. ✅ Device shows "Huawei Health Connected"

---

## 🐛 Common Issues & Quick Fixes

### Issue: Buttons not visible
```bash
# Clear all caches
php artisan view:clear
php artisan config:clear
php artisan route:clear
```

### Issue: Database error
```bash
# Reset database
php artisan migrate:fresh --seed
```

### Issue: Username not showing
- Check: Blade templates should use `{{ '@' . auth()->user()->username }}`
- NOT: `@{{ auth()->user()->username }}`

### Issue: Undefined variable error
- Controller must pass ALL required variables to view
- Check controller method returns `compact('var1', 'var2', ...)`

---

## 📁 Key Files Reference

### Controllers
```
app/Http/Controllers/
├── Admin/
│   ├── UserController.php          # User CRUD
│   ├── RoleController.php          # Role CRUD
│   └── PatientController.php       # Patient CRUD
├── Patient/
│   ├── ProfileController.php       # Patient profile
│   ├── VitalSignController.php     # Vital signs
│   ├── MedicationController.php    # Medications
│   └── WearableController.php      # Wearable devices
└── Clinician/
    └── PatientController.php       # Clinician patient view
```

### Views
```
resources/views/
├── admin/
│   ├── users/              # User management views
│   ├── roles/              # Role management views
│   └── patients/           # Patient management views
├── patient/
│   ├── profile/            # Patient profile
│   ├── vitals/             # Vital signs
│   ├── medications/        # Medications
│   └── wearables/          # Wearable devices
├── clinician/
│   └── patients/           # Clinician patient views
├── dashboards/             # Role-specific dashboards
└── layouts/
    ├── app.blade.php       # Main layout
    └── partials/
        └── sidebar-menu.blade.php  # Dynamic sidebar
```

### Models
```
app/Models/
├── User.php                    # User model
├── PatientProfile.php          # Patient profile
├── VitalSign.php               # Vital signs
├── Medication.php              # Medication master
├── PatientMedication.php       # Patient prescriptions
├── HealthAlert.php             # Health alerts
├── Role.php                    # Custom roles
└── WearableDailySummary.php    # Wearable data
```

---

## 🔍 Quick Debugging

### Check routes
```bash
php artisan route:list | findstr patients
```

### Check if user exists
```bash
php artisan tinker
> App\Models\User::where('email', 'john.doe@email.com')->first();
```

### View logs
```bash
# Check latest errors
tail -n 50 storage/logs/laravel.log
```

### Test database connection
```bash
php artisan tinker
> DB::connection()->getPdo();
```

---

## 📊 Project Status

### ✅ Completed Features
- User authentication & authorization
- Role-based access control (RBAC)
- User management (CRUD)
- Role management (CRUD)
- Patient management (CRUD)
- Patient profile management
- Vital signs tracking
- Medication management
- Wearable device integration (simulated)
- Health alerts system
- Activity logging
- Dynamic role-based sidebar
- Soft deletes with restore
- Form validation
- Success/error messages

### 🚧 Coming Soon
- Organizations CRUD
- Facilities management
- Departments management
- Appointment scheduling
- Real-time wearable sync
- Email notifications
- Advanced analytics
- PDF reports

---

## 💡 Pro Tips

1. **Always test with different roles** - Each role sees different features
2. **Check browser console** - JavaScript errors appear here
3. **Use validation** - All forms have built-in validation
4. **Soft deletes enabled** - Deleted data can be restored
5. **Activity logged** - All important actions are logged for audit

---

## 📞 Need More Help?

- **Full Documentation:** `DOCUMENTATION.md`
- **Troubleshooting:** See DOCUMENTATION.md → Troubleshooting section
- **Testing Checklist:** See DOCUMENTATION.md → Testing Guide

---

**Last Updated:** 2025-11-06  
**Version:** 1.0.0-beta
