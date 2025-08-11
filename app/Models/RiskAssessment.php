<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiskAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_name',
        'client_identification_status',
        'client_screening_date',
        'client_screening_result',
        'client_screening_risk_id',
        'client_screening_description',
        'client_screening_impact',
        'client_screening_likelihood',
        'client_screening_risk_rating',
        'client_category_risk_id',
        'client_category_description',
        'client_category_impact',
        'client_category_likelihood',
        'client_category_risk_rating',
        'requested_services_risk_id',
        'requested_services_description',
        'requested_services_impact',
        'requested_services_likelihood',
        'requested_services_risk_rating',
        'payment_option_risk_id',
        'payment_option_description',
        'payment_option_impact',
        'payment_option_likelihood',
        'payment_option_risk_rating',
        'delivery_method_risk_id',
        'delivery_method_description',
        'delivery_method_impact',
        'delivery_method_likelihood',
        'delivery_method_risk_rating',
        'total_points',
        'overall_risk_rating',
        'client_acceptance',
        'monitoring_frequency',
        'selected_risk_ids',
        'assessment_date',
        'dcs_risk_appetite',
        'dcs_comments',
    ];

    protected $casts = [
        'selected_risk_ids' => 'array',
        'assessment_date' => 'datetime',
        'client_screening_date' => 'date',
    ];

    /**
     * Get dashboard statistics
     */
    public static function getDashboardStats()
    {
        $totalAssessments = self::count();
        
        $riskRatingStats = self::selectRaw('overall_risk_rating, COUNT(*) as count')
            ->whereNotNull('overall_risk_rating')
            ->groupBy('overall_risk_rating')
            ->pluck('count', 'overall_risk_rating')
            ->toArray();

        $identificationStats = self::selectRaw('client_identification, COUNT(*) as count')
            ->groupBy('client_identification')
            ->pluck('count', 'client_identification')
            ->toArray();

        $recentAssessments = self::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return [
            'total_assessments' => $totalAssessments,
            'risk_rating_stats' => $riskRatingStats,
            'identification_stats' => $identificationStats,
            'recent_assessments' => $recentAssessments,
        ];
    }

    /**
     * Get risk rating distribution for charts
     */
    public static function getRiskRatingDistribution()
    {
        return self::selectRaw('overall_risk_rating, COUNT(*) as count')
            ->whereNotNull('overall_risk_rating')
            ->groupBy('overall_risk_rating')
            ->orderBy('overall_risk_rating')
            ->get();
    }

    /**
     * Get monthly trend data
     */
    public static function getMonthlyTrend()
    {
        return self::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }

    /**
     * Get risk color class for display
     */
    public function getRiskColorClass()
    {
        switch ($this->overall_risk_rating) {
            case 'High':
                return 'danger';
            case 'Medium':
                return 'warning';
            case 'Low':
                return 'success';
            default:
                return 'secondary';
        }
    }

    /**
     * Check if client is acceptable
     */
    public function isClientAcceptable()
    {
        return $this->client_acceptance === 'Accept client' || $this->client_acceptance === 'Accept with conditions';
    }

    /**
     * Get formatted assessment date
     */
    public function getFormattedAssessmentDate()
    {
        return $this->assessment_date ? $this->assessment_date->format('M d, Y') : $this->created_at->format('M d, Y');
    }
} 