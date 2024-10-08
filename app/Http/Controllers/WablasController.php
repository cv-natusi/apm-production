<?php

namespace App\Http\Controllers;

### Custom Library
use App\Http\Libraries\GuzzleClient;
# Library / package
use App\Http\Controllers\Controller;
use App\Http\Requests;
use DB,CLog;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
# Traits
use App\Traits\KonfirmasiAntrianTraits;
# Helpers
use App\Helpers\apm as Help;
use App\Http\Libraries\Requestor;
# Models
use App\Http\Models\Antrian;
use App\Http\Models\JadwalDokterInternal;
use App\Http\Models\MstKonterPoli;
use App\Http\Models\Rsu_Bridgingpoli;
use App\Http\Models\Rsu_Register;

class WablasController extends Controller{
	use KonfirmasiAntrianTraits;
	private static $path = 'App/Http/Controllers/WablasController.php';
	private static $logPathError = 'ws-bpjs/antrian-add/error.log';

	public function __construct(){
		date_default_timezone_set("Asia/Jakarta");
		$this->url = "https://apijkn.bpjs-kesehatan.go.id/vclaim-rest";
		$this->consid = env('CONS_ID');
		$this->secretkey = env('SECRET_KEY');
		$this->userkey = '2079632035f01e757d81a8565b074768';
	}

	public function updateCustomer(){
		// $url = $this->url."/peserta/nokartu/0000719925456/tglsep/".date('Y-m-d');
		$consID     = $this->consid; //customer ID RS
		$secretKey  = $this->secretkey; //secretKey RS
		$uk         = $this->userkey;
		$method = 'GET';

		$cek = DB::connection('mysql')->table('rm_cust')->select('KodeCust')->get();
		if(count($cek)>0){
			$arr = [];
			foreach($cek as $k => $v){
				array_push($arr, $v->KodeCust);
			}
			$get = DB::connection('dbrsud')->table('tm_customer')
				// ->where('NoKtp',null)
				->where('FieldCust1',"")
				->whereNotIn('KodeCust',$arr)
				->orderBy('FieldCust1','DESC')
				->limit(10000)->get();
		}else{
			$get = DB::connection('dbrsud')->table('tm_customer')
				// ->where('NoKtp',null)
				->where('FieldCust1',"")
				// ->orderBy('FieldCust1','DESC')
				->orderBy('NoKtp','DESC')
				->limit(10000)->get();
		}
		// return $get;die();
		$arr = [];
		foreach ($get as $key => $val){
			$up = DB::connection('dbrsud')->table('tm_customer')
				->where('KodeCust',$val->KodeCust)
				// ->update(['NoKtp'=>NULL]);
				->update(['FieldCust1'=>NULL]);
			$pus = DB::connection('dbrsud')->table('tm_customer')
				->where('KodeCust',$val->KodeCust)
				->first();
			array_push($arr,$pus);
			$in = DB::connection('mysql')->table('rm_cust')->insert(['KodeCust'=>$val->KodeCust]);
		}
		return $arr;
	}

	public function index(){
		return view('Admin.wablas.main');
	}

	public function verifikasi(Request $request){
		$request->id  = $request->id;
		$random  = $request->random;
		$dateCur = date("Y-m-d");
		$timeCur = date("H");
		$timeCur2 = date("H:i");
		$jamBuka = '06:30';
		$dataBot = DB::connection('mysql')->table('bot_pasien')
			->where([
				'id'          =>$request->id,
				'random'      =>$random,
				'status_akun' =>true,
				'statusChat'  =>99
			])->first();
		// $verif = DB::connection('mysql')->table('bot_pasien')
		// 	->where([
		// 		'id'          =>$request->id,
		// 		'random'      =>$random,
		// 		'status_akun' =>false,
		// 		'statusChat'  =>99
		// 	])->first();

		$dataPasien = DB::connection('mysql')->table('bot_data_pasien')
			->where([
				'idBots'      => $request->id,
				// 'tglBerobat'  => $dateCur
			])->first();

		if(!empty($dataBot)){
			$tglBerobat = $dataBot->tgl_periksa;
			$cekPas = $dataBot->pasien_baru;
			$data['phone'] = $dataBot->phone;
			$bool = $timeCur2<$jamBuka && $tglBerobat==$dateCur;
			// if($dataPasien->kodePoli=='017' && $tglBerobat=='2024-08-05'){
			// 	$txt = "<p style='text-align:center; font-weight:600; font-size:2.5rem; margin-top:4%;'>MOHON MAAF, KUOTA POLI ONKOLOGI SAAT INI SUDAH PENUH</p>";
			// 	return $txt;
			// }
			if($tglBerobat > $dateCur || $bool){
				$data['pesan1'] = "Mohon Maaf URL Belum Aktif!";
				$data['pesan2'] = $bool ? "URL akan aktif pada jam $jamBuka WIB" : "URL hanya berlaku pada tanggal yang didaftarkan!";
				$data['tgl'] = date_format(date_create($tglBerobat),"d-m-Y");
				$data['status'] = "belum";
				return view('verifikasi.main',$data);
			}else if($tglBerobat==$dateCur){
				if($timeCur < 21){
					if(!empty($dataPasien)){
						$kodePoli = $dataPasien->kodePoli;
						$poli = $this->getPoli($kodePoli);

						$data['poli'] = $poli;
						$data['data'] = $dataBot;
						$data['unik'] = $random;
						$data['pesan1'] = "Verifikasi Pendaftaran Pasien";
						$data['pesan2'] = "Nomor Unik";
						$data['status'] = "berhasil";
						return view('verifikasi.main',$data);
					}else{
						$data['pesan1'] = "Mohon Maaf URL Tidak Aktif!";
						$data['pesan2'] = "Konfirmasi Sudah Melawati Tanggal yang Didaftarkan";
						$data['pesan3'] = $this->msgPasien($cekPas);

						$data['tgl'] = date_format(date_create($tglBerobat),"d-m-Y");
						$data['status'] = "lewat";

						$updateBot = DB::connection('mysql')->table('bot_pasien')
							->where([
								'id'=>$request->id,
								'random' => $random,
							])->update(['status_akun'=>0,'konfirmasi'=>'lewat']);
						return view('verifikasi.main',$data);
					}
				}else{
					$data['pesan1'] = "Mohon Maaf URL Tidak Aktif!";
					$data['pesan2'] = "Konfirmasi Sudah Melawati Jam Operasional!";
					$data['pesan3'] = $this->msgPasien($cekPas);

					$data['tgl'] = date_format(date_create($tglBerobat),"d-m-Y");
					$data['status'] = "lewat";
					$updateBot = DB::connection('mysql')->table('bot_pasien')
						->where([
							'id'=>$request->id,
							'random' => $random
						])->update(['status_akun'=>0,'konfirmasi'=>'lewat']);
					return view('verifikasi.main',$data);
				}
			}else if($tglBerobat < $dateCur){
				$data['pesan1'] = "Mohon Maaf URL Tidak Aktif!";
				$data['pesan2'] = "Konfirmasi Sudah Melawati Tanggal yang Didaftarkan";
				$data['pesan3'] = $this->msgPasien($cekPas);

				$data['tgl'] = date_format(date_create($tglBerobat),"d-m-Y");
				$data['status'] = "lewat";
				$updateBot = DB::connection('mysql')->table('bot_pasien')
					->where([
						'id'=>$request->id,
						'random' => $random
					])->update(['status_akun'=>0,'konfirmasi'=>'lewat']);
				return view('verifikasi.main',$data);
			}
		}else{
			// if(!empty($verif)){
			if(
				$verif = DB::connection('mysql')->table('bot_pasien')
				->where([
					'id' => $request->id,
					'random' => $random,
					'status_akun' => false,
					'statusChat' => 99
				])->first()
			){
				if($verif->konfirmasi=='berhasil'){
					$updateBot = DB::connection('mysql')->table('bot_pasien')
						->where([
							'id'=>$request->id,
							'random' => $random
						])->update(['konfirmasi'=>'verified']);
					return "<p style='text-align:center; font-weight:600; font-size:4rem; margin-top:4%;'>Verifikasi Berhasil Silahkan Cek WhatsApp Anda!</p>";
				}else{
					if($verif->konfirmasi=='verified'){
						// return response()->json($dataPasien);
						$antrian = Antrian::where([
							'nik'=>$dataPasien->nik,
							'tgl_periksa'=>date('Y-m-d')
						])->first();
						if(!empty($antrian)){
							// $nomorAntri = ($antrian->metode_ambil=='WA' && $antrian->is_pasien_baru=='N') ? $antrian->nomor_antrian_poli : $antrian->no_antrian;
							$nomorAntri = (!empty($antrian->nomor_antrian_poli)) ? $antrian->nomor_antrian_poli : ($antrian->is_pasien_baru=='Y'?$antrian->no_antrian_pbaru:$antrian->no_antrian);
							$poli = $this->getPoli($antrian->kode_poli)->NamaPoli;
						}else{
							$nomorAntri = '-';
							$poli = '-';
						}
						$txt = "<p style='text-align:center; font-weight:600; font-size:2.5rem; margin-top:4%;'>DATA SUDAH PERNAH DIVERIFIKASI DENGAN NOMOR ANTRIAN : $nomorAntri</p>";
						$txt .= "<p style='text-align:center; font-weight:600; font-size:2.5rem;'>TUJUAN : $poli</p>";
						return $txt;
					}else{
						return "<p style='text-align:center; font-weight:600; font-size:4rem; margin-top:4%;'>URL Tidak Valid!</p>";
					}
				}
			}else{
				return "<p style='text-align:center; font-weight:600; font-size:4rem; margin-top:4%;'>URL Tidak Valid!</p>";
			}
		}
	}

	function msgPasien($cekPas){
		if($cekPas==1){
			$msg = "Silahkan Melakukan Pendaftaran Ulang Melalui WhatsApp<br><b>Sebagai Pasien Baru<b>!";
		}else{
			$msg = "Silahkan Melakukan Pendaftaran Ulang Melalui WhatsApp<br><b>Sebagai Pasien Lama<b>!";
		}
		return $msg;
	}
	
	function getPoli($kodePoli){
		$poli = Rsu_Bridgingpoli::join('tm_poli', 'mapping_poli_bridging.kdpoli_rs', '=', 'tm_poli.KodePoli')
			->where('kdpoli',$kodePoli)
			->groupBy('mapping_poli_bridging.kdpoli_rs') 
			->first();
		return $poli;
	}

	public function verifBerhasil(Request $request){
		$logPayload = [
			'log_path'=>self::$logPathError,
			'url'=>$request->getRequestUri(),
			'file'=>self::$path,
			'method'=>'verifBerhasil()',
			'data'=>$request->all(),
		];
		$random  = $request->random;
		$phone   = $request->phone;
		$dateCur = date('Y-m-d');

		$dataBot = DB::connection('mysql')->table('bot_pasien')
			->where([
				'id'          => $request->id,
				'random'      => $random,
				'status_akun' => true
			])->first();

		$send['phone'] = $phone;
		$send['botPas'] = $dataBot;
		$ceked = '';

		$respon = '';
		if(!empty($dataBot)){
			$cekPas = $dataBot->pasien_baru;
			$send['cekPas'] = $cekPas;
			if($dataPas = DB::connection('mysql')->table('bot_data_pasien')->where('idBots',$request->id)->first()){
				$kodePoli = $dataPas->kodePoli;
				$poli = $this->getPoli($kodePoli);
				$send['poli'] = !empty($poli)?$poli->NamaPoli:'-';

				if($mst = Rsu_Bridgingpoli::with('tm_poli.trans_konter_poli.mst_konterpoli')->where('kdpoli',$kodePoli)->first()){
					if(isset($mst->tm_poli->trans_konter_poli) && isset($mst->tm_poli->trans_konter_poli->mst_konterpoli)){
						$prefix = ($cekPas==1)?"Y":"N";
						$na = $dataPas->caraBayar=='BPJS'?'B':'NB';
						$antri = Antrian::select('no_antrian')
							->where('tgl_periksa',$dateCur)->where('no_antrian','like',"$na%")
							->orderBy('no_antrian','desc')->first();
						$num = 0;
						if(!empty($antri)){
							$num = (int)substr($antri->no_antrian, -3);
						}
						$angkaAntri = sprintf("%03d",$num+1);
						$nextAntri = "$na".$angkaAntri;
						$kodeBooking = date('dmy').$nextAntri;

						# generate no antrian pasien baru
						$request->tglperiksa   = $dataPas->tglBerobat;
						$request->jenis_pasien = $dataPas->caraBayar;
						$noPasBaru = '';
						if($prefix == "Y") {
							$noPasBaru = $this->generateNoAntrianBaru($request);
						}
						# pengecekan/ubah jenis kunjungan berdasarkan prefix, datanya dari $dataPas->nomor_referensi
						// $query = "SELECT id,nomor_referensi FROM antrian WHERE nomor_referensi='$nomorRujukan' LIMIT 1";
						// $antrianByRujukan = mysqli_query($natusiApm,$query);
						// $nums = mysqli_num_rows($antrianByRujukan);
						$noRujuk = $dataPas->nomor_referensi;
						$rujukanDipakai = Antrian::select('nomor_referensi')->where('nomor_referensi',$noRujuk)->first();
						$prefixRujuk = substr($noRujuk,12,1);
						$jenis_kunjungan = '2'; # Rujukan internal
						if(!$rujukanDipakai){ # Rujukan pertama kali berkunjung, jika tidak default "2"
							if(in_array($prefixRujuk, ['P','Y','U','G'])){ # Rujukan FKTP Tingkat 1 (puskesmas,klinik)
								$jenis_kunjungan = '1';
							}
							if($prefixRujuk=='B'){ # Rujukan FKTP Tingkat 2 (RS)
								$jenis_kunjungan = '4';
							}
							if($prefixRujuk=='K'){ # Kontrol
								$jenis_kunjungan = '3';
							}
						}

						$forAntrianTB = [
							'nik'             => $dataPas->nik,
							'kode_poli'       => $dataPas->kodePoli,
							'no_antrian'      => $nextAntri,
							'no_antrian_pbaru'=> ($prefix == 'Y') ? $noPasBaru : null,
							'kode_booking'    => $kodeBooking,
							'jenis_pasien'    => $dataPas->caraBayar,
							'tgl_periksa'     => $dataPas->tglBerobat,
							'is_geriatri'     => $dataPas->isGeriatri,
							'is_pasien_baru'  => $prefix,
							'metode_ambil'    => 'WA',
							'nohp'            => $dataPas->nohp,
							'kode_dokter'     => $dataPas->kodedokter,
							'jam_praktek'     => $dataPas->jam_praktek,
							// 'jenis_kunjungan' => $dataPas->jenis_kunjungan,
							'jenis_kunjungan' => $jenis_kunjungan,
							'link_wa'         => 'YA',
						];

						$dataAntriBpjs = [
							"kodebooking"      => $kodeBooking,
							"jenis_pasien"     => $dataPas->caraBayar,
							"nik"              => $dataPas->nik,
							"nohp"             => $dataPas->nohp,
							"kodepoli"         => $dataPas->kodePoli,
							"namapoli"         => $send['poli'],
							"no_rm"            => ($cekPas==true)?'00000000000':$dataPas->KodeCust,
							"tglperiksa"       => $dataPas->tglBerobat,
							"kddokter"         => $dataPas->kodedokter,
							"jadwal"           => $dataPas->jam_praktek,
							// "jenis_kunjungan"  => $dataPas->jenis_kunjungan,
							"jenis_kunjungan"  => $jenis_kunjungan,
							"no_antrian"       => $nextAntri,
							"metodes"         => 'WA'
						];

						if($cekPas==true){ # Pasien baru
							$forAntrianTB += [
								'status' => 'belum',
								'no_rm'  => '00000000000'
							];
						}else{ # Pasien lama
							$forAntrianTB += [
								'status' => 'antripoli',
								'no_rm'  => $dataPas->KodeCust
							];
						}
						if($dataPas->caraBayar=='BPJS'){ # Pasien bpjs
							$forAntrianTB +=[
								'nomor_kartu'     => $dataPas->nomor_kartu,
								'nomor_referensi' => $dataPas->nomor_referensi
							];

							$dataAntriBpjs += [
								"no_bpjs"      => $dataPas->nomor_kartu,
								"no_referensi" => $dataPas->nomor_referensi
							];
						}

						$whereCekAntri = [
							'nik'=>$dataPas->nik,
							'tgl_periksa'=>$dateCur,
						];
						$send['noAntrianPoli'] = ''; # Init index
						if($cekPas==false){
							$whereCekAntri['no_rm'] = $dataPas->KodeCust;
							$nomorRegis = Rsu_Register::where('No_RM',$dataPas->KodeCust)
								->whereDate('Tgl_Register','=',$dataPas->tglBerobat)
								->first();

							if(!empty($nomorRegis)){
								$nomorRegis = $nomorRegis->No_Register;
							}else{
								$nomorRegis = "";
							}

							$noAntrianPoli = $this->generateNoAntrianPoli($dataPas->kodePoli); # Generate no antrian poli
							$send['noAntrianPoli'] = $noAntrianPoli;
							$forAntrianTB += [
								'nomor_antrian_poli' => $noAntrianPoli,
								'No_Register'=>$nomorRegis
							];
						}
						$cekAntri = Antrian::where($whereCekAntri)->first(); # Cek duplikat antrian

						$duplikatWhere = [
							'no_antrian' =>$nextAntri,
							'tgl_periksa'=>$dateCur,
						];

						if(empty($cekAntri)){ # Create data antrian kemudian get (id) nya
							$conMysql = DB::connection('mysql');
							$conDbrsud = DB::connection('dbrsud');
							$conMysql->beginTransaction();
							$conDbrsud->beginTransaction();
							$insertAntrian = $conMysql->table('antrian')->insertGetId($forAntrianTB);

							# Store taskid to local DB srtart
							if(!in_array($dataPas->kodePoli,['ANT','GIG','GIZ','MCU','PSY','VCT'])){
								// $antrian = Antrian::where('id', $insertAntrian)->first();
								$request->merge([
									'payload_guzzle' => [
										'body' => [
											'antrian_id' => $insertAntrian,
											'pasien_baru' => $prefix === 'Y' ? 1 : 0,
											'kode_booking' => $kodeBooking,
											'task_id' => $prefix === 'Y' ? 1 : 3,
											'tanggal_berobat' => date('d-m-Y', strtotime($dataPas->tglBerobat)),
										],
										'method' => 'POST',
										'endpoint' => 'api/antrian/task-id/store',
									],
								]);
	
								$sendRequest = GuzzleClient::sendRequestTaskId($request)->getData();
								if(!in_array($sendRequest->code, [201, 409])){
									Log::error(json_encode([
										'file' => 'app/Http/Controllers/WablasController.php',
										'status' => 'catch_log_guzzle_in_controller',
										'guzzle_result' => $sendRequest,
										'data' => $request->all(),
									], JSON_PRETTY_PRINT));

									$conMysql->rollback();
									$conDbrsud->rollback();
									return ['code' =>  404, 'status' => 'error', 'message' => 'Task Id gagal disimpan, silahkan coba lagi'];
								}
							}
							# Store taskid to local DB end

							if($cekPas==false){ # Pasien lama update filling(antrian_id) & update jam kedaatngan di tr_registrasi
								$getRegis = Rsu_Register::where('No_RM',$dataPas->KodeCust)
									->whereDate('Tgl_Register','=',$dataPas->tglBerobat)
									->update([
										'Tgl_Register'=>date('Y-m-d H:i:s'),
										'Jam_Register'=>date('H:i:s'),
									]);
								$upFilling = DB::connection('mysql')->table('filling')
									->where([
										'no_rm'=>$dataPas->KodeCust,
										'tgl_periksa'=>$dataPas->tglBerobat,
									])->update(['antrian_id'=>$insertAntrian]);
								if(!$getRegis && !$upFilling){
									$conMysql->rollback();
									$conDbrsud->rollback();
									return [
										'code'=> 500,
										'status'=>'error',
										'message'=>'Konfirmasi gagal, silahkan coba lagi'
									];
								}
								$duplikatPoli=Antrian::where([
									'nomor_antrian_poli'=>$noAntrianPoli,
									'tgl_periksa'=>$dateCur,
								])->count();
								if($duplikatPoli > 1){ # Jika ada antrian duplikat, lakukan rollback
									$conMysql->rollback();
									$conDbrsud->rollback();
									return [
										'code'=> 500,
										'status'=>'error',
										'message'=>'Silahkan ulangi konfirmasi'
									];
								}
							}
							$duplikatAdmisi=Antrian::where([
								'no_antrian' =>$nextAntri,
								'tgl_periksa'=>$dateCur,
							])->count();
							if($duplikatAdmisi > 1){ # Jika ada antrian duplikat, lakukan rollback
								$conMysql->rollback();
								$conDbrsud->rollback();
								return [
									'code'=> 500,
									'status'=>'error',
									'message'=>'Silahkan ulangi konfirmasi'
								];
							}


							$cekTracer = DB::connection('mysql')->table('antrian_tracer')->where('antrian_id',$insertAntrian)->count();
							$dataTracer = [
								'antrian_id'    => $insertAntrian,
								'from'          => 'wa',
								'status_tracer' => '1',
								'tgl'           => $dateCur,
								'time'          => date("H:i:s")
							];
							$dataTracer['to'] = ($cekPas==1)?'loket':'poli';

							if($cekTracer==0){ # Insert baru jika tracer kosong
								$insertTracer = DB::connection('mysql')->table('antrian_tracer')->insert($dataTracer);
							}

							$send['mstKonter']    = $mst->tm_poli->trans_konter_poli->mst_konterpoli->nama_konterpoli;
							$send['nomorAntrian'] = ($prefix=='Y')?$noPasBaru:$nextAntri;
							$send['kodeBooking']  = $kodeBooking;

							if(!$dataDokter = JadwalDokterInternal::where(['date'=>date('Y-m-d'),'kode_dokter'=>$dataPas->kodedokter])->first()){
								$conMysql->rollback();
								$conDbrsud->rollback();
								return ['code'=> 404,'status'=>'error','message'=>'Data dokter tidak ditemukan'];
							}
							$ifKode = $dataDokter->kode_poli_bpjs;
							$conMysql->commit();
							$conDbrsud->commit();

							# Poli tidak ter-cover BPJS, tidak perlu hit TaskID ke bpjs
							if(in_array($ifKode,['ANT','GIG','GIZ','MCU','PSY','VCT'])){
								$ceked = 1; # Konfirmasi sukses
							}else{
								$addAntrian = new BridgBpjsController;
								$respon = $addAntrian->antreanAdd(new Request($dataAntriBpjs)); # Send data antrian ke bpjs
								if($respon['metaData']->code == 200){
									$ceked = 1; # Konfirmasi sukses
								}else{
									$logPayload['message'] = $respon['metaData']->message;
									$request->merge(['log_payload'=>$logPayload]);
									CLog::catchError($request);
									if(stripos($respon['metaData']->message,'duplikasi kode')!==false){
										$ceked = 1; # Konfirmasi sukses
									}else{
										return ['code'=> 404,'status'=>'error','message'=>'Gagal Konfirmasi','data'=>$respon];
									}
								}
							}
						}else{
							$updateBot = DB::connection('mysql')->table('bot_pasien')
							->where([
								'id'=>$request->id,
								'random' => $random
							])->update(['status_akun'=>0,'konfirmasi'=>'verified']);
							$noAntri = ($cekPas==false) ? $cekAntri->nomor_antrian_poli : $cekAntri->no_antrian;
							return [
								'code'=>500,
								'status'=>'error',
								// 'message'=>'Anda sudah pernah mengambil nomor antrian menggunakan nik '.$dataPas->nik.' pada hari ini'
								'message'=>"Nik <b>$dataPas->nik</b> sudah mendapat nomor Antrian: <b>$noAntri</b>"
							];
						}
					}
					$respon = $mst->tm_poli->NamaPoli.' belum masuk ke master Counter Poli';
				}
			}
		}

		if($ceked==''){
			return [
				'code'    => 404,
				'message' => 'Konfirmasi gagal',
				'respon'  => $respon
			];
		}else{
			$sendData = $this->sendData((object)$send);
			$updateBot = DB::connection('mysql')->table('bot_pasien')
				->where([
					'id'=>$request->id,
					'random' => $random
				])->update(['status_akun'=>0,'konfirmasi'=>'berhasil']);
			$updateBot = DB::connection('mysql')->table('bot_data_pasien')->where('idBots',$request->id)->update(['masukMaster'=>'sudah']);
			return [
				'code'    => 200,
				'message' => 'Konfirmasi berhasil',
			];
		}
	}

	public function sendData($data){
		// $token       = 'Qwf4jUkeX3h6OwNpWjzsg82stjUYWcx0tsxXc7vfLgva3Iap3nxPzlO0yrfDPGCl'; # Api key dari wablas
		$token       = 'zvwlTMHoLy80JDVpL7WknpLiQy8FHIhnfRBj396Ft8CpXfT5MgP7mljXZHSigasM'; # Api key dari wablas
		$phone       = $data->phone;
		$botPas      = $data->botPas;
		$cekPas      = $data->cekPas;
		$noAntri     = $data->nomorAntrian; # Nomor antrian admisi
		$noPoli      = $data->noAntrianPoli; # Nomor antrian poli
		$poli        = $data->poli;
		$mstKonter   = $data->mstKonter;
		$kodeBooking = $data->kodeBooking;
		// $phone       = '6281335537942';

		$cek = $cekPas==1?'Pendaftaran':'Antrian';
		$nomor = $cekPas==1?$noAntri:$noPoli;

		$msg  = "Selamat konfirmasi berhasil.\n\n";
		$msg .= "Nomor $cek : *$nomor*\n";
		$msg .= "Kode Booking : *$kodeBooking*\n";
		$msg .= "Tujuan : $poli\n\n";
		if($cekPas==1){
			$msg .= "Silahkan Antri di *LOKET PENDAFTARAN* dan Menunggu Panggilan.\n";
		}else{
			$msg .= "Silahkan Antri di *$poli* dan Menunggu Panggilan.\n";
		}
		$msg .= "Terima Kasih!";

		$data = [
			'phone' => $phone,
			'message' => $msg,
		];
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_HTTPHEADER,
			array(
				"Authorization: $token",
			)
		);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($curl, CURLOPT_URL,  "https://kudus.wablas.com/api/send-message");
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		$result = curl_exec($curl);
		curl_close($curl);
	}

	public function sendDataTesting(Request $request){
		// $token = 'Qwf4jUkeX3h6OwNpWjzsg82stjUYWcx0tsxXc7vfLgva3Iap3nxPzlO0yrfDPGCl';
		$token = 'zvwlTMHoLy80JDVpL7WknpLiQy8FHIhnfRBj396Ft8CpXfT5MgP7mljXZHSigasM';
		$data = [
			'phone' => $request->phone,
			'message' => $request->message,
		];
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_HTTPHEADER,
			array(
				"Authorization: $token",
			)
		);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($curl, CURLOPT_URL,  "https://kudus.wablas.com/api/send-message");
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		$result = curl_exec($curl);
		curl_close($curl);
		return response()->json(json_decode($result));
	}

	// public function sendData(Request $request){
		// 	// return $request->all();
		// 	$datas = [];
		// 	$data['phone'] = request('nomor');
		// 	$data['message'] = request('pesan');
		// 	$data['secret'] = false;
		// 	$data['retry'] = false;
		// 	$data['isGroup'] = false;
		// 	array_push($datas,$data);

		// 	$token = env('SECURITY_TOKEN_WABLAS');
		// 	$baseUrl = env('DOMAIN_SERVER_WABLAS');
		// 	// $url = '/api/v2/send-message';
		// 	// $url = '/api/v2/send-list';
		// 	// $url = '/api/v2/send-template';
		// 	$message = [
		// 		'phone' => '085648182003',
		// 		'message'=> [
		// 			'title' => [
		// 				'type' => 'image',
		// 				'content' => 'https://cdn-asset.jawapos.com/wp-content/uploads/2019/01/keluarga-pawang-di-jepang-maafkan-macan-putih-yang-membunuhnya_m_.jpg',
		// 			],
		// 			'buttons' => [
		// 				'url' => [
		// 					'display' => 'wablas.com',
		// 					'link' => 'https://wablas.com',
		// 				],
		// 				'call' => [
		// 					'display' => 'contact us',
		// 					'phone' => '081335537942',
		// 				],
		// 				'quickReply' => ["reply 1","reply 2"],
		// 			],
		// 			'content' => 'sending template message...',
		// 			'footer' => 'footer template here',
		// 		],
		// 	];

		// 	$curl = curl_init();
		// 	$token = env('SECURITY_TOKEN_WABLAS');
		// 	$payload = [
		// 		'data' => [$message]
		// 	];
		// 	curl_setopt(
		// 		$curl, CURLOPT_HTTPHEADER,
		// 		array(
		// 			"Authorization: $token",
		// 			"Content-Type: application/json"
		// 		)
		// 	);
		// 	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		// 	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		// 	curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));
		// 	curl_setopt($curl, CURLOPT_URL, $baseUrl.$url);
		// 	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		// 	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		// 	$result = curl_exec($curl);
		// 	curl_close($curl);
		// 	return $result;
	// }
}