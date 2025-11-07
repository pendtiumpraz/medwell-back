<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LipidPanel extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'test_date',
        'total_cholesterol',
        'ldl_cholesterol',
        'hdl_cholesterol',
        'triglycerides',
        'unit',
        'notes',
    ];

    protected $casts = [
        'test_date' => 'date',
        'total_cholesterol' => 'decimal:1',
        'ldl_cholesterol' => 'decimal:1',
        'hdl_cholesterol' => 'decimal:1',
        'triglycerides' => 'decimal:1',
    ];

    public function patient()
    {
        return $this->belongsTo(PatientProfile::class, 'patient_id');
    }

    public function getStatus()
    {
        $status = [];

        // Total Cholesterol
        if ($this->total_cholesterol) {
            if ($this->total_cholesterol < 200) {
                $status['total_cholesterol'] = 'desirable';
            } elseif ($this->total_cholesterol < 240) {
                $status['total_cholesterol'] = 'borderline_high';
            } else {
                $status['total_cholesterol'] = 'high';
            }
        }

        // LDL
        if ($this->ldl_cholesterol) {
            if ($this->ldl_cholesterol < 100) {
                $status['ldl'] = 'optimal';
            } elseif ($this->ldl_cholesterol < 130) {
                $status['ldl'] = 'near_optimal';
            } elseif ($this->ldl_cholesterol < 160) {
                $status['ldl'] = 'borderline_high';
            } elseif ($this->ldl_cholesterol < 190) {
                $status['ldl'] = 'high';
            } else {
                $status['ldl'] = 'very_high';
            }
        }

        // HDL
        if ($this->hdl_cholesterol) {
            if ($this->hdl_cholesterol < 40) {
                $status['hdl'] = 'low';
            } else {
                $status['hdl'] = 'good';
            }
        }

        // Triglycerides
        if ($this->triglycerides) {
            if ($this->triglycerides < 150) {
                $status['triglycerides'] = 'normal';
            } elseif ($this->triglycerides < 200) {
                $status['triglycerides'] = 'borderline_high';
            } elseif ($this->triglycerides < 500) {
                $status['triglycerides'] = 'high';
            } else {
                $status['triglycerides'] = 'very_high';
            }
        }

        return $status;
    }
}
