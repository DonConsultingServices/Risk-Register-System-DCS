<?php

namespace App\Http\Controllers;

use App\Models\RiskAssessment;
use Illuminate\Http\Request;

class ClientRiskController extends Controller
{
    /**
     * Display a listing of client risk assessments
     */
    public function index()
    {
        $assessments = RiskAssessment::orderBy('created_at', 'desc')->paginate(15);
        return view('client-risk.index', compact('assessments'));
    }

    /**
     * Display the comprehensive risk register
     */
    public function riskRegister()
    {
        // Get all clients for the risk register table
        $clients = RiskAssessment::with('services')->orderBy('created_at', 'desc')->get();

        // Risk matrix data for the comprehensive risk register
        $riskMatrix = [
            'client_risks' => [
                'CR-01' => ['description' => 'PIP / PEP client', 'impact' => 'high', 'likelihood' => 'medium', 'points' => 15],
                'CR-02' => ['description' => 'Corporate client', 'impact' => 'medium', 'likelihood' => 'high', 'points' => 12],
                'CR-03' => ['description' => 'Individual client', 'impact' => 'medium', 'likelihood' => 'medium', 'points' => 9],
            ],
            'service_risks' => [
                'SR-01' => ['description' => 'High-risk services', 'impact' => 'high', 'likelihood' => 'high', 'points' => 15],
                'SR-02' => ['description' => 'Complex services', 'impact' => 'medium', 'likelihood' => 'medium', 'points' => 9],
                'SR-03' => ['description' => 'Standard services', 'impact' => 'low', 'likelihood' => 'medium', 'points' => 6],
                'SR-04' => ['description' => 'Unrecorded face-to-face transactions', 'impact' => 'medium', 'likelihood' => 'medium', 'points' => 9],
            ],
            'payment_risks' => [
                'PR-01' => ['description' => 'Cash Payments', 'impact' => 'high', 'likelihood' => 'high', 'points' => 15],
                'PR-02' => ['description' => 'EFTs/SWIFT', 'impact' => 'medium', 'likelihood' => 'medium', 'points' => 9],
                'PR-03' => ['description' => 'POS Payments', 'impact' => 'low', 'likelihood' => 'medium', 'points' => 6],
            ],
            'delivery_risks' => [
                'DR-01' => ['description' => 'Remote service risks', 'impact' => 'high', 'likelihood' => 'medium', 'points' => 12],
                'DR-02' => ['description' => 'Face-to-face service risks', 'impact' => 'medium', 'likelihood' => 'low', 'points' => 6],
            ],
        ];

        // Risk criteria for overall rating
        $riskCriteria = [
            'very_high' => [
                'min_points' => 15,
                'acceptance' => 'Reject client',
                'monitoring' => 'Not applicable'
            ],
            'high' => [
                'min_points' => 10,
                'acceptance' => 'Review required',
                'monitoring' => 'Enhanced monitoring'
            ],
            'medium' => [
                'min_points' => 5,
                'acceptance' => 'Accept with conditions',
                'monitoring' => 'Standard monitoring'
            ],
            'low' => [
                'min_points' => 0,
                'acceptance' => 'Accept client',
                'monitoring' => 'Minimal monitoring'
            ],
        ];

        return view('client-risk.risk-register', compact('clients', 'riskMatrix', 'riskCriteria'));
    }

    /**
     * Show the form for creating a new client risk assessment
     */
    public function create()
    {
        // Available risk IDs and their descriptions
        $availableRiskIds = [
            'R001' => 'Client Screening Risk',
            'R002' => 'Client Category Risk',
            'R003' => 'Requested Services Risk',
            'R004' => 'Payment Option Risk',
            'R005' => 'Delivery Method Risk',
            'R006' => 'High-Risk Client',
            'R007' => 'PEP (Politically Exposed Person)',
            'R008' => 'Sanctions Risk',
            'R009' => 'Money Laundering Risk',
            'R010' => 'Terrorist Financing Risk'
        ];

        // Risk matrix data for auto-filling fields
        $riskMatrix = [
            'R001' => [
                'description' => 'Client Screening Risk - Risk associated with client screening and due diligence processes',
                'impact' => 'Medium',
                'likelihood' => 'Medium',
                'points' => 2
            ],
            'R002' => [
                'description' => 'Client Category Risk - Risk based on the type and category of client',
                'impact' => 'Low',
                'likelihood' => 'Low',
                'points' => 1
            ],
            'R003' => [
                'description' => 'Requested Services Risk - Risk associated with the specific services requested by the client',
                'impact' => 'High',
                'likelihood' => 'Medium',
                'points' => 3
            ],
            'R004' => [
                'description' => 'Payment Option Risk - Risk related to the payment methods and options chosen by the client',
                'impact' => 'Medium',
                'likelihood' => 'Medium',
                'points' => 2
            ],
            'R005' => [
                'description' => 'Delivery Method Risk - Risk associated with how services are delivered to the client',
                'impact' => 'Low',
                'likelihood' => 'Low',
                'points' => 1
            ],
            'R006' => [
                'description' => 'High-Risk Client - Client identified as high-risk through screening processes',
                'impact' => 'High',
                'likelihood' => 'High',
                'points' => 5
            ],
            'R007' => [
                'description' => 'PEP (Politically Exposed Person) - Client identified as politically exposed person',
                'impact' => 'High',
                'likelihood' => 'Medium',
                'points' => 4
            ],
            'R008' => [
                'description' => 'Sanctions Risk - Client associated with sanctioned entities or countries',
                'impact' => 'High',
                'likelihood' => 'High',
                'points' => 5
            ],
            'R009' => [
                'description' => 'Money Laundering Risk - Client associated with money laundering activities',
                'impact' => 'High',
                'likelihood' => 'Medium',
                'points' => 4
            ],
            'R010' => [
                'description' => 'Terrorist Financing Risk - Client associated with terrorist financing activities',
                'impact' => 'High',
                'likelihood' => 'High',
                'points' => 5
            ]
        ];

        // Identification status options
        $identificationStatusOptions = [
            'Completed' => 'Completed',
            'In Progress' => 'In Progress',
            'Not Started' => 'Not Started',
            'Pending Documentation' => 'Pending Documentation'
        ];

        // Risk rating guide
        $riskRatingGuide = [
            '0-5' => [
                'rating' => 'Low',
                'acceptance' => 'Accept client',
                'monitoring' => 'Standard monitoring',
                'color' => 'success'
            ],
            '6-10' => [
                'rating' => 'Medium',
                'acceptance' => 'Accept with conditions',
                'monitoring' => 'Enhanced monitoring',
                'color' => 'warning'
            ],
            '11-15' => [
                'rating' => 'High',
                'acceptance' => 'Reject client',
                'monitoring' => 'Not applicable',
                'color' => 'danger'
            ]
        ];

        return view('client-risk.create', compact('availableRiskIds', 'riskMatrix', 'identificationStatusOptions', 'riskRatingGuide'));
    }

    /**
     * Store a newly created client risk assessment
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_identification_status' => 'nullable|string',
            'client_screening_date' => 'nullable|date',
            'client_screening_result' => 'nullable|string',
            'client_screening_risk_id' => 'nullable|string',
            'client_screening_description' => 'nullable|string',
            'client_screening_impact' => 'nullable|string',
            'client_screening_likelihood' => 'nullable|string',
            'client_screening_risk_rating' => 'nullable|string',
            'client_category_risk_id' => 'nullable|string',
            'client_category_description' => 'nullable|string',
            'client_category_impact' => 'nullable|string',
            'client_category_likelihood' => 'nullable|string',
            'client_category_risk_rating' => 'nullable|string',
            'requested_services_risk_id' => 'nullable|string',
            'requested_services_description' => 'nullable|string',
            'requested_services_impact' => 'nullable|string',
            'requested_services_likelihood' => 'nullable|string',
            'requested_services_risk_rating' => 'nullable|string',
            'payment_option_risk_id' => 'nullable|string',
            'payment_option_description' => 'nullable|string',
            'payment_option_impact' => 'nullable|string',
            'payment_option_likelihood' => 'nullable|string',
            'payment_option_risk_rating' => 'nullable|string',
            'delivery_method_risk_id' => 'nullable|string',
            'delivery_method_description' => 'nullable|string',
            'delivery_method_impact' => 'nullable|string',
            'delivery_method_likelihood' => 'nullable|string',
            'delivery_method_risk_rating' => 'nullable|string',
            'dcs_risk_appetite' => 'nullable|string',
            'dcs_comments' => 'nullable|string',
        ]);

        // Calculate overall risk points and rating
        $totalPoints = $this->calculateTotalRiskPoints($validated);
        $overallRating = $this->calculateOverallRiskRating($totalPoints);
        $clientAcceptance = $this->determineClientAcceptance($totalPoints);
        $monitoringFrequency = $this->determineMonitoringFrequency($totalPoints);

        // Add calculated fields
        $validated['total_points'] = $totalPoints;
        $validated['overall_risk_rating'] = $overallRating;
        $validated['client_acceptance'] = $clientAcceptance;
        $validated['monitoring_frequency'] = $monitoringFrequency;
        $validated['assessment_date'] = now();

        // Store selected risk IDs
        $selectedRiskIds = [];
        $riskFields = [
            'client_screening_risk_id',
            'client_category_risk_id', 
            'requested_services_risk_id',
            'payment_option_risk_id',
            'delivery_method_risk_id'
        ];

        foreach ($riskFields as $field) {
            if (!empty($validated[$field])) {
                $selectedRiskIds[] = $validated[$field];
            }
        }
        $validated['selected_risk_ids'] = json_encode($selectedRiskIds);

        RiskAssessment::create($validated);

        return redirect()->route('client-risk.index')
            ->with('success', 'Client Risk Assessment created successfully.');
    }

    /**
     * Display the specified client risk assessment
     */
    public function show(RiskAssessment $clientRisk)
    {
        return view('client-risk.show', compact('clientRisk'));
    }

    /**
     * Show the form for editing the specified client risk assessment
     */
    public function edit(RiskAssessment $clientRisk)
    {
        // Same data as create method
        $availableRiskIds = [
            'R001' => 'Client Screening Risk',
            'R002' => 'Client Category Risk',
            'R003' => 'Requested Services Risk',
            'R004' => 'Payment Option Risk',
            'R005' => 'Delivery Method Risk',
            'R006' => 'High-Risk Client',
            'R007' => 'PEP (Politically Exposed Person)',
            'R008' => 'Sanctions Risk',
            'R009' => 'Money Laundering Risk',
            'R010' => 'Terrorist Financing Risk'
        ];

        // Risk matrix data for auto-filling fields
        $riskMatrix = [
            'R001' => [
                'description' => 'Client Screening Risk - Risk associated with client screening and due diligence processes',
                'impact' => 'Medium',
                'likelihood' => 'Medium',
                'points' => 2
            ],
            'R002' => [
                'description' => 'Client Category Risk - Risk based on the type and category of client',
                'impact' => 'Low',
                'likelihood' => 'Low',
                'points' => 1
            ],
            'R003' => [
                'description' => 'Requested Services Risk - Risk associated with the specific services requested by the client',
                'impact' => 'High',
                'likelihood' => 'Medium',
                'points' => 3
            ],
            'R004' => [
                'description' => 'Payment Option Risk - Risk related to the payment methods and options chosen by the client',
                'impact' => 'Medium',
                'likelihood' => 'Medium',
                'points' => 2
            ],
            'R005' => [
                'description' => 'Delivery Method Risk - Risk associated with how services are delivered to the client',
                'impact' => 'Low',
                'likelihood' => 'Low',
                'points' => 1
            ],
            'R006' => [
                'description' => 'High-Risk Client - Client identified as high-risk through screening processes',
                'impact' => 'High',
                'likelihood' => 'High',
                'points' => 5
            ],
            'R007' => [
                'description' => 'PEP (Politically Exposed Person) - Client identified as politically exposed person',
                'impact' => 'High',
                'likelihood' => 'Medium',
                'points' => 4
            ],
            'R008' => [
                'description' => 'Sanctions Risk - Client associated with sanctioned entities or countries',
                'impact' => 'High',
                'likelihood' => 'High',
                'points' => 5
            ],
            'R009' => [
                'description' => 'Money Laundering Risk - Client associated with money laundering activities',
                'impact' => 'High',
                'likelihood' => 'Medium',
                'points' => 4
            ],
            'R010' => [
                'description' => 'Terrorist Financing Risk - Client associated with terrorist financing activities',
                'impact' => 'High',
                'likelihood' => 'High',
                'points' => 5
            ]
        ];

        $identificationStatusOptions = [
            'Completed' => 'Completed',
            'In Progress' => 'In Progress',
            'Not Started' => 'Not Started',
            'Pending Documentation' => 'Pending Documentation'
        ];

        $riskRatingGuide = [
            '0-5' => [
                'rating' => 'Low',
                'acceptance' => 'Accept client',
                'monitoring' => 'Standard monitoring',
                'color' => 'success'
            ],
            '6-10' => [
                'rating' => 'Medium',
                'acceptance' => 'Accept with conditions',
                'monitoring' => 'Enhanced monitoring',
                'color' => 'warning'
            ],
            '11-15' => [
                'rating' => 'High',
                'acceptance' => 'Reject client',
                'monitoring' => 'Not applicable',
                'color' => 'danger'
            ]
        ];

        return view('client-risk.edit', compact('clientRisk', 'availableRiskIds', 'riskMatrix', 'identificationStatusOptions', 'riskRatingGuide'));
    }

    /**
     * Update the specified client risk assessment
     */
    public function update(Request $request, RiskAssessment $clientRisk)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_identification_status' => 'nullable|string',
            'client_screening_date' => 'nullable|date',
            'client_screening_result' => 'nullable|string',
            'client_screening_risk_id' => 'nullable|string',
            'client_screening_description' => 'nullable|string',
            'client_screening_impact' => 'nullable|string',
            'client_screening_likelihood' => 'nullable|string',
            'client_screening_risk_rating' => 'nullable|string',
            'client_category_risk_id' => 'nullable|string',
            'client_category_description' => 'nullable|string',
            'client_category_impact' => 'nullable|string',
            'client_category_likelihood' => 'nullable|string',
            'client_category_risk_rating' => 'nullable|string',
            'requested_services_risk_id' => 'nullable|string',
            'requested_services_description' => 'nullable|string',
            'requested_services_impact' => 'nullable|string',
            'requested_services_likelihood' => 'nullable|string',
            'requested_services_risk_rating' => 'nullable|string',
            'payment_option_risk_id' => 'nullable|string',
            'payment_option_description' => 'nullable|string',
            'payment_option_impact' => 'nullable|string',
            'payment_option_likelihood' => 'nullable|string',
            'payment_option_risk_rating' => 'nullable|string',
            'delivery_method_risk_id' => 'nullable|string',
            'delivery_method_description' => 'nullable|string',
            'delivery_method_impact' => 'nullable|string',
            'delivery_method_likelihood' => 'nullable|string',
            'delivery_method_risk_rating' => 'nullable|string',
            'dcs_risk_appetite' => 'nullable|string',
            'dcs_comments' => 'nullable|string',
        ]);

        // Calculate overall risk points and rating
        $totalPoints = $this->calculateTotalRiskPoints($validated);
        $overallRating = $this->calculateOverallRiskRating($totalPoints);
        $clientAcceptance = $this->determineClientAcceptance($totalPoints);
        $monitoringFrequency = $this->determineMonitoringFrequency($totalPoints);

        // Add calculated fields
        $validated['total_points'] = $totalPoints;
        $validated['overall_risk_rating'] = $overallRating;
        $validated['client_acceptance'] = $clientAcceptance;
        $validated['monitoring_frequency'] = $monitoringFrequency;

        // Store selected risk IDs
        $selectedRiskIds = [];
        $riskFields = [
            'client_screening_risk_id',
            'client_category_risk_id', 
            'requested_services_risk_id',
            'payment_option_risk_id',
            'delivery_method_risk_id'
        ];

        foreach ($riskFields as $field) {
            if (!empty($validated[$field])) {
                $selectedRiskIds[] = $validated[$field];
            }
        }
        $validated['selected_risk_ids'] = json_encode($selectedRiskIds);

        $clientRisk->update($validated);

        return redirect()->route('client-risk.index')
            ->with('success', 'Client Risk Assessment updated successfully.');
    }

    /**
     * Remove the specified client risk assessment
     */
    public function destroy(RiskAssessment $clientRisk)
    {
        $clientRisk->delete();

        return redirect()->route('client-risk.index')
            ->with('success', 'Client Risk Assessment deleted successfully.');
    }

    /**
     * Calculate total risk points based on selected risk IDs
     */
    private function calculateTotalRiskPoints($data)
    {
        $riskPoints = [
            'R001' => 2,
            'R002' => 1,
            'R003' => 3,
            'R004' => 2,
            'R005' => 1,
            'R006' => 5,
            'R007' => 4,
            'R008' => 5,
            'R009' => 4,
            'R010' => 5
        ];

        $totalPoints = 0;
        $riskFields = [
            'client_screening_risk_id',
            'client_category_risk_id', 
            'requested_services_risk_id',
            'payment_option_risk_id',
            'delivery_method_risk_id'
        ];

        foreach ($riskFields as $field) {
            if (!empty($data[$field]) && isset($riskPoints[$data[$field]])) {
                $totalPoints += $riskPoints[$data[$field]];
            }
        }

        return $totalPoints;
    }

    /**
     * Calculate overall risk rating based on total points
     */
    private function calculateOverallRiskRating($totalPoints)
    {
        if ($totalPoints >= 11) {
            return 'High';
        } elseif ($totalPoints >= 6) {
            return 'Medium';
        } else {
            return 'Low';
        }
    }

    /**
     * Determine client acceptance based on total points
     */
    private function determineClientAcceptance($totalPoints)
    {
        if ($totalPoints >= 11) {
            return 'Reject client';
        } elseif ($totalPoints >= 6) {
            return 'Accept with conditions';
        } else {
            return 'Accept client';
        }
    }

    /**
     * Determine monitoring frequency based on total points
     */
    private function determineMonitoringFrequency($totalPoints)
    {
        if ($totalPoints >= 11) {
            return 'Not applicable';
        } elseif ($totalPoints >= 6) {
            return 'Enhanced monitoring';
        } else {
            return 'Standard monitoring';
        }
    }

    /**
     * Get risk description for a given risk ID
     */
    public function getRiskDescription($riskId)
    {
        $descriptions = [
            'R001' => 'Client Screening Risk',
            'R002' => 'Client Category Risk',
            'R003' => 'Requested Services Risk',
            'R004' => 'Payment Option Risk',
            'R005' => 'Delivery Method Risk',
            'R006' => 'High-Risk Client',
            'R007' => 'PEP (Politically Exposed Person)',
            'R008' => 'Sanctions Risk',
            'R009' => 'Money Laundering Risk',
            'R010' => 'Terrorist Financing Risk'
        ];

        return $descriptions[$riskId] ?? 'Unknown Risk';
    }
} 