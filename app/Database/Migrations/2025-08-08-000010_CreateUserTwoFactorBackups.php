<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserTwoFactorBackups extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'=>['type'=>'BIGINT','unsigned'=>true,'auto_increment'=>true],
            'user_id'=>['type'=>'BIGINT','unsigned'=>true],
            'code_hash'=>['type'=>'VARCHAR','constraint'=>255],
            'created_at'=>['type'=>'DATETIME','null'=>false],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['user_id']);
        $this->forge->createTable('user_twofactor_backups', true);
    }
    public function down()
    {
        $this->forge->dropTable('user_twofactor_backups', true);
    }
}
