<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Http\Models\Identity;
use App\Http\Models\Fitur;
use DB;


abstract class Controller extends BaseController{
	use DispatchesJobs, ValidatesRequests;

	public function __construct(){
		$this->data['identitas'] = Identity::first();
		$this->data['classtutup'] = '';
	}

	public function antrianTracer($idAntri,$from,$to,$status,$UI){
		// $idAntri => id antrian
		// $from => dari mana contoh(kiosk | simapan | wa)
		// $to => mau kemana  contoh(loket | konter  | konter)
		date_default_timezone_set("Asia/Jakarta");
		$cek = DB::connection('mysql')->table('antrian_tracer')
			->where('antrian_id',$idAntri)
			->where('to',$to)
			->first();
		if($UI=='input'){ // INPUT BARU
			if(empty($cek)){
				$insertTracer = [
					'antrian_id'    => $idAntri,
					'from'          => $from,
					'to'            => $to,
					'status_tracer' => $status,
					'tgl'           => date('Y-m-d'),
					'time'          => date('H:i:s')
				];
				$antrianTracer = DB::connection('mysql')->table('antrian_tracer')->insert($insertTracer);
			}
		}else{ // UPDATE status_tracer
			if(!empty($cek)){
				$antrianTracer = DB::connection('mysql')->table('antrian_tracer')
				->where('antrian_id',$idAntri)
				->where('from',$from)
				->where('to',$to)
				->update(['status_tracer'=>$status]);
			}
		}
		if($antrianTracer){
			return true;
		}
		return false;
	}

	public function generateNoAntrianBaru($request)
	{
		$prefix = 'NB';
		if($request->jenis_pasien=='BPJS'){
			$prefix = 'B';
		}
		$length = strlen($prefix)+3;
		$antri = DB::connection('mysql')->table('antrian')
				->select('no_antrian_pbaru')
				->where('tgl_periksa',$request->tglperiksa)
				->whereRaw("LENGTH(no_antrian_pbaru)=$length")
				->where('is_pasien_baru', 'Y')
				->where('no_antrian_pbaru','like',"$prefix%")
				->orderBy('no_antrian_pbaru','desc')->first();
		$num = 0;
		if(!empty($antri)){
			// $num = (int)substr($antri->no_antrian, 1); # Format awal
			$num = (int)substr($antri->no_antrian_pbaru, -3);
		}
		$angkaAntri      = sprintf("%03d",$num+1);
		$next            = "$prefix".$angkaAntri;
		return $next;
	}
}