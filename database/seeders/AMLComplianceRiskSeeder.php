<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RiskCategory;
use App\Models\PredefinedRisk;

class AMLComplianceRiskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing AML risk categories and predefined risks
        RiskCategory::whereIn('name', ['Client Risk', 'Service Risk', 'Payment Risk', 'Delivery Risk'])->delete();
        PredefinedRisk::whereIn('risk_id', ['CR-01', 'CR-02', 'CR-03', 'SR-01', 'SR-02', 'SR-03', 'SR-04', 'PR-01', 'PR-02', 'PR-03', 'DR-01', 'DR-02'])->delete();

        // Create the specific AML/Compliance risk categories
        $categories = [
            [
                'name' => 'Client Risk',
                'description' => 'Risks related to client identification, verification, and due diligence',
                'color' => '#007bff', // Blue
                'is_active' => true,
                'risk_prefix' => 'CR',
            ],
            [
                'name' => 'Service Risk', 
                'description' => 'Risks related to services provided and service delivery methods',
                'color' => '#17a2b8', // Info blue
                'is_active' => true,
                'risk_prefix' => 'SR',
            ],
            [
                'name' => 'Payment Risk',
                'description' => 'Risks related to payment methods and transaction processing',
                'color' => '#ffc107', // Warning yellow
                'is_active' => true,
                'risk_prefix' => 'PR',
            ],
            [
                'name' => 'Delivery Risk',
                'description' => 'Risks related to service delivery channels and methods',
                'color' => '#6c757d', // Secondary gray
                'is_active' => true,
                'risk_prefix' => 'DR',
            ],
        ];

        foreach ($categories as $category) {
            RiskCategory::create($category);
        }

        // Create predefined risks based on your comprehensive risk assessment table
        $predefinedRisks = [
            // CLIENT RISKS (CR)
            [
                'risk_id' => 'CR-01',
                'title' => 'PIP / PEP client',
                'description' => 'High-risk client (e.g., politically exposed person, high-net-worth individual)',
                'risk_level' => 'High',
                'impact' => 'High',
                'likelihood' => 'Medium',
                'mitigation_measures' => 'Enhanced Due Diligence (EDD), ongoing monitoring',
                'owner' => 'Compliance Officer',
                'status' => 'Open',
                'points' => 5,
                'category_id' => RiskCategory::where('name', 'Client Risk')->first()->id,
            ],
            [
                'risk_id' => 'CR-02',
                'title' => 'Corporate client',
                'description' => 'Corporate client with opaque ownership structure (beneficial ownership concerns)',
                'risk_level' => 'Medium',
                'impact' => 'Medium',
                'likelihood' => 'High',
                'mitigation_measures' => 'Verify UBOs (Ultimate Beneficial Owners), review corporate documents',
                'owner' => 'Compliance Officer',
                'status' => 'Open',
                'points' => 3,
                'category_id' => RiskCategory::where('name', 'Client Risk')->first()->id,
            ],
            [
                'risk_id' => 'CR-03',
                'title' => 'Individual client',
                'description' => 'Individual client with inconsistent documentation (ID, proof of address)',
                'risk_level' => 'Medium',
                'impact' => 'Medium',
                'likelihood' => 'Medium',
                'mitigation_measures' => 'Stricter KYC (Know Your Customer) requirements',
                'owner' => 'Compliance Officer',
                'status' => 'Open',
                'points' => 3,
                'category_id' => RiskCategory::where('name', 'Client Risk')->first()->id,
            ],

            // SERVICE RISKS (SR)
            [
                'risk_id' => 'SR-01',
                'title' => 'High-risk services',
                'description' => 'High-risk services (e.g., large cash transactions, cross-border payments)',
                'risk_level' => 'High',
                'impact' => 'High',
                'likelihood' => 'High',
                'mitigation_measures' => 'Specialized training, legal review, compliance checks',
                'owner' => 'Service Manager',
                'status' => 'Open',
                'points' => 5,
                'category_id' => RiskCategory::where('name', 'Service Risk')->first()->id,
            ],
            [
                'risk_id' => 'SR-02',
                'title' => 'Complex services',
                'description' => 'Complex services with high regulatory scrutiny (e.g., tax advisory, financial planning)',
                'risk_level' => 'Medium',
                'impact' => 'Medium',
                'likelihood' => 'Medium',
                'mitigation_measures' => 'Standardized checklists, periodic reviews',
                'owner' => 'Operations Manager',
                'status' => 'Closed',
                'points' => 3,
                'category_id' => RiskCategory::where('name', 'Service Risk')->first()->id,
            ],
            [
                'risk_id' => 'SR-03',
                'title' => 'Standard services',
                'description' => 'Standard services with low complexity (lower risk but potential for complacency)',
                'risk_level' => 'Low',
                'impact' => 'Low',
                'likelihood' => 'Medium',
                'mitigation_measures' => 'Standardized checklists, periodic reviews',
                'owner' => 'Operations Manager',
                'status' => 'Closed',
                'points' => 1,
                'category_id' => RiskCategory::where('name', 'Service Risk')->first()->id,
            ],
            [
                'risk_id' => 'SR-04',
                'title' => 'Unrecorded face-to-face transactions',
                'description' => 'Unrecorded face-to-face transactions (no audit trail)',
                'risk_level' => 'Medium',
                'impact' => 'Medium',
                'likelihood' => 'Medium',
                'mitigation_measures' => 'Specialized training, legal review, compliance checks',
                'owner' => 'Compliance Officer',
                'status' => 'Open',
                'points' => 3,
                'category_id' => RiskCategory::where('name', 'Service Risk')->first()->id,
            ],

            // PAYMENT RISKS (PR)
            [
                'risk_id' => 'PR-01',
                'title' => 'Cash Payments',
                'description' => 'Cash payments increasing money laundering risk',
                'risk_level' => 'High',
                'impact' => 'High',
                'likelihood' => 'High',
                'mitigation_measures' => 'Cash payment limits, mandatory reporting for large transactions',
                'owner' => 'Finance Team',
                'status' => 'Open',
                'points' => 5,
                'category_id' => RiskCategory::where('name', 'Payment Risk')->first()->id,
            ],
            [
                'risk_id' => 'PR-02',
                'title' => 'EFTs/SWIFT',
                'description' => 'EFT/SWIFT payments (risk of fraud, incorrect beneficiary details)',
                'risk_level' => 'Medium',
                'impact' => 'Medium',
                'likelihood' => 'Medium',
                'mitigation_measures' => 'Dual approval for large transfers, client confirmation protocols',
                'owner' => 'Finance Team',
                'status' => 'Open',
                'points' => 3,
                'category_id' => RiskCategory::where('name', 'Payment Risk')->first()->id,
            ],
            [
                'risk_id' => 'PR-03',
                'title' => 'POS Payments',
                'description' => 'POS payments (risk of chargebacks, disputes)',
                'risk_level' => 'Low',
                'impact' => 'Low',
                'likelihood' => 'Medium',
                'mitigation_measures' => 'Clear refund policies, transaction records',
                'owner' => 'Finance Team',
                'status' => 'Closed',
                'points' => 1,
                'category_id' => RiskCategory::where('name', 'Payment Risk')->first()->id,
            ],

            // DELIVERY RISKS (DR)
            [
                'risk_id' => 'DR-01',
                'title' => 'Remote service risks',
                'description' => 'Remote onboarding without proper identity verification',
                'risk_level' => 'High',
                'impact' => 'High',
                'likelihood' => 'Medium',
                'mitigation_measures' => 'Multi-factor authentication (MFA), secure client portals',
                'owner' => 'IT Security',
                'status' => 'Open',
                'points' => 5,
                'category_id' => RiskCategory::where('name', 'Delivery Risk')->first()->id,
            ],
            [
                'risk_id' => 'DR-02',
                'title' => 'Face-to-face service risks',
                'description' => 'Face-to-face service risks (data security, physical safety)',
                'risk_level' => 'Medium',
                'impact' => 'Medium',
                'likelihood' => 'Low',
                'mitigation_measures' => 'Secure document handling, staff training on confidentiality',
                'owner' => 'HR/Security',
                'status' => 'Open',
                'points' => 3,
                'category_id' => RiskCategory::where('name', 'Delivery Risk')->first()->id,
            ],
        ];

        foreach ($predefinedRisks as $risk) {
            PredefinedRisk::create($risk);
        }
    }
}