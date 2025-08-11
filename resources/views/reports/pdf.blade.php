<!DOCTYPE html>
<html>
<head>
    <title>DCS Risk Assessment Report</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 20px; 
            line-height: 1.6;
        }
        .header { 
            text-align: center; 
            margin-bottom: 30px; 
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #007bff;
            margin-bottom: 10px;
        }
        .stats-grid { 
            display: grid; 
            grid-template-columns: repeat(4, 1fr); 
            gap: 20px; 
            margin-bottom: 30px; 
        }
        .stat-card { 
            border: 1px solid #ddd; 
            padding: 15px; 
            text-align: center; 
            border-radius: 5px;
        }
        .stat-number { 
            font-size: 24px; 
            font-weight: bold; 
            color: #007bff; 
        }
        .stat-label { 
            font-size: 14px; 
            color: #666; 
            margin-top: 5px;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 8px; 
            text-align: left; 
        }
        th { 
            background-color: #f8f9fa; 
            font-weight: bold;
        }
        .page-break { 
            page-break-before: always; 
        }
        .section-title {
            color: #007bff;
            border-bottom: 1px solid #007bff;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        .risk-high { color: #dc3545; font-weight: bold; }
        .risk-medium { color: #ffc107; font-weight: bold; }
        .risk-low { color: #28a745; font-weight: bold; }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        @media print {
            body { margin: 0; }
            .page-break { page-break-before: always; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>DCS Risk Assessment Report</h1>
        <p><strong>Generated on:</strong> {{ date('F d, Y') }}</p>
        <p><strong>Period:</strong> Last {{ $period }} days</p>
        <p><strong>Report ID:</strong> RPT-{{ date('Ymd') }}-{{ str_pad($stats['total_assessments'], 4, '0', STR_PAD_LEFT) }}</p>
    </div>
    
    <div class="section-title">
        <h2>Executive Summary</h2>
    </div>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ $stats['total_assessments'] }}</div>
            <div class="stat-label">Total Assessments</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['high_risk_count'] }}</div>
            <div class="stat-label">High Risk Clients</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['average_risk_points'] }}</div>
            <div class="stat-label">Average Risk Points</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ count($assessments) }}</div>
            <div class="stat-label">Period Assessments</div>
        </div>
    </div>

    <div class="section-title">
        <h2>Risk Rating Distribution</h2>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Risk Rating</th>
                <th>Count</th>
                <th>Percentage</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stats['risk_rating_stats'] as $rating => $count)
            <tr>
                <td class="risk-{{ strtolower($rating) }}">{{ $rating }}</td>
                <td>{{ $count }}</td>
                <td>{{ round(($count / $stats['total_assessments']) * 100, 1) }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">
        <h2>Client Acceptance Status</h2>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Acceptance Status</th>
                <th>Count</th>
                <th>Percentage</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stats['acceptance_stats'] as $status => $count)
            <tr>
                <td>{{ $status }}</td>
                <td>{{ $count }}</td>
                <td>{{ round(($count / $stats['total_assessments']) * 100, 1) }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="page-break"></div>

    <div class="section-title">
        <h2>Detailed Risk Assessment Report</h2>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Client Name</th>
                <th>Risk Rating</th>
                <th>Total Points</th>
                <th>Client Acceptance</th>
                <th>Monitoring Frequency</th>
                <th>Assessment Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assessments as $assessment)
            <tr>
                <td><strong>{{ $assessment->client_name }}</strong></td>
                <td class="risk-{{ strtolower($assessment->overall_risk_rating) }}">
                    {{ $assessment->overall_risk_rating }}
                </td>
                <td>{{ $assessment->total_points }}</td>
                <td>{{ $assessment->client_acceptance ?? 'N/A' }}</td>
                <td>{{ $assessment->monitoring_frequency ?? 'N/A' }}</td>
                <td>{{ $assessment->getFormattedAssessmentDate() }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($assessments->count() > 0)
    <div class="page-break"></div>
    
    <div class="section-title">
        <h2>Risk Assessment Details</h2>
    </div>
    
    @foreach($assessments as $index => $assessment)
    <div style="margin-bottom: 30px; border: 1px solid #ddd; padding: 15px; border-radius: 5px;">
        <h3>{{ $index + 1 }}. {{ $assessment->client_name }}</h3>
        <table style="width: 100%; margin-top: 10px;">
            <tr>
                <td><strong>Risk Rating:</strong></td>
                <td class="risk-{{ strtolower($assessment->overall_risk_rating) }}">
                    {{ $assessment->overall_risk_rating }}
                </td>
                <td><strong>Total Points:</strong></td>
                <td>{{ $assessment->total_points }}</td>
            </tr>
            <tr>
                <td><strong>Client Acceptance:</strong></td>
                <td>{{ $assessment->client_acceptance ?? 'N/A' }}</td>
                <td><strong>Monitoring Frequency:</strong></td>
                <td>{{ $assessment->monitoring_frequency ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Assessment Date:</strong></td>
                <td>{{ $assessment->getFormattedAssessmentDate() }}</td>
                <td><strong>Created:</strong></td>
                <td>{{ $assessment->created_at->format('M d, Y H:i') }}</td>
            </tr>
        </table>
        
        @if($assessment->selected_risk_ids && count($assessment->selected_risk_ids) > 0)
        <div style="margin-top: 10px;">
            <strong>Selected Risk IDs:</strong>
            @foreach($assessment->selected_risk_ids as $riskId)
                <span style="background: #f8f9fa; padding: 2px 6px; margin: 2px; border-radius: 3px; font-size: 12px;">
                    {{ $riskId }}
                </span>
            @endforeach
        </div>
        @endif
        
        @if($assessment->dcs_comments)
        <div style="margin-top: 10px;">
            <strong>DCS Comments:</strong><br>
            <em>{{ $assessment->dcs_comments }}</em>
        </div>
        @endif
    </div>
    @endforeach
    @endif

    <div class="footer">
        <p>This report was generated automatically by the DCS Risk Assessment System.</p>
        <p>For questions or concerns, please contact the system administrator.</p>
        <p>Page generated on {{ date('F d, Y \a\t H:i:s') }}</p>
    </div>
</body>
</html> 