<?php

namespace App\Http\Controllers;

# Library / package
use App\Http\Controllers\Controller;
use App\Http\Libraries\Requestor;
use App\Http\Requests;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Redirect, Validator, DB, Auth, DateTime;
use Yajra\Datatables\Datatables;
# Models
use App\Http\Models\Antrian;
use App\Http\Models\Identity;
use App\Http\Models\MstKonterPoli;
use App\Http\Models\KodeAwalanPoli;
use App\Http\Models\Rsu_Bridgingpoli;
use App\Http\Models\rsu_customer;
use App\Http\Models\rsu_dokter_bridging;
use App\Http\Models\rsu_poli;
use App\Http\Models\Rsu_setupall;
use App\Http\Models\Rsu_Register;
use App\Http\Models\Rsu_RiwayatRegistrasi;
use App\Http\Models\TransKonterPoli;
use App\Http\Models\Users;
# Helpers
use App\Helpers\apm as Help;
# Traits
use App\Traits\KonfirmasiAntrianTraits;

class AntrianController extends Controller{
	use KonfirmasiAntrianTraits;
	public function __construct(){
		/* PROD */
		$this->url = "https://apijkn.bpjs-kesehatan.go.id/vclaim-rest";
		$this->consid = env('CONS_ID');
		$this->secretkey = env('SECRET_KEY');
		$this->userkey = '2079632035f01e757d81a8565b074768';
		/* DEV */
		// $this->url = 'https://apijkn-dev.bpjs-kesehatan.go.id/vclaim-rest-dev';
		// $this->consid = '21095';
		// $this->secretkey = 'rsud6778ws122mjkrt';
		// $this->userkey = '21f330a3e8e9f281d845f6b545b23653';

		$this->data['identitas'] = Identity::find(1);
	}

	public function konfirmasiManual(Request $request){
		date_default_timezone_set("Asia/Jakarta");
		DB::beginTransaction();

		$hariIni = date('Y-m-d');
		$kode = $request->kode;

		if( preg_match("/[a-z]/i", $kode) ){ # Kode booking simapan
			$cekKode = DB::connection('mysql')->table('pasien_baru_temporary')
				->where('kodeUnik',$kode)
				->where('masukMaster','belum')
				->where('tanggalPeriksa', date('Y-m-d'))
				->orderBy('id_pas', 'DESC')
				->first();
		}else{
			$cekKode = DB::connection('mysql')->table('bot_pasien as b')
				->join('bot_data_pasien as bd', 'b.id', '=', 'bd.idBots')
				->where('b.tgl_periksa', date('Y-m-d'))
				->where('b.random', $kode)
				->orderBy('b.id', 'DESC')
				->first();
			if(!empty($cekKode)){
				$cekKode = $this->convertReqWaToSimapan($cekKode);
			}
		}

		//mempersiapkan data untuk konfirmasi
		$prefix = '';
		if(!empty($cekKode)){
			$jenis = $cekKode->caraBayar;
			$prefix = $jenis=='BPJS'?'B':'NB';
		}else{
			if( preg_match("/[a-z]/i", $kode) ){
				$cekKode = DB::connection('mysql')->table('pasien_baru_temporary')
					->where('kodeUnik',$kode)
					->where('masukMaster','sudah')
					->where('tanggalPeriksa', date('Y-m-d'))
					->first();
			}else{
				$cekKode = DB::connection('mysql')->table('bot_pasien as b')
					->join('bot_data_pasien as bd', 'b.id', '=', 'bd.idBots')
					->where('b.tgl_periksa', date('Y-m-d'))
					->where('b.random', $kode)
					->where('bd.masukMaster','sudah')
					->first();
			}

			if(!empty($cekKode)){
				return [
					'status'  => 'error',
					'code'    => 400,
					'message' => 'Data sudah pernah dikonfirmasi'
				];
			}else{
				return [
					'status'  => 'error',
					'code'    => 400,
					'message' => 'Kode booking tidak ditemukan'
				];
			}
		}

		$antri = Antrian::select('no_antrian')
			->where('tgl_periksa',$cekKode->tanggalPeriksa)
			->where('no_antrian','like',"$prefix%")
			->orderBy('no_antrian','desc')
			->first();
		
		$num = 0;
		if(!empty($antri)){
			$num = (int)substr($antri->no_antrian, -3);
		}
		$angkaAntri = sprintf("%03d",$num+1);
		$nextAntri = "$prefix".$angkaAntri;

		// kode booking
		$kodebooking = date('dmy').$nextAntri;
		$poliBPJS = DB::connection('dbrsud')->table('mapping_poli_bridging')->where('kdpoli_rs',$cekKode->kodePoli)->first();
		//end of persiapan data konfirmasi

		//memulai untuk konfirmasi
		if($cekKode->tanggalPeriksa > $hariIni){
			//jika tanggal periksa belum waktunya
			return ['status'=> 'error', 'code'=>500 , 'message'=>'Antrian Harus Dikonfirmasi Sesuai Tanggal('. $cekKode->tanggalPeriksa .')'];
		}elseif($cekKode->tanggalPeriksa < $hariIni){
			//jika tangga periksa sudah lewat tanggal sekarang
			return ['status'=> 'error', 'code'=>500 , 'message'=>'Antrian Sudah Kadaluarsa Silahkan Ambil Ulang'];
		}
		//generate no antrian khusus pasien baru
		$pBaru = $cekKode->isPasienBaru;
		if(!isset($cekKode->isPasienBaru)) {
			$pBaru = $cekKode->is_pasien_baru;
		}
		$noPasBaru = '';
		if($pBaru == "Y" || $pBaru == '1'){
			$request->tglperiksa   = $cekKode->tanggalPeriksa;
			$request->jenis_pasien = $jenis;
			$noPasBaru = $this->generateNoAntrianBaru($request);
		}
		// return response()->json($noPasBaru);
		if(preg_match("/[a-z]/i", $kode)){
			//setup data BPJS
			$generateReqAntreanBPJS = $this->generateReqAntrean($cekKode, $kodebooking, $nextAntri, "toBpjs", "simapan", $noPasBaru);
			//setup data local
			$generateReqAntreanLocal = $this->generateReqAntrean($cekKode, $kodebooking, $nextAntri, "toLocal", "simapan", $noPasBaru);
		}else{
			//setup data BPJS
			$generateReqAntreanBPJS = $this->generateReqAntrean($cekKode, $kodebooking, $nextAntri, "toBpjs", "wa", $noPasBaru);
			//setup data local
			$generateReqAntreanLocal = $this->generateReqAntrean($cekKode, $kodebooking, $nextAntri, "toLocal", "wa", $noPasBaru);
		}
		// $dataDokter = rsu_dokter_bridging::where('kodedokter',$generateReqAntreanLocal['kode_dokter'])->first();
		// if(!$dataDokter){
		// 	return ['status'=> 'error', 'code'=>500 , 'message'=>'Data dokter tidak ditemukan'];
		// }
		//validasi agar pasien tidak bisa mengambil 2x nomor
		$cekDataAntrian = Antrian::where('nik',$cekKode->nik)
			->where('tgl_periksa', date('Y-m-d'))
			->first();
		if(!empty($cekDataAntrian)){
			if($cekDataAntrian->is_pasien_baru=='Y'){
				$duplikatAntrian = $cekDataAntrian->no_antrian;
			}else{
				if($cekDataAntrian->metode_ambil=='KIOSK'){
					$duplikatAntrian = $cekDataAntrian->no_antrian;
				}else{
					$duplikatAntrian = $cekDataAntrian->nomor_antrian_poli;
				}
			}
			return ['status'=> 'error', 'code'=>500 , 'message'=>'NIK Telah Mengambil Antrian dengan Nomor Antrian '.$duplikatAntrian];
		}

		//hit to table antrian dan antrian_tracer
		$postAntrian =  DB::connection('mysql')->table('antrian')->insertGetId($generateReqAntreanLocal);
		$generateReqAntreanTracer = $this->generateReqAntreanTracer($postAntrian, $cekKode);
		// $postAntrianTracer =  DB::connection('mysql')->table('antrian_tracer')->insert($generateReqAntreanTracer);
		$insertTracer = [
			'antrian_id'    => $postAntrian,
			'from'          => ($generateReqAntreanLocal['metode_ambil']=='WA')?'wa':'simapan',
			'to'            => 'poli',
			'status_tracer' => 1,
			'tgl'           => date('Y-m-d'),
			'time'          => date('H:i:s')
		];
		$postAntrianTracer = DB::connection('mysql')->table('antrian_tracer')->insert($insertTracer);

		$no_rm = $generateReqAntreanLocal['no_rm'];

		$cekFilling = DB::connection('mysql')->table('filling')
			->where('no_rm',$no_rm)
			->where('tgl_periksa',$request->tglperiksa)
			->first();
		$dataFilling = [
			'no_rm'       => $no_rm,
			'tgl_periksa' => $generateReqAntreanLocal['tgl_periksa'],
			'status'      => 'dicari',
			'antrian_id'  => $postAntrian,
			'tgl_filling' => date('Y-m-d H:i:s'),
		];
		if($no_rm!='00000000000'){
			$isBaru = $generateReqAntreanLocal['is_pasien_baru'];
			if(empty($cekFilling) && $isBaru=='N'){ // insertFilling
				$insertFilling = DB::connection('mysql')->table('filling')->insert($dataFilling);
			}else{
				if($isBaru=='N'){
					//update antrian_id di table filling jika pasien lama
					$updateFilling = DB::connection('mysql')->table('filling')
						->where('no_rm', $cekKode->no_rm)
						->where('tgl_periksa', $cekKode->tanggalPeriksa)
						->update(['antrian_id' => $postAntrian]);
				}
			}
		}

		//hit to BPJS antreanAdd and updateWaktu
		try {
			$ifPoli = $generateReqAntreanLocal['kode_poli'];
			if(in_array($ifPoli,['GIG','PSY','GIZ','VCT','MCU'])){
				$postAntreanBpjs = 'poli internal';
			}else{
				$antreanBpjs = new BridgBpjsController();
				$postAntreanBpjs =  $antreanBpjs->antreanAdd(new Request($generateReqAntreanBPJS));
				if ($postAntreanBpjs['metaData']->code != 200 ){
					if($postAntreanBpjs['metaData']->message == "Terdapat duplikasi Kode Booking"){
					}else{
						DB::rollback();
						// if($kode==2178682){
						// 	// return ['status'=> 'error', 'code'=>400 , 'message'=>'gagal', 'data'=>$generateReqAntreanBPJS];
						// 	return ['status'=> 'error', 'code'=>400 , 'message'=>'gagal', 'data'=>$postAntreanBpjs];
						// }
						throw new \Exception($postAntreanBpjs['metaData']->message, (int)$postAntreanBpjs['metaData']->code);
					}
				}
			}
			Log::info("POST BPJS SUCESS : ", [
				'data' => $generateReqAntreanBPJS,
				'response' => $postAntreanBpjs
			]);
		}catch(\Exception $e){
			DB::rollback();
			Log::info("POST BPJS ERROR : ", [
				'data' => $generateReqAntreanBPJS,
				'messageErr' => $e->getMessage()
			]);
			return ['status'=> 'error', 'code'=>$e->getCode() , 'message'=>$e->getMessage()];
		}

		if($postAntreanBpjs && $postAntrianTracer){
			//menempatkan pasien_baru_temporary ke table token_konfirmasi dan memasukan pasien temporary ke master
			try {
				if(preg_match("/[a-z]/i", $kode)){
					//update status pasien
					DB::connection('mysql')->table('pasien_baru_temporary')
						->where('id_pas', $cekKode->id_pas)
						->update(['masukMaster'=>'sudah']);
				}else{
					//update status pasien di table bot_pasien
					DB::connection('mysql')->table('bot_pasien as b')
						->join('bot_data_pasien as bd', 'b.id', '=', 'bd.idBots')
						->where('b.id', $cekKode->id_pas)
						->update(['masukMaster'=>'sudah','status_akun'=>0,'konfirmasi'=>'verified']);
				}

				//log untuk jaga2 data
				$logs = [
					"data" => [
						"dataBpjs" => [
							"antreanAdd" => $generateReqAntreanBPJS,
						], 
						"dataLocal" => [
							"antrian" => $generateReqAntreanLocal,
							"antrian_tracer" => $generateReqAntreanTracer
						] 
					], 
					"responseBpjs" => [
						"antreanAdd" => $postAntreanBpjs,
					],
					"metodeKonfirmasi" => preg_match("/[a-z]/i", $kode) ? "MANUAL SIMAPAN" : "MANUAL WA"
				];
				Log::info("konfirmasiAntrian - Success - ", $logs);

				//datapasien untuk ditampilkan
				$pasien = DB::connection('mysql')->table('antrian')
					->where('nik',$cekKode->nik)
					->where('tgl_periksa', date('Y-m-d'))
					->first();
				$dataPasien = $this->generateDataPasien($pasien);
				DB::commit();
				return [
					'status' => 'success', 
					'code'=>200, 
					'message'=> 'Berhasil Konfirmasi Antrian '. $nextAntri .', Silahkan Ambil Nomor Antrian',
					'data' => $dataPasien
				];
			} catch (\Exception $e) {
				DB::rollback();
				return ['status'=> 'error', 'code'=>500 , 'message'=>'Kesalahan Konfirmasi, Silahkan Coba Lagi'];
			}
		}
	}

	public function antreanPanggilData(Request $request){
		$totalAntri = DB::connection('mysql')->table('antrian')
			->where('tgl_periksa',date('Y-m-d'))
			->whereIn('status',['belum','panggil'])
			->count();
		$belumPanggil = DB::connection('mysql')->table('antrian')
			->where('tgl_periksa',date('Y-m-d'))
			->where('status','belum')
			->count();
		$cekDilayani = DB::connection('mysql')->table('antrian')
			->where('tgl_periksa',date('Y-m-d'))
			->where('status','panggil')
			->count();
			// ->orderBy('id','desc')
			// ->first();
			if(!empty($cekDilayani)){
				// $dilayani = $cekdilayani->no_antrian;
				$dilayani = $cekDilayani;
			}else{
				$dilayani = '-';
			}
		$getAntrian = [$dilayani,$belumPanggil,$totalAntri];


		$cekAntrianPasien = DB::connection('mysql')->table('antrian')
			->where('tgl_periksa',date('Y-m-d'))
			->where('status','belum')
			->whereNotIn('is_geriatri',['Y'])
			->orderBy('id','asc')
			->get();


		$cekAntrianGeriatri = DB::connection('mysql')->table('antrian')
			->where('tgl_periksa',date('Y-m-d'))
			->where('status','belum')
			->where('is_geriatri','Y')
			->orderBy('id','asc')
			->get();


		$dilayani = DB::connection('mysql')->table('antrian')
			->where('tgl_periksa',date('Y-m-d'))
			->whereNotIn('status',['belum','panggil','batal'])
			->whereNotNull('no_antrian_pbaru')
			->where('no_antrian_pbaru','!=',"")
			->orderBy('id','desc')
			->get();


		return [
			'getAntrian'         =>$getAntrian,
			'cekAntrianPasien'   =>$cekAntrianPasien,
			'cekAntrianGeriatri' =>$cekAntrianGeriatri,
			'dilayani'           =>$dilayani,
		];
	}

	public function panggilSelanjutnya(Request $request){
		date_default_timezone_set("Asia/Jakarta");
		$cek = $request->status;
		$user = \Auth::user()->level;
		// return $request->all();
		$dateCur = date("Y-m-d");
		if($cek=='pasien'){
			// $pasienB = DB::connection('mysql')->table('antrian')
				// 	->where('tgl_periksa',$dateCur)
				// 	->where('no_antrian','LIKE',"B%")
				// 	->where('status','belum')
				// 	->orderBy('no_antrian','asc')
				// 	->first();
				// $pasienL = DB::connection('mysql')->table('antrian')
				// 	->where('tgl_periksa',$dateCur)
				// 	->where('no_antrian','LIKE',"L%")
				// 	->where('status','belum')
				// 	->orderBy('no_antrian','asc')
				// 	->first();

				// if(!empty($pasienB)){
				// 	$prosesB = substr($pasienB->no_antrian, 1);
				// }else{
				// 	$prosesB = "";
				// }
				// if(!empty($pasienL)){
				// 	$prosesL = substr($pasienL->no_antrian, 1);
				// }else{
				// 	$prosesL = "";
				// }

				// if((!empty($prosesL) && empty($prosesB))){
				// 	$panggil = $pasienL->no_antrian;
				// }else if((empty($prosesL) && !empty($prosesB))){
				// 	$panggil = $pasienB->no_antrian;
				// }else if($prosesL==$prosesB && !empty($prosesL) && !empty($prosesB)){
				// 	$panggil = $pasienL->no_antrian;
				// }else if($prosesL>$prosesB){
				// 	$panggil = $pasienB->no_antrian;
				// }else{
				// 	$panggil = "-";
				// }
				// if($panggil!="-"){
				// 	$updatePasien = DB::connection('mysql')->table('antrian')
				// 		->where('tgl_periksa',$dateCur)
				// 		->where('no_antrian',$panggil)
				// 		->where('status','belum')
				// 		->update(['status'=>'panggil']);
				// 	if($updatePasien){
				// 		return ['status' => 'success','message' => 'Panggil antrian berhasil','data'=>$panggil];
				// 	}else{
				// 		return ['status' => 'error','message' => 'Tidak ada antrian'];
				// 	}
				// }else{
				// 	return ['status' => 'error','message' => 'Tidak ada antrian'];
				// }

			$panggilPasien = DB::connection('mysql')->table('antrian')
				->where('tgl_periksa',$dateCur)
				->where('status','belum')
				->where('is_geriatri','N')
				->orderBy('id','asc')
				->first();
			if(!empty($panggilPasien)){
				$updatePasien = DB::connection('mysql')->table('antrian')
					->where('tgl_periksa',$dateCur)
					->where('no_antrian',$panggilPasien->no_antrian)
					->update(['status'=>'panggil']);
				$cekAntrianTracer = DB::connection('mysql')->table('antrian_tracer')
					->where('antrian_id',$panggilPasien->id)
					->first()->loket;
				if(empty($cekAntrianTracer)){
					$antrianTracer = DB::connection('mysql')->table('antrian_tracer')
						->where('antrian_id',$panggilPasien->id)
						->update(['loket' => $user == 9 ? 2 : 1]);
				}
				$request->kodebooking = $panggilPasien->kode_booking;
				$request->waktu = strtotime(date('Y-m-d H:i:s'))*1000;
				$request->taskid = '2';
				$bridgBpjs = new BridgBpjsController;
				$updateWaktu = $bridgBpjs->updateWaktu($request);
				return ['status' => 'success','message' => 'Panggil antrian berhasil','data'=>$panggilPasien];
			}else{
				return ['status' => 'error','message' => 'Tidak ada antrian'];
			}
		}else{
			// $geriatriB = DB::connection('mysql')->table('antrian')
				// 	->where('tgl_periksa',$dateCur)
				// 	->where('no_antrian','LIKE',"B%")
				// 	->where('status','belum')
				// 	->where('is_geriatri','Y')
				// 	->orderBy('no_antrian','asc')
				// 	->first();
				// $geriatriL = DB::connection('mysql')->table('antrian')
				// 	->where('tgl_periksa',$dateCur)
				// 	->where('no_antrian','LIKE',"L%")
				// 	->where('status','belum')
				// 	->where('is_geriatri','Y')
				// 	->orderBy('no_antrian','asc')
				// 	->first();

				// if(!empty($geriatriB)){
				// 	$prosesB = substr($geriatriB->no_antrian, 1);
				// }else{
				// 	$prosesB = "";
				// }
				// if(!empty($geriatriL)){
				// 	$prosesL = substr($geriatriL->no_antrian, 1);
				// }else{
				// 	$prosesL = "";
				// }
				// if((!empty($prosesL) && empty($prosesB))){
				// 	$panggil = $geriatriL->no_antrian;
				// }else if((empty($prosesL) && !empty($prosesB))){
				// 	$panggil = $geriatriB->no_antrian;
				// }else if($prosesL==$prosesB && !empty($prosesL) && !empty($prosesB)){
				// 	$panggil = $geriatriL->no_antrian;
				// }else if($prosesL>$prosesB){
				// 	$panggil = $geriatriB->no_antrian;
				// }else if($prosesL<$prosesB){
				// 	$panggil = $geriatriL->no_antrian;
				// }else{
				// 	$panggil = "-";
				// }
				// if($panggil!="-"){
				// 	$updateGeriatri = DB::connection('mysql')->table('antrian')
				// 		->where('tgl_periksa',$dateCur)
				// 		->where('no_antrian',$panggil)
				// 		->where('status','belum')
				// 		->update(['status'=>'panggil']);
				// 	if($updateGeriatri){
				// 		return ['status' => 'success','message' => 'Panggil antrian berhasil','data'=>$panggil];
				// 	}else{
				// 		return ['status' => 'error','message' => 'Tidak ada antrian geriatri'];
				// 	}
				// }else{
				// 	return ['status' => 'error','message' => 'Tidak ada antrian geriatri'];
				// }
			
			$panggilGeriatri = DB::connection('mysql')->table('antrian')
				->where('tgl_periksa',$dateCur)
				->where('status','belum')
				->where('is_geriatri','Y')
				->orderBy('id','asc')
				->first();
			if(!empty($panggilGeriatri)){
				$updateGeriatri = DB::connection('mysql')->table('antrian')
					->where('tgl_periksa',$dateCur)
					->where('no_antrian',$panggilGeriatri->no_antrian)
					->update(['status'=>'panggil']);

				$antrianTracer = DB::connection('mysql')->table('antrian_tracer')
					->where('antrian_id',$panggilGeriatri->id)
					->update(['loket' => $user == "Loket 2" ? 2 : 1]);
				// return response()->json($antrianTracer);
				$request->kodebooking = $panggilGeriatri->kode_booking;
				$request->waktu = strtotime(date('Y-m-d H:i:s'))*1000;
				$request->taskid = '2';
				$bridgBpjs = new BridgBpjsController;
				$updateWaktu = $bridgBpjs->updateWaktu($request);
				return ['status' => 'success','message' => 'Panggil antrian berhasil','data'=>$panggilGeriatri];
			}else{
				return ['status' => 'error','message' => 'Tidak ada antrian'];
			}
		}
	}

	public function panggilUlang(Request $request){
		date_default_timezone_set("Asia/Jakarta");
		$cek = $request->status;
		$antrian = $request->antrian;
		if(!empty($antrian)){
			$cekAntri = DB::connection('mysql')->table('antrian')
				->where('tgl_periksa',date('Y-m-d'))
				->where('status','panggil')
				->where('no_antrian',$antrian)
				->first();
			if($cek=='pasien'){
				$panggilUlang = DB::connection('mysql')->table('antrian')
					->where('tgl_periksa',date('Y-m-d'))
					->where('status','panggil')
					->where('is_geriatri','N')
					->where('no_antrian',$antrian)
					->first();
			}else{
				$panggilUlang = DB::connection('mysql')->table('antrian')
					->where('tgl_periksa',date('Y-m-d'))
					->where('status','panggil')
					->where('is_geriatri','Y')
					->where('no_antrian',$antrian)
					->first();
			}
			if(!empty($panggilUlang)){
				return [
					'status'=>'success',
					'message'=>'Antrian '.$panggilUlang->no_antrian.' berhasil dipanggil ulang',
					'data'=>$panggilUlang
				];
			}else{
				if(!empty($cekAntri)){
					// return response()->json($cekAntri);
					if($cekAntri->is_geriatri=='N'){
						return ['status'=>'error','message'=>'Silahkan mulai panggilan Geriatri.'];
					}else{
						return ['status'=>'error','message'=>'Silahkan mulai panggilan Pasien.'];
					}
				}else{
					return ['status'=>'error','message'=>'Antrian sudah diarahkan ke poli'];
				}
			}
		}else{
			return ['status'=>'gagal','message'=>'Silahkan mulai panggil antrian terlebih dahulu.'];
		}
	}

	// Konter Poli Start
	public function formListCounter(Request $request){
		if ($request->ajax()) {
			$today = date('Y-m-d');
			$data['user'] = Auth::user()->id;
			$cekUser = Users::find($data['user']);
			if(!empty($cekUser)){
				if($cekUser->lv_user!='admin'){
					$data['konterpoli'] = MstKonterPoli::with(['trans_konter_poli.tm_poli.mapping_poli_bridging'])
						->where('user_id', $data['user'])
						->get();

					$arrpoli = [];
					foreach ($data['konterpoli'] as $key => $value) {
						foreach ($value->trans_konter_poli as $k => $v) {
							if(!empty($tmPoli = $v->tm_poli)){
								if(!empty($tmPoli->mapping_poli_bridging)){
									$getKodePoli = $v->tm_poli->mapping_poli_bridging->kdpoli;
									array_push($arrpoli, $getKodePoli);
								}
							}
						}
					}
					if(in_array('GIG', $arrpoli)||in_array('BDM', $arrpoli)||in_array('GND', $arrpoli)||in_array('KON', $arrpoli)){
						$arrpoli[] = 'GIG';
						$arrpoli[] = 'GND';
						$arrpoli[] = 'BDM';
						$arrpoli[] = 'KON';
					}
					$data['listkonter'] = Antrian::with(['tm_customer','mapping_poli_bridging.tm_poli'])
						->whereIn('kode_poli', $arrpoli)
						->where('tgl_periksa', $today)
						->where('status', 'counter')
						->get();
				}else{
					$data['listkonter'] = Antrian::with(['tm_customer','mapping_poli_bridging.tm_poli'])
						->where('tgl_periksa', $today)
						->where('status', 'counter')
						->get();
				}
			}

			return Datatables::of($data['listkonter'])
			->addIndexColumn()
			->addColumn('tglLahir',function($row){
				$lahir = $row->tm_customer;

				if(!empty($lahir->TglLahir)){
					$getLahir = date_create($lahir->TglLahir);
					$date = date_create('now');
					$res = date_diff($date,$getLahir);
					$Y = $res->y;
					$M = $res->m;
					$D = $res->d;
					$umur = ( ($Y>0) ? $Y.'Thn' :( ($M>0)? $M.'Bln' : $D.'Hri' ) );
					$showUmur = date('d-m-Y',strtotime($lahir->TglLahir)).' ('.$umur.')';
				}else{
					$showUmur = '-';
				}

				$res = !empty($lahir)?(!empty($lahir->TglLahir)?$showUmur:'-'):'-';
				$txt = '<p class="text-center" style="margin:0px">'.($res).'</p>';
				return $txt;
			})
			->addColumn('action',function($row){
				$a = $this->templateAction($row);
				return $a;
			})
			->make(true);
		}
		return view('Admin.antreanBPJS.listAntrian.mainCounterPoli');
	}

	public function detailListCounter(Request $request){
		$data['data'] = Antrian::with(['tm_customer','mapping_poli_bridging.tm_poli'])
		// ->where('kode_booking', $request->id)
		->where('id', $request->id)
		->first();

		$data['poli'] = Rsu_Bridgingpoli::join('tm_poli', 'mapping_poli_bridging.kdpoli_rs', '=', 'tm_poli.KodePoli')
		->get();

		$content = view('Admin.antreanBPJS.listAntrian.detailCounter', $data)->render();
		return ['status' => 'success', 'content' => $content, 'data' => $data];
	}

	public function generateAntrianCounter(Request $request){
		$today = date('Y-m-d');
		$data = Antrian::where('id', $request->id)->first();

		// Generate no. antrian
		$kodeawal = KodeAwalanPoli::where('kdpoli', $request->poli)->first();
		$prefix = $kodeawal->kode_awal;
		$length = strlen($prefix)+3;
		$antri = DB::connection('mysql')->table('antrian')->select('nomor_antrian_poli')
			->where('tgl_periksa',$today)
			->whereRaw("LENGTH(nomor_antrian_poli)=$length")
			->where('nomor_antrian_poli','like',"$prefix%")
			->orderBy('nomor_antrian_poli','desc')
			->first();

		$num = 0;
		if(!empty($antri)){
			$num = (int)substr($antri->nomor_antrian_poli, -3);
		}

		$angkaAntri = sprintf("%03d",$num+1);
		$nextAntri = "$prefix".$angkaAntri;

		if (empty($data->nomor_antrian_poli)) {
			$data->nomor_antrian_poli = $nextAntri;
			$data->kode_poli = $request->poli;
			$data->save();
		}

		if ($data) {
			$data = [
				'type'=>'success',
				'status'=>'success',
				'code'=>200,
				'head_message'=>'Success',
				'message'=>'Antrian Berhasil Generate No. Antrian',
				'data' => $data
			];
		} else {
			$data = [
				'type'=>'warning',
				'status'=>'warning',
				'code'=>300,
				'head_message'=>'Whoops!',
				'message'=>'Antrian Gagal Generate No. Antrian'
			];
		}

		return $data;
	}

	public function cetakTracerPasien($id){
		$this->data['getAntrian'] = Antrian::with(['asuransi','tm_customer','mapping_poli_bridging.tm_poli'])->where('id',$id)->first();
		return view('cetak.cetakTracerPasien')->with('data', $this->data);
	}

	public function cetakTracerPasienPoli($id)
	{
		$this->data['getAntrian'] = Antrian::with(['tm_customer','mapping_poli_bridging.tm_poli'])
				->where('id',$id)
				->first();
		return view('cetak.cetakTracerPasienPoli')->with('data', $this->data);
	}

	// cetak antrian poli
	public function cetakAntrianKonterPoli($id){
		$antrian = Antrian::with(['tm_customer','mapping_poli_bridging.tm_poli.kode_awalan_poli'])
		// ->where('nomor_antrian_poli', $id)
		->where('id', $id)
		->where('tgl_periksa',date('Y-m-d'))
		->first();
		$noPoli = $antrian->nomor_antrian_poli;
		$data = [
			'antrian' => $antrian,
			'noAntrian' => $noPoli!=""?$noPoli:$antrian->no_antrian_pbaru,
			'for'=>$noPoli!=""?'POLI':'ADMISI',
			'tujuan' => $noPoli!=""?$antrian->mapping_poli_bridging->tm_poli->NamaPoli:'Loket Pendaftaran',
		];

		return view('cetak.cetakAntrianPoli', $data)->with('data',$this->data);
	}

	function templateAction($data){
		// Get No RM
		$pm = json_encode((object)['kodebooking' => $data->kode_booking,'nomor_antrian_poli' => $data->nomor_antrian_poli]);
		$am = json_encode((object)['id' => $data->id, 'nomor_antrian_poli' => $data->nomor_antrian_poli]);
		$norm = $data->no_rm;
		$btn = "<div class='text-center'>";
		// $btn .= "<button class='btn btn-sm' style='background-color: #2CBA44; margin-right: 10px; color: #fff;' title='Detail' onclick='detail(`$data->kode_booking`)'>Detail</button>";
		$btn .= "<button class='btn btn-sm' style='background-color: #2CBA44; margin-right: 10px; color: #fff;' title='Detail' onclick='detail(`$data->id`)'>Detail</button>";
		if ($data->jenis_pasien == 'BPJS') {
			$btn .= "<button class='btn btn-sm' style='background-color: #D9D9D9; color: #000;' onclick='cetaksep(`$norm`)'>Cetak SEP</button>";
		} else {
			$btn .= "<button class='btn btn-sm' style='background-color: #92FD6D; color: #000;' onclick='tracer(`$am`)'>Tracer</button>";
		}
		// $btn .= "<button class='btn btn-sm btn-danger' title='Batalkan' style='margin-left: 5px;' onclick='batalkan(`$data->kode_booking`)'><i class='fa fa-remove' aria-hidden='true'></i></button> <br>";
		// $btn .= "<button class='btn btn-sm btn-warning' title='Cetak SEP' style='margin-top: 5px;' onclick='cetaksep(`$norm`)'><i class='fa fa-print' aria-hidden='true'></i></button>";
		// $btn .= "<button class='btn btn-sm btn-primary' title='Kirim Ke Poli' style='margin-left: 5px; margin-top: 5px;' onclick='arahkan(`$pm`)'><i class='fa fa-arrow-right' aria-hidden='true'></i></button>";
		$btn .= "</div>";

		return $btn;
	}

	// CETAK ULANG SEP
	public function cetakUlangSep(Request $request){
		$getReg = Rsu_Register::where('No_RM', $request->KodeCust)->first(); // db rsu
		$request->nosep = $getReg->NoSEP;
		$sep = $request->nosep ? $request->nosep : '0000000000000000000';
		$url = $this->url."/SEP/".$sep; //url web dev service bpjs
		// $url = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/SEP/".$sep; //url web rilis baru service bpjs
		// $url = "http://api.bpjs-kesehatan.go.id:8080/vclaim-rest/SEP/".$sep; //url web service bpjs
		$consID     = $this->consid; //customer ID RS
		$secretKey  = $this->secretkey; //secretKey RS
		$method = 'GET';
		$port = '80';
		$params = '';

		$datasep = Requestor::set_new_curl_bridging($url, $params, $method, $consID, $secretKey, $port,'','');
		$result = [
			'metaData' => json_decode($datasep['metaData']),
			'response' => json_decode($datasep['response']),
		];
		// return $result;
		if ($result['metaData']->code == 200) {
			date_default_timezone_set('Asia/Jakarta');

			$url = $this->url."/Peserta/nokartu/".$result['response']->peserta->noKartu."/tglSEP/".date('Y-m-d'); //url web dev service bpjs
			// $url = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/SEP/".$sep; //url web rilis baru service bpjs
			// $url = "http://api.bpjs-kesehatan.go.id:8080/vclaim-rest/SEP/".$sep; //url web service bpjs
			$consID     = $this->consid; //customer ID RS
			$secretKey  = $this->secretkey; //secretKey RS
			$method = 'GET';
			$port = '80';
			$params = '';

			$peserta = Requestor::set_new_curl_bridging($url, $params, $method, $consID, $secretKey, $port,'','');
			$results = [
				'metaData' => json_decode($peserta['metaData']),
				'response' => json_decode($peserta['response']),
			];
			$data['sepValue'] = [
				'jam' => date('d-m-Y H:i:s'),
				'no_sep' => $result['response']->noSep,
				'tgl_sep' => $result['response']->tglSep,
				'no_kartu' => $result['response']->peserta->noKartu,
				'noMr' => $result['response']->peserta->noMr,
				'nama_kartu' => $result['response']->peserta->nama,
				'tgl_lahir' => $result['response']->peserta->tglLahir,
				'jenis_kelamin' => $result['response']->peserta->kelamin,
				'prb' => $results['response']->peserta->informasi->prolanisPRB,
				'poli_tujuan' => $result['response']->poli,
				'diagnosa' => $result['response']->diagnosa,
				'jenis_rawat' => $result['response']->jnsPelayanan,
				'catatan' => $result['response']->catatan,
				'kls_rawat' => $result['response']->peserta->hakKelas,
				'kls_rawatNaik' => ($result['response']->klsRawat->klsRawatNaik) ? : '',
				'noarsip' => ($request->noarsip) ? : '1',
			];
			$data['respon'] = $result;

			// SAVE INTO ANTRIAN
			$data['getAntrian'] = Antrian::with('tm_customer')->where('no_rm', $request->KodeCust)->first();
			if (empty($data['getAntrian']->sudah_cetak_sep)) {
				$data['getAntrian']->sudah_cetak_sep = 'Ya';
				$data['getAntrian']->save();
			}

			// return $data;
			$return = ['status'=>'success','code'=>200,'data'=>$data];
			return response()->json($return);
		}elseif ($result['metaData']->code == 201) {
			$return = ['status'=>'error','code'=>201,'data'=>''];
			return response()->json($return);
		}else {
			$return = ['status'=>'error','code'=>201,'data'=>''];
			return response()->json($return);
		}
	}

	// Poli Start
	public function listPoli(Request $request){
		return view('Admin.antreanBPJS.listAntrian.mainPoli');
	}

	public function dataGridPoli(Request $request){
		$data = Antrian::getJsonPoli($request);
		return response()->json($data);
	}

	public function panggilPoli(Request $request){
		date_default_timezone_set("Asia/Jakarta");
		$kode = $request->kode;
		$data = DB::table('natusi_apm.antrian')
			->where('kode_booking',$kode)
			->first();
		if(!empty($data)){
			// $antrian = true;
			$antrian = DB::table('natusi_apm.antrian')
				->where('kode_booking',$kode)
				->update(['status'=>'panggilpoli']);
			$tracer = DB::table('natusi_apm.antrian_tracer')
				->where('antrian_id',$data->id)
				->where('to','poli')
				->update(['status_tracer'=>'2']);
			if($antrian){
				// $split = substr($data->no_antrian,0,1);
				// if($split=='B'){
					$request->kodebooking = $kode;
					$request->waktu = strtotime(date('Y-m-d H:i:s'))*1000;
					$request->taskid = '4';
					$bridgBpjs = new BridgBpjsController;
					$updateWaktu = $bridgBpjs->updateWaktu($request);
				// }
				$res = ['status'=>'success','message'=>'Antrian berhasil diipanggil'];
			}else{
				$res = ['status'=>'error','message'=>'Antrian tidak ditemukan'];
			}
		}else{
			$res = ['status'=>'error','message'=>'Antrian tidak ditemukan'];
		}
		return $res;
	}
	// Poli End

	public function counterToPoli(Request $request){
		DB::beginTransaction();
		try {
			date_default_timezone_set("Asia/Jakarta");
			# Update antrian
			$antrian = Antrian::select(
				'id',
				'no_rm',
				'no_antrian',
				'status',
				'kode_poli',
				'cekin',
				'kode_booking',
				'jenis_pasien',
				'tgl_periksa',
				'kode_dokter',
				'pembayaran_pasien',
			)->where('id',$request->kode)
			->first();
			$antrian->status = 'antripoli';
			if ($request->kode_poli) {
				$antrian->kode_poli = $request->kode_poli;
			}
			$antrian->cekin = date('Y-m-d H:i:s');
			$antrian->save();
			if (!$antrian) {
				DB::rollback();
				return ['code'=>400, 'status'=>'error','message'=>'Gagal update antrian, Silahkan coba lagi.'];
			}
			# tr_registrasi 
			$storeRegistrasi = $this->storeRsuRegister($antrian);
			if(!$storeRegistrasi){
				DB::rollback();
				return ['code'=>400, 'status'=>'error','message'=>'Gagal simpan registrasi, Silahkan coba lagi.'];
			}
			$antrian->No_Register = $storeRegistrasi;
			$antrian->save();
			# Update taskid
			$split = substr($antrian->no_antrian,0,1);
			if($split=='B'){
				$request->kodebooking = $antrian->kode_booking;
				$request->waktu = strtotime(date('Y-m-d H:i:s'))*1000;
				$request->taskid = '3';

				$bridgBpjs = new BridgBpjsController;
				$updateWaktu = $bridgBpjs->updateWaktu($request);
			}
			# UPDATE ANTRIAN TRACER
			$id_antrian = $antrian->id;
			$getTracer = DB::connection('mysql')->table('antrian_tracer')->where('antrian_id', $id_antrian)
			->update(['status_tracer' => '2']);
			if (!$getTracer) {
				DB::rollback();
				return ['code'=>400, 'status'=>'error','message'=>'Gagal update tracer, Silahkan coba lagi.'];
			}
			# INSERT NEW ANTRIAN TRACER
			$insertTracer = [
				'antrian_id'    => $antrian->id,
				'from'          => 'counter',
				'to'            => 'poli',
				'status_tracer' => 1,
				'tgl'           => date('Y-m-d'),
				'time'          => date('H:i:s')
			];
			$antrianTracer = DB::connection('mysql')->table('antrian_tracer')->insert($insertTracer);
			if (!$antrianTracer) {
				DB::rollback();
				return ['code'=>400, 'status'=>'error','message'=>'Gagal simpan tracer, Silahkan coba lagi.'];
			}
			DB::commit();
			return ['code'=>200, 'status'=>'success','message'=>'Pasien berhasil diarahkan ke poli'];
		} catch (\Throwable $e) {
			DB::rollback();
			$log = ['ERROR COUNTER TO POLI ('.$e->getFile().')',false,$e->getMessage(),$e->getLine()];
			Help::logging($log);
			return ['code'=>500, 'status'=>'error','message'=>'Terjadi kesalahan sistem'];
		}
	}

	public function resetAntrianCounter(Request $request)
	{
		$antrian = Antrian::where('id', $request->id)->update(['nomor_antrian_poli' => null]);

		if ($antrian) {
			$data = [
				'type' => 'succes',
				'status' => 'success',
				'code' => 200,
				'message' => 'Berhasil Merubah Poli Tujuan',
			];
		} else {
			$data = [
				'type' => 'error',
				'status' => 'error',
				'code' => 201,
				'message' => 'Gagal Merubah Poli Tujuan',
			];
		}
		
		return $data;
	}

	public function storeRsuRegister($req){
		DB::beginTransaction();
		try {
			$pembayaran = Rsu_setupall::where([
				'groups'=>'Asuransi',
				'nilaichar'=>$req->pembayaran_pasien,
			])->first();
			$idAntri = $req->id;
			$tmCust = rsu_customer::select(
					'JenisKel',
					'NamaCust',
					'Alamat',
					'FieldCust1',
					'TglLahir'
				)->where('KodeCust', $req->no_rm)
				->first();
			$poli = Rsu_Bridgingpoli::where('kdpoli', $req->kode_poli)->select('kdpoli_rs')->first(); //get poli rs -> DB Rsu
			$jam        = date('H:i:s');
			$noRM       = $req->no_rm;
			$jenisKel   = $tmCust->JenisKel;
			$nama       = $tmCust->NamaCust;
			$alamat     = $tmCust->Alamat;
			$nomorkartu = $tmCust->FieldCust1;
			$caraBayar  = $req->jenis_pasien;
			# hitung umur start
			$tglLahir = $tmCust->TglLahir;
			$tanggal  = new DateTime($tglLahir);
			$today	  = new DateTime('today');
			$umurReg  = $today->diff($tanggal)->y;
			# hitung umur end
			$tg   = date('y');
			$tg   = $tg.'2';
			$thn  = date('Y');
			$tgl = date("Y-m-d",strtotime($req->tgl_periksa));
			$cekRegis = '';
			$nourut = '';
			if(!$reg = Rsu_Register::where('No_RM', $noRM)->where('Kode_Poli1',$poli->kdpoli_rs)->whereDate('Tgl_Register','=',$tgl)->first()){
				$cekRegis = '1';
				if($urut = Rsu_Register::select('No_Register','Tgl_Register')->whereRaw("(YEAR(Tgl_Register)=$thn) AND LEFT(No_Register,3)=$tg")->orderby('No_Register','DESC')->first()){
					$nourut = $urut->No_Register + 1;
				}else{
					$nourut = date('y').'20000001';
				}
				$kodeAss = $pembayaran ? $pembayaran->subgroups : ($caraBayar=='BPJS' ? '1008' : '1001');
				$reg = new Rsu_Register; // db rsu
				$reg->TransReg         = 'RE';
				$reg->No_Register      = $nourut;
				$reg->Tgl_Register     = date('Y-m-d H:i:s',strtotime($req->tgl_periksa.$jam));
				$reg->Jam_Register     = $jam;
				$reg->No_RM            = $noRM;
				$reg->Nama_Pasien      = $nama;
				$reg->AlamatPasien     = $alamat;
				$reg->Umur             = $umurReg;
				$reg->Kode_Ass         = $kodeAss;
				$reg->Kode_Poli1       = isset($poli->kdpoli_rs)?$poli->kdpoli_rs:null;
				$reg->JenisKel         = isset($jenisKel)?$jenisKel:(!empty($jenisKel)?$jenisKel:null);
				$reg->Rujukan          = null;
				$reg->NoSEP            = null;
				$reg->NoPeserta        = $nomorkartu;
				$reg->Biaya_Registrasi = 0;
				$reg->Status           = 'Belum Dibayar';
				$reg->NamaAsuransi     = $caraBayar;
				$reg->Japel            = 0;
				$reg->JRS              = 0;
				$reg->TipeReg          = 'REG';
				$reg->SudahCetak       = 'N';
				$reg->BayarPendaftaran = 'N';
				$reg->Tgl_Lahir        = $tglLahir;
				$reg->isKaryawan       = 'N';
				$reg->isProcessed      = 'N';
				$reg->isPasPulang      = 'N';
				$reg->Jenazah          = 'N';
				// $reg1 = Rsu_Register::create($reg);
				$reg->save();
				if (!$reg) {
					DB::rollback();
					return false;
				}
			}
			// return $reg;
			# Update no registrasi to table antrian
			$noRegister = $nourut==''?$reg->No_Register:$nourut;
			// $updateToAntrian = DB::table('antrian')->where('id', $idAntri)->update(['No_Register'=>$noRegister]);
			// if (!$updateToAntrian) {
			// 	DB::rollback();
			// 	return false;
			// }
			if($cekRegis=='1'){
				$param = (object)[
					'No_Register' => $nourut,
					'No_RM'       => $noRM,
					'kode_dokter' => $req->kode_dokter,
					'poli_bpjs'	  => $req->kode_poli
				];
				$storeRiwayatRegis = $this->storeRiwayatRegis($param);
				if(!$storeRiwayatRegis){
					DB::rollback();
					return false;
				}
			}
			DB::commit();
			return $noRegister;
		} catch (\Throwable $e) {
			DB::rollback();
			$log = ['ERROR STORE REGISTRASI ('.$e->getFile().')',false,$e->getMessage(),$e->getLine()];
				Help::logging($log);
			return false;
		}
	}

	public function storeRiwayatRegis($param){
		$getDpjp = rsu_dokter_bridging::where('kodedokter',$param->kode_dokter)->first();
		$addHistory = new Rsu_RiwayatRegistrasi; // db rsu
		$addHistory->No_Register = $param->No_Register;
		$addHistory->no_rm       = $param->No_RM;
		$addHistory->kode_dpjp   = $param->kode_dokter;
		$addHistory->nama_dpjp   = $getDpjp->dokter;
		// $addHistory->poli_bpjs   = $getDpjp->polibpjs;
		$addHistory->poli_bpjs   = $param->poli_bpjs;
		$addHistory->save();
		if(!$addHistory){
			return false;
		}
		return true;
	}

	public function loketToCounter(Request $request){
		date_default_timezone_set("Asia/Jakarta");
		DB::beginTransaction();
		try{
			// $antrian = Antrian::where('kode_booking',$request->kode)->first();
			$antrian = Antrian::where('id',$request->kode)->first();
			$antrian->status = 'counter';
			$antrian->save();
			if($antrian){
				if($antrian->metode_ambil=='KIOSK'){
					$antrianTracer = $this->antrianTracer($antrian->id,'kiosk','loket',2,'update');
				}elseif($antrian->metode_ambil=='SIMAPAN'){
					$antrianTracer = $this->antrianTracer($antrian->id,'simapan','loket',2,'update');
				}else{
					$antrianTracer = $this->antrianTracer($antrian->id,'wa','loket',2,'update');
				}
				$antrianTracer = $this->antrianTracer($antrian->id,'loket','counter',1,'input');
				DB::commit();
				return  ['status'=>'success','message'=>'Pasien berhasil diarahkan ke konter poli.'];
			}
			DB::rollback();
			return ['status'=>'error','message'=>'Pasien gagal diarahkan ke konter poli.'];
		} catch (\Throwable $e) {
			DB::rollback();
			$request->merge(['log_payload'=>[
				'method' => 'function loketToCounter()',
				'url' => $request->url(),
				'file' => $e->getFile(),
				'message' => $e->getMessage(),
				'line' => $e->getLine(),
			]]);
			Help::catchError($request);
			return ['status'=>'error','message'=>'Pasien gagal diarahkan ke konter poli.'];
		}
	}

	public function poliToFarmasi(Request $request){
		$antrian = Antrian::where('kode_booking',$request->kode)->first();
		$antrian->status = 'antrifarmasi';
		$antrian->save();
		if($antrian){
			$request->kodebooking = $antrian->kode_booking;
			$request->waktu = strtotime(date('Y-m-d H:i:s'))*1000;
			$request->taskid = '5';
			$bridgBpjs = new BridgBpjsController;
			$updateWaktu = $bridgBpjs->updateWaktu($request);
			$insertTracer = [
				'antrian_id'    => $antrian->id,
				'from'          => 'poli',
				'to'            => 'farmasi',
				'status_tracer' => 1,
				'tgl'           => date('Y-m-d'),
				'time'          => date('H:i:s')
			];
			$antrianTracer = DB::connection('mysql')->table('antrian_tracer')->insert($insertTracer);
			$res = ['status'=>'success','message'=>'Pasien berhasil diarahkan ke farmasi.'];
		}else{
			$res = ['status'=>'error','message'=>'Pasien gagal diarahkan ke farmasi.'];
		}
		return $res;
	}

	public function pasienSelesai(Request $request){
		date_default_timezone_set("Asia/Jakarta");
		$antrian = Antrian::where('kode_booking',$request->kode)->first();
		$antrian->status = 'selesai';
		$antrian->save();
		if($antrian){
			$request->kodebooking = $antrian->kode_booking;
			$request->waktu = strtotime(date('Y-m-d H:i:s'))*1000;
			$request->taskid = '5';
			$bridgBpjs = new BridgBpjsController;
			$updateWaktu = $bridgBpjs->updateWaktu($request);
			$tracer = DB::table('natusi_apm.antrian_tracer')
				->where('antrian_id',$antrian->id)
				->where('to','poli')
				->update(['status_tracer'=>'3']);
			$res = ['status'=>'success','message'=>'Pelayanan pasien berhasil diakhiri.'];
		}else{
			$res = ['status'=>'error','message'=>'Pelayanan pasien gagal diakhiri.'];
		}
		return $res;
	}

	// Farmasi Start
	public function listAntrianFarmasi(Request $request){
		return view('Admin.antreanBPJS.listAntrian.mainFarmasi');
	}

	public function dataGridFarmasi(Request $request){
		$data = Antrian::getJsonFarmasi($request);
		return response()->json($data);
	}

	public function panggilFarmasi(Request $request){
		date_default_timezone_set("Asia/Jakarta");
		$kode = $request->kode;
		$data = DB::table('natusi_apm.antrian')
			->where('kode_booking',$kode)
			->first();
		// return response()->json($data);
		if(!empty($data)){
			$antrian = DB::table('natusi_apm.antrian')
				->where('kode_booking',$kode)
				->update(['status'=>'panggilfarmasi']);
			$tracer = DB::table('natusi_apm.antrian_tracer')
				->where('antrian_id',$data->id)
				->where('to','farmasi')
				->update(['status_tracer'=>'2']);
			if($antrian){
				$request->kodebooking = $kode;
				$request->waktu = strtotime(date('Y-m-d H:i:s'))*1000;
				$request->taskid = '6';
				$bridgBpjs = new BridgBpjsController;
				$updateWaktu = $bridgBpjs->updateWaktu($request);
				$res = ['status'=>'success','message'=>'Antrian berhasil dipanggil'];
			}else{
				$res = ['status'=>'error','message'=>'Antrian gagal dipanggil'];
			}
		}else{
			$res = ['status'=>'error','message'=>'Antrian tidak ditemukan'];
		}
		return $res;
	}

	public function selesaiFarmasi(Request $request){
		date_default_timezone_set("Asia/Jakarta");
		$antrian = Antrian::where('kode_booking',$request->kode)->first();
		$antrian->status = 'selesai';
		$antrian->save();
		if($antrian){
			$request->kodebooking = $antrian->kode_booking;
			$request->waktu = strtotime(date('Y-m-d H:i:s'))*1000;
			$request->taskid = '7';
			$bridgBpjs = new BridgBpjsController;
			$updateWaktu = $bridgBpjs->updateWaktu($request);
			$tracer = DB::table('natusi_apm.antrian_tracer')
				->where('antrian_id',$antrian->id)
				->where('to','poli')
				->update(['status_tracer'=>'3']);
			$res = ['status'=>'success','message'=>'Pelayanan pasien berhasil diakhiri.'];
		}else{
			$res = ['status'=>'error','message'=>'Pelayanan pasien gagal diakhiri.'];
		}
		return $res;
	}
	// Farmasi End
}