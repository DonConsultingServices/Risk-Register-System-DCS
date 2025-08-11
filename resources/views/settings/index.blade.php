@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-cog me-2"></i>System Settings
                </h5>
                <div class="btn-group">
                    <a href="{{ route('settings.export') }}" class="btn btn-outline-primary">
                        <i class="fas fa-download me-1"></i>Export Settings
                    </a>
                    <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#importSettingsModal">
                        <i class="fas fa-upload me-1"></i>Import Settings
                    </button>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('settings.update') }}" method="POST">
                    @csrf
                    
                    <!-- General Settings -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-building me-1"></i>General Settings
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="company_name" class="form-label">Company Name</label>
                                <input type="text" class="form-control @error('company_name') is-invalid @enderror" 
                                       id="company_name" name="company_name" 
                                       value="{{ old('company_name', $settings['company_name']) }}" required>
                                @error('company_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="system_email" class="form-label">System Email</label>
                                <input type="email" class="form-control @error('system_email') is-invalid @enderror" 
                                       id="system_email" name="system_email" 
                                       value="{{ old('system_email', $settings['system_email']) }}" required>
                                @error('system_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="timezone" class="form-label">Timezone</label>
                                <select class="form-select @error('timezone') is-invalid @enderror" 
                                        id="timezone" name="timezone">
                                    <option value="UTC" {{ $settings['timezone'] == 'UTC' ? 'selected' : '' }}>UTC</option>
                                    <option value="America/New_York" {{ $settings['timezone'] == 'America/New_York' ? 'selected' : '' }}>Eastern Time</option>
                                    <option value="America/Chicago" {{ $settings['timezone'] == 'America/Chicago' ? 'selected' : '' }}>Central Time</option>
                                    <option value="America/Denver" {{ $settings['timezone'] == 'America/Denver' ? 'selected' : '' }}>Mountain Time</option>
                                    <option value="America/Los_Angeles" {{ $settings['timezone'] == 'America/Los_Angeles' ? 'selected' : '' }}>Pacific Time</option>
                                    <option value="Europe/London" {{ $settings['timezone'] == 'Europe/London' ? 'selected' : '' }}>London</option>
                                    <option value="Europe/Paris" {{ $settings['timezone'] == 'Europe/Paris' ? 'selected' : '' }}>Paris</option>
                                    <option value="Asia/Tokyo" {{ $settings['timezone'] == 'Asia/Tokyo' ? 'selected' : '' }}>Tokyo</option>
                                </select>
                                @error('timezone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="date_format" class="form-label">Date Format</label>
                                <select class="form-select @error('date_format') is-invalid @enderror" 
                                        id="date_format" name="date_format">
                                    <option value="Y-m-d" {{ $settings['date_format'] == 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD</option>
                                    <option value="m/d/Y" {{ $settings['date_format'] == 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY</option>
                                    <option value="d/m/Y" {{ $settings['date_format'] == 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY</option>
                                    <option value="M d, Y" {{ $settings['date_format'] == 'M d, Y' ? 'selected' : '' }}>Jan 01, 2024</option>
                                </select>
                                @error('date_format')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Notification Settings -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-bell me-1"></i>Notification Settings
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="enable_notifications" 
                                           name="enable_notifications" value="1" 
                                           {{ $settings['enable_notifications'] ? 'checked' : '' }}>
                                    <label class="form-check-label" for="enable_notifications">
                                        Enable Email Notifications
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="notification_email" class="form-label">Notification Email</label>
                                <input type="email" class="form-control @error('notification_email') is-invalid @enderror" 
                                       id="notification_email" name="notification_email" 
                                       value="{{ old('notification_email', $settings['notification_email']) }}">
                                @error('notification_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Security Settings -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-shield-alt me-1"></i>Security Settings
                            </h6>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="session_timeout" class="form-label">Session Timeout (minutes)</label>
                                <input type="number" class="form-control @error('session_timeout') is-invalid @enderror" 
                                       id="session_timeout" name="session_timeout" min="5" max="480"
                                       value="{{ old('session_timeout', $settings['session_timeout']) }}" required>
                                @error('session_timeout')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="max_login_attempts" class="form-label">Max Login Attempts</label>
                                <input type="number" class="form-control @error('max_login_attempts') is-invalid @enderror" 
                                       id="max_login_attempts" name="max_login_attempts" min="3" max="10"
                                       value="{{ old('max_login_attempts', $settings['max_login_attempts']) }}" required>
                                @error('max_login_attempts')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="password_expiry_days" class="form-label">Password Expiry (days)</label>
                                <input type="number" class="form-control @error('password_expiry_days') is-invalid @enderror" 
                                       id="password_expiry_days" name="password_expiry_days" min="30" max="365"
                                       value="{{ old('password_expiry_days', $settings['password_expiry_days']) }}" required>
                                @error('password_expiry_days')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Risk Assessment Settings -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-exclamation-triangle me-1"></i>Risk Assessment Settings
                            </h6>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="risk_threshold_high" class="form-label">High Risk Threshold</label>
                                <input type="number" class="form-control @error('risk_threshold_high') is-invalid @enderror" 
                                       id="risk_threshold_high" name="risk_threshold_high" min="1" max="20"
                                       value="{{ old('risk_threshold_high', $settings['risk_threshold_high']) }}" required>
                                @error('risk_threshold_high')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="risk_threshold_medium" class="form-label">Medium Risk Threshold</label>
                                <input type="number" class="form-control @error('risk_threshold_medium') is-invalid @enderror" 
                                       id="risk_threshold_medium" name="risk_threshold_medium" min="1" max="20"
                                       value="{{ old('risk_threshold_medium', $settings['risk_threshold_medium']) }}" required>
                                @error('risk_threshold_medium')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="risk_threshold_low" class="form-label">Low Risk Threshold</label>
                                <input type="number" class="form-control @error('risk_threshold_low') is-invalid @enderror" 
                                       id="risk_threshold_low" name="risk_threshold_low" min="1" max="20"
                                       value="{{ old('risk_threshold_low', $settings['risk_threshold_low']) }}" required>
                                @error('risk_threshold_low')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Backup Settings -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-database me-1"></i>Backup Settings
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="auto_backup" 
                                           name="auto_backup" value="1" 
                                           {{ $settings['auto_backup'] ? 'checked' : '' }}>
                                    <label class="form-check-label" for="auto_backup">
                                        Enable Automatic Backups
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="backup_frequency" class="form-label">Backup Frequency</label>
                                <select class="form-select @error('backup_frequency') is-invalid @enderror" 
                                        id="backup_frequency" name="backup_frequency">
                                    <option value="daily" {{ $settings['backup_frequency'] == 'daily' ? 'selected' : '' }}>Daily</option>
                                    <option value="weekly" {{ $settings['backup_frequency'] == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                    <option value="monthly" {{ $settings['backup_frequency'] == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                </select>
                                @error('backup_frequency')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#resetSettingsModal">
                            <i class="fas fa-undo me-1"></i>Reset to Defaults
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Import Settings Modal -->
<div class="modal fade" id="importSettingsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Settings</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('settings.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="settings_file" class="form-label">Settings File (JSON)</label>
                        <input type="file" class="form-control" id="settings_file" name="settings_file" 
                               accept=".json" required>
                        <div class="form-text">Upload a JSON file containing system settings.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Import Settings</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reset Settings Modal -->
<div class="modal fade" id="resetSettingsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reset Settings</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to reset all settings to their default values?</p>
                <p class="text-warning"><i class="fas fa-exclamation-triangle me-1"></i>This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('settings.reset') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-warning">Reset Settings</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto backup toggle
    const autoBackupCheckbox = document.getElementById('auto_backup');
    const backupFrequencySelect = document.getElementById('backup_frequency');
    
    function toggleBackupFrequency() {
        backupFrequencySelect.disabled = !autoBackupCheckbox.checked;
    }
    
    autoBackupCheckbox.addEventListener('change', toggleBackupFrequency);
    toggleBackupFrequency(); // Initial state
    
    // Notification toggle
    const enableNotificationsCheckbox = document.getElementById('enable_notifications');
    const notificationEmailInput = document.getElementById('notification_email');
    
    function toggleNotificationEmail() {
        notificationEmailInput.disabled = !enableNotificationsCheckbox.checked;
    }
    
    enableNotificationsCheckbox.addEventListener('change', toggleNotificationEmail);
    toggleNotificationEmail(); // Initial state
});
</script>
@endsection 