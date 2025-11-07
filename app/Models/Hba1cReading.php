<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hba1cReading extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'test_date',
        'hba1c_value',
        'unit',
        'notes',
    ];

    protected $casts = [
        'test_date' => 'date',
        'hba1c_value' => 'decimal:2',
    ];

    public function patient()
    {
        return $this->belongsTo(PatientProfile::class, 'patient_id');
    }

    public function getDiabetesControl()
    {
        if (!$this->hba1c_value) {
            return 'unknown';
        }

        if ($this->hba1c_value < 5.7) {
            return 'normal';
        } elseif ($this->hba1c_value < 6.5) {
            return 'prediabetes';
        } elseif ($this->hba1c_value < 7.0) {
            return 'diabetes_good_control';
        } elseif ($this->hba1c_value < 9.0) {
            return 'diabetes_fair_control';
        } else {
            return 'diabetes_poor_control';
        }
    }
}
