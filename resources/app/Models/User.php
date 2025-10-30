<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements \Illuminate\Contracts\Auth\CanResetPassword
{
    use HasApiTokens, HasFactory, Notifiable, CanResetPassword;

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
        'is_active',
        'last_login_at',
        'password_changed_at',
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
        'last_login_at' => 'datetime',
        'password_changed_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Available roles
     */
    const ROLES = [
        'admin' => 'Administrator',
        'manager' => 'Manager',
        'staff' => 'Staff'
    ];

    /**
     * Check if user has a specific role
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    /**
     * Check if user has any of the specified roles
     */
    public function hasAnyRole($roles)
    {
        if (is_array($roles)) {
            return in_array($this->role, $roles);
        }
        return $this->role === $roles;
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if user is manager or admin
     */
    public function isManagerOrAdmin()
    {
        return $this->hasAnyRole(['admin', 'manager']);
    }

    /**
     * Check if user is staff
     */
    public function isStaff()
    {
        return $this->hasRole('staff');
    }

    /**
     * Check if user is manager
     */
    public function isManager()
    {
        return $this->hasRole('manager');
    }

    /**
     * Check if user can manage risks
     */
    public function canManageRisks()
    {
        return $this->hasAnyRole(['admin', 'manager', 'staff']);
    }

    /**
     * Check if user can approve risks
     */
    public function canApproveRisks()
    {
        return $this->hasAnyRole(['admin', 'manager']);
    }

    /**
     * Check if user can manage risk categories
     */
    public function canManageRiskCategories()
    {
        return $this->hasAnyRole(['admin', 'manager']);
    }

    /**
     * Check if user can view reports
     */
    public function canViewReports()
    {
        return $this->hasAnyRole(['admin', 'manager', 'staff']);
    }

    /**
     * Get role display name
     */
    public function getRoleDisplayNameAttribute()
    {
        return self::ROLES[$this->role] ?? 'Unknown';
    }

    /**
     * Scope for active users
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for inactive users
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Update last login timestamp
     */
    public function updateLastLogin()
    {
        $this->update(['last_login_at' => now()]);
    }

    /**
     * Check if password needs to be changed
     */
    public function needsPasswordChange()
    {
        if (!$this->password_changed_at) {
            return true;
        }

        // Force password change every 90 days
        return $this->password_changed_at->diffInDays(now()) > 90;
    }

    /**
     * Get users by role
     */
    public static function getByRole($role)
    {
        return static::where('role', $role)->active()->get();
    }

    /**
     * Get all users except current user
     */
    public static function getAllExcept($userId)
    {
        return static::where('id', '!=', $userId)->get();
    }

    /**
     * Get the email address where password reset links are sent.
     */
    public function getEmailForPasswordReset()
    {
        return $this->email;
    }

    /**
     * Send the password reset notification.
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \Illuminate\Auth\Notifications\ResetPassword($token));
    }
}
