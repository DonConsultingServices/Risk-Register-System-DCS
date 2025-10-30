<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PredefinedRisk;
use App\Models\RiskCategory;

class PredefinedRiskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create predefined risks using existing categories
        $risks = [
            // Financial Risks
            [
                'title' => 'Insufficient Capital',
                'description' => 'Client lacks adequate capital to meet financial obligations',
                'risk_level' => 'High',
                'impact' => 'High',
                'likelihood' => 'Medium',
                'mitigation_measures' => 'Require additional capital, implement monitoring',
                'category_id' => RiskCategory::where('name', 'Financial Risk')->first()->id,
                'points' => 5,
            ],
            [
                'title' => 'Poor Credit History',
                'description' => 'Client has negative credit history or defaults',
                'risk_level' => 'Medium',
                'impact' => 'Medium',
                'likelihood' => 'High',
                'mitigation_measures' => 'Enhanced due diligence, collateral requirements',
                'category_id' => RiskCategory::where('name', 'Financial Risk')->first()->id,
                'points' => 3,
            ],
            
            // Operational Risks
            [
                'title' => 'Inadequate Internal Controls',
                'description' => 'Client lacks proper internal control systems',
                'risk_level' => 'High',
                'impact' => 'High',
                'likelihood' => 'Medium',
                'mitigation_measures' => 'Require control improvements, regular audits',
                'category_id' => RiskCategory::where('name', 'Operational Risk')->first()->id,
                'points' => 5,
            ],
            [
                'title' => 'Key Personnel Dependency',
                'description' => 'Client heavily dependent on key individuals',
                'risk_level' => 'Medium',
                'impact' => 'Medium',
                'likelihood' => 'Medium',
                'mitigation_measures' => 'Succession planning, knowledge transfer',
                'category_id' => RiskCategory::where('name', 'Operational Risk')->first()->id,
                'points' => 3,
            ],
            
            // Compliance Risks
            [
                'title' => 'Regulatory Violations',
                'description' => 'Client has history of regulatory violations',
                'risk_level' => 'High',
                'impact' => 'High',
                'likelihood' => 'Low',
                'mitigation_measures' => 'Enhanced monitoring, compliance training',
                'category_id' => RiskCategory::where('name', 'Compliance Risk')->first()->id,
                'points' => 5,
            ],
            [
                'title' => 'Incomplete Documentation',
                'description' => 'Client lacks proper documentation and records',
                'risk_level' => 'Medium',
                'impact' => 'Medium',
                'likelihood' => 'High',
                'mitigation_measures' => 'Documentation requirements, regular reviews',
                'category_id' => RiskCategory::where('name', 'Compliance Risk')->first()->id,
                'points' => 3,
            ],
            
            // Strategic Risks
            [
                'title' => 'Market Position Weakness',
                'description' => 'Client has weak competitive market position',
                'risk_level' => 'Medium',
                'impact' => 'Medium',
                'likelihood' => 'Medium',
                'mitigation_measures' => 'Business plan review, performance monitoring',
                'category_id' => RiskCategory::where('name', 'Strategic Risk')->first()->id,
                'points' => 3,
            ],
            [
                'title' => 'Industry Decline',
                'description' => 'Client operates in declining industry',
                'risk_level' => 'High',
                'impact' => 'High',
                'likelihood' => 'Low',
                'mitigation_measures' => 'Diversification strategy, exit planning',
                'category_id' => RiskCategory::where('name', 'Strategic Risk')->first()->id,
                'points' => 5,
            ],
            
            // Reputational Risks
            [
                'title' => 'Negative Media Coverage',
                'description' => 'Client has received negative media attention',
                'risk_level' => 'Medium',
                'impact' => 'High',
                'likelihood' => 'Low',
                'mitigation_measures' => 'Reputation monitoring, crisis management',
                'category_id' => RiskCategory::where('name', 'Reputational Risk')->first()->id,
                'points' => 3,
            ],
            [
                'title' => 'Customer Complaints',
                'description' => 'Client has high number of customer complaints',
                'risk_level' => 'Low',
                'impact' => 'Low',
                'likelihood' => 'High',
                'mitigation_measures' => 'Customer service improvements, complaint tracking',
                'category_id' => RiskCategory::where('name', 'Reputational Risk')->first()->id,
                'points' => 1,
            ],
        ];

        foreach ($risks as $risk) {
            PredefinedRisk::create($risk);
        }
    }
}
