<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Forge;
use CodeIgniter\Database\Migration;

class AddInfoToUsersTable extends Migration
{
    /**
     * @var string[]
     */
    private array $tables;

    public function __construct(?Forge $forge = null)
    {
        parent::__construct($forge);

        /** @var \Config\Auth $authConfig */
        $authConfig = config('Auth');
        $this->tables = $authConfig->tables;
    }

    public function up()
    {
        $fields = [
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 99,
                'null' => false,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 99,
                'null' => false,
            ],
            'mobile' => [
                'type' => 'VARCHAR',
                'constraint' => 99,
                'null' => false,
            ],
            'address' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
                'null' => false,
            ],

        ];
        $this->forge->addColumn($this->tables['users'], $fields);
    }

    public function down()
    {
        $fields = [
            'name', 'email', 'mobile', 'address',
        ];
        $this->forge->dropColumn($this->tables['users'], $fields);
    }
}
