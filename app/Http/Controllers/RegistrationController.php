<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Carbon\Carbon;
use App\Helpers\apm as Help;
use App\Http\Controllers\Controller;
use App\Http\Libraries\Requestor;
use App\Http\Models\Identity;
use App\Http\Models\Antrian;
use App\Http\Models\AntPasienBaru;
use App\Http\Models\Poli;
use App\Http\Models\KodeAwalanPoli;
use App\Http\Models\rsu_poli;
use App\Http\Models\Apm;
use App\Http\Models\Customer;
use App\Http\Models\rsu_customer;
use App\Http\Models\Holidays;
use App\Http\Models\CC;
use App\Http\Models\Rsu_cc;
use App\Http\Models\Bantuan;
use App\Http\Models\RfidApm;
use App\Http\Models\Rsu_rfidApm;
use App\Http\Models\RawatJalanTindakan;
use App\Http\Models\RiwayatRegistrasi;
use App\Http\Models\Rsu_RiwayatRegistrasi;
use App\Http\Models\Rsu_RawatJalanTindakan;
use App\Http\Models\Bridgingpoli;
use App\Http\Models\Setupall;
use App\Http\Models\Register;
use App\Http\Models\BridgingKdPoli;
use App\Http\Models\Rsu_Bridgingpoli;
use App\Http\Models\Rsu_setupall;
use App\Http\Models\Rsu_Register;
use App\Http\Models\Rsu_BridgingKdPoli;
use App\Http\Models\rsu_dokter_bridging;
use App\Traits\KonfirmasiAntrianTraits;
use Illuminate\Support\Facades\Log;
use Redirect, DB;

class RegistrationController extends Controller{
	use KonfirmasiAntrianTraits;
	/* DEV */
	// private $url = 'https://apijkn-dev.bpjs-kesehatan.go.id/vclaim-rest-dev';
	// private $consid = '21095';
	// private $secretkey = 'rsud6778ws122mjkrt';
	// private $userkey = '21f330a3e8e9f281d845f6b545b23653';
	/* PRODUCTION */
	private $url = "https://apijkn.bpjs-kesehatan.go.id/vclaim-rest";
	private $consid = '21095';
	private $secretkey = 'rsud6778ws122mjkrt';
	private $userkey = '2079632035f01e757d81a8565b074768';

	public function __construct(){
		$this->data['identitas'] = Identity::find(1);
	}

	public function cariCetakUlang(Request $request){
		$kode = $request->kode;
		$data = '';
		$q = Antrian::query()->where('tgl_periksa',date('Y-m-d'));

		if(is_numeric($kode)){
			$bot = DB::connection('mysql')->table('bot_pasien as b')
				->join('bot_data_pasien as bd', 'b.id', '=', 'bd.idBots')
				->where('b.random', $kode)
				->where('tglBerobat', date('Y-m-d'))
				->first();
			if($bot){
				$data = ($bot->caraBayar=='BPJS') ? $q->where('nomor_kartu',$bot->nomor_kartu)->first() : $q->where('nik',$bot->nik)->first();
			}
		}else{
			$data = $q->where('kode_booking',$kode)->first();
		}

		if($data){
			return [
				'code' => 200,
				'message' => 'Data berhasil ditemukan',
				'data' => $this->generateDataPasien($data)
			];
		}else{
			return [
				'code' => 204,
				'message' => 'Data tidak ditemukan',
				'data' => []
			];
		}
	}
	
	public function ApiDokter(Request $request){
		$arrdokter = [];
		$getDay = date('D');
		if ($getDay != 'Sat') {
			$jadDok = '08.00-14.00';
		} else {
			$jadDok = '08.00-12.00';
		}
		if ($getDay == 'Sun') {
			$resMetaData = [
				'metaData' => (object)[
					'code' 	  	   => 401,
					'message' 	   => "Tidak ada jadwal dokter pada poli tersebut hari ini",
					'head_message' => "Maaf",
				]
			];
		} else {
			$resMetaData = [
				'metaData' => (object)[
					'code' 	  => 200,
					'message' => "OK",
				]
			];
		}
		// $arrdokgigi = array(

		// 	[
		// 		'kodesubspesialis' => 'GIG',
		// 		'kodedokter' => 16448,
		// 		'kodepoli' => 'GIG',
		// 		'jadwal' => $jadDok,
		// 		'namadokter' => 'drg. Ririen Prawestri',
		// 	],
		// 	[
		// 		'kodesubspesialis' => 'GIG',
		// 		'kodedokter' => 16450,
		// 		'kodepoli' => 'GIG',
		// 		'jadwal' => $jadDok,
		// 		'namadokter' => 'drg. Nurul Mufida',
		// 	]
		// );
		$arrdokgigi = [];
		$arrdokpsikologi = array(
			[
				'kodesubspesialis' => 'PSY',
				'kodedokter' => 161115,
				'kodepoli' => 'PSY',
				'jadwal' => $jadDok,
				'namadokter' => 'HASRI ARDILLA S.Psi.,M.Psi.,Psikolog',
			],
		);
		$arrdokgizi = array(
			[
				'kodesubspesialis' => 'GIZ',
				'kodedokter' => 161111,
				'kodepoli' => 'GIZ',
				'jadwal' => $jadDok,
				'namadokter' => 'SAYEKTI RENI NUGRAHENI, S.Gz',
			],
			[
				'kodesubspesialis' => 'GIZ',
				'kodedokter' => '161112',
				'kodepoli' => 'GIZ',
				'jadwal' => $jadDok,
				'namadokter' => 'HERLINA MEI WULANDARI,S.Gz',
			],
			[
				'kodesubspesialis' => 'GIZ',
				'kodedokter' => 161113,
				'kodepoli' => 'GIZ',
				'jadwal' => $jadDok,
				'namadokter' => 'ENI FAHIMA, S.Gz',
			],
			[
				'kodesubspesialis' => 'GIZ',
				'kodedokter' => 161114,
				'kodepoli' => 'GIZ',
				'jadwal' => $jadDok,
				'namadokter' => 'UNTARI, SKM',
			]
		);
		$arrdokvct = array(
			[
				'kodesubspesialis' => 'VCT',
				'kodedokter' => 16426,
				'kodepoli' => 'VCT',
				'jadwal' => $jadDok,
				'namadokter' => 'dr. TUSY PUNGKI HARTANTO',
			],
		);
		$arrdokmcu = array(
			[
				'kodesubspesialis' => 'MCU',
				'kodedokter' => 16428,
				'kodepoli' => 'MCU',
				'jadwal' => $jadDok,
				'namadokter' => 'dr. WIWIEK ANDAYANI',
			],
		);
		if ($request->kodePoli == "040") {
			$ks = "040";
			$kode = "ANA"; // Kode Poli BPJS
		}else if($request->kodePoli == "017"){
			$ks = "017";
			$kode = "BED"; // Kode Poli BPJS
		} else if($request->kodePoli == "HDL") {
			$ks = "16454";
			$kode = "INT"; // Kode Poli BPJS
		} else {
			$ks = "";
			$kode = strtoupper($request->kodePoli); // Kode Poli BPJS
		}

		$request->kodePoli = $kode;
		$tanggal = $request->tanggal; // Tanggal Jadwal Dokter
		$getdok = new BridgBpjsController;

		// if($request->kodePoli=='GIG'){
		if(in_array($request->kodePoli,['BDM','GIG','GND','KON'])){
			$ks = "GIG";
			$request->kodePoli = "KON";
			$getdokter = $getdok->refJadDok($request);
			if($getdokter['metaData']->code==200){
				foreach($getdokter['response'] as $key => $val){
					// $arrdokgigi[] = $val;
					array_push($arrdokgigi,(array)$val);
				}
			}
			$request->kodePoli = "BDM";
			$getdokter = $getdok->refJadDok($request);
			if($getdokter['metaData']->code==200){
				foreach($getdokter['response'] as $key => $val){
					// $arrdokgigi[] = $val;
					array_push($arrdokgigi,(array)$val);
				}
			}
			$request->kodePoli = "GND";
			$getdokter = $getdok->refJadDok($request);
			if($getdokter['metaData']->code==200){
				foreach($getdokter['response'] as $key => $val){
					// $arrdokgigi[] = $val;
					array_push($arrdokgigi,(array)$val);
				}
			}
			$getdokter['response'] = $arrdokgigi;
		} else if($request->kodePoli=='PSY') {
			$getdokter = $resMetaData;
			$getdokter['response'] = $arrdokpsikologi;
		} else if($request->kodePoli=='GIZ') {
			$getdokter = $resMetaData;
			$getdokter['response'] = $arrdokgizi;
		} else if($request->kodePoli=='VCT') {
			$getdokter = $resMetaData;
			$getdokter['response'] = $arrdokvct;
		} else if($request->kodePoli=='MCU') {
			$getdokter = $resMetaData;
			$getdokter['response'] = $arrdokmcu;
		} else{
			$getdokter = $getdok->refJadDok($request);
		}

		// Log::debug(json_encode($arrdokgigi));
		// Log::debug($arrdokgigi);

		if ($getdokter['metaData']->code == 200) {
			$valdokter = $getdokter['response'];
			if($ks=="040" || $ks=="017"){
				$res = array_filter($getdokter['response'],function($var)use($ks){
					if(stripos($var->kodesubspesialis,$ks)!==false){
						return $var;
					}
				});
				$kdsubspesialis = $ks;
			} else if($ks=="16454") {
				$res = array_filter($getdokter['response'],function($var)use($ks){
					if(stripos($var->kodedokter,$ks)!==false){
						return $var;
					}
				});
				$kdsubspesialis = $ks;
			} else{
				if($ks=="GIG" || $kode=="PSY" || $kode=="GIZ" || $kode=="VCT" || $kode=="MCU"){
					$kdsubspesialis = $valdokter[0]['kodesubspesialis'];
				}else{
					foreach ($valdokter as $key => $v) {
						$kdsubspesialis = $v->kodesubspesialis;
					}
				}
			}

			if ($ks=="16454") {
				$dokter = rsu_dokter_bridging::where('kodedokter', $kdsubspesialis)->get();
			} else {
				$dokter = rsu_dokter_bridging::where('polibpjs', $kdsubspesialis)->get();
			}

			if ($ks=="GIG") {
				$arrdokter = $arrdokgigi;
			} else if($kode=="PSY") {
				$arrdokter = $arrdokpsikologi;
			} else if($kode=="GIZ") {
				$arrdokter = $arrdokgizi;
			} else if($kode=="VCT") {
				$arrdokter = $arrdokvct;
			} else if($kode=="MCU") {
				$arrdokter = $arrdokmcu;
			} else {
				foreach ($dokter as $key => $val) {
					foreach ($valdokter as $dok => $d) {
						if (!empty($val->kodedokter)) {
							if ($val->kodedokter==$d->kodedokter) {
								$array['kodesubspesialis'] = $d->kodesubspesialis;
								$array['kodedokter'] = $d->kodedokter;
								$array['kodepoli'] = $d->kodepoli;
								$array['jadwal'] = $d->jadwal;
								$array['namadokter'] = $d->namadokter;
								array_push($arrdokter, $array);
							}
						}
						
					}	
				}
			}

			$data = [
				'status' => 'Success',
				'code'   =>	$getdokter['metaData']->code,
				'head_message' => 'Success!',
				'message' => $getdokter['metaData']->message,
				'dokter' =>$arrdokter
			];
		} else {
			$data = [
				'status'=>'error',
				'code'=>$getdokter['metaData']->code,
				'head_message'=>'Whoops!',
				'message'=>'Tidak ada jadwal dokter',
			];
		}
		return response()->json($data);
	}

	public function jadwalDokterKiosk(Request $request){
		$request->merge([
			'jenis_pembayaran' => $request->jenis_pasien,
			'kodePoli' => $request->kode_poli, # Kode poli rs
			'tanggalPeriksa' => $request->tanggal,
		]);
		Rsu_Bridgingpoli::convertBPJStoRS($request); # Convert kode poli BPJS ke RS
		return Help::randomDokter($request);
	}

	public function indexAntrian(Request $request){
		$id_kiosk = $request->id_kiosk;
		$ignore = ['ALG','UGD','ANU'];
		// if(date('d-m-Y',strtotime('now'))=='20-06-2024'){
		// 	array_push($ignore,'017');
		// }
		// return $ignore;
		$poli = Rsu_Bridgingpoli::join('tm_poli', 'mapping_poli_bridging.kdpoli_rs', '=', 'tm_poli.KodePoli')
			// ->whereNotIn('kdpoli',['ALG','UGD','ANU'])
			->whereNotIn('kdpoli',$ignore)
			->groupBy('mapping_poli_bridging.kdpoli_rs')
			->orderBy('tm_poli.NamaPoli','ASC')
			->get();

		$waktu = '06:30'; # Init default jam buka, jika di table holiday tidak ada data
		if($holiday = Holidays::orderBy('id_holiday','ASC')->first()){
			$jam = explode(':',$holiday->jam);
			$waktu = "$jam[0]:$jam[1]";
		}
		
		if($id_kiosk > 8){
			return abort(404);
		}elseif(!is_numeric($id_kiosk)){
			return abort(404);
		}

		$generateCode = $this->generateQrCode($id_kiosk);
		$getantrian = [
			'poli' => $poli,
			'jambuka' => $waktu,
			'jamtutup' => '23:59',
			'jamsekarang' => date('H:i',strtotime('now')),
		];
		return view('registration.indexNew', $getantrian)->with('data',$this->data);
	}

	public function cari(Request $request){
		$norm = $request->no_rm;
		$bpjs = $request->bpjs;
		$nik = $request->nik;
		$jkn = $request->jkn;

		if(!empty($norm)) {
			$antrian = DB::connection('dbrsud')->table('tm_customer')->where('KodeCust','like',"$norm%")->first();
		} else if (!empty($bpjs)) {
			$antrian = DB::connection('dbrsud')->table('tm_customer')->where('FieldCust1','like',"$bpjs%")->first();
		} else if (!empty($nik)) {
			$antrian = DB::connection('dbrsud')->table('tm_customer')->where('NoKtp','like',"$nik%")->first();
		} else if (!empty($jkn)) {
			$antrian = DB::connection('mysql')->table('tm_customer_verif')->where('NoKtp','like',"$jkn%")->where('verifikasi', 0)->first();
		} else {
			$antrian = '';
		}

		if (!empty($antrian)) {
			return ['code' => 200, 'response' => 'Data ditemukan', 'antrian' => $antrian];
		} else {
			return ['code' => 201, 'response' => 'Data tidak ditemukan'];
		}
	}

	public function pilihpasien(Request $request){
		$norm = $request->no_rm;
		$antrian = DB::connection('dbrsud')->table('tm_customer')->where('KodeCust','like',"$norm%")->first();

		return ['antrian' => $antrian];
	}

	public function politujuan(Request $request){
		$no_rm = $request->no_rm;
		$no_bpjs = $request->no_bpjs;
		$nik = $request->nik;
		$kddokter = $request->kddokter;
		$jadwal = $request->jadwal;
		$kdpoli  = $request->kodepoli;
		if (in_array($kdpoli, ['GIG','KON','GND','BDM'])) {
			$kdpoli = 'GIG';
		}
		$pasien  = $request->pasien;
		$tanggal = $request->tglperiksa;

		date_default_timezone_set("Asia/Jakarta");
		if (!empty($no_rm)) {
			$cekPasien = DB::connection('dbrsud')->table('tm_customer')->where('KodeCust',$no_rm)->first();
		} else if(!empty($no_bpjs)){
			$cekPasien = DB::connection('dbrsud')->table('tm_customer')->where('KodeCust',$no_bpjs)->first();
		} else if(!empty($nik)) {
			$cekPasien = DB::connection('dbrsud')->table('tm_customer')->where('KodeCust',$nik)->first();
		} else {
			$cekPasien = '';
		}

		// Generate no. antrian
		// if (!empty($cekPasien)) {
		// 	$prefix = 'L';
		// } else {
		// 	$prefix = 'B';
		// }
		// $antri = DB::connection('mysql')->table('antrian')->select('no_antrian')
		// 		->where('tgl_periksa',$tanggal)->where('no_antrian','like',"$prefix%")
		// 		->orderBy('no_antrian','desc')->first();
		// $num = 0;
		// if(!empty($antri)){
		// 	$num = (int)substr($antri->no_antrian, 1);
		// }
		// $angkaAntri = sprintf("%03d",$num+1);
		// $nextAntri = "$prefix".$angkaAntri;
		// // kode booking
		// $kodebooking = date('dmy').$nextAntri;

		// Get Poli
		$getpoli = Rsu_Bridgingpoli::with('tm_poli')->where('kdpoli', $kdpoli)->first();
		return [
				'poli' => $getpoli, 
				// 'no_antrian' => $nextAntri, 
				// 'kdbooking' => $kodebooking,
				'kddokter' => $kddokter,
				'jadwal' => $jadwal,
				'cekpasien' => $cekPasien
			];
	}

	public function cariRujukan(Request $request){
		if($request->jenisKunjungan=='kontrol'){
			$cekRujukan = new BridgingController;
			$request->noSurat = strtoupper($request->noRujuk);
			$respon2 = $cekRujukan->cekSkdp($request);
			if($respon2['metaData']->code==200){
				$data = [
					'status'=>'success',
					'code'=>200,
					'head_message'=>'Success',
					'message'=>'Rujukan Ditemukan',
					'nik' => 'kontrol'
				];
			}else{
				$data = [
					'status'=>'error',
					'code'=>250,
					'head_message'=>'Error',
					'message'=>'Rujukan Tidak Ditemukan',
					'noRujuk' => ''
				];
			}
		}else{
			$cekRujukan = new ApiSimapanController;
			// $request->noBpjs = $respon['data']->peserta->noKartu;
			$request->noRujuk = strtoupper($request->noRujuk);
			$respon2 = $cekRujukan->cekRujukan($request);
			if ($respon2['code'] == 200) {
				// $noRujuk = $respon2['data']['noRujuk'];
				$nik = $respon2['data']['nik'];
				$data = [
					'status'=>'success',
					'code'=>200,
					'head_message'=>'Success',
					'message'=>'Rujukan Ditemukan',
					// 'noRujuk' => $noRujuk,
					'nik' => $nik
				];
			} else {
				$data = [
					'status'=>'error',
					'code'=>250,
					'head_message'=>'Error',
					'message'=>'Rujukan Tidak Ditemukan',
					'noRujuk' => ''
				];
			}
		}
		return response()->json($data);
	}

	public function ambilAntrianSave(Request $request){
		date_default_timezone_set("Asia/Jakarta");
		$no_rm   = $request->no_rm;
		$no_bpjs = $request->no_bpjs;
		$nik     = $request->nik;
		$request->metodes = "KIOSK"; // penting jangan di hapus
		DB::beginTransaction();
		try {
			$query = Antrian::select('nik','id','is_pasien_baru','no_antrian_pbaru','nomor_antrian_poli','kode_booking','tgl_periksa','kode_poli','jenis_pasien')
				->where('tgl_periksa',$request->tglperiksa)
				->where('nik',$request->nik);
			if($request->jenis_pasien==='BPJS'){
				$query->where('jenis_pasien','BPJS');
			}else{
				$query->where('jenis_pasien','!=','BPJS')->where('kode_poli',$request->kodepoli);
			}
			$cekDuplikatAntrian = $query->first();
			if($cekDuplikatAntrian){ # Cek duplikat antrian by nik & tanggal
				$tujuanpoli = Rsu_Bridgingpoli::with('tm_poli')->where('kdpoli', $cekDuplikatAntrian->kode_poli)->first();
				DB::rollback();
				return [
					'status'=>'success',
					'code'=>200,
					'head_message'=>'Success',
					'message'=>'Peserta harap 30 menit lebih awal guna pencatatan administrasi.',
					'data'=> $cekDuplikatAntrian,
					'poli'=> $tujuanpoli
				];
			}
			$prefix = 'NB'; #UMUM/ASURANSI LAIN
			if($request->jenis_pasien=='BPJS'){
				$prefix = 'B';
			}
			$length = strlen($prefix)+3;
			$antri = DB::connection('mysql')->table('antrian')->select('no_antrian')
				->where('tgl_periksa',$request->tglperiksa)
				->whereRaw("LENGTH(no_antrian)=$length")
				->where('no_antrian','like',"$prefix%")
				->orderBy('no_antrian','desc')->first();
			$num = 0;
			if(!empty($antri)){
				$num = (int)substr($antri->no_antrian, -3);
			}
			$angkaAntri = sprintf("%03d",$num+1);
			$nextAntri = "$prefix".$angkaAntri;
			$kodebooking = date('dmy').$nextAntri; // kode booking
			$request->kodebooking = $kodebooking;
			$request->no_antrian = $nextAntri;
			if ($request->pasien=='N') {
				$no_antrian_poli = $this->generateNoAntrianPoli($request->tglperiksa, $request->kodepoli);
			}
			if ($request->pasien=='Y') {
				$noPasBaru = $this->generateNoAntrianBaru($request);
			}
			$antrian = new Antrian;
			$antrian->nik = $request->nik;
			$antrian->nomor_kartu = !empty($request->no_bpjs)?$request->no_bpjs:null;
			if ($request->pasien=='Y') {
				$no_rm = '00000000000';
				$request->no_rm = '00000000000';
				$status = $request->status;
				$request->status = $status;
			} else {
				$no_rm = $request->no_rm;
				$status = 'antripoli';
				$request->status = $status;
			}
			$nohp = '000000000000';
			$antrian->nohp = $nohp;
			$antrian->no_rm = $no_rm;
			$antrian->kode_poli = $request->kodepoli;
			$antrian->no_antrian = $nextAntri;
			$antrian->no_antrian_pbaru = ($request->pasien=='Y') ? $noPasBaru : null;
			$antrian->nomor_antrian_poli = ($request->pasien=='N') ? $no_antrian_poli : null;
			$antrian->status = $status;
			$antrian->tgl_periksa = $request->tglperiksa;
			$antrian->jenis_pasien = $request->jenis_pasien;
			$antrian->kode_booking = $kodebooking;
			$antrian->is_geriatri = $request->geriatri;
			$antrian->metode_ambil = $request->metode;
			$antrian->is_pasien_baru = $request->pasien;
			$antrian->kode_dokter = $request->kddokter;
			$antrian->jam_praktek = $request->jadwal;
			if ($request->jenis_pasien=='UMUM') {
				$request->jenis_kunjungan = '2';
				$antrian->jenis_kunjungan = $request->jenis_kunjungan;
			} else {
				$antrian->jenis_kunjungan = $request->jenis_kunjungan;
				$antrian->nomor_referensi = $request->no_referensi;
			}
			$antrian->save();
			if(!$antrian){
				DB::rollback();
				return [
					'status'=>'error',
					'code'=>400,
					'head_message'=>'Whoops!',
					'message'=>'Gagal menyimpan antrian, Silahkan coba lagi.',
					'data'=> '',
					'poli'=> ''
				];
			}
			$countAntrian = Antrian::where([
				'no_antrian'=>$antrian->no_antrian,
				'tgl_periksa'=>$antrian->tgl_periksa
			])->count();
			if($countAntrian>1){
				DB::rollback();
				return [
					'status'=>'error',
					'code'=>400,
					'head_message'=>'Whoops!',
					'message'=>'Gagal menyimpan antrian, Silahkan coba lagi.',
					'data'=> '',
					'poli'=> ''
				];
			}
			$idAntri  = $antrian->id;
			$kodePoli = $antrian->kode_poli;
			// filling
			$cekFilling = DB::connection('mysql')->table('filling')
				->where('no_rm',$no_rm)
				->where('tgl_periksa',$request->tglperiksa)
				->first();
			$dataFilling = [
				'no_rm'       => $no_rm,
				'tgl_periksa' => $request->tglperiksa,
				'status'      => 'dicari',
				'antrian_id'  => $antrian->id,
				'tgl_filling' => date('Y-m-d H:i:s'),
			];
			// filling
			if($no_rm!='00000000000' && $no_bpjs){
				// $tmCust = rsu_customer::where('KodeCust', $no_rm)->update(['NoKtp'=>$request->nik,'FieldCust1'=>$no_bpjs]);
				$tmCust = rsu_customer::where('KodeCust', $no_rm)->first();
				$tmCust->NoKtp = $request->nik;
				$tmCust->FieldCust1 = $no_bpjs;
				$tmCust->save();
				if(!$tmCust){
					DB::rollback();
					return [
						'status'=>'error',
						'code'=>400,
						'head_message'=>'Whoops!',
						'message'=>'Gagal update customer, Silahkan coba lagi.',
						'data'=> '',
						'poli'=> ''
					];
				}
			}
			if(empty($cekFilling) && $request->pasien=='N'){ // insertFilling
				$insertFilling = DB::connection('mysql')->table('filling')->insert($dataFilling);
				if(!$insertFilling){
					DB::rollback();
					return [
						'status'=>'error',
						'code'=>400,
						'head_message'=>'Whoops!',
						'message'=>'Gagal simpan filling, Silahkan coba lagi.',
						'data'=> '',
						'poli'=> ''
					];
				}
			}
			if ($request->pasien=='Y') {
				$antrianTracer = $this->antrianTracer($idAntri,'kiosk','loket',1,'input');
				if(!$antrianTracer){
					DB::rollback();
					return [
						'status'=>'error',
						'code'=>400,
						'head_message'=>'Whoops!',
						'message'=>'Gagal simpan antrian tracer, Silahkan coba lagi.',
						'data'=> '',
						'poli'=> ''
					];
				}
			} else {
				$antrianTracer = $this->antrianTracer($idAntri,'kiosk','poli',1,'input');
				if(!$antrianTracer){
					DB::rollback();
					return [
						'status'=>'error',
						'code'=>400,
						'head_message'=>'Whoops!',
						'message'=>'Gagal simpan antrian tracer, Silahkan coba lagi.',
						'data'=> '',
						'poli'=> ''
					];
				}
				$storeRegistrasi = new AntrianController;
				$respRegistrasi = $storeRegistrasi->storeRsuRegister($antrian);
				if(!$respRegistrasi){
					DB::rollback();
					return [
						'status'=>'error',
						'code'=>400,
						'head_message'=>'Whoops!',
						'message'=>'Gagal simpan registrasi, Silahkan coba lagi.',
						'data'=> '',
						'poli'=> ''
					];
				}
				$antrian->No_Register = $respRegistrasi;
				$antrian->save();
				if(!$antrian){
					DB::rollback();
					return [
						'status'=>'error',
						'code'=>400,
						'head_message'=>'Whoops!',
						'message'=>'Gagal update no registrasi antrian, Silahkan coba lagi.',
						'data'=> '',
						'poli'=> ''
					];
				}
			}
			$tujuanpoli = Rsu_Bridgingpoli::with('tm_poli')->where('kdpoli', $request->kodepoli)->first();
			if(in_array($request->kodepoli, ['GIG','GIZ','MCU','PSY','VCT'])){
				$data = [
					'status'=>'success',
					'code'=>200,
					'head_message'=>'Success',
					'message'=>'Peserta harap 30 menit lebih awal guna pencatatan administrasi.',
					'data'=> $antrian,
					'poli'=> $tujuanpoli
				];
			}else{
				if($request->jenis_pasien=='BPJS'){
					// API SIMAPAN (CEK NO BPJS)
					$cekpeserta      = new ApiSimapanController;
					$jeniscek        = 'nik';
					$request->jenis  = $jeniscek;
					$request->nobpjs = $request->nik;
					$respon2 = $cekpeserta->cekBPJS($request);

					if($respon2['code']==200){
						// API BRIDGBPJS (VALIDASI DATA)
						$addAntrian = new BridgBpjsController;
						$request->no_bpjs = $respon2['data']->peserta->noKartu;
						$respon = $addAntrian->antreanAdd($request);
						
						if($respon['metaData']->code==200){
							$data = [
								'status'=>'success',
								'code'=>200,
								'head_message'=>'Success',
								'message'=>'Peserta harap 30 menit lebih awal guna pencatatan administrasi.',
								'data'=> $antrian,
								'poli'=> $tujuanpoli
							];
						}else{
							if(stripos($respon['metaData']->message,'duplikasi Kode')!==false){
								$data = [
									'status'=>'success',
									'code'=>200,
									'head_message'=>'Success',
									'message'=>'Peserta harap 30 menit lebih awal guna pencatatan administrasi.',
									'data'=> $antrian,
									'poli'=> $tujuanpoli
								];
							}else{
								DB::rollback();
								$data = [
									'status'=>'error',
									'code'=>$respon['metaData']->code,
									'head_message'=>'Whoops!',
									'message'=>$respon['metaData']->message,
									'data'=> $request->all(),
									'poli'=> ''
								];
							}
						}
					}else{
						$data = [
							'status'=>'error',
							'code'=>250,
							'head_message'=>'Error',
							'message'=>'NIK Yang Anda Masukkan Tidak Terdaftar BPJS',
							'data'=> $request->all(),
							'poli'=> '',
						];
					}
				}else{
					$addAntrian = new BridgBpjsController;
					$respon = $addAntrian->antreanAdd($request);
					if($respon['metaData']->code==200){
						$data = [
							'status'=>'success',
							'code'=>200,
							'head_message'=>'Success',
							'message'=>'Peserta harap 30 menit lebih awal guna pencatatan administrasi.',
							'data'=> $antrian,
							'poli'=> $tujuanpoli
						];
					}else{
						// Jika Messege Duplikasi KodeBooking
						if(stripos($respon['metaData']->message,'duplikasi Kode')!==false){
							$data = [
								'status'=>'success',
								'code'=>200,
								'head_message'=>'Success',
								'message'=>'Peserta harap 30 menit lebih awal guna pencatatan administrasi.',
								'data'=> $antrian,
								'poli'=> $tujuanpoli
							];
						}else{
							DB::rollback();
							$data = [
								'status'=>'error',
								'code'=>$respon['metaData']->code,
								'head_message'=>'Whoops!',
								'message'=>$respon['metaData']->message,
								'data'=> $request->all(),
								'poli'=> ''
							];
						}
					}
				}
			}
			DB::commit();
			return response()->json($data);
		} catch (\Throwable $e) {
			DB::rollback();
			$log = ['ERROR AMBIL KIOSK ANTRIAN ('.$e->getFile().')',false,$e->getMessage(),$e->getLine()];
			Help::logging($log);
			return false;
		}
	}

	public function cetakAntrian($id){
		$antrian = Antrian::where('id', $id)->first();
		$poli = $antrian->kode_poli;
		$tujuanpoli = Rsu_Bridgingpoli::with('tm_poli')
					->where('kdpoli', $poli)
					->first();
		$from = 'Loket Pendaftaran';
		$cek  = 'Loket';
		if($antrian->is_pasien_baru!="Y" && $antrian->metode_ambil!='KIOSK'){
			$cek  = 'Konter';
			$from = $tujuanpoli->tm_poli->NamaPoli;
		}
		$noPoli = $antrian->nomor_antrian_poli;
		$antrian = [
			'antrian'    => $antrian,
			'noAntrian'  => $noPoli!=''?$noPoli:$antrian->no_antrian,
			'tujuanpoli' => $tujuanpoli,
			'from'       => $from,
			'cek'        => $cek,
		];
		return view('cetak.cetak-antrian', $antrian)->with('data',$this->data);
	}

	public function generateNoAntrianPoli($today, $kdpoli)
	{
		$prefix = KodeAwalanPoli::where('kdpoli', $kdpoli)->first()->kode_awal;
		$length = strlen($prefix)+3;
		$antrian = DB::connection('mysql')->table('antrian')->select('nomor_antrian_poli')
			->where('tgl_periksa',$today)
			->whereRaw("LENGTH(nomor_antrian_poli)=$length")
			->where('nomor_antrian_poli','like',"$prefix%")
			->orderBy('nomor_antrian_poli','desc')
			->first();

		$num = 0;
		if(!empty($antrian)){
			$num = (int)substr($antrian->nomor_antrian_poli, -3);
		}
		$angkaAntri = sprintf("%03d",$num+1);
		$nextAntri = "$prefix".$angkaAntri;
		return $nextAntri;
	}
	// End Ambil Antrian

	// Start Konfirmasi Antrian
	public function konfirmasiAntrian(Request $request){
		$id = $request->id;
		$antrian = Antrian::find($id);
		
		$data_antri = [
			'antrian' => $antrian
		];

		return view('registration.pendaftaran.konfirmasi-antrian', $data_antri)->with('data',$this->data);
	}

	public function showAntrian(Request $request){
		return view('registration.pendaftaran.show-antrian')->with('data',$this->data);
	}

	public function antrianManual(Request $request){
		return view('registration.pendaftaran.antrian-manual')->with('data',$this->data);
	}
	// End Konfirmasi Antrian

	public function index(Request $request){
		return view('registration.main')->with('data',$this->data);
	}

	public function help(Request $request){
		$this->data['helper'] = Bantuan::all();
		return view('registration.help.main')->with('data',$this->data);
	}

	public function detailHelper(Request $request){
		$data['helper'] = Bantuan::find($request->id);
		$content = view('registration.help.detail',$data)->render();
		return ['status' => 'success', 'content' => $content];
	}

	public function formPendaftaran(Request $request){
		$data['title'] = $request->title;
		$data['jenis'] = $request->jenis;
		$content = view('registration.pendaftaran.formPendaftaran',$data)->render();
		return ['status' => 'success', 'content' => $content];
	}

	public function confirmCust(Request $request){
		if ($request->no_identitas != null) {
			$no_identitas = $request->no_identitas;
			$cekRFID = Rsu_rfidApm::where('noRfid',$no_identitas)->first(); // db rsu
			if (empty($cekRFID)) {
				$cekRM = rsu_customer::where('KodeCust',$no_identitas)->first(); // db rsu
				if (empty($cekRM)) {
					$cekBPJS = rsu_customer::where('FieldCust1', $no_identitas)->first(); //db rsu
					if (empty($cekBPJS)) {
						$statusAnggota = 'Tidak';
					}else{
						$kodeCus = $cekBPJS->KodeCust;
						$statusAnggota = 'Terdaftar';
						$statusInput = 'BPJS';
						$data['customer'] = $cekBPJS;
					}
				}else{
					$kodeCus = $cekRM->KodeCust;
					$statusAnggota = 'Terdaftar';
					$statusInput = 'RM';
					$data['customer'] = $cekRM;
				}
			}else{
				$datPas = rsu_customer::where('KodeCust',$cekRFID->KodeCust)->first(); // db rsu
				$kodeCus = $datPas->KodeCust;
				$statusAnggota = 'Terdaftar';
				$statusInput = 'RFID';
				$data['customer'] = $datPas;
			}
			if ($statusAnggota == 'Terdaftar') {
				$cekCC = Rsu_cc::where('norm', $kodeCus)->where('KET','Blokir')->first(); // db rsu
				if (empty($cekCC)) {
					date_default_timezone_set('Asia/Jakarta');
					if ($request->jenis_pendaftaran == 'BPJS') {
						$idenPas = rsu_customer::where('KodeCust', $kodeCus)->first(); // db rsu
						if (!empty($idenPas)) {
							$data['noBpjs'] = $idenPas->FieldCust1;
							$url = $this->url."/Rujukan/Peserta/".$idenPas->FieldCust1;
							$consID = $this->consid; //customer ID RS
							$secretKey = $this->secretkey; //secretKey RS
							$method = 'GET';
							$port = '8080';
							$params = '';

							$result = Requestor::set_new_curl_bridging($url, $params, $method, $consID, $secretKey, $port,'','');
							if ($result === false) {
								return ['status' => 'error', 'message' => 'Tidak Terhubung ke Server !!'];
							}else{
								$results = [
									'metaData' => json_decode($result['metaData']),
									'response' => json_decode($result['response']),
								];
								if ($results['response'] == null) {
									$url2 = $this->url."/Rujukan/RS/Peserta/".$idenPas->FieldCust1;
									$result2 = Requestor::set_new_curl_bridging($url2, $params, $method, $consID, $secretKey, $port,'','');
									if ($result2 === false) {
										return ['status' => 'error', 'messages' => 'Tidak Dapat Terhubung ke Server !!'];
									}else{
										$results = [
											'metaData' => json_decode($result2['metaData']),
											'response' => json_decode($result2['response']),
										];
										if ($results['response'] != null) {
											$prosHas = 1;
										}else{
											$prosHas = 0;
										}
									}
								}else{
									$prosHas = 1;
								}
								if ($prosHas == 1) {
									$data['nmPoli'] = $results['response']->rujukan->poliRujukan->nama;
									$kdPoli = Rsu_BridgingKdPoli::where('kode_lama', $results['response']->rujukan->poliRujukan->kode)->first(); // db rsu
									if (!empty($kdPoli)) {
										$data['kdPoli'] = $kdPoli->kode_baru;
									}else{
										$data['kdPoli'] = $results['response']->rujukan->poliRujukan->kode;
									}
									$data['kdPoliRiwayat'] = $results['response']->rujukan->poliRujukan->kode;
									$data['tglRujukan'] = $results['response']->rujukan->tglKunjungan;
									$batasRujukan = date('Y-m-d', strtotime('+90 days', strtotime(date('Y-m-d',strtotime($results['response']->rujukan->tglKunjungan)))));
									$dateNow = date("Y-m-d");
									$data['tglBatas'] = $batasRujukan;
									if ($dateNow <= $batasRujukan) {
										$data['batasRujukan'] = 'Ready';
									}else{
										$data['batasRujukan'] = 'Over';
									}
									$data['tglAkhir'] = $batasRujukan;
									$data['tlpPas'] = $results['response']->rujukan->peserta->mr->noTelepon;
									$data['noRujuk'] = $results['response']->rujukan->noKunjungan;

									//Menghitung Jumlah Hari Rujukan hingga hari ini
									$CheckInX = explode("-", date('Y-m-d',strtotime($results['response']->rujukan->tglKunjungan)));
									$CheckOutX =  explode("-", $dateNow);
									$dateIn1 =  mktime(0, 0, 0, $CheckInX[1],$CheckInX[2],$CheckInX[0]);
									$dateOut2 =  mktime(0, 0, 0, $CheckOutX[1],$CheckOutX[2],$CheckOutX[0]);
									$intervalDays =($dateOut2 - $dateIn1)/(3600*24);
									$data['intervalRujuk'] = $intervalDays;

									$cekSebelum = Rsu_RiwayatRegistrasi::where('no_rujukan', $data['noRujuk'])
													->orderBy('id_riwayat_regis','DESC')
													->first();
									if (!empty($cekSebelum)) {
										$urlDokter = $this->url."/referensi/dokter/pelayanan/2/tglpelayanan/".date('Y-m-d')."/Spesialis/".$cekSebelum->poli_bpjs; // url rilis
										$resultDokter = Requestor::set_new_curl_bridging($urlDokter, $params, $method, $consID, $secretKey, $port,'','');
										$hslDokter = [
											'metaData' => json_decode($resultDokter['metaData']),
											'response' => json_decode($resultDokter['response']),
										];
										$kodeDPJP = '';
										$namaDPJP = '';
										foreach ($hslDokter['response']->list as $key) {
											if (strtolower($key->kode) == $request->id_dokterDpjpLayan) {
												$kodeDPJPRiw = $key->kode;
												$namaDPJPRiw = $key->nama;
											}
										}
										$data['kodeDPJP'] = $kodeDPJP;
										$data['dokterSebelum'] = $namaDPJP;
										$data['record'] = 'exists';
										$panjanNoAwal = strlen($cekSebelum->No_Register) - 6;
										$panjanNo = strlen($cekSebelum->No_Register);
										$data['noSurat'] = substr($cekSebelum->No_Register, $panjanNoAwal,$panjanNo);
										$data['kdPoliRiwayat'] = $cekSebelum->poli_bpjs;
									}else{
										$data['dokterSebelum'] = '';
										$data['kodeDPJP'] = '';
										$data['record'] = 'nothing';
										$getNoSrt = Rsu_Register::where('NoPeserta',$idenPas->FieldCust1)->orderBy('No_Register','DESC')->first();
										if (!empty($getNoSrt)) {
											$panjanNoAwal = strlen($getNoSrt->No_Register) - 6;
											$panjanNo = strlen($getNoSrt->No_Register);
											$dptNoSrt = substr($getNoSrt->No_Register, $panjanNoAwal,$panjanNo);
										}else{
											$dptNoSrt = "";
										}
										// $data['noSurat'] = '';
										$data['noSurat'] = $dptNoSrt;
									}

								}else{
									$data['nmPoli'] = 'Gagal';
									$data['kdPoli'] = '';
									$data['tglRujukan'] = '';
									$data['batasRujukan'] = 'Over';
									$data['tglAkhir'] = '';
									$data['tlpPas'] = '';
									$data['noRujuk'] = '';
									$data['dokterSebelum'] = '';
									$data['kodeDPJP'] = '';
									$data['record'] = 'nothing';
									$data['noSurat'] = '';
									$data['kdPoliRiwayat'] = '';
									$data['intervalRujuk'] = '';
								}
							}
						}else{
							$data['noBpjs'] = '-';
							$data['nmPoli'] = '';
							$data['kdPoli'] = '';
							$data['tglRujukan'] = '';
							$data['batasRujukan'] = 'Over';
							$data['tglAkhir'] = '';
							$data['tlpPas'] = '';
							$data['noRujuk'] = '';
							$data['dokterSebelum'] = '';
							$data['kodeDPJP'] = '';
							$data['record'] = 'nothing';
							$data['noSurat'] = '';
							$data['kdPoliRiwayat'] = '';
							$data['intervalRujuk'] = '';
						}
					}else{
						$data['noBpjs'] = '';
						$data['nmPoli'] = '';
						$data['kdPoli'] = '';
						$data['tglRujukan'] = '';
						$data['batasRujukan'] = 'Over';
						$data['tglAkhir'] = '';
						$data['tlpPas'] = '';
						$data['noRujuk'] = '';
						$data['dokterSebelum'] = '';
						$data['kodeDPJP'] = '';
						$data['record'] = 'nothing';
						$data['noSurat'] = '';
						$data['kdPoliRiwayat'] = '';
						$data['intervalRujuk'] = '';
					}
					date_default_timezone_set('Asia/Jakarta');
					$data['tglNow'] = date('d-m-Y');
					$data['jenis_pendaftaran'] = $request->jenis_pendaftaran;
					$data['no_identitas'] = $request->no_identitas;
					$data['KodeCust'] = $kodeCus;
					$data['statusInput'] = $statusInput;
					$data['dokAlters'] = rsu_dokter_bridging::all();
					// $data['polis'] = Poli::join('mapping_poli_bridging','mapping_poli_bridging.kdpoli_rs','=','tm_poli.KodePoli')->get();
					$data['polis'] = rsu_poli::join('mapping_poli_bridging','mapping_poli_bridging.kdpoli_rs','=','tm_poli.KodePoli')->get();
					$content = view('registration.pendaftaran.confirmCust',$data)->render();
					return ['status' => 'success', 'content' => $content, 'statusInput'=>$statusInput];
				}else{
					return ['status' => 'error', 'message' => 'No Rekam Medis Anda di Blokir, Silahkan menghubungi petugas Pendaftaran !!'];
				}
			}else{
				return ['status' => 'error', 'message' => 'Identitas Tidak Terdaftar, Silahkan Melakukan Pendaftaran Terlebih Dahulu !!'];
			}
		}else{
			return ['status' => 'error', 'message' => 'Identitas Harus Diisi !!'];
		}
	}

	public function pilihPoli(Request $request){
		if (!empty($request->tanggal)) {
			date_default_timezone_set('Asia/Jakarta');
			$dateNow = date('Y-m-d');
			$dateRequest = date('Y-m-d', strtotime($request->tanggal));
			if ($dateRequest >= $dateNow) {
				$cekTanggal = Holidays::where('tanggal',date('Y-m-d',strtotime($request->tanggal)))->first();
				if (empty($cekTanggal)) {
					$cekAntrian = Rsu_cc::where('tanggal',date('d-m-Y',strtotime($request->tanggal)))->where('norm',$request->KodeCust)->first(); // db rsu
					if (empty($cekAntrian)) {
						if ($request->jenis_pendaftaran == 'BPJS' && $request->noRujuk == null) {
							return ['status' => 'error', 'message' => 'No Rujukan Harus Diisi !!'];
						}else{
							$data['tanggal'] = $request->tanggal;
							$data['tglNow'] = date('Y-m-d');
							$data['polis'] = rsu_poli::all(); // db rsu
							$data['jenis_pendaftaran'] = $request->jenis_pendaftaran;
							$data['no_identitas'] = $request->no_identitas;
							$data['KodeCust'] = $request->KodeCust;
							$data['statusInput'] = $request->statusInput;
							$data['noRujuk'] = $request->noRujuk;
							$data['telpPas'] = $request->telpPas;
							$content = view('registration.pendaftaran.formPilihPoli',$data)->render();
							return ['status' => 'success', 'content' => $content,'statusInput'=>$request->statusInput];
						}
					}else{
						return ['status' => 'error', 'message' => 'Anda Sudah Mengambil Antrian Untuk Tanggal Tersebut !!'];
					}
				}else{
					return ['status' => 'error', 'message' => 'Tanggal yang Dipilih adalah Hari Libur, Silahkan Pilih Hari Lain !!'];
				}
			}else{
				return ['status' => 'error', 'message' => 'Tanggal yang dipilih sudah terlewat !!', 'dateNow' => $dateNow, 'dateRequest' => $dateRequest];
			}
		}else{
			return ['status' => 'error', 'message' => 'Tanggal Harus Di isi !!'];
		}
	}

	public function doRegistrasi(Request $request){
		if (!empty($request->KodePoli)) {
			date_default_timezone_set('Asia/Jakarta');
			$dateNow = date('Y-m-d');
			$dateReg = date('Y-m-d', strtotime($request->tanggal));
			$timeNow = date('H:i:s');
			if ($request->jenis_pendaftaran == 'UMUM' && $dateNow == $dateReg) {
				// $infoCust = Customer::where('KodeCust',$request->KodeCust)->first(); // db local
				$infoCust = rsu_customer::where('KodeCust',$request->KodeCust)->first(); // db rsu
				if (!empty($infoCust)) {

					// $cekCC = CC::where('norm',$request->KodeCust)->where('tanggal',$request->tanggal)->where('nourut',$request->noUrut)->first(); // db local
					// $cekCC = Rsu_cc::where('norm',$request->KodeCust)->where('tanggal',$request->tanggal)->where('nourut',$request->noUrut)->first();

					// $infoPoli = Poli::where('KodePoli',$request->KodePoli)->first(); // db lokal
					$infoPoli = rsu_poli::where('KodePoli', $request->KodePoli)->first(); // db rsu
					// $reg = new Register; //db lokal site
					$reg = new Rsu_Register; //db rsu site
					$reg->TransReg = 'RE';
					// $urut = Register::whereYEAR('Tgl_Register','=',date('Y'))->select('No_Register','Tgl_Register')->orderby('No_Register','DESC')->first(); //db lokal site
					// $urut = Rsu_Register::whereYEAR('Tgl_Register','=',date('Y'))->select('No_Register','Tgl_Register')->orderby('No_Register','DESC')->first(); //db rsu
					$tg = date('y');
					$tg =$tg.'2';
					$thn = date('Y'); $mo = date('m'); $da = date('d');
					// $urut = Register::whereRaw("(YEAR(Tgl_Register) = $thn) AND LEFT(No_Register,3) = $tg ")->orderby('No_Register','DESC')->first(); //db lokal
					$urut = Rsu_Register::whereRaw("(YEAR(Tgl_Register) = $thn) AND LEFT(No_Register,3) = $tg ")->orderby('No_Register','DESC')->first(); //db rsu
					if($urut){
						$nourut = $urut->No_Register + 1;
					}else{
						$nourut = date('y').'20000001';
					}
					$reg->No_Register = $nourut;
					$reg->Tgl_Register = date('Y-m-d H:i:s');
					$reg->Jam_Register = date('H:i:s');
					$reg->No_RM = $infoCust->KodeCust;
					$reg->Nama_Pasien = $infoCust->NamaCust;
					$reg->AlamatPasien = $infoCust->Alamat;
					$reg->Umur = $infoCust->umur;
					$reg->Kode_Ass = null;
					$reg->Kode_Poli1 = $infoPoli->KodePoli;
					$reg->JenisKel = $infoCust->JenisKel;
					$reg->NoSEP = null;
					$reg->NoPeserta = null;
					// $reg->Biaya_Registrasi = 5000;
					$reg->Biaya_Registrasi = 0;
					$reg->Status = 'Belum Dibayar';
					$reg->NamaAsuransi = 'UMUM';
					// $reg->Japel = 3000;
					$reg->Japel = 0;
					// $reg->JRS = 2000;
					$reg->JRS = 0;
					$reg->TipeReg = 'REG';
					$reg->SudahCetak = 'N';
					$reg->BayarPendaftaran = 'N';
					$reg->Tgl_Lahir = $infoCust->TglLahir;
					$reg->isKaryawan = 'N';
					$reg->isProcessed = 'N';
					$reg->isPasPulang = 'N';
					$reg->Jenazah = 'N';
					$reg->save();

					// Estimasi Pelayanan Start
					$dateSelect = date('d-m-Y', strtotime($request->tanggal));
					$cekNoPoli = Rsu_cc::where('tanggal',$dateSelect)->where('poli',$infoPoli->NamaPoli)->orderby('urutpoli','DESC')->first(); // db rsu

					if (empty($cekNoPoli)) {
						$no_urut_poli = '1';
						$getDataKode = Poli::where('kode_poli', $request->KodePoli)->first();
					}else{
						$no_urut_poli = $cekNoPoli->urutpoli + 1;
						$getDataKode = Poli::where('NamaPoli', $cekNoPoli->poli)->first();
					}

					$getEstimasi = DB::connection('dbrsudlain')->table('layanan')->where('tampil', 1)->where('kodepoli', $getDataKode->kode_poli)->first();
					$timeEstimate = "0";
					if ($getEstimasi) {
						$timeBuka = strtotime($getEstimasi->jamlayanan);
						$timeBukaPakai = strtotime(date("H:i", strtotime('-'.$getEstimasi->estimasi.' minutes', $timeBuka)));
						$timeEstimate = date("H:i", strtotime('+'.$getEstimasi->estimasi*$no_urut_poli.' minutes', $timeBukaPakai));
					}
					// Estimasi Pelayanan End

					$rowRegis = [
						'nourut' => '-',
						'poli' => $infoPoli->NamaPoli,
						'No_RM' => $reg->No_RM,
						'Nama_Pasien' => $reg->Nama_Pasien,
					];

					if ($reg) {
						$return = ['status'=>'success','code'=>200,'messages'=>'Registrasi Berhasil Dilakukan !!', 'data'=>$rowRegis,'tgl'=>$request->tanggal,'pendaftaran'=>$request->jenis_pendaftaran, 'estimasipelayanan' =>$timeEstimate ,'jam'=>$timeNow, 'waktuDaftar' => 'today'];
					}else{
						$return = ['status'=>'error','code'=>500,'messages'=>'Registrasi Gagal Dilakukan !!'];
					}
				}else{
					$return = ['status'=>'error','code'=>500,'messages'=>'Pasien Tidak Ditemukan !!'];
				}
			}else{
				$dateSelect = date('d-m-Y', strtotime($request->tanggal));
				// Ambil No Urut Registrasi
				// $cekNoRegis = CC::where('tanggal',$dateSelect)->orderby('nourut','DESC')->first(); // bd lokal
				$cekNoRegis = Rsu_cc::where('tanggal',$dateSelect)->orderby('nourut','DESC')->first(); // bd rsu
				if (empty($cekNoRegis)) {
					$no_urut_regis = '1';
				}else{
					$no_urut_regis = $cekNoRegis->nourut + 1;
				}

				// Cek Poli
				// $cekPoli = Poli::where('KodePoli',$request->KodePoli)->first();
				$cekPoli = rsu_poli::where('KodePoli',$request->KodePoli)->first();

				// Ambil No Urut Poli
				// $cekNoPoli = CC::where('tanggal',$dateSelect)->where('poli',$cekPoli->NamaPoli)->orderby('urutpoli','DESC')->first(); // lokal
				$cekNoPoli = Rsu_cc::where('tanggal',$dateSelect)->where('poli',$cekPoli->NamaPoli)->orderby('urutpoli','DESC')->first();  // db rsu
				if (empty($cekNoPoli)) {
					$no_urut_poli = '1';
					$getDataKode = Poli::where('KodePoli', $request->KodePoli)->first();
				}else{
					$no_urut_poli = $cekNoPoli->urutpoli + 1;
					$getDataKode = Poli::where('NamaPoli', $cekNoPoli->poli)->first();
				}

				// return $cekAja = ['cekPoli'=>$cekNoPoli,'no urut'=>$no_urut_poli];

				// Info Customer
				// $cust = Customer::where('KodeCust',$request->KodeCust)->first();
				$cust = rsu_customer::where('KodeCust',$request->KodeCust)->first();

				if ($request->jenis_pendaftaran == 'BPJS') {
					$nobpjs = $cust->FieldCust1;
				}else{
					$nobpjs = null;
				}
				// $dataCC = [
				//     'norm' => $request->KodeCust,
				//     'nama' => $cust->NamaCust,
				//     'alamat' => $cust->Alamat,
				//     'penanggung' => null,
				//     'poli' => $cekPoli->NamaPoli,
				//     'tanggal' => $dateSelect,
				//     'nourut' => $no_urut_regis,
				//     'notelp' => $cust->Telp,
				//     'KET' => '',
				//     'nobpjs' => $nobpjs,
				//     'jam' => date('Y-m-d H:i:s'),
				//     'urutpoli' => $no_urut_poli,
				// ];

				// $adCC = new CC; // bd lokal
				$adCC = new Rsu_cc; // bd rsu
				$adCC->norm = $request->KodeCust;
				$adCC->nama = $cust->NamaCust;
				$adCC->alamat = $cust->Alamat;
				// $adCC->penanggung = null;
				$adCC->penanggung = $request->jenis_pendaftaran;
				$adCC->poli = $cekPoli->NamaPoli;
				$adCC->tanggal = $dateSelect;
				$adCC->nourut = $no_urut_regis;
				$adCC->notelp = $request->telpPas;
				$adCC->KET = '';
				$adCC->nobpjs = $nobpjs;
				$adCC->jam = date('Y-m-d H:i:s');
				$adCC->urutpoli = $no_urut_poli;
				$adCC->pendaftaran = $request->jenis_pendaftaran;
				$adCC->status = '';
				$adCC->norujukan = $request->noRujuk;
				$adCC->save();

				// return $dataCC;

				// $cekApm = Apm::where('tanggal',$request->tanggal)->orderby('no_antrian','DESC')->first();
				// if (!empty($cekApm)) {
				//     $no_urut = $cekApm->no_antrian + 1;
				// }else{
				//     $no_urut = 1;
				// }
				// $apm = new Apm;
				// $apm->no_antrian = $no_urut;
				// $apm->tanggal = date('Y-m-d', strtotime($request->tanggal));
				// $apm->pendaftaran = $request->jenis_pendaftaran;
				// $apm->no_identitas = $request->no_identitas;
				// $apm->KodeCust = $request->KodeCust;
				// $apm->tgl_pengambilan = $dateNow;
				// $apm->jam_pengambilan = date('H:i:s');
				// $apm->KodePoli = $request->KodePoli;
				// $apm->status = 'Pendaftaran';
				// $apm->pengambilan = 'Dekstop';
				// $apm->save();

				if (date('m', strtotime($adCC->tanggal)) == 1) { $bulan = "Januari";}
				elseif(date('m', strtotime($adCC->tanggal)) == 2){$bulan = "Februari";}
				elseif(date('m', strtotime($adCC->tanggal)) == 3){$bulan = "Maret";}
				elseif(date('m', strtotime($adCC->tanggal)) == 4){$bulan = "April";}
				elseif(date('m', strtotime($adCC->tanggal)) == 5){$bulan = "Mei";}
				elseif(date('m', strtotime($adCC->tanggal)) == 6){$bulan = "Juni";}
				elseif(date('m', strtotime($adCC->tanggal)) == 7){$bulan = "Juli";}
				elseif(date('m', strtotime($adCC->tanggal)) == 8){$bulan = "Agustus";}
				elseif(date('m', strtotime($adCC->tanggal)) == 9){$bulan = "September";}
				elseif(date('m', strtotime($adCC->tanggal)) == 10){$bulan = "Oktober";}
				elseif(date('m', strtotime($adCC->tanggal)) == 11){$bulan = "November";}
				elseif(date('m', strtotime($adCC->tanggal)) == 12){$bulan = "Desember";}
				$tgl = date('d', strtotime($adCC->tanggal))." ".$bulan." ".date('Y', strtotime($adCC->tanggal));



				// if ($apm) {
				//     $return = ['status' => 'success', 'message' => 'Pendaftaran Berhasil Dilakukan !!','data'=>$apm,'nama_poli'=>$apm->poli->NamaPoli,'tgl'=>$tgl];
				// }else{
				//     $return = ['status' => 'error', 'message' => 'Pendaftaran Gagal Dilakukan !!'];
				// }

				// Estimasi Pelayanan Start
				$getEstimasi = DB::connection('dbrsudlain')->table('layanan')->where('tampil', 1)->where('kodepoli', $getDataKode->kode_poli)->first();
				$timeEstimate = "0";
				if ($getEstimasi) {
					$timeBuka = strtotime($getEstimasi->jamlayanan);
					$timeBukaPakai = strtotime(date("H:i", strtotime('-'.$getEstimasi->estimasi.' minutes', $timeBuka)));
					$timeEstimate = date("H:i", strtotime('+'.$getEstimasi->estimasi*$no_urut_poli.' minutes', $timeBukaPakai));
				}
				// Estimasi Pelayanan End

				if ($adCC) {
					$return = ['status' => 'success', 'message' => 'Pendaftaran Berhasil Dilakukan !!','data'=>$adCC,'tgl'=>$tgl, 'estimasipelayanan' => $timeEstimate,'jam'=>$timeNow,'pendaftaran'=>$request->jenis_pendaftaran, 'waktuDaftar' => 'not today'];
				}else{
					$return = ['status' => 'error', 'message' => 'Pendaftaran Gagal Dilakukan !!'];
				}
			}
		}else{
			$return = ['status' => 'error', 'message' => 'Poli Harus Dipilih !!'];
		}
		return $return;
	}

	public function bridgingApm(Request $request){
		$cekTanggal = Holidays::where('tanggal',date('Y-m-d',strtotime($request->tanggal)))->first();
		if (empty($cekTanggal)) {
			// return $request->all();
			date_default_timezone_set('Asia/Jakarta');
			$dateNow = date('Y-m-d');
			// $datPas = Customer::where('KodeCust',$request->KodeCust)->first(); // db local
			$datPas = rsu_customer::where('KodeCust',$request->KodeCust)->first(); // db local
			if (!empty($datPas)) {
				$url = $this->url."/Rujukan/".$request->noRujuk; //url web service bpjs rilis
				$consID = $this->consid; //customer ID RS
				$secretKey = $this->secretkey; //secretKey RS
				$method = 'GET';
				$port = '8080';
				$params = '';

				$resultRujukan = Requestor::set_new_curl_bridging($url, $params, $method, $consID, $secretKey, $port,'',''); // bridging data peserta bpjs

				if ($resultRujukan === false) {
					return ['status' => 'error', 'messages' => 'Tidak Dapat Terhubung ke Server !!'];
				} else {
					$resultRujukans = [
						'metaData' => json_decode($resultRujukan['metaData']),
						'response' => json_decode($resultRujukan['response']),
					];
					$kdAsalRujuk = '1';
					$kdPPkRujuk = $resultRujukans['response']->rujukan->peserta->provUmum->kdProvider;
					if ($resultRujukans['response'] == null) {
						$url2 = $this->url."/Rujukan/RS/".$request->noRujuk;
						$resultRujukan2 = Requestor::set_new_curl_bridging($url2, $params, $method, $consID, $secretKey, $port,'','');
						if ($resultRujukan2 === false) {
							return ['status' => 'error', 'messages' => 'Tidak Dapat Terhubung ke Server !!'];
						}else{
							$resultRujukans = json_decode($resultRujukan2, true);
							$kdAsalRujuk = '2';
							// $kdPPkRujuk = '1320R001';
							$kdPPkRujuk = $resultRujukans['response']['rujukan']['peserta']['provUmum']['kdProvider'];
							if ($resultRujukans['response'] != null) {
								$prosHas = 1;
							}else{
								$prosHas = 0;
							}
						}
					}else{
						$prosHas = 1;
					}
					// if ($resultRujukans['response'] != null) {
					if ($prosHas == 1) {
						// ================================================================================
						// =============================== Start Insert SEP ===============================
						// ================================================================================
						$cob = ($resultRujukans['response']->rujukan->peserta->cob->noAsuransi) ? '1' : '0';

						// if ($resultRujukans['response']['rujukan']['poliRujukan']['kode'] == 'ORP') {
						//     $resKodePoli = 'ORT';
						// }elseif($resultRujukans['response']['rujukan']['poliRujukan']['kode'] == 'URL'){
						//     $resKodePoli = 'URO';
						// }elseif($resultRujukans['response']['rujukan']['poliRujukan']['kode'] == 'GND'){
						//     $resKodePoli = 'GIG';
						// }else{
						//     $resKodePoli = $resultRujukans['response']['rujukan']['poliRujukan']['kode'];
						// }
						/*
						$kdPoliLama = $resultRujukans['response']['rujukan']['poliRujukan']['kode'];
						$cekKdPoli = BridgingKdPoli::where('kode_lama',$kdPoliLama)->first(); // db lokal
						// $cekKdPoli = Rsu_BridgingKdPoli::where('kode_lama',$kdPoliLama)->first(); // db rsu
						if (!empty($cekKdPoli)) {
							$resKodePoli = $cekKdPoli->kode_baru;
						}else{
							$resKodePoli = $resultRujukans['response']['rujukan']['poliRujukan']['kode'];
						}
						*/
						$resKodePoli = $request->kdSelectedPoli;
						// if ($resultRujukans['response']['rujukan']['peserta']['mr']['noTelepon'] == null || $resultRujukans['response']['rujukan']['peserta']['mr']['noTelepon'] == '-') {
						//     $resNoTelp = '081753119852';
						// }else{
						//     $resNoTelp = $resultRujukans['response']['rujukan']['peserta']['mr']['noTelepon'];
						// }
						if ($request->phonePasien != '' AND $request->phonePasien != '0') {
							$phonePasien = $request->phonePasien;
						}else{
							$phonePasien = $resultRujukans['response']->rujukan->peserta->mr->noTelepon;
						}
						$recordRujuk = $request->record;
						// if ($recordRujuk == 'exists') {
							$kodeDPJP = $request->kodeDPJP;
						// }else{
							// $noSurat = "";
							// $kodeDPJP = "";
						// }
						$noSurat = $request->noSurat;

						$hitungRujukan = Rsu_Register::where('Rujukan', $resultRujukans['response']->rujukan->noKunjungan)->count();
						if (!empty($request->no_rm)) {
							$updatenik = rsu_customer::where('KodeCust',$datPas->KodeCust)->first();
							if (!empty($updatenik)) {
								$updatenik->NoKtp = $resultRujukans['response']->rujukan->peserta->nik;
								$updatenik->save();
							}
						}

						$data = array(
							"request"=>[
							"t_sep" => [
								"noKartu" => $resultRujukans['response']->rujukan->peserta->noKartu,
								"tglSep" => $dateNow,
								"ppkPelayanan" => '1320R001',
								"jnsPelayanan" => $resultRujukans['response']->rujukan->pelayanan->kode,
								"klsRawat" => [
									"klsRawatHak" => $resultRujukans['response']->rujukan->peserta->hakKelas->kode,
									"klsRawatNaik" => "",
									"pembiayaan"=> "",
									"penanggungJawab" => "",
								],
								"noMR" => $datPas->KodeCust,
								"rujukan" => [
									"asalRujukan" => $kdAsalRujuk,
									"tglRujukan" => date('Y-m-d', strtotime($resultRujukans['response']->rujukan->tglKunjungan)),
									"noRujukan" => $resultRujukans['response']->rujukan->noKunjungan,
									"ppkRujukan" => $resultRujukans['response']->rujukan->provPerujuk->kode,
								],
								"catatan" => '',
								"diagAwal" => $resultRujukans['response']->rujukan->diagnosa->kode,
								"poli" => [
									"tujuan" => $resKodePoli,
									"eksekutif" => "0"
								],
								"cob" => [
									"cob" => $cob
								],
								"katarak" => [
									"katarak" => "0"
								],
								"jaminan"=> [
									"lakaLantas" => '0',
									"noLP" => '',
									"penjamin" => [
										'tglKejadian' => '',
										'keterangan' => '',
										'suplesi' => [
											'suplesi' => '',
											'noSepSuplesi' => '',
											'lokasiLaka' => [
												'kdPropinsi' => '',
												'kdKabupaten' => '',
												'kdKecamatan' => ''
											]
										]
									]
								],
								"tujuanKunj"=>$request->tujuan_kunjugan,
								"flagProcedure" => ($request->prosedur_bpjs != null) ? $request->prosedur_bpjs : '',
								"kdPenunjang" => ($request->penunjang_bpjs != null) ? $request->penunjang_bpjs : "",
								"assesmentPel" => ($request->assesment_bpjs != null) ? $request->assesment_bpjs : "",
								"skdp" => [
									"noSurat" => $noSurat,
									"kodeDPJP" => $kodeDPJP
								],
								"dpjpLayan"=> ($resultRujukans['response']->rujukan->pelayanan->kode == '2') ? $kodeDPJP : '',
								"noTelp" => $phonePasien,
								"user" => 'APM'
								]
							]
						);

						// return $data;
						$url = $this->url."/SEP/2.0/insert";
						$consID = $this->consid; //customer ID RS
						$secretKey = $this->secretkey; //secretKey RS
						$datas = json_encode($data);
						$sendBpjs = $datas;
						$method = 'POST';
						date_default_timezone_set('UTC');
						$stamp = strval(time()-strtotime('1970-01-01 00:00:00'));
						$data = $consID.'&'.$stamp;

						$signature = hash_hmac('sha256', $data, $secretKey, true);
						$encodedSignature = base64_encode($signature);
						$key = $consID.$secretKey.$stamp;

						$ch = curl_init();

						curl_setopt_array($ch, array(
							// CURLOPT_PORT => "8080",
							CURLOPT_URL => $url,
							CURLOPT_RETURNTRANSFER => true,
							CURLOPT_ENCODING => "",
							CURLOPT_MAXREDIRS => 10,
							CURLOPT_TIMEOUT => 30,
							CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
							CURLOPT_CUSTOMREQUEST => $method,
							CURLOPT_POSTFIELDS => $datas,
							CURLOPT_HTTPHEADER => array(
								"cache-control: no-cache",
								"x-cons-id: ".$consID,
								"x-signature: ".$encodedSignature,
								"x-timestamp: ".$stamp."",
								"user_key: ".$this->userkey,
							),
						));
						$response = curl_exec($ch);
						$err = curl_error($ch);
						curl_close($ch);

						if ($err) {
							$messages = "cURL Error #:" . $err;
							$return = ['status' => 'error', 'messages' => $messages];
						} else {
							$respon = json_decode($response,true);
							$string = json_encode($respon['response']);
							// FUNGSI DECRYPT
							$encrypt_method = 'AES-256-CBC';
							// hash
							$key_hash = hex2bin(hash('sha256', $key));
							// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
							$iv = substr(hex2bin(hash('sha256', $key)), 0, 16);
							$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key_hash, OPENSSL_RAW_DATA, $iv);
							$value = \LZCompressor\LZString::decompressFromEncodedURIComponent($output);
							$respon2 = json_decode($value,true);
							// $respon = [
							// 	'metaData' => json_decode($response['metaData']),
							// 	'response' => json_decode($response['response']),
							// ];
							// return $respon;
							if ($respon['metaData']['code'] == 201) {
								// $cek = explode('No.SEP ', $respon['metaData']['message']);
								// $nosep = substr($cek[1], 0,19);

								// $pulang = preg_match('/belum dipulangkan di RSUD DR. W. SUDIROHUSODO/i', $respon['metaData']['message']);
								// $rsu = preg_match('/1320R001/i', $respon['metaData']['message']);
								// if($pulang && $rsu){
								//     $ek = 'update';
								// }else{
								//     $ek = "";
								// }
								$return = [ 'status'=>'error', 'code'=> $respon['metaData']['code'], 'messages'=>$respon['metaData']['message'] ];
							}else{
								// $poli = Bridgingpoli::where('kdpoli',$resKodePoli)->select('kdpoli_rs')->first(); //get poli rs ( db Rs )
								$poli = Rsu_Bridgingpoli::where('kdpoli',$resKodePoli)->select('kdpoli_rs')->first(); //get poli rs ( db Rs )
								// $asu = Setupall::where('subgroups','1008')->first(); //get nama asuransi RS default BPJS NON PBI ( db Rs )
								$asu = Rsu_setupall::where('subgroups','1008')->first(); //get nama asuransi RS default BPJS NON PBI ( db Rs )
								// $namapoli = Poli::find($poli->kdpoli_rs); // db lokal
								$namapoli = rsu_poli::find($poli->kdpoli_rs); // db rsu

								date_default_timezone_set('Asia/Jakarta');

								// Insert tabel Register
								// $reg = new Register; //db lokal site
								$reg = new Rsu_Register; // db rsu
								$reg->TransReg = 'RE';
								$tg = date('y');
								$tg =$tg.'2';
								$thn = date('Y'); $mo = date('m'); $da = date('d');
								// $urut = Register::whereRaw("(YEAR(Tgl_Register) = $thn) AND LEFT(No_Register,3) = $tg ")->orderby('No_Register','DESC')->first(); //db local
								$urut = Rsu_Register::whereRaw("(YEAR(Tgl_Register) = $thn) AND LEFT(No_Register,3) = $tg ")->orderby('No_Register','DESC')->first(); //db rsu

								if($urut){
									$nourut = $urut->No_Register + 1;
								}else{
									$nourut = date('y').'20000001';
								}

								$umurPasLengkap = explode(',', $resultRujukans['response']->rujukan->peserta->umur->umurSekarang);
								$umurPas = explode(' ', $umurPasLengkap[0]);

								$reg->No_Register = $nourut;
								$reg->Tgl_Register = date('Y-m-d H:i:s');
								$reg->Jam_Register = date('H:i:s');
								$reg->No_RM = $datPas->KodeCust;
								$reg->Nama_Pasien = $datPas->NamaCust; // data dari rsud
								// $reg->Nama_Pasien = $resultRujukans['response']['rujukan']['peserta']['nama'].'.'; // data dari bpjs
								$reg->AlamatPasien = $datPas->Alamat;
								$reg->Umur = $umurPas[0];
								$reg->Kode_Ass = $asu->subgroups;
								// $reg->Kode_Ass = '1008';
								$reg->Kode_Poli1 = $poli->kdpoli_rs;
								$reg->JenisKel = $datPas->JenisKel; // data dari rumah sakit
								$reg->Rujukan = $request->noRujuk; // data dari Form
								// $reg->JenisKel = $resultRujukans['response']['rujukan']['peserta']['sex']; // data dari bpjs
								$reg->NoSEP = $respon2['sep']['noSep']; // asli
								$reg->NoPeserta = $datPas->FieldCust1; // data dari rsud
								$reg->Rujukan = $resultRujukans['response']->rujukan->noKunjungan; // data dari bpjs
								$reg->Biaya_Registrasi = 5000;
								$reg->Status = 'Belum Dibayar';
								$reg->NamaAsuransi = $asu->nilaichar;
								// $reg->NamaAsuransi = 'BPJS NON PBI';
								$reg->Japel = 3000;
								$reg->JRS = 2000;
								$reg->TipeReg = 'REG';
								$reg->SudahCetak = 'N';
								$reg->BayarPendaftaran = 'N';
								$reg->Tgl_Lahir = date('Y-m-d', strtotime($datPas->TglLahir)); // data dari rsud
								// $reg->Tgl_Lahir = $resultRujukans['response']['rujukan']['peserta']['tglLahir']; // data dari bpjs
								$reg->isKaryawan = 'N'; // default tidak
								$reg->isProcessed = 'N';
								$reg->isPasPulang = 'N';
								$reg->Jenazah = 'N';
								$reg->save();

								if ($request->record == 'nothing') {
									$dokterBridg = rsu_dokter_bridging::where('polibpjs',$request->kdPoliRiwayat)->first();
									// $urlDokter = "https://dvlp.bpjs-kesehatan.go.id/vclaim-rest/referensi/dokter/pelayanan/2/tglpelayanan/".date('Y-m-d')."/Spesialis/".$request->kdPoliRiwayat; // url develop
									// $urlDokter = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/referensi/dokter/pelayanan/2/tglpelayanan/".date('Y-m-d')."/Spesialis/".$request->kdPoliRiwayat; // url rilis
									$urlDokter = $this->url."/referensi/dokter/pelayanan/2/tglpelayanan/".date('Y-m-d')."/Spesialis/".$request->kdPoliRiwayat; // url rilis
									$kdPoliRw = $request->kdPoliRiwayat;
								}else{
									$dokterBridg = rsu_dokter_bridging::where('polibpjs',$resKodePoli)->first();
									// $urlDokter = "https://dvlp.bpjs-kesehatan.go.id/vclaim-rest/referensi/dokter/pelayanan/2/tglpelayanan/".date('Y-m-d')."/Spesialis/".$resKodePoli; // url develop
									$urlDokter = $this->url."/referensi/dokter/pelayanan/2/tglpelayanan/".date('Y-m-d')."/Spesialis/".$resKodePoli; // url rilis
									$kdPoliRw = $resKodePoli;
								}
								$resultDokter = Requestor::set_new_curl_bridging($urlDokter, $params, $method, $consID, $secretKey, $port,'','');
								$hslDokter = [
									'metaData' => json_decode($resultDokter['metaData']),
									'response' => json_decode($resultDokter['response']),
								];
								// $kodeDPJPRiw = '';
								// $namaDPJPRiw = '';
								$kodeDPJPRiw = $kodeDPJP;
								$namaDPJPRiw = $kodeDPJP;
								// for($z=0;$z<count($hslDokter['response']['list']);$z++){
								// 	if (strtolower($hslDokter['response']['list'][$z]['nama']) == strtolower($dokterBridg->dokter)) {
								// 		$kodeDPJPRiw = $hslDokter['response']['list'][$z]['kode'];
								// 		$namaDPJPRiw = $hslDokter['response']['list'][$z]['nama'];
								// 	}
								// }

								// $addHistory = new RiwayatRegistrasi; // db lokal
								$addHistory = new Rsu_RiwayatRegistrasi; // db rsu
								$addHistory->No_Register = $nourut;
								$addHistory->no_surat = $noSurat;
								$addHistory->no_rm = $datPas->KodeCust;
								$addHistory->no_rujukan = $resultRujukans['response']->rujukan->noKunjungan;
								$addHistory->NoSEP = $respon2['sep']['noSep'];
								$addHistory->kode_dpjp = $kodeDPJPRiw;
								$addHistory->nama_dpjp = $namaDPJPRiw;
								$addHistory->poli_bpjs = $kdPoliRw;
								$addHistory->save();
							/*
								$rawatTindakBf = RawatJalanTindakan::where('No_RM','S-1004111037')->orderby('RwID', 'DESC')->first();
								$newRawatTindak = new RawatJalanTindakan;
								$newRawatTindak->No_Register = $nourut;
								$newRawatTindak->NoTindakan = $rawatTindakBf->NoTindakan + 1;
								$newRawatTindak->NoUrut = '1';
								$newRawatTindak->Tgl = date('Y-m-d');
								$newRawatTindak->No_RawatJL = $rawatTindakBf->No_RawatJL + 1;
								$newRawatTindak->No_RM = $datPas->KodeCust;
								$newRawatTindak->KodeTindakan = $rawatTindakBf->KodeTindakan;
								$newRawatTindak->NamaTindakan = $rawatTindakBf->NamaTindakan;
								$newRawatTindak->TarifTindakan = $rawatTindakBf->TarifTindakan;
								$newRawatTindak->Jml = $rawatTindakBf->Jml;
								$newRawatTindak->Total = $rawatTindakBf->Total;
								$newRawatTindak->Dokter = $request->namaDokterDPJP;
								$newRawatTindak->KodePoli = $namapoli->KodePoli;
								$newRawatTindak->KodeLoket = $rawatTindakBf->KodeLoket;
								$newRawatTindak->KodeAss = $rawatTindakBf->KodeAss;
								$newRawatTindak->Kasir = $rawatTindakBf->Kasir;
								$newRawatTindak->GT = $rawatTindakBf->GT;
								$newRawatTindak->GTPPN = $rawatTindakBf->GTPPN;
								$newRawatTindak->GTNilai = $rawatTindakBf->GTNilai;
								$newRawatTindak->Dibayar = $rawatTindakBf->Dibayar;
								$newRawatTindak->PoliRujukan = $rawatTindakBf->PoliRujukan;
								$newRawatTindak->NoBayar = $rawatTindakBf->NoBayar;
								$newRawatTindak->Japel = $rawatTindakBf->Japel;
								$newRawatTindak->JRS = $rawatTindakBf->JRS;
								$newRawatTindak->NoSEP = $respon['response']['sep']['noSep'];
								$newRawatTindak->DiagnosaPrimer = $rawatTindakBf->DiagnosaPrimer;
								$newRawatTindak->Diagnosa1 = $rawatTindakBf->Diagnosa1;
								$newRawatTindak->Diagnosa2 = $rawatTindakBf->Diagnosa2;
								$newRawatTindak->Diagnosa3 = $rawatTindakBf->Diagnosa3;
								$newRawatTindak->Diagnosa4 = $rawatTindakBf->Diagnosa4;
								$newRawatTindak->Diagnosa5 = $rawatTindakBf->Diagnosa5;
								$newRawatTindak->NamaDiagnosa1 = $rawatTindakBf->NamaDiagnosa1;
								$newRawatTindak->NamaDiagnosa2 = $rawatTindakBf->NamaDiagnosa2;
								$newRawatTindak->NamaDiagnosa3 = $rawatTindakBf->NamaDiagnosa3;
								$newRawatTindak->NamaDiagnosa4 = $rawatTindakBf->NamaDiagnosa4;
								$newRawatTindak->NamaDiagnosa5 = $rawatTindakBf->NamaDiagnosa5;
								$newRawatTindak->NamaDiagnosaPrimer = $rawatTindakBf->NamaDiagnosaPrimer;
								$newRawatTindak->NoPeserta = $resultRujukans['response']['rujukan']['peserta']['noKartu'];
								$newRawatTindak->NamaAsuransi = $rawatTindakBf->NamaAsuransi;
								$newRawatTindak->NoKuitansi = $rawatTindakBf->NoKuitansi;
								$newRawatTindak->TS = date("Y-m-d H:i:s");
								$newRawatTindak->save();
							*/
								$regi = ($reg) ? 'reg berhasil':'reg gagal';

								// $noarsip = Register::whereBetween('Tgl_Register',[date('Y-m-d').' 00:00:00',date('Y-m-d').' 23.59.59'])->count('No_Register'); // db lokal
								$noarsip = Rsu_Register::whereBetween('Tgl_Register',[date('Y-m-d').' 00:00:00',date('Y-m-d').' 23.59.59'])->count('No_Register'); // db rsu
								$noarsip = ($noarsip == 0 ) ? 1 : $noarsip;
								// return ['status'=>'success', 'code'=>'200','messages'=>"return",'nosep'=> $respon['response']['sep']['noSep'], 'sep'=>$respon, 'reg'=>$regi, 'noarsip'=>$noarsip];

								$return = ['status' => 'success', 'messages' => 'Berhasil', 'data' => $reg, 'statusReg' => $regi];

								// ================================================================================
								// ================================ End Insert SEP ================================
								// ================================================================================

								// ================================ Start Print SEP ===============================
								// ================================================================================
								$noSep = $respon2['sep']['noSep']; // asli
								// $urlSEP = "https://dvlp.bpjs-kesehatan.go.id/vclaim-rest/SEP/".$noSep; //url web service bpjs develop
								// $urlSEP = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/SEP/".$noSep; //url web service bpjs rilis
								$url = $this->url."/SEP/".$noSep; //url web dev service bpjs
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
									date_default_timezone_set('Asia/Jakarta');
									$rowSep['sepValue'] = [
											'jam' => date('d-m-Y H:i:s'),
											'no_sep' => $result['response']->noSep,
											'tgl_sep' => $result['response']->tglSep,
											'no_kartu' => $result['response']->peserta->noKartu,
											'noMr' => $result['response']->peserta->noMr,
											'nama_kartu' => $result['response']->peserta->nama,
											'tgl_lahir' => $result['response']->peserta->tglLahir,
											'jenis_kelamin' => $result['response']->peserta->kelamin,
											'poli_tujuan' => $result['response']->poli,
											'diagnosa' => $result['response']->diagnosa,
											'jenis_rawat' => $result['response']->jnsPelayanan,
											'catatan' => $result['response']->catatan,
											'kls_rawat' => $result['response']->peserta->hakKelas,
											'noarsip' => $noarsip,
											'jnsPeserta' => $resultRujukans['response']->rujukan->peserta->jenisPeserta->keterangan,
											// 'noTelepon' => $resultRujukans['response']->rujukan->peserta->mr->noTelepon,
											'noTelepon' => $phonePasien,
											'fakses' => $resultRujukans['response']->rujukan->provPerujuk->kode,
											'kdDiagnosa' => $resultRujukans['response']->rujukan->diagnosa->kode,
											'noRegister' => $nourut,
									];

									$return = ['status' => 'success', 'messages' => 'Berhasil', 'data'=>$rowSep];
									// return $resultSEP;

									// ================================================================================
									// ================================ End Print SEP =================================
									// ================================================================================
								} // Tutup untuk Hasil Insert SEP
							} // Tutup pengecekan insert SEP
							// $return = ['status' => 'error', 'message' => '', 'data' => $datas];
						}else{
						$messages = $resultRujukans['metaData']['message'];
						$return = ['status' => 'error', 'messages' => $messages];
					}
				}
			}else{
				$return = ['status' => 'error', 'messages' => 'Identitas Tidak Ditemukan !!'];
			}
		}else{
			return ['status' => 'error', 'messages' => 'Tanggal yang Dipilih adalah Hari Libur, Silahkan Pilih Hari Lain !!'];
		}
		return $return;
	}

	public function reloadCekRujukan(Request $request){
		if ($request->noBpjs != null OR $request->noBpjs != '') {
			// $cekPas = Customer::where('FieldCust1', $request->noBpjs)->where('KodeCust','!=',$request->noRM)->first(); // db lokal
			$cekPas = rsu_customer::where('FieldCust1', $request->noBpjs)->where('KodeCust','!=',$request->noRM)->first(); // db rsu
			if (empty($cekPas)) {
				// $updateDatPas = Customer::where('KodeCust',$request->noRM)->update(['FieldCust1' => $request->noBpjs]); // db db lokal
				$updateDatPas = rsu_customer::where('KodeCust',$request->noRM)->update(['FieldCust1' => $request->noBpjs]); // db rsu
				// $url = "https://dvlp.bpjs-kesehatan.go.id/vclaim-rest/Rujukan/Peserta/".$request->noBpjs; //url web service bpjs develop
				$url = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/Rujukan/Peserta/".$request->noBpjs; //url web service bpjs rilis
				$consID     = "21095"; //customer ID RS
				$secretKey  = "rsud6778ws122mjkrt"; //secretKey RS
				$method = 'GET';
				$port = '8080';
				$params = '';

				$result = Requestor::set_curl_bridging_new($url, $params, $method, $consID, $secretKey, $port,'',''); // bridging data peserta bpjs
				if ($result === false) {
					return ['status' => 'error', 'messages' => 'Tidak Dapat Terhubung ke Server !!'];
				} else {
					$results = json_decode($result, true);
					if ($results['response'] ==  null) {
						// $url2 = "https://dvlp.bpjs-kesehatan.go.id/vclaim-rest/Rujukan/RS/Peserta/".$request->noBpjs; //url web service bpjs develop
						$url2 = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/Rujukan/RS/Peserta/".$request->noBpjs; //url web service bpjs rilis
						$result2 = Requestor::set_curl_bridging_new($url2, $params, $method, $consID, $secretKey, $port,'',''); // bridging data peserta bpjs
						if ($result2 === false) {
							return ['status' => 'error', 'messages' => 'Tidak Dapat Terhubung ke Server !!'];
						}else{
							$results = json_decode($result2, true);
							if ($results['response'] != null) {
								$prosHas = 1;
							}else{
								$prosHas = 0;
							}
						}
					}else{
						$prosHas = 1;
					}
					// if ($results['response'] != null) {
					if ($prosHas == 1) {
						date_default_timezone_set('Asia/Jakarta');
						$data['nmPoli'] = $results['response']['rujukan']['poliRujukan']['nama'];
						$data['tglRujukan'] = $results['response']['rujukan']['tglKunjungan'];
						$batasRujukan = date('Y-m-d', strtotime('+90 days',strtotime(date('Y-m-d',strtotime($results['response']['rujukan']['tglKunjungan'])))));
						$dateNow = date('Y-m-d');
						$data['tglBatas'] = $batasRujukan;
						if ($dateNow <= $batasRujukan) {
							$data['batasRujukan'] = 'Ready';
						}else{
							$data['batasRujukan'] = 'Over';
						}
						$data['tlpPas'] = $results['response']['rujukan']['peserta']['mr']['noTelepon'];
						$data['noRujuk'] = $results['response']['rujukan']['noKunjungan'];

						$kdPoli = Rsu_BridgingKdPoli::where('kode_lama', $results['response']['rujukan']['poliRujukan']['kode'])->first(); // db rsu
						// $kdPoli = BridgingKdPoli::where('kode_lama', $results['response']['rujukan']['poliRujukan']['kode'])->first(); // db lokal
						if (!empty($kdPoli)) {
							$data['kdPoli'] = $kdPoli->kode_baru;
						}else{
							$data['kdPoli'] = $results['response']['rujukan']['poliRujukan']['kode'];
						}
						$data['kdPoliRiwayat'] = $results['response']['rujukan']['poliRujukan']['kode'];
						$data['polis'] = rsu_poli::join('mapping_poli_bridging','mapping_poli_bridging.kdpoli_rs','=','tm_poli.KodePoli')->get(); // db rsu
						// $data['polis'] = Poli::join('mapping_poli_bridging','mapping_poli_bridging.kdpoli_rs','=','tm_poli.KodePoli')->get(); // db lokal

						// $cekSebelum = RiwayatRegistrasi::join('tr_registrasi','tr_registrasi.No_Register','=','riwayat_registrasi.No_Register')
						//                                 ->where('no_rujukan', $data['noRujuk'])
						//                                 ->orderBy('id_riwayat_regis','DESC')
						//                                 ->first();
						$cekSebelum = Rsu_RiwayatRegistrasi::where('no_rujukan', $data['noRujuk'])
															->orderBy('id_riwayat_regis','DESC')
															->first();
						if (!empty($cekSebelum)) {
							// if ($cekSebelum->no_rm != null) {
							//     $noRm4Cek = $cekSebelum->no_rm;
							// }else{
							//     $noRm4Cek = $cekPas->KodeCust;
							// }
							// $sebelumnya = Rsu_RawatJalanTindakan::where('No_RM', $noRm4Cek)->orderBy('Tgl','DESC')->first(); // db rsu
							// // $sebelumnya = RawatJalanTindakan::where('No_RM', $cekSebelum->no_rm)->orderBy('Tgl','DESC')->first(); // bd lokal
							// $data['dokterSebelum'] = $sebelumnya->Dokter;
							// $poliSebelum = Rsu_Bridgingpoli::where('kdpoli_rs',$sebelumnya->KodePoli)->first();
							// $urlDokter = "https://dvlp.bpjs-kesehatan.go.id/vclaim-rest/referensi/dokter/pelayanan/2/tglpelayanan/".date('Y-m-d')."/Spesialis/".$cekSebelum->poli_bpjs; // url develop
							$urlDokter = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/referensi/dokter/pelayanan/2/tglpelayanan/".date('Y-m-d')."/Spesialis/".$cekSebelum->poli_bpjs; // url rilis
							$resultDokter = Requestor::set_curl_bridging_new($urlDokter, $params, $method, $consID, $secretKey, $port,'','');
							$hslDokter = json_decode($resultDokter, true);
							$kodeDPJP = '';
							$namaDPJP = '';
							// $stDpjp = '0';
							for($z=0;$z<count($hslDokter['response']['list']);$z++){
								if (strtolower($hslDokter['response']['list'][$z]['nama']) == strtolower($sebelumnya->Dokter)) {
									$kodeDPJP = $hslDokter['response']['list'][$z]['kode'];
									$namaDPJP = $hslDokter['response']['list'][$z]['nama'];
									// $stDpjp = '1';
								}
							}
							// if ($stDpjp == '0') {
							//     $dokterAlternatif = rsu_dokter_bridging::where('kode', $poliSebelum->kdpoli_rs)->first();
							//     for($zn=0;$zn<count($hslDokter['response']['list']);$zn++){
							//         if (strtolower($hslDokter['response']['list'][$zn]['nama']) == strtolower($dokterAlternatif->dokter)) {
							//             $kodeDPJP = $hslDokter['response']['list'][$zn]['kode'];
							//         }
							//     }
							//     $data['dokterSebelum'] = $dokterAlternatif->dokter;
							// }
							$data['kodeDPJP'] = $kodeDPJP;
							$data['dokterSebelum'] = $namaDPJP;
							$data['record'] = 'exists';
							$panjanNoAwal = strlen($cekSebelum->No_Register) - 6;
							$panjanNo = strlen($cekSebelum->No_Register);
							$data['noSurat'] = substr($cekSebelum->No_Register, $panjanNoAwal,$panjanNo);
							$data['kdPoliRiwayat'] = $cekSebelum->poli_bpjs;
						}else{
							$data['dokterSebelum'] = '';
							$data['kodeDPJP'] = '';
							$data['record'] = 'nothing';
							$data['noSurat'] = '';
						}


					}else{
						$data['nmPoli'] = 'Gagal';
						$data['tglRujukan'] = '';
						$data['batasRujukan'] = 'Over';
						$data['tlpPas'] = '';
						$data['noRujuk'] = '';

						$data['kdPoli'] = '';
						$data['dokterSebelum'] = '';
						$data['kodeDPJP'] = '';
						$data['record'] = 'nothing';
						$data['noSurat'] = '';
						$data['kdPoliRiwayat'] = '';
					}
					return ['status' => 'success', 'messages' => '', 'data' => $data];
				}
			}else{
				return ['status' => 'error', 'messages' => 'No BPSJ sudah digunakan untuk Pasien lain !!'];
			}
		}else{
			return ['status' => 'error', 'messages' => 'No BPJS Harus di Isi !!'];
		}
	}

	public function reloadNoRujukan(Request $request){
		if ($request->noRujuk != null OR $request->noRujuk != '') {
			// $cekPas = Customer::where('FieldCust1', $request->noBpjs)->first(); // db lokal
			$cekPas = rsu_customer::where('FieldCust1', $request->noBpjs)->first(); // db rsu
			// if (empty($cekPas)) {
				$url = $this->url."/Rujukan/".$request->noRujuk; //url web service bpjs rilis
				$consID = $this->consid; //customer ID RS
				$secretKey = $this->secretkey; //secretKey RS
				$method = 'GET';
				$port = '8080';
				$params = '';

				$result = Requestor::set_new_curl_bridging($url, $params, $method, $consID, $secretKey, $port,'',''); // bridging data peserta bpjs
				if ($result === false) {
					return ['status' => 'error', 'messages' => 'Tidak Dapat Terhubung ke Server !!'];
				} else {
					// $results = json_decode($result, true);
					$results = [
						'metaData' => json_decode($result['metaData']),
						'response' => json_decode($result['response']),
					];
					if ($results['response'] ==  null) {
						$url2 = $this->url."/Rujukan/RS/".$request->noRujuk;
						$result2 = Requestor::set_new_curl_bridging($url2, $params, $method, $consID, $secretKey, $port,'','');
						if ($result2 === false) {
							return ['status' => 'error', 'messages' => 'Tidak Dapat Terhubung ke Server !!'];
						}else{
							$results = [
								'metaData' => json_decode($result2['metaData']),
								'response' => json_decode($result2['response']),
							];
							// $results = json_decode($result2, true);
							if ($results['response'] != null) {
								$prosHas = 1;
							}else{
								$prosHas = 0;
							}
						}
					}else{
						$prosHas = 1;
					}
					// return $results;
					// if ($results['response'] != null) {
					if ($prosHas == 1) {
						date_default_timezone_set('Asia/Jakarta');
						$data['nmPoli'] = $results['response']->rujukan->poliRujukan->nama;
						$data['tglRujukan'] = $results['response']->rujukan->tglKunjungan;
						$batasRujukan = date('Y-m-d', strtotime('+90 days',strtotime(date('Y-m-d',strtotime($results['response']->rujukan->tglKunjungan)))));
						$dateNow = date('Y-m-d');
						$data['tglBatas'] = $batasRujukan;
						if ($dateNow <= $batasRujukan) {
							$data['batasRujukan'] = 'Ready';
						}else{
							$data['batasRujukan'] = 'Over';
						}
						$data['tlpPas'] = $results['response']->rujukan->peserta->mr->noTelepon;
						$data['noRujuk'] = $results['response']->rujukan->noKunjungan;

						$kdPoli = Rsu_BridgingKdPoli::where('kode_lama', $results['response']->rujukan->poliRujukan->kode)->first(); // db rsu
						// $kdPoli = BridgingKdPoli::where('kode_lama', $results['response']['rujukan']['poliRujukan']['kode'])->first(); // db lokal
						if (!empty($kdPoli)) {
							$data['kdPoli'] = $kdPoli->kode_baru;
						}else{
							$data['kdPoli'] = $results['response']->rujukan->poliRujukan->kode;
						}
						$data['kdPoliRiwayat'] = $results['response']->rujukan->poliRujukan->kode;
						$data['polis'] = rsu_poli::join('mapping_poli_bridging','mapping_poli_bridging.kdpoli_rs','=','tm_poli.KodePoli')->get(); // db rsu
						// $data['polis'] = Poli::join('mapping_poli_bridging','mapping_poli_bridging.kdpoli_rs','=','tm_poli.KodePoli')->get(); // db lokal

						//Menghitung Jumlah Hari Rujukan hingga hari ini
						$CheckInX = explode("-", date('Y-m-d',strtotime($results['response']->rujukan->tglKunjungan)));
						$CheckOutX =  explode("-", $dateNow);
						$dateIn1 =  mktime(0, 0, 0, $CheckInX[1],$CheckInX[2],$CheckInX[0]);
						$dateOut2 =  mktime(0, 0, 0, $CheckOutX[1],$CheckOutX[2],$CheckOutX[0]);
						$intervalDays =($dateOut2 - $dateIn1)/(3600*24);
						$data['intervalRujuk'] = $intervalDays;

						// $cekSebelum = RiwayatRegistrasi::join('tr_registrasi','tr_registrasi.No_Register','=','riwayat_registrasi.No_Register')
						//                                 ->where('no_rujukan', $data['noRujuk'])
						//                                 ->orderBy('id_riwayat_regis','DESC')
						//                                 ->first();
						$cekSebelum = Rsu_RiwayatRegistrasi::where('no_rujukan', $data['noRujuk'])
							->orderBy('id_riwayat_regis','DESC')
							->first();
						if (!empty($cekSebelum)) {
							// if ($cekSebelum->no_rm != null) {
							//     $noRm4Cek = $cekSebelum->no_rm;
							// }else{
							//     $noRm4Cek = $cekPas->KodeCust;
							// }
							// // $sebelumnya = RawatJalanTindakan::where('No_RM', $cekSebelum->no_rm)->orderBy('Tgl','DESC')->first(); // db lokal
							// $sebelumnya = Rsu_RawatJalanTindakan::where('No_RM', $noRm4Cek)->orderBy('Tgl','DESC')->first(); // db rsu
							// $data['dokterSebelum'] = $sebelumnya->Dokter;
							// $poliSebelum = Rsu_Bridgingpoli::where('kdpoli_rs',$sebelumnya->KodePoli)->first();
							// $urlDokter = "https://dvlp.bpjs-kesehatan.go.id/vclaim-rest/referensi/dokter/pelayanan/2/tglpelayanan/".date('Y-m-d')."/Spesialis/".$cekSebelum->poli_bpjs; // url develop
							$urlDokter = $this->url."/referensi/dokter/pelayanan/2/tglpelayanan/".date('Y-m-d')."/Spesialis/".$cekSebelum->poli_bpjs; // url rilis
							$resultDokter = Requestor::set_new_curl_bridging($urlDokter, $params, $method, $consID, $secretKey, $port,'','');
							$hslDokter = [
								'metaData' => json_decode($resultDokter['metaData']),
								'response' => json_decode($resultDokter['response']),
							];
							// $hslDokter = json_decode($resultDokter, true);
							$kodeDPJP = '';
							$namaDPJP = '';
							// $stDpjp = '0';
							// for($z=0;$z<count($hslDokter['response']['list']);$z++){
							// 	if (strtolower($hslDokter['response']['list'][$z]['nama']) == strtolower($cekSebelum->nama_dpjp)) {
							// 		$kodeDPJP = $hslDokter['response']['list'][$z]['kode'];
							// 		$namaDPJP = $hslDokter['response']['list'][$z]['nama'];
							// 		// $stDpjp = '1';
							// 	}
							// }
							foreach ($hslDokter['response']->list as $v) {
								if (strtolower($v->nama) == strtolower($cekSebelum->nama_dpjp)) {
									$kodeDPJP = $v->kode;
									$namaDPJP = $v->nama;
								}
							}
							// if ($stDpjp == '0') {
							//     $dokterAlternatif = rsu_dokter_bridging::where('kode', $poliSebelum->kdpoli_rs)->first();
							//     for($zn=0;$zn<count($hslDokter['response']['list']);$zn++){
							//         if (strtolower($hslDokter['response']['list'][$zn]['nama']) == strtolower($dokterAlternatif->dokter)) {
							//             $kodeDPJP = $hslDokter['response']['list'][$zn]['kode'];
							//         }
							//     }
							//     $data['dokterSebelum'] = $dokterAlternatif->dokter;
							// }
							$data['kodeDPJP'] = $kodeDPJP;
							$data['dokterSebelum'] = $namaDPJP;
							$data['record'] = 'exists';
							$panjanNoAwal = strlen($cekSebelum->No_Register) - 6;
							$panjanNo = strlen($cekSebelum->No_Register);
							$data['noSurat'] = substr($cekSebelum->No_Register, $panjanNoAwal,$panjanNo);
							$data['kdPoliRiwayat'] = $cekSebelum->poli_bpjs;
						}else{
							$data['dokterSebelum'] = '';
							$data['kodeDPJP'] = '';
							$data['record'] = 'nothing';
							$getNoSrt = Rsu_Register::where('NoPeserta',$cekPas->FieldCust1)->orderBy('No_Register','DESC')->first();
							$panjanNoAwal = strlen($getNoSrt->No_Register) - 6;
							$panjanNo = strlen($getNoSrt->No_Register);
							// $data['noSurat'] = '';
							$data['noSurat'] = substr($getNoSrt->No_Register, $panjanNoAwal,$panjanNo);
						}

					}else{
						$data['nmPoli'] = 'Gagal';
						$data['tglRujukan'] = '';
						$data['batasRujukan'] = 'Over';
						$data['tlpPas'] = '';
						$data['noRujuk'] = '';
						$data['intervalRujuk'] = '';

						$data['kdPoli'] = '';
						$data['dokterSebelum'] = '';
						$data['kodeDPJP'] = '';
						$data['record'] = 'nothing';
						$data['noSurat'] = '';
						$data['kdPoliRiwayat'] = '';
					}
					return ['status' => 'success', 'messages' => '', 'data' => $data];
				}
			// }else{
				// return ['status' => 'error', 'messages' => 'No BPSJ sudah digunakan untuk Pasien lain !!'];
			// }
		}else{
			return ['status' => 'error', 'messages' => 'No Rujukan Harus di Isi !!'];
		}
	}

	public function getKdPoliBridging(Request $request){
		// $poli = Poli::join('mapping_poli_bridging','mapping_poli_bridging.kdpoli_rs','=','tm_poli.KodePoli') // db lokal
		$poli = rsu_poli::join('mapping_poli_bridging','mapping_poli_bridging.kdpoli_rs','=','tm_poli.KodePoli') // db rsu
					->where('kdpoli', $request->valKdPoli)
					->first();
		$data['nmPoli'] = $poli->NamaPoli;
		$data['kdPoli'] = $poli->kdpoli;
		// $data['polis'] = Poli::join('mapping_poli_bridging','mapping_poli_bridging.kdpoli_rs','=','tm_poli.KodePoli')->get(); // db lokal
		$data['polis'] = rsu_poli::join('mapping_poli_bridging','mapping_poli_bridging.kdpoli_rs','=','tm_poli.KodePoli')->get(); // db rsu
		return ['status' => 'success', 'messages' => '', 'data' => $data];
	}

	public function bridging2Cc(Request $request){
		$data = $request->all();
		date_default_timezone_set('Asia/Jakarta');
		$dateNow = date('Y-m-d');
		$tglPemeriksaan = date('Y-m-d', strtotime($request->tanggal));
		if ($tglPemeriksaan > $dateNow) {
			// $tglbatasRujukan = date('Y-m-d', strtotime('+3 month', strtotime(date('Y-m-d',strtotime($request->tglRujukan))))); // 3 bulan
			if (!empty($request->tglRujukan)) {
				$tglbatasRujukan = date('Y-m-d', strtotime('+90 days', strtotime(date('Y-m-d',strtotime($request->tglRujukan))))); // 90 Hari
			}else {
				$tglbatasRujukan = $tglPemeriksaan;
			}
			$dt['tglbatasRujukan'] = $tglbatasRujukan;
			$dt['tglPemeriksaan'] = $tglPemeriksaan;
			if ($tglPemeriksaan <= $tglbatasRujukan) {
				$dt['batasRujukan'] = 'Ready';
				// $cekNoRegis = CC::where('tanggal',date('d-m-Y', strtotime($tglPemeriksaan)))->orderby('nourut','DESC')->first(); // bd lokal
				$cekNoRegis = Rsu_cc::where('tanggal',date('d-m-Y', strtotime($tglPemeriksaan)))->orderby('nourut','DESC')->first(); // bd rsu
				if (empty($cekNoRegis)) {
					$no_urut_regis = '1';
				}else{
					$no_urut_regis = $cekNoRegis->nourut + 1;
				}
				// Cek Poli
				// $cekPoli = Poli::join('mapping_poli_bridging','mapping_poli_bridging.kdpoli_rs','=','tm_poli.KodePoli')
				$cekPoli = rsu_poli::join('mapping_poli_bridging','mapping_poli_bridging.kdpoli_rs','=','tm_poli.KodePoli')
								->where('kdpoli',$request->kdSelectedPoli)
								->first();
				$dt['poli'] = $cekPoli;
				// Ambil No Urut Poli
				// $cekNoPoli = CC::where('tanggal',date('d-m-Y', strtotime($tglPemeriksaan)))->where('poli',$cekPoli->NamaPoli)->orderby('urutpoli','DESC')->first(); // lokal
				// Estimasi Pelayanan Start
				$cekNoPoli = Rsu_cc::where('tanggal',date('d-m-Y', strtotime($tglPemeriksaan)))->where('poli',$cekPoli->NamaPoli)->orderby('urutpoli','DESC')->first();
				if (empty($cekNoPoli)) {
					$no_urut_poli = '1';
					$getDataKode = Poli::where('kode_poli', $cekPoli->KodePoli)->first();
				}else{
					$no_urut_poli = $cekNoPoli->urutpoli + 1;
					$getDataKode = Poli::where('kode_poli', $cekNoPoli->poli)->first();
				}
				$getEstimasi = DB::connection('dbrsudlain')->table('layanan')->where('tampil', 1)->where('kodepoli', $getDataKode->kode_poli)->first();
				$timeEstimate = "0";
				if ($getEstimasi) {
					$timeBuka = strtotime($getEstimasi->jamlayanan);
					$timeBukaPakai = strtotime(date("H:i", strtotime('-'.$getEstimasi->estimasi.' minutes', $timeBuka)));
					$timeEstimate = date("H:i", strtotime('+'.$getEstimasi->estimasi*$no_urut_poli.' minutes', $timeBukaPakai));
				}
				// Estimasi Pelayanan End

				// return $cekAja = ['cekPoli'=>$cekNoPoli,'no urut'=>$no_urut_poli];
				// Info Customer
				// $cust = Customer::where('KodeCust',$request->KodeCust)->first();
				$cust = rsu_customer::where('KodeCust',$request->KodeCust)->first();

				$dt['2cc'] = [
					'norm' => $cust->KodeCust,
					'nama' => $cust->NamaCust,
					'alamat' => $cust->Alamat,
					'penanggung' => $request->jenis_pendaftaran,
					'poli' => $cekPoli->NamaPoli,
					'tanggal' => date('d-m-Y', strtotime($tglPemeriksaan)),
					'nourut' => $no_urut_regis,
					// 'notelp' => $cust->Telp,
					'notelp' => $request->phonePasien,
					'KET' => '',
					'nobpjs' => $request->noBpjs,
					'jam' => date('Y-m-d H:i:s'),
					'urutpoli' => $no_urut_poli,
					'pendaftaran' => $request->jenis_pendaftaran,
					'status' => '',
					'norujukan' => $request->noRujuk,
					'new' => [
						'kodeDPJP' => $request->kodeDPJP,
						'namaDPJP' => $request->namaDokterDPJP,
						// 'noSurat' => $request->noSurat,
					],
				];

				// $adCC = new CC; // bd lokal
				$adCC = new Rsu_cc; // bd rsu
				$adCC->norm = $request->KodeCust; // v
				$adCC->nama = $cust->NamaCust; // v
				$adCC->alamat = $cust->Alamat; // v
				// $adCC->penanggung = null;
				$adCC->penanggung = $request->jenis_pendaftaran; // v
				$adCC->poli = $cekPoli->NamaPoli; // v
				$adCC->tanggal = date('d-m-Y', strtotime($tglPemeriksaan)); // v
				$adCC->nourut = $no_urut_regis; // v
				$adCC->notelp = $request->phonePasien; // v
				$adCC->KET = ''; // v
				$adCC->nobpjs = $request->noBpjs; // v
				$adCC->jam = date('Y-m-d H:i:s'); // v
				$adCC->urutpoli = $no_urut_poli; // v
				$adCC->pendaftaran = $request->jenis_pendaftaran; // v
				$adCC->status = ''; // v
				$adCC->norujukan = $request->noRujuk; // v
				$adCC->kodeDPJP = $request->kodeDPJP; // v
				$adCC->namaDPJP = $request->namaDokterDPJP; // v
				$adCC->save();

				if (date('m', strtotime($adCC->tanggal)) == 1) { $bulan = "Januari";}
				elseif(date('m', strtotime($adCC->tanggal)) == 2){$bulan = "Februari";}
				elseif(date('m', strtotime($adCC->tanggal)) == 3){$bulan = "Maret";}
				elseif(date('m', strtotime($adCC->tanggal)) == 4){$bulan = "April";}
				elseif(date('m', strtotime($adCC->tanggal)) == 5){$bulan = "Mei";}
				elseif(date('m', strtotime($adCC->tanggal)) == 6){$bulan = "Juni";}
				elseif(date('m', strtotime($adCC->tanggal)) == 7){$bulan = "Juli";}
				elseif(date('m', strtotime($adCC->tanggal)) == 8){$bulan = "Agustus";}
				elseif(date('m', strtotime($adCC->tanggal)) == 9){$bulan = "September";}
				elseif(date('m', strtotime($adCC->tanggal)) == 10){$bulan = "Oktober";}
				elseif(date('m', strtotime($adCC->tanggal)) == 11){$bulan = "November";}
				elseif(date('m', strtotime($adCC->tanggal)) == 12){$bulan = "Desember";}
				$tgl = date('d', strtotime($adCC->tanggal))." ".$bulan." ".date('Y', strtotime($adCC->tanggal));

				// return ['status'=>'error', 'messages'=>'Ini Percobaan', 'data'=>$data, 'use'=>$dt];
				if ($adCC) {
					$return = ['estimasipelayanan'=> $timeEstimate,'status' => 'success', 'message' => 'Pendaftaran Berhasil Dilakukan !!','data'=>$adCC,'tgl'=>$tgl,'jam'=>date('H:i:s'),'pendaftaran'=>$request->jenis_pendaftaran];
				}else{
					$return = ['status' => 'error', 'message' => 'Pendaftaran Gagal Dilakukan !!'];
				}
				return $return;
			}else{
				$dt['batasRujukan'] = 'Over';
				return ['status'=>'error', 'messages'=>'Masa Berlaku Rujukan pada Tanggal Pemeriksaan Telah Habis !!'];
			}
		}else{
			return ['status'=>'error', 'messages'=>'Tanggal Sudah Terlewat, Silahkan cek kembali Tanggal Pemeriksaan Anda !!'];
		}
	}

	public function cekDokterDpjp(Request $request){
		$alter = rsu_dokter_bridging::where('dokter',$request->dokter)->first();
		$urlDokter = $this->url."/referensi/dokter/pelayanan/2/tglpelayanan/".date('Y-m-d')."/Spesialis/".$alter->polibpjs; // url rilis
		$consID = $this->consid; //customer ID RS
		$secretKey = $this->secretkey; //secretKey RS
		$method = 'GET';
		$port = '';
		$params = '';

		$resultDokter = Requestor::set_new_curl_bridging($urlDokter, $params, $method, $consID, $secretKey, $port,'','');
		$hslDokter = [
			'metaData' => json_decode($resultDokter['metaData']),
			'response' => json_decode($resultDokter['response']),
		];
		$kodeDPJP = '';
		$namaDPJP = '';
		foreach ($hslDokter['response']->list as $h) {
			if (strtolower($h->nama) == strtolower($alter->dokter)) {
				$kodeDPJP = $h->kode;
				$namaDPJP = $h->nama;
			}
		}
		$data['kodeDPJP'] = $kodeDPJP;
		$data['namaDPJP'] = $namaDPJP;
		if ($kodeDPJP != '') {
			$return = ['status' => 'success', 'messages' => 'Dokter Berhasil Di Pilih !!','data'=>$data];
		}else{
			$return = ['status'=>'error', 'messages'=>'Dokter Gagal Di pilih'];
		}
		return $return;
	}

	public function bridging2Cc_apm(Request $request){
		$data = $request->all();
		date_default_timezone_set('Asia/Jakarta');
		$dateNow = date('Y-m-d');
		$tglPemeriksaan = date('Y-m-d', strtotime($request->Tgl));
		if ($tglPemeriksaan > $dateNow) {
			$tglbatasRujukan = date('Y-m-d', strtotime('+90 days', strtotime(date('Y-m-d',strtotime($request->tgl_rujukan))))); // 90 Hari
			$dt['tglbatasRujukan'] = $tglbatasRujukan;
			$dt['tglPemeriksaan'] = $tglPemeriksaan;
			if ($tglPemeriksaan <= $tglbatasRujukan) {
				$dt['batasRujukan'] = 'Ready';
				// CEK/INIT ANTRIAN
				$cekNoRegis = Rsu_cc::where('tanggal', date('d-m-Y', strtotime($tglPemeriksaan)))
					->orderby('nourut','DESC')
					->first(); // bd rsu
				if (empty($cekNoRegis)) {
					$no_urut_regis = '1';
				}else{
					$no_urut_regis = $cekNoRegis->nourut + 1;
				}

				// CEK POLI
				$cekPoli = rsu_poli::join('mapping_poli_bridging','mapping_poli_bridging.kdpoli_rs','=','tm_poli.KodePoli')
					->where('kdpoli', $request->poli)
					->first();
				$dt['poli'] = $cekPoli;

				// CEK DAN INIT ANTRIAN POLI
				// Estimasi Pelayanan Start
				$cekNoPoli = Rsu_cc::where('tanggal',date('d-m-Y', strtotime($tglPemeriksaan)))
					->where('poli', $cekPoli->NamaPoli)
					->orderby('urutpoli','DESC')
					->first();
				if (empty($cekNoPoli)) {
					$no_urut_poli = '1';
					$getDataKode = Poli::where('KodePoli', $cekPoli->KodePoli)->first();
				}else{
					$no_urut_poli = $cekNoPoli->urutpoli + 1;
					$getDataKode = Poli::where('NamaPoli', $cekNoPoli->poli)->first();
				}
				$getEstimasi = DB::connection('dbrsudlain')
					->table('layanan')
					->where('tampil', 1)
					->where('kodepoli', $getDataKode->kode_poli)
					->first();
				$timeEstimate = "0";
				if ($getEstimasi) {
					$timeBuka = strtotime($getEstimasi->jamlayanan);
					$timeBukaPakai = strtotime(date("H:i", strtotime('-'.$getEstimasi->estimasi.' minutes', $timeBuka)));
					$timeEstimate = date("H:i", strtotime('+'.$getEstimasi->estimasi*$no_urut_poli.' minutes', $timeBukaPakai));
				}
				// Estimasi Pelayanan End

				$cust = rsu_customer::where('KodeCust', $request->no_rm)->first();

				$dt['2cc'] = [
					'norm' => $cust->KodeCust,
					'nama' => $cust->NamaCust,
					'alamat' => $cust->Alamat,
					'penanggung' => $request->jenis_pendaftaran,
					'poli' => $cekPoli->NamaPoli,
					'tanggal' => date('d-m-Y', strtotime($tglPemeriksaan)),
					'nourut' => $no_urut_regis,
					// 'notelp' => $cust->Telp,
					'notelp' => $request->notelp,
					'KET' => '',
					'nobpjs' => $request->nokartu,
					'jam' => date('Y-m-d H:i:s'),
					'urutpoli' => $no_urut_poli,
					'pendaftaran' => $request->jenis_pendaftaran,
					'status' => '',
					'norujukan' => $request->no_rujukan,
					'new' => [
						'kodeDPJP' => $request->kdDpjp,
						'namaDPJP' => $request->dpjp_rujuk,
						// 'noSurat' => $request->noSurat,
					],
				];

				$adCC = new Rsu_cc; // bd rsu
				$adCC->norm = $cust->KodeCust;
				$adCC->nama = $cust->NamaCust;
				$adCC->alamat = $cust->Alamat;
				$adCC->penanggung = $request->jenis_pendaftaran;
				$adCC->poli = $cekPoli->NamaPoli;
				$adCC->tanggal = date('d-m-Y', strtotime($tglPemeriksaan));
				$adCC->nourut = $no_urut_regis;
				$adCC->notelp = $request->notelp;
				$adCC->KET = '';
				$adCC->nobpjs = $request->nokartu;
				$adCC->jam = date('Y-m-d H:i:s');
				$adCC->urutpoli = $no_urut_poli;
				$adCC->pendaftaran = $request->jenis_pendaftaran;
				$adCC->status = '';
				$adCC->norujukan = $request->no_rujukan;
				$adCC->kodeDPJP = $request->kdDpjp;
				$adCC->namaDPJP = $request->dpjp_rujuk;
				$adCC->save();

				if (date('m', strtotime($adCC->tanggal)) == 1) { $bulan = "Januari";}
				elseif(date('m', strtotime($adCC->tanggal)) == 2){$bulan = "Februari";}
				elseif(date('m', strtotime($adCC->tanggal)) == 3){$bulan = "Maret";}
				elseif(date('m', strtotime($adCC->tanggal)) == 4){$bulan = "April";}
				elseif(date('m', strtotime($adCC->tanggal)) == 5){$bulan = "Mei";}
				elseif(date('m', strtotime($adCC->tanggal)) == 6){$bulan = "Juni";}
				elseif(date('m', strtotime($adCC->tanggal)) == 7){$bulan = "Juli";}
				elseif(date('m', strtotime($adCC->tanggal)) == 8){$bulan = "Agustus";}
				elseif(date('m', strtotime($adCC->tanggal)) == 9){$bulan = "September";}
				elseif(date('m', strtotime($adCC->tanggal)) == 10){$bulan = "Oktober";}
				elseif(date('m', strtotime($adCC->tanggal)) == 11){$bulan = "November";}
				elseif(date('m', strtotime($adCC->tanggal)) == 12){$bulan = "Desember";}
				$tgl = date('d', strtotime($adCC->tanggal))." ".$bulan." ".date('Y', strtotime($adCC->tanggal));

				if ($adCC) {
					$return = ['estimasipelayanan'=> $timeEstimate,'status' => 'success', 'message' => 'Pendaftaran Berhasil Dilakukan !!','data'=>$adCC,'tgl'=>$tgl,'jam'=>date('H:i:s'),'pendaftaran'=>$request->jenis_pendaftaran];
				}else{
					$return = ['status' => 'error', 'message' => 'Pendaftaran Gagal Dilakukan !!'];
				}
				return $return;
			}else{
				$dt['batasRujukan'] = 'Over';
				return ['status'=>'error', 'messages'=>'Masa Berlaku Rujukan pada Tanggal Pemeriksaan Telah Habis !!'];
			}
		}else{
			return ['status'=>'error', 'messages'=>'Tanggal Sudah Terlewat, Silahkan cek kembali Tanggal Pemeriksaan Anda !!'];
		}
	}
}