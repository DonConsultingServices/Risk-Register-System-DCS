<?php

namespace App\Http\Controllers;

use App\Models\RiskAssessment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard
     */
    public function index()
    {
        $stats = RiskAssessment::getDashboardStats();
        $riskDistribution = RiskAssessment::getRiskRatingDistribution();
        $monthlyTrend = RiskAssessment::getMonthlyTrend();

        return view('dashboard', compact('stats', 'riskDistribution', 'monthlyTrend'));
    }
} 