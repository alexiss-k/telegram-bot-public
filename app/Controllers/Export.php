<?php

namespace App\Controllers;

use App\Models\ConversationModel;
use App\Helpers\CsvExportHelper;

class Export extends BaseController
{
	public function index() {
		CsvExportHelper::sendCsvFile(
			'chat_'.date('Ymd').'.csv',
			ConversationModel::getHeadersForExport(),
			ConversationModel::getAllForExport());
		exit;
	}
}
