<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Libraries\TelegramMessenger;
use App\Helpers\ConversationHelper;
use App\Libraries\BotStateMachine;

class Telegram extends BaseController
{
	use ResponseTrait;

	/**
	 * Healthcheck
	 */
	public function index()
	{
		return $this->respond('');
	}

	/**
	 * Telegram webhook endpoint
	 */
	public function webhook()
	{
		$incomming = $this->request->getJSON(true);
		try {
			$response = TelegramMessenger::parseIncommingRequest($incomming);
			$response_chat_id = explode('-', $response['chat_id'])[1];
		} catch (\InvalidArgumentException $e) {
			// TODO: proper logging
			return;
		}

		$conversation = ConversationHelper::getConversation($response['chat_id'], $response['message']);

		$botStateMachine = new BotStateMachine($conversation);
		$botStateMachine->getConversation()->setUserInput($response['message']);

		$next_step = $botStateMachine->getPossibleTransitions();
		$error = $botStateMachine->getConversation()->getErrorOutput();
		if (empty($next_step) && !empty($error)) {
			return $this->respondIntoTelegram($response_chat_id, $error);
		}

		$next_step = array_values($next_step);
		// Since statemachine has only one step to go to next - assuming that we always take first posible next step
		$botStateMachine->apply($next_step[0]);
		return $this->respondIntoTelegram($response_chat_id, $botStateMachine->getUserOutput());
	}

}
