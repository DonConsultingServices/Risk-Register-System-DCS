<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RisksExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $risks;

    public function __construct($risks)
    {
        $this->risks = $risks;
    }

    public function collection()
    {
        return $this->risks;
    }

    public function headings(): array
    {
        return [
            'Client Name',
            'Company',
            'Risk Title',
            'Risk Category',
            'Risk Level',
            'Impact',
            'Likelihood',
            'Risk Rating',
            'Overall Risk Points',
            'Assessment Status',
            'Approval Status',
            'Assessment Date',
            'Next Review Date',
            'Client Acceptance',
            'Ongoing Monitoring',
            'DCS Comments',
            'Assigned User',
            'Created At',
            'Updated At'
        ];
    }

    public function map($risk): array
    {
        return [
            $risk->client ? $risk->client->name : 'N/A',
            $risk->client ? ($risk->client->company ?? 'Individual') : 'N/A',
            $risk->title ?? 'Untitled Risk',
            $risk->risk_category ?? 'N/A',
            $risk->risk_level ?? $risk->overall_risk_rating ?? 'Not Assessed',
            $risk->impact ?? 'N/A',
            $risk->likelihood ?? 'N/A',
            $risk->risk_rating ?? 'N/A',
            $risk->overall_risk_points ?? 'N/A',
            $risk->status ?? 'In Progress',
            $risk->approval_status ?? 'Pending',
            $risk->created_at ? $risk->created_at->format('Y-m-d') : 'N/A',
            $risk->due_date ? $risk->due_date->format('Y-m-d') : 'N/A',
            $risk->client_acceptance ?? 'N/A',
            $risk->ongoing_monitoring ?? 'N/A',
            $risk->dcs_comments ?? 'N/A',
            $risk->assignedUser ? $risk->assignedUser->name : 'Unassigned',
            $risk->created_at ? $risk->created_at->format('Y-m-d H:i:s') : 'N/A',
            $risk->updated_at ? $risk->updated_at->format('Y-m-d H:i:s') : 'N/A'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20, // Client Name
            'B' => 20, // Company
            'C' => 30, // Risk Title
            'D' => 20, // Risk Category
            'E' => 15, // Risk Level
            'F' => 12, // Impact
            'G' => 15, // Likelihood
            'H' => 15, // Risk Rating
            'I' => 15, // Overall Risk Points
            'J' => 15, // Assessment Status
            'K' => 15, // Approval Status
            'L' => 15, // Assessment Date
            'M' => 15, // Next Review Date
            'N' => 20, // Client Acceptance
            'O' => 20, // Ongoing Monitoring
            'P' => 30, // DCS Comments
            'Q' => 20, // Assigned User
            'R' => 20, // Created At
            'S' => 20, // Updated At
        ];
    }
}
