<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Models\Antrian;
use App\Http\Models\Bridgingpoli;
use App\Http\Models\BridgingKdPoli;
use App\Http\Models\Rsu_Bridgingpoli;
use App\Http\Models\Rsu_BridgingKdPoli;
use Illuminate\Support\Facades\DB;

class GrafikPelayananController extends Controller
{   
	public function __construct(){
		date_default_timezone_set('Asia/Jakarta');
	}

	public function main(Request $request)
	{
		$data = Rsu_Bridgingpoli::join('tm_poli', 'mapping_poli_bridging.kdpoli_rs', '=', 'tm_poli.KodePoli')
			->whereNotIn('kdpoli',['ALG','UGD','ANU'])
			->groupBy('mapping_poli_bridging.kdpoli_rs')
			->get();
			
		return view('Admin.GrafikPelayanan.main')->with(['data' => $data]);
	}
	
	public function tampilkan(Request $request)
	{
		// GET ESTIMASI WAKTU
		if ($request->task_waktu == 'waktu tunggu' && $request->tampilkan_untuk == 'admisi') {
			$respon = $this->waktuTungguAdmisi($request);
		} else if($request->task_waktu == 'waktu layanan' && $request->tampilkan_untuk == 'admisi') {
			$respon = $this->waktuLayanAdmisi($request);
		} else if($request->task_waktu == 'waktu tunggu' && $request->tampilkan_untuk == 'poli') {
			$respon = $this->waktuTungguPoli($request);
		} else if($request->task_waktu == 'waktu layanan' && $request->tampilkan_untuk == 'poli') {
			$respon = $this->waktuLayanPoli($request);
		}
		// GET JUMLAH PASIEN
		if ($request->tampilkan_untuk == 'poli') {
			$jmlPasien = Antrian::select('tgl_periksa', 
				DB::raw("COUNT(id) as jumlah"),
			)
			->where('kode_poli', $request->poli)
			->whereBetween('tgl_periksa', [$request->min, $request->max])
			->groupBy('tgl_periksa')
			->get();

			$title = Rsu_Bridgingpoli::join('tm_poli', 'mapping_poli_bridging.kdpoli_rs', '=', 'tm_poli.KodePoli')
				->where('kdpoli', $request->poli)->first()->NamaPoli;
		} else {
			$jmlPasien = Antrian::select('tgl_periksa', 
				DB::raw("COUNT(id) as jumlah")
			)
			->whereBetween('tgl_periksa', [$request->min, $request->max])
			->groupBy('tgl_periksa')
			->get();

			$title = 'ADMISI';
		}

		$countDifference = null;
		$previousCount = null;
		foreach ($jmlPasien as $k => $v) {
			if ($previousCount !== null) { # pengecekan jika type datanya null 
				$currentCount = $v->jumlah; # get data hari ini 
				$countDifference = $currentCount - $previousCount; # menghitung jumlah pasien hari ini - jumlah pasien sebelumnya 
			}
			$previousCount = $v->jumlah; # default null

			$difference = null;
			if (!empty($countDifference)) {
				if (substr($countDifference,0,1) == '-') {
					$difference = "Pasien ".str_replace("-","Berkurang ",$countDifference)." jiwa dari sebelumnya";
				} else {
					$difference = "Pasien bertambah ".$countDifference." jiwa dari sebelumnya";
				}
			}

			$v->difference = $difference;
			$v->countDifference = $countDifference;
			$v->estimasi = $respon[$k];
			$v->title = $title;
		}

		if ($respon) {
			$data = [
				'code' => 200,
				'status' => 'success',
				'message' => 'Berhasil!',
				'jumlahPasien' => $jmlPasien,
			];
		} else {
			$data = [
				'code' => 201, 
				'status' => 'error', 
				'message' => 'Data Tidak Ditemukan!', 
				'jumlahPasien' => '',
			];
		}

		return $data;
	}

	public function waktuTungguAdmisi($request)
	{
		if ($request->has('min') || $request->has('max')) {
            $from = $request->min;
            $to = $request->max;
            $dataToday = DB::table('task_id')->whereBetween('tanggal_insert', [$from, $to])
                ->select('kode_booking', 'tanggal_insert')
                ->groupBy('kode_booking')
                ->get();
        }
		$timeCur = 0;
		$count = 0;
		$arrData = [];
		$initDate = "";
		$i = 0;
		foreach ($dataToday as $key => $v) {
			$taskId1 	= DB::table('task_id')->where('kode_booking', $v->kode_booking)->where('task_id', 1)->where('tanggal_insert', $v->tanggal_insert)->first();
			$taskId2 	= DB::table('task_id')->where('kode_booking', $v->kode_booking)->where('task_id', 2)->where('tanggal_insert', $v->tanggal_insert)->first();

			if (!empty($taskId1) && !empty($taskId2)) {
				if($initDate==""){ # Replace value $initDate jika value ""{kosong}
					$initDate = $v->tanggal_insert;
				}
				$count++;
				$time1 = strtotime("+7 hours",$taskId1->timestamp/1000);
				$time2 = strtotime("+7 hours",$taskId2->timestamp/1000);
				$diff = $time2 - $time1;
				$timeCur += $diff;
				if($initDate==$v->tanggal_insert){
					$arrData[$i] = [$initDate,$count,$timeCur];
				}else{
					$initDate = $v->tanggal_insert; # Replace $initDate untuk group data per tanggal
					$count=1; # Reset $count ke 1 untuk jumlah pasien per hari
					$timeCur=0;
					$i++;
					$timeCur+=$diff; # Reset $timeCur jadi 0 kemudian increment lagi
					$arrData[$i] = [$initDate,$count,$timeCur];
				}
			}
		}
		$arrTimeCur = [];
		foreach ($arrData as $k => $val) {
			$diffCur = round($val[2]/$val[1]);
            $jam   = floor($diffCur / (60 * 60));	
            $menit = floor(($diffCur-$jam*(60*60))/60);
            $detik = $diffCur % 60;
			$arrTimeCur[$k] = ($jam<10?"0$jam":$jam).'H '.($menit<10?"0$menit":$menit).'M '.($detik<10?"0$detik":$detik).'S.'; // jam:menit:detik
		}
		return $arrTimeCur;
	}

	public function waktuLayanAdmisi($request)
	{
		if ($request->has('min') || $request->has('max')) {
            $from = $request->min;
            $to = $request->max;
            $dataToday = DB::table('task_id')->whereBetween('tanggal_insert', [$from, $to])
                ->select('kode_booking', 'tanggal_insert')
                ->groupBy('kode_booking')
                ->get();
        } 
		$timeCur = 0;
		$count = 0;
		$arrData = [];
		$initDate = "";
		$i = 0;
		foreach ($dataToday as $key => $v) {
			$taskId2 = DB::table('task_id')->where('kode_booking', $v->kode_booking)->where('task_id', 2)->where('tanggal_insert', $v->tanggal_insert)->first();
			$taskId3 = DB::table('task_id')->where('kode_booking', $v->kode_booking)->where('task_id', 3)->where('tanggal_insert', $v->tanggal_insert)->first();

			if (!empty($taskId2) && !empty($taskId3)) {
				if($initDate==""){ # Replace value $initDate jika value ""{kosong}
					$initDate = $v->tanggal_insert;
				}
				$count++;
				$time2 = strtotime("+7 hours",$taskId2->timestamp/1000);
				$time3 = strtotime("+7 hours",$taskId3->timestamp/1000);
				$diff = $time3 - $time2;
				$timeCur += $diff;
				if($initDate==$v->tanggal_insert){
					$arrData[$i] = [$initDate,$count,$timeCur];
				}else{
					$initDate = $v->tanggal_insert; # Replace $initDate untuk group data per tanggal
					$count=1; # Reset $count ke 1 untuk jumlah pasien per hari
					$timeCur=0;
					$i++;
					$timeCur+=$diff; # Reset $timeCur jadi 0 kemudian increment lagi
					$arrData[$i] = [$initDate,$count,$timeCur];
				}
			}
		}
		$arrTimeCur = [];
		foreach ($arrData as $k => $val) {
			$diffCur = round($val[2]/$val[1]);
            $jam   = floor($diffCur / (60 * 60));	
            $menit = floor(($diffCur-$jam*(60*60))/60);
            $detik = $diffCur % 60;
			$arrTimeCur[$k] = ($jam<10?"0$jam":$jam).'H '.($menit<10?"0$menit":$menit).'M '.($detik<10?"0$detik":$detik).'S.'; // jam:menit:detik
		}
		return $arrTimeCur;
	}

	public function waktuTungguPoli($request)
	{
		if ($request->has('min') || $request->has('max')) {
            $from = $request->min;
            $to = $request->max;
			$dataToday = Antrian::leftJoin('task_id as ta', 'ta.kode_booking', '=', 'antrian.kode_booking')
				->where('antrian.kode_poli', $request->poli)
				->whereBetween('tgl_periksa', [$from, $to])
				->get();

        } 
		$timeCur = 0;
		$count = 0;
		$arrData = [];
		$initDate = "";
		$i = 0;
		foreach ($dataToday as $key => $v) {
			$taskId3 = DB::table('task_id')->where('kode_booking', $v->kode_booking)->where('task_id', 3)->where('tanggal_insert', $v->tanggal_insert)->first();
			$taskId4 = DB::table('task_id')->where('kode_booking', $v->kode_booking)->where('task_id', 4)->where('tanggal_insert', $v->tanggal_insert)->first();

			if (!empty($taskId3) && !empty($taskId4)) {
				if($initDate==""){ # Replace value $initDate jika value ""{kosong}
					$initDate = $v->tanggal_insert;
				}
				$count++;
				$time3 = strtotime("+7 hours",$taskId3->timestamp/1000);
				$time4 = strtotime("+7 hours",$taskId4->timestamp/1000);
				$diff = $time4 - $time3;
				$timeCur += $diff;
				if($initDate==$v->tanggal_insert){
					$arrData[$i] = [$initDate,$count,$timeCur];
				}else{
					$initDate = $v->tanggal_insert; # Replace $initDate untuk group data per tanggal
					$count=1; # Reset $count ke 1 untuk jumlah pasien per hari
					$timeCur=0;
					$i++;
					$timeCur+=$diff; # Reset $timeCur jadi 0 kemudian increment lagi
					$arrData[$i] = [$initDate,$count,$timeCur];
				}
			}
		}
		$arrTimeCur = [];
		foreach ($arrData as $k => $val) {
			$diffCur = round($val[2]/$val[1]);
            $jam   = floor($diffCur / (60 * 60));	
            $menit = floor(($diffCur-$jam*(60*60))/60);
            $detik = $diffCur % 60;
			$arrTimeCur[$k] = ($jam<10?"0$jam":$jam).'H '.($menit<10?"0$menit":$menit).'M '.($detik<10?"0$detik":$detik).'S.'; // jam:menit:detik
		}
		return $arrTimeCur;
	}

	public function waktuLayanPoli($request)
	{
		if ($request->has('min') || $request->has('max')) {
            $from = $request->min;
            $to = $request->max;
            $dataToday = Antrian::leftJoin('task_id as ta', 'ta.kode_booking', '=', 'antrian.kode_booking')
				->where('antrian.kode_poli', $request->poli)
				->whereBetween('tgl_periksa', [$from, $to])
				->get();
        } 
		$timeCur = 0;
		$count = 0;
		$arrData = [];
		$initDate = "";
		$i = 0;
		foreach ($dataToday as $key => $v) {
			$taskId4 = DB::table('task_id')->where('kode_booking', $v->kode_booking)->where('task_id', 4)->where('tanggal_insert', $v->tanggal_insert)->first();
			$taskId5 = DB::table('task_id')->where('kode_booking', $v->kode_booking)->where('task_id', 5)->where('tanggal_insert', $v->tanggal_insert)->first();

			if (!empty($taskId4) && !empty($taskId5)) {
				if($initDate==""){ # Replace value $initDate jika value ""{kosong}
					$initDate = $v->tanggal_insert;
				}
				$count++;
				$time4 = strtotime("+7 hours",$taskId4->timestamp/1000);
				$time5 = strtotime("+7 hours",$taskId5->timestamp/1000);
				$diff = $time5 - $time4;
				$timeCur += $diff;
				if($initDate==$v->tanggal_insert){
					$arrData[$i] = [$initDate,$count,$timeCur];
				}else{
					$initDate = $v->tanggal_insert; # Replace $initDate untuk group data per tanggal
					$count=1; # Reset $count ke 1 untuk jumlah pasien per hari
					$timeCur=0;
					$i++;
					$timeCur+=$diff; # Reset $timeCur jadi 0 kemudian increment lagi
					$arrData[$i] = [$initDate,$count,$timeCur];
				}
			}
		}
		$arrTimeCur = [];
		foreach ($arrData as $k => $val) {
			$diffCur = round($val[2]/$val[1]);
            $jam   = floor($diffCur / (60 * 60));	
            $menit = floor(($diffCur-$jam*(60*60))/60);
            $detik = $diffCur % 60;
			$arrTimeCur[$k] = ($jam<10?"0$jam":$jam).'H '.($menit<10?"0$menit":$menit).'M '.($detik<10?"0$detik":$detik).'S.'; // jam:menit:detik
		}
		return $arrTimeCur;
	}
}