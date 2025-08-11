<?php
use App\Config\AdminUI;

if (! function_exists('build_admin_breadcrumbs')) {
    /**
     * Build breadcrumbs array from current request path.
     * - Root (admin) becomes Dashboard label.
     * - Each segment accumulative URL.
     * - Numeric segments treated as ID (e.g., #123) without link.
     */
    function build_admin_breadcrumbs(?string $path = null): array
    {
        $request = service('request');
        $cfg = config(AdminUI::class);
        $path = $path ?? trim($request->getPath(), '/');
        $segments = explode('/', $path);
        if (! $segments || $segments[0] !== 'admin') {
            return [];
        }
        $breadcrumbs = [];
        // Dashboard root
        $breadcrumbs[] = [ 'label' => $cfg->breadcrumbLabels['dashboard'] ?? 'Dashboard', 'url' => base_url('admin') ];
        $accum = 'admin';
        // Process remaining segments
        for ($i = 1; $i < count($segments); $i++) {
            $seg = $segments[$i];
            if ($seg === '') continue;
            $accum .= '/' . $seg;
            $isLast = ($i === count($segments) - 1);
            $label = $cfg->breadcrumbLabels[$seg] ?? (is_numeric($seg) ? '#' . $seg : ucfirst(str_replace(['-','_'],' ', $seg)));
            $entry = ['label' => $label];
            if (! $isLast && ! is_numeric($seg)) {
                $entry['url'] = base_url($accum);
            }
            $breadcrumbs[] = $entry;
        }
        return $breadcrumbs;
    }
}

if (! function_exists('admin_url')) {
    /**
     * Generate admin URL with proper base path
     */
    function admin_url(?string $path = null): string
    {
        $base = base_url('admin');
        if ($path === null) {
            return $base;
        }
        return $base . '/' . ltrim($path, '/');
    }
}

if (! function_exists('format_datetime')) {
    /**
     * Format datetime for display
     */
    function format_datetime(string $datetime): string
    {
        return date('M j, Y g:i A', strtotime($datetime));
    }
}
