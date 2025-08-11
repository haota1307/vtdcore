<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <h1><?= $title ?></h1>
                <p class="alert alert-success"><?= $message ?></p>
                
                <h3>Stats:</h3>
                <ul>
                    <li>Total Users: <?= number_format($stats['total_users']) ?></li>
                    <li>Total Orders: <?= number_format($stats['total_orders']) ?></li>
                    <li>Total Revenue: $<?= number_format($stats['total_revenue'], 2) ?></li>
                    <li>Active Sessions: <?= number_format($stats['active_sessions']) ?></li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html> 