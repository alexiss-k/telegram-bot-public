<?php

namespace App\Models;

use CodeIgniter\Model;

class ConversationModel extends Model {

	protected $table      = 'conversations';
	protected $primaryKey = 'chat_id';
	protected $useAutoIncrement = false;

	protected $returnType = '\App\Entities\Conversation';
	protected $useSoftDeletes = false;

	protected $allowedFields = ['chat_id', 'model', 'make_year', 'fuel_type', 'vin_code', 'engine_capacity', 'transit_state', 'phone_number', 'name', 'contact_time_slot', 'estimated_price', 'current_state'];

	protected $useTimestamps = true;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $dateFormat    = 'datetime';

	public function updateKey($oldKey, $newKey) {
		$sql = "UPDATE {$this->table} SET {$this->primaryKey} = ? WHERE {$this->primaryKey} = ?";
		$this->db->query($sql, [$newKey, $oldKey]);
	}

	public static function getAllForExport() {
		$model = new self();
		$conversations = $model->select('*')->findAll();

		if (empty($conversations)) {
			return [];
		}

		$conversationsForExport = [];
		foreach ($conversations as $conversation)
		{
			$conversationsForExport[] = [
				$conversation->model,
				$conversation->make_year,
				$conversation->fuel_type,
				$conversation->vin_code,
				$conversation->engine_capacity,
				$conversation->transit_state,
				$conversation->estimated_price,
				$conversation->phone_number,
				$conversation->name,
				$conversation->contact_time_slot
			];
		}
		return $conversationsForExport;
	}

	public static function getHeadersForExport() {
		return [
			"Марка і модель авто",
			"Рік випуску",
			"Тип топлива",
			"Він-код",
			"Об'єм двигуна",
			"Чи було авто в транзиті",
			"Вартість розмитнення",
			"Номер телефону",
			"Ім'я",
			"Час для консультації"
		];
	}

}