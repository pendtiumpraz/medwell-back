<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'patient_id',
        'uploader_id',
        'title',
        'filename',
        'original_filename',
        'file_path',
        'file_size',
        'mime_type',
        'notes',
        'category',
        'shared_with_patient',
        'shared_at',
        'viewed_by_patient',
        'viewed_at',
        'status',
    ];

    protected $casts = [
        'shared_with_patient' => 'boolean',
        'shared_at' => 'datetime',
        'viewed_by_patient' => 'boolean',
        'viewed_at' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(PatientProfile::class, 'patient_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }

    public function shareWithPatient()
    {
        $this->update([
            'shared_with_patient' => true,
            'shared_at' => now(),
            'status' => 'shared',
        ]);
    }

    public function markAsViewed()
    {
        $this->update([
            'viewed_by_patient' => true,
            'viewed_at' => now(),
        ]);
    }

    public function getFileSizeInMB()
    {
        return round($this->file_size / 1048576, 2);
    }

    public function scopeShared($query)
    {
        return $query->where('shared_with_patient', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
