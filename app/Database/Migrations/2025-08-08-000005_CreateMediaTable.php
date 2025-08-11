<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMediaTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type'=>'BIGINT','unsigned'=>true,'auto_increment'=>true],
            'disk' => ['type'=>'VARCHAR','constraint'=>30,'default'=>'local'],
            'path' => ['type'=>'VARCHAR','constraint'=>255],
            'original_name' => ['type'=>'VARCHAR','constraint'=>255],
            'mime' => ['type'=>'VARCHAR','constraint'=>120,'null'=>true],
            'size' => ['type'=>'INT','unsigned'=>true,'null'=>true],
            'hash' => ['type'=>'CHAR','constraint'=>40], // sha1
            'width' => ['type'=>'INT','unsigned'=>true,'null'=>true],
            'height' => ['type'=>'INT','unsigned'=>true,'null'=>true],
            'variants' => ['type'=>'TEXT','null'=>true],
            'owner_id' => ['type'=>'INT','unsigned'=>true,'null'=>true],
            'created_at' => ['type'=>'DATETIME','null'=>true],
            'scan_status' => ['type'=>'VARCHAR','constraint'=>30,'null'=>true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('hash');
        $this->forge->createTable('media');
    }
    public function down()
    {
        $this->forge->dropTable('media');
    }
}
