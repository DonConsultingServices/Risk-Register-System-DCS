<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RiskCategory;

class RiskCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Financial Risk',
                'description' => 'Risks related to financial stability, capital adequacy, and creditworthiness',
                'color' => '#dc3545',
                'is_active' => true,
            ],
            [
                'name' => 'Operational Risk',
                'description' => 'Risks arising from internal processes, systems, and human factors',
                'color' => '#fd7e14',
                'is_active' => true,
            ],
            [
                'name' => 'Compliance Risk',
                'description' => 'Risks related to regulatory compliance and legal requirements',
                'color' => '#ffc107',
                'is_active' => true,
            ],
            [
                'name' => 'Strategic Risk',
                'description' => 'Risks related to business strategy, market position, and industry trends',
                'color' => '#20c997',
                'is_active' => true,
            ],
            [
                'name' => 'Reputational Risk',
                'description' => 'Risks related to public perception, media coverage, and brand reputation',
                'color' => '#6f42c1',
                'is_active' => true,
            ],
            [
                'name' => 'Technology Risk',
                'description' => 'Risks related to IT systems, cybersecurity, and digital infrastructure',
                'color' => '#17a2b8',
                'is_active' => true,
            ],
            [
                'name' => 'Environmental Risk',
                'description' => 'Risks related to environmental factors and sustainability',
                'color' => '#28a745',
                'is_active' => true,
            ],
            [
                'name' => 'Geopolitical Risk',
                'description' => 'Risks related to political stability, international relations, and regional conflicts',
                'color' => '#6c757d',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            RiskCategory::create($category);
        }
    }
}
