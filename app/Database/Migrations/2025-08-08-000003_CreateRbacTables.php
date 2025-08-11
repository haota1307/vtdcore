<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRbacTables extends Migration
{
    public function up()
    {
        // roles
        $this->forge->addField([
            'id' => ['type'=>'INT','unsigned'=>true,'auto_increment'=>true],
            'slug' => ['type'=>'VARCHAR','constraint'=>60,'unique'=>true],
            'name' => ['type'=>'VARCHAR','constraint'=>120],
            'description' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
            'created_at' => ['type'=>'DATETIME','null'=>true],
            'updated_at' => ['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('roles');

        // permissions
        $this->forge->addField([
            'id' => ['type'=>'INT','unsigned'=>true,'auto_increment'=>true],
            'slug' => ['type'=>'VARCHAR','constraint'=>100,'unique'=>true],
            'name' => ['type'=>'VARCHAR','constraint'=>150],
            'group' => ['type'=>'VARCHAR','constraint'=>80,'null'=>true],
            'created_at' => ['type'=>'DATETIME','null'=>true],
            'updated_at' => ['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('permissions');

        // role_permissions
        $this->forge->addField([
            'role_id' => ['type'=>'INT','unsigned'=>true],
            'permission_id' => ['type'=>'INT','unsigned'=>true],
        ]);
        $this->forge->addKey(['role_id','permission_id'], true);
        $this->forge->addForeignKey('role_id','roles','id','CASCADE','CASCADE');
        $this->forge->addForeignKey('permission_id','permissions','id','CASCADE','CASCADE');
        $this->forge->createTable('role_permissions');

        // user_roles
        $this->forge->addField([
            'user_id' => ['type'=>'INT','unsigned'=>true],
            'role_id' => ['type'=>'INT','unsigned'=>true],
        ]);
        $this->forge->addKey(['user_id','role_id'], true);
        $this->forge->addForeignKey('user_id','users','id','CASCADE','CASCADE');
        $this->forge->addForeignKey('role_id','roles','id','CASCADE','CASCADE');
        $this->forge->createTable('user_roles');
    }

    public function down()
    {
        $this->forge->dropTable('user_roles');
        $this->forge->dropTable('role_permissions');
        $this->forge->dropTable('permissions');
        $this->forge->dropTable('roles');
    }
}
