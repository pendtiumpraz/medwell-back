<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeolocationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'latitude',
        'longitude',
        'accuracy',
        'altitude',
        'recorded_at',
        'is_breach',
        'distance_from_home',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'accuracy' => 'decimal:2',
        'altitude' => 'decimal:2',
        'recorded_at' => 'datetime',
        'is_breach' => 'boolean',
        'distance_from_home' => 'decimal:2',
    ];

    public function patient()
    {
        return $this->belongsTo(PatientProfile::class, 'patient_id');
    }

    public function scopeBreaches($query)
    {
        return $query->where('is_breach', true);
    }
}
