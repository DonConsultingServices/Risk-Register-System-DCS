<?php $__env->startSection('title', 'Risk Management Settings'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('risks.index')); ?>">Risks</a></li>
                        <li class="breadcrumb-item active">Settings</li>
                    </ol>
                </div>
                <h4 class="page-title" style="color: #000000 !important;">Risk Management Settings</h4>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>


    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>


    <?php if($errors->any()): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <ul class="mb-0">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Page Overview -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-light border shadow-sm">
                <div class="d-flex align-items-center">
                    <i class="fas fa-info-circle fa-2x me-3" style="color: #00072D;"></i>
                    <div>
                        <h6 class="mb-1"><strong>Settings Overview</strong></h6>
                        <p class="mb-0 text-muted">Configure system settings, create backups, and access the complete user guide for all features.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- System Backup Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-danger shadow-sm">
                <div class="card-header" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: white;">
                    <h5 class="mb-0"><i class="fas fa-database me-2"></i>System Data Backup (Critical)</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning mb-3">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Important:</strong> Regular backups are essential for data protection and disaster recovery. Recommended: Database backup weekly, Full backup monthly.
                    </div>
                    <div class="d-flex gap-3 flex-wrap">
                        <form action="<?php echo e(route('system.backup.database')); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-success btn-lg" onclick="return confirm('Create a full database backup? This may take a few moments.')">
                                <i class="fas fa-download me-2"></i>Download Database Backup
                            </button>
                        </form>
                        <form action="<?php echo e(route('system.backup.full')); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-info btn-lg" onclick="return confirm('Create a complete backup including all files and documents?')">
                                <i class="fas fa-archive me-2"></i>Download Full System Backup
                            </button>
                        </form>
                    </div>
                    <hr class="my-3">
                    <div class="row text-muted small">
                        <div class="col-md-6">
                            <i class="fas fa-check-circle text-success me-1"></i><strong>Database Backup:</strong> All data tables only (~few MB)
                        </div>
                        <div class="col-md-6">
                            <i class="fas fa-check-circle text-info me-1"></i><strong>Full Backup:</strong> Database + all uploaded documents (~larger file)
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- System Configuration Settings -->
    <div class="row mb-4">
        <!-- General Settings -->
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-cog me-2"></i>General Settings</h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('risks.settings.update')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        
                        <div class="mb-3">
                            <label for="risk_assessment_frequency" class="form-label fw-bold">Risk Assessment Frequency</label>
                            <select class="form-select" id="risk_assessment_frequency" name="risk_assessment_frequency">
                                <?php $frequency = old('risk_assessment_frequency', $settings['risk_assessment_frequency'] ?? 'monthly'); ?>
                                <option value="weekly" <?php echo e($frequency == 'weekly' ? 'selected' : ''); ?>>Weekly</option>
                                <option value="monthly" <?php echo e($frequency == 'monthly' ? 'selected' : ''); ?>>Monthly</option>
                                <option value="quarterly" <?php echo e($frequency == 'quarterly' ? 'selected' : ''); ?>>Quarterly</option>
                                <option value="annually" <?php echo e($frequency == 'annually' ? 'selected' : ''); ?>>Annually</option>
                            </select>
                            <small class="form-text text-muted">How often should risk assessments be conducted?</small>
                        </div>

                        <div class="mb-3">
                            <label for="auto_risk_scoring" class="form-label fw-bold">Automatic Risk Scoring</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="auto_risk_scoring" name="auto_risk_scoring" value="1" 
                                       <?php echo e(old('auto_risk_scoring', $settings['auto_risk_scoring'] ?? false) ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="auto_risk_scoring">
                                    Enable automatic risk score calculation
                                </label>
                            </div>
                            <small class="form-text text-muted">Automatically calculate risk scores based on impact and likelihood</small>
                        </div>

                        <div class="mb-3">
                            <label for="risk_threshold_high" class="form-label fw-bold">High Risk Threshold</label>
                            <input type="number" class="form-control" id="risk_threshold_high" name="risk_threshold_high" 
                                   value="<?php echo e(old('risk_threshold_high', $settings['risk_threshold_high'] ?? 15)); ?>" min="1" max="25">
                            <small class="form-text text-muted">Risk score above which a risk is considered high (1-25)</small>
                        </div>

                        <div class="mb-3">
                            <label for="risk_threshold_critical" class="form-label fw-bold">Critical Risk Threshold</label>
                            <input type="number" class="form-control" id="risk_threshold_critical" name="risk_threshold_critical" 
                                   value="<?php echo e(old('risk_threshold_critical', $settings['risk_threshold_critical'] ?? 20)); ?>" min="1" max="25">
                            <small class="form-text text-muted">Risk score above which a risk is considered critical (1-25)</small>
                        </div>

                        <hr class="my-4">
                        <h6 class="text-danger mb-3"><i class="fas fa-ban me-2"></i>Automatic Client Rejection Settings</h6>

                        <div class="mb-3">
                            <label for="auto_rejection_enabled" class="form-label fw-bold">Automatic Client Rejection</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="auto_rejection_enabled" name="auto_rejection_enabled" value="1" 
                                       <?php echo e(old('auto_rejection_enabled', $riskThresholdSettings['auto_rejection_enabled'] ?? true) ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="auto_rejection_enabled">
                                    Enable automatic client rejection based on risk threshold
                                </label>
                            </div>
                            <small class="form-text text-muted">Automatically reject clients when their risk score exceeds the threshold</small>
                        </div>

                        <div class="mb-3">
                            <label for="auto_rejection_threshold" class="form-label fw-bold">Auto-Rejection Threshold</label>
                            <input type="number" class="form-control" id="auto_rejection_threshold" name="auto_rejection_threshold" 
                                   value="<?php echo e(old('auto_rejection_threshold', $riskThresholdSettings['auto_rejection_threshold'] ?? 20)); ?>" min="1" max="30">
                            <small class="form-text text-muted">Risk score above which clients are automatically rejected (1-30). Default: 20 (Very High risk)</small>
                        </div>

                        <button type="submit" class="btn btn-success w-100"><i class="fas fa-save me-2"></i>Save General Settings</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Notification Settings -->
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-warning">
                    <h5 class="mb-0"><i class="fas fa-bell me-2"></i>Notification Settings</h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('risks.settings.notifications')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        
                        <div class="mb-3">
                            <label for="email_notifications" class="form-label fw-bold">Email Notifications</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="email_notifications" name="email_notifications" value="1" 
                                       <?php echo e(old('email_notifications', $settings['email_notifications'] ?? true) ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="email_notifications">
                                    Enable email notifications for risk updates
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="high_risk_alerts" class="form-label fw-bold">High Risk Alerts</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="high_risk_alerts" name="high_risk_alerts" value="1" 
                                       <?php echo e(old('high_risk_alerts', $settings['high_risk_alerts'] ?? true) ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="high_risk_alerts">
                                    Send immediate alerts for high and critical risks
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="overdue_notifications" class="form-label fw-bold">Overdue Risk Notifications</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="overdue_notifications" name="overdue_notifications" value="1" 
                                       <?php echo e(old('overdue_notifications', $settings['overdue_notifications'] ?? true) ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="overdue_notifications">
                                    Notify when risks become overdue
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notification_frequency" class="form-label fw-bold">Notification Frequency</label>
                            <select class="form-select" id="notification_frequency" name="notification_frequency">
                                <?php $frequency = old('notification_frequency', $settings['notification_frequency'] ?? 'immediate'); ?>
                                <option value="immediate" <?php echo e($frequency == 'immediate' ? 'selected' : ''); ?>>Immediate</option>
                                <option value="daily" <?php echo e($frequency == 'daily' ? 'selected' : ''); ?>>Daily Digest</option>
                                <option value="weekly" <?php echo e($frequency == 'weekly' ? 'selected' : ''); ?>>Weekly Summary</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-warning w-100"><i class="fas fa-save me-2"></i>Save Notification Settings</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Complete System Functionality Guide -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-secondary shadow-sm">
                <div class="card-header" style="background: linear-gradient(135deg, #6c757d 0%, #495057 100%); color: white;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-book me-2"></i>Complete System User Guide & Documentation</h5>
                        <small class="badge bg-light text-dark">12 Sections - Click to Expand</small>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-3">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Quick Reference:</strong> Click on any section below to view detailed step-by-step instructions for that feature.
                    </div>
                    <div class="accordion" id="systemGuideAccordion">
                        
                        <!-- 1. Risk Register & Client Onboarding -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#section1">
                                    <i class="fas fa-shield-alt me-2 text-primary"></i>
                                    <strong>1. Risk Register & Client Onboarding</strong>
                                </button>
                            </h2>
                            <div id="section1" class="accordion-collapse collapse" data-bs-parent="#systemGuideAccordion">
                                <div class="accordion-body">
                                    <h6 class="text-primary"><i class="fas fa-user-plus me-2"></i>Adding a New Client Risk Assessment</h6>
                                    <ol>
                                        <li>Navigate to <strong>Risk Register</strong> from the left sidebar menu</li>
                                        <li>Click the <span class="badge bg-primary">Add New Risk Assessment</span> button</li>
                                        <li>Search for existing client to avoid duplicates</li>
                                        <li>Click <span class="badge bg-success">Proceed with New Client</span> if not found</li>
                                        <li>Fill in <strong>Client Information</strong>:
                                            <ul>
                                                <li>Client name, email, industry</li>
                                                <li>Client type (Individual or Juristic)</li>
                                                <li>Nationality, ID/Passport number</li>
                                            </ul>
                                        </li>
                                        <li>Upload <strong>Required Documents</strong>:
                                            <ul>
                                                <li>ID/Passport/Birth Certificate</li>
                                                <li>Proof of Residence</li>
                                                <li>KYC Form</li>
                                                <li>Source of Earnings</li>
                                            </ul>
                                        </li>
                                        <li>Select screening status and DCS risk appetite</li>
                                        <li>Select risks from 4 categories:
                                            <ul>
                                                <li><strong>SR</strong> - Service Risk (e.g., High-risk services, Complex services)</li>
                                                <li><strong>CR</strong> - Client Risk (e.g., PIP/PEP client, Corporate client)</li>
                                                <li><strong>PR</strong> - Payment Risk (e.g., Cash Payments, EFTs/SWIFT)</li>
                                                <li><strong>DR</strong> - Delivery Risk (e.g., Remote service risks)</li>
                                            </ul>
                                        </li>
                                        <li>Click <span class="badge bg-primary">Calculate Acceptance</span> to see overall risk rating</li>
                                        <li>Review the decision and click <span class="badge bg-success">Confirm & Save</span></li>
                                    </ol>
                                    <div class="alert alert-info mt-3">
                                        <i class="fas fa-lightbulb me-2"></i><strong>Automatic Features:</strong> Client record is automatically created, risk scores calculated, and next review dates set based on risk level.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 2. Client Management -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#section2">
                                    <i class="fas fa-users me-2 text-success"></i>
                                    <strong>2. Client Management</strong>
                                </button>
                            </h2>
                            <div id="section2" class="accordion-collapse collapse" data-bs-parent="#systemGuideAccordion">
                                <div class="accordion-body">
                                    <h6 class="text-primary"><i class="fas fa-list me-2"></i>Viewing All Clients</h6>
                                    <ol>
                                        <li>Go to <strong>Clients</strong> in the left sidebar</li>
                                        <li>View comprehensive table showing all approved clients</li>
                                        <li>See 33 columns including:
                                            <ul>
                                                <li>Client screening date & result</li>
                                                <li>CR, SR, PR, DR risk details (Risk ID, Description, Impact, Risk Rating)</li>
                                                <li>Overall risk points & rating</li>
                                                <li>Client acceptance decision & monitoring schedule</li>
                                            </ul>
                                        </li>
                                        <li>Use filters at the top to search by:
                                            <ul>
                                                <li>Client name, email, company</li>
                                                <li>Risk category (SR, CR, PR, DR)</li>
                                                <li>Screening status</li>
                                                <li>Risk level (Low, Medium, High)</li>
                                            </ul>
                                        </li>
                                    </ol>
                                    
                                    <h6 class="text-primary mt-3"><i class="fas fa-eye me-2"></i>Viewing Client Details</h6>
                                    <ol>
                                        <li>In the Clients table, find the client</li>
                                        <li>Click the <span class="badge bg-primary"><i class="fas fa-eye"></i></span> (View) icon in the Actions column</li>
                                        <li>View complete client profile including:
                                            <ul>
                                                <li>Personal/Company information</li>
                                                <li>KYC documents</li>
                                                <li>Risk summary and distribution</li>
                                                <li>Assessment history timeline</li>
                                            </ul>
                                        </li>
                                    </ol>
                                    
                                    <h6 class="text-primary mt-3"><i class="fas fa-history me-2"></i>Viewing Assessment History</h6>
                                    <ol>
                                        <li>Click the <span class="badge bg-info"><i class="fas fa-history"></i></span> (History) icon next to a client</li>
                                        <li>View complete chronological history of all assessments</li>
                                        <li>Compare risk progression over time</li>
                                        <li>Track changes in risk ratings and decisions</li>
                                    </ol>
                                    
                                    <h6 class="text-primary mt-3"><i class="fas fa-edit me-2"></i>Editing Client Information</h6>
                                    <ol>
                                        <li>Click the <span class="badge bg-warning text-dark"><i class="fas fa-edit"></i></span> (Edit) icon</li>
                                        <li>Confirm you want to edit</li>
                                        <li>Update necessary fields</li>
                                        <li>Save changes</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Reports & Analytics -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#section3">
                                    <i class="fas fa-chart-bar me-2 text-info"></i>
                                    <strong>3. Reports & Analytics</strong>
                                </button>
                            </h2>
                            <div id="section3" class="accordion-collapse collapse" data-bs-parent="#systemGuideAccordion">
                                <div class="accordion-body">
                                    <h6 class="text-primary"><i class="fas fa-file-alt me-2"></i>Accessing Reports</h6>
                                    <ol>
                                        <li>Go to <strong>Reports</strong> in the left sidebar</li>
                                        <li>View dashboard showing:
                                            <ul>
                                                <li>Total risk records</li>
                                                <li>High/Medium/Low risk counts</li>
                                                <li>Open/Closed risks</li>
                                                <li>Overdue reviews</li>
                                                <li>Client approval statistics</li>
                                            </ul>
                                        </li>
                                    </ol>
                                    
                                    <h6 class="text-primary mt-3"><i class="fas fa-filter me-2"></i>Using Report Filters</h6>
                                    <ol>
                                        <li>Use the filter section at top of reports page</li>
                                        <li>Filter by: Risk level, Status, Category, Date range</li>
                                        <li>Click <span class="badge bg-primary">Apply Filters</span></li>
                                        <li>View filtered results in the summary table</li>
                                    </ol>
                                    
                                    <h6 class="text-primary mt-3"><i class="fas fa-download me-2"></i>Exporting Data</h6>
                                    <ol>
                                        <li>Apply desired filters (optional)</li>
                                        <li>Click <span class="badge bg-success">Export PDF</span> or <span class="badge bg-success">Export Excel</span></li>
                                        <li>System generates downloadable report</li>
                                        <li>Use for regulatory compliance or record-keeping</li>
                                    </ol>
                                    
                                    <h6 class="text-primary mt-3"><i class="fas fa-chart-line me-2"></i>Client Risk Analysis</h6>
                                    <ol>
                                        <li>Go to <strong>Clients</strong> page</li>
                                        <li>Click on a client to view details</li>
                                        <li>Click <span class="badge bg-info">Risk Analysis</span> button</li>
                                        <li>View detailed risk breakdown, trends, and matrices</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <!-- 4. Risk Categories -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#section4">
                                    <i class="fas fa-folder me-2 text-warning"></i>
                                    <strong>4. Risk Categories Management</strong>
                                </button>
                            </h2>
                            <div id="section4" class="accordion-collapse collapse" data-bs-parent="#systemGuideAccordion">
                                <div class="accordion-body">
                                    <h6 class="text-primary"><i class="fas fa-folder-open me-2"></i>Main Risk Categories (CR, SR, PR, DR)</h6>
                                    <p>The 4 core categories are pre-configured and contain predefined risks:</p>
                                    <ul>
                                        <li><strong>CR - Client Risk:</strong> PIP/PEP client, Corporate client, Individual client</li>
                                        <li><strong>SR - Service Risk:</strong> High-risk services, Complex services, Standard services, Unrecorded transactions</li>
                                        <li><strong>PR - Payment Risk:</strong> Cash Payments, EFTs/SWIFT, POS Payments</li>
                                        <li><strong>DR - Delivery Risk:</strong> Remote service risks, Face-to-face service risks</li>
                                    </ul>
                                    
                                    <h6 class="text-primary mt-3"><i class="fas fa-plus me-2"></i>Creating Custom Categories</h6>
                                    <ol>
                                        <li>Go to <strong>Risk Categories</strong> in left sidebar</li>
                                        <li>Click <span class="badge bg-primary">Add Category</span></li>
                                        <li>Enter category name and description</li>
                                        <li>Choose a color for visual identification</li>
                                        <li>Save to add to system</li>
                                    </ol>
                                    
                                    <h6 class="text-primary mt-3"><i class="fas fa-tasks me-2"></i>Managing Categories</h6>
                                    <ol>
                                        <li>View all categories in the table</li>
                                        <li>Edit: Click <i class="fas fa-edit text-warning"></i> icon</li>
                                        <li>Delete: Click <i class="fas fa-trash text-danger"></i> icon</li>
                                        <li>Bulk actions: Select multiple, choose action, click Apply</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <!-- 5. User Management -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#section5">
                                    <i class="fas fa-user-cog me-2 text-secondary"></i>
                                    <strong>5. User Management</strong>
                                </button>
                            </h2>
                            <div id="section5" class="accordion-collapse collapse" data-bs-parent="#systemGuideAccordion">
                                <div class="accordion-body">
                                    <h6 class="text-primary"><i class="fas fa-user-plus me-2"></i>Adding New Users</h6>
                                    <ol>
                                        <li>Go to <strong>Users</strong> in the left sidebar</li>
                                        <li>Click <span class="badge bg-primary">Add New User</span></li>
                                        <li>Fill in user details:
                                            <ul>
                                                <li>Name, Email, Password</li>
                                                <li>Role (Admin, Manager, Staff)</li>
                                                <li>Department and position</li>
                                            </ul>
                                        </li>
                                        <li>Set permissions and access levels</li>
                                        <li>Save to create user account</li>
                                    </ol>
                                    
                                    <h6 class="text-primary mt-3"><i class="fas fa-edit me-2"></i>Editing User Profiles</h6>
                                    <ol>
                                        <li>Find user in the Users list</li>
                                        <li>Click <i class="fas fa-edit text-warning"></i> (Edit) icon</li>
                                        <li>Update user information or permissions</li>
                                        <li>Save changes</li>
                                    </ol>
                                    
                                    <h6 class="text-primary mt-3"><i class="fas fa-key me-2"></i>User Roles & Permissions</h6>
                                    <ul>
                                        <li><strong>Admin:</strong> Full system access, manage all users, approve assessments</li>
                                        <li><strong>Manager:</strong> Create assessments, view reports, manage assigned clients</li>
                                        <li><strong>Staff:</strong> Create assessments, view assigned clients</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- 6. Messages & Communication -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#section6">
                                    <i class="fas fa-envelope me-2 text-primary"></i>
                                    <strong>6. Internal Messages & Communication</strong>
                                </button>
                            </h2>
                            <div id="section6" class="accordion-collapse collapse" data-bs-parent="#systemGuideAccordion">
                                <div class="accordion-body">
                                    <h6 class="text-primary"><i class="fas fa-paper-plane me-2"></i>Sending Messages</h6>
                                    <ol>
                                        <li>Click the <i class="fas fa-envelope"></i> Messages icon in top navigation</li>
                                        <li>Click <span class="badge bg-primary">New Message</span></li>
                                        <li>Select recipient from user list</li>
                                        <li>Enter subject and message content</li>
                                        <li>Click <span class="badge bg-success">Send Message</span></li>
                                    </ol>
                                    
                                    <h6 class="text-primary mt-3"><i class="fas fa-inbox me-2"></i>Reading Messages</h6>
                                    <ol>
                                        <li>Click Messages icon (shows unread count badge)</li>
                                        <li>View inbox with all messages</li>
                                        <li>Click on a message to read full content</li>
                                        <li>Reply or delete as needed</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <!-- 7. Notifications -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#section7">
                                    <i class="fas fa-bell me-2 text-danger"></i>
                                    <strong>7. Notifications & Alerts</strong>
                                </button>
                            </h2>
                            <div id="section7" class="accordion-collapse collapse" data-bs-parent="#systemGuideAccordion">
                                <div class="accordion-body">
                                    <h6 class="text-primary"><i class="fas fa-bell me-2"></i>Viewing Notifications</h6>
                                    <ol>
                                        <li>Click the <i class="fas fa-bell"></i> bell icon in top navigation</li>
                                        <li>See dropdown with recent notifications</li>
                                        <li>Click on a notification to view details</li>
                                        <li>Mark as read or clear</li>
                                    </ol>
                                    
                                    <h6 class="text-primary mt-3"><i class="fas fa-info-circle me-2"></i>Notification Types</h6>
                                    <ul>
                                        <li><strong>New Assessment:</strong> When a client assessment is created</li>
                                        <li><strong>Review Due:</strong> When client review date is approaching</li>
                                        <li><strong>High Risk Alert:</strong> When high-risk client is detected</li>
                                        <li><strong>Status Changes:</strong> When assessment status changes</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- 8. Search Functionality -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#section8">
                                    <i class="fas fa-search me-2 text-info"></i>
                                    <strong>8. Search & Filter Features</strong>
                                </button>
                            </h2>
                            <div id="section8" class="accordion-collapse collapse" data-bs-parent="#systemGuideAccordion">
                                <div class="accordion-body">
                                    <h6 class="text-primary"><i class="fas fa-search me-2"></i>Using Global Search</h6>
                                    <ol>
                                        <li>Click the <i class="fas fa-search"></i> search icon in top navigation</li>
                                        <li>Enter client name, email, or company</li>
                                        <li>View instant search results</li>
                                        <li>Click on result to navigate to client</li>
                                    </ol>
                                    
                                    <h6 class="text-primary mt-3"><i class="fas fa-filter me-2"></i>Using Page Filters</h6>
                                    <ul>
                                        <li><strong>Client Page:</strong> Search by name/email, filter by risk level, screening status, category</li>
                                        <li><strong>Reports Page:</strong> Filter by date range, status, risk level, approval status</li>
                                        <li><strong>Risk Categories:</strong> Search categories by name or description</li>
                                    </ul>
                                    <p class="mb-0"><small class="text-muted">All filters work in real-time - results update as you type or select options.</small></p>
                                </div>
                            </div>
                        </div>

                        <!-- 9. Document Management -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#section9">
                                    <i class="fas fa-file-upload me-2 text-success"></i>
                                    <strong>9. Document Management</strong>
                                </button>
                            </h2>
                            <div id="section9" class="accordion-collapse collapse" data-bs-parent="#systemGuideAccordion">
                                <div class="accordion-body">
                                    <h6 class="text-primary"><i class="fas fa-upload me-2"></i>Uploading Documents</h6>
                                    <ol>
                                        <li>During client assessment creation, upload required documents</li>
                                        <li>Accepted formats: PDF, JPG, JPEG, PNG</li>
                                        <li>Maximum size: 5MB per file</li>
                                        <li>Required documents:
                                            <ul>
                                                <li>ID/Passport/Birth Certificate</li>
                                                <li>Proof of Residence</li>
                                                <li>KYC Form</li>
                                                <li>Source of Earnings</li>
                                                <li>Company registration (for Juristic clients)</li>
                                            </ul>
                                        </li>
                                    </ol>
                                    
                                    <h6 class="text-primary mt-3"><i class="fas fa-download me-2"></i>Viewing/Downloading Documents</h6>
                                    <ol>
                                        <li>Go to client details page</li>
                                        <li>Scroll to "Documents" section</li>
                                        <li>Click on document name to download</li>
                                        <li>All documents are securely stored and tracked</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <!-- 10. System Settings -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#section10">
                                    <i class="fas fa-cog me-2 text-secondary"></i>
                                    <strong>10. System Configuration</strong>
                                </button>
                            </h2>
                            <div id="section10" class="accordion-collapse collapse" data-bs-parent="#systemGuideAccordion">
                                <div class="accordion-body">
                                    <h6 class="text-primary"><i class="fas fa-sliders-h me-2"></i>Configuring Risk Thresholds</h6>
                                    <ol>
                                        <li>You're already on <strong>Settings</strong> page</li>
                                        <li>Scroll to "General Settings" section below</li>
                                        <li>Adjust:
                                            <ul>
                                                <li>Risk assessment frequency</li>
                                                <li>High risk threshold (points)</li>
                                                <li>Critical risk threshold (points)</li>
                                                <li>Automatic risk scoring</li>
                                            </ul>
                                        </li>
                                        <li>Save changes</li>
                                    </ol>
                                    
                                    <h6 class="text-primary mt-3"><i class="fas fa-database me-2"></i>Database Backup</h6>
                                    <ol>
                                        <li>See the "System Data Backup" section above</li>
                                        <li>Click <span class="badge bg-success">Download Database Backup</span> for data only</li>
                                        <li>Click <span class="badge bg-info">Download Full System Backup</span> for data + documents</li>
                                        <li>Store backups securely for disaster recovery</li>
                                    </ol>
                                    
                                    <h6 class="text-primary mt-3"><i class="fas fa-shield-alt me-2"></i>Security Best Practices</h6>
                                    <ul>
                                        <li>Regular backups: Weekly for database, monthly for full system</li>
                                        <li>User access: Review user permissions regularly</li>
                                        <li>Document security: All uploads are encrypted and secured</li>
                                        <li>Audit trail: All actions are logged for compliance</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- 11. Profile Management -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#section11">
                                    <i class="fas fa-user-circle me-2 text-primary"></i>
                                    <strong>11. Profile & Account Settings</strong>
                                </button>
                            </h2>
                            <div id="section11" class="accordion-collapse collapse" data-bs-parent="#systemGuideAccordion">
                                <div class="accordion-body">
                                    <h6 class="text-primary"><i class="fas fa-user-edit me-2"></i>Updating Your Profile</h6>
                                    <ol>
                                        <li>Click your profile icon in top navigation</li>
                                        <li>Select <span class="badge bg-info">Edit Profile</span></li>
                                        <li>Update your name, email, or password</li>
                                        <li>Save changes</li>
                                    </ol>
                                    
                                    <h6 class="text-primary mt-3"><i class="fas fa-sign-out-alt me-2"></i>Logging Out</h6>
                                    <ol>
                                        <li>Click your profile icon in top navigation</li>
                                        <li>Click <span class="badge bg-danger">Logout</span></li>
                                        <li>You'll be securely logged out of the system</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <!-- 12. Keyboard Shortcuts & Tips -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#section12">
                                    <i class="fas fa-keyboard me-2 text-dark"></i>
                                    <strong>12. Tips & Shortcuts</strong>
                                </button>
                            </h2>
                            <div id="section12" class="accordion-collapse collapse" data-bs-parent="#systemGuideAccordion">
                                <div class="accordion-body">
                                    <h6 class="text-primary"><i class="fas fa-bolt me-2"></i>Quick Navigation Tips</h6>
                                    <ul>
                                        <li><strong>Dashboard:</strong> Click the logo to return to dashboard anytime</li>
                                        <li><strong>Breadcrumbs:</strong> Use breadcrumb trail at top to navigate back</li>
                                        <li><strong>Sidebar:</strong> All main features accessible from left menu</li>
                                        <li><strong>Mobile:</strong> Tap hamburger menu (≡) to show/hide sidebar on mobile</li>
                                    </ul>
                                    
                                    <h6 class="text-primary mt-3"><i class="fas fa-lightbulb me-2"></i>Best Practices</h6>
                                    <ul>
                                        <li>Always search for existing clients before creating new ones</li>
                                        <li>Complete all required fields for accurate risk assessment</li>
                                        <li>Upload clear, legible documents (PDF preferred)</li>
                                        <li>Review client history before making decisions</li>
                                        <li>Use filters to quickly find specific clients or risks</li>
                                        <li>Create regular backups for data safety</li>
                                    </ul>
                                    
                                    <h6 class="text-primary mt-3"><i class="fas fa-question-circle me-2"></i>Need Help?</h6>
                                    <div class="alert alert-success">
                                        <p class="mb-2"><strong>Support Resources:</strong></p>
                                        <ul class="mb-0">
                                            <li>Each page has contextual help information</li>
                                            <li>Hover over buttons to see tooltips</li>
                                            <li>Watch for info boxes (<i class="fas fa-info-circle text-info"></i>) throughout the system</li>
                                            <li>Contact your system administrator for technical support</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if(auth()->user()->canManageRiskCategories()): ?>
    <div class="row mt-4">
        <!-- Risk Categories Management -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <button class="btn btn-link p-0 me-3" type="button" data-bs-toggle="collapse" data-bs-target="#riskCategoriesCollapse" aria-expanded="true" aria-controls="riskCategoriesCollapse">
                                <i class="fas fa-chevron-down collapse-icon" id="riskCategoriesIcon"></i>
                            </button>
                            <h4 class="header-title mb-0">Risk Categories</h4>
                        </div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                            <i class="fas fa-plus me-1"></i>Add Category
                        </button>
                    </div>
                </div>
                <div class="collapse" id="riskCategoriesCollapse">
                    <div class="card-body">
                        <?php if(isset($categories) && $categories->count() > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-centered table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Category</th>
                                            <th>Description</th>
                                            <th>Color</th>
                                            <th>Risks Count</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($category->name); ?></td>
                                            <td><?php echo e(Str::limit($category->description ?? 'No description', 50)); ?></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="color-preview me-2" 
                                                         style="width: 20px; height: 20px; background-color: <?php echo e($category->getFormattedColor()); ?>; border-radius: 4px;"></div>
                                                    <span><?php echo e($category->getFormattedColor()); ?></span>
                                                </div>
                                            </td>
                                            <td><?php echo e($category->risks_count ?? 0); ?></td>
                                            <td>
                                                <?php if($category->is_active): ?>
                                                    <span class="badge bg-success">Active</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-outline-primary" 
                                                            onclick="editCategory(<?php echo e($category->id); ?>, '<?php echo e(addslashes($category->name)); ?>', '<?php echo e(addslashes($category->description ?? '')); ?>', '<?php echo e($category->getFormattedColor()); ?>')">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger" 
                                                            onclick="deleteCategory(<?php echo e($category->id); ?>)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="fas fa-folder-open text-muted" style="font-size: 3rem;"></i>
                                <h5 class="mt-3 text-muted">No Categories Found</h5>
                                <p class="text-muted">Create your first risk category to get started.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- System Information -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>System Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">System Version</label>
                        <p class="mb-0"><?php echo e($systemInfo['version'] ?? '1.0.0'); ?></p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Last Updated</label>
                        <p class="mb-0"><?php echo e($systemInfo['last_updated'] ?? 'Never'); ?></p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Database Records</label>
                        <p class="mb-0"><?php echo e($systemInfo['total_risks'] ?? 0); ?> risks, <?php echo e($systemInfo['total_clients'] ?? 0); ?> clients</p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Cache Status</label>
                        <p class="mb-0">
                            <?php if($systemInfo['cache_enabled'] ?? false): ?>
                                <span class="badge bg-success">Enabled</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Disabled</span>
                            <?php endif; ?>
                        </p>
                    </div>
                    
                    <hr>
                    
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearCache()">
                            <i class="fas fa-refresh me-1"></i>Clear Cache
                        </button>
                        <button type="button" class="btn btn-outline-info btn-sm" onclick="exportSettings()">
                            <i class="fas fa-download me-1"></i>Export Settings
                        </button>
                        <button type="button" class="btn btn-outline-warning btn-sm" onclick="resetToDefaults()">
                            <i class="fas fa-undo me-1"></i>Reset to Defaults
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if(auth()->user()->canManageRiskCategories()): ?>
<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Risk Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('risk-categories.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="category_name" class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="category_name" name="name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="category_description" class="form-label">Description</label>
                        <textarea class="form-control" id="category_description" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="category_color" class="form-label">Color</label>
                        <input type="color" class="form-control form-control-color" id="category_color" name="color" value="#007bff">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Risk Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editCategoryForm" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_category_name" class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="edit_category_name" name="name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_category_description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit_category_description" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_category_color" class="form-label">Color</label>
                        <input type="color" class="form-control form-control-color" id="edit_category_color" name="color">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Category</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<style>
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
    .col-md-6, .col-md-4 {
        padding: 0.25rem;
        margin-bottom: 0.75rem;
    }
    .form-label {
        font-size: 0.9rem;
        margin-bottom: 0.375rem;
    }
    .form-control, .form-select {
        padding: 0.75rem;
        font-size: 16px; /* Prevents zoom on iOS */
        border-radius: 8px;
    }
    .form-text {
        font-size: 0.8rem;
    }
    .btn {
        padding: 0.75rem 1.5rem;
        font-size: 0.9rem;
    }
    .btn-group {
        flex-direction: column;
        gap: 0.5rem;
    }
    .btn-group .btn {
        width: 100%;
    }
    .text-end {
        text-align: center !important;
        margin-top: 1rem;
    }
    .table {
        font-size: 0.8rem;
    }
    .table th, .table td {
        padding: 0.5rem 0.25rem;
        font-size: 0.75rem;
    }
    .badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
    }
    .alert {
        font-size: 0.85rem;
        padding: 0.75rem 1rem;
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
    .col-md-6, .col-md-4 {
        padding: 0.125rem;
        margin-bottom: 0.5rem;
    }
    .form-control, .form-select {
        padding: 0.625rem;
        font-size: 16px;
    }
    .form-text {
        font-size: 0.75rem;
    }
    .btn {
        padding: 0.625rem 1.25rem;
        font-size: 0.85rem;
    }
    .table {
        font-size: 0.7rem;
    }
    .table th, .table td {
        padding: 0.375rem 0.125rem;
        font-size: 0.65rem;
    }
    .badge {
        font-size: 0.65rem;
        padding: 0.2rem 0.4rem;
    }
    .alert {
        font-size: 0.8rem;
        padding: 0.625rem 0.75rem;
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
    .form-control, .form-select {
        padding: 0.5rem;
        font-size: 16px;
    }
    .form-text {
        font-size: 0.7rem;
    }
    .btn {
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
    }
    .table {
        font-size: 0.65rem;
    }
    .table th, .table td {
        padding: 0.25rem 0.1rem;
        font-size: 0.6rem;
    }
    .badge {
        font-size: 0.6rem;
        padding: 0.15rem 0.3rem;
    }
    .alert {
        font-size: 0.75rem;
        padding: 0.5rem 0.625rem;
    }
}
</style>

<?php $__env->startPush('styles'); ?>
<style>
.color-preview {
    border: 1px solid #dee2e6;
}

.form-control-color {
    width: 100%;
    height: 38px;
}

.collapse-icon {
    transition: transform 0.3s ease;
    font-size: 1.1rem;
    color: #6c757d;
}

.collapse-icon.rotated {
    transform: rotate(-90deg);
}

.btn-link {
    text-decoration: none;
    border: none;
    background: none;
}

.btn-link:hover {
    text-decoration: none;
    color: #007bff;
}

.btn-link:focus {
    box-shadow: none;
    outline: none;
}

.collapse {
    transition: height 0.3s ease;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Handle collapse icon rotation
document.addEventListener('DOMContentLoaded', function() {
    // Risk Categories collapse
    const riskCategoriesCollapse = document.getElementById('riskCategoriesCollapse');
    const riskCategoriesIcon = document.getElementById('riskCategoriesIcon');
    
    if (riskCategoriesCollapse && riskCategoriesIcon) {
        riskCategoriesCollapse.addEventListener('show.bs.collapse', function() {
            riskCategoriesIcon.classList.remove('rotated');
        });
        
        riskCategoriesCollapse.addEventListener('hide.bs.collapse', function() {
            riskCategoriesIcon.classList.add('rotated');
        });
        
        // Set initial state
        if (!riskCategoriesCollapse.classList.contains('show')) {
            riskCategoriesIcon.classList.add('rotated');
        }
    }
});

function editCategory(id, name, description, color) {
    document.getElementById('edit_category_name').value = name;
    document.getElementById('edit_category_description').value = description;
    document.getElementById('edit_category_color').value = color;
    
    const form = document.getElementById('editCategoryForm');
    form.action = `/risk-categories/${id}`;
    
    new bootstrap.Modal(document.getElementById('editCategoryModal')).show();
}

function deleteCategory(id) {
    if (confirm('Are you sure you want to delete this category? This will also affect all associated risks.')) {
        fetch(`/risk-categories/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then(response => {
            if (response.ok) {
                location.reload();
            }
        });
    }
}

function clearCache() {
    if (confirm('Are you sure you want to clear all caches? This may temporarily slow down the system.')) {
        fetch('/dashboard/clear-all-caches', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Cache cleared successfully', 'success');
            } else {
                showNotification('Failed to clear cache', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Failed to clear cache', 'error');
        });
    }
}

function exportSettings() {
    // Create a settings object with current form values
    const settings = {
        general: {
            risk_assessment_frequency: document.getElementById('risk_assessment_frequency').value,
            auto_risk_scoring: document.getElementById('auto_risk_scoring').checked,
            risk_threshold_high: document.getElementById('risk_threshold_high').value,
            risk_threshold_critical: document.getElementById('risk_threshold_critical').value
        },
        notifications: {
            email_notifications: document.getElementById('email_notifications').checked,
            high_risk_alerts: document.getElementById('high_risk_alerts').checked,
            overdue_notifications: document.getElementById('overdue_notifications').checked,
            notification_frequency: document.getElementById('notification_frequency').value
        },
        exported_at: new Date().toISOString(),
        exported_by: '<?php echo e(auth()->user()->name); ?>'
    };
    
    // Create and download JSON file
    const dataStr = JSON.stringify(settings, null, 2);
    const dataBlob = new Blob([dataStr], {type: 'application/json'});
    const url = URL.createObjectURL(dataBlob);
    const link = document.createElement('a');
    link.href = url;
    link.download = 'risk_management_settings_' + new Date().toISOString().split('T')[0] + '.json';
    link.click();
    URL.revokeObjectURL(url);
    
    showNotification('Settings exported successfully', 'success');
}

function resetToDefaults() {
    if (confirm('Are you sure you want to reset all settings to default values? This action cannot be undone.')) {
        // Reset form values to defaults
        document.getElementById('risk_assessment_frequency').value = 'monthly';
        document.getElementById('auto_risk_scoring').checked = true;
        document.getElementById('risk_threshold_high').value = 15;
        document.getElementById('risk_threshold_critical').value = 20;
        document.getElementById('email_notifications').checked = true;
        document.getElementById('high_risk_alerts').checked = true;
        document.getElementById('overdue_notifications').checked = true;
        document.getElementById('notification_frequency').value = 'immediate';
        
        showNotification('Settings reset to defaults (not saved yet)', 'info');
    }
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\well-known\resources\views/risks/settings.blade.php ENDPATH**/ ?>