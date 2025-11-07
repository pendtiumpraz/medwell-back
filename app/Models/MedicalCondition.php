<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalCondition extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'condition_name',
        'diagnosed_date',
        'notes',
        'severity',
        'is_active',
    ];

    protected $casts = [
        'diagnosed_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function patient()
    {
        return $this->belongsTo(PatientProfile::class, 'patient_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
