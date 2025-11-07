<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medication extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'organization_id',
        'name',
        'generic_name',
        'brand_name',
        'category',
        'description',
        'dosage_forms',
        'strengths',
        'route',
        'requires_prescription',
        'status',
        'manufacturer',
    ];

    protected $casts = [
        'dosage_forms' => 'array',
        'strengths' => 'array',
        'requires_prescription' => 'boolean',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function patientMedications()
    {
        return $this->hasMany(PatientMedication::class);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('generic_name', 'like', "%{$search}%");
        });
    }
}
