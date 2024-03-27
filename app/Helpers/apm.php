<?php
namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class apm{
	# Custom response start
	public static function custom_response($code, $status, $msg, $data){ # Rest api with http response
		return response()->json([
			'metaData' => [
				'code' => $code,
				'status' => $status,
				'message' => $msg,
			],
			'response' => $data,
		], $code);
	}
	# Custom response end
	# Logging start
	public static function logTemp($param='store'){ # TEMPLATE PARAMETER FOR LOGGING
		# INDEX LOG (0=>title || 1=>status || 2=>errMsg || 3=>errLine || 4=>data)
		$arrLog = ['',false,null,null,null];
		$arrLog[0] = $param=='delete' ? 'DELETE ' : ($param=='update' ? 'UPDATE ' : 'STORE ');
		return $arrLog;
	}
	public static function logging($param=[]){
		# Modify parameter for logging start
		for($i=0; $i<5; $i++){
			$arr[$i] = isset($param[$i]) ? $param[$i] : (
				$i==0 ? 'NO MESSAGES' : (
					$i==1 ? false : '-'
				)
			);
		}
		# Modify parameter for logging end

		$title   = $arr[0];
		$status  = $arr[1]; # Status => true{jika program berhasil}, false{jika program gagal}
		$errMsg  = $arr[2];
		$errLine = $arr[3];
		$data    = $arr[4];

		$res = [
			$title => [
				'messageErr' => $errMsg,
				'line'       => $errLine,
				'data'       => $data,
			]
		];
		if($status){ # If $status => true, unset key
			unset($res[$title]['messageErr'],$res[$title]['line']);
		}
		Log::info(json_encode($res, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
		return true;
	}
	# Logging end
}
