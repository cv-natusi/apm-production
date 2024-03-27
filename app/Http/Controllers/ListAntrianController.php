<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Helpers\apm as Help;
use App\Http\Controllers\Controller;
use App\Http\Models\Antrian;
use App\Http\Models\AntPasienBaru;
use App\Http\Models\Desa;
use App\Http\Models\Kabupaten;
use App\Http\Models\Kecamatan;
use App\Http\Models\Provinsi;
use App\Http\Models\Bridgingpoli;
use App\Http\Models\Rsu_Register;
use App\Http\Models\rsu_customer;
use App\Http\Models\Rsu_setupall;
use App\Http\Models\Rsu_Bridgingpoli;
use App\Http\Models\Rsu_cc;
use App\Http\Models\rsu_poli;
use App\Http\Models\rsu_dokter_bridging;
use App\Http\Models\Rsu_RiwayatRegistrasi;
use Redirect, Validator, Datatables, DB, Auth, DateTime;

class ListAntrianController extends Controller{
	public function getAntrianLoket(Request $request){
		if ($request->ajax()) {
			$today = date('Y-m-d');
			$botDaPas = DB::connection('mysql')->table('bot_data_pasien')
				->whereBetween('tglBerobat',[$request->tglAwal,$request->tglAkhir])
				->get();

			$simapan = DB::connection('mysql')->table('pasien_baru_temporary')
				->whereBetween('tanggalPeriksa',[$request->tglAwal,$request->tglAkhir])
				->get();

			$data = Antrian::with(['tm_customer','mapping_poli_bridging.tm_poli'])
				->whereIn('status', ['panggil', 'belum'])
				->whereBetween('tgl_periksa', [$request->tglAwal, $request->tglAkhir])
				->orderBy('id','asc')
				->get();

			$namaPasien ="";
			foreach($data as $keyAnt => $valAnt){
				$cekMetod = $valAnt->metode_ambil;
				foreach($botDaPas as $keyBot => $valBot){
					if($cekMetod=="WA" && $valAnt->nik==$valBot->nik && $valAnt->tgl_periksa==$valBot->tglBerobat){
						$namaPasien = $valBot->nama;
					}
				}
				foreach($simapan as $key => $val){
					if($cekMetod=="SIMAPAN" && $valAnt->nik==$val->nik && $valAnt->tgl_periksa==$val->tanggalPeriksa){
						$namaPasien = $val->nama;
					}
				}
				$valAnt->namaPasien = $namaPasien;
				$namaPasien = '';
			}

            return Datatables::of($data)
			->addIndexColumn()
			->addColumn('action',function($row){
				$a = $this->templateAction($row);
				return $a;
			})
			->addColumn('namaCust',function($row){
				if($row->metode_ambil=='WA'||$row->metode_ambil=='SIMAPAN'){
					return $row->namaPasien;
				}else{
					if(isset($row->tm_customer)){
						return $row->tm_customer->NamaCust;
					}else{
						return '-';
					}
				}
			})
			->make(true);
		}
		return view('Admin.antreanBPJS.listAntrian.mainLoket');
	}

	public function main(Request $request){
		$data['data'] = 'Normal';

		return view('Admin.antreanBPJS.listAntrian.mainLoket');
	}

	function templateAction($data){
		$btn = "<div class='text-center'>";
		$btn .= "<button class='btn btn-sm btn-primary' title='Panggil' style='margin-left: 5px'; onclick='panggil(`$data->kode_booking`)'><i class='fa fa-bullhorn' aria-hidden='true'></i></button><br>";
		$btn .= "<button class='btn btn-sm btn-success' title='Kerjakan' style='margin-left: 5px; margin-top: 5px'; onclick='kerjakan(`$data->id`)'><i class='fa fa-file' aria-hidden='true'></i></button><br>";
		$btn .= "<button class='btn btn-sm btn-danger' title='Batalkan' style='margin-left: 5px; margin-top: 5px;' onclick='batalkan(`$data->kode_booking`)'><i class='fa fa-remove' aria-hidden='true'></i></button>";
		// $btn .= "<button class='btn btn-sm btn-warning' title='Cetak SEP' style='margin-top: 5px;' onclick='cetaksep()'><i class='fa fa-print' aria-hidden='true'></i></button> &nbsp;";
		$btn .= "</div>";
		return $btn;
	}
	
	public function kerjakanAntrian(Request $request){
		$id = $request->id;
		$view = $request->view ?? 0;

		$antrian = Antrian::where('id', $id)->first();
		if($antrian->no_rm=='00000000000'){
			if($antrian->metode_ambil=='WA'){
				$this->data['getAntrian'] = Antrian::with(['mapping_poli_bridging.tm_poli'])
					->join('bot_data_pasien as bdp','antrian.nik','=','bdp.nik')
					->where('antrian.id',$id)
					// ->where('bdp.tglBerobat',date('Y-m-d'))
					->where('bdp.tglBerobat',$antrian->tgl_periksa)
					->first();
			}else if($antrian->metode_ambil=='SIMAPAN'){
				$this->data['getAntrian'] = Antrian::with(['mapping_poli_bridging.tm_poli'])
					->join('pasien_baru_temporary as pbt','antrian.nik','=','pbt.nik')
					->where('antrian.id',$id)
					->where('pbt.tanggalPeriksa',$antrian->tgl_periksa)
					->first();
			}else{
				$this->data['getAntrian'] = Antrian::with(['mapping_poli_bridging.tm_poli'])
					->where('id',$id)
					->first();
			}
		}else{
			$this->data['getAntrian'] = Antrian::with(['tm_customer','mapping_poli_bridging.tm_poli'])
				->where('id',$id)
				->first();

			// Get Data AntPasienBaru
			$cust_id = $this->data['getAntrian']->tm_customer->cust_id;
			$vl = $this->data['getAntrian']->tm_customer;
			$antrianPasienBaru = AntPasienBaru::where('cust_id', $cust_id)->first();
			if(!empty($antrianPasienBaru)){
				$namaProv = !empty(Provinsi::where('id', $antrianPasienBaru->provinsi_id)->first()) ? Provinsi::where('id', $antrianPasienBaru->provinsi_id)->first()->name : "";
			}else{
				$namaProv = "";
			}
			$namaKab = !empty(Kabupaten::where('id', $vl->Kota)->first()) ? Kabupaten::where('id', $vl->Kota)->first()->id : "";
			$namaKec = !empty(Kecamatan::where('id', $vl->kecamatan)->first()) ? Kecamatan::where('id', $vl->kecamatan)->first()->id : "";
			$namaKel = !empty(Desa::where('id', $vl->kelurahan)->first()) ? Desa::where('id', $vl->kelurahan)->first()->id : "";

			$this->data['prov'] = $namaProv;
			$this->data['kab'] = $namaKab;
			$this->data['kec'] = $namaKec;
			$this->data['kel'] = $namaKel;
			$this->data['getAntPasBaru'] = $antrianPasienBaru;
		}
		$this->data['jenis_pasien'] = '';
		if ($antrian->jenis_pasien == 'ASURANSILAIN') {
			$this->data['jenis_pasien'] = Rsu_setupall::where('groups','Asuransi')->get();
		}
		$this->data['from'] = $this->data['getAntrian']->metode_ambil;
		$this->data['data_provinsi'] = Provinsi::all();
		$this->data['poli'] = Rsu_Bridgingpoli::join('tm_poli', 'mapping_poli_bridging.kdpoli_rs', '=', 'tm_poli.KodePoli')
		->get();
		$this->data['view'] = $view;

		$content = view('Admin.antreanBPJS.listAntrian.form', $this->data)->render();

		return ['status' => 'success', 'content' => $content, 'data' => $this->data];
	}

	public function formListCounter() {
		return view('Admin.antreanBPJS.listAntrian.mainCounterPoli');
	}

	public function saveList(Request $request){
		// DB::beginTransaction();
		// try {
			$id = $request->id;
			$id_antrian = $request->id_antrian;
			$norm = $request->no_rm;
			$cekAntri = Antrian::where('id', $id_antrian)->first();
			$jenisPas = $cekAntri->jenis_pasien;
			$Customer = rsu_customer::where('KodeCust', $norm)->first();
			if (empty($Customer)) {
				$Customer = rsu_customer::where('NoKtp', $request->nik)->first();
				if (empty($Customer)) {
					$Customer = New rsu_customer;
				}
			}
			// return $cekAntri;
			$Customer->NamaCust = $request->nama;
			$Customer->NoKtp = $request->nik;
			$Customer->Tempat = $request->tmpt_lahir;
			$Customer->JenisKel = $request->jenis_kelamin;
			$Customer->Agama = $request->agama;
			$Customer->Pekerjaan = $request->pekerjaan;
			$Customer->status = $request->s_perkawinan;
			$Customer->warganegara = $request->kewarganegaraan;
			$Customer->goldarah = $request->gol_darah;
			$Customer->goldarah = $request->rt;
			$Customer->goldarah = $request->gol_darah;
			# HITUNG UMUR START
			$tglLahir = $request->tgl_lahir;
			$tanggal  = new DateTime($tglLahir);
			$today	  = new DateTime('today');
			$umur     = $today->diff($tanggal)->y;
			# HITUNG UMUR END
			$Customer->umur = $umur;
			$Customer->Alamat = $request->alamat;
			$Customer->kelurahan = $request->desa_id;
			$Customer->kecamatan = $request->kecamatan_id;
			$Customer->Kota = $request->kabupaten_id;
			$Customer->rt = $request->rt;
			$Customer->rw = $request->rw;
			$Customer->Telp = $request->telp;
			$Customer->FieldCust1 = isset($request->nobpjs)?$request->nobpjs:null;
			$Customer->TglLahir = $request->tgl_lahir;
			$Customer->save();
			if (!$Customer) {
				// DB::rollback();
				return ['type'=>'warning','status'=>'error','code'=>400,'head_message'=>'Whooops!','message'=>'Gagal menyimpan customer','antrian'=>''];
			}
			$cekAntri->no_rm = $norm;
			$cekAntri->nohp  = $request->telp;
			$cekAntri->kode_poli = $request->poli;
			$cekAntri->save();
			if(!$cekAntri){
				// DB::rollback();
				return ['type'=>'warning','status'=>'error','code'=>400,'head_message'=>'Whooops!','message'=>'Gagal update antrian','antrian'=>''];
			}
			$custId = $Customer->cust_id;
			$PasienBaru = AntPasienBaru::where('cust_id',$custId)->first();
			if(empty($PasienBaru)){
				$PasienBaru = new AntPasienBaru;
				$PasienBaru->cust_id = $Customer->cust_id;
			}
			$PasienBaru->provinsi_id = $request->provinsi_id;
			$PasienBaru->kabupaten_id = $request->kabupaten_id;
			$PasienBaru->kecamatan_id = $request->kecamatan_id;
			$PasienBaru->desa_id = $request->desa_id;
			$PasienBaru->rt = $request->rt;
			$PasienBaru->rw = $request->rw;
			$PasienBaru->nik = $request->nik;
			$PasienBaru->no_rm = $Customer->KodeCust;
			$PasienBaru->pen_jawab = $request->pen_jawab;
			$PasienBaru->nama_pen_jawab = $request->nama_pen_jawab;
			$PasienBaru->pend_terakhir = $request->pend_terakhir;
			$PasienBaru->save();
			if(!$PasienBaru){
				// DB::rollback();
				return ['type'=>'warning','status'=>'error','code'=>400,'head_message'=>'Whooops!','message'=>'Gagal simpan antrian pasien baru','antrian'=>''];
			}
			
			// $antrianTracer = $this->antrianTracer($id_antrian,'loket','poli',1,'input');
			// 	return 'ok';
			// if(!$antrianTracer){
			// 	DB::rollback();
			// 	return ['type'=>'warning','status'=>'error','code'=>400,'head_message'=>'Whooops!','message'=>'Gagal simpan antrian tracer','antrian'=>''];
			// }
			$request->kodebooking = $cekAntri->kode_booking;
			$request->waktu = strtotime(date('Y-m-d H:i:s'))*1000;
			$request->taskid = '2';
			$bridgBpjs = new BridgBpjsController;
			$updateWaktu = $bridgBpjs->updateWaktu($request);
			$getAntrian = Antrian::where('id', $id_antrian)->first();
			//insert antrian_id di table filling
			$insertFilling = DB::connection('mysql')->table('filling')
				->insert([
					'no_rm' => $norm,
					'tgl_periksa' => $cekAntri->tgl_periksa,
					'antrian_id' => $id_antrian
				]);

			if(!$insertFilling){
				// DB::rollback();
				return ['type'=>'warning','status'=>'error','code'=>400,'head_message'=>'Whooops!','message'=>'Gagal simpan filling','antrian'=>''];
			}
			// DB::commit();
			return ['type'=>'success','status'=>'success','code'=>200,'head_message'=>'Berhasil!','message'=>'Berhasil menyimpan data','antrian' => $getAntrian];
		// } catch (\Throwable $e) {
		// 	DB::rollback();
		// 	Log::info(['dwialim',$e]);
		// 	$log = ['ERROR STORE CUSTOMER ('.$e->getFile().')',false,$e->getMessage(),$e->getLine()];
  //           Help::logging($log);
		// 	return ['code'=>500, 'status'=>'error','message'=>'Terjadi kesalahan sistem'];
		// }
	}

	// public function storeRsuRegister($req){
	// 	$idCust = $req->id;
	// 	$idAntri = $req->id_antrian;
	// 	$jenisKel = $req->jenis_kelamin;

	// 	$cekAntri = Antrian::where('id', $idAntri)->first();
	// 	$tmCust = DB::connection('dbrsud')->table('tm_customer')
	// 		->where('cust_id', $idCust)->first();
	// 	$poli = Rsu_Bridgingpoli::where('kdpoli', $cekAntri->kode_poli)->select('kdpoli_rs')->first(); //get poli rs -> DB Rsu

	// 	$jam = date('H:i:s');
	// 	$noRM = $cekAntri->no_rm;

	// 	// hitung umur start
	// 		$tglLahir = $req->tgl_lahir;
	// 		$tanggal  = new DateTime($tglLahir);
	// 		$today	 = new DateTime('today');
	// 		$umurReg  = $today->diff($tanggal)->y;
	// 	// hitung umur end

	// 	$tg   = date('y');
	// 	$tg   = $tg.'2';
	// 	$thn  = date('Y'); $mo = date('m'); $da = date('d');
	// 	$urut = Rsu_Register::whereRaw("(YEAR(Tgl_Register) = $thn) AND LEFT(No_Register,3) = $tg ")
	// 		->orderby('No_Register','DESC')->first(); //db rsu use

	// 	$reg = new Rsu_Register; // db rsu
	// 	if($urut){
	// 		$nourut = $urut->No_Register + 1;
	// 	}else{
	// 		$nourut = date('y').'20000001';
	// 	}
	// 	$reg->TransReg         = 'RE';
	// 	$reg->No_Register      = $nourut;
	// 	$reg->Tgl_Register     = date('Y-m-d H:i:s',strtotime($cekAntri->tgl_periksa.$jam));
	// 	$reg->Jam_Register     = $jam;
	// 	$reg->No_RM            = $noRM;
	// 	$reg->Nama_Pasien      = $req->nama;
	// 	$reg->AlamatPasien     = $req->alamat;
	// 	$reg->Umur             = $umurReg;
	// 	$reg->Kode_Ass         = null;
	// 	$reg->Kode_Poli1       = isset($poli->kdpoli_rs)?$poli->kdpoli_rs:null;
	// 	$reg->JenisKel         = isset($tmCust->JenisKel)?$tmCust->JenisKel:(!empty($jenisKel) ? $jenisKel:null);
	// 	$reg->Rujukan          = null;
	// 	$reg->NoSEP            = null;
	// 	$reg->NoPeserta        = null;
	// 	$reg->Biaya_Registrasi = 0;
	// 	$reg->Status           = 'Belum Dibayar';
	// 	$reg->NamaAsuransi     = 'UMUM';
	// 	$reg->Japel            = 0;
	// 	$reg->JRS              = 0;
	// 	$reg->TipeReg          = 'REG';
	// 	$reg->SudahCetak       = 'N';
	// 	$reg->BayarPendaftaran = 'N';
	// 	$reg->Tgl_Lahir        = $tglLahir;
	// 	$reg->isKaryawan       = 'N';
	// 	$reg->isProcessed      = 'N';
	// 	$reg->isPasPulang      = 'N';
	// 	$reg->Jenazah          = 'N';
	// 	$reg->save();

	// 	$param = (object)[
	// 		'No_Register' => $nourut,
	// 		'No_RM'       => $noRM,
	// 		'kode_dokter' => $cekAntri->kode_dokter,
	// 	];
	// 	return$storeRiwayatRegis = $this->storeRiwayatRegis($param);
	// }

	// public function storeRiwayatRegis($param){
	// 	$getDpjp = rsu_dokter_bridging::where('kodedokter',$param->kode_dokter)->first();
	// 	$addHistory = new Rsu_RiwayatRegistrasi; // db rsu
	// 	$addHistory->No_Register = $param->No_Register;
	// 	$addHistory->no_rm       = $param->No_RM;
	// 	$addHistory->kode_dpjp   = $param->kode_dokter;
	// 	$addHistory->nama_dpjp   = $getDpjp->dokter;
	// 	$addHistory->poli_bpjs   = $getDpjp->polibpjs;
	// 	$addHistory->save();
	// 	return $addHistory;
	// }

	public function batalAntrian(Request $request){
		$respon = '';
		$kodebooking = $request->kodebooking;

		$antrian = Antrian::where('kode_booking', $kodebooking)->first();
		if(!empty($antrian)){
			// if ($antrian->status == 'belum' || $antrian->status == 'panggil') {
				$batalAntrian = new BridgBpjsController;
				$respon = $batalAntrian->batalAntrean($request);
				if($respon['metaData']->code == 200){
					$antrian->alasan_batal = $request->keterangan;
					$antrian->status = 'batal';
					$antrian->save();
					$data = [
						'status'=>'success',
						'code'=>200,
						'head_message'=>'Success',
						'message'=>'Berhasil Membatalkan No Antrian.',
						'data'=> $antrian,
					];
				}else{
					// KETIKA GAGAL KIRIM KE BPJS
					$data = [
						'status'=>'Error',
						'code'=>$respon['metaData']->code,
						'head_message'=>'Warning',
						'message'=>$respon['metaData']->message,
						'data'=> $antrian,
					];
				}
			// }
			// END STATUS BELUM DAN DIKIRIM KE BPJS
		}else {
			// KETIKA NO ANTRIAN KOSONG
			$data = [
				'status'=>'Error',
				'code'=>201,
				'head_message'=>'Warning',
				'message'=>'No Antrian Kosong',
				'data'=> $antrian,
			];
		}
		return response()->json($respon);
	}

	public function panggilAntrian(Request $request){
		$user = \Auth::user()->lv_user;
		if(!str_contains($user, "loket")){
			$user = "loket1";
		}
		date_default_timezone_set("Asia/Jakarta");
		$id = $request->id;
		$antrian = Antrian::where('kode_booking', $id)->first();
		$antrian->status = 'panggil';
		$antrian->save();

		if($antrian){
			//update antrian tracer memberi tujuan loket
			$cekAntrianTracer = DB::connection('mysql')->table('antrian_tracer')
				->where('antrian_id',$antrian->id)
				->first()->loket;
			if(empty($cekAntrianTracer)){
				$antrianTracer = DB::connection('mysql')->table('antrian_tracer')
					->where('antrian_id',$antrian->id)
					->update(['loket' => $user]);
			}

			$split = substr($antrian->no_antrian,0,1);
			if($split=='B'){
				$request->kodebooking = $antrian->kode_booking;
				$request->waktu = strtotime(date('Y-m-d H:i:s'))*1000;
				$request->taskid = '2';
				$bridgBpjs = new BridgBpjsController;
				$updateWaktu = $bridgBpjs->updateWaktu($request);
			}

			$data = [
				'type'=>'success',
				'status'=>'success',
				'code'=>200,
				'alert'=>'alert',
				'head_message'=>'Success!',
				'message'=>'Antrian berhasil dipanggil',
			];
		}else{
			$data = [
				'type'=>'warning',
				'status'=>'error',
				'code'=>300,
				'alert'=>'alert',
				'head_message'=>'Whooops!',
				'message'=>'Antrian gagal dipanggil',
			];
		}

		return $data ;
	}

	public function cetakRMAntrian(Request $request){
		date_default_timezone_set("Asia/Jakarta");

		// Generate NO.RM
		$getKode = DB::connection('dbrsud')->table('tm_customer')->max('KodeCust');
		$num = (int)substr($getKode, 5);
		$nextKode = 'W'.date("ym").(string)($num+1);
		$no_rm = $nextKode;

		$id = $request->id;
		$antrian = Antrian::where('kode_booking', $id)->first();
		$antrian->no_rm = $no_rm;
		$antrian->save();

		if($antrian){
			// DBSIMARS_BARU
			$cust = new rsu_customer;
			$cust->KodeCust = $no_rm;
			$cust->NoKtp = $antrian->nik;
			$cust->save();

			$data = [
				'nomor' => $no_rm,
				'type'=>'success',
				'status'=>'success',
				'code'=>200,
				'alert'=>'alert',
				'head_message'=>'Success!',
				'message'=>'Antrian berhasil cetak RM',
			];
		}else{
			$data = [
				'type'=>'warning',
				'status'=>'error',
				'code'=>300,
				'alert'=>'alert',
				'head_message'=>'Whooops!',
				'message'=>'Antrian gagal cetak RM',
			];
		}

		return $data ;
	}

	public function cariFormPasien(Request $request){
		$content = view('Admin.antreanBPJS.listAntrian.cariFormPasien')->render();
		return ['status' => 'success', 'content' => $content];
	}

	public function getDatapasien(Request $request){
		$data = rsu_customer::with('antrian_pasien_baru')->where('KodeCust', $request->key)->first();
		$return = ['status'=>'success','code'=>200,'data'=>$data];
		return response()->json($return);
	}

	public function cariDataPasien(Request $request){
		$key = $request->key;
		$alamat = $request->alamat;
		$gopage = ($request->gopage) ? $request->gopage : 0;
		if ($request->gopage != 0) {
			$start = ($request->gopage - 1) * 15;
		}else{
			$start = ($request->start) ? $request->start : 0;
		}
		$end = ($request->end) ? $request->end : 15;
		// db rsu
		$data = rsu_customer::where('FieldCust1','like',"%$key%")
			->orWhere('NoKtp','like',"%$key%")
			->orWhere('KodeCust','like',"%$key%")
			->orWhere('NamaCust','like',"%$key%")
			->where('Alamat','like',"%$alamat%")
			->offset($start)->limit($end)->get();

		$sum = rsu_customer::where('FieldCust1','like',"%$key%")
			->orWhere('NoKtp','like',"%$key%")
			->orWhere('KodeCust','like',"%$key%")
			->orWhere('NamaCust','like',"%$key%")
			->where('Alamat','like',"%$alamat%")->count();

		if(!empty($data)){
			$return = ['status'=>'success','code'=>200,'data'=>$data,'sum'=>$sum,'gopage'=>$gopage];
		}else{
			$return = ['status'=>'error','code'=>404,'data'=>$data,'sum'=>$sum,'gopage'=>$gopage];
		}
		return response()->json($return);
	}

	function queryCariData($data,$count,$jenis,$kolom,$start='',$end=''){
		if($count=='N'){
			if($jenis=='like'){
				$res = rsu_customer::where("$kolom",'like',"%$data%")->offset($start)->limit($end)->get();
			}else{
				$res = rsu_customer::where("$kolom",$data)->offset($start)->limit($end)->get();
			}
			return $res;
		}else{
			if($jenis=='like'){
				$sum = rsu_customer::where("$kolom",'like',"%$data%")->count();
			}else{
				$sum = rsu_customer::where("$kolom",$data)->count();
			}
			return $sum;
		}
	}

	public function dataGridLoket(Request $request){
		$data = Antrian::getJsonLoket($request);
		return response()->json($data);
	}
}