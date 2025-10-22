<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Risk Assessments Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #2c3e50;
            margin: 0;
            font-size: 24px;
        }
        
        .header .subtitle {
            color: #7f8c8d;
            margin: 5px 0 0 0;
            font-size: 14px;
        }
        
        .summary {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #3498db;
        }
        
        .summary h3 {
            margin: 0 0 10px 0;
            color: #2c3e50;
            font-size: 16px;
        }
        
        .summary-stats {
            display: flex;
            justify-content: space-around;
            margin-top: 10px;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-number {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
        }
        
        .stat-label {
            font-size: 11px;
            color: #7f8c8d;
            text-transform: uppercase;
        }
        
        .high-risk { color: #e74c3c; }
        .medium-risk { color: #f39c12; }
        .low-risk { color: #27ae60; }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 10px;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }
        
        th {
            background-color: #34495e;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .risk-high { background-color: #ffebee; }
        .risk-medium { background-color: #fff3e0; }
        .risk-low { background-color: #e8f5e8; }
        
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #7f8c8d;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .risk-rating {
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 9px;
            text-transform: uppercase;
        }
        
        .rating-high { background-color: #e74c3c; color: white; }
        .rating-medium { background-color: #f39c12; color: white; }
        .rating-low { background-color: #27ae60; color: white; }
        .rating-critical { background-color: #8e44ad; color: white; }
    </style>
</head>
<body>
    <div class="header">
        <h1>DCS Risk Assessments Report</h1>
        <div class="subtitle">Generated on {{ $exported_at }} by {{ $exported_by }}</div>
        <div class="subtitle" style="margin-top: 5px; font-size: 12px;">
            DCS Risk Register System | No 41, Johann and Sturrock, Windhoek, Namibia
        </div>
    </div>
    
    <div class="summary">
        <h3>Report Summary</h3>
        <div class="summary-stats">
            <div class="stat-item">
                <div class="stat-number">{{ $total_risks }}</div>
                <div class="stat-label">Total Assessments</div>
            </div>
            <div class="stat-item">
                <div class="stat-number high-risk">{{ $high_risk_count }}</div>
                <div class="stat-label">High Risk</div>
            </div>
            <div class="stat-item">
                <div class="stat-number medium-risk">{{ $medium_risk_count }}</div>
                <div class="stat-label">Medium Risk</div>
            </div>
            <div class="stat-item">
                <div class="stat-number low-risk">{{ $low_risk_count }}</div>
                <div class="stat-label">Low Risk</div>
            </div>
        </div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th style="width: 8%;">Risk ID</th>
                <th style="width: 12%;">Client</th>
                <th style="width: 10%;">Company</th>
                <th style="width: 15%;">Risk Title</th>
                <th style="width: 10%;">Category</th>
                <th style="width: 6%;">Rating</th>
                <th style="width: 5%;">Impact</th>
                <th style="width: 6%;">Likelihood</th>
                <th style="width: 5%;">Points</th>
                <th style="width: 6%;">Status</th>
                <th style="width: 8%;">Owner</th>
                <th style="width: 6%;">Created</th>
                <th style="width: 6%;">Due Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($risks as $risk)
            <tr class="risk-{{ strtolower($risk->risk_rating ?? 'unknown') }}">
                <td>#{{ $risk->id ?? 'N/A' }}</td>
                <td>{{ $risk->client ? $risk->client->name : 'N/A' }}</td>
                <td>{{ $risk->client ? ($risk->client->company ?? 'Individual') : 'N/A' }}</td>
                <td>{{ $risk->title ?? 'Untitled Risk' }}</td>
                <td>{{ $risk->risk_category ?? 'N/A' }}</td>
                <td>
                    <span class="risk-rating rating-{{ strtolower($risk->risk_rating ?? 'unknown') }}">
                        {{ $risk->risk_rating ?? 'N/A' }}
                    </span>
                </td>
                <td>{{ $risk->impact ?? 'N/A' }}</td>
                <td>{{ $risk->likelihood ?? 'N/A' }}</td>
                <td>{{ $risk->overall_risk_points ?? 'N/A' }}</td>
                <td>{{ $risk->status ?? 'In Progress' }}</td>
                <td>{{ $risk->assignedUser ? $risk->assignedUser->name : 'Unassigned' }}</td>
                <td>{{ $risk->created_at ? $risk->created_at->format('Y-m-d') : 'N/A' }}</td>
                <td>{{ $risk->due_date ? $risk->due_date->format('Y-m-d') : 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        <p><strong>DCS Risk Register System - Professional Risk Management Platform</strong></p>
        <p>This report was generated on {{ $exported_at }} by {{ $exported_by }}</p>
        <p>Report includes {{ $total_risks }} risk assessment(s) with comprehensive analysis and compliance data</p>
        <p style="margin-top: 15px; padding-top: 10px; border-top: 1px solid #ccc;">
            <strong>Contact Information:</strong><br>
            Email: ITSupport@dcs.com.na | info@dcs.com.na<br>
            Phone: +264 82 403 2391<br>
            Address: No 41, Johann and Sturrock, Windhoek, Namibia<br>
            Website: www.dcs.com.na
        </p>
        <p style="margin-top: 10px; font-size: 9px; color: #999;">
            This report is confidential and intended for internal use only. 
            Distribution outside of DCS requires prior authorization.
        </p>
    </div>
</body>
</html>
