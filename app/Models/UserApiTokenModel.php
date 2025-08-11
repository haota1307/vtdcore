<?php
namespace App\Models;

use CodeIgniter\Model;

class UserApiTokenModel extends Model
{
    protected $table = 'user_api_tokens';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id','name','token_hash','abilities','expires_at','created_at'
    ];
    protected $returnType = 'array';
    public $useTimestamps = false;
}
