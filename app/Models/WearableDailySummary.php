<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WearableDailySummary extends Model
{
    use HasFactory;

    protected $table = 'wearable_daily_summary';

    protected $fillable = [
        'patient_id',
        'date',
        'steps',
        'distance',
        'floors_climbed',
        'active_minutes',
        'calories_burned',
        'resting_heart_rate',
        'avg_heart_rate',
        'max_heart_rate',
        'min_heart_rate',
        'sleep_duration',
        'deep_sleep',
        'light_sleep',
        'rem_sleep',
        'awake_time',
        'sleep_score',
        'avg_spo2',
        'min_spo2',
        'wellness_score',
        'last_synced_at',
        'device_type',
    ];

    protected $casts = [
        'date' => 'date',
        'distance' => 'decimal:2',
        'last_synced_at' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(PatientProfile::class, 'patient_id');
    }

    public function getStepsProgress()
    {
        return $this->steps ? round(($this->steps / 10000) * 100, 2) : 0;
    }

    public function getSleepQuality()
    {
        if (!$this->sleep_duration) {
            return null;
        }

        // Recommended: 7-9 hours (420-540 minutes)
        if ($this->sleep_duration >= 420 && $this->sleep_duration <= 540) {
            return 'good';
        } elseif ($this->sleep_duration >= 360 && $this->sleep_duration < 420) {
            return 'fair';
        } elseif ($this->sleep_duration > 540 && $this->sleep_duration <= 600) {
            return 'fair';
        } else {
            return 'poor';
        }
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }
}
