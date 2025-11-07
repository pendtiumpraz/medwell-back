<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeofenceSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'enabled',
        'center_latitude',
        'center_longitude',
        'radius',
        'address',
        'patient_consent',
        'consent_given_at',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'center_latitude' => 'decimal:8',
        'center_longitude' => 'decimal:8',
        'radius' => 'decimal:2',
        'patient_consent' => 'boolean',
        'consent_given_at' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(PatientProfile::class, 'patient_id');
    }
}
