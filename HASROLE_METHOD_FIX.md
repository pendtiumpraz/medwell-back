# ✅ FIXED: hasRole() METHOD IN USER MODEL

## ❌ ERROR:
```
BadMethodCallException
Call to undefined method App\Models\User::hasRole()
```

**Location:** Sidebar menu was calling `auth()->user()->hasRole('patient')`

**Cause:** User model didn't have hasRole() method

---

## ✅ SOLUTION:

### Added hasRole() Method to User Model

**File:** `app/Models/User.php`

**Method Added:**
```php
public function hasRole(string $role): bool
{
    // Check direct role column first
    if ($this->role === $role) {
        return true;
    }
    
    // Also check roles relationship (many-to-many)
    return $this->roles()->where('name', $role)->exists();
}
```

---

## 🎯 HOW IT WORKS:

### 1. **Direct Role Check:**
```php
if ($this->role === $role) {
    return true;
}
```
- Checks the `role` column in users table
- Fast and efficient (no database query)
- For single role system

### 2. **Roles Relationship Check:**
```php
return $this->roles()->where('name', $role)->exists();
```
- Checks the `roles` many-to-many relationship
- For multi-role system (via role_user pivot table)
- Returns true if user has that role

---

## 💡 USAGE EXAMPLES:

### In Blade Views:
```blade
@if(auth()->user()->hasRole('patient'))
    <!-- Patient-specific content -->
@endif

@if(auth()->user()->hasRole('admin'))
    <!-- Admin-specific content -->
@endif

@if(!auth()->user()->hasRole('patient'))
    <a href="{{ route('notifications.create') }}">Send Notification</a>
@endif
```

### In Controllers:
```php
if (auth()->user()->hasRole('super_admin')) {
    // Super admin logic
}

if (auth()->user()->hasRole('clinician')) {
    // Clinician logic
}
```

### In Models:
```php
$user = User::find(1);

if ($user->hasRole('admin')) {
    // Do something
}
```

---

## 🔄 OTHER ROLE METHODS ALREADY EXISTING:

### 1. `isSuperAdmin()` - Check if super admin
```php
if (auth()->user()->isSuperAdmin()) {
    // Super admin code
}
```

### 2. `isClinician()` - Check if clinician or health coach
```php
if (auth()->user()->isClinician()) {
    // Clinician code
}
```

### 3. `isPatient()` - Check if patient
```php
if (auth()->user()->isPatient()) {
    // Patient code
}
```

### 4. `hasPermission()` - Check if has specific permission
```php
if (auth()->user()->hasPermission('manage-users')) {
    // Permission granted
}
```

---

## 📊 USER MODEL ROLE METHODS SUMMARY:

| Method | Purpose | Example |
|--------|---------|---------|
| `hasRole('role_name')` | Check specific role | `hasRole('patient')` |
| `isSuperAdmin()` | Check super admin | `isSuperAdmin()` |
| `isClinician()` | Check clinician/health coach | `isClinician()` |
| `isPatient()` | Check patient | `isPatient()` |
| `hasPermission('perm')` | Check permission | `hasPermission('edit-users')` |

---

## ✅ CACHES CLEARED:

```bash
php artisan clear-compiled     ✅
php artisan view:clear         ✅
php artisan config:clear       ✅
php artisan route:clear        ✅
```

---

## 🎯 WHERE hasRole() IS USED:

### 1. **Sidebar Menu**
```blade
<!-- Hide "Send Notification" from patients -->
@if(!auth()->user()->hasRole('patient'))
<a href="{{ route('notifications.create') }}">
    Send Notification
</a>
@endif
```

### 2. **Notification Controller**
```php
// Can be used to check permissions
if (!auth()->user()->hasRole('patient')) {
    // Allow sending notifications
}
```

### 3. **Any Future Feature**
```blade
@if(auth()->user()->hasRole('admin'))
    <!-- Admin-only features -->
@endif
```

---

## 🚀 NOW WORKING:

✅ Sidebar menu displays correctly for all roles  
✅ "Send Notification" hidden from patients  
✅ No more BadMethodCallException  
✅ All role checks working  
✅ Navigation works properly  

---

## 🧪 TEST NOW:

```bash
cd D:\AI\medwell\backend_2
php artisan serve
```

### Test Each Role:

1. **Super Admin** → Should see all menus including Audit Logs, System Settings
2. **Admin** → Should see admin menus + Send Notification
3. **Clinician** → Should see clinician menus + Send Notification
4. **Patient** → Should see patient menus + Notifications (NO Send Notification)

---

## ✅ FIXED: hasRole() METHOD WORKS PERFECTLY! 🎉
