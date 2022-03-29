<?php

namespace App\Helpers;

use App\Models\ConversationModel;
use App\Entities\Conversation;

class ConversationHelper {

	public static function getConversation($chatId, $message) {
		$conversationModel = new ConversationModel();
		$conversation = $conversationModel->find($response['chat_id']);

		if (empty($conversation)) {
			// Starting brand new conversation
			$conversation = new Conversation();
			$conversation->chat_id = $response['chat_id'];
		} elseif ($conversation->current_state == 'finished' || $response['message'] == '/start') {
			// Conversation finished - need to restart
			$conversation->archive($conversation->chat_id);
			unset($conversation);
			$conversation = new Conversation();
			$conversation->chat_id = $response['chat_id'];
		}

		return $conversation;
	}
}
