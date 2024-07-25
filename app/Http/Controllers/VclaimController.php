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
// MODEL VCLAIM
use App\Http\Models\VclaimRujukan;
use App\Http\Models\VclaimPrb;
use App\Http\Models\VclaimObatPrb;
use Redirect, Validator, DB, Auth;

class VclaimController extends Controller{
	public function __construct(){
		$this->url = "https://apijkn.bpjs-kesehatan.go.id/vclaim-rest/";
		// $this->url = 'https://apijkn-dev.bpjs-kesehatan.go.id/vclaim-rest-dev/';
		// $this->userkey = env('USER_KEY');
		$this->consid = env('CONS_ID');
		$this->secretkey = env('SECRET_KEY');
	}
	/*
	 MODUL RUJUKAN
	 Terdapat 2 menu yaitu Form Rujukan dan Rujukan Khusus
	 Function yang terdapat di modul ini yaitu
	 1. main_form_rujukan
	 3. cek_ppk_rujukan
	 4. cek_diagnosa
	 5. Insert Rujukan
	 5. Update Rujukan
	 5. Get List Rujukan
	*/
	/*========================================================
	 ========    STARTS FROM HERE MODUL RUJUKAN      ========
	 ========================================================*/
	public function main_form_rujukan(Request $request){
		$this->data['classtutup'] = 'sidebar-collapse';
		//db lokal site
		$this->data['poli'] = Poli::all();
		// $this->data['jenispasien'] = Setupall::where('groups','asuransi')->whereNotIn('nilaichar',['UMUM','JAMKESDA','PT POS','PT MENTARI','ACC DIREKTUR','JAMKESMAS','JAMSOSTEK','PT KAI','INHEALTH','PERUSAHAAN','JAMKESPROV','JASA RAHARJA','JKD KAB MJK','RENC BPJS','AMBIL OBAT'])->get();
		//db rsu
		// $this->data['poli'] = rsu_poli::all();
		// $this->data['jenispasien'] = Rsu_setupall::where('groups','asuransi')->whereNotIn('nilaichar',['UMUM','JAMKESDA','PT POS','PT MENTARI','ACC DIREKTUR','JAMKESMAS','JAMSOSTEK','PT KAI','INHEALTH','PERUSAHAAN','JAMKESPROV','JASA RAHARJA','JKD KAB MJK','RENC BPJS','AMBIL OBAT'])->get();
		return view('Admin.vclaim.rujukan.main')->with('data', $this->data);
	}
	public function create_form_rujukan(Request $request){
		$this->data['classtutup'] = 'sidebar-collapse';
		$edit = $request->edit;

		$this->data['edit'] = $edit;
		// if ($this->data['edit'] == 'true') {
		//   $this->data['dataRujukan'] = VclaimRujukan::where('noRujukan',$request->noRujukan)->first();
		//   $id = $this->data['dataRujukan']->id_rujukan;
		// }
		// $this->data['id'] = ($this->data['edit'] == 'true') ? $id : "";
		// CEK NO SEP
		$sep = $request->no_sep ? $request->no_sep : '0000000000000000000';
		$url = $this->url."SEP/".$request->no_sep; //url web dev service bpjs
		$consID     = $this->consid; //customer ID RS
		$secretKey  = $this->secretkey; //secretKey RS
		$method = 'GET';
		$port = '80';
		$params = '';

		$result = Requestor::set_new_curl_bridging($url, $params, $method, $consID, $secretKey, $port,'',''); // bridging data peserta bpjs
		if ($result === false) {
			echo "Tidak dapat menyambung ke server";
		} else {
			$respon = [
				'metaData'=>json_decode($result['metaData']),
				'response'=>json_decode($result['response'])
			];
		}
		$this->data['sep'] = $respon;

		if ($respon['metaData']->code == 200) {
			if ($this->data['edit'] == 'true') {
				// CEK RUJUKAN
				$url2 = $this->url."Rujukan/Keluar/".$request->noRujukan; //url web dev service bpjs
				$consID     = $this->consid; //customer ID RS
				$secretKey  = $this->secretkey; //secretKey RS
				$method = 'GET';
				$port = '80';
				$params = '';

				$result2 = Requestor::set_new_curl_bridging($url2, $params, $method, $consID, $secretKey, $port,'',''); // bridging data peserta bpjs
				if ($result2 === false) {
					echo "Tidak dapat menyambung ke server";
				} else {
					$respon2 = [
						'metaData'=>json_decode($result2['metaData']),
						'response'=>json_decode($result2['response'])
					];
				}
				$this->data['rujukan'] = $respon2;
			}else {
				// CEK RUJUKAN
				$url2 = $this->url."Rujukan/".$respon['response']->noRujukan; //url web dev service bpjs
				$consID     = $this->consid; //customer ID RS
				$secretKey  = $this->secretkey; //secretKey RS
				$method = 'GET';
				$port = '80';
				$params = '';
				
				$result2 = Requestor::set_new_curl_bridging($url2, $params, $method, $consID, $secretKey, $port,'',''); // bridging data peserta bpjs
				if ($result2 === false) {
					echo "Tidak dapat menyambung ke server";
				} else {
					$respon2 = [
						'metaData'=>json_decode($result2['metaData']),
						'response'=>json_decode($result2['response'])
					];
				}
				$this->data['rujukan'] = $respon2;
			}

			$content = view('Admin.vclaim.rujukan.form')->with('data', $this->data)->render();
			return ['status'=>'success','content'=>$content];
		}else {
			return ['status'=>'error','message'=>$respon['metaData']->message,'content'=>''];
		}
	}

	public function form_ppk_rujukan(Request $request){
		$content = view('Admin.vclaim.rujukan.form_ppk')->render();
		return ['status'=>'success','content'=>$content];
	}

	//======= CEK PPK RUJUKAN =========//
	public function cek_ppk_rujukan(Request $request){
		// return urldecode($request->q);
		$sep = $request->q ? $request->q : '0000000000000000000';
		$url = $this->url."referensi/faskes/".urldecode($request->q)."/2"; //url web dev service bpjs
		// $url = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/peserta/nokartu/".$request->nobpjs."/tglsep/".date('Y-m-d'); //url web rilis baru service bpjs
		// $url = "http://api.bpjs-kesehatan.go.id:8080/vclaim-rest/peserta/nokartu/".$request->nobpjs."/tglsep/".date('Y-m-d'); //url web service bpjs
		
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
			return json_decode($result['response'],true);
		}
	}

	//======= CEK List Spesialistik Rujukan =========//
	public function cek_list_spesialistik_rujukan(Request $request){
		$url = $this->url."Rujukan/ListSpesialistik/PPKRujukan/".$request->ppkrujuk_id."/TglRujukan/".$request->tgl_rencana_rujukan; //url web dev service bpjs
		// $url = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/peserta/nokartu/".$request->nobpjs."/tglsep/".date('Y-m-d'); //url web rilis baru service bpjs
		// $url = "http://api.bpjs-kesehatan.go.id:8080/vclaim-rest/peserta/nokartu/".$request->nobpjs."/tglsep/".date('Y-m-d'); //url web service bpjs
		
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
			return json_decode($result['response'],true);
		}
	}

	//======= CEK List Sarana Rujukan =========//
	public function cek_list_sarana_rujukan(Request $request){
		$url = $this->url."Rujukan/ListSarana/PPKRujukan/".$request->ppkrujuk_id; //url web dev service bpjs
		// $url = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/peserta/nokartu/".$request->nobpjs."/tglsep/".date('Y-m-d'); //url web rilis baru service bpjs
		// $url = "http://api.bpjs-kesehatan.go.id:8080/vclaim-rest/peserta/nokartu/".$request->nobpjs."/tglsep/".date('Y-m-d'); //url web service bpjs
		
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
			return json_decode($result['response'],true);
		}
	}

	//======= CEK Rujukan SEP =========//
	public function cek_rujukan_sep(Request $request){
		$url = $this->url."Rujukan/".$request->noRujukan; //url web dev service bpjs
		// $url = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/peserta/nokartu/".$request->nobpjs."/tglsep/".date('Y-m-d'); //url web rilis baru service bpjs
		// $url = "http://api.bpjs-kesehatan.go.id:8080/vclaim-rest/peserta/nokartu/".$request->nobpjs."/tglsep/".date('Y-m-d'); //url web service bpjs
		
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
				'metaData'=>json_decode($result['metaData']),
				'response'=>json_decode($result['response'])
			];
			return $respon;
		}
	}

	//======= CEK Diagnosa =========//
	public function cek_diagnosa(Request $request){
		// return urldecode($request->q);
		$sep = $request->q ? $request->q : '0000000000000000000';
		$url = $this->url."referensi/diagnosa/".$request->q; //url web dev service bpjs
		// $url = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/peserta/nokartu/".$request->nobpjs."/tglsep/".date('Y-m-d'); //url web rilis baru service bpjs
		// $url = "http://api.bpjs-kesehatan.go.id:8080/vclaim-rest/peserta/nokartu/".$request->nobpjs."/tglsep/".date('Y-m-d'); //url web service bpjs

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
			// $respon = [
			//   'response'=>json_decode($result['response']->diagnosa)
			// ];
			return json_decode($result['response'],true);
		}
	}

	//======= Insert Rujukan =========//
	public function insert_rujukan(Request $request){
		$data =array(
			"request"=>[
				"t_rujukan"=> [
					"noSep"=>$request->noSep,
					"tglRujukan"=>$request->tglRujukan,
					"tglRencanaKunjungan"=>$request->tglRencanaKunjungan,
					"ppkDirujuk"=>$request->ppkDirujuk,
					"jnsPelayanan"=>$request->jnsPelayanan,
					"catatan"=>$request->catatan,
					"diagRujukan"=>$request->diagRujukan,
					"tipeRujukan"=>$request->tipeRujukan,
					"poliRujukan"=>$request->poliRujukan,
					"user"=> Auth::user()->name_user,
				]
			]
		);
		$params = json_encode($data);
		$url = $this->url."Rujukan/2.0/insert"; //url web dev service bpjs
		// $url = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/peserta/nokartu/".$request->nobpjs."/tglsep/".date('Y-m-d'); //url web rilis baru service bpjs
		// $url = "http://api.bpjs-kesehatan.go.id:8080/vclaim-rest/peserta/nokartu/".$request->nobpjs."/tglsep/".date('Y-m-d'); //url web service bpjs
		
		$consID     = $this->consid; //customer ID RS
		$secretKey  = $this->secretkey; //secretKey RS
		$method = 'POST';
		// $port = '8080';
		$port = '80';
		$result = Requestor::set_new_curl_bridging($url, $params, $method, $consID, $secretKey, $port,'',''); // bridging data peserta bpjs
		if ($result === false) {
			echo "Tidak dapat menyambung ke server";
		} else {
			$respon = [
				'metaData'=>json_decode($result['metaData']),
				'response'=>json_decode($result['response'])
			];
			if ($respon['metaData']->code == 200) {
				$url2 = $this->url."Rujukan/Keluar/".$respon['response']->rujukan->noRujukan; //url web dev service bpjs
				$consID     = $this->consid; //customer ID RS
				$secretKey  = $this->secretkey; //secretKey RS
				$method = 'GET';
				$port = '80';
				$params = '';
				
				$result2 = Requestor::set_new_curl_bridging($url2, $params, $method, $consID, $secretKey, $port,'',''); // bridging data peserta bpjs
				if ($result2 === false) {
					echo "Tidak dapat menyambung ke server";
				} else {
					$respon2 = [
						'metaData'=>json_decode($result2['metaData']),
						'response'=>json_decode($result2['response'])
					];
				}
				return $data = ['insert'=>$respon,'rujukan'=>$respon2];
			}else {
				return $data = ['insert'=>$respon,'rujukan'=>''];
			}
		}
	}

  //======= Update Rujukan =========//
	public function update_rujukan(Request $request){
		$data =array(
			"request"=>[
				"t_rujukan"=> [
					"noRujukan"=>$request->noRujukan,
					"tglRujukan"=>$request->tglRujukan,
					"tglRencanaKunjungan"=>$request->tglRencanaKunjungan,
					"ppkDirujuk"=>$request->ppkDirujuk,
					"jnsPelayanan"=>$request->jnsPelayanan,
					"catatan"=>$request->catatan,
					"diagRujukan"=>$request->diagRujukan,
					"tipeRujukan"=>$request->tipeRujukan,
					"poliRujukan"=>$request->poliRujukan,
					"user"=> Auth::user()->name_user,
				]
			]
		);
		$params = json_encode($data);
		$url = $this->url."Rujukan/2.0/Update"; //url web dev service bpjs
		// $url = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/peserta/nokartu/".$request->nobpjs."/tglsep/".date('Y-m-d'); //url web rilis baru service bpjs
		// $url = "http://api.bpjs-kesehatan.go.id:8080/vclaim-rest/peserta/nokartu/".$request->nobpjs."/tglsep/".date('Y-m-d'); //url web service bpjs

		$consID     = $this->consid; //customer ID RS
		$secretKey  = $this->secretkey; //secretKey RS
		$method = 'PUT';
		// $port = '8080';
		$port = '80';
		$result = Requestor::set_new_curl_bridging($url, $params, $method, $consID, $secretKey, $port,'',''); // bridging data peserta bpjs
		if ($result === false) {
			echo "Tidak dapat menyambung ke server";
		} else {
			$respon = [
				'metaData'=>json_decode($result['metaData']),
				'response'=>json_decode($result['response'])
			];
			// $newRujukan = VclaimRujukan::find($request->id);
			// $newRujukan->tglRujukan = $respon['response']['tglRujukan'];
			// $newRujukan->tglRencanaKunjungan = $respon['response']['tglRencanaKunjungan'];
			// $newRujukan->tglBerlakuKunjungan = $respon['response']['tglBerlakuKunjungan'];
			// $newRujukan->kode_diagnosa = $respon['response']['diagnosa']['kode'];
			// $newRujukan->cbpelayanan = $request->jnsPelayanan;
			// $newRujukan->noSep = $request->noSep;
			// $newRujukan->rbrujukan = $request->tipeRujukan;
			// $newRujukan->noRujukan = $respon['response']['noRujukan'];
			// $newRujukan->noKartu = $respon['response']['peserta']['noKartu'];
			// $newRujukan->nama = $respon['response']['peserta']['nama'];
			// $newRujukan->noMr = $respon['response']['peserta']['noMr'];
			// $newRujukan->asalRujukan = $respon['response']['AsalRujukan']['kode'];
			// $newRujukan->namaAsalRujukan = $respon['response']['AsalRujukan']['nama'];
			// $newRujukan->tujuanRujukan = $respon['response']['tujuanRujukan']['kode'];
			// $newRujukan->namaTujuanRujukan = $respon['response']['tujuanRujukan']['nama'];
			// $newRujukan->poliTujuan = $request->poliRujukan;
			// $newRujukan->catatan = $request->catatan;
			// $newRujukan->save();
			// if ($newRujukan) {
			if ($respon) {
				if ($respon['metaData']->code == 200) {
					$url2 = $this->url."Rujukan/Keluar/".$request->noRujukan; //url web dev service bpjs
					$consID     = $this->consid; //customer ID RS
					$secretKey  = $this->secretkey; //secretKey RS
					$method = 'GET';
					$port = '80';
					$params = '';
					
					$result2 = Requestor::set_new_curl_bridging($url2, $params, $method, $consID, $secretKey, $port,'',''); // bridging data peserta bpjs
					if ($result2 === false) {
						echo "Tidak dapat menyambung ke server";
					} else {
						$respon2 = [
							'metaData'=>json_decode($result2['metaData']),
							'response'=>json_decode($result2['response'])
						];
					}
					// $this->data['rujukan'] = $respon2;
				}
				return $data = ['update'=>$respon,'rujukan'=>$respon2];
			}else {
				echo "Tidak dapat Menyimpan Data";
			}
		}
	}

	//======= Hapus Rujukan =========//
	public function hapus_rujukan(Request $request){
		$data =array(
			"request"=>[
				"t_rujukan"=> [
					"noRujukan"=>$request->noRujukan,
					"user"=> Auth::user()->name_user,
				]
			]
		);
		$params = json_encode($data);
		$url = $this->url."Rujukan/delete"; //url web dev service bpjs
		// $url = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/peserta/nokartu/".$request->nobpjs."/tglsep/".date('Y-m-d'); //url web rilis baru service bpjs
		// $url = "http://api.bpjs-kesehatan.go.id:8080/vclaim-rest/peserta/nokartu/".$request->nobpjs."/tglsep/".date('Y-m-d'); //url web service bpjs
		
		$consID     = $this->consid; //customer ID RS
		$secretKey  = $this->secretkey; //secretKey RS
		$method = 'DELETE';
		// $port = '8080';
		$port = '80';
		$result = Requestor::set_new_curl_bridging($url, $params, $method, $consID, $secretKey, $port,'',''); // bridging data peserta bpjs
		if ($result === false) {
			echo "Tidak dapat menyambung ke server";
		} else {
			$respon = [
				'metaData'=>json_decode($result['metaData']),
				'response'=>json_decode($result['response'])
			];
			if ($respon['metaData']->code == 200) {
				return $respon;
			}else {
				return $respon;
			}
		}
	}
	//======= Get List Rujukan =========//
	public function get_list_rujukan(Request $request){
		$url = $this->url."Rujukan/Keluar/List/tglMulai/".$request->tgl1."/tglAkhir/".$request->tgl2; //url web dev service bpjs
		// $url = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/peserta/nokartu/".$request->nobpjs."/tglsep/".date('Y-m-d'); //url web rilis baru service bpjs
		// $url = "http://api.bpjs-kesehatan.go.id:8080/vclaim-rest/peserta/nokartu/".$request->nobpjs."/tglsep/".date('Y-m-d'); //url web service bpjs
		
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
				'metaData'=>json_decode($result['metaData']),
				'response'=>json_decode($result['response'])
			];
			return $respon;
		}
	}

	//======= Cetak Rujukan =========//
	public function cetak_rujukan(Request $request){
		// CEK No SRB
		$sep = $request->no_sep ? $request->no_sep : '0000000000000000000';
		$url = $this->url."Rujukan/Keluar/".$request->noRujukan; //url web dev service bpjs
		$consID     = $this->consid; //customer ID RS
		$secretKey  = $this->secretkey; //secretKey RS
		$method = 'GET';
		$port = '80';
		$params = '';
		
		$result = Requestor::set_new_curl_bridging($url, $params, $method, $consID, $secretKey, $port,'',''); // bridging data peserta bpjs
		if ($result === false) {
			echo "Tidak dapat menyambung ke server";
		} else {
			$respon = [
				'metaData'=>json_decode($result['metaData']),
				'response'=>json_decode($result['response'])
			];
		}
		$this->data['dataRujukan'] = VclaimRujukan::where('noRujukan',$request->noRujukan)->first();
		$this->data['rujukan'] = $respon;
		return $this->data;
	}

	/*========================================================
	  ========        HERE END of MODUL RUJUKAN       ========
	  ========================================================*/

	/*
	 MODUL RUJUKAN KHUSUS
	 Terdapat 2 menu yaitu Form Rujukan dan Rujukan Khusus
	 Function yang terdapat di modul ini yaitu
	 1. main_rujukan_khusus
	 2.
	 3. cek_ppk_rujukan
	 4. cek_diagnosa
	 5. Insert Rujukan
	 5. Update Rujukan
	*/

	/*========================================================
	  ========  STARTS FROM HERE MODUL RUJUKAN KHUSUS ========
	  ========================================================*/

	public function main_form_rujukan_khusus(Request $request){
		$this->data['classtutup'] = 'sidebar-collapse';
		//db lokal site
		$this->data['poli'] = Poli::all();
		// $this->data['jenispasien'] = Setupall::where('groups','asuransi')->whereNotIn('nilaichar',['UMUM','JAMKESDA','PT POS','PT MENTARI','ACC DIREKTUR','JAMKESMAS','JAMSOSTEK','PT KAI','INHEALTH','PERUSAHAAN','JAMKESPROV','JASA RAHARJA','JKD KAB MJK','RENC BPJS','AMBIL OBAT'])->get();
		
		//db rsu
		// $this->data['poli'] = rsu_poli::all();
		// $this->data['jenispasien'] = Rsu_setupall::where('groups','asuransi')->whereNotIn('nilaichar',['UMUM','JAMKESDA','PT POS','PT MENTARI','ACC DIREKTUR','JAMKESMAS','JAMSOSTEK','PT KAI','INHEALTH','PERUSAHAAN','JAMKESPROV','JASA RAHARJA','JKD KAB MJK','RENC BPJS','AMBIL OBAT'])->get();
		
		return view('Admin.vclaim.rujukan_khusus.main')->with('data', $this->data);
	}

	//======= CEK List Rujukan Khusus =========//
	public function cek_rujukan_khusus(Request $request){
		$url = $this->url."Rujukan/Khusus/List/Bulan/".$request->bulan."/Tahun/".$request->tahun; //url web dev service bpjs
		// $url = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/peserta/nokartu/".$request->nobpjs."/tglsep/".date('Y-m-d'); //url web rilis baru service bpjs
		// $url = "http://api.bpjs-kesehatan.go.id:8080/vclaim-rest/peserta/nokartu/".$request->nobpjs."/tglsep/".date('Y-m-d'); //url web service bpjs
		
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
				'metaData'=>json_decode($result['metaData']),
				'response'=>json_decode($result['response'])
			];
			return $respon;
		}
	}
	
	public function create_form_rujukan_khusus(Request $request){
		$this->data['classtutup'] = 'sidebar-collapse';
		$edit = $request->edit;
		
		// CEK RUJUKAN
		$url2 = $this->url."Rujukan/".$request->noRujukan; //url web dev service bpjs
		$consID     = $this->consid; //customer ID RS
		$secretKey  = $this->secretkey; //secretKey RS
		$method = 'GET';
		$port = '80';
		$params = '';
		
		$result = Requestor::set_new_curl_bridging($url2, $params, $method, $consID, $secretKey, $port,'',''); // bridging data peserta bpjs
		if ($result === false) {
			echo "Tidak dapat menyambung ke server";
		} else {
			$respon = [
				'metaData'=>json_decode($result['metaData']),
				'response'=>json_decode($result['response'])
			];
		}
		$this->data['rujukan'] = $respon;
		if ($respon['metaData']->code == 200) {
			$content = view('Admin.vclaim.rujukan_khusus.form')->with('data', $this->data)->render();
			return ['status'=>'success','content'=>$content];
		}else {
			return ['status'=>'error','message'=>$respon['metaData']->message,'content'=>''];
		}
	}
	
	//======= CEK Procedure =========//
	public function cek_procedure(Request $request){
		// return urldecode($request->q);
		$sep = $request->q ? $request->q : '0000000000000000000';
		$url = $this->url."referensi/procedure/".$request->q; //url web dev service bpjs
		// $url = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/peserta/nokartu/".$request->nobpjs."/tglsep/".date('Y-m-d'); //url web rilis baru service bpjs
		// $url = "http://api.bpjs-kesehatan.go.id:8080/vclaim-rest/peserta/nokartu/".$request->nobpjs."/tglsep/".date('Y-m-d'); //url web service bpjs
		
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
			// $respon = [
			//   'response'=>json_decode($result['response']->diagnosa)
			// ];
			return json_decode($result['response'],true);
		}
	}
	
	//======= CEK Procedure =========//
	public function storeRujukanKhusus(Request $request){
		$data = [
			"noRujukan"=>$request->no_rujukan,
			"user"=> Auth::user()->name_user,
		];
		$diagnosa_temp = [];
		$procedure_temp = [];
		if (isset($request->utama)) {
			for($i=0; $i<count($request->utama);$i++){
				$item = [
					"kode"=>($request->utama[$i] == '1') ? 'P'.";".$request->kode[$i] : "S".";".$request->kode[$i],
				];
				array_push($diagnosa_temp, $item);
			}
		}
		if (isset($request->kode_proc)) {
			for($i=0; $i<count($request->kode_proc);$i++){
				$item = [
					"kode"=>$request->kode_proc[$i],
				];
				array_push($procedure_temp, $item);
			}
		}
		$data['diagnosa']= $diagnosa_temp;
		$data['procedure']= $procedure_temp;
		$params = json_encode($data);
		$url = $this->url."Rujukan/Khusus/insert"; // url rilis
		$consID     = $this->consid; //customer ID RS
		$secretKey  = $this->secretkey; //secretKey RS
		$method = 'POST';
		$port = '80';
		$result = Requestor::set_new_curl_bridging($url, $params, $method, $consID, $secretKey, $port,'',''); // bridging data peserta bpjs
		if ($result === false) {
			echo "Tidak dapat menyambung ke server";
		} else {
			$respon = [
				'metaData'=>json_decode($result['metaData']),
				'response'=>json_decode($result['response'])
			];
		}
		return $respon;
	}
	/*========================================================
	========        HERE END of MODUL RUJUKAN       ========
	========================================================*/
	
	
	/*========================================================
	======== STARTS FROM HERE MODUL RENCANA KONTROL ========
	========================================================*/
	
	/* Modul vclaim -> kunjungan kontrol / inap */
	public function main_rencana_kontrol(){
		//db lokal site
		$this->data['classtutup'] = 'sidebar-collapse';
		$this->data['poli'] = Poli::all();
		return view('Admin.vclaim.rencana_kontrol.main')->with('data', $this->data);
	}
	
	/* Cari Nomor SEP & noKartu */
	public function cari_rencana_kontrol(Request $request){
		$element = json_decode($request->element);
		// return $request->element;
		
		$consID     = $this->consid; //customer ID RS
		$secretKey  = $this->secretkey; //secretKey RS
		$method = 'GET';
		$port = '80';
		$params = '';
		
		if ($request->isEdit == 'edit') {
			// EDIT
			$url = $this->url."RencanaKontrol/noSuratKontrol/".$element->noSuratKontrol;
			$urlPeserta = $this->url."/Peserta/nokartu/".$element->noKartu."/tglSEP/".$element->tglRencanaKontrol;
			
			$resultPeserta = Requestor::set_new_curl_bridging($urlPeserta, $params, $method, $consID, $secretKey, $port,'',''); // bridging peserta
			$result = Requestor::set_new_curl_bridging($url, $params, $method, $consID, $secretKey, $port,'',''); // bridging
			$arrPeserta = json_decode($resultPeserta['response']);
			
			if ($result === false) {
				return ['status' => 'error','message' => 'Tidak dapat menyambung ke server!'];
			} else {
				$respon = [
					'metaData' => json_decode($result['metaData']),
					'response' => json_decode($result['response']),
					'request' => $request->all(),
				];
			}
			
			if ($respon['metaData']->code != '200') {
				return ['status' => 'error','message' => $respon['metaData']->message];
			}
			// masukkan data peserta ke response
			$respon['response']->peserta = $arrPeserta->peserta;
		} else {
			// IF radio pilih = 2 / 1 (2 = rencana kontrol, 1 = rencana r. inap)
			if ($request->rdpilih == '1') {
				$txtnosep_0 = $request->txtnosep_0 ? $request->txtnosep_0 : '0000000000000000000';
				$url = $this->url."/Peserta/nokartu/".$txtnosep_0."/tglSEP/".$request->txttglrencanakontrol_0;
			} else {
				$txtnosep_0 = $request->txtnosep_0 ? $request->txtnosep_0 : '0000000000000000000';
				$url = $this->url."/RencanaKontrol/nosep/".$txtnosep_0;
			}
			
			$result = Requestor::set_new_curl_bridging($url, $params, $method, $consID, $secretKey, $port,'',''); // bridging
			if ($request->rdpilih == '2') {
				$resp_temp = (object)[
					'sep' => json_decode($result['response'])
				];
			} else {
				$resp_temp = json_decode($result['response']);
			}
			
			if ($result === false) {
				return ['status' => 'error','message' => 'Tidak dapat menyambung ke server!'];
			} else {
				$respon = [
					'metaData' => json_decode($result['metaData']),
					'response' => $resp_temp,
					'request' => $request->all(),
				];
				
				// masukkan data peserta ke response
				// array_push($respon['response'], $resultPeserta);
			}
		}
		// return $respon;
		if ($respon['metaData']->code != 200) {
			return ['status' => 'error','message' => $respon['metaData']->message];
		} else {
			// return $respon;
			$content = view('Admin.vclaim.rencana_kontrol.form', $respon)->render();
			return ['status' => 'success','content' => $content, 'response' => $respon];
		}
	}
	
	/* Tab List Rencana Kunjungan Kontrol / Inap */
	public function cari_list_rencana_kontrol(Request $request){
		$consID     = $this->consid; //customer ID RS
		$secretKey  = $this->secretkey; //secretKey RS
		$method = 'GET';
		$port = '80';
		$params = '';
		
		// IF filter = 2 / 1 (2 = tgl rencana kontrol, 1 = tgl 3ntri)
		$url = $this->url."/RencanaKontrol/ListRencanaKontrol/tglAwal/".$request->tgl1."/tglAkhir/".$request->tgl2."/filter/".$request->cbfilterrencanakontrol;
		
		$result = Requestor::set_new_curl_bridging($url, $params, $method, $consID, $secretKey, $port,'',''); // bridging
		
		if ($result === false) {
			return ['status' => 'error','message' => 'Tidak dapat menyambung ke server!'];
		} else {
			$respon = [
				'metaData' => json_decode($result['metaData']),
				'response' => json_decode($result['response']),
				'request' => $request->all(),
			];
		}
		return response()->json($respon);
	}
	
	/* Cari jadwal poli rujukan kontrol */
	public function cariPoliRujKontrol(Request $request){
		$consID     = $this->consid; //customer ID RS
		$secretKey  = $this->secretkey; //secretKey RS
		$method = 'GET';
		$port = '80';
		$params = '';
		
		// IF filter = 2 / 1 (2 = tgl rencana kontrol, 1 = tgl 3ntri)
		$url = $this->url."/RencanaKontrol/ListSpesialistik/JnsKontrol/".$request->param_1."/nomor/".$request->param_2."/TglRencanaKontrol/".$request->param_3;
		
		$result = Requestor::set_new_curl_bridging($url, $params, $method, $consID, $secretKey, $port,'',''); // bridging
		
		if ($result === false) {
			return ['status' => 'error','message' => 'Tidak dapat menyambung ke server!'];
		} else {
			$respon = [
				'metaData' => json_decode($result['metaData']),
				'response' => json_decode($result['response']),
				'request' => $request->all(),
			];
		}
		return response()->json($respon);
	}
	
	/* Cari dokter berdasarkan jadwal poli rujukan kontrol */
	public function cariDokterRujKontrol(Request $request){
		$obj = json_decode($request->obj);
		// return $request->all();
		$consID     = $this->consid; //customer ID RS
		$secretKey  = $this->secretkey; //secretKey RS
		$method = 'GET';
		$port = '80';
		$params = '';
		
		$url = $this->url."/RencanaKontrol/JadwalPraktekDokter/JnsKontrol/".$request->param1."/KdPoli/".$request->param2."/TglRencanaKontrol/".$request->param3;
		
		$result = Requestor::set_new_curl_bridging($url, $params, $method, $consID, $secretKey, $port,'',''); // bridging
		
		if ($result === false) {
			return ['status' => 'error','message' => 'Tidak dapat menyambung ke server!'];
		} else {
			$respon = [
				'metaData' => json_decode($result['metaData']),
				'response' => json_decode($result['response']),
				'request' => $request->all(),
				'obj' => $obj,
			];
		}
		return response()->json($respon);
	}
	
	public function storeRencanaKontrol(Request $request){
		// INIT BAHAN BRIDGING
		$consID     = $this->consid; //customer ID RS
		$secretKey  = $this->secretkey; //secretKey RS
		$port = '80';
		
		if ($request->noSuratKontrol != '-') {
			// EDIT
			$method = 'PUT';
			// IF radio pilih = 2 (rencana kontrol) / 1 (rencana r. inap)
			if ($request->jenisKontrol == '1') {
				$data =array(
					"request"=>[
						"noSPRI"=>$request->noSuratKontrol,
						"noKartu"=>$request->noSEP,
						"kodeDokter"=>$request->kodeDokter,
						"poliKontrol"=>$request->poliKontrol,
						"tglRencanaKontrol"=>$request->tglRencanaKontrol,
						"user"=> Auth::user()->name_user,
					]
				);
				$url = $this->url."/RencanaKontrol/UpdateSPRI";
			}else{
				$data =array(
					"request"=>[
						"noSuratKontrol"=>$request->noSuratKontrol,
						"noSEP"=>$request->noSEP,
						"kodeDokter"=>$request->kodeDokter,
						"poliKontrol"=>$request->poliKontrol,
						"tglRencanaKontrol"=>$request->tglRencanaKontrol,
						"user"=> Auth::user()->name_user,
					]
				);
				$url = $this->url."/RencanaKontrol/Update";
			}
		} else {
			// NEWDATA
			$method = 'POST';
			// IF radio pilih = 2 (rencana kontrol) / 1 (rencana r. inap)
			if ($request->jenisKontrol == '1') {
				$data =array(
					"request"=>[
						"noKartu"=>$request->noSEP,
						"kodeDokter"=>$request->kodeDokter,
						"poliKontrol"=>$request->poliKontrol,
						"tglRencanaKontrol"=>$request->tglRencanaKontrol,
						"user"=> Auth::user()->name_user,
					]
				);
				$url = $this->url."/RencanaKontrol/InsertSPRI";
			}else{
				$data =array(
					"request"=>[
						"noSEP"=>$request->noSEP,
						"kodeDokter"=>$request->kodeDokter,
						"poliKontrol"=>$request->poliKontrol,
						"tglRencanaKontrol"=>$request->tglRencanaKontrol,
						"user"=> Auth::user()->name_user,
					]
				);
				$url = $this->url."/RencanaKontrol/insert";
			}
		}
		
		$params = json_encode($data);
		$result = Requestor::set_new_curl_bridging($url, $params, $method, $consID, $secretKey, $port,'',''); // bridging
		
		if ($result === false) {
			echo "Tidak dapat menyambung ke server";
		} else {
			$respon = [
				'metaData'=>json_decode($result['metaData']),
				'response'=>json_decode($result['response']),
				'request' => $request->all(),
			];
		}
		
		return response()->json($respon);
	}
	
	/* Jadwal Rujukan Kontrol */
	public function destroyRencanaKontrol(Request $request){
		$data =array(
			"request"=>[
				"t_suratkontrol" => [
					"noSuratKontrol" => $request->no,
					"user" => Auth::user()->name_user,
				]
			]
		);
		$params = json_encode($data);
		// INIT BAHAN BRIDGING
		$consID     = $this->consid; //customer ID RS
		$secretKey  = $this->secretkey; //secretKey RS
		$port = '80';
		$method = 'DELETE';
		$url = $this->url."/RencanaKontrol/Delete";
		
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
	
	/*========================================================
	======== END MODUL RENCANA KONTROL ========
	========================================================*/
	
	/*
	MODUL RUJUKAN KHUSUS
	Terdapat 2 menu yaitu Form Rujukan dan Rujukan Khusus
	Function yang terdapat di modul ini yaitu
	1. main_form_prb
	2.
	*/
	/*========================================================
	========      STARTS FROM HERE MODUL PRB        ========
	========================================================*/
	
	public function main_form_prb(Request $request){
		$this->data['classtutup'] = 'sidebar-collapse';
		return view('Admin.vclaim.prb.main')->with('data', $this->data);
	}
	
	public function create_form_prb(Request $request){
		$this->data['classtutup'] = 'sidebar-collapse';
		$edit = $request->edit;
		
		$this->data['edit'] = $edit;
		if ($this->data['edit'] == 'true') {
			// CEK No SRB
			$sep = $request->no_sep ? $request->no_sep : '0000000000000000000';
			$url = $this->url."prb/".$request->noSRB."/nosep/".$request->no_sep; //url web dev service bpjs
			$consID     = $this->consid; //customer ID RS
			$secretKey  = $this->secretkey; //secretKey RS
			$method = 'GET';
			$port = '80';
			$params = '';
			
			$result = Requestor::set_new_curl_bridging($url, $params, $method, $consID, $secretKey, $port,'',''); // bridging data peserta bpjs
			if ($result === false) {
				echo "Tidak dapat menyambung ke server";
			} else {
				$respon = [
					'metaData'=>json_decode($result['metaData']),
					'response'=>json_decode($result['response'])
				];
			}
			$this->data['dataprb'] = $respon;
			$this->data['prb'] = VclaimPrb::where('noSrb',$request->noSRB)->first();
			$this->data['obat_prb'] = VclaimObatPrb::where('mst_prb_id',$this->data['prb']->id_prb)->get();
			$id = $this->data['prb']->id_prb;
		}
		$this->data['id'] = ($this->data['edit'] == 'true') ? $id : "";
		// CEK NO SEP
		$sep = $request->no_sep ? $request->no_sep : '0000000000000000000';
		$url = $this->url."SEP/".$request->no_sep; //url web dev service bpjs
		$consID     = $this->consid; //customer ID RS
		$secretKey  = $this->secretkey; //secretKey RS
		$method = 'GET';
		$port = '80';
		$params = '';
		
		$result = Requestor::set_new_curl_bridging($url, $params, $method, $consID, $secretKey, $port,'',''); // bridging data peserta bpjs
		if ($result === false) {
			echo "Tidak dapat menyambung ke server";
		} else {
			$respon = [
				'metaData'=>json_decode($result['metaData']),
				'response'=>json_decode($result['response'])
			];
		}
		$this->data['sep'] = $respon;
		
		if ($respon['metaData']->code == 200) {
			// CEK RUJUKAN
			$url2 = $this->url."Rujukan/".$respon['response']->noRujukan; //url web dev service bpjs
			$consID     = $this->consid; //customer ID RS
			$secretKey  = $this->secretkey; //secretKey RS
			$method = 'GET';
			$port = '80';
			$params = '';
			
			$result2 = Requestor::set_new_curl_bridging($url2, $params, $method, $consID, $secretKey, $port,'',''); // bridging data peserta bpjs
			if ($result2 === false) {
				echo "Tidak dapat menyambung ke server";
			} else {
				$respon2 = [
					'metaData'=>json_decode($result2['metaData']),
					'response'=>json_decode($result2['response'])
				];
			}
			$this->data['rujukan'] = $respon2;
			// return $this->data;
			$content = view('Admin.vclaim.prb.form')->with('data', $this->data)->render();
			return ['status'=>'success','content'=>$content];
		}else {
			return ['status'=>'error','message'=>$respon['metaData']->message,'content'=>''];
		}
	}
	
	//======= CEK Diagnosa PRB =========//
	public function cek_diagnosaprb(Request $request){
		// return urldecode($request->q);
		$sep = $request->q ? $request->q : '0000000000000000000';
		$url = $this->url."referensi/diagnosaprb"; //url web dev service bpjs
		// $url = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/peserta/nokartu/".$request->nobpjs."/tglsep/".date('Y-m-d'); //url web rilis baru service bpjs
		// $url = "http://api.bpjs-kesehatan.go.id:8080/vclaim-rest/peserta/nokartu/".$request->nobpjs."/tglsep/".date('Y-m-d'); //url web service bpjs
		
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
			// $respon = [
			//   'response'=>json_decode($result['response']->diagnosa)
			// ];
			return json_decode($result['response'],true);
		}
	}

	//======= CEK Dokter DPJP =========//
	public function cek_dokter_dpjp(Request $request){
		// return urldecode($request->q);
		$sep = $request->q ? $request->q : '0000000000000000000';
		$url = $this->url."referensi/dokter/pelayanan/".$request->rawat."/tglPelayanan/".$request->tanggal."/Spesialis/".$request->poli; //url web dev service bpjs
		// $url = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/peserta/nokartu/".$request->nobpjs."/tglsep/".date('Y-m-d'); //url web rilis baru service bpjs
		// $url = "http://api.bpjs-kesehatan.go.id:8080/vclaim-rest/peserta/nokartu/".$request->nobpjs."/tglsep/".date('Y-m-d'); //url web service bpjs
		
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
			// $respon = [
			//   'response'=>json_decode($result['response']->diagnosa)
			// ];
			return json_decode($result['response'],true);
		}
	}
	//======= CEK Obat PRB =========//
	public function cek_obat_prb(Request $request){
		// return urldecode($request->q);
		$sep = $request->q ? $request->q : '0000000000000000000';
		$url = $this->url."referensi/obatprb/".$request->kd_obat; //url web dev service bpjs
		// $url = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/peserta/nokartu/".$request->nobpjs."/tglsep/".date('Y-m-d'); //url web rilis baru service bpjs
		// $url = "http://api.bpjs-kesehatan.go.id:8080/vclaim-rest/peserta/nokartu/".$request->nobpjs."/tglsep/".date('Y-m-d'); //url web service bpjs
		
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
			// $respon = [
			//   'response'=>json_decode($result['response']->diagnosa)
			// ];
			return json_decode($result['response'],true);
		}
	}
	
	//======= Insert PRB =========//
	public function storePrb(Request $request){
		$jmlObat      = json_decode($request->jmlObat);
		$kode_obat    = json_decode($request->kode_obat);
		$nama_obat    = json_decode($request->nama_obat);
		$signa1       = json_decode($request->signa1);
		$signa2       = json_decode($request->signa2);
		
		$data = [
			"request"=>[
				"t_prb"=> [
					"noSep"     =>$request->noSep,
					"noKartu"   =>$request->noKartu,
					"alamat"    =>$request->alamat,
					"email"     =>$request->email,
					"programPRB"    =>$request->txtnmdiagnosa,
					"kodeDPJP"      =>$request->txtnmdpjpPelayanan,
					"keterangan"    =>$request->keterangan,
					"saran"         =>$request->saran,
					"user"          =>'1'
				]
			]
		];
		$obat_temp = [];
		for($i=0; $i<count($jmlObat);$i++){
			$item = [
				"kdObat"=>$kode_obat[$i],
				"signa1"=>$signa1[$i],
				"signa2"=>$signa2[$i],
				"jmlObat"=>$jmlObat[$i]
			];
			array_push($obat_temp, $item);
		}
		$data['request']['t_prb']['obat'] = $obat_temp;
		
		$params = json_encode($data);
		$url = $this->url."PRB/insert"; //url web dev service bpjs
		
		$consID     = $this->consid; //customer ID RS
		$secretKey  = $this->secretkey; //secretKey RS
		$method     = 'POST';
		$port    = '80';
		
		$result = Requestor::set_new_curl_bridging($url, $params, $method, $consID, $secretKey, $port,'',''); // bridging data peserta bpjs
		if ($result === false) {
			echo "Tidak dapat menyambung ke server";
		} else {
			$respon = [
				'metaData'     => json_decode($result['metaData']),
				'response'     => json_decode($result['response']),
			];
			if ($respon['metaData']->code == 200) {
				$dtP          = new VclaimPrb;
				$dtP->noSRB     = $respon['response']->noSRB;
				$dtP->noKartu   = $respon['response']->peserta->noKartu;
				$dtP->tglSRB    = $respon['response']->tglSRB;
				$dtP->namaPeserta   = $respon['response']->peserta->nama;
				$dtP->programPRB    = $request->txtnmdiagnosa;
				$dtP->noSEP         = $request->noSep;
				$dtP->alamat        = $request->alamat;
				$dtP->email         = $request->email;
				$dtP->kodeDPJP    = $request->txtnmdpjpPelayanan;
				$dtP->namaDPJP    = $request->txtnmdpjpPelayanan;
				$dtP->keterangan  = $request->keterangan;
				$dtP->saran       = $request->saran;
				$dtP->users_id    = '1';
				$dtP->save();
				if ($dtP) {
					for ($i=0; $i <count($kode_obat) ; $i++) {
						
						$dtR                = new VclaimObatPrb;
						$dtR->mst_prb_id    = $dtP->id_prb;
						$dtR->kdObat        = $kode_obat[$i];
						$dtR->nmObat        = $nama_obat[$i];
						$dtR->signa1        = $signa1[$i];
						$dtR->signa2        = $signa2[$i];
						$dtR->jmlObat       = $jmlObat[$i];
						$dtR->save();
					}
				}
				return $respon;
			}else {
				return $respon;
			}
		}
	}

	public function listPRB(Request $request){
		date_default_timezone_set('Asia/Jakarta');
		$url = $this->url."prb/tglMulai/".$request->tgl_awal."/tglAkhir/".$request->tgl_akhir; // url rilis
		$consID     = $this->consid; //customer ID RS
		$secretKey  = $this->secretkey; //secretKey RS
		$method = 'GET';
		$port = '80';
		$params = '';
		
		$result = Requestor::set_new_curl_bridging($url, $params, $method, $consID, $secretKey, $port,'',''); // bridging data peserta bpjs
		
		if ($result === false) {
			return ['status' => 'error', 'message' => 'Tidak Terhubung ke Server !!'];
		}else{
			$respon = [
				'metaData'     => json_decode($result['metaData']),
				'response'     => json_decode($result['response']),
			];
			return $respon;
		}
	}

	//======= Update PRB =========//
	public function updatePrb(Request $request){
		$jmlObat      = json_decode($request->jmlObat);
		$kode_obat    = json_decode($request->kode_obat);
		$nama_obat    = json_decode($request->nama_obat);
		$signa1       = json_decode($request->signa1);
		$signa2       = json_decode($request->signa2);
		
		$data = [
			"request"=>[
				"t_prb"=> [
					"noSrb"     =>$request->noSRB,
					"noSep"     =>$request->noSep,
					"noKartu"   =>$request->noKartu,
					"alamat"    =>$request->alamat,
					"email"     =>$request->email,
					// "programPRB"    =>$request->txtnmdiagnosa,
					"kodeDPJP"      =>$request->txtnmdpjpPelayanan,
					"keterangan"    =>$request->keterangan,
					"saran"         =>$request->saran,
					"user"          =>'1'
				]
			]
		];
		// return $data;
		$obat_temp = [];
		for($i=0; $i<count($jmlObat);$i++){
			$item = [
				"kdObat"=>$kode_obat[$i],
				"signa1"=>$signa1[$i],
				"signa2"=>$signa2[$i],
				"jmlObat"=>$jmlObat[$i]
			];
			array_push($obat_temp, $item);
		}
		$data['request']['t_prb']['obat'] = $obat_temp;
		
		$params = json_encode($data);
		$url = $this->url."PRB/Update"; //url web dev service bpjs
		
		$consID     = $this->consid; //customer ID RS
		$secretKey  = $this->secretkey; //secretKey RS
		$method     = 'PUT';
		$port    = '80';
		
		$result = Requestor::set_new_curl_bridging($url, $params, $method, $consID, $secretKey, $port,'',''); // bridging data peserta bpjs
		if ($result === false) {
			echo "Tidak dapat menyambung ke server";
		} else {
			$respon = [
				'metaData'     => json_decode($result['metaData']),
				'response'     => json_decode($result['response']),
			];
			if ($respon['metaData']->code == 200) {
				$dtP          = VclaimPrb::find($request->id);
				$dtP->noSRB     = $respon['response'];
				$dtP->noKartu   = $request->noKartu;
				$dtP->tglSRB    = $request->tglSRB;
				// $dtP->namaPeserta   = $respon['response']->peserta->nama;
				// $dtP->programPRB    = $request->txtnmdiagnosa;
				$dtP->noSEP         = $request->noSep;
				$dtP->alamat        = $request->alamat;
				$dtP->email         = $request->email;
				$dtP->kodeDPJP    = $request->txtnmdpjpPelayanan;
				$dtP->namaDPJP    = $request->txtnmdpjpPelayanan;
				$dtP->keterangan  = $request->keterangan;
				$dtP->saran       = $request->saran;
				$dtP->users_id    = '1';
				$dtP->save();
				// return $dtP;
				if ($dtP) {
					$cekObat = VclaimObatPrb::where('mst_prb_id', $dtP->id_prb)->whereNotIn('kdObat',$kode_obat)->get();
					if (!empty($cekObat)) {
						foreach ($cekObat as $idDelPrinSal) {
							$deletePrinSal = VclaimObatPrb::where('id_rincian_obat', $idDelPrinSal->id_rincian_obat)->delete();
						}
					}
					
					for ($i=0; $i <count($kode_obat) ; $i++) {
						$cekobat_new = VclaimObatPrb::where('mst_prb_id', $dtP->id_prb)->where('kdObat',$kode_obat[$i])->first();
						if (empty($cekobat_new)) {
							$dtR                = new VclaimObatPrb;
							$dtR->mst_prb_id    = $dtP->id_prb;
							$dtR->kdObat        = $kode_obat[$i];
							$dtR->nmObat        = $nama_obat[$i];
							$dtR->signa1        = $signa1[$i];
							$dtR->signa2        = $signa2[$i];
							$dtR->jmlObat       = $jmlObat[$i];
							$dtR->save();
						}
					}
				}
				return $respon;
			}else {
				return $respon;
			}
		}
	}

	//======= Hapus PRB =========//
	public function hapusPrb(Request $request){
		$data =array(
			"request"=>[
				"t_rujukan"=> [
					"noSrb"=>$request->noSRB,
					"noSep"=>$request->noSep,
					"user"=> Auth::user()->name_user,
				]
			]
		);
		$params = json_encode($data);
		$url = $this->url."PRB/Delete"; //url web dev service bpjs
		// $url = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/peserta/nokartu/".$request->nobpjs."/tglsep/".date('Y-m-d'); //url web rilis baru service bpjs
		// $url = "http://api.bpjs-kesehatan.go.id:8080/vclaim-rest/peserta/nokartu/".$request->nobpjs."/tglsep/".date('Y-m-d'); //url web service bpjs
		
		$consID     = $this->consid; //customer ID RS
		$secretKey  = $this->secretkey; //secretKey RS
		$method = 'DELETE';
		// $port = '8080';
		$port = '80';
		$result = Requestor::set_new_curl_bridging($url, $params, $method, $consID, $secretKey, $port,'',''); // bridging data peserta bpjs
		if ($result === false) {
			echo "Tidak dapat menyambung ke server";
		} else {
			$respon = [
				'metaData'=>json_decode($result['metaData']),
				'response'=>json_decode($result['response'])
			];
			if ($respon['metaData']->code == 200) {
				$delPrb = VclaimPrb::find($request->id);
				$cekObat = VclaimObatPrb::where('mst_prb_id', $dtP->id_prb)->get();
				if (!empty($cekObat)) {
					foreach ($cekObat as $idDelPrinSal) {
						$deletePrinSal = VclaimObatPrb::where('id_rincian_obat', $idDelPrinSal->id_rincian_obat)->delete();
					}
				}
				$delPrb->delete();
				if ($delPrb) {
					return $respon;
				}else {
					echo "Tidak dapat Menyimpan Data";
				}
			}else {
				return $respon;
			}
		}
	}
	
	//======= Cetak PRB =========//
	public function cetakPrb(Request $request){
		// CEK No SRB
		$sep = $request->no_sep ? $request->no_sep : '0000000000000000000';
		$url = $this->url."prb/".$request->noSRB."/nosep/".$request->noSep; //url web dev service bpjs
		$consID     = $this->consid; //customer ID RS
		$secretKey  = $this->secretkey; //secretKey RS
		$method = 'GET';
		$port = '80';
		$params = '';
		
		$result = Requestor::set_new_curl_bridging($url, $params, $method, $consID, $secretKey, $port,'',''); // bridging data peserta bpjs
		if ($result === false) {
			echo "Tidak dapat menyambung ke server";
		} else {
			$respon = [
				'metaData'=>json_decode($result['metaData']),
				'response'=>json_decode($result['response'])
			];
		}
		$this->data['dataprb'] = $respon;
		$this->data['prb'] = VclaimPrb::where('noSrb',$request->noSRB)->first();
		$this->data['obat_prb'] = VclaimObatPrb::where('mst_prb_id',$this->data['prb']->id_prb)->get();
		$id = $this->data['prb']->id_prb;
		return $this->data;
	}
	/*========================================================
	========          HERE END of MODUL PRB         ========
	========================================================*/
}