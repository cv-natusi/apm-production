<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Hash;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Libraries\Requestor;
use App\Traits\KonfirmasiAntrianTraits;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Http\Models\Users;
use App\Http\Models\Antrian;
use App\Http\Models\TaskId;
use DB;
use Illuminate\Support\Facades\Log;

class AaController extends Controller{
	use KonfirmasiAntrianTraits;

	public function __construct(){
		/* PROD */
		// $this->url = "https://apijkn.bpjs-kesehatan.go.id/vclaim-rest";
		// $this->consid = env('CONS_ID');
		// $this->secretkey = env('SECRET_KEY');
		// $this->userkey = '2079632035f01e757d81a8565b074768';
		/* DEV */
		$this->url = 'https://apijkn-dev.bpjs-kesehatan.go.id/vclaim-rest-dev/';
		$this->consid = '21095';
		$this->secretkey = 'rsud6778ws122mjkrt';
		$this->userkey = '21f330a3e8e9f281d845f6b545b23653';
	}

	public function cekrujukan(Request $request){
		// return $request->all();
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
				$res = $result2['response']->rujukan;
				$myOwnData = [
					"tingkatRujuk" => $data['tingkatRujuk'],
					"noBpjs" => $res->peserta->noKartu,
					"noRujuk" => $res->noKunjungan
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

	public function createUser(Request $request){
		$data = [
			'email'        => $request->email,
			'password'     => Hash::make($request->password),
			'level'        => 1,
			'name_user'    => $request->name,
			'alias'        => "",
			'phone'        => "",
			'address_user' => "",
			'photo_user'        => "admin.jpeg",
			'active'       => "active"
		];
		$insert = DB::connection('mysql')->table('users')->insert($data);
		return response()->json($insert);
	}

	public function report(Request $request){
		// $request->request->add([
		// 	'waktu' => date('Y-m-d H:i:s')
		// ]);
		// return $request->all();
		$tanggalAwal = $request->tanggalAwal;
		$tanggalAkhir = $request->tanggalAkhir;
		$case = $request->case;
		$ambil = $request->metodeAmbil;
		if(!in_array($ambil,['ALL','WA','KIOSK','JKN','SIMAPAN'])){
			return [
				'code' => 406,
				'message' => 'metodeAmbil tidak ditemukan : ALL, WA, KIOSK, JKN, SIMAPAN',
			];
		}
		if(!in_array($case,['antrian','hitung'])){
			return [
				'code' => 406,
				'message' => 'case tidak ditemukan : antrian, hitung',
			];
		}
		if(!$tanggalAwal || !$tanggalAkhir){
			return [
				'code' => 406,
				'message' => 'tanggalAwal atau tanggalAkhir harus diisi'
			];
		}
		if($tanggalAwal && $tanggalAkhir){
			if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$tanggalAwal)){
				return [
					'code' => 406,
					'message' => 'Format tanggalAwal : Y-m-d',
				];
			}
			if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$tanggalAkhir)){
				return [
					'code' => 406,
					'message' => 'Format tanggalAkhir : Y-m-d',
				];
			}
		}


		$query = Antrian::with('task_id');
		if($tanggalAwal && $tanggalAkhir){
			$query->whereBetween('tgl_periksa',[$tanggalAwal,$tanggalAkhir]);
		}else{
			$query->whereBetween('tgl_periksa',[date('Y-m-d'),date('Y-m-d')]);
		}
		if($ambil!='ALL'){
			$query->where('metode_ambil',$ambil);
		}
		$data = $query->limit(300)->has('task_id')->get();
		return [
			'code' => 200,
			'message' => (count($data)>0) ? 'Data ditemukan' : 'Tidak ada antrian',
			'data' => $case=='antrian' ? $data : count($data),
		];
	}
	public function testing(Request $request){
		Log::info("cURL BERHASIL");
		return 'Oke';
	}
}