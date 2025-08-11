<?php
namespace App\Models;

use CodeIgniter\Model;

class PasswordResetModel extends Model
{
    protected $table = 'password_resets';
    protected $primaryKey = 'id';
    protected $allowedFields = ['email','token_hash','expires_at','used_at','created_at'];
    protected $returnType = 'array';
    public $useTimestamps = false;
}
