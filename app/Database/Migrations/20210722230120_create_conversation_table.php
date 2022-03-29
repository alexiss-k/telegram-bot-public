<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateConversationTable extends Migration
{
	public function up()
	{
		$this->forge->addField([
				'chat_id' => [
					'type'       => 'VARCHAR',
					'constraint' => '255',
				],
				'model' => [
					'type'       => 'VARCHAR',
					'constraint' => '255',
					'null'       => true,
				],
				'make_year' => [
					'type'       => 'INT',
					'constraint' => 4,
					'null'       => true,
				],
				'fuel_type' => [
					'type'       => 'VARCHAR',
					'constraint' => '20',
					'null'       => true,
				],
				'vin_code' => [
					'type'       => 'VARCHAR',
					'constraint' => '17',
					'null'       => true,
				],
				'engine_capacity' => [
					'type'       => 'INT',
					'constraint' => 5,
					'null'       => true,
				],
				'transit_state' => [
					'type'       => 'VARCHAR',
					'constraint' => 20,
					'null'       => true,
				],
				'phone_number' => [
					'type'       => 'VARCHAR',
					'constraint' => '20',
					'null'       => true,
				],
				'name' => [
					'type'       => 'VARCHAR',
					'constraint' => '100',
					'null'       => true,
				],
				'contact_time_slot' => [
					'type'       => 'VARCHAR',
					'constraint' => '20',
					'null'       => true,
				],
				'estimated_price' => [
					'type'       => 'DECIMAL',
					'constraint' => '15,2',
					'null'       => true,
				],
				'current_state' => [
					'type'       => 'VARCHAR',
					'constraint' => '255',
				],
				'created_at datetime default current_timestamp',
				'updated_at datetime default current_timestamp on update current_timestamp',
		]);
		$this->forge->addKey('chat_id', true);
		$this->forge->createTable('conversations');
	}

	public function down()
	{
		$this->forge->dropTable('conversations');
	}
}