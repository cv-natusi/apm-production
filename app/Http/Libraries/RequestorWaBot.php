<?php
namespace App\Http\Libraries;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Client;
use Env;
class RequestorWaBot{
	public static function setCurlBridg($url,$method='POST',$consID,$secretKey,$uk,$params)
	{
		date_default_timezone_set('UTC');
		$stamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$data = $consID.'&'.$stamp;

		$signature = hash_hmac('sha256', $data, $secretKey, true);
		$encodedSignature = base64_encode($signature);
		$key = $consID.$secretKey.$stamp;

		$ch = curl_init();
		curl_setopt_array($ch, array(
			CURLOPT_URL  => $url,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_TIMEOUT        => 120,
			CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST  => $method,
			CURLOPT_USERAGENT      => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
			CURLOPT_HTTPHEADER     => array(
				"cache-control: no-cache",
				"x-cons-id: $consID",
				"x-signature: $encodedSignature",
				"x-timestamp: $stamp",
				"user_key: $uk",
			),
		));
		// return json_encode($params);
		if($method != 'GET'){
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
		} else {
			curl_setopt($ch, CURLOPT_URL,$url);
		}
		// return $params;
		// return $method;
		$response = curl_exec($ch);
		$err = curl_error($ch);
		curl_close($ch);
		if ($err) {
			$data = [
				'metaData'=> (object)['code'=>'201', 'message' => $err],
				'response'=> ''
			];
			return $data;
		}else{
			$respon = json_decode($response,true);
			if($respon != NULL){
				if($respon['metadata']['code']==200 || $respon['metadata']['code']==1){
					// FUNGSI DECRYPT
					$encrypt_method = 'AES-256-CBC';
					// hash
					$key_hash = hex2bin(hash('sha256', $key));
					$res = [];
					if(array_key_exists('response',$respon)){
						$string = json_encode($respon['response']);
						// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
						$iv = substr(hex2bin(hash('sha256', $key)), 0, 16);
						$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key_hash, OPENSSL_RAW_DATA, $iv);
						$response = \LZCompressor\LZString::decompressFromEncodedURIComponent($output);
						$res['response'] = json_decode($response);
					}
					$metadata = json_encode($respon['metadata']);
					$res['metaData'] = json_decode($metadata);
					$data = $res;
				}else{
					$res = json_encode($respon['metadata']);
					$data = [
						'metaData' => json_decode($res),
					];
				}
				return $data;
			}else{
				return [
					'metaData' => 'Gagal terhubung ke API BPJS',
					'response' => null
				];
			}
		}
	}

	public static function setCurlBPJS($url,$method='POST',$consID,$secretKey,$uk,$params)
	{
		date_default_timezone_set('UTC');
		$stamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$data = $consID.'&'.$stamp;

		$signature = hash_hmac('sha256', $data, $secretKey, true);
		$encodedSignature = base64_encode($signature);
		$key = $consID.$secretKey.$stamp;

		$ch = curl_init();
		curl_setopt_array($ch, array(
			CURLOPT_URL  => $url,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_TIMEOUT        => 120,
			CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST  => $method,
			CURLOPT_USERAGENT      => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
			CURLOPT_HTTPHEADER     => array(
				"cache-control: no-cache",
				"x-cons-id: $consID",
				"x-signature: $encodedSignature",
				"x-timestamp: $stamp",
				"user_key: $uk",
			),
		));
		// return json_encode($params);
		if($method != 'GET'){
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
		} else {
			curl_setopt($ch, CURLOPT_URL,$url);
		}
		$response = curl_exec($ch);
		$err = curl_error($ch);
		curl_close($ch);
		if ($err) {
			$arr = (object)['code'=>'201', 'message' => $err];
			$data = [
				'metaData'=>$arr,
				'response'=>''
			];
			return $data;
		}else{
			$respon = json_decode($response,true);
			if($respon != NULL){
				if($respon['metaData']['code']==200 || $respon['metaData']['code']==1){
					// FUNGSI DECRYPT
					$encrypt_method = 'AES-256-CBC';
					// hash
					$key_hash = hex2bin(hash('sha256', $key));
					$res = [];
					if(array_key_exists('response',$respon)){
						$string = json_encode($respon['response']);
						// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
						$iv = substr(hex2bin(hash('sha256', $key)), 0, 16);
						$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key_hash, OPENSSL_RAW_DATA, $iv);
						$response = \LZCompressor\LZString::decompressFromEncodedURIComponent($output);
						$res['response'] = json_decode($response);
					}
					$metadata = json_encode($respon['metaData']);
					$res['metaData'] = json_decode($metadata);
					$data = $res;
				}else{
					$res = json_encode($respon['metaData']);
					$data = [
						'metaData' => json_decode($res),
						'response' => ""
					];
				}
				return $data;
			}else{
				return [
					'metaData' => (object)[
						'code' => 404,
						'message' => 'Gagal terhubung ke API BPJS'
					],
					'response' => null
				];
			}
		}
	}

	public static function managementPoli($request)
	{
		if (Env::status() === 'production') {
			$url = "https://192.168.1.8:8191/api/webhook/management-poli/$request->url";
		} else {
			$url = "http://localhost/apm-production/public/api/webhook/management-poli/$request->url";
		}

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
		// return response()->json([
		// 	'metadata' => [
		// 		'code' => 500,
		// 		'message' => $errMsg,
		// 	]
		// ],500);
	}
}