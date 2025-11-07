<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, LogsActivity;

    protected $fillable = [
        'organization_id',
        'department_id',
        'username',
        'email',
        'password',
        'role',
        'status',
        'phone',
        'avatar',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['username', 'email', 'role', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // Relationships
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function patientProfile()
    {
        return $this->hasOne(PatientProfile::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user')
                    ->withTimestamps();
    }

    // Clinician relationships
    public function assignedPatients()
    {
        return $this->belongsToMany(PatientProfile::class, 'patient_clinician', 'clinician_id', 'patient_id')
                    ->wherePivot('is_active', true)
                    ->withPivot('role', 'assigned_at')
                    ->withTimestamps()
                    ->select('patient_profiles.*'); // Fix ambiguous column
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'recipient_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopeClinicians($query)
    {
        return $query->whereIn('role', ['clinician', 'health_coach']);
    }

    public function scopePatients($query)
    {
        return $query->where('role', 'patient');
    }

    // Helper methods
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['super_admin', 'organization_admin', 'admin']);
    }

    public function isClinician(): bool
    {
        return in_array($this->role, ['clinician', 'health_coach']);
    }

    public function isPatient(): bool
    {
        return $this->role === 'patient';
    }

    public function hasRole(string $role): bool
    {
        // Check direct role column first
        if ($this->role === $role) {
            return true;
        }
        
        // Also check roles relationship (many-to-many)
        return $this->roles()->where('name', $role)->exists();
    }

    public function hasPermission(string $permission): bool
    {
        foreach ($this->roles as $role) {
            if (in_array($permission, $role->permissions ?? [])) {
                return true;
            }
        }
        return false;
    }

    public function canAccessOrganization($organizationId): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }
        return $this->organization_id == $organizationId;
    }

    // Accessor for unread notifications count
    public function getUnreadNotificationsCountAttribute()
    {
        return $this->notifications()->whereNull('read_at')->count();
    }
}
