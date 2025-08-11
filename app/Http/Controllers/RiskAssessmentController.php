<?php

namespace App\Http\Controllers;

use App\Models\RiskAssessment;
use Illuminate\Http\Request;

class RiskAssessmentController extends Controller
{
    /**
     * Display a listing of risk assessments
     */
    public function index()
    {
        $assessments = RiskAssessment::orderBy('created_at', 'desc')->paginate(15);
        return view('risk-assessments.index', compact('assessments'));
    }

    /**
     * Show the form for creating a new risk assessment
     */
    public function create()
    {
        return view('risk-assessments.create');
    }

    /**
     * Store a newly created risk assessment
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_identification' => 'required|in:Yes,No,In-progress',
            'screening_risk_id' => 'required|string',
            'screening_description' => 'nullable|string',
            'screening_impact' => 'nullable|string',
            'screening_likelihood' => 'nullable|string',
            'screening_risk_rating' => 'nullable|string',
            'client_category_risk_id' => 'required|string',
            'client_category_description' => 'nullable|string',
            'client_category_impact' => 'nullable|string',
            'client_category_likelihood' => 'nullable|string',
            'client_category_risk_rating' => 'nullable|string',
            'services_risk_id' => 'required|string',
            'services_description' => 'nullable|string',
            'services_impact' => 'nullable|string',
            'services_likelihood' => 'nullable|string',
            'services_risk_rating' => 'nullable|string',
            'payment_risk_id' => 'required|string',
            'payment_description' => 'nullable|string',
            'payment_impact' => 'nullable|string',
            'payment_likelihood' => 'nullable|string',
            'payment_risk_rating' => 'nullable|string',
            'delivery_risk_id' => 'required|string',
            'delivery_description' => 'nullable|string',
            'delivery_impact' => 'nullable|string',
            'delivery_likelihood' => 'nullable|string',
            'delivery_risk_rating' => 'nullable|string',
            'overall_risk_points' => 'nullable|integer',
            'overall_risk_rating' => 'nullable|string',
            'client_acceptance' => 'nullable|string',
            'ongoing_monitoring' => 'nullable|string',
            'dcs_risk_appetite' => 'required|in:Conservative,Moderate,Aggressive',
            'dcs_comments' => 'nullable|string',
        ]);

        RiskAssessment::create($validated);

        return redirect()->route('dashboard')
            ->with('success', 'Risk Assessment created successfully.');
    }

    /**
     * Display the specified risk assessment
     */
    public function show(RiskAssessment $riskAssessment)
    {
        return view('risk-assessments.show', compact('riskAssessment'));
    }

    /**
     * Show the form for editing the specified risk assessment
     */
    public function edit(RiskAssessment $riskAssessment)
    {
        return view('risk-assessments.edit', compact('riskAssessment'));
    }

    /**
     * Update the specified risk assessment
     */
    public function update(Request $request, RiskAssessment $riskAssessment)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_identification' => 'required|in:Yes,No,In-progress',
            'screening_risk_id' => 'required|string',
            'screening_description' => 'nullable|string',
            'screening_impact' => 'nullable|string',
            'screening_likelihood' => 'nullable|string',
            'screening_risk_rating' => 'nullable|string',
            'client_category_risk_id' => 'required|string',
            'client_category_description' => 'nullable|string',
            'client_category_impact' => 'nullable|string',
            'client_category_likelihood' => 'nullable|string',
            'client_category_risk_rating' => 'nullable|string',
            'services_risk_id' => 'required|string',
            'services_description' => 'nullable|string',
            'services_impact' => 'nullable|string',
            'services_likelihood' => 'nullable|string',
            'services_risk_rating' => 'nullable|string',
            'payment_risk_id' => 'required|string',
            'payment_description' => 'nullable|string',
            'payment_impact' => 'nullable|string',
            'payment_likelihood' => 'nullable|string',
            'payment_risk_rating' => 'nullable|string',
            'delivery_risk_id' => 'required|string',
            'delivery_description' => 'nullable|string',
            'delivery_impact' => 'nullable|string',
            'delivery_likelihood' => 'nullable|string',
            'delivery_risk_rating' => 'nullable|string',
            'overall_risk_points' => 'nullable|integer',
            'overall_risk_rating' => 'nullable|string',
            'client_acceptance' => 'nullable|string',
            'ongoing_monitoring' => 'nullable|string',
            'dcs_risk_appetite' => 'required|in:Conservative,Moderate,Aggressive',
            'dcs_comments' => 'nullable|string',
        ]);

        $riskAssessment->update($validated);

        return redirect()->route('risk-assessments.index')
            ->with('success', 'Risk Assessment updated successfully.');
    }

    /**
     * Remove the specified risk assessment
     */
    public function destroy(RiskAssessment $riskAssessment)
    {
        $riskAssessment->delete();

        return redirect()->route('risk-assessments.index')
            ->with('success', 'Risk Assessment deleted successfully.');
    }
} 