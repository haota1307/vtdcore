<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSettingsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type'=>'INT','unsigned'=>true,'auto_increment'=>true],
            'scope_type' => ['type'=>'VARCHAR','constraint'=>20], // system|module|user
            'scope_id' => ['type'=>'INT','null'=>true], // user id for user scope
            'module' => ['type'=>'VARCHAR','constraint'=>80,'null'=>true], // module id for module scope
            'key' => ['type'=>'VARCHAR','constraint'=>150],
            'value' => ['type'=>'TEXT','null'=>true],
            'type' => ['type'=>'VARCHAR','constraint'=>30,'null'=>true], // string,int,bool,json
            'created_at' => ['type'=>'DATETIME','null'=>true],
            'updated_at' => ['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['scope_type','scope_id','module','key'], false, true); // composite unique
        $this->forge->createTable('settings');
    }
    public function down()
    {
        $this->forge->dropTable('settings');
    }
}
