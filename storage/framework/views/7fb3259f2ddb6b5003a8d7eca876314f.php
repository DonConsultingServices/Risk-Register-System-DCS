<?php $__env->startSection('title', 'Client Details'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .assessment-history-timeline {
        position: relative;
    }
    
    .assessment-history-item {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
        position: relative;
    }
    
    .assessment-history-item:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }
    
    .assessment-history-item:last-child {
        margin-bottom: 0;
    }
    
    .assessment-number {
        text-align: center;
    }
    
    .assessment-date {
        text-align: left;
    }
    
    .assessment-rating {
        text-align: center;
    }
    
    .assessment-acceptance,
    .assessment-monitoring {
        text-align: center;
    }
    
    .assessment-history-item::before {
        content: '';
        position: absolute;
        left: -10px;
        top: 50%;
        transform: translateY(-50%);
        width: 4px;
        height: 60%;
        background: var(--logo-dark-blue-primary);
        border-radius: 2px;
    }
    
    .assessment-history-item:first-child::before {
        background: var(--logo-green);
    }
    
    /* Mobile-First Responsive Design */
    @media (max-width: 768px) {
        .container-fluid {
            padding: 0.5rem;
        }
        
        .page-title-box {
            padding: 1rem 0;
        }
        
        .page-title {
            font-size: 1.25rem;
        }
        
        .breadcrumb {
            font-size: 0.8rem;
            margin-bottom: 0.5rem;
        }
        
        .card {
            margin-bottom: 1rem;
        }
        
        .card-header {
            padding: 0.75rem 1rem;
        }
        
        .card-body {
            padding: 1rem;
        }
        
        .header-title {
            font-size: 1.1rem;
        }
        
        .row {
            margin: 0;
        }
        
        .col-lg-8, .col-lg-4 {
            padding: 0.25rem;
            margin-bottom: 1rem;
        }
        
        .table {
            font-size: 0.8rem;
        }
        
        .table th, .table td {
            padding: 0.5rem 0.25rem;
            font-size: 0.75rem;
        }
        
        .btn {
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
        }
        
        .btn-group {
            flex-direction: column;
            gap: 0.25rem;
        }
        
        .btn-group .btn {
            width: 100%;
            margin-bottom: 0.25rem;
        }
        
        .assessment-history-timeline {
            margin-top: 1rem;
        }
        
        .assessment-history-item {
            padding: 0.75rem;
            margin-bottom: 0.75rem;
        }
        
        .assessment-history-item::before {
            left: -8px;
            width: 3px;
        }
        
        .assessment-number,
        .assessment-date,
        .assessment-rating,
        .assessment-acceptance,
        .assessment-monitoring {
            text-align: left;
            margin-bottom: 0.5rem;
        }
        
        .dropdown-menu {
            font-size: 0.8rem;
        }
    }
    
    @media (max-width: 576px) {
        .container-fluid {
            padding: 0.25rem;
        }
        
        .page-title {
            font-size: 1.1rem;
        }
        
        .breadcrumb {
            font-size: 0.75rem;
        }
        
        .card-header {
            padding: 0.5rem 0.75rem;
        }
        
        .card-body {
            padding: 0.75rem;
        }
        
        .header-title {
            font-size: 1rem;
        }
        
        .col-lg-8, .col-lg-4 {
            padding: 0.125rem;
            margin-bottom: 0.75rem;
        }
        
        .table {
            font-size: 0.7rem;
        }
        
        .table th, .table td {
            padding: 0.375rem 0.125rem;
            font-size: 0.65rem;
        }
        
        .btn {
            padding: 0.4rem 0.75rem;
            font-size: 0.75rem;
        }
        
        .assessment-history-item {
            padding: 0.5rem;
            margin-bottom: 0.5rem;
        }
        
        .assessment-history-item::before {
            left: -6px;
            width: 2px;
        }
    }
    
    @media (max-width: 480px) {
        .page-title {
            font-size: 1rem;
        }
        
        .breadcrumb {
            font-size: 0.7rem;
        }
        
        .card-header {
            padding: 0.4rem 0.5rem;
        }
        
        .card-body {
            padding: 0.5rem;
        }
        
        .header-title {
            font-size: 0.9rem;
        }
        
        .table {
            font-size: 0.65rem;
        }
        
        .table th, .table td {
            padding: 0.25rem 0.1rem;
            font-size: 0.6rem;
        }
        
        .btn {
            padding: 0.3rem 0.5rem;
            font-size: 0.7rem;
        }
        
        .assessment-history-item {
            padding: 0.4rem;
            margin-bottom: 0.4rem;
        }
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('clients.index')); ?>">Clients</a></li>
                        <li class="breadcrumb-item active">Details</li>
                    </ol>
                </div>
                <h4 class="page-title" style="color: #000000 !important;">Client Details</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="header-title"><?php echo e($client->name); ?></h4>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                Actions
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?php echo e(route('clients.edit', $client)); ?>" 
                                       onclick="return confirmEdit('client')">
                                    <i class="mdi mdi-pencil me-2"></i>Edit
                                </a></li>
                                <li><a class="dropdown-item" href="<?php echo e(route('clients.risk-analysis', $client)); ?>">
                                    <i class="mdi mdi-chart-line me-2"></i>Risk Analysis
                                </a></li>
                                <li><a class="dropdown-item" href="<?php echo e(route('clients.export', $client)); ?>">
                                    <i class="mdi mdi-download me-2"></i>Export
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="<?php echo e(route('clients.destroy', $client)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="dropdown-item text-danger" 
                                                onclick="return confirmDelete('client')">
                                            <i class="mdi mdi-delete me-2"></i>Delete
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- KYC & Documents Overview (mirrors approval view) -->
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase mb-3">KYC Details</h6>
                            <div class="mb-2"><strong>Client Type:</strong> <?php echo e($client->client_type ?? 'N/A'); ?></div>
                            
                            <?php if($client->client_type === 'Individual'): ?>
                                <!-- Individual-specific fields -->
                                <div class="mb-2"><strong>Gender:</strong> <?php echo e($client->gender ?? 'N/A'); ?></div>
                                <div class="mb-2"><strong>Nationality:</strong> <?php echo e($client->nationality ?? 'N/A'); ?></div>
                                <div class="mb-2"><strong>Is Minor:</strong> <?php echo e(isset($client->is_minor) ? ($client->is_minor ? 'Yes' : 'No') : 'N/A'); ?></div>
                                
                                <?php if($client->nationality === 'Namibian' && !$client->is_minor): ?>
                                    <div class="mb-2"><strong>Namibian ID Number:</strong> <?php echo e($client->id_number ?? 'N/A'); ?></div>
                                <?php endif; ?>
                                
                                <?php if($client->nationality === 'Foreign' && !$client->is_minor): ?>
                                    <div class="mb-2"><strong>Passport Number:</strong> <?php echo e($client->passport_number ?? 'N/A'); ?></div>
                                <?php endif; ?>
                                
                                <div class="mb-2"><strong>Source of Income:</strong> <?php echo e($client->income_source ?? 'N/A'); ?></div>
                            <?php elseif($client->client_type === 'Juristic'): ?>
                                <!-- Juristic-specific fields -->
                                <div class="mb-2"><strong>Company Nationality:</strong> <?php echo e($client->company_nationality ?? 'N/A'); ?></div>
                                <div class="mb-2"><strong>Registration Number:</strong> <?php echo e($client->registration_number ?? 'N/A'); ?></div>
                                <div class="mb-2"><strong>Entity Type:</strong> <?php echo e($client->entity_type ?? 'N/A'); ?></div>
                                <div class="mb-2"><strong>Trading Address:</strong> <?php echo e($client->trading_address ?? 'N/A'); ?></div>
                                <div class="mb-2"><strong>Source of Income:</strong> <?php echo e($client->income_source ?? 'N/A'); ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase mb-3">Supporting Documents</h6>
                            <?php (
                                $doc = function($type) use ($client) {
                                    return optional(optional($client->documents)->firstWhere('document_type', $type))->file_path;
                                }
                            ); ?>
                            
                            <?php if($client->client_type === 'Individual'): ?>
                                <!-- Individual-specific documents -->
                                <?php if($client->nationality === 'Namibian' && !$client->is_minor): ?>
                                    <div class="mb-2"><strong>ID Document:</strong>
                                        <?php ($p = $doc('id_document') ?? $client->id_document_path ?? null); ?>
                                        <?php if($p): ?>
                                            <a href="<?php echo e(Storage::disk('public')->url($p)); ?>" target="_blank">View</a>
                                        <?php else: ?> N/A <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if($client->is_minor): ?>
                                    <div class="mb-2"><strong>Birth Certificate:</strong>
                                        <?php ($p = $doc('birth_certificate') ?? $client->birth_certificate_path ?? null); ?>
                                        <?php if($p): ?>
                                            <a href="<?php echo e(Storage::disk('public')->url($p)); ?>" target="_blank">View</a>
                                        <?php else: ?> N/A <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if($client->nationality === 'Foreign' && !$client->is_minor): ?>
                                    <div class="mb-2"><strong>Passport Document:</strong>
                                        <?php ($p = $doc('passport_document') ?? $client->passport_document_path ?? null); ?>
                                        <?php if($p): ?>
                                            <a href="<?php echo e(Storage::disk('public')->url($p)); ?>" target="_blank">View</a>
                                        <?php else: ?> N/A <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                            
                            <!-- Common documents for both types -->
                            <div class="mb-2"><strong><?php echo e(($client->client_type ?? '') === 'Juristic' ? 'Trading Address Residence' : 'Proof of Residence'); ?>:</strong>
                                <?php ($p = $doc('proof_of_residence') ?? $client->proof_of_residence_path ?? null); ?>
                                <?php if($p): ?>
                                    <a href="<?php echo e(Storage::disk('public')->url($p)); ?>" target="_blank">View</a>
                                <?php else: ?> N/A <?php endif; ?>
                            </div>
                            <div class="mb-2"><strong>KYC Form:</strong>
                                <?php ($p = $doc('kyc_form') ?? $client->kyc_form_path ?? null); ?>
                                <?php if($p): ?>
                                    <a href="<?php echo e(Storage::disk('public')->url($p)); ?>" target="_blank">View</a>
                                <?php else: ?> N/A <?php endif; ?>
                            </div>
                            <div class="mb-2"><strong>Source of Earnings:</strong>
                                <?php ($p = $doc('source_of_earnings') ?? null); ?>
                                <?php if($p): ?>
                                    <a href="<?php echo e(Storage::disk('public')->url($p)); ?>" target="_blank">View</a>
                                <?php else: ?> N/A <?php endif; ?>
                            </div>
                            
                            <?php if($client->client_type === 'Juristic'): ?>
                                <!-- Juristic-specific documents -->
                                <div class="mb-2"><strong>Company Registration Certificate:</strong>
                                    <?php ($p = $doc('registration_document') ?? $client->registration_document_path ?? null); ?>
                                    <?php if($p): ?>
                                        <a href="<?php echo e(Storage::disk('public')->url($p)); ?>" target="_blank">View</a>
                                    <?php else: ?> N/A <?php endif; ?>
                                </div>
                                <div class="mb-2"><strong>Tax Certificate (ITAS):</strong>
                                    <?php ($p = $doc('tax_certificate') ?? $client->tax_certificate_path ?? null); ?>
                                    <?php if($p): ?>
                                        <a href="<?php echo e(Storage::disk('public')->url($p)); ?>" target="_blank">View</a>
                                    <?php else: ?> N/A <?php endif; ?>
                                </div>
                                
                                <?php if(($client->company_nationality ?? '') === 'Foreign'): ?>
                                    <div class="mb-2"><strong>Foreign Registration Certificate:</strong>
                                        <?php ($p = $doc('foreign_registration') ?? $client->foreign_registration_path ?? null); ?>
                                        <?php if($p): ?>
                                            <a href="<?php echo e(Storage::disk('public')->url($p)); ?>" target="_blank">View</a>
                                        <?php else: ?> N/A <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase mb-3">Basic Information</h6>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Company</label>
                                <p class="mb-0"><?php echo e($client->company ?? 'N/A'); ?></p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Email</label>
                                <p class="mb-0">
                                    <a href="mailto:<?php echo e($client->email); ?>" class="text-decoration-none"><?php echo e($client->email); ?></a>
                                </p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Phone</label>
                                <p class="mb-0"><?php echo e($client->phone ?? 'N/A'); ?></p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Industry</label>
                                <p class="mb-0"><?php echo e($client->industry ?? 'N/A'); ?></p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase mb-3">Status & Risk</h6>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Status</label>
                                <span class="badge bg-<?php echo e($client->status_color); ?>"><?php echo e($client->status); ?></span>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Risk Level</label>
                                <span class="badge bg-<?php echo e($client->risk_level_color); ?>"><?php echo e($client->risk_level); ?></span>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Screening Status</label>
                                <p class="mb-0"><?php echo e($client->client_screening_result ?? 'N/A'); ?></p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Screening Date</label>
                                <p class="mb-0">
                                    <?php ($dateValue = $client->screening_date ?? $client->client_screening_date); ?>
                                    <?php if(!empty($dateValue)): ?>
                                        <?php echo e(method_exists($dateValue, 'format') ? $dateValue->format('M d, Y') : (\Carbon\Carbon::parse($dateValue)->format('M d, Y'))); ?>

                                        <?php if(method_exists($client, 'getDaysSinceScreening')): ?>
                                            <small class="text-muted">(<?php echo e($client->getDaysSinceScreening()); ?> days ago)</small>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted">Not screened</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">DCS Risk Appetite</label>
                                <p class="mb-0"><?php echo e($client->dcs_risk_appetite ?? 'N/A'); ?></p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Approved/Rejected By</label>
                                <p class="mb-0"><?php echo e(optional($client->approver)->name ?? 'N/A'); ?></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Decision Date</label>
                                <p class="mb-0"><?php echo e($client->approved_at ? (method_exists($client->approved_at, 'format') ? $client->approved_at->format('M d, Y H:i') : (\Carbon\Carbon::parse($client->approved_at)->format('M d, Y H:i'))) : 'N/A'); ?></p>
                            </div>
                            <?php if(!empty($client->approval_notes)): ?>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Approval Notes</label>
                                <p class="mb-0"><?php echo e($client->approval_notes); ?></p>
                            </div>
                            <?php endif; ?>
                            <?php if($client->assessment_status === 'rejected' && !empty($client->rejection_reason)): ?>
                            <div class="mb-3">
                                <label class="form-label fw-bold text-danger">Rejection Reason</label>
                                <p class="mb-0 text-danger"><?php echo e($client->rejection_reason); ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <?php if($client->notes): ?>
                    <div class="mt-4">
                        <h6 class="text-muted text-uppercase mb-3">Notes</h6>
                        <p class="mb-0"><?php echo e($client->notes); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Assessment History Section -->
            <?php if(isset($assessmentHistory) && $assessmentHistory->count() > 0): ?>
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="header-title">
                        <i class="fas fa-history me-2"></i>
                        Assessment History for <?php echo e($client->name); ?>

                    </h5>
                </div>
                <div class="card-body">
                    <div class="assessment-history-timeline">
                        <?php $__currentLoopData = $assessmentHistory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $historyAssessment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="assessment-history-item">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <div class="assessment-number">
                                        <span class="badge bg-secondary">#<?php echo e($index + 2); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="assessment-date">
                                        <strong><?php echo e($historyAssessment->created_at->format('M d, Y')); ?></strong>
                                        <br>
                                        <small class="text-muted"><?php echo e($historyAssessment->created_at->format('H:i')); ?></small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="assessment-rating">
                                        <span class="badge bg-<?php echo e($historyAssessment->overall_risk_rating == 'High' ? 'danger' : ($historyAssessment->overall_risk_rating == 'Medium' ? 'warning' : 'success')); ?>">
                                            <?php echo e($historyAssessment->overall_risk_rating); ?>

                                        </span>
                                        <br>
                                        <small class="text-muted"><?php echo e($historyAssessment->overall_risk_points); ?> points</small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="assessment-acceptance">
                                        <small><?php echo e($historyAssessment->client_acceptance ?? 'N/A'); ?></small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="assessment-monitoring">
                                        <small><?php echo e($historyAssessment->ongoing_monitoring ?? 'N/A'); ?></small>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <a href="<?php echo e(route('clients.show', $historyAssessment)); ?>" class="btn btn-sm btn-outline-primary" title="View Assessment">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- New Assessment History Component -->
            <?php if(isset($newAssessmentHistory) && $newAssessmentHistory->count() > 0): ?>
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="header-title">
                        <i class="fas fa-chart-line me-2"></i>
                        Complete Assessment History
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (isset($component)) { $__componentOriginal322e94999d678519356ba2ad2f91afa8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal322e94999d678519356ba2ad2f91afa8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.assessment-history','data' => ['assessments' => $newAssessmentHistory,'showCurrent' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('assessment-history'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['assessments' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($newAssessmentHistory),'show-current' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal322e94999d678519356ba2ad2f91afa8)): ?>
<?php $attributes = $__attributesOriginal322e94999d678519356ba2ad2f91afa8; ?>
<?php unset($__attributesOriginal322e94999d678519356ba2ad2f91afa8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal322e94999d678519356ba2ad2f91afa8)): ?>
<?php $component = $__componentOriginal322e94999d678519356ba2ad2f91afa8; ?>
<?php unset($__componentOriginal322e94999d678519356ba2ad2f91afa8); ?>
<?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="header-title">Risk Summary</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Total Risks</label>
                        <div class="d-flex align-items-center">
                            <span class="h4 mb-0 me-2"><?php echo e($client->total_risks); ?></span>
                            <a href="<?php echo e(route('clients.risk-analysis', $client)); ?>" class="btn btn-sm btn-outline-primary">View All</a>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Risk Distribution</label>
                        <div class="d-flex justify-content-between mb-1">
                            <span>High</span>
                            <span class="badge bg-danger"><?php echo e($client->high_risks); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>Medium</span>
                            <span class="badge bg-warning"><?php echo e($client->medium_risks); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>Low</span>
                            <span class="badge bg-success"><?php echo e($client->low_risks); ?></span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Open Risks</label>
                        <p class="mb-0"><?php echo e($client->open_risks); ?> out of <?php echo e($client->total_risks); ?></p>
                    </div>
                    
                    <hr>
                    
                    <div class="d-grid gap-2">
                        <a href="<?php echo e(route('risk-categories.index')); ?>" class="btn btn-primary">
                            <i class="mdi mdi-folder me-1"></i>Add New Risk
                        </a>
                        <a href="<?php echo e(route('risks.index')); ?>" class="btn btn-success">
                            <i class="mdi mdi-clipboard-check me-1"></i>Add Client Assessment
                        </a>
                        <a href="<?php echo e(route('clients.risk-analysis', $client)); ?>" class="btn btn-outline-info">
                            <i class="mdi mdi-chart-line me-1"></i>Risk Analysis
                        </a>
                    </div>
                </div>
            </div>
            
            <?php if($client->isHighRisk()): ?>
            <div class="card border-warning mt-3">
                <div class="card-body text-center">
                    <i class="mdi mdi-alert-triangle text-warning" style="font-size: 2rem;"></i>
                    <h6 class="mt-2 mb-1">High-Risk Client</h6>
                    <p class="text-muted mb-0">This client requires special attention</p>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Recent Risks -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="header-title">Recent Risks</h5>
                    <a href="<?php echo e(route('clients.risk-analysis', $client)); ?>" class="btn btn-sm btn-outline-primary">View All Risks</a>
                </div>
                <div class="card-body">
                    <?php if($client->risks->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-centered table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Risk</th>
                                        <th>Risk Level</th>
                                        <th>Status</th>
                                        <th>Due Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $client->risks->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $risk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <div>
                                                <h6 class="mb-0"><?php echo e($risk->title); ?></h6>
                                                <small class="text-muted"><?php echo e(Str::limit($risk->description, 50)); ?></small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php echo e($risk->risk_rating_color); ?>"><?php echo e($risk->risk_level); ?></span>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php echo e($risk->status_color); ?>"><?php echo e($risk->status); ?></span>
                                        </td>
                                        <td>
                                            <?php if($risk->due_date): ?>
                                                <?php echo e($risk->due_date->format('M d, Y')); ?>

                                                <?php if($risk->isOverdue()): ?>
                                                    <span class="badge bg-danger ms-1">Overdue</span>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="text-muted">No due date</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?php echo e(route('risks.show', $risk)); ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="mdi mdi-shield-check text-success" style="font-size: 3rem;"></i>
                            <h5 class="mt-3 text-success">No Risks Found</h5>
                            <p class="text-muted">This client has no associated risks yet.</p>
                            <a href="<?php echo e(route('client-risk-assessment.index')); ?>?client_id=<?php echo e($client->id); ?>" class="btn btn-primary">
                                <i class="mdi mdi-plus me-1"></i>Add First Risk
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Client Timeline -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="header-title">Client Timeline</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Client Created</h6>
                                <p class="timeline-text"><?php echo e($client->created_at->format('M d, Y H:i')); ?></p>
                                <small class="text-muted">by <?php echo e($client->creator->name ?? 'System'); ?></small>
                            </div>
                        </div>
                        
                        <?php if($client->screening_date): ?>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Initial Screening</h6>
                                <p class="timeline-text"><?php echo e($client->screening_date->format('M d, Y')); ?></p>
                                <small class="text-muted">Status: <?php echo e($client->client_screening_result); ?></small>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if($client->updated_at != $client->created_at): ?>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Client Updated</h6>
                                <p class="timeline-text"><?php echo e($client->updated_at->format('M d, Y H:i')); ?></p>
                                <small class="text-muted">by <?php echo e($client->updater->name ?? 'System'); ?></small>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if($client->risks->count() > 0): ?>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">First Risk Added</h6>
                                <p class="timeline-text"><?php echo e($client->risks->first()->created_at->format('M d, Y')); ?></p>
                                <small class="text-muted"><?php echo e($client->risks->count()); ?> total risks</small>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
// Confirmation functions for edit and delete actions
function confirmEdit(type) {
    const messages = {
        'client': 'Are you sure you want to edit this client? Any changes will be saved immediately.',
        'risk': 'Are you sure you want to edit this risk assessment? Any changes will be saved immediately.',
        'category': 'Are you sure you want to edit this risk category? Any changes will be saved immediately.'
    };
    return confirm(messages[type] || 'Are you sure you want to proceed with editing?');
}

function confirmDelete(type, count = 1) {
    const messages = {
        'client': `Are you sure you want to delete ${count > 1 ? 'these clients' : 'this client'}? This will also delete all associated risks and cannot be undone.`,
        'risk': `Are you sure you want to delete ${count > 1 ? 'these risks' : 'this risk'}? This action cannot be undone.`,
        'category': `Are you sure you want to delete ${count > 1 ? 'these categories' : 'this category'}? This will also affect all associated risks and cannot be undone.`
    };
    return confirm(messages[type] || `Are you sure you want to delete ${count > 1 ? 'these items' : 'this item'}?`);
}
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
}

.timeline-marker {
    position: absolute;
    left: -45px;
    top: 0;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 3px solid #fff;
    box-shadow: 0 0 0 3px #e9ecef;
}

.timeline-content {
    padding-left: 20px;
}

.timeline-title {
    margin-bottom: 5px;
    font-weight: 600;
}

.timeline-text {
    margin-bottom: 5px;
    color: #6c757d;
}
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\well-known\resources\views/clients/show.blade.php ENDPATH**/ ?>