<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePasswordResetsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type'=>'BIGINT','unsigned'=>true,'auto_increment'=>true],
            'email' => ['type'=>'VARCHAR','constraint'=>191],
            'token_hash' => ['type'=>'CHAR','constraint'=>64],
            'expires_at' => ['type'=>'DATETIME','null'=>true],
            'used_at' => ['type'=>'DATETIME','null'=>true],
            'created_at' => ['type'=>'DATETIME','null'=>false],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['email']);
        $this->forge->createTable('password_resets');
    }
    public function down()
    {
        $this->forge->dropTable('password_resets');
    }
}
