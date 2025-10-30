<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientAssessmentHistory extends Model
{
    use HasFactory;

    protected $table = 'client_assessment_history';

    protected $fillable = [
        'client_id',
        'name',
        'email',
        'phone',
        'company',
        'industry',
        'overall_risk_points',
        'overall_risk_rating',
        'client_acceptance',
        'ongoing_monitoring',
        'dcs_risk_appetite',
        'dcs_comments',
        'assessment_status',
        'rejection_reason',
        'approval_notes',
        'created_by',
        'approved_by',
        'approved_at',
        'assessment_date'
    ];

    protected $casts = [
        'overall_risk_points' => 'integer',
        'approved_at' => 'datetime',
        'assessment_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Accessors
    public function getRiskLevelColorAttribute()
    {
        return [
            'High' => 'danger',
            'Medium' => 'warning',
            'Low' => 'success',
            'Critical' => 'danger'
        ][$this->overall_risk_rating] ?? 'secondary';
    }

    public function getAssessmentStatusColorAttribute()
    {
        return [
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger'
        ][$this->assessment_status] ?? 'secondary';
    }

    // Scopes
    public function scopeByClient($query, $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    public function scopeOrderedByDate($query)
    {
        return $query->orderBy('assessment_date', 'desc');
    }

    public function scopeApproved($query)
    {
        return $query->where('assessment_status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('assessment_status', 'rejected');
    }

    public function scopePending($query)
    {
        return $query->where('assessment_status', 'pending');
    }
}
