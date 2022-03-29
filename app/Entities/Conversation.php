<?php
namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Conversation extends Entity
{
	private $stateA = 'general_greeting';
	private $stateB = 'general_greeting';

	private $userInput;
	private $errorOutput;
	private $conversationModel;

	public function __construct(array $data = null)
	{
		parent::__construct($data);
		$this->conversationModel = new \App\Models\ConversationModel();
	}

	public function getStateA()
	{
		return $this->stateA;
	}

	public function setStateA($state)
	{
		$this->stateA = $state;
	}

	public function getStateB()
	{
		return $this->stateB;
	}

	public function setStateB($state)
	{
		$this->stateB = $state;
	}

	public function setUserInput($input) {
		$this->userInput = $input;
	}

	public function getErrorOutput() {
		return $this->errorOutput;
	}

	public function validateModel()
	{
		if (empty($this->userInput)) {
			$this->errorOutput = 'Будь ласка, введіть модель та марку автомобіля.';
			return false;
		}
		return true;
	}

	public function validateMakeYear()
	{
		if (empty($this->userInput)) {
			$this->errorOutput = 'Будь ласка, введіть рік випуску автомобіля.';
			return false;
		}
		if (!is_numeric($this->userInput) || (int) date('Y') < (int) $this->userInput) {
			$this->errorOutput = 'Будь ласка, введіть коректний рік випуску автомобіля.';
			return false;
		}
		if ((int) $this->userInput < 1996) {
			$this->errorOutput = 'Рік випуску має бути не менше 1996р.';
			return false;
		}
		return true;
	}

	public function validateFuelType()
	{
		$fuel_types = ['бензин', 'дизель', 'електро'];
		if (empty($this->userInput) || !in_array($this->userInput, $fuel_types)) {
			$this->errorOutput = [];
			$this->errorOutput[] = 'Будь ласка, оберіть тип пального.';
			$this->errorOutput[] = $fuel_types;
			return false;
		}
		return true;
	}

	public function validateVinCode()
	{
		if (empty($this->userInput)) {
			$this->errorOutput = 'Будь ласка, введіть він-код або напишіть \'-\', якщо ви його не знаєте.';
			return false;
		}
		if (mb_strlen($this->userInput) != 17 && $this->userInput != '-') {
			$this->errorOutput = 'Він-код має містити 17 символів. Якщо ви його не знаєте, напишіть \'-\'.';
			return false;
		}
		return true;
	}

	public function validateEngineCapacity()
	{
		if (!is_numeric($this->userInput)) {
			$this->errorOutput = 'Будь ласка, вкажіть коректний об\'єм двигуна.';
			return false;
		}
		if ((int) $this->userInput > 0 && (int) $this->userInput < 500) {
			$this->errorOutput = 'Будь ласка, вкажіть об\'єм двигуна повністю у см3.';
			return false;
		}
		return true;
	}

	public function validateTransitState()
	{
		$transit_states = ['Так', 'Ні', 'Невідомо'];
		if (empty($this->userInput) || !in_array($this->userInput, $transit_states)) {
			$this->errorOutput = [];
			$this->errorOutput[] = 'Будь ласка, оберіть відповідь за допомогою кнопок.';
			$this->errorOutput[] = $transit_states;
			return false;
		}
		return true;
	}

	public function validatePhoneNumber()
	{
		if (empty($this->userInput)) {
			$this->errorOutput = 'Будь ласка, введіть ваш номер телефону.';
			return false;
		}
		$phone = $this->userInput;
		$filtered_phone_number = filter_var($phone, FILTER_SANITIZE_NUMBER_INT);
		$phone_to_check = str_replace("-", "", $filtered_phone_number);
		if (strlen($phone_to_check) < 10 || strlen($phone_to_check) > 14) {
			$this->errorOutput = 'Будь ласка, введіть коректний номер телефону.';
			return false;
		}
		return true;
	}

	public function validateName()
	{
		if (empty($this->userInput)) {
			$this->errorOutput = 'Будь ласка, введіть ваше ім\'я.';
			return false;
		}
		return true;
	}

	public function validateContactTimeSlot()
	{
		$time_slots = ['9-12:00', '12-18:00', 'після 18:00'];
		if (empty($this->userInput) || !in_array($this->userInput, $time_slots)) {
			$this->errorOutput = [];
			$this->errorOutput[] = 'Будь ласка, оберіть відповідь за допомогою кнопок.';
			$this->errorOutput[] = $time_slots;
			return false;
		}
		return true;
	}

	public function saveModel()
	{
		$this->model = $this->userInput;
		$this->updateOrInsert();
	}

	public function saveMakeYear()
	{
		$this->make_year = $this->userInput;
		$this->updateOrInsert();
	}

	public function saveFuelType()
	{
		$this->fuel_type = $this->userInput;
		$this->updateOrInsert();
	}

	public function saveVinCode()
	{
		$this->vin_code = $this->userInput;
		$this->updateOrInsert();
	}

	public function saveEngineCapacity()
	{
		$this->engine_capacity = $this->userInput;
		$this->updateOrInsert();
	}

	public function saveTransitState()
	{
		$this->transit_state = $this->userInput;
		$this->updateOrInsert();
	}

	public function savePhoneNumber()
	{
		$this->phone_number = $this->userInput;
		$this->updateOrInsert();
	}

	public function saveName()
	{
		$this->name = $this->userInput;
		$this->updateOrInsert();
	}

	public function saveContactTimeSlot()
	{
		$this->contact_time_slot = $this->userInput;
		$this->updateOrInsert();
	}

	public function save()
	{
		$this->current_state = $this->stateA;
		$this->updateOrInsert();
	}

	public function archive($chat_id) {
		$this->conversationModel->updateKey($chat_id, $chat_id . '/' . date('YmdHis'));
	}

	public function getTaxAmountFormatted()
	{
		$value = 0;
		$years = (int)date('Y') - $this->make_year;
		if ($years == 10) {
			$value += 25;
		} elseif ($years == 11) {
			$value += 50;
		} elseif ($years == 12) {
			$value += 75;
		} elseif ($years == 13) {
			$value += 100;
		} elseif ($years == 14) {
			$value += 125;
		} elseif ($years >= 15) {
			$value += 150;
		}

		if ($this->engine_capacity > 0 && $this->engine_capacity <= 2000) {
			$value += $this->engine_capacity * 0.25;
		} elseif ($this->engine_capacity >= 2001 && $this->engine_capacity <= 3000) {
			$value += $this->engine_capacity * 0.2;
		} elseif ($this->engine_capacity >= 3001 && $this->engine_capacity <= 4000) {
			$value += $this->engine_capacity * 0.25;
		} elseif ($this->engine_capacity >= 4001 && $this->engine_capacity <= 5000) {
			$value += $this->engine_capacity * 0.35;
		} elseif ($this->engine_capacity >= 5001) {
			$value += $this->engine_capacity * 0.5;
		}

		if ($this->fuel_type == 'дизель') {
			$value += 100;
		}

		$value = round($value, 2);
		$this->estimated_price = $value;
		$this->updateOrInsert();

		if ($this->transit_state !== 'Ні') {
			return $value . ' євро. *додаткову інформацію вам повідомить працівник після проведення перевірки';
		}
		return $value . ' євро';
	}

	public function sendSummary()
	{
		$messenger = new \App\Libraries\TelegramMessenger();

		$messenger->sendSystemMessages([
"1. Марка і модель авто: {$this->model}
2. Рік випуску: {$this->make_year}
3. Тип пального: {$this->fuel_type}
4. Він-код: {$this->vin_code}
5. Об'єм двигуна: {$this->engine_capacity} см3
6. Чи було авто 31.12.2020 на Україні в режимі транзиту або тимчасового ввезення: {$this->transit_state}
7. Вартість розмитнення: {$this->estimated_price} євро
8. Номер телефону та ім'я: {$this->phone_number} {$this->name}
9. Час для консультації: {$this->contact_time_slot}"
		]);
	}

	private function updateOrInsert()
	{
		try {
			$this->conversationModel->save($this);
		} catch (\CodeIgniter\Database\Exceptions\DataException $e) {
			return;
		}
	}

}
