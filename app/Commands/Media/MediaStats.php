<?php
namespace App\Commands\Media;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\MediaModel;

class MediaStats extends BaseCommand
{
    protected $group = 'Media';
    protected $name = 'media:stats';
    protected $description = 'Show detailed media storage statistics.';

    public function run(array $params)
    {
        $model = new MediaModel();
        
        CLI::write("=== Media Storage Statistics ===", 'green');
        
        // Total files and size
        $totalStats = $model->select('COUNT(*) as count, SUM(size) as total_size')
                           ->first();
        
        $totalFiles = (int)($totalStats['count'] ?? 0);
        $totalSize = (int)($totalStats['total_size'] ?? 0);
        
        CLI::write("Total Files: " . number_format($totalFiles));
        CLI::write("Total Size: " . $this->formatBytes($totalSize));
        
        // By MIME type
        CLI::write("\n=== By File Type ===", 'blue');
        
        $mimeStats = $model->select('mime, COUNT(*) as count, SUM(size) as size')
                          ->groupBy('mime')
                          ->orderBy('size', 'DESC')
                          ->findAll();
        
        foreach ($mimeStats as $stat) {
            $mime = $stat['mime'];
            $count = (int)$stat['count'];
            $size = (int)$stat['size'];
            
            CLI::write(sprintf(
                "%-40s %8s files %12s",
                $mime,
                number_format($count),
                $this->formatBytes($size)
            ));
        }
        
        // By scan status
        CLI::write("\n=== By Scan Status ===", 'blue');
        
        $scanStats = $model->select('scan_status, COUNT(*) as count')
                          ->groupBy('scan_status')
                          ->findAll();
        
        foreach ($scanStats as $stat) {
            $status = $stat['scan_status'] ?: 'not_scanned';
            $count = (int)$stat['count'];
            
            $color = match($status) {
                'clean' => 'green',
                'infected' => 'red',
                default => 'yellow'
            };
            
            CLI::write("$status: $count files", $color);
        }
        
        // Recent uploads
        CLI::write("\n=== Recent Uploads (Last 7 days) ===", 'blue');
        
        $recentStats = $model->select('COUNT(*) as count, SUM(size) as size')
                            ->where('created_at >=', date('Y-m-d H:i:s', time() - 7*86400))
                            ->first();
        
        $recentCount = (int)($recentStats['count'] ?? 0);
        $recentSize = (int)($recentStats['size'] ?? 0);
        
        CLI::write("Files uploaded: " . number_format($recentCount));
        CLI::write("Size uploaded: " . $this->formatBytes($recentSize));
        
        // Storage usage by month
        CLI::write("\n=== Storage by Month ===", 'blue');
        
        $monthlyStats = $model->select('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count, SUM(size) as size')
                             ->groupBy('month')
                             ->orderBy('month', 'DESC')
                             ->limit(12)
                             ->findAll();
        
        foreach ($monthlyStats as $stat) {
            $month = $stat['month'];
            $count = (int)$stat['count'];
            $size = (int)$stat['size'];
            
            CLI::write(sprintf(
                "%-10s %8s files %12s",
                $month,
                number_format($count),
                $this->formatBytes($size)
            ));
        }
    }
    
    private function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
