<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'industry',
        'status',
        'notes',
        // client_identification_done field removed - consolidated with screening fields
        'client_screening_date',
        'client_screening_result',
        'risk_category',
        'risk_id',
        'overall_risk_points',
        'overall_risk_rating',
        'client_acceptance',
        'ongoing_monitoring',
        'dcs_risk_appetite',
        'dcs_comments',
        'created_by',
        'updated_by',
        // Approval fields
        'assessment_status',
        'approved_by',
        'approved_at',
        'approval_notes',
        'rejection_reason'
    ];

    protected $casts = [
        'screening_date' => 'date',
        'client_screening_date' => 'date',
        'overall_risk_points' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // Relationships
    public function risks()
    {
        return $this->hasMany(Risk::class, 'client_id', 'id');
    }
    
    public function comprehensiveRiskAssessment()
    {
        return $this->hasOneThrough(
            \App\Models\ComprehensiveRiskAssessment::class,
            Risk::class,
            'client_id', // Foreign key on risks table
            'risk_id', // Foreign key on comprehensive_risk_assessments table
            'id', // Local key on clients table
            'id' // Local key on risks table
        );
    }

    public function comprehensiveRiskAssessments()
    {
        return $this->hasManyThrough(
            \App\Models\ComprehensiveRiskAssessment::class,
            Risk::class,
            'client_id', // Foreign key on risks table
            'risk_id', // Foreign key on comprehensive_risk_assessments table
            'id', // Local key on clients table
            'id' // Local key on risks table
        );
    }

    public function latestComprehensiveRiskAssessment()
    {
        return $this->hasOneThrough(
            \App\Models\ComprehensiveRiskAssessment::class,
            Risk::class,
            'client_id', // Foreign key on risks table
            'risk_id', // Foreign key on comprehensive_risk_assessments table
            'id', // Local key on clients table
            'id' // Local key on risks table
        )->latest();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function documents()
    {
        return $this->hasMany(ClientDocument::class);
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'Inactive');
    }

    public function scopeHighRisk($query)
    {
        return $query->where('risk_level', 'High');
    }

    public function scopeMediumRisk($query)
    {
        return $query->where('risk_level', 'Medium');
    }

    public function scopeLowRisk($query)
    {
        return $query->where('risk_level', 'Low');
    }

    // Accessors
    public function getRiskLevelColorAttribute()
    {
        return [
            'High' => 'danger',
            'Medium' => 'warning',
            'Low' => 'success'
        ][$this->risk_level] ?? 'secondary';
    }

    public function getStatusColorAttribute()
    {
        return [
            'Active' => 'success',
            'Inactive' => 'secondary',
            'Pending' => 'warning',
            'Suspended' => 'danger'
        ][$this->status] ?? 'secondary';
    }

    public function getTotalRisksAttribute()
    {
        return $this->risks()->count();
    }

    public function getHighRisksAttribute()
    {
        return $this->risks()->where('risk_rating', 'High')->count();
    }

    public function getMediumRisksAttribute()
    {
        return $this->risks()->where('risk_rating', 'Medium')->count();
    }

    public function getLowRisksAttribute()
    {
        return $this->risks()->where('risk_rating', 'Low')->count();
    }

    public function getOpenRisksAttribute()
    {
        return $this->risks()->where('status', 'Open')->count();
    }

    // Helper methods
    public function isHighRisk()
    {
        return $this->risk_level === 'High';
    }

    public function isActive()
    {
        return $this->status === 'Active';
    }

    public function getScreeningStatus()
    {
        if (!$this->screening_date) {
            return 'Not Screened';
        }

        if ($this->screening_result) {
            return 'Screened - ' . $this->screening_result;
        }

        return 'Screened - No Result';
    }

    public function getDaysSinceScreening()
    {
        if (!$this->screening_date) {
            return null;
        }

        return now()->diffInDays($this->screening_date);
    }

    // Assessment Approval Methods
    public function isPendingAssessment()
    {
        return $this->assessment_status === 'pending';
    }

    public function isApprovedAssessment()
    {
        return $this->assessment_status === 'approved';
    }

    public function isRejectedAssessment()
    {
        return $this->assessment_status === 'rejected';
    }

    public function canBeApproved()
    {
        return $this->assessment_status === 'pending';
    }

    public function canBeRejected()
    {
        return $this->assessment_status === 'pending';
    }

    public function getAssessmentStatusColorAttribute()
    {
        return [
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger'
        ][$this->assessment_status] ?? 'secondary';
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function assessmentHistory()
    {
        return $this->hasMany(ClientAssessmentHistory::class)->orderBy('assessment_date', 'desc');
    }

    public function latestAssessmentHistory()
    {
        return $this->hasOne(ClientAssessmentHistory::class)->latest('assessment_date');
    }

    // Scopes for assessment status
    public function scopePendingAssessment($query)
    {
        return $query->where('assessment_status', 'pending');
    }

    public function scopeApprovedAssessment($query)
    {
        return $query->where('assessment_status', 'approved');
    }

    public function scopeRejectedAssessment($query)
    {
        return $query->where('assessment_status', 'rejected');
    }

    // Events
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($client) {
            if (auth()->check()) {
                $client->created_by = auth()->id();
            }
        });

        static::updating(function ($client) {
            if (auth()->check()) {
                $client->updated_by = auth()->id();
            }
        });
    }
}
