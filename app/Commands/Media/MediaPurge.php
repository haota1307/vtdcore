<?php
namespace App\Commands\Media;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;

class MediaPurge extends BaseCommand
{
    protected $group = 'Media';
    protected $name = 'media:purge';
    protected $description = 'Permanently delete soft-deleted media older than a retention window.';

    public function run(array $params)
    {
        $days = (int)($params[0] ?? 30);
        $cutoff = date('Y-m-d H:i:s', time() - $days*86400);
        $db = Database::connect();
        $rows = $db->table('media')->where('deleted_at <', $cutoff)->where('deleted_at IS NOT NULL', null, false)->get()->getResultArray();
        $count = 0;
        foreach ($rows as $r) {
            $uploadRoot = WRITEPATH.'uploads'.DIRECTORY_SEPARATOR;
            @unlink($uploadRoot.$r['path']);
            if (!empty($r['variants'])) {
                $vars = json_decode($r['variants'], true);
                if (is_array($vars)) { foreach ($vars as $vp) @unlink($uploadRoot.$vp); }
            }
            $db->table('media')->where('id',$r['id'])->delete();
            $count++;
        }
        CLI::write("Purged $count media records older than $days days.");
    }
}
