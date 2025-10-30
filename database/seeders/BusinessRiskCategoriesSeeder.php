<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusinessRiskCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        DB::table('key_controls')->delete();
        DB::table('risk_examples')->delete();
        DB::table('business_risk_categories')->delete();

        // Insert the 4 main risk categories
        $categories = [
            [
                'id' => 1,
                'code' => 'CR',
                'name' => 'Client Risk',
                'description' => 'Risks related to client relationships and client screening processes',
                'icon_class' => 'fas fa-user-shield',
                'color' => '#00072D',
                'is_active' => true
            ],
            [
                'id' => 2,
                'code' => 'SR',
                'name' => 'Service Risk',
                'description' => 'Risks associated with the services provided and service delivery',
                'icon_class' => 'fas fa-cogs',
                'color' => '#00072D',
                'is_active' => true
            ],
            [
                'id' => 3,
                'code' => 'PR',
                'name' => 'Payment Risk',
                'description' => 'Risks concerning payment processes and financial transactions',
                'icon_class' => 'fas fa-credit-card',
                'color' => '#00072D',
                'is_active' => true
            ],
            [
                'id' => 4,
                'code' => 'DR',
                'name' => 'Delivery Risk',
                'description' => 'Risks related to the delivery of services or products',
                'icon_class' => 'fas fa-truck',
                'color' => '#00072D',
                'is_active' => true
            ]
        ];

        DB::table('business_risk_categories')->insert($categories);

        // Insert the 12 specific risk examples exactly as shown in the table
        $riskExamples = [
            // Client Risk (CR) - 3 risks
            [
                'business_risk_category_id' => 1,
                'risk_id' => 'CR-01',
                'title' => 'PIP / PEP client',
                'detail' => 'High-risk client (e.g., politically exposed person, high-net-worth individual)',
                'impact' => 'High',
                'likelihood' => 'Medium',
                'risk_rating' => 'High',
                'mitigation_strategies' => 'Enhanced Due Diligence (EDD), ongoing monitoring',
                'owner' => 'Compliance Officer',
                'status' => 'Open'
            ],
            [
                'business_risk_category_id' => 1,
                'risk_id' => 'CR-02',
                'title' => 'Corporate client',
                'detail' => 'Corporate client with opaque ownership structure (beneficial ownership concerns)',
                'impact' => 'Medium',
                'likelihood' => 'High',
                'risk_rating' => 'Medium',
                'mitigation_strategies' => 'Verify UBOs (Ultimate Beneficial Owners), review corporate documents',
                'owner' => 'Compliance Officer',
                'status' => 'Open'
            ],
            [
                'business_risk_category_id' => 1,
                'risk_id' => 'CR-03',
                'title' => 'Individual client',
                'detail' => 'Individual client with inconsistent documentation (ID, proof of address)',
                'impact' => 'Medium',
                'likelihood' => 'Medium',
                'risk_rating' => 'Medium',
                'mitigation_strategies' => 'Stricter KYC (Know Your Customer) requirements',
                'owner' => 'Compliance Officer',
                'status' => 'Open'
            ],

            // Service Risk (SR) - 4 risks
            [
                'business_risk_category_id' => 2,
                'risk_id' => 'SR-01',
                'title' => 'High-risk services',
                'detail' => 'High-risk services (e.g., large cash transactions, cross-border payments)',
                'impact' => 'High',
                'likelihood' => 'High',
                'risk_rating' => 'High',
                'mitigation_strategies' => 'Specialized training, legal review, compliance checks',
                'owner' => 'Service Manager',
                'status' => 'Open'
            ],
            [
                'business_risk_category_id' => 2,
                'risk_id' => 'SR-02',
                'title' => 'Complex services',
                'detail' => 'Complex services with high regulatory scrutiny (e.g., tax advisory, financial planning)',
                'impact' => 'Medium',
                'likelihood' => 'Medium',
                'risk_rating' => 'Medium',
                'mitigation_strategies' => 'Standardized checklists, periodic reviews',
                'owner' => 'Operations Manager',
                'status' => 'Closed'
            ],
            [
                'business_risk_category_id' => 2,
                'risk_id' => 'SR-03',
                'title' => 'Standard services',
                'detail' => 'Standard services with low complexity (lower risk but potential for complacency)',
                'impact' => 'Low',
                'likelihood' => 'Medium',
                'risk_rating' => 'Low',
                'mitigation_strategies' => 'Standardized checklists, periodic reviews',
                'owner' => 'Operations Manager',
                'status' => 'Closed'
            ],
            [
                'business_risk_category_id' => 2,
                'risk_id' => 'SR-04',
                'title' => 'Unrecorded face-to-face transactions',
                'detail' => 'Unrecorded face-to-face transactions (no audit trail)',
                'impact' => 'Medium',
                'likelihood' => 'Medium',
                'risk_rating' => 'Medium',
                'mitigation_strategies' => 'Specialized training, legal review, compliance checks',
                'owner' => 'Compliance Officer',
                'status' => 'Open'
            ],

            // Payment Risk (PR) - 3 risks
            [
                'business_risk_category_id' => 3,
                'risk_id' => 'PR-01',
                'title' => 'Cash Payments',
                'detail' => 'Cash payments increasing money laundering risk',
                'impact' => 'High',
                'likelihood' => 'High',
                'risk_rating' => 'High',
                'mitigation_strategies' => 'Cash payment limits, mandatory reporting for large transactions',
                'owner' => 'Finance Team',
                'status' => 'Open'
            ],
            [
                'business_risk_category_id' => 3,
                'risk_id' => 'PR-02',
                'title' => 'EFTs/SWIFT',
                'detail' => 'EFT/SWIFT payments (risk of fraud, incorrect beneficiary details)',
                'impact' => 'Medium',
                'likelihood' => 'Medium',
                'risk_rating' => 'Medium',
                'mitigation_strategies' => 'Dual approval for large transfers, client confirmation protocols',
                'owner' => 'Finance Team',
                'status' => 'Open'
            ],
            [
                'business_risk_category_id' => 3,
                'risk_id' => 'PR-03',
                'title' => 'POS Payments',
                'detail' => 'POS payments (risk of chargebacks, disputes)',
                'impact' => 'Low',
                'likelihood' => 'Medium',
                'risk_rating' => 'Low',
                'mitigation_strategies' => 'Clear refund policies, transaction records',
                'owner' => 'Finance Team',
                'status' => 'Closed'
            ],

            // Delivery Risk (DR) - 2 risks
            [
                'business_risk_category_id' => 4,
                'risk_id' => 'DR-01',
                'title' => 'Remote service risks',
                'detail' => 'Remote onboarding without proper identity verification',
                'impact' => 'High',
                'likelihood' => 'Medium',
                'risk_rating' => 'High',
                'mitigation_strategies' => 'Multi-factor authentication (MFA), secure client portals',
                'owner' => 'IT Security',
                'status' => 'Open'
            ],
            [
                'business_risk_category_id' => 4,
                'risk_id' => 'DR-02',
                'title' => 'Face-to-face service risks',
                'detail' => 'Face-to-face service risks (data security, physical safety)',
                'impact' => 'Medium',
                'likelihood' => 'Low',
                'risk_rating' => 'Medium',
                'mitigation_strategies' => 'Secure document handling, staff training on confidentiality',
                'owner' => 'HR/Security',
                'status' => 'Open'
            ]
        ];

        DB::table('risk_examples')->insert($riskExamples);

        // Insert key controls for each category
        $keyControls = [
            // Client Risk Controls
            [
                'business_risk_category_id' => 1,
                'control_name' => 'Enhanced Due Diligence (EDD)',
                'description' => 'Comprehensive client screening for high-risk clients including PEP checks, source of funds verification, and ongoing monitoring'
            ],
            [
                'business_risk_category_id' => 1,
                'control_name' => 'KYC Procedures',
                'description' => 'Know Your Customer procedures including identity verification, address verification, and beneficial ownership checks'
            ],
            [
                'business_risk_category_id' => 1,
                'control_name' => 'Client Risk Assessment',
                'description' => 'Regular risk assessment of existing clients and periodic review of client relationships'
            ],

            // Service Risk Controls
            [
                'business_risk_category_id' => 2,
                'control_name' => 'Service Risk Assessment',
                'description' => 'Pre-service risk assessment for all new service offerings and complex transactions'
            ],
            [
                'business_risk_category_id' => 2,
                'control_name' => 'Staff Training',
                'description' => 'Regular training on risk identification, compliance requirements, and service delivery standards'
            ],
            [
                'business_risk_category_id' => 2,
                'control_name' => 'Service Checklists',
                'description' => 'Standardized checklists and procedures for each service type to ensure consistency and risk mitigation'
            ],

            // Payment Risk Controls
            [
                'business_risk_category_id' => 3,
                'control_name' => 'Payment Limits',
                'description' => 'Transaction limits and approval requirements based on risk assessment and client profile'
            ],
            [
                'business_risk_category_id' => 3,
                'control_name' => 'Dual Approval',
                'description' => 'Dual approval system for high-value transactions and unusual payment patterns'
            ],
            [
                'business_risk_category_id' => 3,
                'control_name' => 'Transaction Monitoring',
                'description' => 'Real-time monitoring of payment transactions for suspicious activity and compliance violations'
            ],

            // Delivery Risk Controls
            [
                'business_risk_category_id' => 4,
                'control_name' => 'Secure Delivery Channels',
                'description' => 'Secure channels for service delivery including encrypted communications and secure portals'
            ],
            [
                'business_risk_category_id' => 4,
                'control_name' => 'Data Protection',
                'description' => 'Data protection measures including encryption, access controls, and secure document handling'
            ],
            [
                'business_risk_category_id' => 4,
                'control_name' => 'Staff Security Training',
                'description' => 'Regular security training for staff on data protection, confidentiality, and physical security measures'
            ]
        ];

        DB::table('key_controls')->insert($keyControls);
    }
}
