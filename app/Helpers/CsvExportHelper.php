<?php

namespace App\Helpers;

class CsvExportHelper {

	public static function sendCsvFile($filename, $headers, $dataList) {
		header("Content-Type: application/csv");
		header("Content-Disposition: attachment; filename=$filename");

		$file = fopen('php://output', 'w');

		fputcsv($file, array_values($headers));
		foreach ($dataList as $row)
		{
			fputcsv($file, $row);
		}
		fclose($file);
	}
}
