<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Libraries\RequestorWaBot;
use App\Http\Models\Antrian;
use App\Http\Models\Rsu_Register;
use App\Http\Models\rsu_customer;
use DB,config,DateTime;
use Illuminate\Support\Facades\Log;

class WaBotBridgingController extends Controller{
	public function __construct(){
		date_default_timezone_set('Asia/Jakarta');

		// PROD START 
		$this->urlAntrian     = 'https://apijkn.bpjs-kesehatan.go.id/antreanrs/';
		$this->urlVClaim      = "https://apijkn.bpjs-kesehatan.go.id/vclaim-rest/";
		$this->consID         = env('CONS_ID','21095');
		$this->secretKey      = env('SECRET_KEY','rsud6778ws122mjkrt');
		$this->userKeyVClaim  = '2079632035f01e757d81a8565b074768';
		$this->userKeyAntrian = '364e21ef098e7d6e69889eac7cadb3c3';
		// PROD END

		// DEV START
		// $this->urlAntrian      = 'https://apijkn-dev.bpjs-kesehatan.go.id/antreanrs_dev/';
		// $this->urlVClaim       = 'https://apijkn-dev.bpjs-kesehatan.go.id/vclaim-rest-dev/';
		// $this->consID          = '21095';
		// $this->secretKey       = 'rsud6778ws122mjkrt';
		// $this->userKeyVClaim   = '21f330a3e8e9f281d845f6b545b23653';
		// $this->userKeyAntrian  = 'dd6817bcc763343bde6eafb760f0c596';
		// DEV END
	}

	public function cekPeserta(Request $request){
		date_default_timezone_set("Asia/Jakarta");
		// $sep = $request->nobpjs ? $request->nobpjs : '0000000000000000000';
		$nomor = $request->nomor;
		$jenis = $request->jenis;
		
		$url = '';
		if($jenis == 'nik'){
			$url = $this->urlVClaim."Peserta/nik/".$nomor."/tglSEP/".date('Y-m-d');
		}else{
			$url = $this->urlVClaim."Peserta/nokartu/".$nomor."/tglSEP/".date('Y-m-d');
		}
		$consID     = $this->consID; //customer ID RS
		$secretKey  = $this->secretKey; //secretKey RS
		$uk         = $this->userKeyVClaim;
		$method = 'GET';
		$result    = RequestorWaBot::setCurlBPJS($url,$method,$consID,$secretKey,$uk,'');

		// $result = Requestor::set_new_curl_bridging($url, $params, $method, $consID, $secretKey, $port,'','');
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

	public function cekMultiRujukan(Request $request){
		$jenis = $request->jenis;
		$nomor = $request->nomor;
		date_default_timezone_set('Asia/Jakarta');
		/*
		RUJUKAN DARI TK 1
		Cara Kerja : Cari Dari TK 1 dulu kalau responnya kosong maka carikan dari TK 2
		Fungsi dibawah, cari rujukan dari TK 1
		*/
		if($jenis=='bpjs'){
			$url = $this->urlVClaim."Rujukan/Peserta/".$nomor;
			// $url = $this->urlVClaim."Rujukan/List/Peserta/".$nomor;
		}else{
			$url = $this->urlVClaim."Rujukan/".$nomor;
			// $url = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/Rujukan/".$request->noRujuk; // url rilis
		}

		$consID     = $this->consID; //customer ID RS
		$secretKey  = $this->secretKey; //secretKey RS
		$uk         = $this->userKeyVClaim;
		$method = 'GET';
		$result = RequestorWaBot::setCurlBPJS($url,$method,$consID,$secretKey,$uk,'');
		$owner = 'FKTP1'; # Puskesmas/klinik
		$resSKDP = '';
		if($result === false){
			return ['status' => 'error','code'=>404,'message' => 'Tidak Terhubung ke Server !!'];
		}
		$response = $result['response'];
		$results = [
			'metaData' => $result['metaData'],
			'response' => $response,
		];

		$data['tingkatRujuk'] = '1';
		$nums=$prosHas=0;
		if($response!=''){
			$natusiApm = mysqli_connect('192.168.1.5','client','Wahidin123','natusi_apm');
			$nomorRujukan = $response->rujukan->noKunjungan;
			// $nomorRujukan = '123';
			$query = "SELECT id,nomor_referensi FROM antrian WHERE nomor_referensi='$nomorRujukan' LIMIT 1";
			$antrianByRujukan = mysqli_query($natusiApm,$query);
			$nums = mysqli_num_rows($antrianByRujukan);
		}
		if($response=='' || $nums>0){
			$nums = 0; # Reset nums to 0
			/*
			RUJUKAN DARI TK 2
			sekarang cari dari TK 2 karena tidak dapat respon / null dari TK 1
			*/
			if($jenis=='bpjs'){
				$url2 = $this->urlVClaim."Rujukan/RS/Peserta/".$nomor;
			}else{
				$url2 = $this->urlVClaim."Rujukan/RS/".$nomor;
			}
			$result2 = RequestorWaBot::setCurlBPJS($url2,$method,$consID,$secretKey,$uk,''); // bridging data peserta bpjs
			if ($result2 === false) {
				return ['status' => 'error', 'messages' => 'Tidak Dapat Terhubung ke Server !!'];
			}else{
				$response2 = $result2['response'];
				$results = [
					'metaData' => $result2['metaData'],
					'response' => $response2,
				];
				if($response2!=""){
					$natusiApm = mysqli_connect('192.168.1.5','client','Wahidin123','natusi_apm');
					$nomorRujukan = $response2->rujukan->noKunjungan;
					$query = "SELECT id,nomor_referensi FROM antrian WHERE nomor_referensi='$nomorRujukan' LIMIT 1";
					$antrianByRujukan = mysqli_query($natusiApm,$query);
					$nums = mysqli_num_rows($antrianByRujukan);
					// return$data = (object)$antrianByRujukan->fetch_assoc();
					// return $data->nomor_referensi;
				}
				if(($response2=="" || $nums>0) && $jenis!='bpjs'){ # Hanya digunakan ketika $jenis=="rujukan" / selain "bpjs"
					$nums=0;
					$cekRujukan = new BridgingController;
					$request->noSurat = $nomor;
					$respon2 = $cekRujukan->cekSkdp($request);
					if($respon2['response']!=""){
						if($respon2['metaData']->code==200){
							$resSKDP = $respon2['response'];
							$owner = 'SKDP'; # Surat Kontrol
							$prosHas = 1;
						}
					}else{
						// if($request->nomor=='132002021123P000557'){
							if($response!=''){
								$prosHas = 1;
								$owner = 'FKTP1'; # Puskesmas/klinik
								$results = [
									'metaData' => $result['metaData'],
									'response' => $response,
								];
							}
							if($response2!=''){
								$prosHas = 1;
								$owner = 'FKTP2'; # RS
								$results = [
									'metaData' => $result2['metaData'],
									'response' => $response2,
								];
							}
							// return $response;
							// return $owner;
						// }
					}
				}else{
					if($response2!="" && $jenis!='bpjs'){
						$prosHas = 1;
						$data['tingkatRujuk'] = '2';
						$owner = 'FKTP2'; # RS
					}
				}
			}
		}else{
			$prosHas = 1;
		}

		if ($prosHas == 1) {
			$data['rujukan'] = $results;

			if($owner=='SKDP'){
				$res = $resSKDP;
				$myOwnData = [
					"tingkatRujuk" => $res->namaJnsKontrol,
					"noBpjs"       => $res->sep->peserta->noKartu,
					"noRujuk"      => $res->noSuratKontrol,
					"nik"          => null,
					"kodePoli"     => $res->poliTujuan,
					"namaPoli"     => $res->namaPoliTujuan,
					"tglLahir"     => $res->sep->peserta->tglLahir,
					"data" => $res
				];
			}else{
				if($results['response']==''){
	                $res = $result2['response']->rujukan;
                }else{
                	$res = $results['response']->rujukan;
				}

				$myOwnData = [
					"tingkatRujuk"     => $data['tingkatRujuk'],
					"noBpjs"           => $res->peserta->noKartu,
					"noRujuk"          => $res->noKunjungan,
					"nik"              => $res->peserta->nik,
					"kodePoli"         => $res->poliRujukan->kode,
					"namaPoli"         => $res->poliRujukan->nama,
					"tglLahir"         => $res->peserta->tglLahir,
					"tglKunjungan"     => $res->tglKunjungan,
					"tglKunjunganPlus" => date("Y-m-d",strtotime("+89 days",strtotime($res->tglKunjungan))),
				];
			}
			return ['status' => 'success', 'code'=>200 ,'message' => 'Rujukan Ditemukan','data'=>$myOwnData];
		}else{
			return ['status' => 'error','code' => 404 ,'message' => 'Rujukan Tidak Ditemukan !!','data'=>$data];
		}
	}

	public function cekRujukan(Request $request){
		$jenis = $request->jenis;
		$nomor = $request->nomor;
		date_default_timezone_set('Asia/Jakarta');
		/*
		RUJUKAN DARI TK 1
		Cara Kerja : Cari Dari TK 1 dulu kalau responnya kosong maka carikan dari TK 2
		Fungsi dibawah, cari rujukan dari TK 1
		*/
		if($jenis=='bpjs'){
			$url = $this->urlVClaim."Rujukan/Peserta/".$nomor;
			// $url = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/Rujukan/Peserta/".$request->noBpjs; // url rilis
		}else{
			$url = $this->urlVClaim."Rujukan/".$nomor;
			// $url = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/Rujukan/".$request->noRujuk; // url rilis
		}

		$consID     = $this->consID; //customer ID RS
		$secretKey  = $this->secretKey; //secretKey RS
		$uk         = $this->userKeyVClaim;
		$method = 'GET';
		$result = RequestorWaBot::setCurlBPJS($url,$method,$consID,$secretKey,$uk,'');
		$owner = 'FKTP1';
		$resSKDP = '';
		if($result === false){
			return ['status' => 'error','code'=>404,'message' => 'Tidak Terhubung ke Server !!'];
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
				if($jenis=='bpjs'){
					$url2 = $this->urlVClaim."Rujukan/RS/Peserta/".$nomor;
					// $url2 = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/Rujukan/RS/Peserta/".$request->noBpjs; //url web service bpjs rilis
				}else{
					$url2 = $this->urlVClaim."Rujukan/RS/".$nomor;
					// $url2 = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/Rujukan/RS/".$request->noRujuk; // url rilis
				}
				$result2 = RequestorWaBot::setCurlBPJS($url2,$method,$consID,$secretKey,$uk,''); // bridging data peserta bpjs
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
						$owner = 'FKTP2';
					}else{
						$cekRujukan = new BridgingController;
						$request->noSurat = $nomor;
						$respon2 = $cekRujukan->cekSkdp($request);
						if($respon2['response']!=""){
							if($respon2['metaData']->code==200){
								$resSKDP = $respon2['response'];
								$owner = 'SKDP';
								$prosHas = 1;
							}else{
								$prosHas = 0;
							}
						}else{
							$prosHas = 0;
						}
					}
				}
			}else{
				$prosHas = 1;
			}

			if ($prosHas == 1) {
				$data['rujukan'] = $results;

				//custom return
				// tambahkan pengecekan disini, ketika dari TK 2 responnya null, cek SKDP
				if($owner=='SKDP'){
					$res = $resSKDP;
					$myOwnData = [
						"tingkatRujuk" => $res->namaJnsKontrol,
						"noBpjs"       => $res->sep->peserta->noKartu,
						"noRujuk"      => $res->noSuratKontrol,
						"nik"          => null,
						"kodePoli"     => $res->poliTujuan,
						"namaPoli"     => $res->namaPoliTujuan,
						"tglLahir"     => $res->sep->peserta->tglLahir,
						"data" => $res
					];
				}else{
					if($results['response']==''){
		                $res = $result2['response']->rujukan;
	                }else{
	                	$res = $results['response']->rujukan;
					}

					$myOwnData = [
						"tingkatRujuk"     => $data['tingkatRujuk'],
						"noBpjs"           => $res->peserta->noKartu,
						"noRujuk"          => $res->noKunjungan,
						"nik"              => $res->peserta->nik,
						"kodePoli"         => $res->poliRujukan->kode,
						"namaPoli"         => $res->poliRujukan->nama,
						"tglLahir"         => $res->peserta->tglLahir,
						"tglKunjungan"     => $res->tglKunjungan,
						"tglKunjunganPlus" => date("Y-m-d",strtotime("+89 days",strtotime($res->tglKunjungan))),
					];
				}
				return ['status' => 'success', 'code'=>200 ,'message' => 'Rujukan Ditemukan','data'=>$myOwnData];
				//end custom return
			}else{
				return ['status' => 'error','code' => 404 ,'message' => 'Rujukan Tidak Ditemukan !!','data'=>$data];
			}
			// return $return;
		}
	}

	public function refJadDok(Request $request){
		// $kode = strtoupper($data[0]); // Kode Poli BPJS
		// $tanggal = date("Y-m-d",strtotime($data[1])); // Tanggal Jadwal Dokter
		$kode      = $request->kode;
		$tanggal   = date("Y-m-d",strtotime($request->tanggal));

		$url       = $this->urlAntrian."jadwaldokter/kodepoli/$kode/tanggal/$tanggal";
		$consID    = $this->consID; // customer ID RS
		$secretKey = $this->secretKey; // secretKey RS
		$uk        = $this->userKeyAntrian;
		$method    = 'GET';

		$requestorWaBot = new RequestorWaBot;
		$result = $requestorWaBot->setCurlBridg($url,$method,$consID,$secretKey,$uk,'');
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
						'metaData' => $result['metaData'],
						'response' => ""
					];
				}
			}else{
				$msg = $kode?"Tanggal tidak boleh kosong.":"Kode Poli tidak boleh kosong.";
				return [
					'metaData' => [
						'code' => 201,
						'message' => $msg
					],
					'response' => ""
				];
			}
		}
	}

	public function storeRsuRegister(Request $req){
		$isPasienBaru = $req->data->is_pasien_baru;
		if($isPasienBaru==0){
			$wablas = $req->wablas; // koneksi natusiAPM
			$dbrsud = $req->dbrsud; // koneksi dbSimars
			$tglBerobat = $req->data->tglBerobat;
			$nama = ($req->data->nama!=="")?str_replace("'","''",$req->data->nama):"";
			$noRM = $req->data->KodeCust;
			$kodePoli = $req->data->kodePoli;
			$caraBayar = $req->data->caraBayar;

			$tmCust = "SELECT * FROM tm_customer WHERE KodeCust='$noRM'";
			$execTmCust = mysqli_query($dbrsud,$tmCust);
			if(mysqli_num_rows($execTmCust)>0){
				$resTmCust = (object)$execTmCust->fetch_assoc();

				$jam = date('H:i:s');
				$jenisKel = $resTmCust->JenisKel;
				$noRM = $noRM;
				$alamat = $resTmCust->Alamat;
				$nomorKartu = $resTmCust->FieldCust1;
		
				// hitung umur start
					$tglLahir = $req->data->tglLahir;
					if($tglLahir==""||$tglLahir==null||$tglLahir=='0000-00-00 00:00:00'){
						$tglLahir = date('Y-m-d',strtotime("-37 year",strtotime("today"))); // set tanggal lahir -37 tahun dari sekarang
					}
					$tanggal  = new DateTime($tglLahir);
					$today    = new DateTime('today');
					$umurReg  = $today->diff($tanggal)->y;
				// hitung umur end

				$tg   = date('y',strtotime($tglBerobat));
				$tg   = $tg.'2';
				$thn  = date('Y',strtotime($tglBerobat)); $mo = date('m',strtotime($tglBerobat)); $da = date('d',strtotime($tglBerobat));
				$urut = "SELECT * FROM tr_registrasi WHERE (YEAR(Tgl_Register)=$thn) AND LEFT(No_Register,3)=$tg ORDER BY No_Register DESC LIMIT 1";
				$execUrut = mysqli_query($dbrsud,$urut);
				$resUrut = (object)$execUrut->fetch_assoc();
		
				// PENGECEKAN DATA DI TR_REGISTRASI
				$tgl = date("Y-m-d",strtotime($tglBerobat));
				// $cekRegis = "SELECT * FROM tr_registrasi WHERE No_RM='$noRM' AND Tgl_Register LIKE '$tgl%' LIMIT 1";
				$cekRegis = "SELECT * FROM tr_registrasi WHERE No_RM='$noRM' AND DATE(Tgl_Register)='$tgl' LIMIT 1";
				$execCekRegis = mysqli_query($dbrsud,$cekRegis);
				if(mysqli_num_rows($execCekRegis)==0){
					if(mysqli_num_rows($execUrut)>0){
						$noUrut = $resUrut->No_Register+1;
					}else{
						$noUrut = date('y').'20000001';
					}

					// $cekRegis2 = "SELECT * FROM tr_registrasi WHERE No_Register='$noUrut' AND Tgl_Register LIKE '$tgl%' LIMIT 1";
					$cekRegis2 = "SELECT * FROM tr_registrasi WHERE No_Register='$noUrut' AND DATE(Tgl_Register)='$tgl' LIMIT 1";
					$execCekRegis2 = mysqli_query($dbrsud,$cekRegis2);
					if(mysqli_num_rows($execCekRegis2)>0){
						$thn  = date('Y',strtotime($tglBerobat)); $mo = date('m',strtotime($tglBerobat)); $da = date('d',strtotime($tglBerobat));
						$urut = "SELECT * FROM tr_registrasi WHERE (YEAR(Tgl_Register)=$thn) AND LEFT(No_Register,3)=$tg ORDER BY No_Register DESC LIMIT 1";
						$execUrut = mysqli_query($dbrsud,$urut);
						$resUrut = (object)$execUrut->fetch_assoc();

						if(mysqli_num_rows($execUrut)>0){
							$noUrut = $resUrut->No_Register+1;
						}else{
							$noUrut = date('y').'20000001';
						}
					}

					$tglRegis = date('Y-m-d H:i:s',strtotime($tglBerobat.$jam));
					$kodePoli = !empty($kodePoli)?$kodePoli:"";
					$jenisKel = !empty($jenisKel)?$jenisKel:"";
					$poliRS = "SELECT * FROM mapping_poli_bridging WHERE kdpoli='$kodePoli' LIMIT 1";
					$execPoliRS = mysqli_query($dbrsud,$poliRS);
					if(mysqli_num_rows($execPoliRS)>0){
						$resPoliRS = (object)$execPoliRS->fetch_assoc();
						$kodePoli = $resPoliRS->kdpoli_rs;
					}
					if($caraBayar=='BPJS'){
						$kodeAss = '1008';
					}else{
						$kodeAss = '1001';
					}
					$insertRegis = "
						INSERT INTO tr_registrasi (TransReg,No_Register,Tgl_Register,Jam_Register,No_RM,Nama_Pasien,AlamatPasien,Umur,Kode_Ass,Kode_Poli1,JenisKel,Rujukan,NoSEP,NoPeserta,Biaya_Registrasi,Status,NamaAsuransi,Japel,JRS,TipeReg,SudahCetak,BayarPendaftaran,Tgl_Lahir,isKaryawan,isProcessed,isPasPulang,Jenazah)
						VALUES('RE','$noUrut','$tglRegis','$jam','$noRM','$nama','$alamat','$umurReg','$kodeAss','$kodePoli','$jenisKel',null,null,'$nomorKartu','0','Belum Dibayar','$caraBayar','0','0','REG','N','N','$tglLahir','N','N','N','N')
					";
					// $resInsertRegis = mysqli_query($dbrsud,$insertRegis) or die(mysqli_error($dbrsud));
					$resInsertRegis = mysqli_query($dbrsud,$insertRegis);
					if(!$resInsertRegis){
						return false;
					}

					$cekFilling = "SELECT * FROM filling WHERE no_rm='$noRM' AND tgl_periksa='$tglBerobat' LIMIT 1";
					$execCekFilling = mysqli_query($wablas,$cekFilling);
					if(mysqli_num_rows($execCekFilling)==0){
						$inFilling = "
							INSERT INTO filling (no_rm,tgl_periksa,status) VALUES('$noRM','$tglBerobat','dicari')
						";
						// $execInFilling = mysqli_query($wablas,$inFilling) or die(mysqli_error($wablas));
						$execInFilling = mysqli_query($wablas,$inFilling);
						if(!$execInFilling){
							return false;
						}
						return $execInFilling==1?"filling has been created":$execInFilling;
					}else{
						return "duplikat filling";
					}
				}else{
					return "duplikat registrasi";
				}
			}else{
				return "kosong";
			}
		}
	}

	public function randomDokter(Request $request){
		$apm = $request->apm_conn;
		$rsu = $request->rsu_conn;
		$date = date('Y-m-d',strtotime($request->tanggal_periksa));
		$where = "date = '$date' AND is_active = true AND kode_poli_rs='$request->kode_poli'";

		if($request->jenis_pembayaran=='BPJS'){ # Jika pasien BPJS
			$where.= " AND is_bpjs = true";
		}
		if($request->kode_poli_bpjs=='HDL'){ # Hemodialisa hanya ada jadwal dengan kode 16454
			$where.= " AND kode_dokter = '16454'";
		}

		// $exec = mysqli_query($apm,$query) or die(mysqli_error($apm));
		$exec = mysqli_query($apm,"SELECT * FROM jadwal_dokter_internal WHERE $where");
		if(mysqli_num_rows($exec)>0){
			$result = $exec->fetch_all(MYSQLI_ASSOC);
			// $data = $exec->fetch_all(MYSQLI_BOTH);
			// $data = $exec->free_result();
			// $apm->close();
			$filter = array_values(array_filter($result,fn($item)=>$item['status_pilih']==false));
			if(count($filter)>0){
				$dokter = (object)$filter[mt_rand(0, count($filter)-1)];
				mysqli_query($apm,"UPDATE jadwal_dokter_internal SET status_pilih=true WHERE id='$dokter->id'");
				return json_encode([
					'metadata' => [
						'code' => 200,
						'message' => 'Ok'
					],
					'response' => $dokter,
				]);
			}

			# Jika tidak ada, lakukan update "status_pilih", set menjadi false, kemudian pilih ulang
			mysqli_query($apm,"UPDATE jadwal_dokter_internal SET status_pilih=false WHERE $where");
			$exec = mysqli_query($apm,"SELECT * FROM jadwal_dokter_internal WHERE $where");
			$result = $exec->fetch_all(MYSQLI_ASSOC);
			$filter = array_values(array_filter($result,fn($item)=>$item['status_pilih']==false));
			if(count($filter)>0){
				$dokter = (object)$filter[mt_rand(0, count($filter)-1)];
				mysqli_query($apm, "UPDATE jadwal_dokter_internal SET status_pilih=true WHERE id='$dokter->id'");
				return json_encode([
					'metadata' => [
						'code' => 200,
						'message' => 'Ok'
					],
					'response' => $dokter,
				]);
			}
		}
		return json_encode([
			'metadata' => [
				'code' => 204,
				'message' => 'No content'
			]
		]);
	}

	public function convertBPJStoRS(Request $request){
		$result = mysqli_query($request->rsu_conn,"SELECT * FROM mapping_poli_bridging WHERE kdpoli='$request->kode_poli'");
		$request->merge(['kode_poli' => mysqli_num_rows($result)>0 ? $result->fetch_assoc()['kdpoli_rs'] : $request->kode_poli]);
	}
}