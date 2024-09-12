<?php
namespace Webhook;
class Helper{
	public static function namaHari($request){
		$str = $request->nama_hari;
		switch (true) {
			case ($str==='Mon' || $str===1):
				$hari = 'Senin';
				break;
			case ($str==='Tue' || $str===2):
				$hari = 'Selasa';
				break;
			case ($str==='Wed' || $str===3):
				$hari = 'Rabu';
				break;
			case ($str==='Thu' || $str===4):
				$hari = 'Kamis';
				break;
			case ($str==='Fri' || $str===5):
				$hari = "Jum'at";
				break;
			case ($str==='Sat' || $str===6):
				$hari = 'Sabtu';
				break;
			default:
				$hari = 'Minggu';
				break;
		}
		return $hari;
	}

	public static function dateDetail($request){
		date_default_timezone_set("Asia/Jakarta");
		$arrayHari = [];
		$arrayTanggal = [];
		for($i=1; $i<=3; $i++){ # Ambil tanggal dan nama hari untuk 3 hari kedepan, dari tanggal sekarang
			$ts = strtotime("today +$i day");
			$dayInNum = date('N',$ts);
			array_push($arrayHari, $dayInNum);
			$request->merge(['nama_hari'=>(int)$dayInNum]);
			$arrayTanggal[$dayInNum] = (object)[
				'tanggal'=>date('d-m-Y',$ts),
				'nama_hari'=>self::namaHari($request),
			];
		}
		$tsNow = strtotime('now');
		$tsPlus = strtotime('now +3day');
		$request->merge([
			'array_hari'=>implode(",",$arrayHari),
			'tanggal_detail'=>$arrayTanggal,
			'ts_now'=>$tsNow,
			'ts_plus'=>$tsPlus,
			'dt_now'=>date('Y-m-d'),
			'dt_plus'=>date('Y-m-d',$tsPlus),
		]);
	}
}