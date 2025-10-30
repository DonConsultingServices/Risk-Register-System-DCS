<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiskCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'color',
        'risk_prefix',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function risks()
    {
        return $this->hasMany(Risk::class, 'risk_category', 'name');
    }

    public function predefinedRisks()
    {
        return $this->hasMany(PredefinedRisk::class, 'category_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Accessors
    public function getRiskCountAttribute()
    {
        return $this->risks()->count();
    }

    public function getPredefinedRiskCountAttribute()
    {
        return $this->predefinedRisks()->count();
    }

    public function getTotalRiskCountAttribute()
    {
        return $this->risk_count + $this->predefined_risk_count;
    }

    // Helper methods
    public function isActive()
    {
        return $this->is_active;
    }

    public function hasRisks()
    {
        return $this->risks()->exists();
    }

    public function hasPredefinedRisks()
    {
        return $this->predefinedRisks()->exists();
    }

    public function getFormattedColor()
    {
        return $this->color ?: '#6c757d';
    }
}
