<?php
namespace App\Config;

use CodeIgniter\Config\BaseConfig;

class AuthSecurity extends BaseConfig
{
    public int $loginMaxAttempts = 5;
    public int $loginDecayMinutes = 15; // window

    // Generic rate limit per key (requests per window)
    public int $genericLimit = 100;
    public int $genericWindowSeconds = 60;

    // Endpoint-specific overrides [routePattern => [limit, windowSeconds]]
    public array $endpointLimits = [
        'POST /media/upload' => [10, 60],
        'POST /auth/tokens' => [5, 300],
    ];

    // Password policy
    public int $passwordMinLength = 8;
    public bool $passwordRequireNumber = true;
    public bool $passwordRequireSymbol = true;
    public bool $passwordRequireUpper = true;
}
