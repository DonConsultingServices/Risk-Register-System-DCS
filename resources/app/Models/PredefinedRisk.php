<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PredefinedRisk extends Model
{
    use HasFactory;

    protected $fillable = [
        'risk_id',
        'title',
        'description',
        'risk_level',
        'impact',
        'likelihood',
        'mitigation_measures',
        'owner',
        'status',
        'category_id',
        'points',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'points' => 'integer',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(RiskCategory::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeByRiskLevel($query, $level)
    {
        return $query->where('risk_level', $level);
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

    public function getImpactColorAttribute()
    {
        return [
            'High' => 'danger',
            'Medium' => 'warning',
            'Low' => 'success'
        ][$this->impact] ?? 'secondary';
    }

    public function getLikelihoodColorAttribute()
    {
        return [
            'High' => 'danger',
            'Medium' => 'warning',
            'Low' => 'success'
        ][$this->likelihood] ?? 'secondary';
    }

    // Helper methods
    public function isHighRisk()
    {
        return $this->risk_level === 'High';
    }

    public function isActive()
    {
        return $this->is_active;
    }

    public function getFormattedPoints()
    {
        return $this->points . ' pts';
    }

    public function getRiskScore()
    {
        $impactScores = ['Low' => 1, 'Medium' => 2, 'High' => 3];
        $likelihoodScores = ['Low' => 1, 'Medium' => 2, 'High' => 3];
        
        $impactScore = $impactScores[$this->impact] ?? 1;
        $likelihoodScore = $likelihoodScores[$this->likelihood] ?? 1;
        
        return $impactScore * $likelihoodScore;
    }
}
