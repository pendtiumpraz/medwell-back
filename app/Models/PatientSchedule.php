<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'created_by',
        'schedule_type',
        'start_date',
        'end_date',
        'frequency',
        'times_per_day',
        'timings',
        'settings',
        'instructions',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'timings' => 'array',
        'settings' => 'array',
    ];

    public function patient()
    {
        return $this->belongsTo(PatientProfile::class, 'patient_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                     ->where('start_date', '<=', today())
                     ->where(function($q) {
                         $q->whereNull('end_date')
                           ->orWhere('end_date', '>=', today());
                     });
    }
}
