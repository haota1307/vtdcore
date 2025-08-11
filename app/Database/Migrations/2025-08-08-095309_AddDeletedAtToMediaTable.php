<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDeletedAtToMediaTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('media', [
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'created_at'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('media', 'deleted_at');
    }
}
