<?php
namespace App\Http\Libraries;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Client;
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

		// return '$request->all()';

      $url = "http://192.168.1.8:8191/api/webhook/management-poli/kuota-poli/ignore-poli?data=data";
      $method = "GET";
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
			// CURLOPT_HTTPHEADER     => array(
			// 	"cache-control: no-cache",
			// 	"x-cons-id: $consID",
			// 	"x-signature: $encodedSignature",
			// 	"x-timestamp: $stamp",
			// 	"user_key: $uk",
			// ),
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
		if ($response) {
			// return 'oke';
			return $response;
		}
		return response()->json([
			'metadata' => [
				'code' => 500,
				'message' => $err,
			]
		],500);

		$curl = curl_init();

		curl_setopt_array($curl, array(
			// CURLOPT_URL => 'https://apm.rsuwahidinmojokerto.com:8191/api/send-message?phone=6281335537942&message=Testing',
			// CURLOPT_URL => "localhost:8012/api/webhook/management-poli/$request->url?$request->payload",
			CURLOPT_URL => 'http://localhost:8012/api/webhook/management-poli/kuota-poli/ignore-poli?data=data',
			// CURLOPT_URL => 'http://192.168.2.251:8012/api/webhook/management-poli/kuota-poli/ignore-poli?data=data',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			// CURLOPT_HTTPHEADER => array(
			// 	'Cookie: XSRF-TOKEN=eyJpdiI6IlB0Sll2U1wvVlZXN1N5RGFXdGluQlhBPT0iLCJ2YWx1ZSI6IlVEcVY4elJCWUxSeUwycFdcL1BTczlJRWFSYitPRllwR1JSdXhNSTBQVEpQY2dRSnZNcXEyVGlnUUllYjRBRWU2a2ZVVDZya2MrbHhNeFM1ZkdzQkNudz09IiwibWFjIjoiOTIxYzBhMjAzMzU1NTE2YjJhYzVhYzBiMTcyZmRhNTM5ZWE5Y2M4Mzg0MTcyMDQxNjVkMjcxZDZmZTM1ZDNjMSJ9; dev_apm_session=eyJpdiI6IkRSdTBGK1pMUytaS3drR1dVaXpDWFE9PSIsInZhbHVlIjoiTUZDVDJTc0prWG43WWxiUUEyU0c2NFVzSHlTOFg1VXBtN04zakhCQWRGdkM3emU2bDI2RThMVFwvWkZ5b0hWa3BlS3JaRnB1dFlwSGdxajFCeXNaQndBPT0iLCJtYWMiOiJjYWMyMmE4MzA4NmY5ZmVkOWJiYTNkZTFlYzc5MDgwZGM4ODU3OWJjOTcxMGEzNTA4MDFhNGM2NTdiYTJkYzk3In0%3D'
			// ),
		));

		$response = curl_exec($curl);

		$error = 'Gagal terhubung ke server';
		if (curl_errno($curl)) {
			$error = curl_error($curl);
		}
		curl_close($curl);
		if ($response) {
			// return 'oke';
			return $response;
		}
		return response()->json([
			'metadata' => [
				'code' => 500,
				'message' => $error,
			]
		],500);
		echo $response;




		$curl = curl_init();

		curl_setopt_array($curl, array(
			// CURLOPT_URL => '192.168.1.8:8191/api/member/add',
			CURLOPT_URL => "localhost:8012/api/webhook/management-poli/",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => array(
				// 'is_member' => '1',
				// 'is_new_member' => '1',
				// 'no_ktp' => '1111111111111112',
				// 'no_bpjs' => '',
				// 'no_rm' => '467227',
				// 'nama' => 'TES TES TES',
				// 'foto_identitas'=> new CURLFILE('/path/to/file'),
				// 'foto_profile'=> new CURLFILE('/path/to/file'),
				// 'gender' => '1',
				// 'tgllahir' => '1997-06-06',
				// 'status_hubungan' => 'lainnya',
				// 'alamat' => 'tes2',
				// 'kode_provinsi' => '35',
				// 'kode_kabupaten' => '3525',
				// 'kode_kecamatan' => '352510',
				// 'id_tn' => '118',
				// 'wa' => '0895320894992',
				// 'email' => 'testestes2@gmail.com'
			),
			CURLOPT_HTTPHEADER => array(
				'Authorization: Bearer 1078|ngvuMArVvxgoMqTT0ua2bz42HCbsEpV2cuXcafFh'
			),
		));
		$response = curl_exec($curl);
		curl_close($curl);
		echo $response;
	}
}