<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserApiTokens extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type'=>'INT','constraint'=>10,'unsigned'=>true,'auto_increment'=>true],
            'user_id' => ['type'=>'INT','constraint'=>10,'unsigned'=>true],
            'name' => ['type'=>'VARCHAR','constraint'=>60],
            'token_hash' => ['type'=>'VARCHAR','constraint'=>64],
            'abilities' => ['type'=>'TEXT','null'=>true],
            'expires_at' => ['type'=>'DATETIME','null'=>true],
            'created_at' => ['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('token_hash');
        $this->forge->addForeignKey('user_id','users','id','CASCADE','CASCADE');
        $this->forge->createTable('user_api_tokens');
    }
    public function down()
    {
        $this->forge->dropTable('user_api_tokens');
    }
}
