<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [ 'type'=>'INT','constraint'=>10,'unsigned'=>true,'auto_increment'=>true],
            'username' => ['type'=>'VARCHAR','constraint'=>50,'unique'=>true],
            'email' => ['type'=>'VARCHAR','constraint'=>120,'unique'=>true],
            'password_hash' => ['type'=>'VARCHAR','constraint'=>255],
            'status' => ['type'=>'VARCHAR','constraint'=>20,'default'=>'active'],
            'last_login_at' => ['type'=>'DATETIME','null'=>true],
            'created_at' => ['type'=>'DATETIME','null'=>true],
            'updated_at' => ['type'=>'DATETIME','null'=>true],
            'deleted_at' => ['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('users');
    }
    public function down()
    {
        $this->forge->dropTable('users');
    }
}
