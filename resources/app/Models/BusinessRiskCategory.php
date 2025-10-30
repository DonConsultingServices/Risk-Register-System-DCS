<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessRiskCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'icon_class',
        'color',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function risks()
    {
        return $this->hasMany(Risk::class, 'business_risk_category', 'code');
    }

    public function riskExamples()
    {
        return $this->hasMany(RiskExample::class);
    }

    public function keyControls()
    {
        return $this->hasMany(KeyControl::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Accessors
    public function getDisplayNameAttribute()
    {
        return "{$this->code} - {$this->name}";
    }

    public function getRiskCountAttribute()
    {
        return $this->risks()->count();
    }

    public function getActiveRiskCountAttribute()
    {
        return $this->risks()->where('status', '!=', 'Closed')->count();
    }
}
