<?php
namespace App\Http\Libraries;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Client;
use Log;
/*
@params
    $url     string
    $params  array
*/
class Requestor{
	public static function set_curl($url, $params, $method='post', $headers=null, $buildQuery=true){
		$ch = curl_init();
		curl_setopt_array($ch, array(
				CURLOPT_URL  => $url,
				CURLOPT_RETURNTRANSFER  => 1,
				CURLOPT_SSL_VERIFYHOST  => 0,
				CURLOPT_SSL_VERIFYPEER  => 0,
				CURLOPT_TIMEOUT => 120,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_USERAGENT => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
			)
		);
		if($buildQuery){
			$params = http_build_query($params);
		}
		if($method == 'post'){
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		} else {
			curl_setopt($ch, CURLOPT_URL, $url.'?'.$params);
		}
		if(is_array($headers)){
			curl_setopt($ch, CURLOPT_HEADER, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		}

		$response = curl_exec($ch);
		$err = curl_error($ch);
		curl_close($ch);
		if ($err) {
			return "cURL Error #:" . $err;
		} else {
			return $response;
		}
	}

	public static function set_guzzle($url, $params,  $method='post', $headers=null, $contetType = 'application/x-www-form-urlencoded'){
		$gzl = new Client();
		$guz_params = ['verify' => false];
		if(is_array($headers)){
			$guz_params['headers'] = $headers;
		}
		if($method == 'get'){
			$method = 'GET';
			$guz_params['query'] = $params;
		} else {
			$method = 'POST';
			if($contetType == 'application/json'){
				$guz_params['body'] = json_encode($params);
			} else {
				$guz_params['form_params'] = $params;
			}
		}
		$response = $gzl->request($method, $url,$guz_params);
		return $response->getBody()->getContents();
	}

	public static function set_curl_bridging($url, $params, $method='post', $consID, $secretKey, $port, $headers=null, $buildQuery=true){
		date_default_timezone_set('UTC');
		$stamp      = strval(time()-strtotime('1970-01-01 00:00:00'));
		$data       = $consID.'&'.$stamp;

		$signature = hash_hmac('sha256', $data, $secretKey, true);
		$encodedSignature = base64_encode($signature);

		$ch = curl_init();
		curl_setopt_array($ch, array(
				CURLOPT_PORT => $port,
				CURLOPT_URL  => $url,
				CURLOPT_RETURNTRANSFER  => 1,
				CURLOPT_SSL_VERIFYHOST  => 0,
				CURLOPT_SSL_VERIFYPEER  => 0,
				CURLOPT_TIMEOUT => 120,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => $method,
				CURLOPT_USERAGENT => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
				CURLOPT_HTTPHEADER => array(
					"cache-control: no-cache",
					"x-cons-id: ".$consID,
					"x-signature: ".$encodedSignature,
					"x-timestamp: ".$stamp.""
				),
			)
		);
		if($buildQuery){
			$params = http_build_query($params);
		}
		if($method != 'GET'){
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		} else {
			curl_setopt($ch, CURLOPT_URL, $url.'?'.$params);
		}

		if(is_array($headers)){
			curl_setopt($ch, CURLOPT_HEADER, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		}

		$response = curl_exec($ch);
		$err = curl_error($ch);
		curl_close($ch);
		if ($err) {
			return "cURL Error #:" . $err;
		} else {
			return $response;
		}
	}

	public static function set_curl_bridging_new($url, $params, $method, $consID, $secretKey, $port){
		date_default_timezone_set('UTC');
		$stamp      = strval(time()-strtotime('1970-01-01 00:00:00'));
		$data       = $consID.'&'.$stamp;

		$signature = hash_hmac('sha256', $data, $secretKey, true);
		$encodedSignature = base64_encode($signature);

		$ch = curl_init();
		curl_setopt_array($ch, array(
				// CURLOPT_PORT => $port,
				CURLOPT_URL  => $url,
				CURLOPT_RETURNTRANSFER  => 1,
				CURLOPT_SSL_VERIFYHOST  => 0,
				CURLOPT_SSL_VERIFYPEER  => 0,
				CURLOPT_TIMEOUT => 120,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => $method,
				CURLOPT_USERAGENT => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
				CURLOPT_HTTPHEADER => array(
					"cache-control: no-cache",
					"x-cons-id: ".$consID,
					"x-signature: ".$encodedSignature,
					"x-timestamp: ".$stamp.""
				),
			)
		);
		if($method != 'GET'){
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		} else {
			curl_setopt($ch, CURLOPT_URL, $url.'?'.$params);
		}
		$response = curl_exec($ch);
		$err = curl_error($ch);
		curl_close($ch);
		if ($err) {
			return "cURL Error #:" . $err;
		} else {
			return $response;
		}
	}

	public static function set_new_curl_bridging($url, $params, $method='POST', $consID, $secretKey, $headers=null, $buildQuery=true){
		date_default_timezone_set('UTC');
		$stamp      = strval(time()-strtotime('1970-01-01 00:00:00'));
		$data       = $consID.'&'.$stamp;

		$signature = hash_hmac('sha256', $data, $secretKey, true);
		$encodedSignature = base64_encode($signature);
		$key = $consID.$secretKey.$stamp;

		$ch = curl_init();
		curl_setopt_array($ch, array(
			// CURLOPT_PORT => $port,
			CURLOPT_URL  => $url,
			CURLOPT_RETURNTRANSFER  => 1,
			CURLOPT_SSL_VERIFYHOST  => 0,
			CURLOPT_SSL_VERIFYPEER  => 0,
			CURLOPT_TIMEOUT => 120,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => $method,
			CURLOPT_USERAGENT => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
			CURLOPT_HTTPHEADER => array(
				"cache-control: no-cache",
				"x-cons-id: ".$consID,
				"x-signature: ".$encodedSignature,
				"x-timestamp: ".$stamp."",
				"user_key:dd6817bcc763343bde6eafb760f0c596", // DEV
				// "user_key:2079632035f01e757d81a8565b074768", // PROD
				// "user_key: ".env('USER_KEY')."",
			),
		));
		if($method != 'GET'){
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		} else {
			curl_setopt($ch, CURLOPT_URL, $url.'?'.$params);
		}
		$response = curl_exec($ch);
		$err = curl_error($ch);
		curl_close($ch);
		if($err){
			// return "cURL Error #:" . $err;
			$arr = (object)['code'=>'201', 'message' => $err];
			$data = [
				'metaData'=>$arr,
				'response'=>''
			];
			return $data;
		}else{
			$respon = json_decode($response,true);
			if ($respon != NULL) {
				$string = json_encode($respon['response']);
				// FUNGSI DECRYPT
				$encrypt_method = 'AES-256-CBC';
				// hash
				$key_hash = hex2bin(hash('sha256', $key));
				// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
				$iv = substr(hex2bin(hash('sha256', $key)), 0, 16);
				$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key_hash, OPENSSL_RAW_DATA, $iv);
				$response = \LZCompressor\LZString::decompressFromEncodedURIComponent($output);
				$metadata = json_encode($respon['metaData']);
				$data = [
					'metaData'=>$metadata,
					'response'=>$response
				];
				return $data;
			}else{
				return [ 'metaData'=>'Data Tidak Ada', 'response'=>'' ];
			}
		}
	}

	// Sementara tidak digunakan
	public static function set_curl_tes_dwi($url, $params, $method='POST', $consID, $secretKey, $headers=null, $buildQuery=true){
		date_default_timezone_set('UTC');
		$stamp      = strval(time()-strtotime('1970-01-01 00:00:00'));
		$data       = $consID.'&'.$stamp;

		$signature = hash_hmac('sha256', $data, $secretKey, true);
		$encodedSignature = base64_encode($signature);
		$key = $consID.$secretKey.$stamp;

		$ch = curl_init();
		curl_setopt_array($ch, array(
			// CURLOPT_PORT => $port,
			CURLOPT_URL  => $url,
			CURLOPT_RETURNTRANSFER  => 1,
			CURLOPT_SSL_VERIFYHOST  => 0,
			CURLOPT_SSL_VERIFYPEER  => 0,
			CURLOPT_TIMEOUT => 120,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => $method,
			CURLOPT_USERAGENT => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
			CURLOPT_HTTPHEADER => array(
				"cache-control: no-cache",
				"x-cons-id: ".$consID,
				"x-signature: ".$encodedSignature,
				"x-timestamp: ".$stamp."",
				"user_key:dd6817bcc763343bde6eafb760f0c596", // DEV
				// "user_key:2079632035f01e757d81a8565b074768", // PROD
				// "user_key: ".env('USER_KEY')."",
			),
		));
		if($method != 'GET'){
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		} else {
			curl_setopt($ch, CURLOPT_URL, $url.'?'.$params);
		}
		$response = curl_exec($ch);
		$err = curl_error($ch);
		curl_close($ch);
		if($err){
			// return "cURL Error #:" . $err;
			$arr = (object)['code'=>'201', 'message' => $err];
			$data = [
				'metaData'=>$arr,
				'response'=>''
			];
			return $data;
		}else{
			$respon = json_decode($response,true);
			if ($respon != NULL) {
				$string = json_encode($respon['response']);
				// FUNGSI DECRYPT
				$encrypt_method = 'AES-256-CBC';
				// hash
				$key_hash = hex2bin(hash('sha256', $key));
				// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
				$iv = substr(hex2bin(hash('sha256', $key)), 0, 16);
				$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key_hash, OPENSSL_RAW_DATA, $iv);
				$response = \LZCompressor\LZString::decompressFromEncodedURIComponent($output);
				$metadata = json_encode($respon['metaData']);
				$data = [
					'metaData'=>$metadata,
					'response'=>$response
				];
				return $data;
			}else{
				return [ 'metaData'=>'Data Tidak Ada', 'response'=>'' ];
			}
		}
	}

	// public static function setCurlBridg($url, $method='POST', $consID, $secretKey, $uk, $headers=null, $buildQuery=true){
	public static function setCurlBridg($url,$method='POST',$consID,$secretKey,$uk,$params,$case=''){
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
			CURLOPT_TIMEOUT        => 30,
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
		if($response){
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
		}else{
			if($case=='addantrian'){
			Log::info(json_encode(['JUDUL'=>'ANTRIAN ADD GAGAL','DATA'=>$params], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
			}
			return [
				'metaData' => [
					(object)['code'=>'408', 'message' => 'Request timeout'],
				],
				'response' => null,
			];
		}
	}

	public static function setCurlBPJS($url,$method='POST',$consID,$secretKey,$uk,$params){
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
}