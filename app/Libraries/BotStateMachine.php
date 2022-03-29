<?php

namespace App\Libraries;

class BotStateMachine {

	private $_stateMachine;
	private $userOutput;

	public function __construct($conversation) {
		if (!empty($conversation->current_state)) {
			$conversation->setStateA($conversation->current_state);
		} else {
			$conversation->current_state = $conversation->getStateA();
		}
		$config = [
			'graph'         => 'Bot',
			'property_path' => 'stateA',
			'states'        => [
				'general_greeting',
				'model',
				'make_year',
				'fuel_type',
				'vin_code',
				'engine_capacity',
				'transit_state',
				'phone_number',
				'name',
				'contact_time_slot',
				'finished'
			],
			'transitions' => [
				'start' => [
					'from' => ['general_greeting'],
					'to'   => 'model'
				],
				'enter_model' => [
					'from' => ['model'],
					'to'   => 'make_year'
				],
				'enter_make_year' => [
					'from' => ['make_year'],
					'to'   => 'fuel_type'
				],
				'enter_fuel_type' => [
					'from' => ['fuel_type'],
					'to'   => 'vin_code'
				],
				'enter_vin_code' => [
					'from' => ['vin_code'],
					'to'   => 'engine_capacity'
				],
				'enter_engine_capacity' => [
					'from' => ['engine_capacity'],
					'to'   => 'transit_state'
				],
				'enter_transit_state' => [
					'from' => ['transit_state'],
					'to'   => 'phone_number'
				],
				'enter_phone_number' => [
					'from' => ['phone_number'],
					'to'   => 'name'
				],
				'enter_name' => [
					'from' => ['name'],
					'to'   => 'contact_time_slot'
				],
				'enter_contact_time_slot' => [
					'from' => ['contact_time_slot'],
					'to'   => 'finished'
				],
			],
			'callbacks' => [
				'guard' => [
					'guard-model' => [
						'from' => 'model',
						'do' => ['object', 'validateModel']
					],
					'guard-make_year' => [
						'from' => 'make_year',
						'do' => ['object', 'validateMakeYear']
					],
					'guard-fuel_type' => [
						'from' => 'fuel_type',
						'do' => ['object', 'validateFuelType']
					],
					'guard-vin_code' => [
						'from' => 'vin_code',
						'do' => ['object', 'validateVinCode']
					],
					'guard-engine_capacity' => [
						'from' => 'engine_capacity',
						'do' => ['object', 'validateEngineCapacity']
					],
					'guard-transit_state' => [
						'from' => 'transit_state',
						'do' => ['object', 'validateTransitState']
					],
					'guard-phone_number' => [
						'from' => 'phone_number',
						'do' => ['object', 'validatePhoneNumber']
					],
					'guard-name' => [
						'from' => 'name',
						'do' => ['object', 'validateName']
					],
					'guard-contact_time_slot' => [
						'from' => 'contact_time_slot',
						'do' => ['object', 'validateContactTimeSlot']
					]
				],
				'after' => [
					'to-model' => [
						'to' => 'model',
						'do' => function() {
							$this->userOutput = [];
							$this->userOutput[] = 'Привіт, я бот-брокер :) Залиште інформацію про Ваше авто, і я розрахую вартість розмитнення.';
							$this->userOutput[] = 'Введіть марку і модель авто';
						}
					],
					'on-enter_model-save' => [
						'on' => 'enter_model',
						'do' => ['object', 'saveModel']
					],
					'to-make_year' => [
						'to' => 'make_year',
						'do' => function() {
							$this->userOutput = 'Введіть рік випуску авто';
						}
					],
					'on-enter_make_year-save' => [
						'on' => 'enter_make_year',
						'do' => ['object', 'saveMakeYear']
					],
					'to-fuel_type' => [
						'to' => 'fuel_type',
						'do' => function() {
							$this->userOutput = [];
							$this->userOutput[] = 'Оберіть тип пального';
							$this->userOutput[] = ['бензин', 'дизель', 'електро'];
						}
					],
					'on-enter_fuel_type-save' => [
						'on' => 'enter_fuel_type',
						'do' => ['object', 'saveFuelType']
					],
					'to-vin_code' => [
						'to' => 'vin_code',
						'do' => function() {
							$this->userOutput = 'Введіть він-код. Якщо ви його не знаєте, напишіть \'-\'.';
						}
					],
					'on-enter_vin_code-save' => [
						'on' => 'enter_vin_code',
						'do' => ['object', 'saveVinCode']
					],
					'to-engine_capacity' => [
						'to' => 'engine_capacity',
						'do' => function() {
							$this->userOutput = 'Вкажіть об\'єм двигуна у см3';
						}
					],
					'on-enter_engine_capacity-save' => [
						'on' => 'enter_engine_capacity',
						'do' => ['object', 'saveEngineCapacity']
					],
					'to-transit_state' => [
						'to' => 'transit_state',
						'do' => function() {
							$this->userOutput = [];
							$this->userOutput[] = 'Чи було авто до 31.12.2020 в Україні в режимі транзиту або тимчасового ввезення?';
							$this->userOutput[] = ['Так', 'Ні', 'Невідомо'];
						}
					],
					'on-enter_transit_state-save' => [
						'on' => 'enter_transit_state',
						'do' => ['object', 'saveTransitState']
					],
					'to-phone_number' => [
						'to' => 'phone_number',
						'do' => function() {
							$this->userOutput = 'Залиште свій номер телефону';
						}
					],
					'on-enter_phone_number-save' => [
						'on' => 'enter_phone_number',
						'do' => ['object', 'savePhoneNumber']
					],
					'to-name' => [
						'to' => 'name',
						'do' => function() {
							$this->userOutput = 'Залиште ваше ім\'я';
						}
					],
					'on-enter_name-save' => [
						'on' => 'enter_name',
						'do' => ['object', 'saveName']
					],
					'to-contact_time_slot' => [
						'to' => 'contact_time_slot',
						'do' => function() {
							$this->userOutput = [];
							$this->userOutput[] = 'Приблизна вартість розмитнення вашого авто буде становити: ' . $this->getConversation()->getTaxAmountFormatted();
							$this->userOutput[] = 'Точну вартість може надати наш брокер, в який час вам зручно отримати консультацію?';
							$this->userOutput[] = ['9-12:00', '12-18:00', 'після 18:00'];
						}
					],
					'on-enter_contact_time_slot-save' => [
						'on' => 'enter_contact_time_slot',
						'do' => ['object', 'saveContactTimeSlot']
					],
					'to-finished' => [
						'to' => 'finished',
						'do' => function() {
							$this->userOutput = 'Дякую!';
							$this->getConversation()->sendSummary();
						}
					],
					'save-entity' => [
						'on' => ['start', 'enter_model', 'enter_make_year', 'enter_fuel_type', 'enter_vin_code', 'enter_engine_capacity', 'enter_transit_state', 'enter_phone_number', 'enter_name', 'enter_contact_time_slot'],
						'do' => ['object', 'save']
					],
				]
			],
		];
		
		$this->_stateMachine = new \SM\StateMachine\StateMachine($conversation, $config);
	}

	public function getUserOutput() {
		return $this->userOutput;
	}

	public function getState() {
		return $this->_stateMachine->getState();
	}

	public function can($transition) {
		return $this->_stateMachine->can($transition);
	}

	public function apply($transition) {
		return $this->_stateMachine->apply($transition);
	}

	public function getConversation() {
		return $this->_stateMachine->getObject();
	}

	public function getPossibleTransitions() {
		return $this->_stateMachine->getPossibleTransitions();
	}

}
