<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserTwoFactorTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type'=>'BIGINT','unsigned'=>true,'auto_increment'=>true],
            'user_id' => ['type'=>'BIGINT','unsigned'=>true],
            'secret' => ['type'=>'VARCHAR','constraint'=>64],
            'enabled_at' => ['type'=>'DATETIME','null'=>true],
            'created_at' => ['type'=>'DATETIME','null'=>false],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['user_id']);
        $this->forge->createTable('user_twofactor');
    }
    public function down()
    {
        $this->forge->dropTable('user_twofactor');
    }
}
