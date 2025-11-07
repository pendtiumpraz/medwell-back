<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\VitalSignApiController;
use App\Http\Controllers\Api\MedicationApiController;
use App\Http\Controllers\Api\WearableApiController;
use App\Http\Controllers\Api\AlertApiController;
use App\Http\Controllers\Api\NotificationApiController;
use App\Http\Controllers\Api\DocumentApiController;
use App\Http\Controllers\Api\MessageApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| 
| All routes are prefixed with /api
| Authentication: Laravel Sanctum (Bearer token)
*/

// Public API routes
Route::prefix('v1')->group(function () {
    
    // Health check
    Route::get('/health', function () {
        return response()->json([
            'success' => true,
            'message' => 'Medwell API is running',
            'version' => '1.0.0',
            'timestamp' => now()->toIso8601String(),
        ]);
    });

    // ============================
    // AUTHENTICATION
    // ============================
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthApiController::class, 'login']);
        Route::post('/register', [AuthApiController::class, 'register']);
        
        // Protected auth routes
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [AuthApiController::class, 'logout']);
            Route::get('/me', [AuthApiController::class, 'me']);
            Route::post('/refresh', [AuthApiController::class, 'refresh']);
        });
    });

    // ============================
    // PROTECTED ROUTES (require authentication)
    // ============================
    Route::middleware('auth:sanctum')->group(function () {

        // ============================
        // PATIENT API
        // ============================
        Route::prefix('patient')->group(function () {
            // Profile
            // Route::get('/profile', [\App\Http\Controllers\Api\PatientApiController::class, 'getProfile']);
            // Route::put('/profile', [\App\Http\Controllers\Api\PatientApiController::class, 'updateProfile']);
            
            // Dashboard
            // Route::get('/dashboard', [\App\Http\Controllers\Api\PatientApiController::class, 'dashboard']);
            
            // Onboarding
            // Route::post('/onboarding/complete', [\App\Http\Controllers\Api\PatientApiController::class, 'completeOnboarding']);
        });

        // ============================
        // VITAL SIGNS API
        // ============================
        Route::prefix('vitals')->group(function () {
            // Log vitals
            Route::post('/blood-pressure', [VitalSignApiController::class, 'storeBloodPressure']);
            Route::post('/blood-glucose', [VitalSignApiController::class, 'storeGlucose']);
            Route::post('/temperature', [VitalSignApiController::class, 'storeTemperature']);
            Route::post('/spo2', [VitalSignApiController::class, 'storeSpo2']);
            Route::post('/weight', [VitalSignApiController::class, 'storeWeight']);
            
            // Get vitals
            Route::get('/history', [VitalSignApiController::class, 'history']);
            Route::get('/trends', [VitalSignApiController::class, 'trends']);
            Route::get('/latest', [VitalSignApiController::class, 'latest']);
        });

        // ============================
        // MEDICATIONS API
        // ============================
        Route::prefix('medications')->group(function () {
            // Get medications
            Route::get('/', [MedicationApiController::class, 'index']);
            Route::get('/{id}', [MedicationApiController::class, 'show']);
            
            // Consent
            Route::post('/{id}/consent', [MedicationApiController::class, 'consent']);
            
            // Schedule
            Route::get('/schedule', [MedicationApiController::class, 'schedule']);
            Route::get('/schedule/today', [MedicationApiController::class, 'todaySchedule']);
            
            // Log adherence
            Route::post('/log', [MedicationApiController::class, 'log']);
            Route::post('/{scheduleId}/taken', [MedicationApiController::class, 'markTaken']);
            Route::post('/{scheduleId}/delayed', [MedicationApiController::class, 'markDelayed']);
            Route::post('/{scheduleId}/missed', [MedicationApiController::class, 'markMissed']);
            
            // Statistics
            Route::get('/adherence/rate', [MedicationApiController::class, 'adherenceRate']);
        });

        // ============================
        // WEARABLES API
        // ============================
        Route::prefix('wearables')->group(function () {
            // Connection status
            Route::get('/status', [WearableApiController::class, 'status']);
            
            // Fitbit
            Route::post('/fitbit/connect', [WearableApiController::class, 'connectFitbit']);
            Route::get('/fitbit/callback', [WearableApiController::class, 'fitbitCallback']);
            Route::post('/fitbit/sync', [WearableApiController::class, 'syncFitbit']);
            
            // Huawei
            Route::post('/huawei/connect', [WearableApiController::class, 'connectHuawei']);
            Route::get('/huawei/callback', [WearableApiController::class, 'huaweiCallback']);
            Route::post('/huawei/sync', [WearableApiController::class, 'syncHuawei']);
            
            // Apple Watch (HealthKit)
            Route::post('/apple/sync', [WearableApiController::class, 'syncApple']);
            
            // Samsung
            Route::post('/samsung/connect', [WearableApiController::class, 'connectSamsung']);
            Route::post('/samsung/sync', [WearableApiController::class, 'syncSamsung']);
            
            // Disconnect
            Route::delete('/disconnect', [WearableApiController::class, 'disconnect']);
            
            // Get wearable data
            Route::get('/data', [WearableApiController::class, 'getData']);
            Route::get('/data/today', [WearableApiController::class, 'getTodayData']);
        });

        // ============================
        // HEALTH ALERTS API
        // ============================
        Route::prefix('alerts')->group(function () {
            // Get alerts
            Route::get('/', [AlertApiController::class, 'index']);
            Route::get('/unresolved', [AlertApiController::class, 'unresolved']);
            Route::get('/critical', [AlertApiController::class, 'critical']);
            
            // Acknowledge (clinician only)
            Route::post('/{id}/acknowledge', [AlertApiController::class, 'acknowledge']);
            
            // Resolve (clinician only)
            Route::post('/{id}/resolve', [AlertApiController::class, 'resolve']);
        });

        // ============================
        // NOTIFICATIONS API
        // ============================
        Route::prefix('notifications')->group(function () {
            // Get notifications
            Route::get('/', [NotificationApiController::class, 'index']);
            Route::get('/unread', [NotificationApiController::class, 'unread']);
            Route::get('/unread/count', [NotificationApiController::class, 'unreadCount']);
            
            // Mark as read
            Route::post('/{id}/read', [NotificationApiController::class, 'markAsRead']);
            Route::post('/read-all', [NotificationApiController::class, 'markAllAsRead']);
            
            // Delete
            Route::delete('/{id}', [NotificationApiController::class, 'destroy']);
        });

        // ============================
        // DOCUMENTS API
        // ============================
        Route::prefix('documents')->group(function () {
            // Get documents
            Route::get('/', [DocumentApiController::class, 'index']);
            Route::get('/{id}', [DocumentApiController::class, 'show']);
            Route::get('/{id}/download', [DocumentApiController::class, 'download']);
            
            // Upload (patient can upload)
            Route::post('/upload', [DocumentApiController::class, 'upload']);
            
            // Mark as viewed
            Route::post('/{id}/viewed', [DocumentApiController::class, 'markAsViewed']);
        });

        // ============================
        // MESSAGES API
        // ============================
        Route::prefix('messages')->group(function () {
            // Get messages
            Route::get('/', [MessageApiController::class, 'index']);
            Route::get('/{id}', [MessageApiController::class, 'show']);
            
            // Send message
            Route::post('/', [MessageApiController::class, 'store']);
            
            // Mark as read
            Route::post('/{id}/read', [MessageApiController::class, 'markAsRead']);
            
            // Get conversation
            Route::get('/conversation/{userId}', [MessageApiController::class, 'conversation']);
        });

        // ============================
        // SCHEDULES API
        // ============================
        Route::prefix('schedules')->group(function () {
            // Get schedules
            // Route::get('/', [\App\Http\Controllers\Api\ScheduleApiController::class, 'index']);
            // Route::get('/today', [\App\Http\Controllers\Api\ScheduleApiController::class, 'today']);
            // Route::get('/upcoming', [\App\Http\Controllers\Api\ScheduleApiController::class, 'upcoming']);
        });

        // ============================
        // CLINICIAN API (for mobile app)
        // ============================
        Route::prefix('clinician')->middleware('check.role:clinician,health_coach')->group(function () {
            // Patients
            // Route::get('/patients', [\App\Http\Controllers\Api\ClinicianApiController::class, 'patients']);
            // Route::get('/patients/{id}', [\App\Http\Controllers\Api\ClinicianApiController::class, 'patientDetail']);
            
            // Quick stats
            // Route::get('/dashboard', [\App\Http\Controllers\Api\ClinicianApiController::class, 'dashboard']);
            
            // Prescribe medication
            // Route::post('/patients/{id}/prescribe', [\App\Http\Controllers\Api\ClinicianApiController::class, 'prescribe']);
        });
    });
});
