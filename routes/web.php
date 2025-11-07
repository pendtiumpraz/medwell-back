<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\PatientController as AdminPatientController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Clinician\PatientController as ClinicianPatientController;
use App\Http\Controllers\Clinician\VitalSignController as ClinicianVitalSignController;
use App\Http\Controllers\Clinician\MedicationController as ClinicianMedicationController;
use App\Http\Controllers\Clinician\AlertController as ClinicianAlertController;
use App\Http\Controllers\Clinician\MessageController as ClinicianMessageController;
use App\Http\Controllers\Patient\ProfileController;
use App\Http\Controllers\Patient\VitalSignController as PatientVitalSignController;
use App\Http\Controllers\Patient\MedicationController as PatientMedicationController;
use App\Http\Controllers\Patient\WearableController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', function () {
    return view('landing');
})->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    // Password reset routes (placeholder)
    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Authenticated routes
Route::middleware('auth')->group(function () {
    
    // General dashboard (redirects based on role)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ============================
    // NOTIFICATIONS (All Users)
    // ============================
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread', [\App\Http\Controllers\NotificationController::class, 'unread'])->name('notifications.unread');
    Route::get('/notifications/create', [\App\Http\Controllers\NotificationController::class, 'create'])->name('notifications.create');
    Route::post('/notifications', [\App\Http\Controllers\NotificationController::class, 'store'])->name('notifications.store');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    Route::delete('/notifications/{id}', [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');

    // ============================
    // SUPER ADMIN ROUTES
    // ============================
    Route::middleware('check.role:super_admin')->prefix('super-admin')->name('super-admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'superAdmin'])->name('dashboard');
        
        // Users Management (Super Admin)
        Route::get('/users/{id}/json', [AdminUserController::class, 'getJson'])->name('users.json');
        Route::resource('users', AdminUserController::class);
        Route::put('/users/{id}/change-password', [AdminUserController::class, 'changePassword'])->name('users.change-password');
        Route::post('/users/{id}/restore', [AdminUserController::class, 'restore'])->name('users.restore');
        Route::delete('/users/{id}/force-delete', [AdminUserController::class, 'forceDelete'])->name('users.force-delete');
        
        // Roles & Permissions (Super Admin)
        Route::resource('roles', RoleController::class);
        Route::post('/roles/{id}/assign-user', [RoleController::class, 'assignUser'])->name('roles.assign-user');
        Route::post('/roles/{id}/remove-user', [RoleController::class, 'removeUser'])->name('roles.remove-user');
        
        // Patients Management (Super Admin)
        Route::get('/patients/{id}/json', [AdminPatientController::class, 'getJson'])->name('patients.json');
        Route::resource('patients', AdminPatientController::class);
        Route::post('/patients/{id}/restore', [AdminPatientController::class, 'restore'])->name('patients.restore');
        Route::delete('/patients/{id}/force-delete', [AdminPatientController::class, 'forceDelete'])->name('patients.force-delete');
        Route::post('/patients/{id}/assign-clinician', [AdminPatientController::class, 'assignClinician'])->name('patients.assign-clinician');
        Route::post('/patients/{id}/remove-clinician', [AdminPatientController::class, 'removeClinician'])->name('patients.remove-clinician');
        
        // Organizations (Super Admin only)
        Route::get('/organizations/{id}/json', [\App\Http\Controllers\Admin\OrganizationController::class, 'getJson'])->name('organizations.json');
        Route::resource('organizations', \App\Http\Controllers\Admin\OrganizationController::class);
        
        // Facilities
        Route::get('/facilities/{id}/json', [\App\Http\Controllers\Admin\FacilityController::class, 'getJson'])->name('facilities.json');
        Route::resource('facilities', \App\Http\Controllers\Admin\FacilityController::class);
        
        // Departments
        Route::get('/departments/{id}/json', [\App\Http\Controllers\Admin\DepartmentController::class, 'getJson'])->name('departments.json');
        Route::resource('departments', \App\Http\Controllers\Admin\DepartmentController::class);
        
        // Medications Master
        Route::get('/medications/{id}/json', [\App\Http\Controllers\Admin\MedicationController::class, 'getJson'])->name('medications.json');
        Route::resource('medications', \App\Http\Controllers\Admin\MedicationController::class);
        
        // System settings
        Route::get('/settings', function () { return view('super-admin.settings'); })->name('settings');
        Route::post('/settings', function () { /* Save settings */ })->name('settings.save');
        
        // Audit logs
        Route::get('/audit-logs', function () { return view('super-admin.audit-logs'); })->name('audit-logs');
    });

    // ============================
    // ADMIN ROUTES (organization_admin, admin)
    // ============================
    Route::middleware('check.role:super_admin,organization_admin,admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
        
        // Patients Management
        Route::get('/patients/{id}/json', [AdminPatientController::class, 'getJson'])->name('patients.json');
        Route::resource('patients', AdminPatientController::class);
        Route::post('/patients/{id}/restore', [AdminPatientController::class, 'restore'])->name('patients.restore');
        Route::delete('/patients/{id}/force-delete', [AdminPatientController::class, 'forceDelete'])->name('patients.force-delete');
        Route::post('/patients/{id}/assign-clinician', [AdminPatientController::class, 'assignClinician'])->name('patients.assign-clinician');
        Route::post('/patients/{id}/remove-clinician', [AdminPatientController::class, 'removeClinician'])->name('patients.remove-clinician');
        
        // Users Management
        Route::get('/users/{id}/json', [AdminUserController::class, 'getJson'])->name('users.json');
        Route::resource('users', AdminUserController::class);
        Route::put('/users/{id}/change-password', [AdminUserController::class, 'changePassword'])->name('users.change-password');
        Route::post('/users/{id}/restore', [AdminUserController::class, 'restore'])->name('users.restore')->middleware('check.role:super_admin');
        Route::delete('/users/{id}/force-delete', [AdminUserController::class, 'forceDelete'])->name('users.force-delete')->middleware('check.role:super_admin');
        
        // Roles & Permissions Management
        Route::resource('roles', RoleController::class);
        Route::post('/roles/{id}/assign-user', [RoleController::class, 'assignUser'])->name('roles.assign-user');
        Route::post('/roles/{id}/remove-user', [RoleController::class, 'removeUser'])->name('roles.remove-user');
        
        // Permissions
        Route::get('/permissions', function () { return view('admin.permissions.index'); })->name('permissions.index');
        
        // Medications Master
        // Route::resource('medications', \App\Http\Controllers\Admin\MedicationController::class);
        
        // Documents
        // Route::resource('documents', \App\Http\Controllers\Admin\DocumentController::class);
        
        // Reports
        // Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
        // Route::get('/reports/patients', [\App\Http\Controllers\Admin\ReportController::class, 'patients'])->name('reports.patients');
        // Route::get('/reports/adherence', [\App\Http\Controllers\Admin\ReportController::class, 'adherence'])->name('reports.adherence');
    });

    // ============================
    // CLINICIAN ROUTES (clinician, health_coach)
    // ============================
    Route::middleware('check.role:clinician,health_coach')->prefix('clinician')->name('clinician.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'clinician'])->name('dashboard');
        
        // Patients (assigned only)
        Route::get('/patients', [ClinicianPatientController::class, 'index'])->name('patients.index');
        Route::get('/patients/{id}', [ClinicianPatientController::class, 'show'])->name('patients.show');
        
        // Vital Signs
        Route::get('/patients/{id}/vitals', [ClinicianVitalSignController::class, 'index'])->name('vitals.index');
        Route::post('/patients/{id}/vitals', [ClinicianVitalSignController::class, 'store'])->name('vitals.store');
        
        // Medications (clinician can prescribe)
        Route::get('/patients/{id}/medications', [ClinicianMedicationController::class, 'index'])->name('medications.index');
        Route::get('/patients/{id}/medications/create', [ClinicianMedicationController::class, 'create'])->name('medications.create');
        Route::post('/patients/{id}/medications', [ClinicianMedicationController::class, 'store'])->name('medications.store');
        Route::put('/medications/{id}', [ClinicianMedicationController::class, 'update'])->name('medications.update');
        Route::post('/medications/{id}/pause', [ClinicianMedicationController::class, 'pause'])->name('medications.pause');
        Route::post('/medications/{id}/discontinue', [ClinicianMedicationController::class, 'discontinue'])->name('medications.discontinue');
        
        // Health Alerts
        Route::get('/alerts', [ClinicianAlertController::class, 'index'])->name('alerts.index');
        Route::post('/alerts/{id}/acknowledge', [ClinicianAlertController::class, 'acknowledge'])->name('alerts.acknowledge');
        Route::post('/alerts/{id}/resolve', [ClinicianAlertController::class, 'resolve'])->name('alerts.resolve');
        
        // Messages
        Route::get('/messages', [ClinicianMessageController::class, 'index'])->name('messages.index');
        Route::post('/messages', [ClinicianMessageController::class, 'store'])->name('messages.store');
    });

    // ============================
    // PATIENT ROUTES
    // ============================
    Route::middleware('check.role:patient')->prefix('patient')->name('patient.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'patient'])->name('dashboard');
        
        // Profile
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile.index');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::post('/profile/avatar', [ProfileController::class, 'uploadAvatar'])->name('profile.upload-avatar');
        Route::post('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
        
        // Vital Signs
        Route::get('/vitals', [PatientVitalSignController::class, 'index'])->name('vitals.index');
        Route::get('/vitals/create', [PatientVitalSignController::class, 'create'])->name('vitals.create');
        Route::post('/vitals/blood-pressure', [PatientVitalSignController::class, 'storeBloodPressure'])->name('vitals.blood-pressure');
        Route::post('/vitals/glucose', [PatientVitalSignController::class, 'storeGlucose'])->name('vitals.glucose');
        Route::post('/vitals/temperature', [PatientVitalSignController::class, 'storeTemperature'])->name('vitals.temperature');
        Route::post('/vitals/spo2', [PatientVitalSignController::class, 'storeSpo2'])->name('vitals.spo2');
        Route::post('/vitals/weight', [PatientVitalSignController::class, 'storeWeight'])->name('vitals.weight');
        
        // Medications
        Route::get('/medications', [PatientMedicationController::class, 'index'])->name('medications.index');
        Route::post('/medications/{id}/consent', [PatientMedicationController::class, 'consent'])->name('medications.consent');
        Route::get('/medications/schedule', [PatientMedicationController::class, 'schedule'])->name('medications.schedule');
        Route::post('/medications/log', [PatientMedicationController::class, 'log'])->name('medications.log');
        Route::post('/medications/{id}/mark-taken', [PatientMedicationController::class, 'markTaken'])->name('medications.mark-taken');
        
        // Wearables
        Route::get('/wearables', [WearableController::class, 'index'])->name('wearables.index');
        Route::get('/wearables/connect/{type}', [WearableController::class, 'connect'])->name('wearables.connect');
        Route::get('/wearables/{type}/callback', [WearableController::class, 'callback'])->name('wearables.callback');
        Route::post('/wearables/sync', [WearableController::class, 'sync'])->name('wearables.sync');
        Route::delete('/wearables/disconnect', [WearableController::class, 'disconnect'])->name('wearables.disconnect');
    });
});
