<div class="page-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Module Management</h4>
                    <div class="page-title-right">
                        <button type="button" class="btn btn-primary" onclick="scanModules()">
                            <i class="ri-refresh-line align-middle me-1"></i> Scan Modules
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-sm">
                                    <div class="avatar-title bg-soft-primary text-primary rounded fs-3">
                                        <i class="ri-apps-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <p class="text-muted mb-1">Total Modules</p>
                                <h4 class="mb-0"><?= count($modules) ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-sm">
                                    <div class="avatar-title bg-soft-success text-success rounded fs-3">
                                        <i class="ri-check-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <p class="text-muted mb-1">Active Modules</p>
                                <h4 class="mb-0"><?= count(array_filter($modules, fn($m) => $m['enabled'] ?? true)) ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-sm">
                                    <div class="avatar-title bg-soft-warning text-warning rounded fs-3">
                                        <i class="ri-error-warning-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <p class="text-muted mb-1">Inactive Modules</p>
                                <h4 class="mb-0"><?= count(array_filter($modules, fn($m) => !($m['enabled'] ?? true))) ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-sm">
                                    <div class="avatar-title bg-soft-info text-info rounded fs-3">
                                        <i class="ri-settings-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <p class="text-muted mb-1">System Modules</p>
                                <h4 class="mb-0"><?= count(array_filter($modules, fn($m) => $m['type'] ?? 'custom' === 'system')) ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Module Filters -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Search</label>
                                <input type="text" class="form-control" id="searchModules" placeholder="Search modules...">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" id="statusFilter">
                                    <option value="">All Status</option>
                                    <option value="enabled">Active</option>
                                    <option value="disabled">Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Type</label>
                                <select class="form-select" id="typeFilter">
                                    <option value="">All Types</option>
                                    <option value="system">System</option>
                                    <option value="custom">Custom</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <button type="button" class="btn btn-primary w-100" onclick="filterModules()">
                                    <i class="ri-search-line"></i> Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modules Grid -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row g-4 align-items-center">
                            <div class="col-sm">
                                <div>
                                    <h5 class="card-title mb-0">Available Modules</h5>
                                    <p class="text-muted mb-0">Manage system modules and their configurations</p>
                                </div>
                            </div>
                            <div class="col-sm-auto">
                                <div class="d-flex gap-1">
                                    <button class="btn btn-soft-info" type="button" onclick="exportModules()">
                                        <i class="ri-download-line align-bottom"></i> Export
                                    </button>
                                    <button class="btn btn-soft-warning" type="button" onclick="bulkToggle()">
                                        <i class="ri-toggle-line align-bottom"></i> Bulk Toggle
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row" id="modulesGrid">
                            <?php if (!empty($modules)): foreach ($modules as $module): ?>
                            <div class="col-xl-4 col-lg-6 col-md-6 module-card" data-module-id="<?= $module['id'] ?>">
                                <div class="card module-item">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar-sm">
                                                    <div class="avatar-title bg-soft-<?= $module['enabled'] ?? true ? 'success' : 'secondary' ?> text-<?= $module['enabled'] ?? true ? 'success' : 'secondary' ?> rounded">
                                                        <i class="<?= $module['icon'] ?? 'ri-apps-line' ?>"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1"><?= esc($module['name']) ?></h6>
                                                <small class="text-muted"><?= esc($module['version'] ?? '1.0.0') ?></small>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input module-toggle" type="checkbox" 
                                                           id="module_<?= $module['id'] ?>" 
                                                           data-module-id="<?= $module['id'] ?>"
                                                           <?= ($module['enabled'] ?? true) ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="module_<?= $module['id'] ?>"></label>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <p class="text-muted mb-3"><?= esc($module['description'] ?? 'No description available') ?></p>
                                        
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div>
                                                <span class="badge bg-soft-<?= $module['type'] ?? 'custom' === 'system' ? 'warning' : 'info' ?> text-<?= $module['type'] ?? 'custom' === 'system' ? 'warning' : 'info' ?>">
                                                    <?= ucfirst($module['type'] ?? 'custom') ?>
                                                </span>
                                                <?php if ($module['enabled'] ?? true): ?>
                                                <span class="badge bg-soft-success text-success">Active</span>
                                                <?php else: ?>
                                                <span class="badge bg-soft-secondary text-secondary">Inactive</span>
                                                <?php endif; ?>
                                            </div>
                                            <small class="text-muted"><?= esc($module['author'] ?? 'Unknown') ?></small>
                                        </div>
                                        
                                        <div class="d-flex gap-1">
                                            <button class="btn btn-soft-primary btn-sm flex-fill" onclick="viewModuleDetails('<?= $module['id'] ?>')">
                                                <i class="ri-eye-line align-bottom me-1"></i> Details
                                            </button>
                                            <button class="btn btn-soft-info btn-sm flex-fill" onclick="configureModule('<?= $module['id'] ?>')">
                                                <i class="ri-settings-line align-bottom me-1"></i> Configure
                                            </button>
                                            <div class="dropdown">
                                                <button class="btn btn-soft-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    <i class="ri-more-fill"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="#" onclick="viewModuleRoutes('<?= $module['id'] ?>')">
                                                        <i class="ri-route-line align-bottom me-2 text-muted"></i> Routes
                                                    </a></li>
                                                    <li><a class="dropdown-item" href="#" onclick="viewModuleDependencies('<?= $module['id'] ?>')">
                                                        <i class="ri-links-line align-bottom me-2 text-muted"></i> Dependencies
                                                    </a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li><a class="dropdown-item text-warning" href="#" onclick="reinstallModule('<?= $module['id'] ?>')">
                                                        <i class="ri-refresh-line align-bottom me-2"></i> Reinstall
                                                    </a></li>
                                                    <?php if (($module['type'] ?? 'custom') === 'custom'): ?>
                                                    <li><a class="dropdown-item text-danger" href="#" onclick="uninstallModule('<?= $module['id'] ?>')">
                                                        <i class="ri-delete-bin-line align-bottom me-2"></i> Uninstall
                                                    </a></li>
                                                    <?php endif; ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; else: ?>
                            <div class="col-12">
                                <div class="text-center text-muted py-5">
                                    <div class="avatar-md mx-auto mb-3">
                                        <div class="avatar-title bg-soft-primary text-primary rounded-circle fs-24">
                                            <i class="ri-apps-line"></i>
                                        </div>
                                    </div>
                                    <h5>No Modules Found</h5>
                                    <p class="text-muted">No modules are currently installed or available.</p>
                                    <button type="button" class="btn btn-primary" onclick="scanModules()">
                                        <i class="ri-refresh-line align-middle me-1"></i> Scan for Modules
                                    </button>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Module Details Modal -->
<div class="modal fade" id="moduleDetailsModal" tabindex="-1" aria-labelledby="moduleDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="moduleDetailsModalLabel">Module Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="moduleDetailsContent">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="configureCurrentModule()">Configure</button>
            </div>
        </div>
    </div>
</div>

<!-- Module Configuration Modal -->
<div class="modal fade" id="moduleConfigModal" tabindex="-1" aria-labelledby="moduleConfigModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="moduleConfigModalLabel">Module Configuration</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="moduleConfigForm">
                <div class="modal-body" id="moduleConfigContent">
                    <!-- Configuration form will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Configuration</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentModuleId = null;

// Module toggle functionality
document.querySelectorAll('.module-toggle').forEach(toggle => {
    toggle.addEventListener('change', function() {
        const moduleId = this.dataset.moduleId;
        const enabled = this.checked;
        
        fetch('/admin/modules/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                module_id: moduleId,
                enabled: enabled
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the module card appearance
                const moduleCard = this.closest('.module-card');
                const avatar = moduleCard.querySelector('.avatar-title');
                const statusBadge = moduleCard.querySelector('.badge');
                
                if (enabled) {
                    avatar.className = 'avatar-title bg-soft-success text-success rounded';
                    statusBadge.className = 'badge bg-soft-success text-success';
                    statusBadge.textContent = 'Active';
                } else {
                    avatar.className = 'avatar-title bg-soft-secondary text-secondary rounded';
                    statusBadge.className = 'badge bg-soft-secondary text-secondary';
                    statusBadge.textContent = 'Inactive';
                }
                
                // Show success message
                showToast('Module ' + (enabled ? 'enabled' : 'disabled') + ' successfully!', 'success');
            } else {
                // Revert the toggle
                this.checked = !enabled;
                showToast('Failed to ' + (enabled ? 'enable' : 'disable') + ' module: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            this.checked = !enabled;
            showToast('An error occurred while toggling module', 'error');
        });
    });
});

function filterModules() {
    const search = document.getElementById('searchModules').value.toLowerCase();
    const status = document.getElementById('statusFilter').value;
    const type = document.getElementById('typeFilter').value;
    
    const moduleCards = document.querySelectorAll('.module-card');
    
    moduleCards.forEach(card => {
        const moduleName = card.querySelector('h6').textContent.toLowerCase();
        const moduleDescription = card.querySelector('p').textContent.toLowerCase();
        const moduleStatus = card.querySelector('.badge').textContent.toLowerCase();
        const moduleType = card.querySelectorAll('.badge')[0].textContent.toLowerCase();
        
        const matchesSearch = moduleName.includes(search) || moduleDescription.includes(search);
        const matchesStatus = !status || moduleStatus.includes(status);
        const matchesType = !type || moduleType.includes(type);
        
        card.style.display = (matchesSearch && matchesStatus && matchesType) ? '' : 'none';
    });
}

function scanModules() {
    fetch('/admin/modules/scan', {
        method: 'POST'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Modules scanned successfully! Found ' + data.count + ' modules.', 'success');
            location.reload();
        } else {
            showToast('Failed to scan modules: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('An error occurred while scanning modules', 'error');
    });
}

function exportModules() {
    window.location.href = '/admin/modules/export';
}

function bulkToggle() {
    const enabledModules = document.querySelectorAll('.module-toggle:checked');
    const disabledModules = document.querySelectorAll('.module-toggle:not(:checked)');
    
    const action = confirm('Do you want to enable all modules or disable all modules?') ? 'enable' : 'disable';
    const modules = action === 'enable' ? disabledModules : enabledModules;
    
    if (modules.length === 0) {
        showToast('No modules to ' + action, 'info');
        return;
    }
    
    const moduleIds = Array.from(modules).map(toggle => toggle.dataset.moduleId);
    
    fetch('/admin/modules/bulk-toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            module_ids: moduleIds,
            action: action
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Bulk ' + action + ' completed successfully!', 'success');
            location.reload();
        } else {
            showToast('Failed to bulk ' + action + ' modules: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('An error occurred during bulk operation', 'error');
    });
}

function viewModuleDetails(moduleId) {
    currentModuleId = moduleId;
    
    fetch(`/admin/modules/${moduleId}/details`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const content = document.getElementById('moduleDetailsContent');
            content.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <h6>Basic Information</h6>
                        <table class="table table-sm">
                            <tr><td><strong>Name:</strong></td><td>${data.module.name}</td></tr>
                            <tr><td><strong>Version:</strong></td><td>${data.module.version}</td></tr>
                            <tr><td><strong>Author:</strong></td><td>${data.module.author}</td></tr>
                            <tr><td><strong>Type:</strong></td><td><span class="badge bg-soft-info">${data.module.type}</span></td></tr>
                            <tr><td><strong>Status:</strong></td><td><span class="badge bg-soft-${data.module.enabled ? 'success' : 'secondary'}">${data.module.enabled ? 'Active' : 'Inactive'}</span></td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Description</h6>
                        <p class="text-muted">${data.module.description || 'No description available'}</p>
                        
                        <h6 class="mt-3">Dependencies</h6>
                        <ul class="list-unstyled">
                            ${data.module.dependencies ? data.module.dependencies.map(dep => `<li><i class="ri-check-line text-success me-2"></i>${dep}</li>`).join('') : '<li class="text-muted">No dependencies</li>'}
                        </ul>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <h6>Routes</h6>
                        <div class="border rounded p-3 bg-light">
                            <code class="small">${data.module.routes ? data.module.routes.join('<br>') : 'No routes defined'}</code>
                        </div>
                    </div>
                </div>
            `;
            
            new bootstrap.Modal(document.getElementById('moduleDetailsModal')).show();
        } else {
            showToast('Failed to load module details: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('An error occurred while loading module details', 'error');
    });
}

function configureModule(moduleId) {
    currentModuleId = moduleId;
    
    fetch(`/admin/modules/${moduleId}/config`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const content = document.getElementById('moduleConfigContent');
            content.innerHTML = data.form || '<p class="text-muted">No configuration options available for this module.</p>';
            
            new bootstrap.Modal(document.getElementById('moduleConfigModal')).show();
        } else {
            showToast('Failed to load module configuration: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('An error occurred while loading module configuration', 'error');
    });
}

function configureCurrentModule() {
    if (currentModuleId) {
        configureModule(currentModuleId);
    }
}

function viewModuleRoutes(moduleId) {
    // TODO: Implement routes view
    showToast('Routes view will be implemented', 'info');
}

function viewModuleDependencies(moduleId) {
    // TODO: Implement dependencies view
    showToast('Dependencies view will be implemented', 'info');
}

function reinstallModule(moduleId) {
    if (confirm('Are you sure you want to reinstall this module? This will reset all module data.')) {
        fetch(`/admin/modules/${moduleId}/reinstall`, {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Module reinstalled successfully!', 'success');
                location.reload();
            } else {
                showToast('Failed to reinstall module: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred while reinstalling module', 'error');
        });
    }
}

function uninstallModule(moduleId) {
    if (confirm('Are you sure you want to uninstall this module? This action cannot be undone.')) {
        fetch(`/admin/modules/${moduleId}/uninstall`, {
            method: 'DELETE'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Module uninstalled successfully!', 'success');
                location.reload();
            } else {
                showToast('Failed to uninstall module: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred while uninstalling module', 'error');
        });
    }
}

// Handle module configuration form submission
document.getElementById('moduleConfigForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append('module_id', currentModuleId);
    
    fetch('/admin/modules/save-config', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Module configuration saved successfully!', 'success');
            bootstrap.Modal.getInstance(document.getElementById('moduleConfigModal')).hide();
        } else {
            showToast('Failed to save configuration: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('An error occurred while saving configuration', 'error');
    });
});

// Toast notification function
function showToast(message, type = 'info') {
    // You can implement your own toast notification system here
    alert(message);
}
</script>
