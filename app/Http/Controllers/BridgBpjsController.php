<?php

namespace App\Http\Controllers;

# Library & package
use App\Http\Controllers\Controller;
use App\Http\Libraries\Requestor;
use App\Http\Requests;
use DB,DateTime,Log;
use Illuminate\Http\Request;
# Models
use App\Http\Models\Poli;
use App\Http\Models\JadwalDokterInternal;

class BridgBpjsController extends Controller{
	public function __construct(){
		// PRODUCTION START
		$this->url = 'https://apijkn.bpjs-kesehatan.go.id/antreanrs/';
		$this->consid = '21095';
		$this->secretkey = 'rsud6778ws122mjkrt';
		$this->userkey = '364e21ef098e7d6e69889eac7cadb3c3';
		// PRODUCTION END

		// DEVELOP START
		// $this->url = 'https://apijkn-dev.bpjs-kesehatan.go.id/antreanrs_dev/';
		// $this->consid = '21095';
		// $this->secretkey = 'rsud6778ws122mjkrt';
		// $this->userkey = 'dd6817bcc763343bde6eafb760f0c596';
		// DEVELOP END
		date_default_timezone_set('Asia/Jakarta');
	}

	// ANTREAN(FE Wahidin) START
	public function webRefJadDok(Request $request){
		$tanggal = $request->tanggal;
		if(isset($tanggal)){
			return $this->refJadDok($request);
			// return [$a,$request->kodePoli,$tanggal];
		}
		$this->data['classtutup'] = 'sidebar-collapse';
		$this->data['mn_active'] = 'dashboard';
		// $this->data['poli'] = DB::connection('dbrsud')->table('mapping_poli_bridging as m')
		// 		->join('tm_poli as p','m.kdpoli_rs','=','p.KodePoli')->get();
		$getPoli = (object)$this->refPoli();
		$this->data['poli'] = $getPoli->response;
		return view('Admin.antreanBPJS.dokter.referensiJadwalDokter',$this->data);
	}

	public function webEditJadDok(Request $request){
		$status   = $request->status;
		$dokter   = $request->dokter;
		// $senin    = $request->hariOn1;
			// $selasa   = $request->hariOn2;
			// $rabu     = $request->hariOn3;
			// $kamis    = $request->hariOn4;
			// $jumat    = $request->hariOn5;
			// $sabtu    = $request->hariOn6;
			// $minggu   = $request->hariOn7;
			// $liburNas = $request->hariOn8;
			// "hariOn1": "1,1",
			// "hariOff2": "2,0",
			// "hariOff3": "3,0",
			// "hariOff4": "4,0",
			// "hariOff5": "5,0",
			// "hariOff6": "6,0",
			// "hariOff7": "7,0",
			// "hariOff8": "8,0",
		if(isset($status)){
			return $this->updateJadDok($request);
		}
		$refDokter = (object)$this->refDokter();
		$this->data['dokter'] = $refDokter->response;
		// $a = (object)$this->refDokter();
		// dd($a->response);
		$this->data['classtutup'] = 'sidebar-collapse';
		$this->data['mn_active'] = 'dashboard';
		$getPoli = (object)$this->refPoli();
		$this->data['poli'] = $getPoli->response;
		return view('Admin.antreanBPJS.dokter.editJadwalDokter',$this->data);
	}

	public function webGetJadDok(Request $request){
		$kode = $request->kode;
		$poli = $request->poli[0];
		$subPoli = $request->poli[1];
		$resJadDok = DB::connection('mysql')->table('jadwal_dokter')
			->where('kodeDokter',$kode)
			->where('kodeBridgPoli',$poli)
			->where('kodeBridgSubPoli',$subPoli)
			->first();
		// return response()->json($resJadDok);
		if(empty($resJadDok)){
			return ['status'=>'Ok','message'=>'Jadwal Dokter Belum ada','code'=>202];
		}else{
			return ['status'=>'Ok','dokter'=>$resJadDok,'code'=>200];
		}
	}

	public function webSaveJadDok(Request $request){
		return $request->all();
	}

	public function antreanPanggil(Request $request){
		$antrian = DB::connection('mysql')->table('antrian')->where('tgl_periksa',date('Y-m-d'))->count();
		// return response()->json($antrian);
		return view('Admin.antreanBPJS.antreanPanggil.antreanPanggil',['antrian'=>$antrian]);
	}
	// ANTREAN(FE Wahidin) END

	// BRIDGING START
	public function refPoli(){
		$url       = $this->url."ref/poli";
		$consID    = $this->consid; // customer ID RS
		$secretKey = $this->secretkey; // secretKey RS
		$uk        = $this->userkey;
		$method    = 'GET';
		$result    = Requestor::setCurlBridg($url,$method,$consID,$secretKey,$uk,'');
		if($result === false){
			return ['status' => 'error', 'message' => 'Tidak Terhubung ke Server !!'];
		}else{
			$res = $result['metaData']->code;
			if($res==200 || $res==1){
				return [
					'metaData' => $result['metaData'],
					'response' => $result['response'],
				];
			}else{
				return [
					'metaData' => $result['metaData']
				];
			}
		}
	}

	public function refDokter(){
		$url       = $this->url."ref/dokter";
		$consID    = $this->consid; // customer ID RS
		$secretKey = $this->secretkey; // secretKey RS
		$uk        = $this->userkey;
		$method    = 'GET';
		$result    = Requestor::setCurlBridg($url,$method,$consID,$secretKey,$uk,'');
		if($result === false){
			return ['status' => 'error', 'message' => 'Tidak Terhubung ke Server !!'];
		}else{
			$res = $result['metaData']->code;
			if($res==200 || $res==1){
				return [
					'metaData' => $result['metaData'],
					'response' => $result['response'],
				];
			}else{
				return [
					'metaData' => $result['metaData']
				];
			}
		}
	}

	public function refJadDok(Request $request){
		$kode = strtoupper($request->kodePoli); // Kode Poli BPJS
		$tanggal = date("Y-m-d",strtotime($request->tanggal)); // Tanggal Jadwal Dokter

		$url       = $this->url."jadwaldokter/kodepoli/$kode/tanggal/$tanggal";
		$consID    = $this->consid; // customer ID RS
		$secretKey = $this->secretkey; // secretKey RS
		$uk        = $this->userkey;
		$method    = 'GET';
		$result    = Requestor::setCurlBridg($url,$method,$consID,$secretKey,$uk,'');
		if($result === false){
			return ['status' => 'error', 'message' => 'Tidak Dapat Terhubung ke Server.'];
		}else{
			if(!empty($kode) && !empty($tanggal)){
				$res = $result['metaData']->code;
				if($res==200 || $res==1){
					return [
						'metaData' => $result['metaData'],
						'response' => $result['response'],
					];
				}else{
					return [
						'metaData' => $result['metaData']
					];
				}
			}else{
				$msg = $kode?"Tanggal tidak boleh kosong.":"Kode Poli tidak boleh kosong.";
				return [
					'metaData' => [
						'code' => 201,
						'message' => $msg
					]
				];
			}
		}
	}

	public function updateJadDok(Request $request){
		$data    = '';
		$status  = $request->status;
		$dokter  = $request->dokter;
		$poli    = '';
		$subPoli = '';
		$input   = [];
		if(isset($request->status)){ // dari website
			$expPol   = explode(",", $request->poli);
			$poli     = $expPol[0];
			$subPoli  = $expPol[1];
			$senBuka  = $request->buka1;
			$senTutup = $request->tutup1;
			$selBuka  = $request->buka2;
			$selTutup = $request->tutup2;
			$rabBuka  = $request->buka3;
			$rabTutup = $request->tutup3;
			$kamBuka  = $request->buka4;
			$kamTutup = $request->tutup4;
			$jumBuka  = $request->buka5;
			$jumTutup = $request->tutup5;
			$sabBuka  = $request->buka6;
			$sabTutup = $request->tutup6;
			$minBuka  = $request->buka7;
			$minTutup = $request->tutup7;
			$libBuka  = $request->buka8;
			$libTutup = $request->tutup8;
			$day = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu','Libur Nasional'];
			$res = [];
			$arr = [];
			$input['kodeDokter'] = $dokter;
			$input['kodeBridgPoli'] = $poli;
			$input['kodeBridgSubPoli'] = $subPoli;
			if(isset($request->hariOn1)){
				if(!empty($senBuka) && !empty($senTutup)){
					$h = explode(",",$request->hariOn1);
					if($senBuka<$senTutup){
					}else{
						return [
							'metaData' => [
								'code' => 201,
								'message'=>'Jadwal hari : '.$day[$h[0]-1].', jam tutup harus lebih besar dari jam buka.'
							]
						];
					}
					$arr['hari'] = $h[0];
					$arr['buka'] = $senBuka;
					$arr['tutup'] = $senTutup;
					$input['seninBuka'] = $senBuka;
					$input['seninTutup'] = $senTutup;
					array_push($res,$arr);
				}else{
					return [
						'metaData' => [
							'code' => 201,
							'message'=>'jam Buka atau Tutup Tidak Boleh Kosong'
						]
					];
				}
			}
			if(isset($request->hariOn2)){
				if(!empty($selBuka) && !empty($selTutup)){
					$h = explode(",",$request->hariOn2);
					if($selBuka<$selTutup){
					}else{
						return [
							'metaData' => [
								'code' => 201,
								'message'=>'Jadwal hari : '.$day[$h[0]-1].', jam tutup harus lebih besar dari jam buka.'
							]
						];
					}
					$arr['hari'] = $h[0];
					$arr['buka'] = $selBuka;
					$arr['tutup'] = $selTutup;
					$input['selasaBuka'] = $selBuka;
					$input['selasaTutup'] = $selTutup;
					array_push($res,$arr);
				}else{
					return [
						'metaData' => [
							'code' => 201,
							'message'=>'jam Buka atau Tutup Tidak Boleh Kosong'
						]
					];
				}
			}
			if(isset($request->hariOn3)){
				if(!empty($rabBuka) && !empty($rabTutup)){
					$h = explode(",",$request->hariOn3);
					if($rabBuka<$rabTutup){
						$arr['hari'] = $h[0];
						$arr['buka'] = $rabBuka;
						$arr['tutup'] = $rabTutup;
						$input['rabuBuka'] = $rabBuka;
						$input['rabuTutup'] = $rabTutup;
						array_push($res,$arr);
					}else{
						return [
							'metaData' => [
								'code' => 201,
								'message'=>'Jadwal hari : '.$day[$h[0]-1].', jam tutup harus lebih besar dari jam buka.'
							]
						];
					}
				}
				// else if((!empty($rabBuka) && empty($rabTutup)) || (empty($rabBuka) && !empty($rabTutup))){
				// 	$arr['hari'] = $h1[0];
				// 	$arr['buka'] = '-';
				// 	$arr['tutup'] = '-';
				// }
				else{
					return [
						'metaData' => [
							'code' => 201,
							'message'=>'jam Buka atau Tutup Tidak Boleh Kosong'
						]
					];
				}
			}
			if(isset($request->hariOn4)){
				if(!empty($kamBuka) && !empty($kamTutup)){
					$h = explode(",",$request->hariOn4);
					if($kamBuka<$kamTutup){
						$arr['hari'] = $h[0];
						$arr['buka'] = $kamBuka;
						$arr['tutup'] = $kamTutup;
						$input['kamisBuka'] = $kamBuka;
						$input['kamisTutup'] = $kamTutup;
						array_push($res,$arr);
					}else{
						return [
							'metaData' => [
								'code' => 201,
								'message'=>'Jadwal hari : '.$day[$h[0]-1].', jam tutup harus lebih besar dari jam buka.'
							]
						];
					}
				}else{
					return [
						'metaData' => [
							'code' => 201,
							'message'=>'jam Buka atau Tutup Tidak Boleh Kosong'
						]
					];
				}
			}
			if(isset($request->hariOn5)){
				if(!empty($jumBuka) && !empty($jumTutup)){
					$h = explode(",",$request->hariOn5);
					if($jumBuka<$jumTutup){
						$arr['hari'] = $h[0];
						$arr['buka'] = $jumBuka;
						$arr['tutup'] = $jumTutup;
						$input['jumatBuka'] = $jumBuka;
						$input['jumatTutup'] = $jumTutup;
						array_push($res,$arr);
					}else{
						return [
							'metaData' => [
								'code' => 201,
								'message'=>'Jadwal hari : '.$day[$h[0]-1].', jam tutup harus lebih besar dari jam buka.'
							]
						];
					}
				}else{
					return [
						'metaData' => [
							'code' => 201,
							'message'=>'jam Buka atau Tutup Tidak Boleh Kosong'
						]
					];
				}
			}
			if(isset($request->hariOn6)){
				if(!empty($sabBuka) && !empty($sabTutup)){
					$h = explode(",",$request->hariOn6);
					if($sabBuka<$sabTutup){
						$arr['hari'] = $h[0];
						$arr['buka'] = $sabBuka;
						$arr['tutup'] = $sabTutup;
						$input['sabtuBuka'] = $sabBuka;
						$input['sabtuTutup'] = $sabTutup;
						array_push($res,$arr);
					}else{
						return [
							'metaData' => [
								'code' => 201,
								'message'=>'Jadwal hari : '.$day[$h[0]-1].', jam tutup harus lebih besar dari jam buka.'
							]
						];
					}
				}else{
					return [
						'metaData' => [
							'code' => 201,
							'message'=>'jam Buka atau Tutup Tidak Boleh Kosong'
						]
					];
				}
			}
			if(isset($request->hariOn7)){
				if(!empty($minBuka) && !empty($minTutup)){
					$h = explode(",",$request->hariOn7);
					if($minBuka<$minTutup){
						$arr['hari'] = $h[0];
						$arr['buka'] = $minBuka;
						$arr['tutup'] = $minTutup;
						$input['mingguBuka'] = $minBuka;
						$input['mingguTutup'] = $minTutup;
						array_push($res,$arr);
					}else{
						return [
							'metaData' => [
								'code' => 201,
								'message'=>'Jadwal hari : '.$day[$h[0]-1].', jam tutup harus lebih besar dari jam buka.'
							]
						];
					}
				}else{
					return [
						'metaData' => [
							'code' => 201,
							'message'=>'jam Buka atau Tutup Tidak Boleh Kosong'
						]
					];
				}
			}
			if(isset($request->hariOn8)){
				if(!empty($libBuka) && !empty($libTutup)){
					$h = explode(",",$request->hariOn8);
					if($libBuka<$libTutup){
						$arr['hari'] = $h[0];
						$arr['buka'] = $libBuka;
						$arr['tutup'] = $libTutup;
						$input['liburNasionalBuka'] = $libBuka;
						$input['liburNasionalTutup'] = $libTutup;
						array_push($res,$arr);
					}else{
						return [
							'metaData' => [
								'code' => 201,
								'message'=>'Jadwal hari : '.$day[$h[0]-1].', jam tutup harus lebih besar dari jam buka.'
							]
						];
					}
				}else{
					return [
						'metaData' => [
							'code' => 201,
							'message'=>'jam Buka atau Tutup Tidak Boleh Kosong'
						]
					];
				}
			}

			if(count($res)>0){
				$data = [
					'kodepoli' => $poli,
					'kodesubspesialis' => $subPoli,
					'kodedokter' => $dokter,
					'jadwal' => $res
				];
			}else{
				return [
					'metaData' => [
						'code' => 201,
						'message' => 'Jadwal tidak bisa kosong.'
					]
				];
			}
		}else{
			$kode      = strtoupper($request->kodePoli); // Kode Poli BPJS
			$kodeSub   = strtoupper($request->kodeSubSpesialis); // Kode Sub BPJS
			$kodeDok   = $request->kodeDokter;
			$hari      = $request->hari; // 1(senin),2(selasa),3(rabu),4(kamis),5(jumat),6(sabtu),7(minggu),8(hariLiburNasional)
			$buka      = $request->buka;
			$tutup     = $request->tutup;
			$jadwal = [];
			if(!empty($hari)){
				foreach($hari as $key => $val){
					$hari = $val['hari'];
					$buka1 = isset($buka[$key])?$buka[$key]['buka']:null;
					$tutup1 = isset($tutup[$key])?$tutup[$key]['tutup']:null;
					$result = ['hari'=>$hari,'buka'=>$buka1,'tutup'=>$tutup1];
					array_push($jadwal,$result);
				}
			}
			$data = [
				"kodepoli" => $kode,
				"kodesubspesialis" => $kodeSub,
				"kodedokter" => $kodeDok,
				"jadwal" => $jadwal
			];
		}

		$url       = $this->url."jadwaldokter/updatejadwaldokter";
		$consID    = $this->consid; // customer ID RS
		$secretKey = $this->secretkey; // secretKey RS
		$uk        = $this->userkey;
		$method    = 'POST';
		$result    = Requestor::setCurlBridg($url,$method,$consID,$secretKey,$uk,$data);
		if($result === false){
			return ['status' => 'error', 'message' => 'Tidak Dapat Terhubung ke Server.'];
		}else{
			if($result['metaData']=='Gagal terhubung ke API BPJS'){
				$res = [
					'metaData' => [
						'code' => 201,
						'message' => 'Data tidak ditemukan'
					]
				];
			}else{
				$code = $result['metaData']->code;
				if($code==200 || $code==1){
					$arrRes = [];
					$arrRes['metaData'] = $result['metaData'];
					if(array_key_exists('response',$result)){
						$arrRes['response'] = $result['response'];
					}
					$res = $arrRes;
					if($status==200){
						// $updateCekIn = DB::connection('apm')->table('antrian')
						// 	->where('tgl_periksa',$tanggalPeriksa)
						// 	->where('kode_booking',$kodeBooking)->update(['cekin'=>$dateCekIn]);
						// return $dokter;
						$cekJadDok = DB::connection('mysql')->table('jadwal_dokter')
							->where('kodeDokter',$dokter)
							->where('kodeBridgPoli',$poli)
							->where('kodeBridgSubPoli',$subPoli)->first();
						if(!empty($cekJadDok)){
							$resJadDok = DB::connection('mysql')->table('jadwal_dokter')
								->where('kodeDokter',$dokter)
								->where('kodeBridgPoli',$poli)
								->where('kodeBridgSubPoli',$subPoli)->update($input);
						}else{
							$insertJadDok = DB::connection('mysql')->table('jadwal_dokter')->insert($input);
						}
					}else if($status==202){
						$insertJadDok = DB::connection('mysql')->table('jadwal_dokter')->insert($input);
					}
				}else{
					$res = [
						'metaData' => $result['metaData']
					];
				}
			}
			return $res;
		}
	}

	public function antreanAdd(Request $request){
		$mt = explode(' ', microtime());
		$mm = ((int)$mt[1] + (int)round($mt[0])) * 1000;

		$url       = $this->url."antrean/add";
		$consID    = $this->consid; // customer ID RS
		$secretKey = $this->secretkey; // secretKey RS
		$uk        = $this->userkey;
		$method    = 'POST';

		$nomorkartu      = $request->no_bpjs;
		$nik             = ($request->nik=='null')?'':(!empty($request->nik)?$request->nik:'');
		if(!empty($request->nohp)){
			$no_hp = $request->nohp;
		}else{
			$no_hp = '000000000000';
		}
		$nohp            = $no_hp;
		$kodepoli        = strtoupper($request->kodepoli); //kodePoli (P) kapital (untuk getDPJP)
		$pasienbaru      = $request->pasienbaru;
		$norm            = $request->no_rm;
		$tanggalperiksa  = date("Y-m-d",strtotime($request->tglperiksa));
		$kodedokter      = $request->kddokter;
		$jampraktek      = $request->jadwal;
		$jeniskunjungan  = $request->jenis_kunjungan;
		$nomorreferensi  = $request->no_referensi;
		$kuotanonjkn     = '300';
		$kuotajkn        = '300';
		$keterangan      = 'Peserta harap 30 menit lebih awal guna pencatatan administrasi.';

		$jenispasien     = strtoupper($request->jenis_pasien);
		$result = '';
		$split = substr($request->no_antrian,0,1);
		$umur = '';
		$geriatri = '';

		$pasienbaru = '1'; # Pasien baru
		if($request->pasien!='Y'){
			$pasienbaru = '0'; # Pasien lama
		}

		if(!$cekPasien = DB::connection('dbrsud')->table('tm_customer')->where('KodeCust',$norm)->first()){
			if(!$cekPasien = DB::connection('dbrsud')->table('tm_customer')->where('NoKtp',$nik)->first()){
				$cekPasien = DB::connection('dbrsud')->table('tm_customer')
					->where('FieldCust1',$nomorkartu)
					->first();
			}
		}
		$poli = DB::connection('dbrsud')->table('mapping_poli_bridging as m')->join('tm_poli as p','m.kdpoli_rs','=','p.KodePoli')->where('m.kdpoli',$kodepoli)->first();
		$namapoli = $poli->NamaPoli;

		if(date('D',strtotime($tanggalperiksa))=='Sun'){
			return [
				'metaData'=>(object)['code'=>'201', 'message' => 'Hari ini adalah hari Minggu'],
				'response'=>''
			];
		}
		if(!$poli){
			return [
				'metaData'=>(object)['code'=>'201', 'message' => 'Poli tidak terdaftar'],
				'response'=>''
			];
		}
		if(!$kodedokter){
			return [
				'metaData' => (object)[
					'code'=>'201',
					'message'=>'Anda belum melengkapi data pendaftaran (jadwal dokter)'
				],
				'response'=>''
			];
		}
		if(!$dokter = JadwalDokterInternal::where('date',$tanggalperiksa)->where('kode_dokter',$kodedokter)->first()){
			return [
				'metaData'=>(object)['code'=>'201', 'message' => 'Dokter tidak terdaftar'],
				'response'=>''
			];
		}
		// if(!$dokter = DB::connection('dbrsud')->table('dokter_bridg')->where('kodedokter',$kodedokter)->first()){
		// 	return [
		// 		'metaData'=>(object)['code'=>'201', 'message' => 'Dokter tidak terdaftar'],
		// 		'response'=>''
		// 	];
		// }
		$namadokter = $dokter->nama_dokter;
		if ($jenispasien=='BPJS') {
			if($nomorkartu && strlen($nomorkartu)!=13){
				return [
					'metaData'=>(object)[
						'code'=>'201',
						'message' => 'Nomor BPJS tidak sesuai standar 13 digit'
					],
					'response'=>''
				];
			}
		}
		// else{
			// if($split=='B'){
			// 	$jenispasien = $jenispasien;
			// }else{
			// 	$jenispasien = $jenispasien;
			// }
		// }

		date_default_timezone_set("Asia/Jakarta");
		if($nik){
			$cekPasien = DB::connection('dbrsud')->table('tm_customer')
				->where('NoKtp',$nik)->first();
		}else{
			$cekPasien = DB::connection('dbrsud')->table('tm_customer')
				->where('KodeCust',$norm)->first();
		}

		if($cekPasien){
			if($jenispasien=='UMUM'||$jenispasien=='ASURANSILAIN'){
				$nomorkartu = "";
			}

			if($cekPasien->NoKtp!=""){
				$nik = $cekPasien->NoKtp;
			}
			$statusPasien = 'lama';
			// $na = 'L';
			if(!empty($cekPasien->TglLahir)){
				$lahir = new DateTime($cekPasien->TglLahir);
				$diffCur = new DateTime();
				$umur = $diffCur->diff($lahir);
				if($umur->y>=60){
					$geriatri = 'Y';
				}else{
					$geriatri = 'N';
				}
			}else{
				$geriatri = 'N';
			}
		}else{
			if($jenispasien=='BPJS'){
				$nomorkartu = $nomorkartu;
			}else{
				$nomorkartu = "";
			}
			$statusPasien = 'baru';
			// $na = 'B';
		}

		$jkn = $this->kuotaJKN($tanggalperiksa);
		$JKN = $jkn['JKN'];
		$nonJKN = $jkn['nonJKN'];

		$timeCur = date("H");
		$timeCurMinute = date("H:i");
		$dateCur = date("Y-m-d");
		$getDtName = date("D");
		$senJum = $getDtName!="Sat" && $timeCurMinute<"23:00";
		$sabtu = $getDtName=="Sat" && $timeCur<15;
		// $sabtu = $getDtName=="Sat" && $timeCur<17;
		if(($tanggalperiksa==$dateCur && (($senJum) || ($sabtu))) || ($tanggalperiksa>$dateCur)){
			$cekAntrian = '';
			$cekAntrianNIK = DB::connection('mysql')->table('antrian')
				->where('tgl_periksa',$tanggalperiksa)
				->where('nik',$nik)
				->first();
			if (empty($cekAntrianNIK)) {
				if($norm=='00000000000'){
					$cekAntrian = "";
				}else{
					$cekAntrianNoRM = DB::connection('mysql')->table('antrian')
						->where('tgl_periksa',$tanggalperiksa)
						->where('no_rm',$norm)
						->first();
					$cekAntrian = ""; # Initial null value
					if ($cekAntrianNoRM) {
						$cekAntrian = $cekAntrianNoRM;
					}
				}
			}else{
				$cekAntrian = $cekAntrianNIK;
			}
			// if(!empty($cekAntrian) && ($cekAntrian->nik==$nik)){
			// }else{
			if($dokter->kode_poli_bpjs=='GIZ'){
				$request->kodepoli = 'UMU';
			}else if($dokter->kode_poli_bpjs=='040'){
				$request->kodepoli = 'ANA';
			}else if($dokter->kode_poli_bpjs=='017'){
				$request->kodepoli = 'BED';
			}else{
				$request->kodepoli = $dokter->kode_poli_bpjs;
			}
			$jadwalDokter = $this->getDPJP($request);
			$arrJad = [];
			if(isset($jadwalDokter['response'])){
				foreach($jadwalDokter['response'] as $key => $val){
					if($val->kodedokter == $kodedokter){
						$arrJad['time'] = $val;
					}else{
						$arrJad['noData'] = "";
					}
				}
			}else{
				return [
					'metaData'=>(object)[
						'code'=>'201',
						'message' => 'Jadwal Dokter '.$dokter->nama_dokter.' Tersebut Belum Tersedia, Silahkan Reschedule Tanggal dan Jam Praktek Lainnya'
					],
					'response'=>''
				];
			}
			if(array_key_exists("time", $arrJad)){
				date_default_timezone_set("Asia/Jakarta");
				$expJadwalDokter = explode("-",$arrJad['time']->jadwal);
				$expJamPraktek = explode("-",$jampraktek);
				$timeCurPoli = date("H:i",strtotime("14:00"));
				$fTimePra = $expJamPraktek[0];
				$eTimePra = $expJamPraktek[1];
				$fTimeDok = $expJadwalDokter[0];
				$eTimeDok = $expJadwalDokter[1];
				if($eTimePra<$eTimeDok || $fTimePra<$eTimeDok){
					$na = substr($request->no_antrian,0,-3);
					$antri = DB::connection('mysql')->table('antrian')->select('no_antrian')
						->where('tgl_periksa',$tanggalperiksa)->where('no_antrian','like',"$na%")
						->orderBy('no_antrian','desc')->first();
					$num = 0;
					if(!empty($request)){
						$num = (int)substr($request->no_antrian, -3);
					}
					$angkaAntri = sprintf("%03d",$num+0);
					$nextAntri = "$na".$angkaAntri;
					$kodeBooking = $request->kodebooking;
					$kodebooking     = $kodeBooking;
					$nomorantrean    = $nextAntri;
					$angkaantrean    = $angkaAntri;
					$sisakuotajkn    = (300-$JKN)-($jenispasien=='BPJS'?1:0);
					$sisakuotanonjkn = (300-$nonJKN)-($jenispasien=='UMUM'?1:0);
					$dataBPJS = [
						'kodebooking'      => $kodeBooking,
						'jenispasien'      => ($jenispasien=='BPJS'?"JKN":"NON JKN"),
						'nomorkartu'       => $nomorkartu,
						'nik'              => $nik,
						'nohp'             => $nohp,
						// 'kodepoli'         => strtoupper($kodepoli),
						'kodepoli'         => strtoupper($dokter->kode_poli_bpjs),
						'namapoli'         => $namapoli,
						// 'pasienbaru'       => ($na=='B'?1:0),
						'pasienbaru'       => $pasienbaru,
						'norm'             => $norm,
						'tanggalperiksa'   => $tanggalperiksa,
						'kodedokter'       => $kodedokter,
						'namadokter'       => $namadokter,
						'jampraktek'       => $jampraktek,
						'jeniskunjungan'   => ($jenispasien=='BPJS'?$jeniskunjungan:"2"),
						'nomorreferensi'   => ($jenispasien=='BPJS'?$nomorreferensi:""),
						'nomorantrean'     => $nextAntri,
						'angkaantrean'     => $angkaAntri,
						'estimasidilayani' => $mm,
						'sisakuotajkn'     => $sisakuotajkn,
						'kuotajkn'         => $kuotajkn,
						'sisakuotanonjkn'  => $sisakuotanonjkn,
						'kuotanonjkn'      => $kuotanonjkn,
						'keterangan'       => $keterangan,
					];
					$result = Requestor::setCurlBridg($url,$method,$consID,$secretKey,$uk,$dataBPJS,'addantrian');
					if ($result) {
						$res = $result['metaData']->code;
						if($res==200 || $res==1){
							// if($na=='B'){
							// 	$request->taskid = '1';
							// }else{
							// 	if($request->metodes=="KIOSK"){
							// 		$request->taskid = '3'; # Dulu pakai 1
							// 	}else{
							// 		$request->taskid = '3';
							// 	}
							// }

                     ### 03-10-2024 end
							// if($pasienbaru=='1'){ # Pasien baru
							// 	$request->taskid = '1';
							// }else{ # Pasien lama
							// 	$request->taskid = '3';
							// }
							// $request->kodebooking = $kodeBooking;
							// date_default_timezone_set("Asia/Jakarta");
							// $request->waktu = strtotime(date('Y-m-d H:i:s'))*1000;
							// $updateWaktu = $this->updateWaktu($request);
                     ### 03-10-2024 end
							return [
								'metaData' => $result['metaData'],
							];
						}else{
							$result['MESSAGE'] = 'FAILED TO ADD ANTRIAN';
							Log::info(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
							return [
								'metaData' => $result['metaData']
							];
						}
					}else{
						$result['file'] = 'BridgBpjsController';
						Log::info(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
					}
				}else{
					$arr = (object)['code'=>'201', 'message' => 'Jadwal Dokter '.$dokter->nama_dokter.' Tersebut Belum Tersedia, Silahkan Reschedule Tanggal dan Jam Praktek Lainnya'];
					$data = [
						'metaData'=>$arr,
						'response'=>''
					];
					return $data;
				}
			}else{
				$arr = (object)['code'=>'201', 'message' => 'Jadwal Dokter '.$dokter->nama_dokter.' Tersebut Belum Tersedia, Silahkan Reschedule Tanggal dan Jam Praktek Lainnya'];
				$data = [
					'metaData'=>$arr,
					'response'=>''
				];
				return $data;
			}
			// }
		}else if($tanggalperiksa<$dateCur){
			return [
				'response' => null,
				'metaData' => [
					'message' => 'Tanggal sudah terlewat!',
					'code' => 201
				]
			];
		}else{
			if($getDtName=='Sat'){
				$jam = "12:00";
			}else{
				$jam = "21:00";
			}
			return [
				'metaData'=>(object)[
					'code'=>'201',
					'message' => 'Pendaftaran Ke Poli '.$poli->NamaPoli.' Sudah Tutup Jam '.$jam
				],
				'response'=>''
			];
		}
		if($result === false){
			return ['status' => 'error','message'=>'Tidak Dapat Terhubung ke Server.'];
		}else{
			if(!empty($kode) && !empty($tanggal)){
				$res = $result['metaData']->code;
				if($res==200 || $res==1){
					return [
						'metaData' => $result['metaData'],
						'response' => $result['response'],
					];
				}else{
					return [
						'metaData' => $result['metaData']
					];
				}
			}else{
				$msg = $kode?"Tanggal tidak boleh kosong.":"Kode Poli tidak boleh kosong.";
				return [
					'metaData' => [
						'code' => 201,
						'message' => $msg
					]
				];
			}
		}
	}

	function batalAntrean(Request $request){
		$data = [
			"kodebooking"=> $request->kodebooking,
			"keterangan" => $request->keterangan,
		];

		#start local
		// $dataLocal = [
		// 	"kodebooking"=>$request->kodebooking,
		// 	"taskid"=> "99",
		// ];
		// $taskLocal  = new TaskIdController;
		// $saveTaskId = $taskLocal->store($dataLocal); # Simpan task id ke lokal
		#end local

		$url = $this->url."antrean/batal"; //url web dev service bpjs

		$consID    = $this->consid; // customer ID RS
		$secretKey = $this->secretkey; // secretKey RS
		$uk        = $this->userkey;
		$method    = 'POST';
		$result    = Requestor::setCurlBridg($url,$method,$consID,$secretKey,$uk,$data);
		if($result === false){
			echo "Tidak dapat menyambung ke server";
		}else{
			$res = $result['metaData']->code;
			if($res==200 || $res==1){
				$request->taskid = '99';
				date_default_timezone_set('Asia/Jakarta');
				$request->waktu = strtotime('Y-m-d H:i:s');
				$this->updateWaktu($request);
				return [
					'metaData' => $result['metaData'],
				];
			}else{
				return [
					'metaData' => $result['metaData']
				];
			}
		}
	}

	function updateWaktu(Request $request){
		date_default_timezone_set('Asia/Jakarta');
		// $request->waktu = strtotime(date('Y-m-d H:i:s'))*1000;
		$request->request->add([
			'waktu' => strtotime(date('Y-m-d H:i:s'))*1000
		]);
		$data = [
			"kodebooking"=>$request->kodebooking,
			"taskid"=> $request->taskid,
			"waktu"=> $request->waktu,
		];
		$taskLocal  = new TaskIdController;
		$saveTaskId = $taskLocal->store($request); # Simpan task id ke lokal
		$url = $this->url."antrean/updatewaktu"; //url web dev service bpjs

		$consID    = $this->consid; // customer ID RS
		$secretKey = $this->secretkey; // secretKey RS
		$uk        = $this->userkey;
		$method    = 'POST';
		$result    = Requestor::setCurlBridg($url,$method,$consID,$secretKey,$uk,$data);
		if($result === false){
			echo "Tidak dapat menyambung ke server";
		}else{
			$res = $result['metaData']->code;
			if($res==200 || $res==1){
				return [
					'metaData' => $result['metaData'],
					// 'response' => $result['response'],
				];
			}else{
				return [
					'metaData' => $result['metaData']
				];
			}
		}
	}

	function getListTask(Request $request){
		$data = [
			"kodebooking"=>$request->kodebooking
		];
		$url = $this->url."antrean/getlisttask"; //url web dev service bpjs

		$consID    = $this->consid; // customer ID RS
		$secretKey = $this->secretkey; // secretKey RS
		$uk        = $this->userkey;
		$method    = 'POST';
		$result    = Requestor::setCurlBridg($url,$method,$consID,$secretKey,$uk,$data);
		if ($result === false) {
			echo "Tidak dapat menyambung ke server";
		} else {
			$res = $result['metaData']->code;
			if($res==200 || $res==1){
				return [
				'metaData' => $result['metaData'],
				'response' => $result['response'],
				];
			}else{
				return [
				'metaData' => $result['metaData']
				];
			}
		}
	}

	public function dashboardPerTanggal(Request $request){
		$tanggal = date("Y-m-d",strtotime($request->tanggal)); // Tanggal Jadwal Dokter
		$waktu = $request->waktu; // Tanggal Jadwal Dokter

		$url       = $this->url."dashboard/waktutunggu/tanggal/$tanggal/waktu/$waktu";
		$consID    = $this->consid; // customer ID RS
		$secretKey = $this->secretkey; // secretKey RS
		$uk        = $this->userkey;
		$method    = 'GET';
		$result    = Requestor::setCurlBridg($url,$method,$consID,$secretKey,$uk,'');
		if($result['metaData']=='Gagal terhubung ke API BPJS'){
			return [
				'metaData' => [
					'code' => 404,
					'message' => $result['metaData']
				]
			];
		}else{
			if($result === false){
				return ['status' => 'error', 'message' => 'Tidak Terhubung ke Server !!'];
			}else{
				$res = $result['metaData']->code;
				if($res==200 || $res==1){
					return [
						'metaData' => $result['metaData'],
						'response' => $result['response'],
					];
				}else{
					return [
						'metaData' => $result['metaData']
					];
				}
			}
		}
	}

	public function dashboardPerBulan(Request $request){
		$bulan = $request->bulan;
		$tahun = $request->tahun;
		$waktu = $request->waktu;

		$url       = $this->url."dashboard/waktutunggu/bulan/$bulan/tahun/$tahun/waktu/$waktu";
		$consID    = $this->consid; // customer ID RS
		$secretKey = $this->secretkey; // secretKey RS
		$uk        = $this->userkey;
		$method    = 'GET';
		$result    = Requestor::setCurlBridg($url,$method,$consID,$secretKey,$uk,'');
		if($result['metaData']=='Gagal terhubung ke API BPJS'){
			return [
				'metaData' => [
					'code' => 404,
					'message' => $result['metaData']
				]
			];
		}else{
			if($result === false){
				return ['status' => 'error', 'message' => 'Tidak Terhubung ke Server !!'];
			}else{
				$res = $result['metaData']->code;
				if($res==200 || $res==1){
					return [
						'metaData' => $result['metaData'],
						'response' => $result['response'],
					];
				}else{
					return [
						'metaData' => $result['metaData']
					];
				}
			}
		}
	}
	// BRIDGING END

	function getDPJP(Request $request){
		// $poli='PAR',$berobat='2022-09-26'
		if (!empty($request->kodepoli)) {
			$poli = strtoupper($request->kodepoli);
		$berobat = date("Y-m-d",strtotime($request->tglperiksa));
		}else{
		$poli = strtoupper($request->kodePoli);
		$berobat = date("Y-m-d",strtotime($request->tanggal));
		}
		date_default_timezone_set('Asia/Jakarta');
		// $url = $this->url."ref/dokter"; // GET DOKTER
		$url       = $this->url."jadwaldokter/kodepoli/$poli/tanggal/$berobat"; // GET JADWAL DOKTER
		// $url       = $this->url."jadwaldokter/kodepoli/ANA/tanggal/2022-09-19"; // GET JADWAL DOKTER
		$consID    = $this->consid; // customer ID RS
		$secretKey = $this->secretkey; // secretKey RS
		$uk        = $this->userkey;
		$method    = 'GET';
		$result    = Requestor::setCurlBridg($url, $method, $consID, $secretKey, $uk,'');

		if($result['metaData']=='Gagal terhubung ke API BPJS'){
			return [
				'metaData' => [
					'code' => 201,
					'message' => 'Data tidak ditemukan'
				]
			];
		}else{
			if ($result === false) {
				return ['status' => 'error', 'message' => 'Tidak Terhubung ke Server !!'];
			} else {
				if($result['metaData']->code==200){
					return [
						'metaData' => $result['metaData'],
						'response' => $result['response'],
					];
				}else{
					return [
						'metaData' => $result['metaData']
					];
				}
			}
		}
	}

	function kuotaJKN($tanggalperiksa){
		// $kuotaJKN = DB::connection('apm')->table('antrian')
		// 	->where('tgl_periksa',$tanggalperiksa)
		// 	->where('jenis_pasien','BPJS')
		// 	->count();
		$JKN = DB::connection('mysql')->table('antrian')
			->where('tgl_periksa',$tanggalperiksa)
			// ->where('status','belum')
			->where('jenis_pasien','BPJS')
			->count();
		// $kuotaNonJKN = DB::connection('apm')->table('antrian')
		// 	->where('tgl_periksa',$tanggalperiksa)
		// 	->where('jenis_pasien','UMUM')
		// 	->count();
		$nonJKN = DB::connection('mysql')->table('antrian')
			->where('tgl_periksa',$tanggalperiksa)
			// ->where('status','belum')
			->where('jenis_pasien','UMUM')
			->count();
		return [
			// 'JKN' => $kuotaJKN,
			'JKN' => $JKN,
			// 'nonJKN' => $kuotaNonJKN,
			'nonJKN' => $nonJKN,
		];
	}
}
