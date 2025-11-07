<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatientMedication extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'patient_id',
        'medication_id',
        'prescriber_id',
        'dosage',
        'dosage_unit',
        'frequency',
        'times_per_day',
        'times',
        'start_date',
        'end_date',
        'instructions',
        'prescriber_notes',
        'consent_status',
        'consent_given_at',
        'consent_comment',
        'status',
    ];

    protected $casts = [
        'dosage' => 'decimal:2',
        'times' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
        'consent_given_at' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(PatientProfile::class, 'patient_id');
    }

    public function medication()
    {
        return $this->belongsTo(Medication::class);
    }

    public function prescriber()
    {
        return $this->belongsTo(User::class, 'prescriber_id');
    }

    public function schedules()
    {
        return $this->hasMany(MedicationSchedule::class);
    }

    public function isPending()
    {
        return $this->consent_status === 'pending';
    }

    public function isAccepted()
    {
        return $this->consent_status === 'accepted';
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePendingConsent($query)
    {
        return $query->where('consent_status', 'pending');
    }
}
