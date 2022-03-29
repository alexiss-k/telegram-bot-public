<?php

namespace App\Libraries;

use \Longman\TelegramBot\Request;
use \Longman\TelegramBot\Telegram;
use \Longman\TelegramBot\Entities\Keyboard;

class TelegramMessenger {

	private $botToken;
	private $botName;
	private $systemChatId;

	private $client;

	public function __construct() {
		$config = new \Config\Thirdparty();
		$this->botToken = $config->telegramBotToken;
		$this->botName = $config->telegramBotName;
		$this->systemChatId = $config->telegramResultChatId;
		$this->client = new Telegram($this->botToken, $this->botName);
	}

	public function sendMessage($chat_id, $message) {
		if (!is_array($message)) {
			$message = [$message];
		}
		foreach ($message as $key => $user_message) {
			if (!is_array($user_message) && (!isset($message[$key + 1]) || !is_array($message[$key + 1]))) {
				$data = [
					'chat_id' => $chat_id,
					'text'    => $user_message,
					'reply_markup' => Keyboard::remove()
				];
				Request::sendMessage($data);
			} else {
				$data = [
					'chat_id' => $chat_id,
					'text'    => $user_message,
				];
				$rows = [];
				foreach($message[$key + 1] as $button) {
					$rows[] = [
						['text' => $button]
					];
				}
				$data['reply_markup'] = ['keyboard' => $rows];
				return Request::sendMessage($data);
			}
		}
		return true;
	}

	public function sendSystemMessages($messages) {
		foreach ($messages as $message) {
			$data = [
				'chat_id' => $this->systemChatId,
				'text'    => $message,
			];
			Request::sendMessage($data);
		}
	}

	public staticfunction parseIncommingRequest($request) {
		if (!empty($request['callback_query'])) {
			return [
				'chat_id' => 'chat-' . $request['callback_query']['message']['chat']['id'],
				'message' => $request['callback_query']['message']['text']
			];
		} elseif (!empty($request['message'])) {
			return [
				'chat_id' => 'chat-' . $request['message']['chat']['id'],
				'message' => $request['message']['text']
			];
		}
		throw new \InvalidArgumentException('Incomming request from Telegram cannot be parsed.');
	}

}