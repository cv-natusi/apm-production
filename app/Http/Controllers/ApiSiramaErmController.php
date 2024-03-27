<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Traits\KonfirmasiAntrianTraits;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Models\Antrian;
use App\Http\Models\Rsu_Bridgingpoli;
use App\Http\Models\Rsu_Register;
use App\Http\Models\Rsu_setupall;
use App\Http\Models\AntrianFarmasi;
use App\Http\Models\rsu_customer;

class ApiSiramaErmController extends Controller{
	use KonfirmasiAntrianTraits;
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function batalAntrian(Request $request){
		//initial variabel request
		$noAntrian = isset($request->no_antrian) ? $request->no_antrian : "";
		if(empty($noAntrian)){
			return ['status' => 'error','code' => 500, 'message'=>'Nomor Antrian Tidak Terdeteksi'];
		}

		try {
			//update status table antrian
			$antrian = DB::connection('mysql')->table('antrian')
				->where('no_antrian', $noAntrian)
				->where('tgl_periksa', date('Y-m-d'))
				->first();
			if(empty($antrian)){
				return ['status' => 'error','code' => 500, 'message'=>'Data Antrian Tidak Ada'];
			}
			$updateAntrian = DB::connection('mysql')->table('antrian')
				->where('no_antrian', $noAntrian)
				->where('tgl_periksa', date('Y-m-d'))
				->update(['status' => 'batal']);
			if($updateAntrian){
				$hitBpjs = $this->updateTaskBpjs(new Request(["no_antrian" => $antrian->no_antrian, "task_id" => 99]));
				if($hitBpjs['status'] == "success" ){
					return [
						'status' => 'success',
						'code' => 200, 
						'message'=>'Berhasil Membatalkan Antrian No. ' . $antrian->no_antrian
					];
				}else{
					return [
						'status' => 'error',
						'code' => 500, 
						'message'=>'Gagal Membatalkan Antrian No. ' . $antrian->no_antrian, 
						'messageErr' => isset($hitBpjs['messageErr']) ? $hitBpjs['messageErr'] : "" 
					];
				}
			}
		} catch (\Exception $e) {
		}
	}

	public function panggil(Request $request){
		try {
			DB::beginTransaction();
			//masih abu2 punya sirama
			$kode_unit = isset($request->kode_unit) ? $request->kode_unit : "";
			$id_antrean = $request->id_antrean;
			//initial data
			$antrian = Antrian::where('id', $id_antrean)->first();

			$codeCounter = DB::connection('mysql')->table('mapping_poli_bridging as mpb')
				->join('trans_konter_poli as tkp','tkp.poli_id','=','mpb.kdpoli_rs')
				->join('mst_konterpoli as mk','mk.id','=','tkp.konter_poli_id')
				// ->join('users as us','us.id','=','mk.user_id')
				->where('mpb.kdpoli',$antrian->kode_poli)
				->first();
				// return response()->json($codeCounter);
			//getcode counter
			// $codeCounter = Rsu_Bridgingpoli::with('tm_poli.trans_konter_poli.mst_konterpoli')->where('kdpoli', $antrian->kode_poli)
			// 	->first();
			// $uid = $codeCounter->tm_poli->trans_konter_poli->mst_konterpoli->user_id;
			$uid = $codeCounter->user_id;
			$kdCounterC = [['IRM'],['SAR','PAR','GIZ','ANT']];
			$c1 = in_array($antrian->kode_poli, $kdCounterC[0]);
			$c2 = in_array($antrian->kode_poli, $kdCounterC[1]);
			if($c1){
				$userCounter = DB::connection('mysql')->table('users')->where('id',4)->first();
			}else if($c2){
				$userCounter = DB::connection('mysql')->table('users')->where('id',5)->first();
			}else{
				$userCounter = DB::connection('mysql')->table('users')->where('id',$uid)->first();
			}
			// return response()->json($userCounter);

			//update status antrian ke panggilpoli
			$update = DB::connection('mysql')->table('antrian')
				->where('id', $id_antrean)
				->where('status', "antripoli")
				->update(['status' => "panggilpoli"]);
			if($update){
				//jika berhasil update maka insert ke pemanggilan dan update status di antrian tracer jadi 2
				$q = DB::connection('mysql')->table('antrian_tracer')
					->where('antrian_id', $id_antrean);
				$mtd = $antrian->metode_ambil;
				if($mtd=='WA'){
					$q = $q->where('from','wa');
				}else if($mtd=='SIMAPAN'){
					$q = $q->where('from','simapan');
				}else if($mtd=='JKN'){
					$q = $q->where('from','jkn');
				}elseif($mtd=='KIOSK'){
					$q = $q->where('from','counter');
				}
				$antrian_tracer = $q->where('to','poli')
					->update([
						'status_tracer' => "2", 
						'time2' => date('H:i:s')
					]);

				$dataPemanggilan = [
					'antrian_id' => $antrian->id,
					'no_antrian' => $antrian->nomor_antrian_poli,
					'status' => 1,
					'dari' => $userCounter->lv_user,
					'api_sirama_erm' => true,
				];

				//update data pasien sebelumnya ditable pemanggilan untuk ditampilkan
				$update = DB::connection('mysql')->table('pemanggilan')
					->where('dari',$userCounter->lv_user)
					->orderBy('id', 'DESC')
					->update(['tampilkan' => 1]);
				$pemanggilan = DB::connection('mysql')->table('pemanggilan')->insert([$dataPemanggilan]);
				DB::commit();
				return [
					'status'=>'success',
					'code'=>200,
					'message'=>'Pasien dengan Nomor Antrian '.$antrian->nomor_antrian_poli.' berhasil dipanggil',
					"data" => "if"
				];
			}else{
				//jika gagal update maka data sudah ada, cukup update status dipemanggilan untuk recall pasien
				// $pemanggilan = DB::connection('mysql')->table('pemanggilan')
				//             ->where('antrian_id',$antrian->id)
				//             ->where('no_antrian',$antrian->nomor_antrian_poli)
				//             ->update([
				//                 'status' => 1
				//             ]);
				$dataPemanggilan = [
					'antrian_id' => $antrian->id,
					'no_antrian' => $antrian->nomor_antrian_poli,
					'status' => 1,
					'dari' => $userCounter->lv_user,
					'api_sirama_erm' => true,
				];

				//update data pasien sebelumnya ditable pemanggilan untuk ditampilkan
				$update = DB::connection('mysql')->table('pemanggilan')
					->where('dari',$userCounter->lv_user)
					->orderBy('id', 'DESC')
					->update(['tampilkan' => 1]);
				$pemanggilan = DB::connection('mysql')->table('pemanggilan')->insert([$dataPemanggilan]);
				DB::commit();
				return [
					'status'=>'success',
					'code'=>200,
					'message'=>'Pasien dengan Nomor Antrian '.$antrian->nomor_antrian_poli.' berhasil dipanggil ulang',
					"data"=>"else"
				];
			}
		} catch (\Exception $e) {
			DB::rollback();
			return [
				'status'=>'error',
				'code'=>500,
				'message'=>'Gagal Memanggil Pasien Coba Ulangi Kembali',
				'messageerr'=>$e->getMessage(),
			];
		}
	}

	public function updateTaskBpjsSirama(Request $request){
		//initial variabel request
		$jenisResep = '';
		$noAntrian  = $request->no_antrian;
		$taskId 	= $request->task_id;

		//validasi parameter
		if(empty($noAntrian)){
			return ['status' => 'error','code' => 500, 'message'=>'Nomor Antrian Tidak Terdeteksi'];
		}
		if(empty($taskId)){
			return ['status' => 'error','code' => 500, 'message'=>'Task Id Tidak Terdeteksi'];
		}
		//proses update task
		try {
			//ambil data pasien ditable antrian
			DB::beginTransaction();
			$dataPasien =  DB::connection('mysql')->table('antrian_farmasi')
				->where('no_antrian_farmasi', '=', $noAntrian)
				->whereDate('created_at', '=', date('Y-m-d'))
				->first();
			if(empty($dataPasien)){
				return ['status' => 'error','code' => 500, 'message'=>'Data Pasien Tidak Ditemukan'];
			}
			//panggil function dan post data BPJS
			$antreanBpjs = new BridgBpjsController();
			$generateReqUpdateWaktuBPJS = $this->generateReqUpdateWaktuBpjs($dataPasien,$taskId,$jenisResep);
			$postUpdateWaktuBpjs =  $antreanBpjs->updateWaktu(new Request($generateReqUpdateWaktuBPJS));
			// if ($kategori=='erm') {
			// 	$generateReqUpdateWaktuBPJS = $this->generateReqUpdateWaktuBpjs($dataPasien,$taskId,$jenisResep);
			// 	$postUpdateWaktuBpjs =  $antreanBpjs->updateWaktu(new Request($generateReqUpdateWaktuBPJS));
			// } else {
			// 	$taskId = '4';
			// 	$generateReqUpdateWaktuBPJS = $this->generateReqUpdateWaktuBpjs($dataPasien,$taskId,$jenisResep);
			// 	$postUpdateWaktuBpjs =  $antreanBpjs->updateWaktu(new Request($generateReqUpdateWaktuBPJS));
			// 	if ($postUpdateWaktuBpjs['metaData']->code == 200 ){
			// 		$taskId = '5';
			// 		$generateReqUpdateWaktuBPJS2 = $this->generateReqUpdateWaktuBpjs($dataPasien,$taskId,$jenisResep);
			// 		$postUpdateWaktuBpjs2 =  $antreanBpjs->updateWaktu(new Request($generateReqUpdateWaktuBPJS2));
			// 		if ($postUpdateWaktuBpjs2['metaData']->code == 200 ){
			// 			$taskId = '6';
			// 			$generateReqUpdateWaktuBPJS3 = $this->generateReqUpdateWaktuBpjs($dataPasien,$taskId,$jenisResep);
			// 			$postUpdateWaktuBpjs3 =  $antreanBpjs->updateWaktu(new Request($generateReqUpdateWaktuBPJS3));
			// 			if ($postUpdateWaktuBpjs3['metaData']->code != 200 ){
			// 				throw new \Exception($postUpdateWaktuBpjs['metaData']->message, (int)$postUpdateWaktuBpjs['metaData']->code);
			// 				DB::rollback();
			// 			}
			// 		}else{
			// 			throw new \Exception($postUpdateWaktuBpjs['metaData']->message, (int)$postUpdateWaktuBpjs['metaData']->code);
			// 			DB::rollback();
			// 		}
			// 	}
			// }
			//validasi jika gagal update
			if ($postUpdateWaktuBpjs['metaData']->code != 200 ){
				DB::rollback();
				throw new \Exception($postUpdateWaktuBpjs['metaData']->message, (int)$postUpdateWaktuBpjs['metaData']->code);
				return ['status' => 'error', 'code' => 500, 'message' => 'Gagal Update TaskId '.$taskId.' BPJS'];
			}
			DB::commit();
			return ['status' => 'success', 'code' => 200, 'message' => 'Berhasil Update TaskId '.$taskId.' BPJS'];
		} catch (\Exception $th) {
			// savelogsbpjs
			$dataLogs = [
				'taskId' => $taskId,
				'nomorAntrian' => $noAntrian,
				'pasien' => $dataPasien,
				'messageErr' => $th->getMessage(),
			];
			Log::info('updateTaskBpjs - Error :', $dataLogs);
			return ['status' => 'success', 'code' => 500, 'message' => 'Gagal Update TaskId '.$taskId.' BPJS', 'message_err' => $th->getMessage() ];
		}
	}

	public function updateTaskBpjs(Request $request){
		//initial variabel request
		$noAntrian = $request->no_antrian;
		$taskId = $request->task_id;
		$jenisResep = $request->jenisresep;
		//validasi parameter
		if(empty($noAntrian)){
			return ['status' => 'error','code' => 500, 'message'=>'Nomor Antrian Tidak Terdeteksi'];
		}
		if(empty($taskId)){
			return ['status' => 'error','code' => 500, 'message'=>'Task Id Tidak Terdeteksi'];
		}
		//proses update task
		try {
			//ambil data pasien ditable antrian
			$dataPasien =  DB::connection('mysql')->table('antrian')
				->where('nomor_antrian_poli', $noAntrian)
				->where('tgl_periksa', date('Y-m-d'))
				->first();
			if(empty($dataPasien)){
				return ['status' => 'error','code' => 500, 'message'=>'Data Pasien Tidak Ditemukan'];
			}
			//panggil function dan post data BPJS
			$antreanBpjs = new BridgBpjsController();
			$generateReqUpdateWaktuBPJS = $this->generateReqUpdateWaktuBpjs($dataPasien,$taskId,$jenisResep);
			$postUpdateWaktuBpjs =  $antreanBpjs->updateWaktu(new Request($generateReqUpdateWaktuBPJS));
			//validasi jika gagal update
			if ($postUpdateWaktuBpjs['metaData']->code != 200 ){
				throw new \Exception($postUpdateWaktuBpjs['metaData']->message, (int)$postUpdateWaktuBpjs['metaData']->code);
			}
			//save logs bpjs
			$dataLogs = [
				'taskId' => $taskId,
				'nomorAntrian' => $noAntrian,
				'pasien' => $dataPasien,
				'messageErr' => $postUpdateWaktuBpjs['metaData']->message,
			];
			Log::info('updateTaskBpjs - Success :', $dataLogs);
			return ['status' => 'success', 'code' => 200, 'message' => 'Berhasil Update TaskId '.$taskId.' BPJS'];
		} catch (\Exception $th) {
			// savelogsbpjs
			$dataLogs = [
				'taskId' => $taskId,
				'nomorAntrian' => $noAntrian,
				'pasien' => $dataPasien,
				'messageErr' => $th->getMessage(),
			];
			Log::info('updateTaskBpjs - Error :', $dataLogs);
			return ['status' => 'success', 'code' => 500, 'message' => 'Gagal Update TaskId '.$taskId.' BPJS', 'message_err' => $th->getMessage() ];
		}
	}

	public function antreanAddFarmasi(Request $request){
		// return 'api sirama';
		// return $request->all();
		$validate = Validator::make($request->all(),[
			'kodebooking' => 'required',
			'jenisresep' => 'required',
			'nomorantrean' => 'required',
			'keterangan' => 'required'
		],[
			'kodebooking.required' => 'Kode Booking Wajib Di isi',
			'jenisresep.required' => 'Jenis Resep  Wajib Di isi',
			'nomorantrean.required' => 'Nomor Antrean Wajib Di isi',
			'keterangan.required' => 'Keterangan Wajib Di isi',
		]);
		if (!$validate->fails()) {
			try {
				// panggil function dan post data BPJS
				$bridgBpjs = new BridgBpjsController();
				$addAntreanFarmasi =  $bridgBpjs->antreanFarmasiAdd($request);
				//validasi jika gagal update
				if ($addAntreanFarmasi['metaData']->code != 200 ){
					throw new \Exception($addAntreanFarmasi['metaData']->message, (int)$addAntreanFarmasi['metaData']->code);
				}
				//save logs bpjs
				$dataLogs = [
					'messageErr' => $addAntreanFarmasi['metaData']->message,
				];
				Log::info('AddAntreanFarmasi - Success :', $dataLogs);
				return ['status' => 'success', 'code' => 200, 'message' => 'Berhasil Add Antrian Farmasi BPJS'];
			} catch (\Exception $th) {
				// savelogsbpjs
				$dataLogs = [
					'messageErr' => $th->getMessage(),
				];
				Log::info('AddAntreanFarmasi - Error :', $dataLogs);
				return ['status' => 'success', 'code' => 500, 'message' => 'Gagal Add Antrian Farmasi BPJS', 'message_err' => $th->getMessage() ];
			}
		}else{
            return response()->json([
                'metadata' => [
                    'message' => $validate->errors()->all()[0],
                    'code'    => 500,
                ],
                'response' => [],
            ]);
        }
	}

	public function panggilSirama(Request $request){
		try {
			DB::beginTransaction();
			// return 'test';
			$kode_unit = isset($request->kode_unit) ? $request->kode_unit : "";
			$type = ($kode_unit=='LFAR')?'rj1':'rj2';
			$norm = $request->no_rm;
			$noregist = $request->no_register;
			// $jenisResep = '';
			// $taskId = '7';
			//initial data noregist
			$antrean = DB::connection('mysql')->table('antrian')->where('No_Register',$noregist)->first();
			$registrasi = Rsu_Register::where('No_Register',$noregist)->first();
			$kdPoli = Rsu_Bridgingpoli::where('kdpoli_rs',$registrasi->Kode_Poli1)->first()->kdpoli;
			$asuransi = Rsu_setupall::where('groups','Asuransi')->where('subgroups',$registrasi->Kode_Ass)->first()->nilaichar;
			$trResep = DB::connection('dbwahidin')->table('tr_resep_m')->where('No_Register', $noregist)->first();
			$cust = rsu_customer::select('NoKtp')->where('KodeCust',$registrasi->No_RM)->first();
			# Start generate no antrian
			$prefix = 'NB'; #UMUM/ASURANSI LAIN
			if($registrasi->Kode_Ass=='1008' || $registrasi->Kode_Ass=='1008'){
				$prefix = 'B';
			}
			$length = strlen($prefix)+3;
			$antri = DB::connection('mysql')->table('antrian')->select('no_antrian')
					->where('tgl_periksa', date('Y-m-d'))
					->whereRaw("LENGTH(no_antrian)=$length")
					->where('no_antrian','like',"$prefix%")
					->orderBy('no_antrian','desc')->first();
			$num = 0;
			if(!empty($antri)){
				$num = (int)substr($antri->no_antrian, -3);
			}
			$angkaAntri           = sprintf("%03d",$num+1);
			$noAntreGenerate      = "$prefix".$angkaAntri;
			# End generate no antrian
			if(!$antrean){
				$insertAntrian = new Antrian;
				$insertAntrian->nik = ($cust)?$cust->NoKtp:null;
				$insertAntrian->kode_poli = $kdPoli;
				$insertAntrian->no_antrian = $noAntreGenerate;
				$insertAntrian->no_rm = $registrasi->No_RM;
				$insertAntrian->status = 'antrifarmasi';
				$insertAntrian->tgl_periksa = date('Y-m-d');
				$insertAntrian->nomor_kartu = ($registrasi->NoPeserta)?$registrasi->NoPeserta:null;
				$insertAntrian->jenis_pasien = $registrasi->NamaAsuransi;
				$insertAntrian->is_geriatri = ($registrasi->Umur > 60)?'Y':'N';
				$insertAntrian->metode_ambil = 'SIMRS';
				$insertAntrian->is_pasien_baru = $registrasi->Baru;
				$insertAntrian->No_Register = $noregist;
				$insertAntrian->save();
				if($insertAntrian){
					$updateAntrianFarmasi = AntrianFarmasi::where('no_rm',$norm)->whereDate('created_at','=',date('Y-m-d'))->first();
					$updateAntrianFarmasi->antrian_id = $insertAntrian->id;
					$updateAntrianFarmasi->save();
				}
			}
			$antreanFarmasi = DB::connection('mysql')->table('antrian_farmasi')->where('no_rm',$norm)->whereDate('created_at','=',date('Y-m-d'))->first();
			// return response()->json($antreanFarmasi);
			//update status antrian ke panggilpoli
			$update = DB::connection('mysql')->table('antrian')
				->where('id', $antreanFarmasi->antrian_id)
				->where('status', "antrifarmasi")
				->update(['status' => "panggilfarmasi"]);

			if($update){
				// return'ok';
				$dataPemanggilan = [
					'antrian_id' => $antreanFarmasi->antrian_id,
					'no_antrian' => $antreanFarmasi->no_antrian_farmasi,
					'status' => 1,
					'dari' => $type,
					'api_sirama_erm' => true,
				];
				//update data pasien sebelumnya ditable pemanggilan untuk ditampilkan
				$update = DB::connection('mysql')->table('pemanggilan')
					->where('dari', $type)
					->orderBy('id', 'DESC')
					->update(['tampilkan' => 1]);

				$pemanggilan = DB::connection('mysql')->table('pemanggilan')->insert([$dataPemanggilan]);
				// if($pemanggilan){
				// 		return 'ok';
				// }
				//ambil data pasien ditable antrian
				// if(empty($antreanFarmasi)){
				// 	return ['status' => 'error','code' => 500, 'message'=>'Data Pasien Tidak Ditemukan'];
				// 	DB::rollback();
				// }
				//panggil function dan post data BPJS
				// $antreanBpjs = new BridgBpjsController();
				// $generateReqUpdateWaktuBPJS = $this->generateReqUpdateWaktuBpjs($antreanFarmasi,$taskId,$jenisResep);
				// $postUpdateWaktuBpjs =  $antreanBpjs->updateWaktu(new Request($generateReqUpdateWaktuBPJS));
				// return $postUpdateWaktuBpjs;
				//validasi jika gagal update
				// if ($postUpdateWaktuBpjs['metaData']->code!=200 || $postUpdateWaktuBpjs['metaData']->code!=208){
				// 	throw new \Exception($postUpdateWaktuBpjs['metaData']->message, (int)$postUpdateWaktuBpjs['metaData']->code);
				// 	DB::rollback();
				// }
				//save logs bpjs
				// $dataLogs = [
				// 	'taskId' => $taskId,
				// 	'nomorAntrian' => $antreanFarmasi->no_antrian_farmasi,
				// 	'messageErr' => $postUpdateWaktuBpjs['metaData']->message,
				// ];
				// Log::info('updateTaskBpjs - Success :', $dataLogs);
				DB::commit();
				return [
					'status'=>'success',
					'code'=>200,
					'message'=>'Pasien dengan Nomor Antrian '.$antreanFarmasi->no_antrian_farmasi.' berhasil dipanggil',
					"data" => "if"
				];
			}else{
				$dataPemanggilan = [
					'antrian_id' => $antreanFarmasi->antrian_id,
					'no_antrian' => $antreanFarmasi->no_antrian_farmasi,
					'status' => 1,
					'dari' => $type,
					'api_sirama_erm' => true,
				];
				//update data pasien sebelumnya ditable pemanggilan untuk ditampilkan
				$update = DB::connection('mysql')->table('pemanggilan')
					->where('dari', $type)
					->orderBy('id', 'DESC')
					->update(['tampilkan' => 1]);
				$pemanggilan = DB::connection('mysql')->table('pemanggilan')->insert([$dataPemanggilan]);
				DB::commit();
				return [
					'status'=>'success',
					'code'=>200,
					'message'=>'Pasien dengan Nomor Antrian '.$antreanFarmasi->no_antrian_farmasi.' berhasil dipanggil ulang',
					"data"=>"else"
				];
			}
		} catch (\Exception $e) {
			DB::rollback();
			// savelogsbpjs
			$dataLogs = [
				// 'taskId' => $taskId,
				'nomorAntrian' => 'xxx',
				'messageErr' => $e->getMessage(),
			];
			Log::info('updateTaskBpjs - Error :', $dataLogs);
			return [
				'status'=>'error',
				'code'=>500,
				'message'=>'Gagal Memanggil Pasien Coba Ulangi Kembali',
				'messageerr'=>$e->getMessage(),
			];
		}
	}
}