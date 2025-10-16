<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComprehensiveRiskAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'risk_id',
        
        // Service Risk (SR) Details
        'sr_risk_id', 'sr_risk_name', 'sr_impact', 'sr_likelihood', 
        'sr_risk_rating', 'sr_points', 'sr_mitigation', 'sr_owner', 'sr_status',
        
        // Client Risk (CR) Details
        'cr_risk_id', 'cr_risk_name', 'cr_impact', 'cr_likelihood', 
        'cr_risk_rating', 'cr_points', 'cr_mitigation', 'cr_owner', 'cr_status',
        
        // Payment Risk (PR) Details
        'pr_risk_id', 'pr_risk_name', 'pr_impact', 'pr_likelihood', 
        'pr_risk_rating', 'pr_points', 'pr_mitigation', 'pr_owner', 'pr_status',
        
        // Delivery Risk (DR) Details
        'dr_risk_id', 'dr_risk_name', 'dr_impact', 'dr_likelihood', 
        'dr_risk_rating', 'dr_points', 'dr_mitigation', 'dr_owner', 'dr_status',
        
        // Overall Assessment
        'total_points', 'overall_risk_rating', 'client_acceptance', 'ongoing_monitoring',
        
        // Audit Trail
        'created_by', 'updated_by'
    ];

    protected $casts = [
        'sr_points' => 'integer',
        'cr_points' => 'integer',
        'pr_points' => 'integer',
        'dr_points' => 'integer',
        'total_points' => 'integer',
        'sr_impact' => 'string',
        'sr_likelihood' => 'string',
        'sr_risk_rating' => 'string',
        'cr_impact' => 'string',
        'cr_likelihood' => 'string',
        'cr_risk_rating' => 'string',
        'pr_impact' => 'string',
        'pr_likelihood' => 'string',
        'pr_risk_rating' => 'string',
        'dr_impact' => 'string',
        'dr_likelihood' => 'string',
        'dr_risk_rating' => 'string',
    ];

    // Relationships
    public function risk()
    {
        return $this->belongsTo(Risk::class);
    }

    public function client()
    {
        return $this->hasOneThrough(
            Client::class,
            Risk::class,
            'id', // Foreign key on risks table
            'id', // Foreign key on clients table
            'risk_id', // Local key on comprehensive_risk_assessments table
            'client_id' // Local key on risks table
        );
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Accessors for regulatory reporting
    public function getHighestRiskCategoryAttribute()
    {
        $points = [
            'SR' => $this->sr_points ?? 0,
            'CR' => $this->cr_points ?? 0,
            'PR' => $this->pr_points ?? 0,
            'DR' => $this->dr_points ?? 0,
        ];
        
        return array_search(max($points), $points) ?: 'None';
    }

    public function getRiskBreakdownAttribute()
    {
        return [
            'Service Risk (SR)' => [
                'points' => $this->sr_points ?? 0,
                'rating' => $this->sr_risk_rating ?? 'Not Assessed',
                'impact' => $this->sr_impact ?? 'Not Assessed',
                'likelihood' => $this->sr_likelihood ?? 'Not Assessed',
            ],
            'Client Risk (CR)' => [
                'points' => $this->cr_points ?? 0,
                'rating' => $this->cr_risk_rating ?? 'Not Assessed',
                'impact' => $this->cr_impact ?? 'Not Assessed',
                'likelihood' => $this->cr_likelihood ?? 'Not Assessed',
            ],
            'Payment Risk (PR)' => [
                'points' => $this->pr_points ?? 0,
                'rating' => $this->pr_risk_rating ?? 'Not Assessed',
                'impact' => $this->pr_impact ?? 'Not Assessed',
                'likelihood' => $this->pr_likelihood ?? 'Not Assessed',
            ],
            'Delivery Risk (DR)' => [
                'points' => $this->dr_points ?? 0,
                'rating' => $this->dr_risk_rating ?? 'Not Assessed',
                'impact' => $this->dr_impact ?? 'Not Assessed',
                'likelihood' => $this->dr_likelihood ?? 'Not Assessed',
            ],
        ];
    }

    // Scopes for filtering
    public function scopeHighRisk($query)
    {
        return $query->where('overall_risk_rating', 'like', '%High%');
    }

    public function scopeMediumRisk($query)
    {
        return $query->where('overall_risk_rating', 'like', '%Medium%');
    }

    public function scopeLowRisk($query)
    {
        return $query->where('overall_risk_rating', 'like', '%Low%');
    }

    public function scopeAcceptedClients($query)
    {
        return $query->where('client_acceptance', 'like', '%Accept%');
    }

    public function scopeRejectedClients($query)
    {
        return $query->where('client_acceptance', 'like', '%Do not accept%');
    }
}
