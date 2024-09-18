<?php
namespace App\Helpers;

# Library / package
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
# Models
use App\Http\Models\JadwalDokterInternal;
use App\Http\Models\Rsu_Bridgingpoli;

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
	public static function catchError($request){
		$keyForLog = ['url','file','method','message','line','data']; # Declar key param log, tambahkan value di baris ini jika ingin menambah parameter untuk log
		$payload = [];
		# Modify params start
		foreach($keyForLog as $k => $v){
			if(isset($request->log_payload[$v])){
				$message = $request->log_payload[$v];
			}else{
				switch ($v) {
					case 'url': $message='URL NOT SET'; break;
					case 'file': $message='FILE NOT SET'; break;
					// case 'method': $message='METHOD NOT SET'; break;
					case 'message': $message='MESSAGE NOT SET'; break;
					default: $message='-'; break;
				}
			}
			if($message!=='-'){
				$payload[$v] = $message;
			}
		}
		Log::error(json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
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

	public static function randomDokter($request){
		// $date = date('N',strtotime($request->tanggalPeriksa));
		$date = date('Y-m-d',strtotime($request->tanggalPeriksa));
		$where = [
			'date' => $date,
			'is_active' => true,
			'kode_poli_rs' => $request->kodePoli,
		];
		if($request->jenis_pembayaran=='BPJS'){ # Jika pasien BPJS
			$where['is_bpjs'] = true;
		}

		$jadwal = JadwalDokterInternal::where($where)->get();
		if(count($jadwal)>0){ # Jadwal di tanggal tersebut ada
			$jadwal = collect($jadwal);
			$filter = $jadwal->filter(function($item){
				return $item->status_pilih==false;
			});
			$jadwal = $filter->values();
			if(count($jadwal)>0){ # Jika "status_pilih" masih ada yg bernilai false
				$dokter = $jadwal->random();
				$request->merge(['id'=>$dokter->id]);
				JadwalDokterInternal::updateToTrue($request);
				return response()->json([
					'metadata' => [
						'code' => 200,
						'message' => 'Ok'
					],
					'response' => $dokter,
				]);
			}

			# Jika tidak ada, lakukan update "status_pilih", set menjadi false, kemudian pilih ulang
			JadwalDokterInternal::where($where)->update(['status_pilih'=>false]);
			$jadwal = collect(JadwalDokterInternal::where($where)->get());
			$filter = $jadwal->filter(function($item){
				return $item->status_pilih==false;
			});
			$jadwal = $filter->values();
			if(count($jadwal)>0){
				$dokter = $jadwal->random();
				$request->merge(['id'=>$dokter->id]);
				JadwalDokterInternal::updateToTrue($request);
				return response()->json([
					'metadata' => [
						'code' => 200,
						'message' => 'Ok'
					],
					'response' => $dokter,
				]);
			}
		}
		return response()->json([
			'metadata' => [
				'code' => 204,
				'message' => 'No content'
			],
		]);
	}
	public static function namaHariID($request)
	{
		$str = $request->nama_hari_en;
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
	public static function kuotaWa($request)
	{
		return DB::table('bot_pasien as bp')
			->join('bot_data_pasien as bdp', 'bp.id', '=', 'bdp.idBots')
			->whereDate('bp.tgl_periksa', '=', date('Y-m-d', $request->timestamps))
			->where([
				'bp.statusChat' => 99,
				'bdp.kodePoli' => $request->poli_bpjs_id
			])->count();
	}
	public static function numberToTimestamps($request)
	{
		date_default_timezone_set('Asia/Jakarta');
		$date = (int)$request->date_number;
		$numCurrent = (int)date('N',strtotime('now'));
		$numPayload = $date;
		// return "$numCurrent || $numPayload";
		$count =  ($numCurrent <= $numPayload ? '+' : '').($numPayload - $numCurrent).' day';
		// return "$numCurrent || $numPayload || $count";
		$timestamps = strtotime("now $count");
		return $timestamps;
		// return date('d-m-Y',$timestamps);
	}
	public static function dateWhatsApp($request)
	{
		date_default_timezone_set("Asia/Jakarta");
		$arrayHari = [];
		$arrayTanggal = [];
		for($i=1; $i<=3; $i++){ # Ambil tanggal dan nama hari untuk 3 hari kedepan, dari tanggal sekarang
			$ts = strtotime("today +$i day");
			$dayInNum = date('N',$ts);
			array_push($arrayHari, $dayInNum);

			$request->merge(['nama_hari_en'=>(int)$dayInNum]);

			$arrayTanggal[$dayInNum] = (object)[
				'tanggal'=>date('d-m-Y',$ts),
				'nama_hari'=>self::namaHariID($request),
			];

			$request->request->remove('nama_hari_en');
		}
		$tsNow = strtotime('now');
		$tsPlus1 = strtotime('now +1day');
		$tsPlus3 = strtotime('now +3day');
		$request->merge([
			// 'array_hari'=>implode(",",$arrayHari),
			'array_hari'=>$arrayHari,
			'tanggal_detail'=>$arrayTanggal,
			'ts_now'=>$tsNow,
			'ts_plus_1'=>$tsPlus1,
			'ts_plus_3'=>$tsPlus3,
			'dt_now'=>date('Y-m-d'),
			'dt_plus_1'=>date('Y-m-d',$tsPlus1),
			'dt_plus_3'=>date('Y-m-d',$tsPlus3),
		]);
	}
}
