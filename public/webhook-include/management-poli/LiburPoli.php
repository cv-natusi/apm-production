<?php
namespace Webhook\ManagementPoli;

use Webhook\ManagementPoli;
use Webhook\Helper;

class LiburPoli extends ManagementPoli
{
	public static function message($request): string
	{
		$request->merge([
			'url' => 'libur-poli/message',
		]);
		$exec = Helper::curl($request);
		// return json_encode($exec);
		if ($exec && $exec->metadata->code===200) {
			return $exec->response;
		}
		return '';
	}

	public static function ignore($request): string
	{
		$payload = "
			tanggal_berobat=$request->tanggal_berobat
			&metode_ambil=wa
		";
		$replacements = [
			"/\r\n\t\t\t/" => "",  # Ganti "\r\n\t\t\t" dengan string kosong
			"/\r\n\t\t/" => "",    # Ganti "\r\n\t\t" dengan string kosong
		];
		foreach ($replacements as $pattern => $replacement) {
			$payload = preg_replace(
				$pattern,
				$replacement,
				$payload
			);
		}
		$request->merge([
			'url' => 'kuota-poli/ignore-poli',
			'payload' => $payload,
		]);
		$exec = Helper::curl($request);
		if ($exec && $exec->metadata->code===200) {
			return "'".implode("','", $exec->response)."'";
		}
		return "'ALG','UGD','ANU','GIG'";


		$tanggal = $request->tanggal_berobat;
		$dataKuota = self::getKuotaPoli($request);

		// $ignorePoli = "'ALG','UGD','ANU','GIG'"; # Default ignore
		$ignorePoli = ['ALG','UGD','ANU','GIG']; # Default ignore
		foreach($dataKuota as $item){
			$item = (object)$item;
			$total = 0;
			if(
				(
					$item->hari!="" && strtotime($request->tanggal_detail[$item->hari]->tanggal) == strtotime($tanggal)
				)
				|| strtotime($item->tanggal) == strtotime($tanggal)
			){
				$query = "SELECT count(cust_id) as total FROM bot_pasien as bp
					JOIN bot_data_pasien as bdp ON bp.id = bdp.idBots
					WHERE bp.tgl_periksa = '$tanggal'
					AND bp.statusChat='99'
					AND bdp.kodePoli='$item->poli_bpjs_id'
				";
				$res = mysqli_query($request->natusi_apm,$query);
				$total = mysqli_fetch_assoc($res)['total'];
				if($total >= $item->kuota_wa){
					array_push($ignorePoli, $item->poli_bpjs_id);
				}
			}
		}
		return "'".implode("','", $ignorePoli)."'";
	}
}