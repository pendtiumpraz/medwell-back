<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'name',
        'description',
        'duration',
        'intensity',
        'instructions',
        'video_url',
        'thumbnail_url',
        'equipment',
        'calories_estimate',
    ];

    protected $casts = [
        'equipment' => 'array',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function scopeByIntensity($query, $intensity)
    {
        return $query->where('intensity', $intensity);
    }
}
