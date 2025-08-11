<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - VTDevCore</title>
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            background: #f8f9fa;
            padding: 2rem 0;
        }
        .test-card {
            margin-bottom: 1rem;
        }
        .status-success {
            color: #198754;
        }
        .status-error {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">System Test Results</h4>
                    </div>
                    <div class="card-body">
                        <?php foreach ($tests as $testName => $result): ?>
                            <div class="test-card">
                                <h6 class="text-capitalize"><?= str_replace('_', ' ', $testName) ?></h6>
                                <div class="alert alert-<?= $result['status'] === 'success' ? 'success' : 'danger' ?>">
                                    <strong class="status-<?= $result['status'] ?>">
                                        <?= ucfirst($result['status']) ?>:
                                    </strong>
                                    <?= $result['message'] ?>
                                    
                                    <?php if (isset($result['example'])): ?>
                                        <br><small class="text-muted">Example: <?= $result['example'] ?></small>
                                    <?php endif; ?>
                                    
                                    <?php if (isset($result['class'])): ?>
                                        <br><small class="text-muted">Class: <?= $result['class'] ?></small>
                                    <?php endif; ?>
                                    
                                    <?php if (isset($result['test_result'])): ?>
                                        <br><small class="text-muted">Test Result: <?= $result['test_result'] ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        
                        <hr>
                        
                        <div class="text-center">
                            <a href="<?= base_url('admin/auth/login') ?>" class="btn btn-primary">
                                Go to Admin Login
                            </a>
                            <a href="<?= base_url() ?>" class="btn btn-secondary">
                                Back to Home
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
