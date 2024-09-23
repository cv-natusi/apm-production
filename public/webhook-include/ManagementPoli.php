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
		$payload = (object)[];
		$text = "*UNTUK SEMENTARA WAKTU.*\n";
		$text .= "*==============================*\n\n";

		### Pesan libur nasional start
		$request->merge([
			'url' => 'libur-nasional/message',
		]);
		$exec = Helper::curl($request);
		if ($exec && $exec->metadata->code===200) {
			$text .= $exec->response;
			$text .= "\nkami informasikan bahwa seluruh layanan poliklinik di RSUD dr. Wahidin Sudiro Husodo akan ditutup.";
			$text .= "\nLayanan *Poli Hemodialisa akan tetap beroperasi* seperti biasa sesuai jadwal yang telah ditentukan.\n";
			$text .= "\n*==============================*\n\n";
			$isActive = true;

			$payload->libur_nasional_tanggal = json_encode($exec->tanggal);
			$request->merge([
				'url' => 'libur-nasional/ignore-poli',
				'payload' => $payload,
			]);
			$exec = Helper::curl($request);
			if ($exec && $exec->metadata->code===200) {
				$payload->libur_nasional_kode_poli = json_encode($exec->response);
				$request->merge([
					'payload' => $payload
				]);
			}
		}
		### Pesan libur nasional end

		### Pesan Libur poli start
		$request->merge([
			'url' => 'libur-poli/message',
		]);
		$exec = Helper::curl($request);
		if ($exec && $exec->metadata->code===200) {
			$text .= $exec->response;
			$text .= "\n\n*==============================*\n\n";
			$isActive = true;
  
			$payload->data_libur_poli = json_encode($exec->data_libur_poli);
			$request->merge([
				'url' => 'libur-poli/ignore-poli',
				'payload' => $payload,
			]);
		}
		### Pesan Libur poli end

		### Pesan kuota poli start
		$request->merge([
			'url' => 'kuota-poli/message',
		]);
		$exec = Helper::curl($request);
		if ($exec && $exec->metadata->code===200) {
			$text .= $exec->response;
			$text .= "\n*==============================*\n\n";
			$isActive = true;
		}
		### Pesan kuota poli end

		return $isActive ? $text : '';
	}

	public static function ignorePoli($request)
	{
		// $payload = "
		// 	tanggal_berobat=$request->tanggal_berobat
		// 	&metode_ambil=wa
		// ";
		// $replacements = [
		// 	"/\r\n/" => "",  # Ganti "\r\n" dengan string kosong
		// 	"/\t/" => "",    # Ganti "\t" dengan string kosong
		// ];
		// foreach ($replacements as $pattern => $replacement) {
		// 	$payload = preg_replace(
		// 		$pattern,
		// 		$replacement,
		// 		$payload
		// 	);
		// }

		$ignorePoli = ['ALG','UGD','ANU','GIG'];
		$payload = (object) [
			'tanggal_berobat' => $request->tanggal_berobat,
			'metode_ambil' => 'wa'
		];

		### Libur nasional start
		$request->merge([
			'url' => 'libur-nasional/ignore-poli',
			'payload' => $payload,
		]);
		$exec = Helper::curl($request);
		if ($exec && $exec->metadata->code===200) {
			$ignorePoli = array_values(array_unique(array_merge($ignorePoli, $exec->response)));
			return "'".implode("','", $ignorePoli)."'";
		}
		### Libur nasional end

		### Libur poli start
		$request->merge(['url' => 'libur-poli/ignore-poli']);
		$exec = Helper::curl($request);
		if ($exec && $exec->metadata->code===200) {
			$ignorePoli = array_values(array_unique(array_merge($ignorePoli, $exec->response)));
			return "'".implode("','", $ignorePoli)."'";
		}
		### Libur poli end

		### Kuota poli start
		$request->merge([
			'url' => 'kuota-poli/ignore-poli',
			'payload' => $payload,
		]);
		$exec = Helper::curl($request);
		if ($exec && $exec->metadata->code===200) {
			$ignorePoli = array_values(array_unique(array_merge($ignorePoli,$exec->response)));
		}
		### Kuota poli end
		return "'".implode("','", $ignorePoli)."'";
	}
}