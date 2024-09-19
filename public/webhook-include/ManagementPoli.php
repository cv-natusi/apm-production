<?php
namespace Webhook;

require_once "webhook-include/Helper.php";
use Webhook\Helper;

class ManagementPoli
{
	protected static $namaHari;

	public function __construct($request)
	{
      Helper::dateDetail($request); # Add variable to request object
	}

	public static function messagePoli($request)
	{
		$isActive = false;
		$text = "*Untuk sementara waktu.*\n";
		$text .= "*==============================*\n\n";
		$request->merge([
			'url' => 'libur-poli/message',
		]);
		$exec = Helper::curl($request);
		if ($exec && $exec->metadata->code===200) {
			$text .= $exec->response;
			$text .= "\n\n*==============================*\n\n";
			$isActive = true;
		}

		$request->merge([
			'url' => 'kuota-poli/message',
		]);
		$exec = Helper::curl($request);
		if ($exec && $exec->metadata->code===200) {
			$text .= $exec->response;
			$text .= "\n*==============================*\n\n";
			$isActive = true;
		}

		$request->merge([
			'url' => 'libur-nasional/message',
		]);
		$exec = Helper::curl($request);
		if ($exec && $exec->metadata->code===200) {
			$text .= $exec->response;
			$text .= "Layanan Poli Hemodialisa akan tetap beroperasi seperti biasa sesuai jadwal yang telah ditentukan.";
			$text .= "\n*==============================*\n\n";
			$isActive = true;
		}
		return $isActive ? $text : '';
	}

	public static function ignorePoli($request)
	{
		$payload = "
			tanggal_berobat=$request->tanggal_berobat
			&metode_ambil=wa
		";
		$replacements = [
			"/\r\n/" => "",  # Ganti "\r\n" dengan string kosong
			"/\t/" => "",    # Ganti "\t" dengan string kosong
		];
		foreach ($replacements as $pattern => $replacement) {
			$payload = preg_replace(
				$pattern,
				$replacement,
				$payload
			);
		}

		$ignorePoli = ['ALG','UGD','ANU','GIG'];

		$request->merge([
			'url' => 'kuota-poli/ignore-poli',
			'payload' => $payload,
		]);
		$exec = Helper::curl($request);
		if ($exec && $exec->metadata->code===200) {
			$ignorePoli = array_merge($ignorePoli,$exec->response);
		}

		$request->merge(['url' => 'libur-poli/ignore-poli']);
		$exec = Helper::curl($request);
		if ($exec && $exec->metadata->code===200) {
			$ignorePoli = array_merge($ignorePoli,$exec->response);
		}
		$ignorePoli = array_values(array_unique($ignorePoli));
		return "'".implode("','", $ignorePoli)."'";
	}
}