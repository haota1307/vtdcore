<?php
namespace App\Models;

use CodeIgniter\Model;

class MediaModel extends Model
{
    protected $table = 'media';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'disk','path','original_name','mime','size','hash','width','height','variants','owner_id','created_at','deleted_at','scan_status'
    ];
    protected $returnType = 'array';
    public $useTimestamps = false;
    protected $useSoftDeletes = true;
}
