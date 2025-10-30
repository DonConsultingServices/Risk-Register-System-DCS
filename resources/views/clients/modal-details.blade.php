<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Details - {{ $client->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .client-details-container {
            padding: 20px;
            max-width: 100%;
        }
        .client-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .detail-section {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .detail-section h5 {
            color: #495057;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #f8f9fa;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #6c757d;
            flex: 0 0 40%;
        }
        .detail-value {
            color: #495057;
            flex: 1;
        }
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 500;
        }
        .status-active {
            background-color: #d4edda;
            color: #155724;
        }
        .status-inactive {
            background-color: #f8d7da;
            color: #721c24;
        }
        .risk-low {
            background-color: #d4edda;
            color: #155724;
        }
        .risk-medium {
            background-color: #fff3cd;
            color: #856404;
        }
        .risk-high {
            background-color: #f8d7da;
            color: #721c24;
        }
        .document-link {
            color: #007bff;
            text-decoration: none;
        }
        .document-link:hover {
            text-decoration: underline;
        }
        .no-data {
            color: #6c757d;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="client-details-container">
        <!-- Client Header -->
        <div class="client-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">{{ $client->name }}</h3>
                    <p class="mb-0 opacity-75">Client ID: {{ $client->id }}</p>
                </div>
                <div class="text-end">
                    <span class="status-badge {{ $client->is_active ? 'status-active' : 'status-inactive' }}">
                        {{ $client->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- KYC Details -->
        <div class="detail-section">
            <h5><i class="fas fa-user-check me-2"></i>KYC DETAILS</h5>
            <div class="detail-row">
                <span class="detail-label">Client Type:</span>
                <span class="detail-value">{{ $client->client_type ?? 'N/A' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Nationality:</span>
                <span class="detail-value">{{ $client->nationality ?? 'N/A' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Is Minor:</span>
                <span class="detail-value">{{ $client->is_minor ? 'Yes' : 'No' }}</span>
            </div>
            @if($client->nationality === 'Namibian' && !$client->is_minor)
            <div class="detail-row">
                <span class="detail-label">Namibian ID Number:</span>
                <span class="detail-value">{{ $client->id_number ?? 'N/A' }}</span>
            </div>
            @endif
            @if($client->nationality === 'Foreign')
            <div class="detail-row">
                <span class="detail-label">Passport Number:</span>
                <span class="detail-value">{{ $client->passport_number ?? 'N/A' }}</span>
            </div>
            @endif
            @if($client->client_type === 'Juristic')
            <div class="detail-row">
                <span class="detail-label">Company Nationality:</span>
                <span class="detail-value">{{ $client->company_nationality ?? 'N/A' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Registration Number:</span>
                <span class="detail-value">{{ $client->registration_number ?? 'N/A' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Entity Type:</span>
                <span class="detail-value">{{ $client->entity_type ?? 'N/A' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Trading Address:</span>
                <span class="detail-value">{{ $client->trading_address ?? 'N/A' }}</span>
            </div>
            @endif
            <div class="detail-row">
                <span class="detail-label">Source of Income:</span>
                <span class="detail-value">{{ $client->income_source ?? 'N/A' }}</span>
            </div>
        </div>

        <!-- Supporting Documents -->
        <div class="detail-section">
            <h5><i class="fas fa-file-alt me-2"></i>SUPPORTING DOCUMENTS</h5>
            @php
                $documents = $client->documents ?? collect();
                
                // Base document types
                $documentTypes = [
                    'id_document' => 'ID Document',
                    'birth_certificate' => 'Birth Certificate',
                    'passport_document' => 'Passport Document',
                    'proof_of_residence' => $client->client_type === 'Juristic' ? 'Trading Address Residence' : 'Proof of Residence',
                    'kyc_form' => 'KYC Form',
                    'source_of_earnings' => 'Source of Earnings'
                ];
                
                // Add juristic-specific documents if client is Juristic
                if ($client->client_type === 'Juristic') {
                    $documentTypes['registration_document'] = 'Company Registration Certificate';
                    $documentTypes['tax_certificate'] = 'Tax Certificate (ITAS)';
                    
                    // Add foreign registration if company is foreign
                    if (($client->company_nationality ?? '') === 'Foreign') {
                        $documentTypes['foreign_registration'] = 'Foreign Registration Certificate';
                    }
                }
            @endphp
            
            @foreach($documentTypes as $type => $label)
                @php
                    $document = $documents->where('document_type', $type)->first();
                    $legacyPath = null;
                    
                    // Check for legacy document paths
                    switch($type) {
                        case 'id_document':
                            $legacyPath = $client->id_document_path ?? null;
                            break;
                        case 'birth_certificate':
                            $legacyPath = $client->birth_certificate_path ?? null;
                            break;
                        case 'passport_document':
                            $legacyPath = $client->passport_document_path ?? null;
                            break;
                        case 'proof_of_residence':
                            $legacyPath = $client->proof_of_residence_path ?? null;
                            break;
                        case 'kyc_form':
                            $legacyPath = $client->kyc_form_path ?? null;
                            break;
                        case 'source_of_earnings':
                            // Source of earnings is only stored in client_documents table, not risks table
                            $legacyPath = null;
                            break;
                        case 'registration_document':
                            $legacyPath = $client->registration_document_path ?? null;
                            break;
                        case 'foreign_registration':
                            $legacyPath = $client->foreign_registration_path ?? null;
                            break;
                        case 'tax_certificate':
                            $legacyPath = $client->tax_certificate_path ?? null;
                            break;
                    }
                    
                    $filePath = $document->file_path ?? $legacyPath;
                @endphp
                <div class="detail-row">
                    <span class="detail-label">{{ $label }}:</span>
                    <span class="detail-value">
                        @if($filePath)
                            <a href="{{ Storage::disk('public')->url($filePath) }}" 
                               target="_blank" class="document-link">
                                <i class="fas fa-download me-1"></i>Download
                            </a>
                        @else
                            <span class="no-data">N/A</span>
                        @endif
                    </span>
                </div>
            @endforeach
        </div>

        <!-- Basic Information -->
        <div class="detail-section">
            <h5><i class="fas fa-info-circle me-2"></i>BASIC INFORMATION</h5>
            <div class="detail-row">
                <span class="detail-label">Company:</span>
                <span class="detail-value">{{ $client->company ?? 'N/A' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Email:</span>
                <span class="detail-value">{{ $client->email ?? 'N/A' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Phone:</span>
                <span class="detail-value">{{ $client->phone ?? 'N/A' }}</span>
            </div>
        </div>

        <!-- Status & Risk -->
        <div class="detail-section">
            <h5><i class="fas fa-shield-alt me-2"></i>STATUS & RISK</h5>
            <div class="detail-row">
                <span class="detail-label">Status:</span>
                <span class="detail-value">
                    <span class="status-badge {{ $client->is_active ? 'status-active' : 'status-inactive' }}">
                        {{ $client->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Risk Level:</span>
                <span class="detail-value">
                    @php
                        $riskLevel = $client->risk_level ?? 'Low';
                        $riskClass = 'risk-' . strtolower($riskLevel);
                    @endphp
                    <span class="status-badge {{ $riskClass }}">{{ $riskLevel }}</span>
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Screening Status:</span>
                <span class="detail-value">
                    @php($dateValue = $client->screening_date ?? $client->client_screening_date)
                    @if(!empty($dateValue))
                        {{ method_exists($dateValue, 'format') ? $dateValue->format('M d, Y') : (\Carbon\Carbon::parse($dateValue)->format('M d, Y')) }}
                        @if(method_exists($client, 'getDaysSinceScreening'))
                            <small class="text-muted">({{ $client->getDaysSinceScreening() }} days ago)</small>
                        @endif
                    @else
                        <span class="no-data">Not screened</span>
                    @endif
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Screening Result:</span>
                <span class="detail-value">{{ $client->client_screening_result ?? 'N/A' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">DCS Risk Appetite:</span>
                <span class="detail-value">{{ $client->dcs_risk_appetite ?? 'N/A' }}</span>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
