<?php

namespace App\Http\Controllers;

use App\Models\RiskAssessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display the reports page
     */
    public function index()
    {
        $period = request('period', 30);
        $stats = $this->getReportStats($period);
        $assessments = $this->getAssessmentsForPeriod($period);
        
        return view('reports.index', compact('stats', 'assessments', 'period'));
    }

    /**
     * Generate PDF report
     */
    public function generatePdf(Request $request)
    {
        $period = $request->get('period', 30);
        $stats = $this->getReportStats($period);
        $assessments = $this->getAssessmentsForPeriod($period);
        
        // Generate PDF content
        $pdfContent = $this->generatePdfContent($stats, $assessments, $period);
        
        // For now, return HTML that can be printed
        return view('reports.pdf', compact('stats', 'assessments', 'period'));
    }

    /**
     * Export CSV report
     */
    public function exportCsv(Request $request)
    {
        $period = $request->get('period', 30);
        $assessments = $this->getAssessmentsForPeriod($period);
        
        $filename = 'risk_assessment_report_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($assessments) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'Client Name',
                'Risk Rating',
                'Total Points',
                'Client Acceptance',
                'Monitoring Frequency',
                'Assessment Date',
                'Screening Risk ID',
                'Category Risk ID',
                'Services Risk ID',
                'Payment Risk ID',
                'Delivery Risk ID'
            ]);
            
            // CSV data
            foreach ($assessments as $assessment) {
                fputcsv($file, [
                    $assessment->client_name,
                    $assessment->overall_risk_rating,
                    $assessment->total_points,
                    $assessment->client_acceptance,
                    $assessment->monitoring_frequency,
                    $assessment->getFormattedAssessmentDate(),
                    $assessment->client_screening_risk_id,
                    $assessment->client_category_risk_id,
                    $assessment->requested_services_risk_id,
                    $assessment->payment_option_risk_id,
                    $assessment->delivery_method_risk_id
                ]);
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }

    /**
     * Export Excel report
     */
    public function exportExcel(Request $request)
    {
        $period = $request->get('period', 30);
        $assessments = $this->getAssessmentsForPeriod($period);
        
        $filename = 'risk_assessment_report_' . date('Y-m-d') . '.xlsx';
        
        // For now, return CSV as Excel (you can implement proper Excel generation later)
        return $this->exportCsv($request);
    }

    /**
     * Get report statistics
     */
    private function getReportStats($period)
    {
        $startDate = Carbon::now()->subDays($period);
        
        $totalAssessments = RiskAssessment::where('created_at', '>=', $startDate)->count();
        
        $riskRatingStats = RiskAssessment::where('created_at', '>=', $startDate)
            ->selectRaw('overall_risk_rating, COUNT(*) as count')
            ->whereNotNull('overall_risk_rating')
            ->groupBy('overall_risk_rating')
            ->pluck('count', 'overall_risk_rating')
            ->toArray();
        
        $acceptanceStats = RiskAssessment::where('created_at', '>=', $startDate)
            ->selectRaw('client_acceptance, COUNT(*) as count')
            ->whereNotNull('client_acceptance')
            ->groupBy('client_acceptance')
            ->pluck('count', 'client_acceptance')
            ->toArray();
        
        $averageRiskPoints = RiskAssessment::where('created_at', '>=', $startDate)
            ->whereNotNull('total_points')
            ->avg('total_points');
        
        $highRiskCount = RiskAssessment::where('created_at', '>=', $startDate)
            ->where('overall_risk_rating', 'High')
            ->count();
        
        return [
            'total_assessments' => $totalAssessments,
            'risk_rating_stats' => $riskRatingStats,
            'acceptance_stats' => $acceptanceStats,
            'average_risk_points' => round($averageRiskPoints, 2),
            'high_risk_count' => $highRiskCount,
            'period' => $period
        ];
    }

    /**
     * Get assessments for specific period
     */
    private function getAssessmentsForPeriod($period)
    {
        $startDate = Carbon::now()->subDays($period);
        
        return RiskAssessment::where('created_at', '>=', $startDate)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Generate PDF content
     */
    private function generatePdfContent($stats, $assessments, $period)
    {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <title>DCS Risk Assessment Report</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .header { text-align: center; margin-bottom: 30px; }
                .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px; }
                .stat-card { border: 1px solid #ddd; padding: 15px; text-align: center; }
                .stat-number { font-size: 24px; font-weight: bold; color: #007bff; }
                .stat-label { font-size: 14px; color: #666; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f8f9fa; }
                .page-break { page-break-before: always; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>DCS Risk Assessment Report</h1>
                <p>Generated on: ' . date('F d, Y') . '</p>
                <p>Period: Last ' . $period . ' days</p>
            </div>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">' . $stats['total_assessments'] . '</div>
                    <div class="stat-label">Total Assessments</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">' . $stats['high_risk_count'] . '</div>
                    <div class="stat-label">High Risk Clients</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">' . $stats['average_risk_points'] . '</div>
                    <div class="stat-label">Average Risk Points</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">' . count($assessments) . '</div>
                    <div class="stat-label">Period Assessments</div>
                </div>
            </div>
            
            <h2>Risk Assessment Details</h2>
            <table>
                <thead>
                    <tr>
                        <th>Client Name</th>
                        <th>Risk Rating</th>
                        <th>Points</th>
                        <th>Acceptance</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>';
        
        foreach ($assessments as $assessment) {
            $html .= '
                    <tr>
                        <td>' . $assessment->client_name . '</td>
                        <td>' . $assessment->overall_risk_rating . '</td>
                        <td>' . $assessment->total_points . '</td>
                        <td>' . $assessment->client_acceptance . '</td>
                        <td>' . $assessment->getFormattedAssessmentDate() . '</td>
                    </tr>';
        }
        
        $html .= '
                </tbody>
            </table>
        </body>
        </html>';
        
        return $html;
    }
} 