<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\RiskCategory;
use App\Models\ComprehensiveRiskAssessment;

class Risk extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'risk_id',
        'client_name',
        // client_identification_done field removed - consolidated with screening fields
        'client_screening_date',
        'client_screening_result',
        'risk_description',
        'risk_detail',
        'risk_category',
        'impact',
        'likelihood',
        'risk_rating',
        'mitigation_strategies',
        'mitigation_measures',
        'owner',
        'status',
        'overall_risk_points',
        'overall_risk_rating',
        'client_acceptance',
        'ongoing_monitoring',
        'dcs_risk_appetite',
        'dcs_comments',
        // KYC fields
        'client_type',
        'gender',
        'nationality',
        'is_minor',
        'id_number',
        'passport_number',
        'registration_number',
        'entity_type',
        'trading_address',
        'income_source',
        'id_document_path',
        'birth_certificate_path',
        'passport_document_path',
        'proof_of_residence_path',
        'kyc_form_path',
        'assigned_to',
        'assigned_user_id',
        'due_date',
        'client_id',
        'created_by',
        'updated_by',
        'approval_status',
        'approved_by',
        'approved_at',
        'approval_notes',
        'rejection_reason'
    ];

    protected $casts = [
        'client_screening_date' => 'date',
        'due_date' => 'date',
        'overall_risk_points' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'approved_at' => 'datetime'
    ];


    // Relationships
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function documents()
    {
        return $this->hasMany(ClientDocument::class);
    }

    public function businessRiskCategory()
    {
        return $this->belongsTo(BusinessRiskCategory::class, 'business_risk_category', 'code');
    }

    public function category()
    {
        return $this->belongsTo(RiskCategory::class, 'risk_category');
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function comprehensiveRiskAssessment()
    {
        return $this->hasOne(ComprehensiveRiskAssessment::class, 'risk_id', 'id');
    }

    // Scopes
    public function scopeHighRisk($query)
    {
        return $query->where('risk_rating', 'High');
    }

    public function scopeMediumRisk($query)
    {
        return $query->where('risk_rating', 'Medium');
    }

    public function scopeLowRisk($query)
    {
        return $query->where('risk_rating', 'Low');
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'Open');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'Closed');
    }

    public function scopePending($query)
    {
        return $query->where('approval_status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('approval_status', 'rejected');
    }

    // Accessors
    public function getRiskScoreAttribute()
    {
        return \App\Services\RiskClassificationService::calculateRiskPoints($this->impact, $this->likelihood);
    }

    public function getRiskLevelAttribute()
    {
        $points = $this->risk_score;
        $classification = \App\Services\RiskClassificationService::getIndividualRiskRating($points);
        return $classification['level'];
    }

    public function getStatusColorAttribute()
    {
        return [
            'Open' => 'danger',
            'In Progress' => 'warning',
            'Closed' => 'success',
            'On Hold' => 'info'
        ][$this->status] ?? 'secondary';
    }

    public function getRiskRatingColorAttribute()
    {
        return [
            'High' => 'danger',
            'Medium' => 'warning',
            'Low' => 'success'
        ][$this->risk_rating] ?? 'secondary';
    }

    public function getApprovalStatusColorAttribute()
    {
        return [
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger'
        ][$this->approval_status] ?? 'secondary';
    }

    // Helper methods
    public function isOverdue()
    {
        return $this->due_date && $this->due_date->isPast() && $this->status !== 'Closed';
    }

    public function isHighPriority()
    {
        return $this->risk_rating === 'High' || $this->status === 'Open';
    }

    public function isPending()
    {
        return $this->approval_status === 'pending';
    }

    public function isApproved()
    {
        return $this->approval_status === 'approved';
    }

    public function isRejected()
    {
        return $this->approval_status === 'rejected';
    }

    public function canBeApproved()
    {
        return $this->approval_status === 'pending';
    }

    public function canBeRejected()
    {
        return $this->approval_status === 'pending';
    }

    public function getDaysUntilDue()
    {
        if (!$this->due_date) {
            return null;
        }

        return now()->diffInDays($this->due_date, false);
    }

    // Events
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($risk) {
            if (auth()->check()) {
                $risk->created_by = auth()->id();
            }
        });

        static::updating(function ($risk) {
            if (auth()->check()) {
                $risk->updated_by = auth()->id();
            }
        });

        static::saving(function ($risk) {
            // Automatically update risk_rating when impact or likelihood changes
            if ($risk->isDirty(['impact', 'likelihood'])) {
                $points = \App\Services\RiskClassificationService::calculateRiskPoints($risk->impact, $risk->likelihood);
                $classification = \App\Services\RiskClassificationService::getIndividualRiskRating($points);
                
                $risk->risk_rating = $classification['rating'];
            }
        });
    }
}
