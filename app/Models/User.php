<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'department',
        'phone',
        'is_active',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    /**
     * Get the user's role display name
     */
    public function getRoleDisplayNameAttribute()
    {
        $roles = [
            'admin' => 'Administrator',
            'manager' => 'Manager',
            'analyst' => 'Risk Analyst',
            'viewer' => 'Viewer'
        ];

        return $roles[$this->role] ?? $this->role;
    }

    /**
     * Get the user's status badge class
     */
    public function getStatusBadgeClassAttribute()
    {
        return $this->is_active ? 'bg-success' : 'bg-danger';
    }

    /**
     * Get the user's status text
     */
    public function getStatusTextAttribute()
    {
        return $this->is_active ? 'Active' : 'Inactive';
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is manager
     */
    public function isManager()
    {
        return $this->role === 'manager';
    }

    /**
     * Check if user is analyst
     */
    public function isAnalyst()
    {
        return $this->role === 'analyst';
    }

    /**
     * Check if user is viewer
     */
    public function isViewer()
    {
        return $this->role === 'viewer';
    }

    /**
     * Check if user has permission to perform action
     */
    public function hasPermission($permission)
    {
        switch ($permission) {
            case 'manage_users':
                return in_array($this->role, ['admin']);
            case 'manage_assessments':
                return in_array($this->role, ['admin', 'manager', 'analyst']);
            case 'view_reports':
                return in_array($this->role, ['admin', 'manager', 'analyst', 'viewer']);
            case 'export_data':
                return in_array($this->role, ['admin', 'manager']);
            case 'manage_settings':
                return in_array($this->role, ['admin']);
            default:
                return false;
        }
    }

    /**
     * Get user's risk assessments
     */
    public function riskAssessments()
    {
        return $this->hasMany(RiskAssessment::class, 'created_by');
    }

    /**
     * Get user's recent activity
     */
    public function getRecentActivity($limit = 10)
    {
        return $this->riskAssessments()
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Update last login timestamp
     */
    public function updateLastLogin()
    {
        $this->update(['last_login_at' => now()]);
    }

    /**
     * Get formatted last login date
     */
    public function getFormattedLastLoginAttribute()
    {
        return $this->last_login_at ? $this->last_login_at->format('M d, Y H:i') : 'Never';
    }
} 