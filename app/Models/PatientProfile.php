<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatientProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'full_name', 'date_of_birth', 'gender', 'phone', 'address',
        'racial_origin', 'height', 'weight', 'blood_type',
        'wearable_type', 'fitbit_user_id', 'fitbit_access_token', 'fitbit_refresh_token',
        'fitbit_token_expires_at', 'huawei_user_id', 'huawei_access_token',
        'huawei_refresh_token', 'huawei_token_expires_at',
        'apple_user_id', 'apple_access_token', 'samsung_user_id', 'samsung_access_token',
        'onboarding_completed', 'onboarding_completed_at',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'onboarding_completed' => 'boolean',
        'onboarding_completed_at' => 'datetime',
        'fitbit_token_expires_at' => 'datetime',
        'huawei_token_expires_at' => 'datetime',
        'height' => 'decimal:2',
        'weight' => 'decimal:2',
    ];

    protected $hidden = [
        'fitbit_access_token', 'fitbit_refresh_token',
        'huawei_access_token', 'huawei_refresh_token',
        'apple_access_token', 'samsung_access_token',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function medicalConditions()
    {
        return $this->hasMany(MedicalCondition::class, 'patient_id');
    }

    public function allergies()
    {
        return $this->hasMany(Allergy::class, 'patient_id');
    }

    public function vitalSigns()
    {
        return $this->hasMany(VitalSign::class, 'patient_id');
    }

    public function latestVitals()
    {
        return $this->vitalSigns()->latest('recorded_at');
    }

    public function medications()
    {
        return $this->hasMany(PatientMedication::class, 'patient_id');
    }

    public function activeMedications()
    {
        return $this->medications()->where('status', 'active');
    }

    public function wearableData()
    {
        return $this->hasMany(WearableDailySummary::class, 'patient_id');
    }

    public function todayWearableData()
    {
        return $this->wearableData()->whereDate('date', today());
    }

    public function healthAlerts()
    {
        return $this->hasMany(HealthAlert::class, 'patient_id');
    }

    public function unresolvedAlerts()
    {
        return $this->healthAlerts()->where('resolved', false);
    }

    public function assignedClinicians()
    {
        return $this->belongsToMany(User::class, 'patient_clinician', 'patient_id', 'clinician_id')
                    ->wherePivot('is_active', true)
                    ->withPivot('role', 'assigned_at')
                    ->withTimestamps();
    }

    public function primaryClinician()
    {
        return $this->assignedClinicians()->wherePivot('role', 'primary')->first();
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'patient_id');
    }

    public function schedules()
    {
        return $this->hasMany(PatientSchedule::class, 'patient_id');
    }

    public function geofenceSettings()
    {
        return $this->hasOne(GeofenceSettings::class, 'patient_id');
    }

    public function geolocationLogs()
    {
        return $this->hasMany(GeolocationLog::class, 'patient_id');
    }

    // Helper methods
    public function getAgeAttribute()
    {
        return $this->date_of_birth->age;
    }

    public function getCurrentBMI()
    {
        $latestWeight = $this->vitalSigns()
            ->whereNotNull('weight')
            ->latest('recorded_at')
            ->value('weight');
        
        if ($latestWeight && $this->height) {
            $heightInMeters = $this->height / 100;
            return round($latestWeight / ($heightInMeters * $heightInMeters), 2);
        }
        
        return null;
    }

    public function hasWearableConnected(): bool
    {
        return $this->wearable_type !== 'none';
    }

    public function needsTokenRefresh(): bool
    {
        if ($this->wearable_type === 'fitbit' && $this->fitbit_token_expires_at) {
            return $this->fitbit_token_expires_at->isPast();
        }
        if ($this->wearable_type === 'huawei' && $this->huawei_token_expires_at) {
            return $this->huawei_token_expires_at->isPast();
        }
        return false;
    }

    public function getTodayWellnessScore()
    {
        return $this->todayWearableData()->value('wellness_score');
    }

    // Scopes
    public function scopeOnboarded($query)
    {
        return $query->where('onboarding_completed', true);
    }

    public function scopeNotOnboarded($query)
    {
        return $query->where('onboarding_completed', false);
    }

    public function scopeWithWearable($query)
    {
        return $query->where('wearable_type', '!=', 'none');
    }
}
