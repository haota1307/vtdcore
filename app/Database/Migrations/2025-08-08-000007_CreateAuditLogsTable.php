<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAuditLogsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type'=>'BIGINT','unsigned'=>true,'auto_increment'=>true],
            'user_id' => ['type'=>'BIGINT','unsigned'=>true,'null'=>true],
            'action' => ['type'=>'VARCHAR','constraint'=>100],
            'ip' => ['type'=>'VARCHAR','constraint'=>45,'null'=>true],
            'context' => ['type'=>'TEXT','null'=>true],
            'created_at' => ['type'=>'DATETIME','null'=>false],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['user_id']);
        $this->forge->addKey(['action']);
        $this->forge->createTable('audit_logs');
    }
    public function down()
    {
        $this->forge->dropTable('audit_logs');
    }
}
