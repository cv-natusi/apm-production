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
			'array_hari2'=>$arrayHari,
			'tanggal_detail'=>$arrayTanggal,
			'ts_now'=>$tsNow,
			'ts_plus'=>$tsPlus,
			'dt_now'=>date('Y-m-d'),
			'dt_plus'=>date('Y-m-d',$tsPlus),
		]);
	}

	public static function curl($request)
	{
		header('Content-Type: application/json; charset=utf-8');
		// $url = "https://192.168.1.8:8191/api/webhook/management-poli/$request->url?$request->payload";

		# Production
		$url = "https://192.168.1.8:8191/api/webhook/management-poli/$request->url";
		# Develop
		// $url = "http://localhost/apm-production/public/api/webhook/management-poli/$request->url";

		$ch = curl_init();
		curl_setopt_array($ch, array(
			CURLOPT_URL  => $url,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_TIMEOUT        => 120,
			CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST  => "POST",
			CURLOPT_USERAGENT      => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
			CURLOPT_POSTFIELDS     => $request->payload,
		));
		// curl_setopt($ch, CURLOPT_POST, 1);
		// curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request->payload));
		// curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request->payload));
		// curl_setopt($ch, CURLOPT_HEADER, true);
		// curl_setopt($ch, CURLOPT_URL,$url);

		$response = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		$errMsg = "Gagal terhubung ke server!";
		if ($err = curl_errno($ch)) {
			$errMsg = curl_error($ch);
		}

		curl_close($ch);

		if (in_array($httpcode,[200,204])) {
			return json_decode($response);
		}

		return json_decode(json_encode([
			'metadata' => [
				'code' => 500,
				'message' => $errMsg,
			]
		]));
	}
}