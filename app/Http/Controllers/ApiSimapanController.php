<?php

namespace App\Http\Controllers;

# Helpers
use App\Helpers\apm as Helpers;
# Library / package
use App\Http\Controllers\Controller;
use App\Http\Libraries\Requestor;
use App\Http\Requests;
use DB,Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
# Models
use App\Http\Models\JadwalDokterInternal;
use App\Http\Models\rsu_dokter_bridging;
# Traits
use App\Traits\FillingTraits;
use App\Traits\KonfirmasiAntrianTraits;

class ApiSimapanController extends Controller{
	use KonfirmasiAntrianTraits;

	public function __construct(){
		/* PROD */
		$this->url = "https://apijkn.bpjs-kesehatan.go.id/vclaim-rest";
		$this->consid = env('CONS_ID');
		$this->secretkey = env('SECRET_KEY');
		$this->userkey = '2079632035f01e757d81a8565b074768';
		/* DEV */
		// $this->url = 'https://apijkn-dev.bpjs-kesehatan.go.id/vclaim-rest-dev/';
		// $this->consid = '21095';
		// $this->secretkey = 'rsud6778ws122mjkrt';
		// $this->userkey = '21f330a3e8e9f281d845f6b545b23653';
	}

	public function requestQr(Request $request){
		$id_kiosk = $request->id_kiosk;

		$generateQr = $this->generateQrCode($id_kiosk);
		return $generateQr;
	}

	public function kategoriPasien(Request $request){
		return [
			'metadata' => [
				'code' => 200,
				'message' => 'Data Berhasil Didapatkan'
			]
		];
	}

	public function refJadDok(Request $request){
		$apiJadDok = new BridgBpjsController;
		$dokter = rsu_dokter_bridging::all();
		$resApi = $apiJadDok->refJadDok($request);

		$arrDokter = [];
		if(!empty($resApi['response'])){
			foreach($dokter as $keyDL => $valDL){
				foreach($resApi['response'] as $key => $val){
					if($valDL['kodedokter']==$val->kodedokter){
						array_push($arrDokter,$val);
					}
				}
			}
			return ['code'=>200,'message'=>'Ok','data'=>$arrDokter];
		}else{
			return ['code'=>404,'messages'=>$resApi['metaData']->message,'data'=>$arrDokter];
		}
	}

	public function getPoliKodeBPJS(){
		$poli = DB::connection('dbrsud')->table('mapping_poli_bridging as m')
			->join('tm_poli as p','m.kdpoli_rs','=','p.KodePoli')
			->groupBy('m.kdpoli_rs')->orderBy('p.KodePoli','asc')
			->get();
		if(!empty($poli)){
			return [
				'status'   => 'success',
				'code'     => 200,
				'message'  => '',
				'data'     => $poli
			];
		}else{
			return [
				'status'   => 'error',
				'code'     => 500,
				'message'  => 'Poli tidak ditemukan',
				'data'     => []
			];
		}
	}

	public function antrianAdd(Request $request){
		try{
			DB::beginTransaction();
			// Log::info("ApiSimapanController - antrianAdd - " . $request->pasienBaru == 0 ? "Lama" : "Baru" . " : ", [$request->all()] );
			date_default_timezone_set('Asia/Jakarta');
			$pasienBaru      = $request->pasienBaru;
			$nomor           = $request->nomor; # (nomor BPJS/NIK) kosongkan jika pasien lama
			$nomorBpjs       = $request->nomor_bpjs; # (nomor BPJS/NIK) kosongkan jika pasien lama
			$namaPasien      = $request->namaPasien; # kosongkan jika pasien lama
			$tglLahir        = $request->tanggalLahir; # kosongkan jika pasien lama
			$namaIbu         = $request->namaIbuKandung; # kosongkan jika pasien lama
			$kodePoli        = strtoupper($request->kodePoli);
			$nohp            = $request->nohp; # kosongkan jika pasien baru
			$tglPeriksa      = $request->tanggalPeriksa;
			$caraBayar       = strtoupper($request->jenisPendaftaran); # kosongkan jika pasien baru
			$isGeriatri      = $request->disabilitas;
			// $kode_dokter     = $request->kode_dokter;
			// $jam_praktek     = $request->jam_praktek;
			$no_rm           = $request->no_rm;
			$nomor_referensi = $request->nomor_referensi;
			$day = date('N',strtotime($request->tanggalPeriksa));

			$request->merge(['jenis_pembayaran'=>$caraBayar]);
			$jadwalDokter = Helpers::randomDokter($request)->getData();
			if($jadwalDokter->metadata->code!=200){
				return [
					'code' => 400,
					'message' => 'Tidak ada jadwal dokter pada poli yang Anda pilih.'
				];
			}
			$jadwalDokter = $jadwalDokter->response;

			$dateCur   = date("Y-m-d");
			$strRandom = $this->randomString(7);
			$cekPasien = "";
			$data      = [];
			if($pasienBaru==""){
				return [
					'code' => 400,
					'message' => 'Pasien baru tidak bisa kosong.'
				];
			}

			if(strlen($nomor)==16){
				$jenisNomor = "nik";
			}elseif(strlen($nomor)==13){
				$jenisNomor = "bpjs";
			}else{
				return [
					'code'    => 400,
					'message' => 'Format nomor kartu tidak sesuai(13 atau 16 digit).'
				];
			}

			if($pasienBaru==1 || $pasienBaru==0){
				if($pasienBaru==1){ // JIKA PASIEN BARU
					$validasi = [
						$nomor,$namaPasien,$tglLahir,
						$namaIbu,$kodePoli,
						$tglPeriksa,$isGeriatri
					];
					$msg = [
						'NIK/BPJS belum diisi.',
						'Nama pasien belum diisi.',
						'Tanggal lahir belum diisi.',
						'Nama ibu belum diisi.',
						'Poli belum diisi.',
						'Tanggal Periksa belum diisi.',
						'Disabilitas belum diisi.'
					];
					$cekValid = $this->validasi($validasi);
					if(count($cekValid)>0){
						return [
							'code'=> 400,
							'message'=> $msg[$cekValid[0]]
						];
					}

					// if(strlen($nomor)==16){
					// 	$jenisNomor = "nik";
					// }elseif(strlen($nomor)==13){
					// 	$jenisNomor = "bpjs";
					// }else{
					// 	return [
					// 		'code'    => 400,
					// 		'message' => 'Format nomor kartu tidak sesuai(13 atau 16 digit).'
					// 	];
					// }

					if(preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$tglLahir)){
						if($tglLahir > $dateCur){
							return [
								'code'    => 400,
								'message' => 'Tanggal Lahir tidak bisa lebih dari tanggal sekarang.'
							];
						}
					}else{
						return [
							'code'    => 400,
							'message' => 'Format tanggal lahir tidak sesuai (yyyy-mm-dd).'
						];
					}

					$cekPeriksa = $this->cekTanggalPeriksa($tglPeriksa);
					if(isset($cekPeriksa)){
						return $cekPeriksa;
					}

					if($jenisNomor=='nik'){
						$data['nik'] = $nomor;
						$data['bpjs'] = $nomorBpjs;
						$cekPasien = DB::connection('mysql')->table('pasien_baru_temporary')
							->where('tanggalPeriksa',$tglPeriksa)
							// ->where('kodePoli',$kodePoli)
							->where('nik',$nomor)
							->first();
					}
					if($jenisNomor=='bpjs'){
						$data['nik'] = $nomor;
						$data['bpjs'] = $nomorBpjs;
						$cekPasien = DB::connection('mysql')->table('pasien_baru_temporary')
							->where('tanggalPeriksa',$tglPeriksa)
							// ->where('kodePoli',$kodePoli)
							->where('bpjs',$nomorBpjs)
							->first();
					}

					$data['isPasienBaru']   = 'Y';
					$data['kodeUnik']       = $strRandom;
					$data['nama']           = $namaPasien;
					$data['tanggalLahir']   = $tglLahir;
					$data['namaIbu']        = $namaIbu;
					$data['kodePoli']       = $kodePoli;
					$data['tanggalPeriksa'] = $tglPeriksa;
					$data['isGeriatri']     = ($isGeriatri==1?'Y':'N');
					$data['caraBayar']      = ($caraBayar=="")?'UMUM':$caraBayar;
					$data['masukMaster']    = 'belum';
					$data['masukTMCust']    = 'belum';
					$data['kode_dokter']    = $jadwalDokter->kode_dokter;
					$data['jam_praktek']    = $jadwalDokter->jam_praktek;
					$data['nomor_referensi']= $caraBayar == "BPJS" ? $nomor_referensi : "";
				}else{ // JIKA PASIEN LAMA
					$validasi = [
						$caraBayar,$tglPeriksa,
						$kodePoli,$nohp,
						$isGeriatri
					];
					$msg = [
						'Jenis pendaftaran belum diisi.',
						'Tanggal periksa belum diisi.',
						'Poli belum diisi.',
						'Nomor HP belum diisi.',
						'Disabilitas belum diisi.',
					];
					$cekValid = $this->validasi($validasi);
					if(count($cekValid)>0){
						return [
							'code'=> 400,
							'message'=> $msg[$cekValid[0]]
						];
					}

					$cekPeriksa = $this->cekTanggalPeriksa($tglPeriksa);
					if(isset($cekPeriksa)){
						return $cekPeriksa;
					}

					// $cekPasien = DB::connection('mysql')->table('pasien_baru_temporary')
					// 	->where('tanggalPeriksa',$tglPeriksa)
					// 	->where('nik',$)
					// 	// ->where('no_hp',$nohp)
					// 	->where('masukMaster','belum')
					// 	->first();

					$data = [
						'isPasienBaru'   => 'N',
						'kodeUnik'       => $strRandom,
						'tanggalPeriksa' => $tglPeriksa,
						'kodePoli'       => $kodePoli,
						'no_hp'          => $nohp,
						'caraBayar'      => $caraBayar,
						'isGeriatri'     => ($isGeriatri==1?'Y':'N'),
						'masukMaster'    => 'belum',
						'masukTMCust'    => 'belum',
						// 'nama'           => null,
						'nama'           => $namaPasien,
						'tanggalLahir'   => null,
						'namaIbu'        => null,
						'kode_dokter'	 => $jadwalDokter->kode_dokter,
						'jam_praktek'	 => $jadwalDokter->jam_praktek,
						'no_rm' 		 => $no_rm,
						'nomor_referensi'=> $caraBayar == "BPJS" ? $nomor_referensi : ""
					];
					if($jenisNomor=='nik'){
						$data['nik'] = $nomor;
						$data['bpjs'] = $nomorBpjs;
						$cekPasien = DB::connection('mysql')->table('pasien_baru_temporary')
							->where('tanggalPeriksa',$tglPeriksa)
							->where('nik',$nomor)
							->first();
					}
					if($jenisNomor=='bpjs'){
						$data['nik'] = $nomor;
						$data['bpjs'] = $nomorBpjs;
						$cekPasien = DB::connection('mysql')->table('pasien_baru_temporary')
							->where('tanggalPeriksa',$tglPeriksa)
							->where('bpjs',$nomorBpjs)
							->first();
					}
				}
				if($isGeriatri==1 || $isGeriatri==0){

				}else{
					return [
						'code' => 400,
						'message' => 'Geriatri Tidak sesuai.'
					];
				}

				if(empty($cekPasien)){
					//insert ke table pasien_baru_temporary
					$insertNewPas = DB::connection('mysql')->table('pasien_baru_temporary')->insert($data);
					
					//jika pasien lama maka insert ke table filling dan tr_registrasi
					$insertFilling = true;
					$insertTrReg = true;
					if($data['isPasienBaru'] == "N"){
						//insert ke table filling
						$dataFilling = $this->fillingPayload((object) $data);
						$insertFilling = DB::connection('mysql')->table('filling')->insert($dataFilling);
						
						//insert ke table tr_registrasi
						$dataCustomer = $this->dataCustomer((String) $data['no_rm']);
						$dataRegistrasi = $this->registrasiPayload($dataCustomer,(object) $data);
						$insertTrReg = DB::connection('dbrsud')->table('tr_registrasi')->insert($dataRegistrasi);
					}

					if(!$insertNewPas || !$insertFilling || !$insertTrReg){
						DB::rollback();
						$response = [
							'status' => 'error',
							'code' => 500,
							'message' => 'Kesalahan Ketika Ambil Antrian, Silahkan Coba Lagi',
						];

						return $response;
					}
					DB::commit();

					if($jenisNomor == "nik"){
						$cekNewPas = DB::connection('mysql')->table('pasien_baru_temporary')
							->where('nik',$nomor)
							->where('tanggalPeriksa',$tglPeriksa)
							->where('masukMaster','belum')
							->first();
					}else{
						$cekNewPas = DB::connection('mysql')->table('pasien_baru_temporary')
							->where('bpjs',$nomorBpjs)
							->where('tanggalPeriksa',$tglPeriksa)
							->where('masukMaster','belum')
							->first();
					}
					if(!empty($cekNewPas)){
						// Log::info("ApiSimapanController - antrianAdd(".$cekNewPas->nik.") - success");
						return [
							'code'     => 200,
							'message'  => 'Ok',
							'response' => [
								'kodeUnik' => $cekNewPas->kodeUnik
							]
						];
					}else{
						return [
							'code'     => 400,
							'message'  => 'Data gagal dimasukkan'
						];
					}
				}else{
					return [
						'code' => 400,
						'message' => 'Nomor antrian hanya dapat diambil 1 kali pada tanggal yang sama.'
					];
				}
			}else{
				return [
					'code' => 400,
					'message' => 'Pasien Baru: {1(Ya),0(Tidak)}'
				];
			}
		}catch(\Exception $th){
			DB::rollback();
			$response = [
				'status' => 'error',
				'code' => 500,
				'message' => 'Gagal Ketika Mengambil Antrian Silahkan Coba Lagi',
				'messageErr' => $th->getMessage()
			];
			Log::info('ApiSimapanController - antrianAdd - error : ', $response);
			return $response;
		}
	}

	public function cekBPJS(Request $request){
		$url = '';
		if($request->jenis == 'nik'){
			$url = $this->url."/Peserta/nik/".$request->nobpjs."/tglSEP/".date('Y-m-d'); //url web dev service bpjs
		}else{
			$url = $this->url."/Peserta/nokartu/".$request->nobpjs."/tglsep/".date('Y-m-d'); //url web dev service bpjs
		}
		$consID     = $this->consid; //customer ID RS
		$secretKey  = $this->secretkey; //secretKey RS
		$uk         = $this->userkey;
		$method = 'GET';
		$result    = Requestor::setCurlBPJS($url,$method,$consID,$secretKey,$uk,'');

		if ($result === false) {
			echo "Tidak dapat menyambung ke server";
		}else{
			$code = $result['metaData']->code;
			if($code==200){
				$respon = [
					'status'  => 'success',
					'code'    => $result['metaData']->code,
					'message' => $result['metaData']->message,
					'data'    => $result['response'],
				];
			}else{
				$respon = [
					'status'  => 'error',
					'code'    => $result['metaData']->code,
					'message' => $result['metaData']->message,
					'data'    => [],
				];
			}
			return $respon;
		}
	}

	public function cekRujukan(Request $request){
		date_default_timezone_set('Asia/Jakarta');
		/*
		RUJUKAN DARI TK 1
		Cara Kerja : Cari Dari TK 1 dulu kalau responnya kosong maka carikan dari TK 2
		Fungsi dibawah, cari rujukan dari TK 1
		*/
		if (!empty($request->noBpjs)) {
			$url = $this->url."/Rujukan/Peserta/".$request->noBpjs; // url develop
			// $url = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/Rujukan/Peserta/".$request->noBpjs; // url rilis
		}else{
			$url = $this->url."/Rujukan/".$request->noRujuk; // url develop
			// $url = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/Rujukan/".$request->noRujuk; // url rilis
		}
		$consID     = $this->consid; //customer ID RS
		$secretKey  = $this->secretkey; //secretKey RS
		$uk         = $this->userkey;
		$method = 'GET';
		$port = '80';
		$result = Requestor::setCurlBPJS($url,$method,$consID,$secretKey,$uk,'');
		if ($result === false) {
			return ['status' => 'error','code'=>500,'message' => 'Tidak Terhubung ke Server !!'];
		}else{
			$results = [
				'metaData' => $result['metaData'],
				'response' => $result['response'],
			];

			$data['tingkatRujuk'] = '1';
			if ($results['response'] == '') {
				/*
				RUJUKAN DARI TK 2
				sekarang cari dari TK 2 karena tidak dapat respon / null dari TK 1
				*/
				if ($results['metaData']->code != 201) {
					return ['status' => 'error', 'code' => 404,'message' => $results['metaData']->message,'data'=>''];
				}
				if (!empty($request->noBpjs)) {
					$url2 = $this->url."/Rujukan/RS/Peserta/".$request->noBpjs; //url web service bpjs develop
					// $url2 = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/Rujukan/RS/Peserta/".$request->noBpjs; //url web service bpjs rilis
				}else{
					$url2 = $this->url."/Rujukan/RS/".$request->noRujuk; //url web service bpjs develop
					// $url2 = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/Rujukan/RS/".$request->noRujuk; // url rilis
				}
				$result2 = Requestor::setCurlBPJS($url2,$method,$consID,$secretKey,$uk,''); // bridging data peserta bpjs
				// $result2 = Requestor::set_curl_bridging_new($url2, $params, $method, $consID, $secretKey, $port,'',''); // bridging data peserta bpjs
				if ($result2 === false) {
					return ['status' => 'error', 'messages' => 'Tidak Dapat Terhubung ke Server !!'];
				}else{
					$results = [
						'metaData' => $result2['metaData'],
						'response' => $result2['response'],
					];
					if ($results['response'] != null) {
						$prosHas = 1;
						$data['tingkatRujuk'] = '2';
					}else{
						$prosHas = 0;
					}
				}
			}else{
				$prosHas = 1;
			}

			if ($prosHas == 1) {
				$data['rujukan'] = $results;
				if(!empty($request->rm)){
					$cekSebelum = Rsu_RiwayatRegistrasi::where('no_rujukan', $results['response']->rujukan->noKunjungan)->orderBy('id_riwayat_regis','DESC')->first();
					$kodeDPJP = '';
					$namaDPJP = '';
					$riwayat = '0';
					if (!empty($cekSebelum)) {
						$urlDokter = $this->url."/referensi/dokter/pelayanan/".$request->rawat."/tglPelayanan/".date('Y-m-d')."/Spesialis/".$cekSebelum->poli_bpjs; // url develop
						// $urlDokter = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/referensi/dokter/pelayanan/".$request->rawat."/tglpelayanan/".date('Y-m-d')."/Spesialis/".$cekSebelum->poli_bpjs; // url rilis
						$resultDokter = Requestor::set_new_curl_bridging($urlDokter, $params, $method, $consID, $secretKey, $port,'','');

						$hslDokter = [
							'metaData' => json_decode($resultDokter['metaData']),
							'response' => json_decode($resultDokter['response']),
						];

						foreach ($hslDokter['response']->list as $k) {
							if (strtolower($k->nama) == strtolower($cekSebelum->nama_dpjp)) {
								$kodeDPJP = $k->kode;
								$namaDPJP = $k->nama;
							}
						}
						$riwayat = '1';

						$panjanNoAwal = strlen($cekSebelum->No_Register) - 6;
						$panjanNo = strlen($cekSebelum->No_Register);
						$noSurat = substr($cekSebelum->No_Register, $panjanNoAwal,$panjanNo);
					}else{
						$regisSebelum = Rsu_Register::where('No_RM', $request->rm)->orderby('Tgl_Register','DESC')->first();
						$panjanNoAwal = strlen($regisSebelum->No_Register) - 6;
						$panjanNo = strlen($regisSebelum->No_Register);
						$noSurat = substr($regisSebelum->No_Register, $panjanNoAwal,$panjanNo);
					}

					$data['kodeDPJP'] = $kodeDPJP;
					$data['namaDPJP'] = $namaDPJP;
					$data['riwayatRegis'] = $riwayat;
					$data['noSurat'] = $noSurat;
				}
				
				//custom return
				$res = $results['response']->rujukan;
				$myOwnData = [
					"tingkatRujuk" => $data['tingkatRujuk'],
					"noBpjs" => $res->peserta->noKartu,
					"nik" => $res->peserta->nik,
					"noRujuk" => $res->noKunjungan,
					"tglKunjungan" => $res->tglKunjungan,
					"tglKunjunganPlus" => date("Y-m-d",strtotime("+89 days",strtotime($res->tglKunjungan))),
				];
				return ['status' => 'success', 'code'=>200 ,'message' => 'Rujukan Ditemukan','data'=>$myOwnData];
				//end custom return

				$return = ['status' => 'success', 'code' => 200, 'message' => 'Rujukan Ditemukan !!', 'data' => $data];
			}else{
				return ['status' => 'error','code' => 500 ,'message' => 'Rujukan Tidak Ditemukan !!','data'=>$data];
			}
			// return $return;
		}
	}

	public function cekKodeUnik(Request $request){
		$nik     = $request->nik;
		$periksa = $request->tanggalPeriksa;

		// validasi start
		$res = [
			'status'  => 'error',
			'code'    => 400,
			'message' => 'NIK Belum Diisi'
		];
		if(!empty($nik)){
			$res['message'] = 'Format NIK Tidak Sesuai';
			if(is_numeric($nik)){
				if(strlen($nik)<>16){
					return $res;
				}
			}else{
				return $res;
			}
		}else{
			return $res;
		}
		if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$periksa)){
			$res['message'] = 'Format Tanggal Periksa Tidak Sesuai';
			return $res;
		}
		// validasi end

		$data = DB::connection('mysql')->table('pasien_baru_temporary')
			->where('nik',$nik)
			->where('tanggalPeriksa',$periksa)
			->where('masukMaster','belum')
			// ->select('kodeUnik','nik','nama','tanggalLahir','no_hp','tanggalPeriksa')
			->first();
		if(!empty($data)){
			$poli = DB::connection('dbrsud')->table('tm_poli')->where('KodePoli',$data->kodePoli)->first();
			return [
				'status'  => 'success',
				'code'    => 200,
				'message' => 'Ok',
				'data'    => [
					'kodeUnik'       => $data->kodeUnik,
					'nik'            => $data->nik,
					'nama'           => $data->nama,
					'tanggalLahir'   => $data->tanggalLahir,
					'no_hp'          => $data->no_hp,
					'tanggalPeriksa' => $data->tanggalPeriksa,
					'isPasienBaru'   => ($data->isPasienBaru=='Y')?0:1,
					'toPoli'         => $poli->NamaPoli
				]
			];
		}else{
			$res['message'] = 'Data Tidak Ditemukan';
			$res['data'] = [];
			return $res;
		}
	}

	function validasi($data){
		$valid = [];
		foreach($data as $key => $val){
			// if(empty((string)($val))){
			if((string)($val)==""){
				if(count($valid)==0){
					array_push($valid, $key);
				}
			}
		}
		return $valid;
	}

	function cekTanggalPeriksa($tglPeriksa){
		date_default_timezone_set('Asia/Jakarta');
		if(preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$tglPeriksa)){
			$dateCur   = date("Y-m-d");
			$datePlus7 = date("Y-m-d",strtotime($dateCur."+7 day"));
			if($tglPeriksa>=$dateCur && $tglPeriksa<=$datePlus7){
			}else{
				return [
					'code'    => 400,
					'message' => 'Tanggal tidak sesuai *(tanggal sekarang s/d H+7).'
				];
			}
		}else{
			return [
				'code'    => 400,
				'message' => 'Format tanggal periksa tidak sesuai (yyyy-mm-dd).'
			];
		}
	}

	function randomString($length){
		$characters = '0123456789abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';
		// $characters = '0123456789';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}

	// public function konfirmasiAntrian(Request $request){
	// 	$hariIni = date('Y-m-d');
	// 	if(!isset($request->kode_booking) && !isset($request->token)){
	// 		return ['status'=> 'error', 'code'=>500 , 'message'=>'Ada Data Yang Tidak Terkirim, Silahkan Coba Lagi'];
	// 	}
	// 	Log::info("Konfirm Android Starter - (".$request->kode_booking." - ". $request->token .")");

	// 	//data request
	// 	$kodeBooking = $request->kode_booking;
	// 	$token = $request->token;

	// 	//mencocokan data
	// 	$dataBooking = DB::connection('mysql')->table('pasien_baru_temporary')
	// 		->where('kodeUnik',$kodeBooking)
	// 		->where('masukMaster',"belum")
	// 		->orderBy('id_pas', 'DESC')
	// 		->first();
	// 	$dataToken = DB::connection('mysql')->table('token_konfirmasi')
	// 		->where('token',$token)
	// 		->where('status',0)
	// 		->first();

	// 	if(!empty($dataBooking) && !empty($dataToken)){
	// 		//validasi antrian
	// 		if($dataBooking->tanggalPeriksa > $hariIni){
	// 			return ['status'=> 'error', 'code'=>500 , 'message'=>'Antrian Harus Dikonfirmasi Sesuai Tanggal('. $dataBooking->tanggalPeriksa .')'];
	// 		}elseif($dataBooking->tanggalPeriksa < $hariIni){
	// 			return ['status'=> 'error', 'code'=>500 , 'message'=>'Antrian Sudah Kadaluarsa Silahkan Ambil Ulang'];
	// 		}

	// 		$noPasBaru = '';
	// 		if($dataBooking->isPasienBaru == "Y"){
	// 			$request->tglperiksa   = $dataBooking->tanggalPeriksa;
	// 			$request->jenis_pasien = $dataBooking->caraBayar;
	// 			$noPasBaru = $this->generateNoAntrianBaru($request);
	// 		}

	// 		//setup data BPJS
	// 		$noKodeBooking = $this->generateNoKodeBooking($dataBooking->isPasienBaru, $dataBooking->tanggalPeriksa);
	// 		$generateReqAntreanBPJS = $this->generateReqAntrean($dataBooking, $noKodeBooking["kode_booking"], $noKodeBooking['nomor_antrian'], "toBpjs", $noPasBaru);
			
	// 		//setup data local
	// 		$generateReqAntreanLocal = $this->generateReqAntrean($dataBooking, $noKodeBooking["kode_booking"], $noKodeBooking['nomor_antrian'], "toLocal", $noPasBaru);
			
	// 		//validasi agar pasien tidak bisa mengambil 2x nomor
	// 		$cekDataAntrian = DB::connection('mysql')->table('antrian')
	// 		->where('nik',$dataBooking->nik)
	// 		->where('tgl_periksa', date('Y-m-d'))
	// 		->first();
	// 		if(!empty($cekDataAntrian)){
	// 			return ['status'=> 'error', 'code'=>500 , 'message'=>'NIK Telah Mengambil Antrian dengan Nomor Antrian '.$cekDataAntrian->no_antrian];
	// 		}

	// 		//hit to BPJS antreanAdd and updateWaktu
	// 		try{
	// 			$antreanBpjs = new BridgBpjsController();
	// 			$postAntreanBpjs =  $antreanBpjs->antreanAdd(new Request($generateReqAntreanBPJS));
	// 			if($postAntreanBpjs['metaData']->code != 200 ){
	// 				if($postAntreanBpjs['metaData']->message == "Terdapat duplikasi Kode Booking"){
	// 					//hit to table antrian dan antrian_tracer
	// 					$postAntrian =  DB::connection('mysql')->table('antrian')->insertGetId($generateReqAntreanLocal);
	// 					$generateReqAntreanTracer = $this->generateReqAntreanTracer($postAntrian, $dataBooking);
	// 					$postAntrianTracer =  DB::connection('mysql')->table('antrian_tracer')->insert($generateReqAntreanTracer);
						
	// 					//update antrian_id di table filling jika pasien lama
	// 					if($dataBooking->isPasienBaru == "N"){
	// 						$updateFilling = DB::connection('mysql')->table('filling')
	// 							->where('no_rm', $dataBooking->no_rm)
	// 							->where('tgl_periksa', $dataBooking->tanggalPeriksa)
	// 							->update(['antrian_id' => $postAntrian]);
	// 					}
	// 				}else{
	// 					throw new \Exception($postAntreanBpjs['metaData']->message, (int)$postAntreanBpjs['metaData']->code);
	// 				}
	// 			}
	// 			Log::info("POST BPJS SUCESS (SIMAPAN) : ", [
	// 				'data' => $generateReqAntreanBPJS,
	// 				'response' => $postAntreanBpjs
	// 			]);
	// 		}catch(\Exception $e){
	// 			Log::info("POST BPJS ERROR (SIMAPAN) : ", [
	// 				'data' => $generateReqAntreanBPJS,
	// 				'messageErr' => $e->getMessage()
	// 			]);
	// 			return ['status'=> 'error', 'code'=>$e->getCode() , 'message'=>$e->getMessage()];
	// 		}

	// 		if($postAntreanBpjs && $postAntrianTracer){
	// 			//menempatkan pasien_baru_temporary ke table token_konfirmasi dan memasukan pasien temporary ke master
	// 			try {
	// 				//update pasien ke token_konfirmasi
	// 				DB::connection('mysql')->table('token_konfirmasi')->where('token',$token)->update([
	// 					'pasien_baru_temporary_id' => $dataBooking->id_pas,
	// 					'status' => 1
	// 				]);

	// 				//update status pasien
	// 				DB::connection('mysql')->table('pasien_baru_temporary')
	// 				->where('id_pas', $dataBooking->id_pas)
	// 				->update(['masukMaster'=>'sudah']);

	// 				Log::info('Berhasil Mengambil Memasukan IdPasien('.$dataBooking->id_pas.') ke Token('.$token.')');
	// 			} catch (\Exception $e) {
	// 				Log::info("konfirmasiAntrian - Error - ", $e->getMessage());
	// 				return ['status'=> 'error', 'code'=>500 , 'message'=>'Kesalahan Konfirmasi, Silahkan Coba Lagi'];
	// 			}
	// 			//log untuk jaga2 data
	// 			$logs = [
	// 				"data" => [
	// 					"dataBpjs" => [
	// 						"antreanAdd" => $generateReqAntreanBPJS,
	// 					], 
	// 					"dataLocal" => [
	// 						"antrian" => $generateReqAntreanLocal,
	// 						"antrian_tracer" => $generateReqAntreanTracer
	// 					] 
	// 				], 
	// 				"responseBpjs" => [
	// 					"antreanAdd" => $postAntreanBpjs,
	// 				],
	// 				"metodeKonfirmasi" => "SCAN"
	// 			];
	// 			Log::info("konfirmasiAntrian - Success - ", $logs);
	// 			//return success
	// 			return ['status' => 'success', 'code'=>200, 'message'=> 'Berhasil Konfirmasi Antrian '. $noKodeBooking['nomor_antrian'] .', Silahkan Ambil Nomor Antrian'];
	// 		}
	// 	}elseif(empty($dataBooking)){
	// 		return ['status'=> 'error', 'code'=>500 , 'message'=>'Data Pasien Tidak Ditemukan, Silahkan Cek Kembali / Ambi Ulang Antrian'];
	// 	}elseif(empty($dataToken)){
	// 		return ['status'=> 'error', 'code'=>500 , 'message'=>'Token QR Sudah Digunakan, Silahkan Refresh Halaman QR'];
	// 	}

	// 	return ['status'=> 'error', 'code'=>500 , 'message'=>'Data Tidak Ditemukan, Silahkan Coba Lagi'];
	// }
	public function konfirmasiAntrian(Request $request){
		$hariIni = date('Y-m-d');
		if(!isset($request->kode_booking) && !isset($request->token)){
			return ['status'=> 'error', 'code'=>500 , 'message'=>'Ada Data Yang Tidak Terkirim, Silahkan Coba Lagi'];
		}
		Log::info("Konfirm Android Starter - (".$request->kode_booking." - ". $request->token .")");

		//data request
		$kodeBooking = $request->kode_booking;
		$token = $request->token;

		//mencocokan data
		$dataBooking = DB::connection('mysql')->table('pasien_baru_temporary')
			->where('kodeUnik',$kodeBooking)
			->where('masukMaster',"belum")
			->orderBy('id_pas', 'DESC')
			->first();
		$dataToken = DB::connection('mysql')->table('token_konfirmasi')
			->where('token',$token)
			->where('status',0)
			->first();

		if(!empty($dataBooking) && !empty($dataToken)){
			//validasi antrian
			if($dataBooking->tanggalPeriksa > $hariIni){
				return ['status'=> 'error', 'code'=>500 , 'message'=>'Antrian Harus Dikonfirmasi Sesuai Tanggal('. $dataBooking->tanggalPeriksa .')'];
			}elseif($dataBooking->tanggalPeriksa < $hariIni){
				return ['status'=> 'error', 'code'=>500 , 'message'=>'Antrian Sudah Kadaluarsa Silahkan Ambil Ulang'];
			}

			$noPasBaru = '';
			if($dataBooking->isPasienBaru == "Y"){
				$request->tglperiksa   = $dataBooking->tanggalPeriksa;
				$request->jenis_pasien = $dataBooking->caraBayar;
				$noPasBaru = $this->generateNoAntrianBaru($request);
			}

			//setup data BPJS
			$noKodeBooking = $this->generateNoKodeBooking($dataBooking->caraBayar, $dataBooking->tanggalPeriksa);
			$generateReqAntreanBPJS = $this->generateReqAntrean($dataBooking, $noKodeBooking["kode_booking"], $noKodeBooking['nomor_antrian'], "toBpjs", "simapan", $noPasBaru);
			
			//setup data local
			$generateReqAntreanLocal = $this->generateReqAntrean($dataBooking, $noKodeBooking["kode_booking"], $noKodeBooking['nomor_antrian'], "toLocal", "simapan", $noPasBaru);
			
			//validasi agar pasien tidak bisa mengambil 2x nomor
			$cekDataAntrian = DB::connection('mysql')->table('antrian')
			->where('nik',$dataBooking->nik)
			->where('tgl_periksa', date('Y-m-d'))
			->first();
			if(!empty($cekDataAntrian)){
				if (!empty($cekDataAntrian->no_antrian_pbaru)) {
					return ['status'=> 'error', 'code'=>500 , 'message'=>'NIK Telah Mengambil Antrian dengan Nomor Antrian '.$cekDataAntrian->no_antrian_pbaru];
				} else {
					return ['status'=> 'error', 'code'=>500 , 'message'=>'NIK Telah Mengambil Antrian dengan Nomor Antrian '.$cekDataAntrian->no_antrian];
				}
			}
			//hit to BPJS antreanAdd and updateWaktu
			try{
				$antreanBpjs = new BridgBpjsController();
				$postAntreanBpjs =  $antreanBpjs->antreanAdd(new Request($generateReqAntreanBPJS));
				if($postAntreanBpjs['metaData']->code==200 || stripos($postAntreanBpjs['metaData']->message,'duplikasi Kode')!==false){
					//hit to table antrian dan antrian_tracer
					$postAntrian =  DB::connection('mysql')->table('antrian')->insertGetId($generateReqAntreanLocal);
					$generateReqAntreanTracer = $this->generateReqAntreanTracer($postAntrian, $dataBooking);
					$postAntrianTracer =  DB::connection('mysql')->table('antrian_tracer')->insert($generateReqAntreanTracer);
					
					//update antrian_id di table filling jika pasien lama
					if($dataBooking->isPasienBaru == "N"){
						$updateFilling = DB::connection('mysql')->table('filling')
							->where('no_rm', $dataBooking->no_rm)
							->where('tgl_periksa', $dataBooking->tanggalPeriksa)
							->update(['antrian_id' => $postAntrian]);
					}
				} else {
					throw new \Exception($postAntreanBpjs['metaData']->message, (int)$postAntreanBpjs['metaData']->code);
				}
				Log::info("POST BPJS SUCESS (SIMAPAN) : ", [
					'data' => $generateReqAntreanBPJS,
					'response' => $postAntreanBpjs
				]);
			}catch(\Exception $e){
				Log::info("POST BPJS ERROR (SIMAPAN) : ", [
					'data' => $generateReqAntreanBPJS,
					'messageErr' => $e->getMessage(),
					'file' => $e->getFile(),
					'line' => $e->getLine()
				]);
				return ['status'=> 'error', 'code'=>$e->getCode() , 'message'=>$e->getMessage()];
			}

			if($postAntreanBpjs || (isset($postAntrianTracer) && $postAntrianTracer)){
				//menempatkan pasien_baru_temporary ke table token_konfirmasi dan memasukan pasien temporary ke master
				try {
					//update pasien ke token_konfirmasi
					DB::connection('mysql')->table('token_konfirmasi')->where('token',$token)->update([
						'pasien_baru_temporary_id' => $dataBooking->id_pas,
						'status' => 1
					]);

					//update status pasien
					DB::connection('mysql')->table('pasien_baru_temporary')
					->where('id_pas', $dataBooking->id_pas)
					->update(['masukMaster'=>'sudah']);

					Log::info('Berhasil Mengambil Memasukan IdPasien('.$dataBooking->id_pas.') ke Token('.$token.')');
				} catch (\Exception $e) {
					Log::info("konfirmasiAntrian - Error - ", $e->getMessage());
					return ['status'=> 'error', 'code'=>500 , 'message'=>'Kesalahan Konfirmasi, Silahkan Coba Lagi'];
				}
				//log untuk jaga2 data
				// $logs = [
				// 	"data" => [
				// 		"dataBpjs" => [
				// 			"antreanAdd" => $generateReqAntreanBPJS,
				// 		], 
				// 		"dataLocal" => [
				// 			"antrian" => $generateReqAntreanLocal,
				// 			"antrian_tracer" => $generateReqAntreanTracer
				// 		] 
				// 	], 
				// 	"responseBpjs" => [
				// 		"antreanAdd" => $postAntreanBpjs,
				// 	],
				// 	"metodeKonfirmasi" => "SCAN"
				// ];
				// Log::info("konfirmasiAntrian - Success - ", $logs);
				//return success
				if($dataBooking->isPasienBaru == "Y"){
					$nomorAntre = DB::table('antrian')
					->where('nik', $dataBooking->nik)
					->first()->no_antrian_pbaru;
					return ['status' => 'success', 'code'=>200, 'message'=> 'Berhasil Konfirmasi Antrian '. $nomorAntre .', Silahkan Ambil Nomor Antrian'];
				}else{
					return ['status' => 'success', 'code'=>200, 'message'=> 'Berhasil Konfirmasi Antrian '. $noKodeBooking['nomor_antrian'] .', Silahkan Ambil Nomor Antrian'];
				}
			}
		}elseif(empty($dataBooking)){
			return ['status'=> 'error', 'code'=>500 , 'message'=>'Data Pasien Tidak Ditemukan, Silahkan Cek Kembali / Ambi Ulang Antrian'];
		}elseif(empty($dataToken)){
			return ['status'=> 'error', 'code'=>500 , 'message'=>'Token QR Sudah Digunakan, Silahkan Refresh Halaman QR'];
		}

		return ['status'=> 'error', 'code'=>500 , 'message'=>'Data Tidak Ditemukan, Silahkan Coba Lagi'];
	}

	public function ubahStatusPrint($token){
		$token = $token;
		try {
			$updateStatusPrint =  DB::connection('mysql')->table('token_konfirmasi')
				->where('token',$token)
				->update(['sudah_print' => 1]);
			return ['status'=> 'success', 'code'=>200 , 'message'=>"Berhasil Di Cetak"];
		} catch (\Exception $th) {
			return ['status'=> 'error', 'code'=>500 , 'message'=>"Gagal Cetak, Silahkan Cetak Kembali"];
		}
	}

	public function nomorUrut(Request $request){
		$no_rm = $request->no_rm;
		if($no_rm == "00000000000"){
			return ['status' => 'error', 'code' => 500, 'message' => 'No RM Belum diUpdate, Silahkan Tanya ke Loket'];
		}

		try {
			//get nomor urut regis dan nourut poli di table antrian
			$dataPasien = DB::connection('mysql')->table('antrian')
				->where('no_rm',$no_rm)
				->where('tgl_periksa',date('Y-m-d'))
				->first();
	
			// validasi jika datapasien tidak ditemukan
			if(!empty($dataPasien)){
				$data = [
					"noUrutRegis" => !empty($dataPasien->no_antrian) ? $dataPasien->no_antrian : "-",
					"noUrutPoli" => !empty($dataPasien->nomor_antrian_poli) ? $dataPasien->nomor_antrian_poli : "-"
				];
			}else{
				$data = [
					"noUrutRegis" => "-",
					"noUrutPoli"  => "-"
				];
			}
			return ['status' => 'success', 'code' => 200, 'message' => 'Berhasil Mendapatakan Nomor Urut Antrian', 'data' => $data];
		} catch (\Exception $e) {
			return ['status' => 'error', 'code' => 500, 'message' => 'Gagal Mendapatakan Nomor Urut Antrian, Coba Refresh Kembali'];
		}
	}

	public function createUser(Request $request){
		$data = [
			'email'        => $request->email,
			'password'     => Hash::make($request->password),
			'level'        => $request->level,
			'lv_user'	   => $request->lv_user,
			'name_user'    => $request->name_user,
			'alias'        => "",
			'phone'        => "",
			'address_user' => "",
			'photo_user'   => "admin.jpeg",
			'active'       => "active"
		];
		$insert = DB::connection('mysql')->table('users')->insert($data);
		return response()->json($insert);
	}
}