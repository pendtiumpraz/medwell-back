<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'alert_type',
        'parameter',
        'value',
        'threshold',
        'message',
        'notified_clinician',
        'notified_at',
        'acknowledged_by',
        'acknowledged_at',
        'acknowledgement_note',
        'resolved',
        'resolved_at',
        'resolution_note',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'notified_clinician' => 'boolean',
        'notified_at' => 'datetime',
        'acknowledged_at' => 'datetime',
        'resolved' => 'boolean',
        'resolved_at' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(PatientProfile::class, 'patient_id');
    }

    public function acknowledgedBy()
    {
        return $this->belongsTo(User::class, 'acknowledged_by');
    }

    public function acknowledge(User $user, $note = null)
    {
        $this->update([
            'acknowledged_by' => $user->id,
            'acknowledged_at' => now(),
            'acknowledgement_note' => $note,
        ]);
    }

    public function resolve($note = null)
    {
        $this->update([
            'resolved' => true,
            'resolved_at' => now(),
            'resolution_note' => $note,
        ]);
    }

    public function scopeCritical($query)
    {
        return $query->where('alert_type', 'critical');
    }

    public function scopeUnresolved($query)
    {
        return $query->where('resolved', false);
    }

    public function scopeUnacknowledged($query)
    {
        return $query->whereNull('acknowledged_at');
    }
}
