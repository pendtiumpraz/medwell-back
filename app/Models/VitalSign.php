<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VitalSign extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'recorded_at',
        'systolic',
        'diastolic',
        'pulse',
        'glucose_value',
        'glucose_unit',
        'glucose_context',
        'temperature',
        'temperature_unit',
        'temperature_location',
        'core_temperature',
        'spo2_value',
        'pr_bpm',
        'weight',
        'waist_circumference',
        'bmi',
        'source',
        'device_type',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
        'glucose_value' => 'decimal:1',
        'temperature' => 'decimal:2',
        'core_temperature' => 'decimal:2',
        'weight' => 'decimal:2',
        'waist_circumference' => 'decimal:2',
        'bmi' => 'decimal:2',
    ];

    public function patient()
    {
        return $this->belongsTo(PatientProfile::class, 'patient_id');
    }

    // Helper: Calculate BMI
    public function calculateBMI($height)
    {
        if ($this->weight && $height) {
            $heightInMeters = $height / 100;
            return round($this->weight / ($heightInMeters * $heightInMeters), 2);
        }
        return null;
    }

    // Helper: Blood pressure status
    public function getBloodPressureStatus()
    {
        if (!$this->systolic || !$this->diastolic) {
            return null;
        }

        if ($this->systolic < 120 && $this->diastolic < 80) {
            return 'normal';
        } elseif ($this->systolic < 130 && $this->diastolic < 80) {
            return 'elevated';
        } elseif ($this->systolic < 140 || $this->diastolic < 90) {
            return 'stage_1_hypertension';
        } elseif ($this->systolic >= 140 || $this->diastolic >= 90) {
            return 'stage_2_hypertension';
        } elseif ($this->systolic >= 180 || $this->diastolic >= 120) {
            return 'hypertensive_crisis';
        }

        return 'unknown';
    }

    // Helper: Glucose status
    public function getGlucoseStatus()
    {
        if (!$this->glucose_value) {
            return null;
        }

        // For mg/dL unit
        if ($this->glucose_unit === 'mg/dL') {
            if ($this->glucose_context === 'fasting_8hr') {
                if ($this->glucose_value < 100) return 'normal';
                if ($this->glucose_value < 126) return 'prediabetes';
                return 'diabetes';
            } elseif ($this->glucose_context === 'after_meal_2hr') {
                if ($this->glucose_value < 140) return 'normal';
                if ($this->glucose_value < 200) return 'prediabetes';
                return 'diabetes';
            }
        }

        return 'unknown';
    }

    public function scopeByType($query, $type)
    {
        switch ($type) {
            case 'blood_pressure':
                return $query->whereNotNull('systolic');
            case 'glucose':
                return $query->whereNotNull('glucose_value');
            case 'temperature':
                return $query->whereNotNull('temperature');
            case 'spo2':
                return $query->whereNotNull('spo2_value');
            case 'weight':
                return $query->whereNotNull('weight');
            default:
                return $query;
        }
    }
}
