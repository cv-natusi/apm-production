<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RataPelayananController extends Controller
{
    public function __construct(){
		date_default_timezone_set('Asia/Jakarta');
	}

	public function main(Request $request)
    {
        return view('Admin.RataPelayanan.main');
    }

    public function tungguAdmisi(Request $request)
	{
        if ($request->has('tanggal')) {
            $tanggal = $request->tanggal;
        } else {
            $tanggal = date('Y-m-d');
        }
		$dataToday = DB::table('task_id')->where('tanggal_insert', $tanggal)
			->select('kode_booking', 'tanggal_insert')
			->groupBy('kode_booking')
			->get();

		$timeCur = 0;
		$count = 0;
		foreach ($dataToday as $key => $v) {
			$taskId1 = DB::table('task_id')->where('kode_booking', $v->kode_booking)->where('task_id', 1)->where('tanggal_insert', $v->tanggal_insert)->first();
			$taskId2 = DB::table('task_id')->where('kode_booking', $v->kode_booking)->where('task_id', 2)->where('tanggal_insert', $v->tanggal_insert)->first();

            if (!empty($taskId1) && !empty($taskId2)) {
				$count++;
				$time1 = strtotime("+7 hours",$taskId1->timestamp/1000);
				$time2 = strtotime("+7 hours",$taskId2->timestamp/1000);
				$diff = $time2 - $time1;
				$timeCur += $diff;
			}
		}
		if ($timeCur > 0 && $count > 0) {
            $diffCur = round($timeCur/$count);
            $jam   = floor($diffCur / (60 * 60));	
            $menit = floor(($diffCur-$jam*(60*60))/60);
            $detik = $diffCur % 60;
            $timeCurrent =  ($jam<10?"0$jam":$jam).':'.($menit<10?"0$menit":$menit).':'.($detik<10?"0$detik":$detik); // jam:menit:detik
            return $respon = [
                'diff' => $diffCur,
                'estimasi' => $timeCurrent
            ];
        } 

		// 	if (!empty($taskId1) && !empty($taskId2)) {
		// 		$count++;
		// 		$timestamp1 = $taskId1->timestamp/1000;
		// 		$timestamp2 = $taskId2->timestamp/1000;
		// 		$diff = $timestamp2 - $timestamp1;
		// 		$timeCur += $diff;
		// 	}
		// }
		// if ($timeCur > 0 && $count > 0) {
        //     $diffCur = round($timeCur/$count);
        //     $jam   = floor($diffCur / (60 * 60));
        //     $menit = $diffCur - ( $jam * (60 * 60) );
        //     $detik = $diffCur % 60;
        //     $timeCurrent =  ($jam < 10 ? '0'.$jam : $jam) .  ':' . (floor( $menit / 60 ) < 10 ? '0'.floor( $menit / 60 ) : floor( $menit / 60 )) . ':' . ($detik < 10 ? '0'.$detik : $detik); // jam:menit:detik
        //     return $respon = [
        //         'diff' => $diffCur,
        //         'estimasi' => $timeCurrent
        //     ];
        // } 
        return $respon = [
            'diff' => '0',
            'estimasi' => '00:00:00'
        ];
	}
	public function layanAdmisi(Request $request)
	{
        if ($request->has('tanggal')) {
            $tanggal = $request->tanggal;
        } else {
            $tanggal = date('Y-m-d');
        }
		$dataToday = DB::table('task_id')->where('tanggal_insert', $tanggal)
			->select('kode_booking', 'tanggal_insert')
			->groupBy('kode_booking')
			->get();

		$timeCur = 0;
		$count = 0;
		foreach ($dataToday as $key => $v) {
			$taskId2 = DB::table('task_id')->where('kode_booking', $v->kode_booking)->where('task_id', 2)->where('tanggal_insert', $v->tanggal_insert)->first();
			$taskId3 = DB::table('task_id')->where('kode_booking', $v->kode_booking)->where('task_id', 3)->where('tanggal_insert', $v->tanggal_insert)->first();

			if (!empty($taskId3) && !empty($taskId2)) {
                $count++;
				$time1 = strtotime("+7 hours",$taskId2->timestamp/1000);
				$time2 = strtotime("+7 hours",$taskId3->timestamp/1000);
				$diff = $time2 - $time1;
				$timeCur += $diff;
			}
		}
        if ($timeCur > 0 && $count > 0) {
            $diffCur = round($timeCur/$count);
            $jam   = floor($diffCur / (60 * 60));	
            $menit = floor(($diffCur-$jam*(60*60))/60);
            $detik = $diffCur % 60;
            $timeCurrent =  ($jam<10?"0$jam":$jam).':'.($menit<10?"0$menit":$menit).':'.($detik<10?"0$detik":$detik); // jam:menit:detik
            return $respon = [
                'diff' => $diffCur,
                'estimasi' => $timeCurrent
            ];
        }
        return $respon = [
            'diff' => '0',
            'estimasi' => '00:00:00'
        ];
	}
	public function tungguPoli(Request $request)
	{
        if ($request->has('tanggal')) {
            $tanggal = $request->tanggal;
        } else {
            $tanggal = date('Y-m-d');
        }
		$dataToday = DB::table('task_id')->where('tanggal_insert', $tanggal)
			->select('kode_booking', 'tanggal_insert')
			->groupBy('kode_booking')
			->get();

		$timeCur = 0;
		$count = 0;
		foreach ($dataToday as $key => $v) {
			$taskId3 = DB::table('task_id')->where('kode_booking', $v->kode_booking)->where('task_id', 3)->where('tanggal_insert', $v->tanggal_insert)->first();
			$taskId4 = DB::table('task_id')->where('kode_booking', $v->kode_booking)->where('task_id', 4)->where('tanggal_insert', $v->tanggal_insert)->first();

			if (!empty($taskId3) && !empty($taskId4)) {
                $count++;
				$time1 = strtotime("+7 hours",$taskId3->timestamp/1000);
				$time2 = strtotime("+7 hours",$taskId4->timestamp/1000);
				$diff = $time2 - $time1;
				$timeCur += $diff;
			}
		}
		if ($timeCur > 0 && $count > 0) {
            $diffCur = round($timeCur/$count);
            $jam   = floor($diffCur / (60 * 60));	
            $menit = floor(($diffCur-$jam*(60*60))/60);
            $detik = $diffCur % 60;
            $timeCurrent =  ($jam<10?"0$jam":$jam).':'.($menit<10?"0$menit":$menit).':'.($detik<10?"0$detik":$detik); // jam:menit:detik
            return $respon = [
                'diff' => $diffCur,
                'estimasi' => $timeCurrent
            ];
        }
        return $respon = [
            'diff' => '0',
            'estimasi' => '00:00:00'
        ];
	}
	public function layanPoli(Request $request)
	{
        if ($request->has('tanggal')) {
            $tanggal = $request->tanggal;
        } else {
            $tanggal = date('Y-m-d');
        }
		$dataToday = DB::table('task_id')->where('tanggal_insert', $tanggal)
			->select('kode_booking', 'tanggal_insert')
			->groupBy('kode_booking')
			->get();

		$timeCur = 0;
		$count = 0;
		foreach ($dataToday as $key => $v) {
            $taskId4 = DB::table('task_id')->where('kode_booking', $v->kode_booking)->where('task_id', 4)->where('tanggal_insert', $v->tanggal_insert)->first();
			$taskId5 = DB::table('task_id')->where('kode_booking', $v->kode_booking)->where('task_id', 5)->where('tanggal_insert', $v->tanggal_insert)->first();

			if (!empty($taskId5) && !empty($taskId4)) {
                $count++;
				$time1 = strtotime("+7 hours",$taskId4->timestamp/1000);
				$time2 = strtotime("+7 hours",$taskId5->timestamp/1000);
				$diff = $time2 - $time1;
				$timeCur += $diff;
			}
		}
		if ($timeCur > 0 && $count > 0) {
            $diffCur = round($timeCur/$count);
            $jam   = floor($diffCur / (60 * 60));	
            $menit = floor(($diffCur-$jam*(60*60))/60);
            $detik = $diffCur % 60;
            $timeCurrent =  ($jam<10?"0$jam":$jam).':'.($menit<10?"0$menit":$menit).':'.($detik<10?"0$detik":$detik); // jam:menit:detik
            return $respon = [
                'diff' => $diffCur,
                'estimasi' => $timeCurrent
            ];
        }
        return $respon = [
            'diff' => '0',
            'estimasi' => '00:00:00'
        ];
	}
    public function tungguFarmasi(Request $request)
	{
        // if ($request->has('tanggal')) {
        //     $tanggal = $request->tanggal;
        // } else {
        //     $tanggal = date('Y-m-d');
        // }
		// $dataToday = DB::table('task_id')->where('tanggal_insert', $tanggal)
		// 	->select('kode_booking', 'tanggal_insert')
		// 	->groupBy('kode_booking')
		// 	->get();

		// $timeCur = 0;
		// $count = 0;
		// foreach ($dataToday as $key => $v) {
        //     $taskId5 = DB::table('task_id')->where('kode_booking', $v->kode_booking)->where('task_id', 5)->where('tanggal_insert', $v->tanggal_insert)->first();
        //     $taskId6 = DB::table('task_id')->where('kode_booking', $v->kode_booking)->where('task_id', 6)->where('tanggal_insert', $v->tanggal_insert)->first();

		// 	if (!empty($taskId5) && !empty($taskId6)) {
		// 		$count++;
		// 		$timestamp5 = $taskId5->timestamp/1000;
		// 		$timestamp6 = $taskId6->timestamp/1000;
		// 		$diff = $timestamp6 - $timestamp5;
		// 		$timeCur += $diff;
		// 	}
		// }
        // // return $timeCur;
        // // return $count;
        // if ($timeCur > 0 && $count > 0) {
        //     $diffCur = round($timeCur/$count);
        //     $jam   = floor($diffCur / (60 * 60));
        //     $menit = $diffCur - ( $jam * (60 * 60) );
        //     $detik = $diffCur % 60;
        //     $timeCurrent =  ($jam < 10 ? '0'.$jam : $jam) .  ':' . (floor( $menit / 60 ) < 10 ? '0'.floor( $menit / 60 ) : floor( $menit / 60 )) . ':' . ($detik < 10 ? '0'.$detik : $detik); // jam:menit:detik
        //     return $respon = [
        //         'diff' => $diffCur,
        //         'estimasi' => $timeCurrent
        //     ];
        // } 
        return $respon = [
            'diff' => '0',
            'estimasi' => '00:00:00'
        ];
        
	}
    public function layanFarmasi(Request $request)
	{
        // if ($request->has('tanggal')) {
        //     $tanggal = $request->tanggal;
        // } else {
        //     $tanggal = date('Y-m-d');
        // }
		// $dataToday = DB::table('task_id')->where('tanggal_insert', $tanggal)
		// 	->select('kode_booking', 'tanggal_insert')
		// 	->groupBy('kode_booking')
		// 	->get();

		// $timeCur = 0;
		// $count = 0;
		// foreach ($dataToday as $key => $v) {
        //     $taskId6 = DB::table('task_id')->where('kode_booking', $v->kode_booking)->where('task_id', 6)->where('tanggal_insert', $v->tanggal_insert)->first();
		// 	$taskId7 = DB::table('task_id')->where('kode_booking', $v->kode_booking)->where('task_id', 7)->where('tanggal_insert', $v->tanggal_insert)->first();

		// 	if (!empty($taskId6) && !empty($taskId7)) {
		// 		$count++;
		// 		$timestamp6 = $taskId6->timestamp/1000;
		// 		$timestamp7 = $taskId7->timestamp/1000;
		// 		$diff = $timestamp7 - $timestamp6;
		// 		$timeCur += $diff;
		// 	}
		// }
		// if ($timeCur > 0 && $count > 0) {
        //     $diffCur = round($timeCur/$count);
        //     $jam   = floor($diffCur / (60 * 60));
        //     $menit = $diffCur - ( $jam * (60 * 60) );
        //     $detik = $diffCur % 60;
        //     $timeCurrent =  ($jam < 10 ? '0'.$jam : $jam) .  ':' . (floor( $menit / 60 ) < 10 ? '0'.floor( $menit / 60 ) : floor( $menit / 60 )) . ':' . ($detik < 10 ? '0'.$detik : $detik); // jam:menit:detik
        //     return $respon = [
        //         'diff' => $diffCur,
        //         'estimasi' => $timeCurrent
        //     ];
        // } 
        return $respon = [
            'diff' => '0',
            'estimasi' => '00:00:00'
        ];
	}
    public function layanAdmisiFarmasi(Request $request)
	{
        $diffCur       = 0;
        $request->tanggal = date('Y-m-d');
        $tungguAdmisi  = $this->tungguAdmisi($request);
        $layanAdmisi   = $this->layanAdmisi($request);
        $tungguPoli    = $this->tungguPoli($request);
        $layanPoli     = $this->layanPoli($request);
        // $tungguFarmasi = $this->tungguFarmasi($request);
        // $layanFarmasi  = $this->layanFarmasi($request);
        $diffCur       = $tungguAdmisi['diff'] + $layanAdmisi['diff'] + $tungguPoli['diff'] + $layanPoli['diff'];
        // $diffCur       = $tungguAdmisi['diff'] + $layanAdmisi['diff'] + $tungguPoli['diff'] + $layanPoli['diff'] + $tungguFarmasi['diff'] + $layanFarmasi['diff'];
        $jam   = floor($diffCur / (60 * 60));	
        $menit = floor(($diffCur-$jam*(60*60))/60);
        $detik = $diffCur % 60;
        $timeCurrent =  ($jam<10?"0$jam":$jam).':'.($menit<10?"0$menit":$menit).':'.($detik<10?"0$detik":$detik); // jam:menit:detik
        return $respon = [
            'diff' => $diffCur,
            'estimasi' => $timeCurrent
        ];
	}
    public function tampilkan(Request $request)
    {
        $diffCur  = 0;
        $year     = date('Y', strtotime($request->monthYear));
        $month    = date('m', strtotime($request->monthYear));
        $myDate   = "01-$month-$year";
        $lastDate = Carbon::createFromFormat('d-m-Y', $myDate)->endOfMonth()->format('Y-m-d');
        $data = DB::table('task_id')->whereBetween('tanggal_insert', [$myDate, $lastDate])
			->select('kode_booking', 'tanggal_insert')
			->groupBy('kode_booking')
			->get();

        $timeCur1 = 0;
        $timeCur2 = 0;
        $timeCur3 = 0;
        $timeCur4 = 0;
        $count1 = 0;
        $count2 = 0;
        $count3 = 0;
        $count4 = 0;
        foreach ($data as $key => $v) {
            $taskId1 = DB::table('task_id')->where('kode_booking', $v->kode_booking)->where('task_id', 1)->where('tanggal_insert', $v->tanggal_insert)->first();
            $taskId2 = DB::table('task_id')->where('kode_booking', $v->kode_booking)->where('task_id', 2)->where('tanggal_insert', $v->tanggal_insert)->first();
            $taskId3 = DB::table('task_id')->where('kode_booking', $v->kode_booking)->where('task_id', 3)->where('tanggal_insert', $v->tanggal_insert)->first();
            $taskId4 = DB::table('task_id')->where('kode_booking', $v->kode_booking)->where('task_id', 4)->where('tanggal_insert', $v->tanggal_insert)->first();
            $taskId5 = DB::table('task_id')->where('kode_booking', $v->kode_booking)->where('task_id', 5)->where('tanggal_insert', $v->tanggal_insert)->first();

            if (!empty($taskId1) && !empty($taskId2)) {
                $count1++;
				$time1 = strtotime("+7 hours",$taskId1->timestamp/1000);
				$time2 = strtotime("+7 hours",$taskId2->timestamp/1000);
				$diff1 = $time2 - $time1;
				$timeCur1 += $diff1;
            }
            if (!empty($taskId3) && !empty($taskId2)) {
                $count2++;
				$time2 = strtotime("+7 hours",$taskId2->timestamp/1000);
				$time3 = strtotime("+7 hours",$taskId3->timestamp/1000);
				$diff2 = $time3 - $time2;
				$timeCur2 += $diff2;
			}
            if (!empty($taskId3) && !empty($taskId4)) {
                $count3++;
				$time3 = strtotime("+7 hours",$taskId3->timestamp/1000);
				$time4 = strtotime("+7 hours",$taskId4->timestamp/1000);
				$diff3 = $time4 - $time3;
				$timeCur3 += $diff3;
			}
            if (!empty($taskId5) && !empty($taskId4)) {
                $count4++;
				$time4 = strtotime("+7 hours",$taskId4->timestamp/1000);
				$time5 = strtotime("+7 hours",$taskId5->timestamp/1000);
				$diff4 = $time5 - $time4;
				$timeCur4 += $diff4;
			}
        }
        if ($timeCur1 > 0 && $timeCur2 > 0 && $timeCur3 > 0 && $timeCur4 > 0 && $count1 > 0 && $count2 > 0 && $count3 > 0 && $count4 > 0) {            
            $diffTungguAdmisi = round($timeCur1/$count1);
            $diffLayanAdmisi  = round($timeCur2/$count2);
            $diffTungguPoli   = round($timeCur3/$count3);
            $diffLayanPoli    = round($timeCur4/$count4);
            $diffCur          = $diffTungguAdmisi + $diffLayanAdmisi + $diffTungguPoli + $diffLayanPoli;
            $jam   = floor($diffCur / (60 * 60));	
            $menit = floor(($diffCur-$jam*(60*60))/60);
            $detik = $diffCur % 60;
            $timeCurrent =  ($jam<10?"0$jam":$jam).':'.($menit<10?"0$menit":$menit).':'.($detik<10?"0$detik":$detik); // jam:menit:detik
            return $respon = [
                'diff' => $diffCur,
                'roundEst' => round($timeCurrent),
                'estimasi' => $timeCurrent,
                'code' => 200,
                'bulan' => $month,
                'tahun' => $year
                // 'periode' => $month.' '.$year
            ];
        }
        return $respon = [
            'diff' => '0',
            'estimasi' => '00:00:00',
            'code' => 201,
            'periode' => ''
        ];
    }
}