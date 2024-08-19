<?php

namespace App\Http\Controllers\Antrian;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BridgBpjsController;

# Helpers
use App\Helpers\apm as Help;
use TM;

# Library / package
use Datatables, DateTime, DB;
# Models
use App\Http\Models\Antrian;
use App\Http\Models\AntPasienBaru;
use App\Http\Models\Desa;
use App\Http\Models\Kabupaten;
use App\Http\Models\Kecamatan;
use App\Http\Models\Provinsi;
use App\Http\Models\Rsu_Bridgingpoli;
use App\Http\Models\rsu_customer;
use App\Http\Models\Rsu_setupall;

class LoketController extends Controller{
	public function main(Request $request){
		if ($request->ajax()) {
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
				$valAnt->namaPasien = ucwords($namaPasien ? : '-');
				$namaPasien = '';
			}
			return Datatables::of($data)
				->addIndexColumn()
				->addColumn('action',function($row){
					$btn = "<div class='text-center' style='width:100%; display: inline-block;'>";
					$btn .= "<button class='btn btn-sm btn-primary' title='Panggil' style='' onclick='panggil(`$row->kode_booking`)'><i class='fa fa-bullhorn' aria-hidden='true'></i></button>";
					$btn .= "<button class='btn btn-sm btn-success' title='Kerjakan' style='margin-left: 5px;' onclick='kerjakan(`$row->id`)'><i class='fa fa-file' aria-hidden='true'></i></button>";
					$btn .= "<button class='btn btn-sm btn-danger' title='Batalkan' style='margin-left: 5px;' onclick='batalkan(`$row->kode_booking`)'><i class='fa fa-remove' aria-hidden='true'></i></button>";
					$btn .= "</div>";
					return $btn;
				})
				->addColumn('namaCust',fn($row)=>
					($row->metode_ambil=='WA'||$row->metode_ambil=='SIMAPAN') ? $row->namaPasien : ((isset($row->tm_customer)) ? $row->tm_customer->NamaCust : '-')
				)
				->make(true);
		}
		$data['data'] = 'Normal';
		return view('Admin.antreanBPJS.listAntrian.mainLoket');
	}

	public function kerjakanAntrian(Request $request){
		$id = $request->id;
		$view = $request->view ?: 0;

		$antrian = Antrian::where('id', $id)->first();
		if($antrian->no_rm=='00000000000'){
			if($antrian->metode_ambil=='WA'){
				$this->data['getAntrian'] = Antrian::with(['mapping_poli_bridging.tm_poli'])
					->join('bot_data_pasien as bdp','antrian.nik','=','bdp.nik')
					->where('antrian.id',$id)
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
		$this->data['jenis_pasien'] = Rsu_setupall::where('groups','Asuransi')->get();
		// if ($antrian->jenis_pasien == 'ASURANSILAIN') {
		// 	$this->data['jenis_pasien'] = Rsu_setupall::where('groups','Asuransi')->get();
		// }
		$this->data['from'] = $this->data['getAntrian']->metode_ambil;
		$this->data['data_provinsi'] = Provinsi::all();
		$this->data['poli'] = Rsu_Bridgingpoli::join('tm_poli', 'mapping_poli_bridging.kdpoli_rs', '=', 'tm_poli.KodePoli')->get();
		$this->data['view'] = $view;

		$content = view('Admin.antreanBPJS.listAntrian.form', $this->data)->render();

		return ['status' => 'success', 'content' => $content, 'data' => $this->data];
	}

	/**
	 * Summary of sendToCounterPoli
	 * @param \Illuminate\Http\Request $request
	 * @return mixed|\Illuminate\Http\JsonResponse
	 * @var object $antrian
	 * @var object $getPasien
	 * @var object $pasien
	 */
	public function sendToCounterPoli(Request $request){
		date_default_timezone_set("Asia/Jakarta");
		DB::beginTransaction();
		try{
			if(!($antrian = Antrian::where('id', $request->id_antrian)->first())){
				DB::rollback();
				return response()->json([
					'metadata' => [
						'code' => 500,
						'status' => 'warning',
						'message' => 'Antrian tidak valid',
					]
				], 500);
			}

			if($pasien = rsu_customer::where('NoKtp',$request->nik)->first(['KodeCust'])){ # Find by nik
				$antrian->no_rm = $pasien->KodeCust;
			}else if($request->nomor_rm && ($pasien = rsu_customer::where('KodeCust',$request->nomor_rm)->first(['KodeCust']))){ # Find by nomor rm
				$antrian->no_rm = $pasien->KodeCust;
			}else{ # Nik belum dibuat, generatekan RM baru
				$getKode = DB::connection('dbrsud')->table('tm_customer')->max('KodeCust');
				$num = (int)substr($getKode, 5);
				$nextKode = 'W'.date("ym").(string)($num+1);
				$antrian->no_rm = $nextKode;
				$pasien = new rsu_customer;
				$pasien->KodeCust = $nextKode;
			}

			$pasien->NamaCust = $request->nama;
			$pasien->NoKtp = $request->nik;
			$pasien->Tempat = $request->tmpt_lahir;
			$pasien->JenisKel = $request->jenis_kelamin;
			$pasien->Agama = $request->agama;
			$pasien->Pekerjaan = $request->pekerjaan;
			$pasien->status = $request->s_perkawinan;
			$pasien->warganegara = $request->kewarganegaraan;
			$pasien->goldarah = $request->gol_darah;
			$pasien->goldarah = $request->rt;
			$pasien->goldarah = $request->gol_darah;
			# HITUNG UMUR START
			$tglLahir = $request->tgl_lahir;
			$tanggal = new DateTime($tglLahir);
			$today = new DateTime('today');
			$umur = $today->diff($tanggal)->y;
			# HITUNG UMUR END
			$pasien->umur = $umur;
			$pasien->Alamat = $request->alamat;
			$pasien->kelurahan = $request->desa_id;
			$pasien->kecamatan = $request->kecamatan_id;
			$pasien->Kota = $request->kabupaten_id;
			$pasien->rt = $request->rt;
			$pasien->rw = $request->rw;
			$pasien->Telp = $request->telp;
			$pasien->FieldCust1 = isset($request->nobpjs) ? $request->nobpjs : null;
			$pasien->TglLahir = $request->tgl_lahir;
			$pasien->save();
			if (!$pasien) {
				DB::rollback();
				return response()->json([
					'metadata' => [
						'code' => 500,
						'status' => 'error',
						'message' => 'Data pasien gagal disimpan',
					]
				], 500);
			}
			$antrian->jenis_pasien = $request->jenis_pasien;
			$antrian->pembayaran_pasien = $request->pembayaran_pasien;
			$antrian->nohp = $request->telp;
			$antrian->status = 'counter';
			$antrian->kode_poli = $request->poli;
			$antrian->save();
			if(!$antrian){
				DB::rollback();
				return response()->json([
					'metadata' => [
						'code' => 500,
						'status' => 'error',
						'message' => 'Data antrian gagal diupdate',
					]
				], 500);
			}

			if($antrian->metode_ambil=='KIOSK'){
				$antrianTracer = $this->antrianTracer($antrian->id,'kiosk','loket',2,'update');
			}elseif($antrian->metode_ambil=='SIMAPAN'){
				$antrianTracer = $this->antrianTracer($antrian->id,'simapan','loket',2,'update');
			}else{
				$antrianTracer = $this->antrianTracer($antrian->id,'wa','loket',2,'update');
			}
			$antrianTracer = $this->antrianTracer($antrian->id,'loket','counter',1,'input');

			$getPasien = DB::connection('dbrsud')->table('tm_customer')->where('KodeCust',$antrian->no_rm)->first(['cust_id']);
			$custId = $getPasien->cust_id;
			# Data tambahan untuk pasien(tm_customer), disimpan ke table(antrian_pasien_baru)
			if(!($pasien2 = AntPasienBaru::where('cust_id',$custId)->first())){
				$pasien2 = new AntPasienBaru;
				$pasien2->cust_id = $custId;
			}
			$pasien2->provinsi_id = $request->provinsi_id;
			$pasien2->kabupaten_id = $request->kabupaten_id;
			$pasien2->kecamatan_id = $request->kecamatan_id;
			$pasien2->desa_id = $request->desa_id;
			$pasien2->rt = $request->rt;
			$pasien2->rw = $request->rw;
			$pasien2->nik = $request->nik;
			$pasien2->no_rm = $antrian->no_rm;
			$pasien2->pen_jawab = $request->pen_jawab;
			$pasien2->nama_pen_jawab = $request->nama_pen_jawab;
			$pasien2->pend_terakhir = $request->pend_terakhir;
			$pasien2->save();
			if(!$pasien2){
				DB::rollback();
				return response()->json([
					'metadata' => [
						'code' => 500,
						'status' => 'error',
						'message' => 'Data pasien baru gagal disimpan',
					]
				], 500);
			}

			$request->merge([
				'kodebooking' => $antrian->kode_booking,
				'waktu' => strtotime(date('Y-m-d H:i:s'))*1000,
				'taskid' => '2',
			]);
			$bridgBpjs = new BridgBpjsController;
			$updateWaktu = $bridgBpjs->updateWaktu($request);
			// \Log::info(json_encode($updateWaktu,JSON_PRETTY_PRINT));

			//insert antrian_id di table filling
			$insertFilling = DB::connection('mysql')->table('filling')
			->insert([
				'no_rm' => $antrian->no_rm,
				'tgl_periksa' => $antrian->tgl_periksa,
				'antrian_id' => $antrian->id,
			]);
			if(!$insertFilling){
				DB::rollback();
				return response()->json([
					'metadata' => [
						'code' => 500,
						'status' => 'error',
						'message' => 'Data filling gagal disimpan',
					],
				], 500);
			}
			DB::commit();
			return response()->json([
				'metadata' => [
					'code' => 200,
					'status' => 'success',
					'message' => 'Data berhasil disimpan',
				],
				'response' => $antrian,
			], 200);
		}catch(\Throwable $e){
			DB::rollback();
			$request->merge(['log_payload'=>[
				'method' => 'function sendToCounterPoli()',
				'url' => $request->url(),
				'file' => $e->getFile(),
				'message' => $e->getMessage(),
				'line' => $e->getLine(),
			]]);
			Help::catchError($request);
			return response()->json([
				'metadata' => [
					'code' => 500,
					'status' => 'error',
					'message' => 'Data gagal disimpan, terjadi kesalahan sistem',
				],
			], 500);
		}
	}
}