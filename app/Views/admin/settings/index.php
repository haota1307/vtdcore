<div class="page-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">System Settings</h4>
                    <div class="page-title-right">
                        <button type="button" class="btn btn-success" onclick="saveSettings()">
                            <i class="ri-save-line align-middle me-1"></i> Save Settings
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Settings Tabs -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-tabs-custom" id="settingsTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab">
                                    <i class="ri-settings-3-line align-bottom me-1"></i> General
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="email-tab" data-bs-toggle="tab" data-bs-target="#email" type="button" role="tab">
                                    <i class="ri-mail-line align-bottom me-1"></i> Email
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab">
                                    <i class="ri-shield-line align-bottom me-1"></i> Security
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content" id="settingsTabContent">
                            <!-- General Settings -->
                            <div class="tab-pane fade show active" id="general" role="tabpanel">
                                <form id="generalSettingsForm">
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <div class="mb-4">
                                                <h5 class="mb-3">Site Information</h5>
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label">Site Name</label>
                                                        <input type="text" class="form-control" name="site_name" value="<?= esc($settings['site_name'] ?? 'VTDevCore') ?>">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Site URL</label>
                                                        <input type="url" class="form-control" name="site_url" value="<?= esc($settings['site_url'] ?? base_url()) ?>">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label">Site Description</label>
                                                        <textarea class="form-control" name="site_description" rows="3"><?= esc($settings['site_description'] ?? '') ?></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-4">
                                                <h5 class="mb-3">System Configuration</h5>
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label">Default Language</label>
                                                        <select class="form-select" name="default_language">
                                                            <option value="en" <?= ($settings['default_language'] ?? 'en') === 'en' ? 'selected' : '' ?>>English</option>
                                                            <option value="vi" <?= ($settings['default_language'] ?? 'en') === 'vi' ? 'selected' : '' ?>>Vietnamese</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Timezone</label>
                                                        <select class="form-select" name="timezone">
                                                            <option value="Asia/Ho_Chi_Minh" <?= ($settings['timezone'] ?? 'Asia/Ho_Chi_Minh') === 'Asia/Ho_Chi_Minh' ? 'selected' : '' ?>>Asia/Ho Chi Minh</option>
                                                            <option value="UTC" <?= ($settings['timezone'] ?? 'Asia/Ho_Chi_Minh') === 'UTC' ? 'selected' : '' ?>>UTC</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-4">
                                                <h5 class="mb-3">Maintenance Mode</h5>
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" name="maintenance_mode" id="maintenanceMode" <?= ($settings['maintenance_mode'] ?? false) ? 'checked' : '' ?>>
                                                            <label class="form-check-label" for="maintenanceMode">Enable Maintenance Mode</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Maintenance Message</label>
                                                        <textarea class="form-control" name="maintenance_message" rows="2"><?= esc($settings['maintenance_message'] ?? 'Site is under maintenance. Please check back later.') ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="card border">
                                                <div class="card-body">
                                                    <h6 class="card-title mb-3">System Information</h6>
                                                    <div class="d-flex align-items-center mb-3">
                                                        <div class="flex-shrink-0">
                                                            <div class="avatar-sm">
                                                                <div class="avatar-title bg-soft-primary text-primary rounded">
                                                                    <i class="ri-server-line"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 ms-3">
                                                            <h6 class="mb-1">PHP Version</h6>
                                                            <p class="text-muted mb-0"><?= PHP_VERSION ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex align-items-center mb-3">
                                                        <div class="flex-shrink-0">
                                                            <div class="avatar-sm">
                                                                <div class="avatar-title bg-soft-success text-success rounded">
                                                                    <i class="ri-database-2-line"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 ms-3">
                                                            <h6 class="mb-1">Database</h6>
                                                            <p class="text-muted mb-0">MySQL</p>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0">
                                                            <div class="avatar-sm">
                                                                <div class="avatar-title bg-soft-warning text-warning rounded">
                                                                    <i class="ri-hard-drive-line"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 ms-3">
                                                            <h6 class="mb-1">Disk Usage</h6>
                                                            <p class="text-muted mb-0"><?= $diskUsage ?? 'Unknown' ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Email Settings -->
                            <div class="tab-pane fade" id="email" role="tabpanel">
                                <form id="emailSettingsForm">
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <div class="mb-4">
                                                <h5 class="mb-3">SMTP Configuration</h5>
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label">SMTP Host</label>
                                                        <input type="text" class="form-control" name="smtp_host" value="<?= esc($settings['smtp_host'] ?? '') ?>">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">SMTP Port</label>
                                                        <input type="number" class="form-control" name="smtp_port" value="<?= esc($settings['smtp_port'] ?? '587') ?>">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">SMTP Username</label>
                                                        <input type="text" class="form-control" name="smtp_username" value="<?= esc($settings['smtp_username'] ?? '') ?>">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">SMTP Password</label>
                                                        <input type="password" class="form-control" name="smtp_password" value="<?= esc($settings['smtp_password'] ?? '') ?>">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">From Email</label>
                                                        <input type="email" class="form-control" name="from_email" value="<?= esc($settings['from_email'] ?? 'noreply@example.com') ?>">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">From Name</label>
                                                        <input type="text" class="form-control" name="from_name" value="<?= esc($settings['from_name'] ?? 'VTDevCore') ?>">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-4">
                                                <h5 class="mb-3">Test Email</h5>
                                                <div class="row g-3">
                                                    <div class="col-md-8">
                                                        <label class="form-label">Test Email Address</label>
                                                        <input type="email" class="form-control" id="testEmail" placeholder="Enter email to test">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">&nbsp;</label>
                                                        <button type="button" class="btn btn-primary w-100" onclick="testEmail()">
                                                            <i class="ri-send-plane-line align-middle me-1"></i> Send Test
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="card border">
                                                <div class="card-body">
                                                    <h6 class="card-title mb-3">Email Status</h6>
                                                    <div class="d-flex align-items-center mb-3">
                                                        <div class="flex-shrink-0">
                                                            <div class="avatar-sm">
                                                                <div class="avatar-title bg-soft-success text-success rounded">
                                                                    <i class="ri-mail-check-line"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 ms-3">
                                                            <h6 class="mb-1">SMTP Status</h6>
                                                            <p class="text-muted mb-0"><?= $smtpStatus ?? 'Not configured' ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Security Settings -->
                            <div class="tab-pane fade" id="security" role="tabpanel">
                                <form id="securitySettingsForm">
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <div class="mb-4">
                                                <h5 class="mb-3">Password Policy</h5>
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label">Minimum Password Length</label>
                                                        <input type="number" class="form-control" name="min_password_length" value="<?= esc($settings['min_password_length'] ?? '8') ?>" min="6" max="20">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Password Expiry (days)</label>
                                                        <input type="number" class="form-control" name="password_expiry_days" value="<?= esc($settings['password_expiry_days'] ?? '90') ?>" min="0">
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="require_uppercase" id="requireUppercase" <?= ($settings['require_uppercase'] ?? true) ? 'checked' : '' ?>>
                                                            <label class="form-check-label" for="requireUppercase">Require uppercase letters</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="require_numbers" id="requireNumbers" <?= ($settings['require_numbers'] ?? true) ? 'checked' : '' ?>>
                                                            <label class="form-check-label" for="requireNumbers">Require numbers</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-4">
                                                <h5 class="mb-3">Session Security</h5>
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label">Session Timeout (minutes)</label>
                                                        <input type="number" class="form-control" name="session_timeout" value="<?= esc($settings['session_timeout'] ?? '120') ?>" min="15">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Max Login Attempts</label>
                                                        <input type="number" class="form-control" name="max_login_attempts" value="<?= esc($settings['max_login_attempts'] ?? '5') ?>" min="3" max="10">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="card border">
                                                <div class="card-body">
                                                    <h6 class="card-title mb-3">Security Status</h6>
                                                    <div class="d-flex align-items-center mb-3">
                                                        <div class="flex-shrink-0">
                                                            <div class="avatar-sm">
                                                                <div class="avatar-title bg-soft-success text-success rounded">
                                                                    <i class="ri-shield-check-line"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 ms-3">
                                                            <h6 class="mb-1">SSL Certificate</h6>
                                                            <p class="text-muted mb-0"><?= $sslStatus ?? 'Not configured' ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function saveSettings() {
    // Collect all form data
    const forms = ['generalSettingsForm', 'emailSettingsForm', 'securitySettingsForm'];
    const formData = new FormData();
    
    forms.forEach(formId => {
        const form = document.getElementById(formId);
        if (form) {
            const formElements = form.elements;
            for (let element of formElements) {
                if (element.name) {
                    if (element.type === 'checkbox') {
                        formData.append(element.name, element.checked ? '1' : '0');
                    } else {
                        formData.append(element.name, element.value);
                    }
                }
            }
        }
    });
    
    // Send to server
    fetch('/admin/settings/save', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Settings saved successfully!');
        } else {
            alert('Failed to save settings: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while saving settings');
    });
}

function testEmail() {
    const email = document.getElementById('testEmail').value;
    if (!email) {
        alert('Please enter an email address');
        return;
    }
    
    fetch('/admin/settings/test-email', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ email: email })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Test email sent successfully!');
        } else {
            alert('Failed to send test email: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while sending test email');
    });
}
</script>
