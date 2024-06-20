<?php

namespace App\Http\Controllers;

# Library / package
use App\Http\Controllers\Controller;
use App\Http\Libraries\Requestor;
use App\Http\Libraries\Notifikasi;
use App\Http\Requests;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator, File, DB;
# Models
use App\Http\Models\Apm;
use App\Http\Models\CC;
use App\Http\Models\Customer;
use App\Http\Models\Device;
use App\Http\Models\dokter_bridging;
use App\Http\Models\Holidays;
use App\Http\Models\InformasiPenyakit;
use App\Http\Models\KotakSaran;
use App\Http\Models\PolaHidupSehat;
use App\Http\Models\Poli;
use App\Http\Models\Rsu_cc;
use App\Http\Models\rsu_customer;
use App\Http\Models\Rsu_device;
use App\Http\Models\rsu_dokter_bridging;
use App\Http\Models\rsu_poli;
use App\Http\Models\VersiAndroid;

class APIAPMController extends Controller{
	public function getPoli(Request $request){
		// $poli = Poli::all(); // db lokal
		$poli = rsu_poli::all(); // db rsu
		$return = ['status'=>'success','code'=>'200','message'=>'','data'=>$poli];
		return response()->json($return);
	}

	public function cekIdentitas(Request $request){
		$rules = array(
			// 'no_rm'=>'required',
			'imei'=>'required',
			// 'player_id'=>'required',
		);
		$messages = array(
			'required' => 'Kolom harus di isi',
		);
		$validator = Validator::make($request->all(), $rules,$messages);
		if(!$validator->fails()){
			$new_imei = $request->imei;
			$input_RM = $request->no_rm;
			$playerId = $request->player_id;
			// $cekRM = Customer::where('KodeCust',$input_RM)->first(); // DB lokal
			$cekRM = rsu_customer::where('KodeCust',$input_RM)->orWhere('NoKtp',$input_RM)->first(); // DB rsu
			if (empty($cekRM)) {
				// $cekBPJS = Customer::where('FieldCust1',$input_RM)->first(); // db lokal
				$cekBPJS = rsu_customer::where('FieldCust1',$input_RM)->first(); // db rsu
				if (empty($cekBPJS)) {
					$statusAnggota = 'Tidak';
				}else{
					$kodeCus = $cekBPJS->KodeCust;
					$statusAnggota = 'Terdaftar';
					$customer = $cekBPJS;
				}
			}else{
				$kodeCus = $cekRM->KodeCust;
				$statusAnggota = 'Terdaftar';
				$customer = $cekRM;
			}
			if ($statusAnggota == 'Terdaftar') {
				$cekCustDevice = Device::where('KodeCust',$kodeCus)->first();
				if (!empty($cekCustDevice)) { # Jika Customer Sudah Ada Di Device
					$cekImei = Device::where('imei',$new_imei)->first();
					if (empty($cekImei)) {
						if ($cekCustDevice->imei == '') {
							$stImei = 'Accept';
						}elseif($cekCustDevice->imei == $new_imei){
							$stImei = 'Accept';
						}else{
							$stImei = 'Reject';
							$message = 'Device Tidak Sesuai dengan Akun Anda !!. Akun sudah terdaftar dengan ID Device '.$cekCustDevice->imei;
						}
					}else{
						if ($cekCustDevice->KodeCust == $cekImei->KodeCust) {
							$stImei = 'Accept';
						}else{
							$stImei = 'Reject';
							$message = 'Device Sudah Terdaftar Untuk Akun Lain. dengan No. RM '.$cekImei->KodeCust.'';
						}
					}
					$stDevice = 'Exist';
					$accDevice = $cekCustDevice->accepted;
				}else{ # Jika Customer Tidak Ada di Device
					$cekImei = Device::where('imei',$new_imei)->first();
					if (empty($cekImei)) {
						$stImei = 'Accept';
					}else{
						$stImei = 'Reject';
						$message = 'Device Sudah Terdaftar Untuk Akun Lain. dengan No. RM '.$cekImei->KodeCust.'';
					}
					$stDevice = 'Nothing';
					$accDevice = '0';
				}
				if ($stImei == 'Accept') {
					if ($stDevice == 'Exist') {
						$device = Device::where('KodeCust',$kodeCus)->first();
						if ($accDevice == '1') {
							$device->player_id = $playerId;
							$codeReturn = '200';
							$statusReturn = 'success';
							$messageReturn = 'Identitas Terdaftar';
						}else{
							$codeReturn = '500';
							$statusReturn = 'error';
							$messageReturn = 'Silahkan konfirmasi ke WA admin 081336580009';
						}
					}else{
						$device = new Device;
						$device->KodeCust = $kodeCus;
						$device->accepted = '0';
						
						$codeReturn = '201';
						$statusReturn = 'success';
						$messageReturn = 'Handphone Berhasil Didaftarkan, Silahkan kontak WA admin 081336580009 untuk konfirmasi';
					}
					$device->imei = $new_imei;
					$device->save();
					// $return = ['status' => 'success', 'code'=>'200', 'message'=>'Identitas Terdaftar','KodeCust'=>$kodeCus, 'customer'=>$customer];
					if ($codeReturn == '200') {
						$return = ['status' => $statusReturn, 'code'=>$codeReturn, 'message'=>$messageReturn,'KodeCust'=>$kodeCus, 'customer'=>$customer];
					}else{
						$return = ['status' => $statusReturn, 'code'=>$codeReturn, 'message'=>$messageReturn];
					}
				}else{
					$return = ['status' => 'error', 'code'=>'500', 'message' => $message];
				}
				// berhasil
			}else{
				$return = ['status' => 'error', 'code'=>'500', 'message' => 'Identitas Tidak Terdaftar, Silahkan Melakukan Pendaftaran Terlebih Dahulu !!'];
			}
		} else {
			$message = $validator->messages();
			$return = ['status'=>'error', 'code'=>'400', 'message'=>$message];
		}
		return response()->json($return);
	}

	public function ambilAntrian(Request $request){
		// return ['status' => 'error', 'code'=>'500', 'message' => 'Mohon Maaf, Sistem Sedang Dalam Perbaikan !!'];
		$rules = array(
			// 'no_rm'=>'required',
			'KodeCust'=>'required',
			'jenis_pendaftaran'=>'required',
			'KodePoli'=>'required',
			'tanggal'=>'required',
			'no_tlp'=>'required',
		);
		$messages = array(
			'required' => 'Kolom harus di isi',
		);
		$validator = Validator::make($request->all(), $rules,$messages);
		if(!$validator->fails()){
			// $cekCC = CC::where('norm', $kodeCus)->where('KET','Blokir')->first(); // db lokal
			$cekCC = Rsu_cc::where('norm', $request->kodeCus)->where('KET','Blokir')->first(); // db rsu
			if (empty($cekCC)) {
				$cekTanggal = Holidays::where('tanggal',date('Y-m-d',strtotime($request->tanggal)))->first();
				if (count($cekTanggal) == 0) {
					date_default_timezone_set('Asia/Jakarta');
					$dateNow = date('Y-m-d');
					$dateRequest = date('Y-m-d', strtotime($request->tanggal));
					if ($dateRequest >= $dateNow) {
						$timeNow = date('H');
						// if ($dateNow == $dateRequest) {
						// 	if ($timeNow < 11) {
						// 		$doPros = 'Doit';
						// 	}else{
						// 		$doPros = 'Block';
						// 	}
						// }else{
						// 	// ketika tanggal sekarang lebih dari request
						// 	$doPros = 'Doit';
						// }

						if($dateNow == $dateRequest && $timeNow < 11){
							$return = ['status' => 'error', 'code'=>'500', 'message' => 'Saat ini sudah melebihi jam Operasional !!'];
						}else{
							if ($request->jenis_pendaftaran == 'BPJS' && $request->noRujukan == null) {
								return ['status' => 'error', 'code'=>'500', 'message' => 'No Rujukan Harus Diisi !!'];
							}
							// Cek Antrian
							// $cekAntrian = CC::where('tanggal',date('d-m-Y',strtotime($request->tanggal)))->where('norm',$request->KodeCust)->first(); // bd local
							$cekAntrian = Rsu_cc::where('tanggal',date('d-m-Y',strtotime($request->tanggal)))->where('norm',$request->KodeCust)->first(); // db rsu
							if (empty($cekAntrian)) {
								if ($request->jenis_pendaftaran == 'BPJS') {
									$url = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/Rujukan/".$request->noRujukan; //url web service bpjs
									$consID = "21095"; //customer ID RS
									$secretKey = "rsud6778ws122mjkrt"; //secretKey RS
									$method = 'GET';
									$port = '8080';
									$params = '';
									$resultRujukan = Requestor::set_curl_bridging_new($url, $params, $method, $consID, $secretKey, $port,'',''); // bridging data peserta bpjs
									if ($resultRujukan === false) {
										return ['status' => 'error', 'code'=>'500', 'message' => 'Tidak Dapat Terhubung ke Server !!'];
									}else{
										$resultRujukans = json_decode($resultRujukan, true);
										if ($resultRujukans['response'] == null) {
											$url2 = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/Rujukan/RS/".$request->noRujukan; //url web service bpjs
											$resultRujukan2 = Requestor::set_curl_bridging_new($url2, $params, $method, $consID, $secretKey, $port,'',''); // bridging data peserta bpjs
											if ($resultRujukan2 === false) {
												return ['status' => 'error', 'code'=>'500', 'message' => 'Tidak Dapat Terhubung ke Server !!'];
											}else{
												$resultRujukans = json_decode($resultRujukan2, true);
												if ($resultRujukans['response'] != null) {
													$prosHas = 1;
												}else{
													return ['status' => 'error', 'code'=>'500', 'message' => $resultRujukans['metaData']['message']];
												}
											}
										}
									}

									$batasRujukan = date('Y-m-d', strtotime('+90 days', strtotime(date('Y-m-d',strtotime($resultRujukans['response']['rujukan']['tglKunjungan'])))));
									if (date('Y-m-d', strtotime($request->tanggal)) > $batasRujukan) {
										return ['status' => 'error', 'code'=>'500', 'message' => 'Masa Berlaku Rujukan Telah Habis !!'];
									}
									
									if ($request->id_dpjp == null) {
										return ['status' => 'error', 'code'=>'500', 'message' => 'Dokter Perujuk Harus Diisi !!'];
									}else{
										$drDpjp = rsu_dokter_bridging::find($request->id_dpjp);
										$urlDokter = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/referensi/dokter/pelayanan/2/tglpelayanan/".date('Y-m-d')."/Spesialis/".$drDpjp->polibpjs;
										$resultDokter = Requestor::set_curl_bridging_new($urlDokter, $params, $method, $consID, $secretKey, $port,'','');
										$hslDokter = json_decode($resultDokter, true);
										$kodeDPJP = '';
										for($z=0;$z<count($hslDokter['response']['list']);$z++){
											if (strtolower($hslDokter['response']['list'][$z]['nama']) == strtolower($drDpjp->dokter)) {
												$kodeDPJP = $hslDokter['response']['list'][$z]['kode'];
												$namaDPJP = $hslDokter['response']['list'][$z]['nama'];
											}
										}
										if ($kodeDPJP == '') {
											return ['status' => 'error', 'code'=>'500', 'message' => 'Dokter Tidak Ditemukan !!'];
										}
									}
								}else{
									$kodeDPJP = "";
									$namaDPJP = "";
								}
								$dateSelect = date('d-m-Y', strtotime($request->tanggal));

								// Ambil No Urut Registrasi
								// $cekNoRegis = CC::where('tanggal',$dateSelect)->orderby('nourut','DESC')->first(); // db lokal
								$cekNoRegis = Rsu_cc::where('tanggal',$dateSelect)->orderby('nourut','DESC')->first(); //d rsu
								if (empty($cekNoRegis)) {
									$no_urut_regis = '1';
								}else{
									$no_urut_regis = $cekNoRegis->nourut + 1;
								}

								// Cek Poli
								// $cekPoli = Poli::where('KodePoli',$request->KodePoli)->first(); // db lokal
								$cekPoli = rsu_poli::where('KodePoli',$request->KodePoli)->first(); // db rsu

								// Ambil No Urut Poli
								// $cekNoPoli = CC::where('tanggal',$dateSelect)->where('poli',$cekPoli->NamaPoli)->orderby('urutpoli','DESC')->first(); // db lokal
								$cekNoPoli = Rsu_cc::where('tanggal',$dateSelect)->where('poli',$cekPoli->NamaPoli)->orderby('urutpoli','DESC')->first(); // db rsu
								if (empty($cekNoPoli)) {
									$no_urut_poli = '1';
									$getDataKode = Poli::where('kode_poli', $request->KodePoli)->first();
								}else{
									$no_urut_poli = $cekNoPoli->urutpoli + 1;
									$getDataKode = Poli::where('NamaPoli', $cekNoPoli->poli)->first();
								}
								// Info Customer
								// $cust = Customer::where('KodeCust',$request->KodeCust)->first(); // db lokal
								$cust = rsu_customer::where('KodeCust',$request->KodeCust)->first(); // db rsu
								if ($request->jenis_pendaftaran == 'BPJS') {
									$nobpjs = $cust->FieldCust1;
								}else{
									$nobpjs = null;
								}
								// Get Waktu Estimasi dilayani
								$getEstimasi = DB::connection('dbrsudlain')->table('layanan')->where('tampil', 1)->where('kodepoli', $getDataKode->kode_poli)->first();
								$timeEstimate = "0";
								if ($getEstimasi) {
									$timeBuka = strtotime($getEstimasi->jamlayanan);
									$timeBukaPakai = strtotime(date("H:i", strtotime('-'.$getEstimasi->estimasi.' minutes', $timeBuka)));
									$timeEstimate = date("H:i", strtotime('+'.$getEstimasi->estimasi*$no_urut_poli.' minutes', $timeBukaPakai));
								}
								// $adCC = new CC; // db lokal
								$adCC = new Rsu_cc; // db rsu
								$adCC->norm = $request->KodeCust;
								$adCC->nama = $cust->NamaCust;
								$adCC->alamat = $cust->Alamat;
								// $adCC->penanggung = null;
								$adCC->penanggung = $request->jenis_pendaftaran;
								$adCC->poli = $cekPoli->NamaPoli;
								$adCC->tanggal = $dateSelect;
								$adCC->nourut = $no_urut_regis;
								$adCC->notelp = $request->no_tlp;
								$adCC->KET = '';
								$adCC->nobpjs = $nobpjs;
								$adCC->jam = date('Y-m-d H:i:s');
								$adCC->urutpoli = $no_urut_poli;
								$adCC->pendaftaran = $request->jenis_pendaftaran;
								$adCC->status = '';
								$adCC->norujukan = $request->noRujukan;
								$adCC->kodeDPJP = $kodeDPJP;
								$adCC->namaDPJP = $namaDPJP;
								$adCC->save();
								// $dateReq = date('Y-m-d', strtotime($request->tanggal));
								// $cekApm = Apm::where('tanggal',$dateReq)->orderby('no_antrian','DESC')->first();
								// if (!empty($cekApm)) {
								// 	$no_urut = $cekApm->no_antrian + 1;
								// }else{
								// 	$no_urut = 1;
								// }
								// $apm = new Apm;
								// $apm->no_antrian = $no_urut;
								// $apm->tanggal = date('Y-m-d',strtotime($request->tanggal));
								// $apm->pendaftaran = $request->jenis_pendaftaran;
								// $apm->no_identitas = $request->no_rm;
								// $apm->KodeCust = $request->KodeCust;
								// $apm->tgl_pengambilan = date('Y-m-d');
								// $apm->jam_pengambilan = date('H:i:s');
								// $apm->KodePoli = $request->KodePoli;
								// $apm->status = 'Pendaftaran';
								// $apm->pengambilan = 'Android';
								// $apm->save();
								
								// if ($apm) {
								// 	$return = ['status' => 'success', 'code'=>'200', 'message' => 'Pendaftaran Berhasil Dilakukan !!','data'=>$apm,'nama_poli'=>$apm->poli->NamaPoli];
								// }else{
								// 	$return = ['status' => 'error', 'code'=>'500', 'message' => 'Pendaftaran Gagal Dilakukan !!'];
								// }
								
								if ($adCC) {
									$return = ['status' => 'success', 'code'=>'200', 'message' => 'Pendaftaran Berhasil Dilakukan !!','data'=>$adCC,'estimasi'=>$timeEstimate,'jenis_pendaftaran'=>$request->jenis_pendaftaran];
								}else{
									$return = ['status' => 'error', 'code'=>'500', 'message' => 'Pendaftaran Gagal Dilakukan !!'];
								}
							}else{
								$return = ['status' => 'error', 'code'=>'500', 'message' => 'Anda Sudah Mengambil Antrian Untuk Tanggal Tersebut !!'];
							}
						}
					}else{
						$return = ['status' => 'error', 'code'=>'500', 'message' => 'Tanggal yang dipilih sudah terlewat !!'];
					}
				}else{
					$return = ['status' => 'error', 'code'=>'500', 'message' => 'Tanggal yang Dipilih adalah Hari Libur, Silahkan Pilih Hari Lain !!'];
				}
			}else{
				$return = ['status' => 'error', 'code'=>'500', 'message' => 'No Rekam Medis Anda di Blokir, Silahkan menghubungi petugas Pendaftaran !!'];
			}
		} else {
			$message = $validator->messages();
			$return = ['status'=>'error', 'code'=>'400', 'message'=>$message];
		}
		return response()->json($return);
	}

	public function kotakSaran(Request $request){
		$rules = array(
			'KodeCust'=>'required',
			'judul'=>'required',
			'saran'=>'required',
		);
		$messages = array(
			'required' => 'Kolom harus di isi',
		);
		$validator = Validator::make($request->all(), $rules,$messages);
		if(!$validator->fails()){
			date_default_timezone_set('Asia/Jakarta');
			$saran = new KotakSaran;
			$saran->judul = $request->judul;
			$saran->saran = $request->saran;
			$saran->tanggal = date('Y-m-d H:i:s');
			$saran->KodeCust = $request->KodeCust;
			$saran->save();
			
			if ($saran) {
				$return = ['status' => 'success', 'code'=>'200', 'message' => 'Saran Berhasil Disimpan !!','data'=>$saran];
			}else{
				$return = ['status' => 'error', 'code'=>'500', 'message' => 'Saran Gagal Disimpan !!'];
			}
		} else {
			$message = $validator->messages();
			$return = ['status'=>'error', 'code'=>'400', 'message'=>$message];
		}
		return response()->json($return);
	}

	public function getInfoPenyakit(Request $request){
		$informasi = InformasiPenyakit::all();
		$return = ['status'=>'success','code'=>'200','message'=>'','data'=>$informasi];
		return response()->json($return);
	}

	public function getPolaHidup(Request $request){
		$polaHidup = PolaHidupSehat::all();
		$return = ['status'=>'success','code'=>'200','message'=>'','data'=>$polaHidup];
		return response()->json($return);
	}

	public function getNoUrut(Request $request){
		$rules = array(
			'KodeCust'=>'required',
		);
		$messages = array(
			'required' => 'Kolom harus di isi',
		);
		$validator = Validator::make($request->all(), $rules,$messages);
		if(!$validator->fails()){
			date_default_timezone_set('Asia/Jakarta');
			$dateNow = date('d-m-Y');
			// $cekNoUrut = CC::where('norm',$request->KodeCust)->where('tanggal',$dateNow)->first(); // db lokal
			$cekNoUrut = Rsu_cc::where('norm',$request->KodeCust)->where('tanggal',$dateNow)->first(); // db rsu
			if (!empty($cekNoUrut)) {
				// $noUrutRegis = $cekNoUrut->nourut;
				if (strlen($cekNoUrut->nourut) == 1) {
					$noUrutRegis = '00'.$cekNoUrut->nourut;
				}elseif (strlen($cekNoUrut->nourut) == 2) {
					$noUrutRegis = '0'.$cekNoUrut->nourut;
				}else{
					$noUrutRegis = $cekNoUrut->nourut;
				}
				if ($cekNoUrut->urutpoli != null) {
					// $noUrutPoli = $cekNoUrut->urutpoli;
					if (strlen($cekNoUrut->urutpoli) == 1) {
						$noUrutPoli = '00'.$cekNoUrut->urutpoli;
					}elseif (strlen($cekNoUrut->urutpoli) == 2) {
						$noUrutPoli = '0'.$cekNoUrut->urutpoli;
					}else{
						$noUrutPoli = $cekNoUrut->urutpoli;
					}
				}else{
					$noUrutPoli = '-';
				}
				$namaPoli = $cekNoUrut->poli;
			}else{
				$noUrutRegis = '-';
				$noUrutPoli = '-';
				$namaPoli = '-';
			}
			$return = ['status'=>'success','code'=>'200','noUrutRegis'=>$noUrutRegis,'noUrutPoli'=>$noUrutPoli,'namaPoli'=>$namaPoli];
		} else {
			$message = $validator->messages();
			$return = ['status'=>'error', 'code'=>'400', 'message'=>$message];
		}
		return response()->json($return);
	}

	public function versiApp(Request $request){
		$versi = VersiAndroid::orderby('id_versi','DESC')->first();
		return ['status' => 'success', 'code' => '200', 'versi' => $versi->versi];
	}

	public function addVersiApp(Request $request){
		if ($request->versi != null) {
			date_default_timezone_set('Asia/Jakarta');
			$versi = new VersiAndroid;
			$versi->versi = $request->versi;
			$versi->tanggal = date('Y-m-d H:i:s');
			$versi->save();
			if ($versi) {
				$return = ['status' => 'success', 'code' => '200','message' => 'versi berhasil ditambah !!', 'data' => $versi];
			}else{
				$return = ['status' => 'error', 'code' => '500','message' => 'versi gagal ditambah !!'];
			}
		}else{
			$return = ['status' => 'error', 'code' => '500','message' => 'versi harus diisi !!'];
		}
		return $return;
	}

	public function allVersiApp(Request $request){
		$versi = VersiAndroid::all();
		return $versi;
	}

	public function getDokterDpjp(Request $request){
		$dokter = rsu_dokter_bridging::all();
		$return = ['status'=>'success','code'=>'200','message'=>'','data'=>$dokter];
		return response()->json($return);
	}

	// REMINDER CONTROL PASIEN
	public function getReminder(Request $request) {
		$cur_page = $request->page;
		$per_page = 10;
		$jumlah_halaman = 0;
		$offset = ($per_page*$cur_page);

		$kueri = "SELECT * FROM rekap_medik WHERE no_RM='".$request->KodeCust."' OR tgl_kontrol='".$request->tgl_kontrol."' ORDER BY tanggalKunjungan DESC LIMIT 15"/*.$per_page." OFFSET ".$offset*/;
		$kueri_total = "SELECT * FROM rekap_medik WHERE no_RM='".$request->KodeCust."' OR tgl_kontrol='".$request->tgl_kontrol."' ORDER BY tanggalKunjungan DESC";

		$getData = DB::connection('dbwahidin')->select($kueri);
		$getDataAll = DB::connection('dbwahidin')->select($kueri_total);
		$arr_pakai = [];
		$status = 'error';
		$message = 'data tidak ada';
		$code = '300';
		// if($getData){
		// 	push notif
		// 	$input = [
		// 			'judul'=>'Pengingat Kontrol',
		// 			'data'=>[
		// 				'status'=>'success',
		// 				'code'=>'200',
		// 				'message'=>'Hari ini ada jadwal kontrol',
		// 				'data'=>[
		// 					'No_RM'=>'$getData->No_RM',
		// 					'tgl_kontrol'=>'$getData->tgl_kontrol',
		// 				],
		// 			],
		// 			'id_player'=>[$user->player_id],
		// 	];
		// 	Notifikasi::notifikasi($input);
			$jumlah_halaman = ceil(count($getDataAll)/$per_page);
			foreach ($getData as $key) {
				$arr['no_RM'] = $key->no_RM;
				$arr['Nama_Pasien'] = $key->Nama_Pasien;
				$arr['NamaPoli'] = $key->NamaPoli;
				$arr['tanggalKunjungan'] = $key->tanggalKunjungan;
				$arr['tgl_kontrol'] = $key->tgl_kontrol;
				$arr_pakai[] = $arr;
			}
			$status='success';
			$message='data ada';
			$code='200';
		// }
		return [
			'status' => $status,
			'code' => $code,
			'message' => $message,
			'current_page' => $cur_page,
			'jumlah_page' => $jumlah_halaman,
			'data' => $arr_pakai,
		];
	}

	 // REMINDER CHECK (Reminder Kontrol)
	 // id_rekapMedik
	 // no_RM
	 // no_Register
	 // tanggalKunjungan
	 // NamaPoli
	 // tgl_kontrol
	public function reminderCheck(Request $request) {
		$tgl_sekarang = date("Y-m-d");
		$date = Carbon::createFromFormat('Y-m-d', $tgl_sekarang)->addDay(1)->format("Y-m-d");
		// return $date;
		$kueri2 = "SELECT id_rekapMedik,no_RM,no_Register,tanggalKunjungan,NamaPoli,tgl_kontrol FROM rekap_medik WHERE tgl_kontrol = '".$date."'";
		$kueri = "SELECT id_rekapMedik,no_RM,no_Register,tanggalKunjungan,NamaPoli,tgl_kontrol FROM rekap_medik WHERE tgl_kontrol = '".$tgl_sekarang."'";
		$arrPakai = [];
		$playerIDPakai = [];
		$hariini = DB::connection('dbwahidin')->select($kueri);
		$besok = DB::connection('dbwahidin')->select($kueri2);
		if($besok){
			foreach($besok as $row){
				$arrPakai[] = $row->no_RM;
			}
			$cekCustDevice = Device::whereIn('KodeCust', $arrPakai)->get();
			foreach($cekCustDevice as $pId){
				$playerIDPakai[] = $pId->player_id;
			}
			// return $playerIDPakai;
			// push notif
			$input = [
				'judul'=>'Pengingat Kontrol',
				'data'=>[
					'status'=>'success',
					'code'=>'200',
					'message'=>'Besok ada jadwal kontrol!',
					'data'=>$row,
				],
				'id_player'=>$playerIDPakai,
			];
			// return $input;
			Notifikasi::notifikasi($input);
		} elseif($hariini) {
			foreach($hariini as $row){
				$arrPakai[] = $row->no_RM;
			}
			$cekCustDevice = Device::whereIn('KodeCust', $arrPakai)->get();
			foreach($cekCustDevice as $pId){
				$playerIDPakai[] = $pId->player_id;
			}
			// return $playerIDPakai;
			// push notif
			$input = [
				'judul'=>'Pengingat Kontrol',
				'data'=>[
					'status'=>'success',
					'code'=>'200',
					'message'=>'Hari ini ada jadwal kontrol!',
					'data'=>$row,
				],
				'id_player'=>$playerIDPakai,
			];
			// return $input;
			Notifikasi::notifikasi($input);
		}

		// $status='success';
		// $message='data ada';
		// $code='200';
		// return [
		// 	'status' => $status,
		// 	'code' => $code,
		// 	'message' => $message,
		// 	'data' => $getData,
		// ];
	}

	// GET RADIOLOGI RESULT
	public function getRadiologiResult(Request $request){
		$dbcon = pg_connect("host='192.168.1.172' user='postgres' password='postgres5432' dbname='dicom'");
		// Cek Koneksi Ke Server Database
		// PAKAI
		$query = "SELECT NO_RM,TGL,CATATAN FROM tab_rdl_master A LEFT JOIN tab_rdl_catatan B ON A.ID=B.id_master WHERE NO_RM='".$request->KodeCust."'";
		$result = pg_query($dbcon, $query) or die("Query gagal  " );
		$return = [
			'status' => 'success',
			'code' => '200',
			'message' => 'Berhasil mengambil data',
			'data' => pg_fetch_all($result),
		];
		return response()->json($return);
	}

	// HISTORICAL DIAGNOSE ONLINE BY ICD 10
	public function getDiagnoseOnline(Request $request) {
		$cur_page = $request->page;
		$per_page = 10;
		$jumlah_halaman = 0;
		$offset = ($per_page*$cur_page);
		
		$kueri = "SELECT No_RM, Tgl, DiagnosaPrimer, NamaDiagnosaPrimer AS Diagnosa, Dokter FROM tr_rawatjalantindakan WHERE No_RM='".$request->KodeCust."' GROUP BY No_Register ORDER BY Tgl DESC LIMIT ".$per_page." OFFSET ".$offset;
		$kueri_total = "SELECT No_RM, Tgl, DiagnosaPrimer, NamaDiagnosaPrimer AS Diagnosa, Dokter FROM tr_rawatjalantindakan WHERE No_RM='".$request->KodeCust."' GROUP BY No_Register ORDER BY Tgl DESC";
		
		$getData = DB::connection('dbrsud')->select($kueri);
		$getDataAll = DB::connection('dbrsud')->select($kueri_total);
		$arr_pakai = [];
		$status = 'error';
		$message = 'data tidak ada';
		$code = '300';
		if($getDataAll != 0){
			$jumlah_halaman = ceil(count($getDataAll)/$per_page);
			foreach ($getData as $key) {
				$arr['No_RM'] = $key->No_RM;
				$arr['Tgl'] = $key->Tgl;
				$arr['DiagnosaPrimer'] = $key->DiagnosaPrimer;
				$arr['Diagnosa'] = $key->Diagnosa;
				$arr['Dokter'] = $key->Dokter;
				$arr_pakai[] = $arr;
			}
			$status='success';
			$message='data ada';
			$code='200';
		}
		return [
			'status' => $status,
			'code' => $code,
			'message' => $message,
			'current_page' => $cur_page,
			'jumlah_page' => $jumlah_halaman,
			'data' => $arr_pakai,
		];
	}

	// HISTORICAL TREATMENT PASIEN ONLINE
	public function getTreatmentOnline(Request $request) {
		$cur_page = $request->page;
		$per_page = 10;
		$jumlah_halaman = 0;
		$offset = ($per_page*$cur_page);
		
		$kueri = "SELECT No_RM, Tgl, NamaBrg AS Obat, Satuan, CONVERT(Jml, UNSIGNED INTEGER) AS Jumlah FROM tr_rawatjalanobat WHERE No_RM='".$request->KodeCust."' ORDER BY Tgl DESC LIMIT ".$per_page." OFFSET ".$offset;
		$kueri_total = "SELECT No_RM, Tgl, NamaBrg AS Obat, Satuan, CONVERT(Jml, UNSIGNED INTEGER) AS Jumlah FROM tr_rawatjalanobat WHERE No_RM='".$request->KodeCust."' ORDER BY Tgl DESC";
		
		$getData = DB::connection('dbrsud')->select($kueri);
		$getDataAll = DB::connection('dbrsud')->select($kueri_total);
		$arr_pakai = [];
		$status = 'error';
		$message = 'data tidak ada';
		$code = '300';
		if($getDataAll != 0){
			$jumlah_halaman = ceil(count($getDataAll)/$per_page);
			foreach ($getData as $key) {
				$arr['No_RM'] = $key->No_RM;
				$arr['Tgl'] = $key->Tgl;
				$arr['Obat'] = $key->Obat;
				$arr['Satuan'] = $key->Satuan;
				$arr['Jumlah'] = $key->Jumlah;
				$arr_pakai[] = $arr;
			}
			$status='success';
			$message='data ada';
			$code='200';
		}
		return [
			'status' => $status,
			'code' => $code,
			'message' => $message,
			'current_page' => $cur_page,
			'jumlah_page' => $jumlah_halaman,
			'data' => $arr_pakai,
		];
	}

	// INTEGRATED SOBAT WAHIDIN
	public function simpanSobat(Request $request) {
		// value KodeCust = No_RM
		$dateNow = date('Y-m-d');
		$find = DB::connection('dbrsudlain')->table('sobat')
			->where('Tgl', $dateNow)->orderBy('NOURUT', 'DESC')->first();
		$pas = DB::connection('dbrsud')
			->table('tr_registrasi')
			->select('tr_registrasi.*', 'tm_poli.NamaPoli AS NamaPoli')
			->rightJoin('tm_poli', 'tr_registrasi.Kode_Poli1', '=', 'tm_poli.KodePoli')
			->orderBy('No_Register', 'DESC')
			->where('No_RM', $request->KodeCust)
			->first();
		if ($find) {
			$no_urut = $find->NOURUT + 1;
		} else {
			$no_urut = 1;
		}
		$data = [
			'No_register' => $pas->No_Register,
			'Tgl' => $dateNow,
			'keterangan' => '',
			'pj' => 0,
			'LOKASI' => $request->LOKASI,
			'jenis' => 'LAYANAN ANTAR OBAT',
			'Norm' => $request->KodeCust,
			'ruangan' => $pas->NamaPoli,
			'alamat' => $pas->AlamatPasien,
			'jeniskel' => $pas->JenisKel,
			'usia' => $pas->Umur,
			'tanggallahir' => $pas->Tgl_Lahir,
			'nama' => $pas->Nama_Pasien,
			'NOURUT' => $no_urut,
		];
		$insert = DB::connection('dbrsudlain')->table('sobat')->insert($data);
		if($insert){
			$return = [
				'status' => 'success',
				'code' => '200',
				'message' => 'Berhasil memasukkan data pesanan obat',
				'data' => $data,
			];
		}else{
			$return = [
				'status' => 'failed',
				'code' => '250',
				'message' => 'Gagal memasukkan data!',
			];
		}
		return response()->json($return);
	}

	// BED MANAGEMENT INTEGRATED
	public function getBed(Request $request) {
		$cur_page = $request->page;
		$per_page = 100;
		$jumlah_halaman = 0;
		$offset = ($per_page*$cur_page);
		$kueri = "SELECT e.keterangan AS Keterangan,
			COUNT(IF(s.status='IN',1,NULL)) AS Terisi,
			COUNT(IF(s.status='RD',1,NULL)) AS Tersedia,
			COUNT(IF(s.status='ME',1,NULL)) AS Perbaikan,
			COUNT(b.kodebed) jumlah
			FROM xlink.fokmrsetupbed b 
			LEFT JOIN xlink.fokmrmstbed s ON s.kode=b.kodebed 
			LEFT JOIN xlink.fokmrmstruang c ON c.kode=b.koderuang 
			LEFT JOIN xlink.fokmrmstlantai e ON b.kodelantai=e.kode 
			GROUP BY e.keterangan LIMIT ".$per_page." OFFSET ".$offset;
		$kueri_total = "SELECT e.keterangan AS Keterangan,
			COUNT(IF(s.status='IN',1,NULL)) AS Terisi,
			COUNT(IF(s.status='RD',1,NULL)) AS Tersedia,
			COUNT(IF(s.status='ME',1,NULL)) AS Perbaikan,
			COUNT(b.kodebed) jumlah
			FROM xlink.fokmrsetupbed b 
			LEFT JOIN xlink.fokmrmstbed s ON s.kode=b.kodebed 
			LEFT JOIN xlink.fokmrmstruang c ON c.kode=b.koderuang 
			LEFT JOIN xlink.fokmrmstlantai e ON b.kodelantai=e.kode 
			GROUP BY e.keterangan";
		$getData = DB::connection('dbxlink')->select($kueri);
		$getDataAll = DB::connection('dbxlink')->select($kueri_total);
		$arr_pakai = [];
		$status = 'error';
		$message = 'data tidak ada';
		$code = '300';
		if($getDataAll != 0){
			$jumlah_halaman = ceil(count($getDataAll)/$per_page);
			foreach ($getData as $key) {
				$arr['Keterangan'] = $key->Keterangan;
				$arr['Terisi'] = $key->Terisi;
				$arr['Tersedia'] = $key->Tersedia;
				$arr['Perbaikan'] = $key->Perbaikan;
				$arr['jumlah'] = $key->jumlah;
				$arr_pakai[] = $arr;
			}
			$status='success';
			$message='data ada';
			$code='200';
		}
		return [
			'status' => $status,
			'code' => $code,
			'message' => $message,
			'current_page' => $cur_page,
			'jumlah_page' => $jumlah_halaman,
			'data' => $arr_pakai,
		];
	}
}