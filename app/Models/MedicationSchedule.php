<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicationSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_medication_id',
        'scheduled_date',
        'scheduled_time',
        'status',
        'taken_at',
        'delayed_time',
        'notes',
        'skip_reason',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'taken_at' => 'datetime',
    ];

    public function patientMedication()
    {
        return $this->belongsTo(PatientMedication::class);
    }

    public function markAsTaken()
    {
        $this->update([
            'status' => 'taken',
            'taken_at' => now(),
        ]);
    }

    public function markAsDelayed($minutes)
    {
        $this->update([
            'status' => 'delayed',
            'delayed_time' => $minutes,
        ]);
    }

    public function markAsMissed()
    {
        $this->update(['status' => 'missed']);
    }

    public function scopeTaken($query)
    {
        return $query->where('status', 'taken');
    }

    public function scopeMissed($query)
    {
        return $query->where('status', 'missed');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('scheduled_date', today());
    }
}
