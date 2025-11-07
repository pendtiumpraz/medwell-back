# COMPLETE IMPLEMENTATION CODE - 4 FEATURES

## ✅ Database Migrations Status
- ✅ notifications table (already exists: 2024_01_01_000024)
- ✅ user_settings table (created: 2025_11_06_193448)
- ✅ system_settings table (already exists: 2024_01_01_000031)
- ✅ activity_log table (spatie package)

---

## STEP 1: CREATE MODELS

### File: `app/Models/Notification.php`
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'action_url',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Methods
    public function markAsRead()
    {
        $this->update(['read_at' => now()]);
    }

    public function isUnread()
    {
        return is_null($this->read_at);
    }

    // Type constants
    const TYPE_INFO = 'info';
    const TYPE_SUCCESS = 'success';
    const TYPE_WARNING = 'warning';
    const TYPE_ERROR = 'error';
    const TYPE_ALERT = 'alert';
}
```

### File: `app/Models/UserSetting.php`
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'key',
        'value',
    ];

    protected $casts = [
        'value' => 'array',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper methods
    public static function get($userId, $key, $default = null)
    {
        $setting = static::where('user_id', $userId)
            ->where('key', $key)
            ->first();

        return $setting ? $setting->value : $default;
    }

    public static function set($userId, $key, $value)
    {
        return static::updateOrCreate(
            ['user_id' => $userId, 'key' => $key],
            ['value' => $value]
        );
    }

    // Setting keys constants
    const THEME = 'theme';
    const LANGUAGE = 'language';
    const TIMEZONE = 'timezone';
    const NOTIFICATION_EMAIL = 'notification_email';
    const NOTIFICATION_PUSH = 'notification_push';
    const NOTIFICATION_SMS = 'notification_sms';
}
```

---

## STEP 2: CREATE CONTROLLERS

### File: `app/Http/Controllers/NotificationController.php`
```php
<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::forUser(auth()->id())
            ->latest()
            ->paginate(20);

        $unreadCount = Notification::forUser(auth()->id())->unread()->count();

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    public function unread()
    {
        $notifications = Notification::forUser(auth()->id())
            ->unread()
            ->latest()
            ->take(10)
            ->get();

        return response()->json($notifications);
    }

    public function markAsRead($id)
    {
        $notification = Notification::forUser(auth()->id())->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        Notification::forUser(auth()->id())
            ->unread()
            ->update(['read_at' => now()]);

        return response()->json(['success' => true, 'message' => 'All notifications marked as read']);
    }

    public function destroy($id)
    {
        $notification = Notification::forUser(auth()->id())->findOrFail($id);
        $notification->delete();

        return response()->json(['success' => true, 'message' => 'Notification deleted']);
    }
}
```

### File: `app/Http/Controllers/UserSettingController.php`
```php
<?php

namespace App\Http\Controllers;

use App\Models\UserSetting;
use Illuminate\Http\Request;

class UserSettingController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        
        $settings = [
            'theme' => UserSetting::get($userId, UserSetting::THEME, 'light'),
            'language' => UserSetting::get($userId, UserSetting::LANGUAGE, 'en'),
            'timezone' => UserSetting::get($userId, UserSetting::TIMEZONE, 'UTC'),
            'notification_email' => UserSetting::get($userId, UserSetting::NOTIFICATION_EMAIL, true),
            'notification_push' => UserSetting::get($userId, UserSetting::NOTIFICATION_PUSH, true),
            'notification_sms' => UserSetting::get($userId, UserSetting::NOTIFICATION_SMS, false),
        ];

        return view('settings.my-settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $userId = auth()->id();

        $validated = $request->validate([
            'theme' => 'required|in:light,dark',
            'language' => 'required|in:en,id',
            'timezone' => 'required|string',
            'notification_email' => 'boolean',
            'notification_push' => 'boolean',
            'notification_sms' => 'boolean',
        ]);

        foreach ($validated as $key => $value) {
            UserSetting::set($userId, $key, $value);
        }

        return redirect()->back()->with('success', 'Settings updated successfully');
    }
}
```

### File: `app/Http/Controllers/Admin/AuditLogController.php`
```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with(['causer', 'subject'])->latest();

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('causer_id', $request->user_id);
        }

        // Filter by action
        if ($request->filled('description')) {
            $query->where('description', 'like', "%{$request->description}%");
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(50);
        $users = \App\Models\User::select('id', 'username', 'email')->get();

        return view('admin.audit-logs.index', compact('logs', 'users'));
    }

    public function export()
    {
        // Export to CSV implementation
        $logs = Activity::with(['causer', 'subject'])->latest()->get();

        $csv = \League\Csv\Writer::createFromString('');
        $csv->insertOne(['ID', 'User', 'Action', 'Subject', 'Date']);

        foreach ($logs as $log) {
            $csv->insertOne([
                $log->id,
                $log->causer->username ?? 'System',
                $log->description,
                $log->subject_type,
                $log->created_at->format('Y-m-d H:i:s'),
            ]);
        }

        return response()->streamDownload(function() use ($csv) {
            echo $csv->toString();
        }, 'audit-logs-' . date('Y-m-d') . '.csv');
    }
}
```

### File: `app/Http/Controllers/Admin/SystemSettingController.php`
```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class SystemSettingController extends Controller
{
    public function index()
    {
        $organizationId = auth()->user()->organization_id;
        
        $settings = SystemSetting::where('organization_id', $organizationId)
            ->orWhereNull('organization_id')
            ->get()
            ->keyBy('key');

        return view('admin.settings.system', compact('settings'));
    }

    public function update(Request $request)
    {
        $organizationId = auth()->user()->organization_id;

        foreach ($request->except('_token') as $key => $value) {
            SystemSetting::updateOrCreate(
                [
                    'organization_id' => $organizationId,
                    'key' => $key
                ],
                [
                    'value' => $value,
                    'type' => $this->getType($value)
                ]
            );
        }

        return redirect()->back()->with('success', 'System settings updated successfully');
    }

    private function getType($value)
    {
        if (is_bool($value)) return 'boolean';
        if (is_numeric($value)) return 'number';
        if (is_array($value)) return 'array';
        return 'string';
    }
}
```

---

## STEP 3: ADD ROUTES

### File: `routes/web.php` - Add these routes:
```php
// Notifications (All authenticated users)
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread', [NotificationController::class, 'unread'])->name('notifications.unread');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    
    // My Settings (Personal)
    Route::get('/settings/my-settings', [UserSettingController::class, 'index'])->name('settings.my-settings');
    Route::post('/settings/my-settings', [UserSettingController::class, 'update'])->name('settings.my-settings.update');
});

// Admin routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    // Audit Logs
    Route::get('/audit-logs', [Admin\AuditLogController::class, 'index'])->name('audit-logs.index');
    Route::get('/audit-logs/export', [Admin\AuditLogController::class, 'export'])->name('audit-logs.export');
    
    // System Settings
    Route::get('/settings/system', [Admin\SystemSettingController::class, 'index'])->name('settings.system');
    Route::post('/settings/system', [Admin\SystemSettingController::class, 'update'])->name('settings.system.update');
});

// Super Admin routes
Route::prefix('super-admin')->name('super-admin.')->middleware(['auth', 'role:super_admin'])->group(function () {
    // Audit Logs
    Route::get('/audit-logs', [Admin\AuditLogController::class, 'index'])->name('audit-logs.index');
    Route::get('/audit-logs/export', [Admin\AuditLogController::class, 'export'])->name('audit-logs.export');
    
    // System Settings
    Route::get('/settings/system', [Admin\SystemSettingController::class, 'index'])->name('settings.system');
    Route::post('/settings/system', [Admin\SystemSettingController::class, 'update'])->name('settings.system.update');
});
```

---

## STEP 4: UPDATE SIDEBAR MENU

### File: `resources/views/layouts/partials/sidebar-menu.blade.php` - Add menu items:
```blade
<!-- Notifications (for all users) -->
<li class="mb-1">
    <a href="{{ route('notifications.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-purple-50 transition-colors {{ request()->routeIs('notifications.*') ? 'bg-purple-100 text-primary' : 'text-gray-700' }}">
        <i class="fas fa-bell w-5 text-center"></i>
        <span class="font-medium">Notifications</span>
        @if(auth()->user()->unread_notifications_count > 0)
            <span class="ml-auto bg-red-500 text-white text-xs rounded-full px-2 py-0.5">
                {{ auth()->user()->unread_notifications_count }}
            </span>
        @endif
    </a>
</li>

<!-- My Settings (for all users) -->
<li class="mb-1">
    <a href="{{ route('settings.my-settings') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-purple-50 transition-colors {{ request()->routeIs('settings.my-settings*') ? 'bg-purple-100 text-primary' : 'text-gray-700' }}">
        <i class="fas fa-user-cog w-5 text-center"></i>
        <span class="font-medium">My Settings</span>
    </a>
</li>

<!-- Admin Section -->
@if(auth()->user()->isAdmin() || auth()->user()->isSuperAdmin())
<li class="mt-4 mb-2 px-4">
    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Admin</span>
</li>

<!-- Audit Logs -->
<li class="mb-1">
    <a href="{{ route(auth()->user()->isSuperAdmin() ? 'super-admin.audit-logs.index' : 'admin.audit-logs.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-purple-50 transition-colors {{ request()->routeIs('*.audit-logs.*') ? 'bg-purple-100 text-primary' : 'text-gray-700' }}">
        <i class="fas fa-history w-5 text-center"></i>
        <span class="font-medium">Audit Logs</span>
    </a>
</li>

<!-- System Settings -->
<li class="mb-1">
    <a href="{{ route(auth()->user()->isSuperAdmin() ? 'super-admin.settings.system' : 'admin.settings.system') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-purple-50 transition-colors {{ request()->routeIs('*.settings.system*') ? 'bg-purple-100 text-primary' : 'text-gray-700' }}">
        <i class="fas fa-cog w-5 text-center"></i>
        <span class="font-medium">System Settings</span>
    </a>
</li>
@endif
```

---

## NEXT STEPS

1. Create view files for each feature (I'll provide these in separate files due to size)
2. Add notification bell to navbar
3. Test all features
4. Add seeder data for testing

Would you like me to:
A) Create all view files now?
B) Focus on one feature at a time?
C) Create a quick test/demo first?

Please let me know which approach you prefer!
