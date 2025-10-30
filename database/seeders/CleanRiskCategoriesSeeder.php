<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CleanRiskCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        DB::table('risk_categories')->delete();

        // Insert ONLY the 12 specific risks from your Excel table
        $risks = [
            // Client Risk (CR) - 3 risks
            [
                'risk_id' => 'CR-01',
                'risk_name' => 'PIP / PEP client',
                'risk_detail' => 'High-risk client (e.g., politically exposed person, high-net-worth individual)',
                'risk_category' => 'CR',
                'impact' => 'High',
                'likelihood' => 'Medium',
                'risk_rating' => 'High',
                'mitigation_strategies' => 'Enhanced Due Diligence (EDD), ongoing monitoring',
                'owner' => 'Compliance Officer',
                'status' => 'Open'
            ],
            [
                'risk_id' => 'CR-02',
                'risk_name' => 'Corporate client',
                'risk_detail' => 'Corporate client with opaque ownership structure (beneficial ownership concerns)',
                'risk_category' => 'CR',
                'impact' => 'Medium',
                'likelihood' => 'High',
                'risk_rating' => 'Medium',
                'mitigation_strategies' => 'Verify UBOs (Ultimate Beneficial Owners), review corporate documents',
                'owner' => 'Compliance Officer',
                'status' => 'Open'
            ],
            [
                'risk_id' => 'CR-03',
                'risk_name' => 'Individual client',
                'risk_detail' => 'Individual client with inconsistent documentation (ID, proof of address)',
                'risk_category' => 'CR',
                'impact' => 'Medium',
                'likelihood' => 'Medium',
                'risk_rating' => 'Medium',
                'mitigation_strategies' => 'Stricter KYC (Know Your Customer) requirements',
                'owner' => 'Compliance Officer',
                'status' => 'Open'
            ],

            // Service Risk (SR) - 4 risks
            [
                'risk_id' => 'SR-01',
                'risk_name' => 'High-risk services',
                'risk_detail' => 'High-risk services (e.g., large cash transactions, cross-border payments)',
                'risk_category' => 'SR',
                'impact' => 'High',
                'likelihood' => 'High',
                'risk_rating' => 'High',
                'mitigation_strategies' => 'Specialized training, legal review, compliance checks',
                'owner' => 'Service Manager',
                'status' => 'Open'
            ],
            [
                'risk_id' => 'SR-02',
                'risk_name' => 'Complex services',
                'risk_detail' => 'Complex services with high regulatory scrutiny (e.g., tax advisory, financial planning)',
                'risk_category' => 'SR',
                'impact' => 'Medium',
                'likelihood' => 'Medium',
                'risk_rating' => 'Medium',
                'mitigation_strategies' => 'Standardized checklists, periodic reviews',
                'owner' => 'Operations Manager',
                'status' => 'Closed'
            ],
            [
                'risk_id' => 'SR-03',
                'risk_name' => 'Standard services',
                'risk_detail' => 'Standard services with low complexity (lower risk but potential for complacency)',
                'risk_category' => 'SR',
                'impact' => 'Low',
                'likelihood' => 'Medium',
                'risk_rating' => 'Low',
                'mitigation_strategies' => 'Standardized checklists, periodic reviews',
                'owner' => 'Operations Manager',
                'status' => 'Closed'
            ],
            [
                'risk_id' => 'SR-04',
                'risk_name' => 'Unrecorded face-to-face transactions',
                'risk_detail' => 'Unrecorded face-to-face transactions (no audit trail)',
                'risk_category' => 'SR',
                'impact' => 'Medium',
                'likelihood' => 'Medium',
                'risk_rating' => 'Medium',
                'mitigation_strategies' => 'Specialized training, legal review, compliance checks',
                'owner' => 'Compliance Officer',
                'status' => 'Open'
            ],

            // Payment Risk (PR) - 3 risks
            [
                'risk_id' => 'PR-01',
                'risk_name' => 'Cash Payments',
                'risk_detail' => 'Cash payments increasing money laundering risk',
                'risk_category' => 'PR',
                'impact' => 'High',
                'likelihood' => 'High',
                'risk_rating' => 'High',
                'mitigation_strategies' => 'Cash payment limits, mandatory reporting for large transactions',
                'owner' => 'Finance Team',
                'status' => 'Open'
            ],
            [
                'risk_id' => 'PR-02',
                'risk_name' => 'EFTs/SWIFT',
                'risk_detail' => 'EFT/SWIFT payments (risk of fraud, incorrect beneficiary details)',
                'risk_category' => 'PR',
                'impact' => 'Medium',
                'likelihood' => 'Medium',
                'risk_rating' => 'Medium',
                'mitigation_strategies' => 'Dual approval for large transfers, client confirmation protocols',
                'owner' => 'Finance Team',
                'status' => 'Open'
            ],
            [
                'risk_id' => 'PR-03',
                'risk_name' => 'POS Payments',
                'risk_detail' => 'POS payments (risk of chargebacks, disputes)',
                'risk_category' => 'PR',
                'impact' => 'Low',
                'likelihood' => 'Medium',
                'risk_rating' => 'Low',
                'mitigation_strategies' => 'Clear refund policies, transaction records',
                'owner' => 'Finance Team',
                'status' => 'Closed'
            ],

            // Delivery Risk (DR) - 2 risks
            [
                'risk_id' => 'DR-01',
                'risk_name' => 'Remote service risks',
                'risk_detail' => 'Remote onboarding without proper identity verification',
                'risk_category' => 'DR',
                'impact' => 'High',
                'likelihood' => 'Medium',
                'risk_rating' => 'High',
                'mitigation_strategies' => 'Multi-factor authentication (MFA), secure client portals',
                'owner' => 'IT Security',
                'status' => 'Open'
            ],
            [
                'risk_id' => 'DR-02',
                'risk_name' => 'Face-to-face service risks',
                'risk_detail' => 'Face-to-face service risks (data security, physical safety)',
                'risk_category' => 'DR',
                'impact' => 'Medium',
                'likelihood' => 'Low',
                'risk_rating' => 'Medium',
                'mitigation_strategies' => 'Secure document handling, staff training on confidentiality',
                'owner' => 'HR/Security',
                'status' => 'Open'
            ]
        ];

        DB::table('risk_categories')->insert($risks);
    }
}
