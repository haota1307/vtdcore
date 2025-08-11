<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRefreshTokens extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'=>['type'=>'BIGINT','unsigned'=>true,'auto_increment'=>true],
            'user_id'=>['type'=>'BIGINT','unsigned'=>true],
            'token'=>['type'=>'VARCHAR','constraint'=>128],
            'expires_at'=>['type'=>'DATETIME','null'=>false],
            'rotated_at'=>['type'=>'DATETIME','null'=>true],
            'revoked_at'=>['type'=>'DATETIME','null'=>true],
            'parent_id'=>['type'=>'BIGINT','unsigned'=>true,'null'=>true],
            'created_at'=>['type'=>'DATETIME','null'=>false],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['token']);
        $this->forge->addKey(['user_id']);
        $this->forge->createTable('refresh_tokens', true);
    }
    public function down()
    {
        $this->forge->dropTable('refresh_tokens', true);
    }
}
