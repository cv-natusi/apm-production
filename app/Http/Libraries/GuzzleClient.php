<?php
namespace App\Http\Libraries;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
// use GuzzleHttp\Exception\BadResponseException;

use TM;
use Illuminate\Support\Facades\Log;

class GuzzleClient
{
	public static function setUpHeader($request)
	{
		// $consId = $request->trustmark_credentials['cons_id'];
		// $signature = $request->trustmark_credentials['signature'];
		// $timestamps = $request->trustmark_credentials['timestamps'];
		// $userKey = $request->trustmark_credentials['user_key'];
		$baseUri = $request->prepare_guzzle['base_uri'];

		return new Client([
			'base_uri' => $baseUri,
			'headers' => [
				'Accept' => 'application/json',
				'Content-Type' => 'application/json',
				// 'x-cons-id' => $consId,
				// 'x-signature' => $signature,
				// 'x-timestamp' => $timestamps,
				// 'user_key' => $userKey,
			],
		]);
	}

	public static function sendRequestTaskId($request)
	{
		$logPayload = [
			'file' => 'GuzzleClient.php',
			'data' => $request->all(),
		];

		try{
			$request->merge([
				'prepare_guzzle' => [
					'base_uri' => 'http://host.docker.internal:8002',
					'time_out' => 10,
				]
			]);

			# Passing signature to setUpHeader()
			$client = self::setUpHeader($request);

			# Limit request api
			$inSeconds = isset($request->prepare_guzzle['time_out']) ? $request->prepare_guzzle['time_out'] : 15;
			$payloadRequest = ['timeout' => $inSeconds];

			if ($request->payload_guzzle['method'] === 'GET') {
				$sendRequest = $client->get($request->payload_guzzle['endpoint'],$payloadRequest);
			} else {
				$payloadRequest['json'] = $request->payload_guzzle['body'];
				$sendRequest = $client->post($request->payload_guzzle['endpoint'],$payloadRequest);
			}

			// $result = json_decode($sendRequest->getBody());
			// $metadata = $result->metadata;
			// $code = $metadata->code;
			$response = json_decode($sendRequest->getBody());
			$code = $response->code;
			return response()->json($response, $code);

			// $checkString = self::checkStr($metadata->message);
			// if($code === 1){
			// 	// $code = $checkString ? 201 : 200;
			// 	$code = $checkString ? $checkString : 200;
			// } else if ($code === 0 || $code < 200) {
			// 	// $code = $checkString ? 409 : 400;
			// 	$code = $checkString ? $checkString : 400;
			// }

			// $payloadResponse = [
			// 	'code' => $code,
			// 	'message' => $response->message,
			// ];

			// if (isset($result->response)) {
			// 	$payloadResponse['data'] = $result->response;
			// }

			return response()->json($payloadResponse, $code);
		} catch(ClientException $e) { # 400-level errors
			$response = $e->getResponse();
			$code = $response->getStatusCode();

			$payloadResponse = [
				'code' => $code,
				'message' => 'Client error',
			];
			if ($e->hasResponse()) {
				$responseBody = json_decode((string) $e->getResponse()->getBody());

				if (isset($responseBody->message)) {
					$payloadResponse['message'] = $responseBody->message;
				}

				if (isset($responseBody->errors)) {
					$payloadResponse['errors'] = $responseBody->errors;
				}
			}

			$logPayload['message'] = $e->getMessage();
			$logPayload['status'] = 'error_client_exception';
			Log::error(json_encode($logPayload, JSON_PRETTY_PRINT));

			return response()->json($payloadResponse, $code);
		} catch(ServerException $e) { # 500-level errors
			$response = $e->getResponse();
			$code = $response->getStatusCode();
			$payloadResponse = [
				'code' => $code,
				'message' => 'API server error!',
			];

			if ($e->hasResponse()) {
				$responseBody = json_decode((string) $e->getResponse()->getBody());

				if (isset($responseBody->message)) {
					$payloadResponse['message'] = $responseBody->message;
				}

				if (isset($responseBody->errors)) {
					$payloadResponse['errors'] = $responseBody->errors;
				}
			}

			$logPayload['message'] = $e->getMessage();
			$logPayload['status'] = 'error_api_server_exception';
			Log::error(json_encode($logPayload, JSON_PRETTY_PRINT));

			return response()->json($payloadResponse, $code);
		}
		// catch(BadResponseException $e){ # for both (it's their superclass)
		// }
		catch(\Exception $e) { # Internal server Error
			$logPayload['message'] = $e->getMessage();
			$logPayload['status'] = 'error_exception';
			Log::error(json_encode($logPayload, JSON_PRETTY_PRINT));

			$message = self::checkStr($e->getMessage()) ? 'Gagal terhubung ke API server' : 'Terjadi kesalahan sistem';

			return response()->json([
				'code' => 500,
				'message' => $message,
			], 500);
		}
	}

	public static function checkStr($string)
	{
		$data = [
			'201' => 'Data berhasil disimpan',
			'409' =>'Data tersebut sudah ada',
			'500' => 'Could not resolve host',
		];
		foreach($data as $key => $val){
			if(stripos($string,$val)!==false){
				return $key;
			}
		}
		return false;
	}
}
