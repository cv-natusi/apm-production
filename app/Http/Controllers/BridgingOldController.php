<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Libraries\Formatters;
use App\Http\Libraries\Requestor;
use App\Http\Controllers\Controller;
use App\Http\Models\Loket;
use App\Http\Models\Sep;
use App\Http\Models\Poli;
use App\Http\Models\Antrian;
use App\Http\Models\Bridgingpoli;
use App\Http\Models\Customer;
use App\Http\Models\Setupall;
use App\Http\Models\Register;
use App\Http\Models\Tracer;
use App\Http\Models\Rawatjalan;
use App\Http\Models\Historypasien;
use App\Http\Models\diagnosaBpjs;
use App\Http\Models\Rsu_Bridgingpoli;
use App\Http\Models\Rsu_setupall;
use App\Http\Models\Rsu_Historypasien;
use App\Http\Models\Rsu_Register;
use App\Http\Models\Rsu_Tracer;
use App\Http\Models\rsu_poli;
use App\Http\Models\Rsu_Rawatjalan;
use App\Http\Models\rsu_customer;
use App\Http\Models\rsu_diagnosaBpjs;
use App\Http\Models\Rsu_cc;
use App\Http\Models\CC;
use App\Http\Models\RiwayatRegistrasi;
use App\Http\Models\RawatJalanTindakan;
use App\Http\Models\Rsu_RiwayatRegistrasi;
use App\Http\Models\Rsu_RawatJalanTindakan;
use App\Http\Models\rsu_dokter_bridging;
use App\Http\Models\VclaimPengajuanSep;
use Redirect, Validator, DB, Auth;

class BridgingController extends Controller
{
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
	public function main(Request $request){
		
		$this->data['classtutup'] = 'sidebar-collapse';
		// $this->data['poli'] = Poli::all();
        // $nosisaantrian = Loket::where('tgl_antrian',date('Y-m-d'))->where('status',0)->count('id');
        // $this->data['sisaantrian'] = ($nosisaantrian != 0) ? $nosisaantrian : 0;
        // $this->data['noantriannow']= Loket::where('tgl_antrian',date('Y-m-d'))->where('status',0)->first();
        // $this->data['jenispasien'] = Rsu_Setupall::where('groups','asuransi')->whereNotIn('nilaichar',['UMUM','JAMKESDA','PT POS','PT MENTARI','ACC DIREKTUR','JAMKESMAS','JAMSOSTEK','PT KAI','INHEALTH','PERUSAHAAN','JAMKESPROV','JASA RAHARJA','JKD KAB MJK','RENC BPJS','AMBIL OBAT'])->get(); // db local
        // $this->data['jenispasien'] = Setupall::where('groups','asuransi')
		//db rsu
		// $this->data['poli'] = rsu_poli::all();
		$this->data['jenispasien'] = Rsu_setupall::where('groups','asuransi')->whereNotIn('nilaichar',['UMUM','JAMKESDA','PT POS','PT MENTARI','ACC DIREKTUR','JAMKESMAS','JAMSOSTEK','PT KAI','INHEALTH','PERUSAHAAN','JAMKESPROV','JASA RAHARJA','JKD KAB MJK','RENC BPJS','AMBIL OBAT'])->get();

		return view('Admin.bridging.main')->with('data', $this->data);
	}

	public function trymain(Request $request){
		$this->data['classtutup'] = 'sidebar-collapse';
		//db rsu
		$this->data['poli'] = rsu_poli::all();
		$this->data['jenispasien'] = Rsu_setupall::where('groups','asuransi')->whereNotIn('nilaichar',['UMUM','JAMKESDA','PT POS','PT MENTARI','ACC DIREKTUR','JAMKESMAS','JAMSOSTEK','PT KAI','INHEALTH','PERUSAHAAN','JAMKESPROV','JASA RAHARJA','JKD KAB MJK','RENC BPJS','AMBIL OBAT'])->get();

		return view('Admin.bridging.try_main')->with('data', $this->data);
	}

	public function carifromrs(Request $request){
		$content = view('Admin.bridging.carifromrs')->render();
		return ['status' => 'success', 'content' => $content];
	}

	public function caripasienrs(Request $request){
		$gopage = ($request->gopage) ? $request->gopage : 0;
		if ($request->gopage != 0) {
			$start = ($request->gopage - 1) * 15;
		}else{
			$start = ($request->start) ? $request->start : 0;
		}
		$end = ($request->end) ? $request->end : 15;
		// db rsu
		$data = rsu_customer::where('FieldCust1','like','%'.$request->key.'%')->orWhere('KodeCust','like','%'.$request->key.'%')->orWhere('NamaCust','like','%'.$request->key.'%')->where('Alamat','like','%'.$request->alamat.'%')->offset($start)->limit($end)->get();
		$sum = rsu_customer::where('FieldCust1','like','%'.$request->key.'%')->orWhere('KodeCust','like','%'.$request->key.'%')->orWhere('NamaCust','like','%'.$request->key.'%')->where('Alamat','like','%'.$request->alamat.'%')->count();

		$return = ['status'=>'success','code'=>200,'data'=>$data,'sum'=>$sum,'gopage'=>$gopage];
		return response()->json($return);
	}

	public function getpasienrs(Request $request){
		$data = rsu_customer::find($request->key); // db rsu
		$return = ['status'=>'success','code'=>200,'data'=>$data];
		return response()->json($return);
	}

	public function cariformpolirs(Request $request){
		$content = view('Admin.bridging.cariformpolirs')->render();
		return ['status' => 'success', 'content' => $content];
	}

	public function caripolirs(Request $request){
		$polibridging = Bridgingpoli::select('kdpoli_rs')->get();
		$gopage = ($request->gopage) ? $request->gopage : 0;
		if ($request->gopage != 0) {
			$start = ($request->gopage - 1) * 15;
		}else{
			$start = ($request->start) ? $request->start : 0;
		}
		$end = ($request->end) ? $request->end : 15;
		// modal data rsu
		$data = DB::connection('dbrsud')->table('tm_poli as p')->join('mapping_poli_bridging as bp','bp.kdpoli_rs','=','p.KodePoli')->orWhere('KodePoli','like','%'.$request->key.'%')->orWhere('NamaPoli','like','%'.$request->key.'%')->offset($start)->limit($end)->get();
		$sum = DB::connection('dbrsud')->table('tm_poli as p')->join('mapping_poli_bridging as bp','bp.kdpoli_rs','=','p.KodePoli')->orWhere('KodePoli','like','%'.$request->key.'%')->orWhere('NamaPoli','like','%'.$request->key.'%')->count();

		$return = ['status'=>'success','code'=>200,'data'=>$data,'sum'=>$sum,'gopage'=>$gopage];
		return response()->json($return);
	}

	public function getpolinama(Request $request){
		// rsu
		$data = DB::connection('dbrsud')->table('tm_poli as p')->join('mapping_poli_bridging as bp','bp.kdpoli_rs','=','p.KodePoli')->where('NamaPoli','=',$request->key)->first();

		$return = ['status'=>'success','code'=>200,'data'=>$data];
		return response()->json($return);
	}

	public function cariformrmrs(Request $request){
		$content = view('Admin.bridging.carifromrmrs')->render();
		return ['status' => 'success', 'content' => $content];
	}

	public function carifromdiagnosars(Request $request)
	{
		$content = view('Admin.bridging.carifromdiagnosars')->render();
		return ['status' => 'success', 'content' => $content];
	}

	public function cariDiagnosars(Request $request)
	{
		$gopage = ($request->gopage) ? $request->gopage : 0;
		if ($request->gopage != 0) {
			$start = ($request->gopage - 1) * 15;
		}else{
			$start = ($request->start) ? $request->start : 0;
		}
		$end = ($request->end) ? $request->end : 15;
		// db rsu
		$data = rsu_diagnosaBpjs::where('KodeICD','like','%'.$request->key.'%')->orWhere('Diagnosa','like','%'.$request->key.'%')->offset($start)->limit($end)->get();
		$sum = rsu_diagnosaBpjs::where('KodeICD','like','%'.$request->key.'%')->orWhere('Diagnosa','like','%'.$request->key.'%')->count();

		$return = ['status'=>'success','code'=>200,'data'=>$data,'sum'=>$sum,'gopage'=>$gopage];
		return response()->json($return);
	}

	/*
	*/
	public function insertsep(Request $request){
		// return $request->all();
		$idantri = $request->id_antrian;
		$hitungRujukan = Rsu_Register::where('Rujukan', $request->no_rujukan)->count();
		if (!empty($request->no_rm)) {
			$updatenik = rsu_customer::where('KodeCust',$request->no_rm)->first();
			if (!empty($updatenik)) {
				$updatenik->NoKtp = $request->nonik;
				$updatenik->save();
			}
		}
		$rules = [
			'nokartu'=>'required',
			'tgl_sep'=>'required',
			'rawat'=>'required',
			// 'kelasrawat'=>'required',
			'jenisPeserta'=>'required',
			// 'katarak'=>'required',
			'no_rm'=>'required',
			'tgl_rujukan'=>'required',
			// 'no_rujukan'=>'required',
			'ppk_rujukan' =>'required',
			'catatan'=>'required',
			'diagnosa'=>'required',
			// 'poli' => 'required',
			// 'cob'=>'required',
			'tujuan_kunjugan'=>'required',
			'statuslaka'=>'required',
			// 'jenisPeserta'=>'required',
		];
		if ($request->jnsRujukan == 'nonIgd') {
			$rules['no_rujukan'] = 'required';
		}
		if ($request->rawat == '2') {
		  $rules['poli'] = 'required';
		}
		$messages = [
			'required' => 'kolom harus di isi',
		];

		$valid = Validator::make($request->all(), $rules,$messages);
		if($valid->fails()){
			return $valid->messages();
			// return ['status'=>'error', 'code'=> '400', 'messages'=>'Periksa kolom inputan !'];
		}else{
			$namauser = Auth::user()->name_user;
			$jaminan = '';
			// for($i=0; $i<count($request->penjamin); $i++) {
			// 	if($i == 0 ){
			// 		$jaminan .= ''.$request->penjamin[$i];
			// 	}else{
			// 		$jaminan .= ','.$request->penjamin[$i];
			// 	}
			// }

			if ($request->kdDpjp != '') {
				$noSurat = $request->no_surat;
			}else{
				$noSurat = '';
			}

			date_default_timezone_set('Asia/Jakarta');
			// $asalRujukan = ($request->rawat == '1' || $request->ppk_rujukan == '1320R001') ? '2' : '1';
			$asalRujukan = $request->asal_rujukan;
			$data =array(
				"request"=>[
					"t_sep"=> [
						"noKartu"=> $request->nokartu,
						"tglSep"=> $request->tgl_sep,
						"ppkPelayanan"=> "1320R001",  // kode faskes pemberi pelayanan
						"jnsPelayanan"=> $request->rawat,
						"klsRawat"=> [
							"klsRawatHak"=>$request->kelasrawat,
							"klsRawatNaik"=>($request->naikKelasRawat != null) ? $request->naikKelasRawat:"",
							"pembiayaan"=>($request->pembiayaan != null) ? $request->pembiayaan:"",
							"penanggungJawab"=>($request->namaPenanggungJawab != null) ? $request->namaPenanggungJawab:"",
						],
						"noMR"=> $request->no_rm,
						"rujukan"=> [
							"asalRujukan"=> $asalRujukan,
							"tglRujukan"=> date('Y-m-d', strtotime($request->tgl_rujukan)),
							"noRujukan"=> $request->no_rujukan,
							"ppkRujukan"=> $request->ppk_rujukan
						],
						"catatan"=> ($request->catatan) ? $request->catatan:"",
						"diagAwal"=> $request->diagnosa,
						"poli"=> [
							"tujuan"=> ($request->rawat == '2') ? $request->poli : '',
							"eksekutif"=> "0"
						],
						"cob"=> [
							"cob" => ($request->cob) ? $request->cob : '0',
						],
						"katarak" => [
							"katarak" => ($request->katarak) ? $request->katarak : '0',
						],
						"jaminan"=> [ // default 0; if 1 pilihan
							"lakaLantas" => $request->statuslaka,
							"noLP" => ($request->statuslaka != '0') ? $request->no_lp : '',
							"penjamin" => [
								'tglKejadian' => ($request->statuslaka != '0') ? $request->tgl_laka : '',
								'keterangan' => ($request->statuslaka != '0') ? $request->keterangan_laka : '',
								'suplesi' => [
									'suplesi' => ($request->statuslaka != '0') ? $request->suplesi : '',
									'noSepSuplesi' => ($request->suplesi != '0') ? $request->nosep_suplesi : '',
									'lokasiLaka' => [
										'kdPropinsi' => ($request->statuslaka != '0') ? $request->kdProvinsi : '',
										'kdKabupaten' => ($request->statuslaka != '0') ? $request->kdKabupaten : '',
										'kdKecamatan' => ($request->statuslaka != '0') ? $request->kdKecamatan : ''
									]
								]
							]
						],
						"tujuanKunj"=> $request->tujuan_kunjugan,
						"flagProcedure" => ($request->prosedur_bpjs != null) ? $request->prosedur_bpjs : "",
						"kdPenunjang" => ($request->penunjang_bpjs != null) ? $request->penunjang_bpjs : "",
						// "assesmentPel" => ($request->tujuan_kunjugan == '0') ? (($hitungRujukan > 0) ? $request->assesment_bpjs : "") : $request->assesment_bpjs,
						"assesmentPel" => ($request->tujuan_kunjugan == '0') ? (($request->assesment_bpjs != null) ? $request->assesment_bpjs : "") : $request->assesment_bpjs,
						"skdp" => [
							"noSurat" => $noSurat,
							"kodeDPJP" => ($noSurat != '') ? $request->kdDpjp : '',
						],
						"dpjpLayan"=>($request->rawat == '2')?$request->dpjpLayan:'',
						"noTelp"=> ($request->notelp) ? $request->notelp : '000000000000',
						"user"=> $namauser
					]
				]
			);
			// return $data;

			$url=$this->url."/SEP/2.0/insert"; //url web dev service bpjs
			// $url="https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/SEP/2.0/insert"; //url web rilis baru service bpjs

			// create signature
			$consID     = $this->consid; //customer ID RS
			$secretKey  = $this->secretkey; //secretKey RS
			$datas = json_encode($data);
			$sendBpjs = $datas;
			$method = 'POST';
			date_default_timezone_set('UTC');
			$stamp      = strval(time()-strtotime('1970-01-01 00:00:00'));
			$data       = $consID.'&'.$stamp;

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
					"x-cons-id: 21095",
					"x-signature: ".$encodedSignature,
					"x-timestamp: ".$stamp."",
					// "user_key: 2079632035f01e757d81a8565b074768",
					"user_key: ".$this->userkey,
				),
			));
			$response = curl_exec($ch);
			$err = curl_error($ch);
			curl_close($ch);
			if ($err) {
				return "cURL Error #:" . $err;
			} else {
				// return $response;
				$respon = json_decode($response,true);
				if ($respon['metaData']['code'] != 200 && !preg_match('/belum dipulangkan di RSUD DR. W. SUDIROHUSODO/i', $respon['metaData']['message'])) {
					return ['status'=>'error', 'code'=> $respon['metaData']['code'], 'messages'=>$respon['metaData']['message']];
				}
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
				if ($respon['response'] == null) {
					$cek = explode('No.SEP ', $respon['metaData']['message']);
					if (count($cek) > 1) {
						$nosep = substr($cek[1], 0,19);
						// $pulang = preg_match('/belum dipulangkan di RSUD DR. W. SUDIROHUSODO/i', $respon['metaData']['message']);
						$pulang = preg_match('/telah mendapat Pelayanan R.Inap/i', $respon['metaData']['message']);
						$rsu = preg_match('/1320R001/i', $respon['metaData']['message']);
						if($pulang && $rsu){
							$ek = 'update';
						}else{
							$ek = "";
						}
						return ['status'=>'error', 'code'=> $respon['metaData']['code'], 'messages'=>$respon['metaData']['message'], 'update' => $ek, 'nobpjs'=>$nosep, 'tingkat'=>'1', 'sendBpjs' => $sendBpjs];
					}else{
						$tingkatRujuk = preg_match('/Asal rujukan Harus Diisi 2/i', $respon['metaData']['message']);
						if ($tingkatRujuk) {
							return ['status'=>'error', 'code'=> $respon['metaData']['code'], 'messages'=>$respon['metaData']['message'], 'update' => 'tingkat', 'nobpjs'=>'', 'tingkat'=>'2', 'sendBpjs' => $sendBpjs];
						}else{
							return ['status'=>'error', 'code'=> $respon['metaData']['code'], 'messages'=>$respon['metaData']['message'], 'update' => '', 'nobpjs'=>'', 'tingkat'=>'1', 'sendBpjs' => $sendBpjs];
						}
					}
				}else{
					$poli = Rsu_Bridgingpoli::where('kdpoli', $request->poli)->select('kdpoli_rs')->first(); //get poli rs -> DB Rsu
					$asu = Rsu_setupall::where('subgroups',$request->jenisPeserta)->select('nilaichar')->first(); //get nama asuransi RS -> DB RSU

					// Insert tabel Register
					date_default_timezone_set('Asia/Jakarta');
					$reg = new Rsu_Register; // db rsu
					$reg->TransReg = 'RE';
					$tg = date('y');
					$tg =$tg.'2';
					$thn = date('Y'); $mo = date('m'); $da = date('d');
					$urut = Rsu_Register::whereRaw("(YEAR(Tgl_Register) = $thn) AND LEFT(No_Register,3) = $tg ")->orderby('No_Register','DESC')->first(); //db rsu use

					if($urut){
						$nourut = $urut->No_Register + 1;
					}else{
						$nourut = date('y').'20000001';
					}

					$reg->No_Register = $nourut;
					$reg->Tgl_Register = date('Y-m-d H:i:s');
					$reg->Jam_Register = date('H:i:s');
					$reg->No_RM =  $request->no_rm;
					$reg->Nama_Pasien = $request->nama;
					$reg->AlamatPasien = $request->alamat;
					$reg->Umur = $request->umur;
					$reg->Kode_Ass = $request->jenisPeserta;
					$reg->Kode_Poli1 = isset($poli->kdpoli_rs) ? $poli->kdpoli_rs : '';
					$reg->JenisKel = $request->sex;
					$reg->Rujukan = $request->no_rujukan;
					$reg->NoSEP = $respon2['sep']['noSep'];
					$reg->NoPeserta = $request->nokartu;
					$reg->Biaya_Registrasi = 5000;
					$reg->Status = 'Belum Dibayar';
					$reg->NamaAsuransi = isset($asu->nilaichar) ? $asu->nilaichar : '';
					$reg->Japel = 3000;
					$reg->JRS = 2000;
					$reg->TipeReg = 'REG';
					$reg->SudahCetak = 'N';
					$reg->BayarPendaftaran = 'N';
					$reg->Tgl_Lahir = $request->tgl_lahir;
					$reg->isKaryawan = ($request->karyawan) ? $request->karyawan : 'N';
					$reg->isProcessed = 'N';
					$reg->isPasPulang = 'N';
					$reg->Jenazah     = 'N';
					$reg->save();

					if(!empty($request->apm)){
						// db rsu
						$updateCC = Rsu_cc::where('norm',$request->no_rm)
							->where('tanggal',date('d-m-Y'))
							->where('nourut', $request->noUrut)
							->update(['status' => 'Sudah']);
					}

					$methodRujukan = 'GET';
					$paramsRujukan = '';
					$portRujukan = '80';
					if ($request->kdDpjp == '') {
						$urlRujukan = $this->url."/Rujukan/".$request->no_rujukan; //url web service bpjs develop
						// $urlRujukan = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/Rujukan/".$request->no_rujukan; //url web service bpjs rilis
						$resultRujukan = Requestor::set_new_curl_bridging($urlRujukan, $paramsRujukan, $methodRujukan, $consID, $secretKey, $portRujukan,'',''); // bridging data peserta bpjs
						if ($resultRujukan === false) {
							return ['status' => 'error', 'message' => 'Tidak Dapat Terhubung ke Server !!'];
						} else {
							$resultRujukans = [
								'metaData' => json_decode($resultRujukan['metaData']),
								'response' => json_decode($resultRujukan['response']),
							];
							if ($resultRujukans['response'] == '') {
								$urlRujukan2 = $this->url."/Rujukan/RS/".$request->noRujuk; //url web service bpjs develop
								// $urlRujukan2 = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/Rujukan/RS/".$request->no_rujukan; //url web service bpjs rilis
								$resultRujukan2 = Requestor::set_new_curl_bridging($urlRujukan2, $paramsRujukan, $methodRujukan, $consID, $secretKey, $portRujukan,'',''); // bridging data peserta bpjs
								if ($resultRujukan2 === false) {
									return ['status' => 'error', 'messages' => 'Tidak Dapat Terhubung ke Server !!'];
								}else{
									$resultRujukans = [
										'metaData' => json_decode($resultRujukan2['metaData']),
										'response' => json_decode($resultRujukan2['response']),
									];
									if ($resultRujukans['response'] != '') {
										$prosHas = 1;
									}else{
										$prosHas = 0;
									}
								}
							}else{
								$prosHas = 1;
							}
							if ($prosHas == 1) {
								$resKodePoli = $resultRujukans['response']->rujukan->poliRujukan->kode;
							}else{
								$messages = $resultRujukans['metaData']['message'];
								$return = ['status' => 'error', 'message' => $messages];
							}
						}
						$dokterBridg = rsu_dokter_bridging::where('polibpjs',$resKodePoli)->first();
						$urlDokter = $this->url."/referensi/dokter/pelayanan/".$request->rawat."/tglpelayanan/".date('Y-m-d')."/Spesialis/".$resKodePoli; // url develop
						// $urlDokter = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/referensi/dokter/pelayanan/".$request->rawat."/tglpelayanan/".date('Y-m-d')."/Spesialis/".$resKodePoli; // url rilis
						$kdPoliRw = $resKodePoli;
					}else{
						$kdDok = ($request->jnsRujukan == 'igd') ? '1' : $request->rawat;
						$poli = ($request->poli == 'HDL') ? 'INT' : $request->poli;
						if ($request->jnsRujukan == 'igd') {
							$dokterBridg = rsu_dokter_bridging::where('polibpjs', $poli)->first();
						} else {
							$dokterBridg = rsu_dokter_bridging::where('polibpjs', $poli)->first();
						}
						$urlDokter = $this->url."/referensi/dokter/pelayanan/".$kdDok."/tglpelayanan/".date('Y-m-d')."/Spesialis/".$poli; // url develop
						// $urlDokter = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/referensi/dokter/pelayanan/".$request->rawat."/tglpelayanan/".date('Y-m-d')."/Spesialis/".$poli; // url rilis
						$kdPoliRw = $poli;
					}
					$resultDokter = Requestor::set_new_curl_bridging($urlDokter, $paramsRujukan, $methodRujukan, $consID, $secretKey, $portRujukan,'','');
					$hslDokter = [
						'metaData' => json_decode($resultDokter['metaData']),
						'response' => json_decode($resultDokter['response']),
					];
					$kodeDPJPRiw = '';
					$namaDPJPRiw = '';
					// IF IGD
					if ($request->jnsRujukan == 'igd') {
						foreach ($hslDokter['response']->list as $key) {
							if (strtolower($key->kode) == $request->id_dokterDpjpLayan) {
								$kodeDPJPRiw = $key->kode;
								$namaDPJPRiw = $key->nama;
							}
						}
					}elseif ($request->rawat == 1) {
						$kodeDPJPRiw = $request->kdDpjp;
						$namaDPJPRiw = $request->dpjp_rujuk;
					} else {
						// foreach ($hslDokter['response']->list as $key) {
						// 	if (strtolower($key->nama) == strtolower($dokterBridg->dokter)) {
						// 		$kodeDPJPRiw = $key->kode;
						// 		$namaDPJPRiw = $key->nama;
						// 	}
						// }
						$kodeDPJPRiw = $request->kdDpjp;
						$namaDPJPRiw = $request->dpjp_rujuk;
					}

					$addHistory = new Rsu_RiwayatRegistrasi; // db rsu
					$addHistory->No_Register = $nourut;
					$addHistory->no_surat = $noSurat;
					$addHistory->no_rm = $request->no_rm;
					$addHistory->no_rujukan = $request->no_rujukan;
					$addHistory->NoSEP = $respon2['sep']['noSep'];
					$addHistory->kode_dpjp = $kodeDPJPRiw;
					$addHistory->nama_dpjp = $namaDPJPRiw;
					$addHistory->poli_bpjs = $kdPoliRw;
					$addHistory->save();

					$regi = ($reg) ? 'reg berhasil':'reg gagal';
					if(!empty($request->apm)){
						$updateCC = Rsu_cc::where('norm',$request->no_rm) // db rsu
						->where('tanggal',date('d-m-Y'))
						->where('nourut', $request->noUrut)
						->update(['status' => 'Sudah']);
					}

					$noarsip = Rsu_Register::whereBetween('Tgl_Register',[date('Y-m-d').' 00:00:00',date('Y-m-d').' 23.59.59'])->count('No_Register'); // db rsu
					$noarsip = ($noarsip == 0 ) ? '1' : $noarsip;

					// GET ANTRIAN
					$getAntrian = Antrian::where('id', $idantri)->first();

					return [
						'status'=>'success',
						'code'=>'200',
						'messages'=>"return",
						'nosep'=> $respon2['sep']['noSep'],
						'sep'=>$respon2,
						'reg'=>$regi,
						'noarsip'=>$noarsip,
						'noKontrol' => $nourut,
						'dtRegis' => $reg,
						'sendBpjs' => $sendBpjs,
						'antrian' => $getAntrian
					];
				}
			}
		}
	}

	// public function insertsep(Request $request){
	// 	// return $request->all();
	// 	$idantri = $request->id_antrian;

	// 	// $hitungRujukan = Rsu_Register::where('Rujukan', $request->no_rujukan)->count();
	// 	// if (!empty($request->no_rm)) {
	// 	// 	$updatenik = rsu_customer::where('KodeCust',$request->no_rm)->first();
	// 	// 	if (!empty($updatenik)) {
	// 	// 		$updatenik->NoKtp = $request->nonik;
	// 	// 		$updatenik->save();
	// 	// 	}
	// 	// }
	// 	// $rules = [
	// 	// 	'nokartu'=>'required',
	// 	// 	'tgl_sep'=>'required',
	// 	// 	'rawat'=>'required',
	// 	// 	// 'kelasrawat'=>'required',
	// 	// 	'jenisPeserta'=>'required',
	// 	// 	// 'katarak'=>'required',
	// 	// 	'no_rm'=>'required',
	// 	// 	'tgl_rujukan'=>'required',
	// 	// 	// 'no_rujukan'=>'required',
	// 	// 	'ppk_rujukan' =>'required',
	// 	// 	'catatan'=>'required',
	// 	// 	'diagnosa'=>'required',
	// 	// 	// 'poli' => 'required',
	// 	// 	// 'cob'=>'required',
	// 	// 	'tujuan_kunjugan'=>'required',
	// 	// 	'statuslaka'=>'required',
	// 	// 	// 'jenisPeserta'=>'required',
	// 	// ];
	// 	// if ($request->jnsRujukan == 'nonIgd') {
	// 	// 	$rules['no_rujukan'] = 'required';
	// 	// }
	// 	// if ($request->rawat == '2') {
	// 	//   $rules['poli'] = 'required';
	// 	// }
	// 	// $messages = [
	// 	// 	'required' => 'kolom harus di isi',
	// 	// ];

	// 	// $valid = Validator::make($request->all(), $rules,$messages);
	// 	// if($valid->fails()){
	// 	// 	return $valid->messages();
	// 	// 	// return ['status'=>'error', 'code'=> '400', 'messages'=>'Periksa kolom inputan !'];
	// 	// }else{
	// 		// $namauser = Auth::user()->name_user;
	// 		// $jaminan = '';
	// 		// // for($i=0; $i<count($request->penjamin); $i++) {
	// 		// // 	if($i == 0 ){
	// 		// // 		$jaminan .= ''.$request->penjamin[$i];
	// 		// // 	}else{
	// 		// // 		$jaminan .= ','.$request->penjamin[$i];
	// 		// // 	}
	// 		// // }

	// 		// if ($request->kdDpjp != '') {
	// 		// 	$noSurat = $request->no_surat;
	// 		// }else{
	// 		// 	$noSurat = '';
	// 		// }

	// 		// date_default_timezone_set('Asia/Jakarta');
	// 		// // $asalRujukan = ($request->rawat == '1' || $request->ppk_rujukan == '1320R001') ? '2' : '1';
	// 		// $asalRujukan = $request->asal_rujukan;
	// 		// $data =array(
	// 		// 	"request"=>[
	// 		// 		"t_sep"=> [
	// 		// 			"noKartu"=> $request->nokartu,
	// 		// 			"tglSep"=> $request->tgl_sep,
	// 		// 			"ppkPelayanan"=> "1320R001",  // kode faskes pemberi pelayanan
	// 		// 			"jnsPelayanan"=> $request->rawat,
	// 		// 			"klsRawat"=> [
	// 		// 				"klsRawatHak"=>$request->kelasrawat,
	// 		// 				"klsRawatNaik"=>($request->naikKelasRawat != null) ? $request->naikKelasRawat:"",
	// 		// 				"pembiayaan"=>($request->pembiayaan != null) ? $request->pembiayaan:"",
	// 		// 				"penanggungJawab"=>($request->namaPenanggungJawab != null) ? $request->namaPenanggungJawab:"",
	// 		// 			],
	// 		// 			"noMR"=> $request->no_rm,
	// 		// 			"rujukan"=> [
	// 		// 				"asalRujukan"=> $asalRujukan,
	// 		// 				"tglRujukan"=> date('Y-m-d', strtotime($request->tgl_rujukan)),
	// 		// 				"noRujukan"=> $request->no_rujukan,
	// 		// 				"ppkRujukan"=> $request->ppk_rujukan
	// 		// 			],
	// 		// 			"catatan"=> ($request->catatan) ? $request->catatan:"",
	// 		// 			"diagAwal"=> $request->diagnosa,
	// 		// 			"poli"=> [
	// 		// 				"tujuan"=> ($request->rawat == '2') ? $request->poli : '',
	// 		// 				"eksekutif"=> "0"
	// 		// 			],
	// 		// 			"cob"=> [
	// 		// 				"cob" => ($request->cob) ? $request->cob : '0',
	// 		// 			],
	// 		// 			"katarak" => [
	// 		// 				"katarak" => ($request->katarak) ? $request->katarak : '0',
	// 		// 			],
	// 		// 			"jaminan"=> [ // default 0; if 1 pilihan
	// 		// 				"lakaLantas" => $request->statuslaka,
	// 		// 				"noLP" => ($request->statuslaka != '0') ? $request->no_lp : '',
	// 		// 				"penjamin" => [
	// 		// 					'tglKejadian' => ($request->statuslaka != '0') ? $request->tgl_laka : '',
	// 		// 					'keterangan' => ($request->statuslaka != '0') ? $request->keterangan_laka : '',
	// 		// 					'suplesi' => [
	// 		// 						'suplesi' => ($request->statuslaka != '0') ? $request->suplesi : '',
	// 		// 						'noSepSuplesi' => ($request->suplesi != '0') ? $request->nosep_suplesi : '',
	// 		// 						'lokasiLaka' => [
	// 		// 							'kdPropinsi' => ($request->statuslaka != '0') ? $request->kdProvinsi : '',
	// 		// 							'kdKabupaten' => ($request->statuslaka != '0') ? $request->kdKabupaten : '',
	// 		// 							'kdKecamatan' => ($request->statuslaka != '0') ? $request->kdKecamatan : ''
	// 		// 						]
	// 		// 					]
	// 		// 				]
	// 		// 			],
	// 		// 			"tujuanKunj"=> $request->tujuan_kunjugan,
	// 		// 			"flagProcedure" => ($request->prosedur_bpjs != null) ? $request->prosedur_bpjs : "",
	// 		// 			"kdPenunjang" => ($request->penunjang_bpjs != null) ? $request->penunjang_bpjs : "",
	// 		// 			// "assesmentPel" => ($request->tujuan_kunjugan == '0') ? (($hitungRujukan > 0) ? $request->assesment_bpjs : "") : $request->assesment_bpjs,
	// 		// 			"assesmentPel" => ($request->tujuan_kunjugan == '0') ? (($request->assesment_bpjs != null) ? $request->assesment_bpjs : "") : $request->assesment_bpjs,
	// 		// 			"skdp" => [
	// 		// 				"noSurat" => $noSurat,
	// 		// 				"kodeDPJP" => ($noSurat != '') ? $request->kdDpjp : '',
	// 		// 			],
	// 		// 			"dpjpLayan"=>($request->rawat == '2')?$request->dpjpLayan:'',
	// 		// 			"noTelp"=> ($request->notelp) ? $request->notelp : '000000000000',
	// 		// 			"user"=> $namauser
	// 		// 		]
	// 		// 	]
	// 		// );
	// 		// return $data;

	// 		// $url=$this->url."/SEP/2.0/insert"; //url web dev service bpjs
	// 		// // $url="https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/SEP/2.0/insert"; //url web rilis baru service bpjs

	// 		// // create signature
	// 		// $consID     = $this->consid; //customer ID RS
	// 		// $secretKey  = $this->secretkey; //secretKey RS
	// 		// $datas = json_encode($data);
	// 		// $sendBpjs = $datas;
	// 		// $method = 'POST';
	// 		// date_default_timezone_set('UTC');
	// 		// $stamp      = strval(time()-strtotime('1970-01-01 00:00:00'));
	// 		// $data       = $consID.'&'.$stamp;

	// 		// $signature = hash_hmac('sha256', $data, $secretKey, true);
	// 		// $encodedSignature = base64_encode($signature);
	// 		// $key = $consID.$secretKey.$stamp;

	// 		// $ch = curl_init();

	// 		// curl_setopt_array($ch, array(
	// 		// 	// CURLOPT_PORT => "8080",
	// 		// 	CURLOPT_URL => $url,
	// 		// 	CURLOPT_RETURNTRANSFER => true,
	// 		// 	CURLOPT_ENCODING => "",
	// 		// 	CURLOPT_MAXREDIRS => 10,
	// 		// 	CURLOPT_TIMEOUT => 30,
	// 		// 	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	// 		// 	CURLOPT_CUSTOMREQUEST => $method,
	// 		// 	CURLOPT_POSTFIELDS => $datas,
	// 		// 	CURLOPT_HTTPHEADER => array(
	// 		// 		"cache-control: no-cache",
	// 		// 		"x-cons-id: 21095",
	// 		// 		"x-signature: ".$encodedSignature,
	// 		// 		"x-timestamp: ".$stamp."",
	// 		// 		// "user_key: 2079632035f01e757d81a8565b074768",
	// 		// 		"user_key: ".$this->userkey,
	// 		// 	),
	// 		// ));
	// 		// $response = curl_exec($ch);
	// 		// $err = curl_error($ch);
	// 		// curl_close($ch);
	// 		// if ($err) {
	// 		// 	return "cURL Error #:" . $err;
	// 		// } else {
	// 		// 	// return $response;
	// 		// 	$respon = json_decode($response,true);
	// 		// 	if ($respon['metaData']['code'] != 200 && !preg_match('/belum dipulangkan di RSUD DR. W. SUDIROHUSODO/i', $respon['metaData']['message'])) {
	// 		// 		return ['status'=>'error', 'code'=> $respon['metaData']['code'], 'messages'=>$respon['metaData']['message']];
	// 		// 	}
	// 		// 	$string = json_encode($respon['response']);
	// 		// 	// FUNGSI DECRYPT
	// 		// 	$encrypt_method = 'AES-256-CBC';
	// 		// 	// hash
	// 		// 	$key_hash = hex2bin(hash('sha256', $key));
	// 		// 	// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
	// 		// 	$iv = substr(hex2bin(hash('sha256', $key)), 0, 16);
	// 		// 	$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key_hash, OPENSSL_RAW_DATA, $iv);
	// 		// 	$value = \LZCompressor\LZString::decompressFromEncodedURIComponent($output);
	// 		// 	$respon2 = json_decode($value,true);
	// 		// 	if ($respon['response'] == null) {
	// 		// 		$cek = explode('No.SEP ', $respon['metaData']['message']);
	// 		// 		if (count($cek) > 1) {
	// 		// 			$nosep = substr($cek[1], 0,19);
	// 		// 			// $pulang = preg_match('/belum dipulangkan di RSUD DR. W. SUDIROHUSODO/i', $respon['metaData']['message']);
	// 		// 			$pulang = preg_match('/telah mendapat Pelayanan R.Inap/i', $respon['metaData']['message']);
	// 		// 			$rsu = preg_match('/1320R001/i', $respon['metaData']['message']);
	// 		// 			if($pulang && $rsu){
	// 		// 				$ek = 'update';
	// 		// 			}else{
	// 		// 				$ek = "";
	// 		// 			}
	// 		// 			return ['status'=>'error', 'code'=> $respon['metaData']['code'], 'messages'=>$respon['metaData']['message'], 'update' => $ek, 'nobpjs'=>$nosep, 'tingkat'=>'1', 'sendBpjs' => $sendBpjs];
	// 		// 		}else{
	// 		// 			$tingkatRujuk = preg_match('/Asal rujukan Harus Diisi 2/i', $respon['metaData']['message']);
	// 		// 			if ($tingkatRujuk) {
	// 		// 				return ['status'=>'error', 'code'=> $respon['metaData']['code'], 'messages'=>$respon['metaData']['message'], 'update' => 'tingkat', 'nobpjs'=>'', 'tingkat'=>'2', 'sendBpjs' => $sendBpjs];
	// 		// 			}else{
	// 		// 				return ['status'=>'error', 'code'=> $respon['metaData']['code'], 'messages'=>$respon['metaData']['message'], 'update' => '', 'nobpjs'=>'', 'tingkat'=>'1', 'sendBpjs' => $sendBpjs];
	// 		// 			}
	// 		// 		}
	// 		// 	}else{
	// 		// 		$poli = Rsu_Bridgingpoli::where('kdpoli', $request->poli)->select('kdpoli_rs')->first(); //get poli rs -> DB Rsu
	// 		// 		$asu = Rsu_setupall::where('subgroups',$request->jenisPeserta)->select('nilaichar')->first(); //get nama asuransi RS -> DB RSU

	// 				// Insert tabel Register
	// 				date_default_timezone_set('Asia/Jakarta');
	// 				$reg = new Rsu_Register; // db rsu
	// 				$reg->TransReg = 'RE';
	// 				$tg = date('y');
	// 				$tg =$tg.'2';
	// 				$thn = date('Y'); $mo = date('m'); $da = date('d');
	// 				$urut = Rsu_Register::whereRaw("(YEAR(Tgl_Register) = $thn) AND LEFT(No_Register,3) = $tg ")->orderby('No_Register','DESC')->first(); //db rsu use

	// 				if($urut){
	// 					$nourut = $urut->No_Register + 1;
	// 				}else{
	// 					$nourut = date('y').'20000001';
	// 				}

	// 				$reg->No_Register = $nourut;
	// 				$reg->Tgl_Register = date('Y-m-d H:i:s');
	// 				$reg->Jam_Register = date('H:i:s');
	// 				$reg->No_RM =  $request->no_rm;
	// 				$reg->Nama_Pasien = $request->nama;
	// 				$reg->AlamatPasien = $request->alamat;
	// 				$reg->Umur = $request->umur;
	// 				$reg->Kode_Ass = $request->jenisPeserta;
	// 				// $reg->Kode_Poli1 = isset($poli->kdpoli_rs) ? $poli->kdpoli_rs : '';
	// 				$reg->Kode_Poli = $request->poli;
	// 				$reg->JenisKel = $request->sex;
	// 				$reg->Rujukan = $request->no_rujukan;
	// 				// $reg->NoSEP = $respon2['sep']['noSep'];
	// 				$reg->NoPeserta = $request->nokartu;
	// 				$reg->Biaya_Registrasi = 5000;
	// 				$reg->Status = 'Belum Dibayar';
	// 				$reg->NamaAsuransi = isset($asu->nilaichar) ? $asu->nilaichar : '';
	// 				$reg->Japel = 3000;
	// 				$reg->JRS = 2000;
	// 				$reg->TipeReg = 'REG';
	// 				$reg->SudahCetak = 'N';
	// 				$reg->BayarPendaftaran = 'N';
	// 				$reg->Tgl_Lahir = $request->tgl_lahir;
	// 				$reg->isKaryawan = ($request->karyawan) ? $request->karyawan : 'N';
	// 				$reg->isProcessed = 'N';
	// 				$reg->isPasPulang = 'N';
	// 				$reg->Jenazah     = 'N';
	// 				$reg->save();

	// 				if(!empty($request->apm)){
	// 					// db rsu
	// 					$updateCC = Rsu_cc::where('norm',$request->no_rm)
	// 						->where('tanggal',date('d-m-Y'))
	// 						->where('nourut', $request->noUrut)
	// 						->update(['status' => 'Sudah']);
	// 				}

	// 				// $methodRujukan = 'GET';
	// 				// $paramsRujukan = '';
	// 				// $portRujukan = '80';
	// 				// if ($request->kdDpjp == '') {
	// 				// 	$urlRujukan = $this->url."/Rujukan/".$request->no_rujukan; //url web service bpjs develop
	// 				// 	// $urlRujukan = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/Rujukan/".$request->no_rujukan; //url web service bpjs rilis
	// 				// 	$resultRujukan = Requestor::set_new_curl_bridging($urlRujukan, $paramsRujukan, $methodRujukan, $consID, $secretKey, $portRujukan,'',''); // bridging data peserta bpjs
	// 				// 	if ($resultRujukan === false) {
	// 				// 		return ['status' => 'error', 'message' => 'Tidak Dapat Terhubung ke Server !!'];
	// 				// 	} else {
	// 				// 		$resultRujukans = [
	// 				// 			'metaData' => json_decode($resultRujukan['metaData']),
	// 				// 			'response' => json_decode($resultRujukan['response']),
	// 				// 		];
	// 				// 		if ($resultRujukans['response'] == '') {
	// 				// 			$urlRujukan2 = $this->url."/Rujukan/RS/".$request->noRujuk; //url web service bpjs develop
	// 				// 			// $urlRujukan2 = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/Rujukan/RS/".$request->no_rujukan; //url web service bpjs rilis
	// 				// 			$resultRujukan2 = Requestor::set_new_curl_bridging($urlRujukan2, $paramsRujukan, $methodRujukan, $consID, $secretKey, $portRujukan,'',''); // bridging data peserta bpjs
	// 				// 			if ($resultRujukan2 === false) {
	// 				// 				return ['status' => 'error', 'messages' => 'Tidak Dapat Terhubung ke Server !!'];
	// 				// 			}else{
	// 				// 				$resultRujukans = [
	// 				// 					'metaData' => json_decode($resultRujukan2['metaData']),
	// 				// 					'response' => json_decode($resultRujukan2['response']),
	// 				// 				];
	// 				// 				if ($resultRujukans['response'] != '') {
	// 				// 					$prosHas = 1;
	// 				// 				}else{
	// 				// 					$prosHas = 0;
	// 				// 				}
	// 				// 			}
	// 				// 		}else{
	// 				// 			$prosHas = 1;
	// 				// 		}
	// 				// 		if ($prosHas == 1) {
	// 				// 			$resKodePoli = $resultRujukans['response']->rujukan->poliRujukan->kode;
	// 				// 		}else{
	// 				// 			$messages = $resultRujukans['metaData']['message'];
	// 				// 			$return = ['status' => 'error', 'message' => $messages];
	// 				// 		}
	// 				// 	}
	// 				// 	$dokterBridg = rsu_dokter_bridging::where('polibpjs',$resKodePoli)->first();
	// 				// 	$urlDokter = $this->url."/referensi/dokter/pelayanan/".$request->rawat."/tglpelayanan/".date('Y-m-d')."/Spesialis/".$resKodePoli; // url develop
	// 				// 	// $urlDokter = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/referensi/dokter/pelayanan/".$request->rawat."/tglpelayanan/".date('Y-m-d')."/Spesialis/".$resKodePoli; // url rilis
	// 				// 	$kdPoliRw = $resKodePoli;
	// 				// }else{
	// 				// 	$kdDok = ($request->jnsRujukan == 'igd') ? '1' : $request->rawat;
	// 				// 	$poli = ($request->poli == 'HDL') ? 'INT' : $request->poli;
	// 				// 	if ($request->jnsRujukan == 'igd') {
	// 				// 		$dokterBridg = rsu_dokter_bridging::where('polibpjs', $poli)->first();
	// 				// 	} else {
	// 				// 		$dokterBridg = rsu_dokter_bridging::where('polibpjs', $poli)->first();
	// 				// 	}
	// 				// 	$urlDokter = $this->url."/referensi/dokter/pelayanan/".$kdDok."/tglpelayanan/".date('Y-m-d')."/Spesialis/".$poli; // url develop
	// 				// 	// $urlDokter = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/referensi/dokter/pelayanan/".$request->rawat."/tglpelayanan/".date('Y-m-d')."/Spesialis/".$poli; // url rilis
	// 				// 	$kdPoliRw = $poli;
	// 				// }
	// 				// $resultDokter = Requestor::set_new_curl_bridging($urlDokter, $paramsRujukan, $methodRujukan, $consID, $secretKey, $portRujukan,'','');
	// 				// $hslDokter = [
	// 				// 	'metaData' => json_decode($resultDokter['metaData']),
	// 				// 	'response' => json_decode($resultDokter['response']),
	// 				// ];
	// 				// $kodeDPJPRiw = '';
	// 				// $namaDPJPRiw = '';
	// 				// // IF IGD
	// 				// if ($request->jnsRujukan == 'igd') {
	// 				// 	foreach ($hslDokter['response']->list as $key) {
	// 				// 		if (strtolower($key->kode) == $request->id_dokterDpjpLayan) {
	// 				// 			$kodeDPJPRiw = $key->kode;
	// 				// 			$namaDPJPRiw = $key->nama;
	// 				// 		}
	// 				// 	}
	// 				// }elseif ($request->rawat == 1) {
	// 				// 	$kodeDPJPRiw = $request->kdDpjp;
	// 				// 	$namaDPJPRiw = $request->dpjp_rujuk;
	// 				// } else {
	// 				// 	// foreach ($hslDokter['response']->list as $key) {
	// 				// 	// 	if (strtolower($key->nama) == strtolower($dokterBridg->dokter)) {
	// 				// 	// 		$kodeDPJPRiw = $key->kode;
	// 				// 	// 		$namaDPJPRiw = $key->nama;
	// 				// 	// 	}
	// 				// 	// }
	// 				// 	$kodeDPJPRiw = $request->kdDpjp;
	// 				// 	$namaDPJPRiw = $request->dpjp_rujuk;
	// 				// }

	// 				// $addHistory = new Rsu_RiwayatRegistrasi; // db rsu
	// 				// $addHistory->No_Register = $nourut;
	// 				// $addHistory->no_surat = $noSurat;
	// 				// $addHistory->no_rm = $request->no_rm;
	// 				// $addHistory->no_rujukan = $request->no_rujukan;
	// 				// $addHistory->NoSEP = $respon2['sep']['noSep'];
	// 				// $addHistory->kode_dpjp = $kodeDPJPRiw;
	// 				// $addHistory->nama_dpjp = $namaDPJPRiw;
	// 				// $addHistory->poli_bpjs = $kdPoliRw;
	// 				// $addHistory->save();

	// 				// $regi = ($reg) ? 'reg berhasil':'reg gagal';
	// 				// if(!empty($request->apm)){
	// 				// 	$updateCC = Rsu_cc::where('norm',$request->no_rm) // db rsu
	// 				// 	->where('tanggal',date('d-m-Y'))
	// 				// 	->where('nourut', $request->noUrut)
	// 				// 	->update(['status' => 'Sudah']);
	// 				// }

	// 				// $noarsip = Rsu_Register::whereBetween('Tgl_Register',[date('Y-m-d').' 00:00:00',date('Y-m-d').' 23.59.59'])->count('No_Register'); // db rsu
	// 				// $noarsip = ($noarsip == 0 ) ? '1' : $noarsip;
	// 				$getAntrian = Antrian::where('id', $idantri)->first();
	// 				return [
	// 					'status'=>'success',
	// 					'code'=>'200',
	// 					'messages'=>"return",
	// 					// 'nosep'=> $respon2['sep']['noSep'],
	// 					// 'sep'=>$respon2,
	// 					// 'reg'=>$regi,
	// 					// 'noarsip'=>$noarsip,
	// 					'noKontrol' => $nourut,
	// 					'dtRegis' => $reg,
	// 					'antrian'=> $getAntrian
	// 					// 'sendBpjs' => $sendBpjs
	// 				];
	// 	// 		}
	// 	// 	}
	// 	// }
	// }

	public function cekcustomer(Request $request){
		return rsu_customer::find('S1212000253');
	}

	// web service
	public function cetaksepservice(Request $request){
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

			$return = ['status'=>'success','code'=>200,'data'=>$data];
			return response()->json($return);
		}elseif ($result['metaData']->code == 201) {
			return $result;
		}else {
			return $result;
		}
	}

	//update tanggal pulang
	public function saveChange(Request $request){
		$url = $this->url."/SEP/2.0/updtglplg"; //url web dev service bpjs
		// $url = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/Sep/updtglplg"; //url web rilis baru service bpjs
		// $url = "http://api.bpjs-kesehatan.go.id:8080/vclaim-rest/Sep/updtglplg"; //url web service bpjs
		$consID     = $this->consid; //customer ID RS
		$secretKey  = $this->secretkey; //secretKey RS
		$method = 'PUT';
		$port = '80';

		$namauser = Auth::user()->name_user;
		$data =array(
			"t_sep"=> [
				"noSep"=> $request->nobpjspulang,
				"statusPulang"=> $request->statusPulang,
				"tglMeninggal"=> $request->tglMeninggal,
				"noSuratMeninggal"=> $request->noSuratMeninggal,
				"tglPulang"=> date('Y-m-d', strtotime($request->tglpulang)),
				"noLPManual"=> $request->noLPManual,
				// "ppkPelayanan"=>"1320R001",
				"user" => $namauser,
			],
		);
		$params = json_encode(array('request' => $data));

		$result = Requestor::set_new_curl_bridging($url, $params, $method, $consID, $secretKey, $port,'',''); // bridging data peserta bpjs
		if ($result === false) {
			echo "Tidak dapat menyambung ke server";
		} else {
			$respon = [
				'metaData' => json_decode($result['metaData']),
				'response' => json_decode($result['response']),
			];
			return $respon;
		}
	}

	// get history pasien
	public function gethistorypasien(Request $request){
		// $history = Historypasien::where('no_rm',$request->norm)->limit(10)->get(); // db lokal site
		// $history = Rsu_Historypasien::where('no_rm',$request->norm)->limit(10)->get(); //db rsu
		// $history = DB::connection('dbrsud')->SELECT('SELECT * FROM (SELECT "RJ", tr_tracer.No_Register, tr_tracer.Nama_Pasien, DATE(tr_tracer.Tgl_Register) AS TANGGAL, tr_tracer.NAMAPOLI, tr_tracer.NamaAsuransi, tr_registrasi.NoSEP FROM tr_tracer, tr_registrasi WHERE tr_tracer.No_Register = tr_registrasi.No_Register  AND tr_tracer.No_RM="'.$request->norm.'" UNION ALL
			// SELECT "RI", No_register, NamaPasien, DATE(Tgl_masuk) AS TANGGAL,NamaKamar, StatusBayar, "" as NoSEP  FROM tm_rawatinap  WHERE tm_rawatinap.NO_RM="'.$request->norm.'")  AS XX  ORDER BY TANGGAL DESC limit 10');

		// $tblA = Rsu_Register::select('Nama_Pasien', 'tm_poli.NamaPoli AS Kode_Poli1', 'NoSEP', 'No_Register', 'Tgl_Register', 'NamaAsuransi')
		// 	->join('tm_poli', 'tm_poli.KodePoli', '=', 'tr_registrasi.Kode_Poli1')
		// 	->where('No_RM', $request->norm)
		// 	->orderby('Tgl_Register', 'DESC')->get();
		$history = DB::connection('dbrsud')->SELECT('SELECT * FROM (SELECT "RJ", tr_tracer.No_Register, tr_tracer.Nama_Pasien, DATE(tr_tracer.Tgl_Register) AS TANGGAL, tr_tracer.NAMAPOLI, tr_tracer.NamaAsuransi, tr_registrasi.NoSEP FROM tr_tracer, tr_registrasi WHERE tr_tracer.No_Register = tr_registrasi.No_Register  AND tr_tracer.No_RM="'.$request->norm.'" UNION ALL SELECT "RI", No_register, NamaPasien, DATE(Tgl_masuk) AS TANGGAL,NamaKamar, StatusBayar, "" as NoSEP  FROM tm_rawatinap  WHERE tm_rawatinap.NO_RM="'.$request->norm.'")  AS XX  ORDER BY TANGGAL DESC limit 10');
		return  ['status'=>'success','code'=>'200','history'=>$history];

		// return  ['status'=>'success','code'=>'200','history'=>$tblA];
	}

	//cek peserta bpjs
	public function cekpeserta(Request $request){
		// return $request->all();
		$sep = $request->nobpjs ? $request->nobpjs : '0000000000000000000';
		if ($request->jnsCari == 'nik') {
			$url = $this->url."/Peserta/nik/".$request->nobpjs."/tglSEP/".date('Y-m-d'); //url web dev service bpjs
		} else {
			$url = $this->url."/peserta/nokartu/".$request->nobpjs."/tglsep/".date('Y-m-d'); //url web dev service bpjs
			// $url = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/peserta/nokartu/".$request->nobpjs."/tglsep/".date('Y-m-d'); //url web rilis baru service bpjs
			// $url = "http://api.bpjs-kesehatan.go.id:8080/vclaim-rest/peserta/nokartu/".$request->nobpjs."/tglsep/".date('Y-m-d'); //url web service bpjs
		}

		$consID     = $this->consid; //customer ID RS
		$secretKey  = $this->secretkey; //secretKey RS
		$method = 'GET';
		// $port = '8080';
		$port = '80';
		$params = '';

		$result = Requestor::set_new_curl_bridging($url, $params, $method, $consID, $secretKey, $port,'',''); // bridging data peserta bpjs
		if ($result === false) {
			echo "Tidak dapat menyambung ke server";
		} else {
			$respon = [
				'metaData' => json_decode($result['metaData']),
				'response' => json_decode($result['response']),
			];
			return $respon;
		}
	}

	//form update tanggal pulang
	public function formupdatetglpulang(Request $request){
		$data['nobpjs'] = $request->nobpjs;
		$data['messages'] = $request->pesan;
		$data['tglSEP'] = $request->tglSEP;
		$content = view('Admin.bridging.formupdatetglpulang', $data)->render();
		return ['status' => 'success', 'content' => $content];
	}

	// update no kartu bpjs di rm
	public function changeNokartu(Request $request){
		$customer = rsu_Customer::where('KodeCust',$request->data['kdcust'])->update([$request->data['field'] => $request->data['value']]); // db rsu
		if($customer){
			$data = rsu_Customer::where('KodeCust',$request->data['kdcust'])->first();
			return $data;
		}else{
			return 'gagal update';
		}
	}

	public function getregister(Request $request){
		$data = Rsu_Register::find($request->noregister); // db rsu
		if($data){
			$url = $this->url."/SEP/".$data->NoSEP; //url web dev service bpjs
			// $url = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/SEP/".$data->NoSEP; //url web rilis baru service bpjs
			// $url = "http://api.bpjs-kesehatan.go.id:8080/vclaim-rest/SEP/".$data->NoSEP; //url web service bpjs
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

			date_default_timezone_set('Asia/Jakarta');

			$dat['Kode_Ass'] = $data->Kode_Ass;
			$dat['NoSEP'] = $data->NoSEP;
			$dat['catatan'] = $result['response']->catatan;
			$poli = DB::connection('dbrsud')->table('tm_poli as p')->join('mapping_poli_bridging as bp','bp.kdpoli_rs','=','p.KodePoli')->where('kdpoli_rs',$data->Kode_Poli1)->first(); // db rsu
			$dat['kdpoli'] = $poli->kdpoli;
			$dat['namapoli'] = $poli->NamaPoli;
			if($result['response']->diagnosa != ''){
				$kd_diagnosa = rsu_diagnosaBpjs::where('diagnosa','like','%'.$result['response']->diagnosa.'%')->first(); // db rsu
				$dat['kddiagnosa'] = $kd_diagnosa->KodeICD;
				$dat['diagnosa'] = $result['response']->diagnosa;
			}else{
				$dat['kddiagnosa'] = '';
				$dat['diagnosa'] = '';
			}

			$riwayat = Rsu_RiwayatRegistrasi::where('NoSEP', $data->NoSEP)->first();
			if (!empty($riwayat)) {
				$dat['noRujuk'] = $riwayat->no_rujukan;
			}else{
				$dat['noRujuk'] = '';
			}

			return ['status'=>'success','code'=>'200','data'=>$dat];
		}else{
			return ['status'=>'success','code'=>'404','data'=>$data];
		}
	}

	public function cekrujukan(Request $request){
		// return $request->all();
		date_default_timezone_set('Asia/Jakarta');
		/*
		RUJUKAN DARI TK 1
		Cara Kerja : Cari Dari TK 1 dulu kalau responnya kosong maka carikan dari TK 2
		Fungsi dibawah, cari rujukan dari TK 1
		*/
		if (!empty($request->nobpjs)) {
			$url = $this->url."/Rujukan/Peserta/".$request->nobpjs; // url develop
			// $url = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/Rujukan/Peserta/".$request->nobpjs; // url rilis
		}else{
			$url = $this->url."/Rujukan/".$request->noRujuk; // url develop
			// $url = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/Rujukan/".$request->noRujuk; // url rilis
		}
		$consID     = $this->consid; //customer ID RS
		$secretKey  = $this->secretkey; //secretKey RS
		$method = 'GET';
		$port = '80';
		$params = '';
		$result = Requestor::set_new_curl_bridging($url, $params, $method, $consID, $secretKey, $port,'','');
		if ($result === false) {
			return ['status' => 'error', 'message' => 'Tidak Terhubung ke Server !!'];
		}else{
			$results = [
				'metaData' => json_decode($result['metaData']),
				'response' => json_decode($result['response']),
			];

			$data['tingkatRujuk'] = '1';
			if ($results['response'] == '') {
				/*
				RUJUKAN DARI TK 2
				sekarang cari dari TK 2 karena tidak dapat respon / null dari TK 1
				*/
				if ($results['metaData']->code != 201) {
					return ['status' => 'error', 'message' => $results['metaData']->message,'data'=>''];
				}
				if (!empty($request->nobpjs)) {
					$url2 = $this->url."/Rujukan/RS/Peserta/".$request->nobpjs; //url web service bpjs develop
					// $url2 = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/Rujukan/RS/Peserta/".$request->nobpjs; //url web service bpjs rilis
				}else{
					$url2 = $this->url."/Rujukan/RS/".$request->noRujuk; //url web service bpjs develop
					// $url2 = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/Rujukan/RS/".$request->noRujuk; // url rilis
				}
				$result2 = Requestor::set_new_curl_bridging($url2, $params, $method, $consID, $secretKey, $port,'',''); // bridging data peserta bpjs
				// $result2 = Requestor::set_curl_bridging_new($url2, $params, $method, $consID, $secretKey, $port,'',''); // bridging data peserta bpjs
				if ($result2 === false) {
					return ['status' => 'error', 'messages' => 'Tidak Dapat Terhubung ke Server !!'];
				}else{
					$results = [
						'metaData' => json_decode($result2['metaData']),
						'response' => json_decode($result2['response']),
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

				$return = ['status' => 'success', 'message' => 'Rujukan Ditemukan !!', 'data' => $data];
			}else{
				return ['status' => 'error', 'message' => 'Rujukan Tidak Ditemukan !!','data'=>$data];
			}
			return $return;
		}
	}

	public function getDokterDpjp(Request $request){
		// return $request->all();
		$dokter = rsu_dokter_bridging::find($request->idDokterBridg);
		$urlDokter = $this->url."/referensi/dokter/pelayanan/2/tglpelayanan/".date('Y-m-d')."/Spesialis/".$dokter->polibpjs; // url Develop
		// $urlDokter = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/referensi/dokter/pelayanan/2/tglpelayanan/".date('Y-m-d')."/Spesialis/".$dokter->polibpjs; // url rilis
		$consID     = $this->consid; //customer ID RS
		$secretKey  = $this->secretkey; //secretKey RS
		$method = 'GET';
		$port = '80';
		$params = '';
		$resultDokter = Requestor::set_new_curl_bridging($urlDokter, $params, $method, $consID, $secretKey, $port,'','');
		$hslDokter = [
			'metaData' => json_decode($resultDokter['metaData']),
			'response' => json_decode($resultDokter['response']),
		];
		$kodeDPJP = '';
		$namaDPJP = '';
		foreach ($hslDokter['response']->list as $h) {
			if (strtolower($h->nama) == strtolower($dokter->dokter)) {
				$kodeDPJP = $h->kode;
				$namaDPJP = $h->nama;
			}
		}
		$data['kodeDPJP'] = $kodeDPJP;
		$data['namaDPJP'] = $namaDPJP;
		return ['status' => 'success', 'message' => 'Dokter Ditemukan !!', 'data' => $data];
	}

	public function getNoSurat(Request $request){
		$cekSebelum = Rsu_RiwayatRegistrasi::where('no_rujukan', $request->noRujuk)
		->orderBy('id_riwayat_regis','DESC')
		->first();
		if (!empty($cekSebelum)) {
			$panjanNoAwal = strlen($cekSebelum->No_Register) - 6;
			$panjanNo = strlen($cekSebelum->No_Register);
			$noSurat = substr($cekSebelum->No_Register, $panjanNoAwal,$panjanNo);
		}else{
			$regisSebelum = Rsu_Register::where('No_RM', $request->rm)->orderby('Tgl_Register','DESC')->first();
			$panjanNoAwal = strlen($regisSebelum->No_Register) - 6;
			$panjanNo = strlen($regisSebelum->No_Register);
			$noSurat = substr($regisSebelum->No_Register, $panjanNoAwal,$panjanNo);
		}

		return ['status' => 'success', 'message' => 'No SKDP Berhasil dibuat !!', 'noSurat' => $noSurat];
	}

	public function deleteSEP(Request $request){
		return $request->all();
	}

	public function cariSEP(Request $request){
		// return $request->all();
		$url = $this->url."/SEP/".$request->noSep;
		$consID     = $this->consid; // customer ID RS
		$secretKey  = $this->secretkey; // secretKey RS
		$method = 'GET';
		$port = '80';
		$params = '';
	 $result = Requestor::set_new_curl_bridging($url, $params, $method, $consID, $secretKey, $port,'','');

		if ($result === false) {
			return ['status' => 'error', 'message' => 'Tidak Terhubung ke Server !!'];
		} else {
			$return = [
				'metaData' => json_decode($result['metaData']),
				'response' => json_decode($result['response']),
			];

			if ($return['response'] == null) {
				return $return;
			}

			if ($return['response']->diagnosa != null) {
				$diagnosa = rsu_diagnosaBpjs::where('Diagnosa', $return['response']->diagnosa)->first();
			}
			// if ($return['response']->poli != null) {
			// 	$poli = DB::connection('dbrsud')
			// 		->table('tm_poli as p')
			// 		->join('mapping_poli_bridging as bp','bp.kdpoli_rs','=','p.KodePoli')
			// 		->where('NamaPoli', 'like', '%'.$return['response']->poli.'%')
			// 		->first();
			// }

			$return['kodediagnosa'] = (isset($diagnosa->KodeICD)) ? $diagnosa->KodeICD : '';
			// $return['kodepoli'] = (isset($poli->kdpoli)) ? $poli->kdpoli : '';

			return $return;
		}
	}

	public function cariSEPInternal(Request $request){
		// return $request->all();
		$url = $this->url."/SEP/Internal/".$request->noSep;
		$consID     = $this->consid; // customer ID RS
		$secretKey  = $this->secretkey; // secretKey RS
		$method = 'GET';
		$port = '80';
		$params = '';
		$result = Requestor::set_new_curl_bridging($url, $params, $method, $consID, $secretKey, $port,'','');

		if ($result === false) {
			return ['status' => 'error', 'message' => 'Tidak Terhubung ke Server !!'];
		} else {
			$return = [
				'metaData' => json_decode($result['metaData']),
				'response' => json_decode($result['response']),
			];

			return $return;
		}
	}

	public function deleteSEPInternal(Request $request)
	{
		$data =array(
			"request"=>[
				"t_sep" => [
					"noSep" => $request->nsep,
					"noSurat" => $request->nsurat,
					"tglRujukanInternal" => $request->tgl,
					"kdPoliTuj" => $request->tuj,
					"user" => Auth::user()->name_user,
				]
			]);
		$params = json_encode($data);
		// INIT BAHAN BRIDGING
		$consID     = $this->consid; //customer ID RS
		$secretKey  = $this->secretkey; //secretKey RS
		$port = '80';
		$method = 'DELETE';
		$url = $this->url."/SEP/Internal/delete";

		$result = Requestor::set_new_curl_bridging($url, $params, $method, $consID, $secretKey, $port,'',''); // bridging

		if ($result === false) {
			return ['status' => 'error','message' => 'Tidak dapat menyambung ke server!'];
		} else {
			$respon = [
				'metaData' => json_decode($result['metaData']),
				'response' => json_decode($result['response']),
			];
		}
		return response()->json($respon);
	}

	public function cariHistorySEP(Request $request){
		// return $request->all();
		$dateNow = date('Y-m-d');
		$dateAgo =  date('Y-m-d', strtotime("-120 day", strtotime($dateNow)));
		// $dateAgo =  date('2014-01-01');
		$url = $this->url."monitoring/HistoriPelayanan/NoKartu/".$request->no."/tglMulai/".$request->dateAwal."/tglAkhir/".$request->dateAkhir;
		$consID     = $this->consid; // customer ID RS
		$secretKey  = $this->secretkey; // secretKey RS
		$method = 'GET';
		$port = '80';
		$params = '';
		$result = Requestor::set_new_curl_bridging($url, $params, $method, $consID, $secretKey, $port,'','');

		if ($result === false) {
			return ['status' => 'error', 'message' => 'Tidak Terhubung ke Server !!'];
		} else {
			$return = [
				'metaData' => json_decode($result['metaData']),
				'response' => json_decode($result['response']),
			];

			return $return;
		}
	}

	public function carippk(Request $request){
		$content = view('Admin.bridging.carippk')->render();
		return ['status' => 'success', 'content' => $content];
	}

	public function getFaskes(Request $request){
		date_default_timezone_set('Asia/Jakarta');
		$faskes = $request->key;
		// $url = "https://dvlp.bpjs-kesehatan.go.id/vclaim-rest/Rujukan/Peserta/".$request->nobpjs; // url develop
		$url = $this->url."/referensi/faskes/".$faskes."/".$request->tingkat; // url rilis
		$consID     = $this->consid; // customer ID RS
		$secretKey  = $this->secretkey; // secretKey RS
		$method = 'GET';
		$port = '80';
		$params = '';
		$result = Requestor::set_new_curl_bridging($url, $params, $method, $consID, $secretKey, $port,'','');
		// return $result;
		if ($result === false) {
			return ['status' => 'error', 'message' => 'Tidak Terhubung ke Server !!'];
		} else {
			return [
				'metaData' => json_decode($result['metaData']),
				'response' => json_decode($result['response']),
			];
		}
	}

	public function caridpjp(Request $request){
		// return $request->all();
		$data['title'] = ($request->dpjp == 'layan') ? 'DPJP Layan' : 'Perujuk' ;
		$data['namaPoli'] = $request->poli;
		$content = view('Admin.bridging.caridpjp', $data)->render();
		return ['status' => 'success', 'content' => $content];
	}

	public function getDPJP(Request $request){
		// return $request->all();
		date_default_timezone_set('Asia/Jakarta');
		// $url = "https://dvlp.bpjs-kesehatan.go.id/vclaim-rest/Rujukan/Peserta/".$request->nobpjs; // url develop
		$url = $this->url."/referensi/dokter/pelayanan/".$request->rawat."/tglPelayanan/".$request->tgl."/Spesialis/".$request->kdpoli; // url rilis
		$consID     = $this->consid; // customer ID RS
		$secretKey  = $this->secretkey; // secretKey RS
		$method = 'GET';
		$port = '80';
		$params = '';
		$result = Requestor::set_new_curl_bridging($url, $params, $method, $consID, $secretKey, $port,'','');

		if ($result === false) {
			return ['status' => 'error', 'message' => 'Tidak Terhubung ke Server !!'];
		} else {
			return [
				'metaData' => json_decode($result['metaData']),
				'response' => json_decode($result['response']),
			];
		}
	}

	public function cekSkdp(Request $request){
		// return $request->all();
		$url = $this->url."/RencanaKontrol/noSuratKontrol/".$request->noSurat; // url Develop

		$consID     = $this->consid; //customer ID RS
		$secretKey  = $this->secretkey; //secretKey RS
		$method = 'GET';
		$port = '80';
		$params = '';

		$result = Requestor::set_new_curl_bridging($url, $params, $method, $consID, $secretKey, $port,'','');
		if ($result === false) {
			return ['status' => 'error', 'message' => 'Tidak Terhubung ke Server !!'];
		} else {
			return [
				'metaData' => json_decode($result['metaData']),
				'response' => json_decode($result['response']),
			];
		}
	}

	public function updateSEP(Request $request){
		// return $request->all();
		$rules = [
			'nokartu'=>'required',
			'tgl_sep'=>'required',
			'jenisPeserta'=>'required',
			'katarak'=>'required',
			'no_rm'=>'required',
			'tgl_rujukan'=>'required',
			'ppk_rujukan' =>'required',
			'catatan'=>'required',
			'diagnosa'=>'required',
			'laka'=>'required',
			'tujuan_kunjugan'=>'required',
		];

		if ($request->jnsRujukan == 'nonIgd') {
			$rules['no_rujukan'] = 'required';
		}
		if ($request->rawat == '2') {
		  $rules['poli'] = 'required';
		}
		$messages = [
			'required' => 'kolom harus di isi',
		];

		$valid = Validator::make($request->all(), $rules,$messages);
		if($valid->fails()){
			return $valid->messages();
		}

		$asalRujukan = ($request->rawat == '1' || $request->ppk_rujukan == '1320R001') ? '2' : '1';
		$namauser = Auth::user()->name_user;
		$data =array(
			"request"=>[
				"t_sep"=> [
					"noSep"=> $request->noSep,
					"noKartu"=> $request->nokartu,
					"tglSep"=> $request->tgl_sep,
					"ppkPelayanan"=> "1320R001",
					"jnsPelayanan"=> $request->rawat,
					"klsRawat"=> [
						"klsRawatHak"=>$request->kelasrawat,
						"klsRawatNaik"=>($request->naikKelasRawat != null) ? $request->naikKelasRawat:"",
						"pembiayaan"=>($request->pembiayaan != null) ? $request->pembiayaan:"",
						"penanggungJawab"=>($request->namaPenanggungJawab != null) ? $request->namaPenanggungJawab:"",
					],
					"noMR"=> $request->no_rm,
					"rujukan"=> [
						"asalRujukan"=> $request->tingkatRujuk,
						"tglRujukan"=> date('Y-m-d', strtotime($request->tgl_rujukan)),
						"noRujukan"=> $request->no_rujukan,
						"ppkRujukan"=> $request->ppk_rujukan
					],
					"catatan"=> ($request->catatan) ? $request->catatan:"",
					"diagAwal"=> $request->diagnosa,
					"poli"=> [
						"tujuan"=> $request->poli,
						"eksekutif"=> "0"
					],
					"cob"=> [
						"cob"=> ($request->cob) ? $request->cob : '0',
					],
					"katarak" => [
						"katarak" => "0",
					],
					"jaminan"=> [
						"lakaLantas" => $request->laka,
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
						"noSurat" => $request->no_surat,
						"kodeDPJP" => $request->kdDpjp,
					],
					"dpjpLayan"=>$request->dpjpLayan,
					"noTelp"=> ($request->notelp) ? $request->notelp : '000000000000',
					"user"=> $namauser
				]
			]
		);
		// return $data;

		$url = $this->url."/SEP/2.0/update";
		$consID     = $this->consid; //customer ID RS
		$secretKey  = $this->secretkey; //secretKey RS
		$method = 'PUT';
		$port = '80';
		$params = json_encode($data);

		$result = Requestor::set_new_curl_bridging($url, $params, $method, $consID, $secretKey, $port,'',''); // bridging data peserta bpjs
		if ($result === false) {
			return ["code" => "400", "message" => "Tidak dapat menyambung ke server", "data" => ""];
		} else {
			$result = [
				'metaData' => json_decode($result['metaData']),
				'response' => json_decode($result['response']),
			];
		}
		return $result;
	}

	public function cariLokasiLaka(Request $request){
		$data['title'] = ($request->jnsLokasi == 'prov') ? 'Provinsi' : (($request->jnsLokasi == 'kab') ? 'Kabupaten/Kota' : 'Kecamatan') ;
		$data['jnsLokasi'] = $request->jnsLokasi;
		$data['request'] = $request->all();
		$content = view('Admin.bridging.cariLokasiLaka', $data)->render();
		return ['status' => 'success', 'content' => $content];
	}

	public function getLokasiLaka(Request $request){
		// return $request->all();
		if ($request->jnsLokasi == 'kab') {
			$url = $this->url."/referensi/kabupaten/propinsi/".$request->prov; // url Develop
		} elseif($request->jnsLokasi == 'kec'){
			$url = $this->url."/referensi/kecamatan/kabupaten/".$request->kab; // url Develop
		} else {
			$url = $this->url."/referensi/propinsi"; // url Develop
		}
		// return $url;

		$consID     = $this->consid; //customer ID RS
		$secretKey  = $this->secretkey; //secretKey RS
		$method = 'GET';
		$port = '80';
		$params = '';

		$result = Requestor::set_new_curl_bridging($url, $params, $method, $consID, $secretKey, $port,'','');
		// return $result;
		if ($result === false) {
			return ['status' => 'error', 'message' => 'Tidak Terhubung ke Server !!'];
		} else {
			return [
				'metaData' => json_decode($result['metaData']),
				'response' => json_decode($result['response']),
			];
		}
	}

	public function cariSuplesi(Request $request){
		$content = view('Admin.bridging.carisuplesi')->render();
		return ['status' => 'success', 'content' => $content];
	}

	public function getSuplesi(Request $request){
		date_default_timezone_set('Asia/Jakarta');

		$url = $this->url."/sep/JasaRaharja/Suplesi/".$request->nokepesertaan."/tglPelayanan/".$request->tglsep_laka; // url rilis
		$params = '';
		$method = 'GET';
		$consID = $this->consid; // customer ID RS
		$secretKey = $this->secretkey; // secretKey RS
		$port = '80';
		$result = Requestor::set_new_curl_bridging($url, $params, $method, $consID, $secretKey, $port,'','');

		if ($result === false) {
			return ['status' => 'error', 'message' => 'Tidak Terhubung ke Server !!'];
		} else {
			return [
				'metaData' => json_decode($result['metaData']),
				'response' => json_decode($result['response']),
			];
		}
	}

	/*
	=================================================================================================================================================================
	=================================================================================================================================================================
	======================================================================= PERSEtUJUAN SEP =========================================================================
	=================================================================================================================================================================
	=================================================================================================================================================================
	*/

	public function main_persetujuan_sep(Request $request){
		$this->data['classtutup'] = 'sidebar-collapse';
		//db rsu
		$this->data['poli'] = rsu_poli::all();
		$this->data['jenispasien'] = Rsu_setupall::where('groups','asuransi')->whereNotIn('nilaichar',['UMUM','JAMKESDA','PT POS','PT MENTARI','ACC DIREKTUR','JAMKESMAS','JAMSOSTEK','PT KAI','INHEALTH','PERUSAHAAN','JAMKESPROV','JASA RAHARJA','JKD KAB MJK','RENC BPJS','AMBIL OBAT'])->get();

		return view('Admin.bridging.persetujuanSEP.main')->with('data', $this->data);
	}

	public function create_persetujuan_sep(Request $request){
		$this->data['classtutup'] = 'sidebar-collapse';
		$content = view('Admin.bridging.persetujuanSEP.form')->with('data', $this->data)->render();
		return ['status'=>'success','content'=>$content];
	}

	// Simpan
	public function simpanPengajuanSEP(Request $request){
		// return $request->all();
		$url = $this->url."/Sep/pengajuanSEP"; //url web dev service bpjs
		// $url = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/Sep/updtglplg"; //url web rilis baru service bpjs
		// $url = "http://api.bpjs-kesehatan.go.id:8080/vclaim-rest/Sep/updtglplg"; //url web service bpjs
		$consID     = $this->consid; //customer ID RS
		$secretKey  = $this->secretkey; //secretKey RS
		$method = 'POST';
		$port = '80';

		$namauser = Auth::user()->name_user;
		$data =array(
			"t_sep"=> [
				"noKartu"=> $request->noka,
				"tglSep"=> date('Y-m-d', strtotime($request->tgl)),
				"jnsPelayanan"=> $request->jenpel,
				"jnsPengajuan"=> $request->flag,
				"keterangan"=> $request->keterangan,
				"user" => $namauser,
			],
		);

		$params = json_encode(array('request' => $data));
		$result = Requestor::set_new_curl_bridging($url, $params, $method, $consID, $secretKey, $port,'',''); // bridging data peserta bpjs
		if ($result === false) {
			echo "Tidak dapat menyambung ke server";
		} else {
			$result = [
				'metaData' => json_decode($result['metaData']),
				'response' => json_decode($result['response']),
			];
			// return $result;
			if ($result['metaData']->code == 200) {
				$newdata = New VclaimPengajuanSep;
				$newdata->noKartu = $request->noka;
				$newdata->tglSep = date('Y-m-d', strtotime($request->tgl));
				$newdata->jnsPelayanan = $request->jenpel;
				$newdata->jnsPengajuan = $request->flag;
				$newdata->keterangan = $request->keterangan;
				$newdata->user = $namauser;
				$newdata->save();
				return $result;
			}else {
				return $result;
			}
		}
	}

	// ======= Get List Pengajuan ========= //
	public function get_list_pengajuan(Request $request){
		$data = VclaimPengajuanSep::whereYear('tglSep', '=', $request->tahun)
			->whereMonth('tglSep', '=', $request->bulan)
			->get();
		if (count($data) != 0) {
			$return = ['code'=>'200','status'=>'Data Ditemukan','data'=>$data];
		}else {
			$return = ['code'=>'500','status'=>'Data Tidak Ditemukan','data'=>''];
		}
		return response()->json($return);
	}

	public function aproval_pengajuan_sep(Request $request){
		// return ['status' => 'error', 'message' => 'erro', 'data'=> $request->all()];
		$url = $this->url."/Sep/aprovalSEP"; //url web dev service bpjs

		$consID     = $this->consid; //customer ID RS
		$secretKey  = $this->secretkey; //secretKey RS
		$method = 'POST';
		$port = '80';

		$namauser = Auth::user()->name_user;
		$data =array(
			"t_sep"=> [
				"noKartu"=> $request->noKartu,
				"tglSep"=> date('Y-m-d', strtotime($request->tglSep)),
				"jnsPelayanan"=> $request->jnsPelayanan,
				"jnsPengajuan"=> $request->jnsPengajuan,
				"keterangan"=> $request->keterangan,
				"user" => $namauser,
			],
		);
		$params = json_encode(array('request' => $data));
		$result = Requestor::set_new_curl_bridging($url, $params, $method, $consID, $secretKey, $port,'',''); // bridging data peserta bpjs
		if ($result === false) {
			return ["code" => "400", "message" => "Tidak dapat menyambung ke server", "data" => ""];
		} else {
			// return $result;
			$result = [
				'metaData' => json_decode($result['metaData']),
				'response' => json_decode($result['response']),
			];
		}
		return $result;
	}

	//cek SEP
	public function ceksep(Request $request){
		// return $request->all();
		$sep = $request->no_sep ? $request->no_sep : '0000000000000000000';
		$url = $this->url."/SEP/".$request->no_sep; //url web dev service bpjs
		$consID     = $this->consid; //customer ID RS
		$secretKey  = $this->secretkey; //secretKey RS
		$method = 'GET';
		// $port = '8080';
		$port = '80';
		$params = '';

		$result = Requestor::set_new_curl_bridging($url, $params, $method, $consID, $secretKey, $port,'',''); // bridging data peserta bpjs
		if ($result === false) {
			echo "Tidak dapat menyambung ke server";
		} else {
			$respon = [
				'metaData' => json_decode($result['metaData']),
				'response' => json_decode($result['response']),
			];
			return $respon;
		}
	}

	public function destroySEP(Request $request)
	{
		$data =array(
		"request"=>[
			"t_sep" => [
				"noSep" => $request->no,
				"user" => Auth::user()->name_user,
			]
		]);
		$params = json_encode($data);
		// INIT BAHAN BRIDGING
		$consID     = $this->consid; //customer ID RS
		$secretKey  = $this->secretkey; //secretKey RS
		$port = '80';
		$method = 'DELETE';
		$url = $this->url."/SEP/2.0/delete";

		$result = Requestor::set_new_curl_bridging($url, $params, $method, $consID, $secretKey, $port,'',''); // bridging

		if ($result === false) {
			return ['status' => 'error','message' => 'Tidak dapat menyambung ke server!'];
		} else {
			$respon = [
				'metaData' => json_decode($result['metaData']),
				'response' => json_decode($result['response']),
			];
		}
		return response()->json($respon);
	}
	/*
	=================================================================================================================================================================
	=================================================================================================================================================================
	===================================================================== END PERSEtUJUAN SEP =======================================================================
	=================================================================================================================================================================
	=================================================================================================================================================================
	*/

	public function countRujukan(Request $request)
	{
		$result = Rsu_Register::where('Rujukan', $request->noRujuk)->count();
		return $result;
	}
}
