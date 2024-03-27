<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Models\rsu_customer;
use App\Http\Models\MstKonterPoli;
use App\Http\Models\Rsu_Bridgingpoli;
use App\Http\Models\rsu_poli;
use App\Http\Models\Users;
use App\Http\Models\Antrian;

use Redirect, DB, Auth,Datatables;

class DisplayController extends Controller{
	public function listKonter(Request $request){
		if ($request->ajax()) {
			$today = date('Y-m-d');

			$data['user'] = Auth::user()->id;
			$cekUser = Users::find($data['user']);
			if(!empty($cekUser)){
				if($cekUser->level!=1){
					$data['konterpoli'] = MstKonterPoli::with(['trans_konter_poli.tm_poli.mapping_poli_bridging'])
						->where('user_id', $data['user'])
						->get();

					$arrpoli = [];
					foreach ($data['konterpoli'] as $key => $value) {
						foreach ($value->trans_konter_poli as $k => $v) {
							if(!empty($tmPoli = $v->tm_poli)){
								if(!empty($tmPoli->mapping_poli_bridging)){
									$getKodePoli = $v->tm_poli->mapping_poli_bridging->kdpoli;
									array_push($arrpoli, $getKodePoli);
								}
							}
						}
					}

					$data['listkonter'] = Antrian::with(['tm_customer','mapping_poli_bridging.tm_poli'])
						->whereIn('kode_poli', $arrpoli)
						->where('tgl_periksa', $today)
						->where('status', 'counter')
						->get();
				}else{
					$data['listkonter'] = Antrian::with(['tm_customer','mapping_poli_bridging.tm_poli'])
						->where('tgl_periksa', $today)
						->where('status', 'counter')
						->get();
				}
			}

			return Datatables::of($data['listkonter'])
				->addIndexColumn()
				->addColumn('tglLahir',function($row){
					$lahir = $row->tm_customer;

					if(!empty($lahir->TglLahir)){
						$getLahir = date_create($lahir->TglLahir);
						$date = date_create('now');
						$res = date_diff($date,$getLahir);
						$Y = $res->y;
						$M = $res->m;
						$D = $res->d;
						$umur = ( ($Y>0) ? $Y.'Thn' :( ($M>0)? $M.'Bln' : $D.'Hri' ) );
						$showUmur = date('d-m-Y',strtotime($lahir->TglLahir)).' ('.$umur.')';
					}else{
						$showUmur = '-';
					}

					$res = !empty($lahir)?(!empty($lahir->TglLahir)?$showUmur:'-'):'-';
					$txt = '<p class="text-center" style="margin:0px">'.($res).'</p>';
					return $txt;
				})
				->addColumn('action',function($row){
					$a = $this->templateAction($row);
					return $a;
				})
				->make(true);
		}
		return view('Admin.antreanBPJS.listAntrian.mainCounter');
	}

	function templateAction($data){
		$btn = "<button class='btn btn-sm btn-info' onclick='detail(`$data->kode_booking`)'>Detail</button>"
		."<button class='btn btn-sm btn-success' style='margin-left: 5px;' onclick='generate(`$data->kode_booking`)'>Generate No.Antrian</button>"
		."<button class='btn btn-sm btn-warning' style='margin-left: 5px;' onclick='buatsep(`$data->kode_booking`)' >Buat SEP</button>"
		."<button class='btn btn-sm btn-warning' style='margin-left: 5px;' onclick='arahkan(`$data->kode_booking`)' >Arahkan Ke poli</button>";
		
		return $btn;
	}

	public function displayLoket(){
		$this->data['jam'] = 'JAM';
		$this->data['tanggal'] = $this->tanggal_format();
		return view('display.loket')->with('data', $this->data);
	}
	
	public function dataLoket(Request $request){
		$user_id = $request->id;
		$type = $request->type;

		if($type == "antrian"){
			$loket1 = Antrian::join('antrian_tracer as at', 'at.antrian_id','=', 'antrian.id')
				->join('pemanggilan as p', 'p.antrian_id', '=', 'antrian.id')
				->orderBy('p.id','DESC')
				// ->where('p.tampilkan',1)
				->where('tgl_periksa', date('Y-m-d'))
				->where('antrian.status', 'panggil')
				->where('at.to', 'loket')
				->where('at.status_tracer', 1)
				->where('at.loket', "loket1")
				->first();
			$loket2 = Antrian::join('antrian_tracer as at', 'at.antrian_id','=', 'antrian.id')
				->join('pemanggilan as p', 'p.antrian_id', '=', 'antrian.id')
				->orderBy('p.id','DESC')
				// ->where('p.tampilkan',1)
				->where('tgl_periksa', date('Y-m-d'))
				->where('antrian.status', 'panggil')
				->where('at.to', 'loket')
				->where('at.status_tracer', 1)
				->where('at.loket', "loket2")
				->first();
			$loket3 = Antrian::join('antrian_tracer as at', 'at.antrian_id','=', 'antrian.id')
				->join('pemanggilan as p', 'p.antrian_id', '=', 'antrian.id')
				->orderBy('p.id','DESC')
				// ->where('p.tampilkan',1)
				->where('tgl_periksa', date('Y-m-d'))
				->where('antrian.status', 'panggil')
				->where('at.to', 'loket')
				->where('at.status_tracer', 1)
				->where('at.loket', "loket3")
				->first();
			$loket4 = Antrian::join('antrian_tracer as at', 'at.antrian_id','=', 'antrian.id')
				->join('pemanggilan as p', 'p.antrian_id', '=', 'antrian.id')
				->orderBy('p.id','DESC')
				// ->where('p.tampilkan',1)
				->where('tgl_periksa', date('Y-m-d'))
				->where('antrian.status', 'panggil')
				->where('at.to', 'loket')
				->where('at.status_tracer', 1)
				->where('at.loket', "loket4")
				->first();
			$loket5 = Antrian::join('antrian_tracer as at', 'at.antrian_id','=', 'antrian.id')
				->join('pemanggilan as p', 'p.antrian_id', '=', 'antrian.id')
				->orderBy('p.id','DESC')
				// ->where('p.tampilkan',1)
				->where('tgl_periksa', date('Y-m-d'))
				->where('antrian.status', 'panggil')
				->where('at.to', 'loket')
				->where('at.status_tracer', 1)
				->where('at.loket', "loket5")
				->first();
				
			$Antrian = [
				"loket1" => $loket1,
				"loket2" => $loket2,
				"loket3" => $loket3,
				"loket4" => $loket4,
				"loket5" => $loket5,
			];

			$panggilan = DB::connection('mysql')->table('pemanggilan as p')
				->join('antrian_tracer as a', 'a.antrian_id', '=', 'p.antrian_id')
				->where('a.tgl', date('Y-m-d'))
				->where('p.status', 1)
				->where('p.dari', "loket")
				->orderBy('p.id', 'DESC')
				->select('p.*','a.loket as loket')
				->first();
		}

		if($type == "saatIni"){
			$panggilan = DB::connection('mysql')->table('pemanggilan as p')
				->join('antrian_tracer as a', 'a.antrian_id', '=', 'p.antrian_id')
				->where('a.tgl', date('Y-m-d'))
				->where('p.status', 1)
				->where('p.dari', "loket")
				->orderBy('p.id', 'DESC')
				->select('p.*','a.loket as loket')
				->first();
		}

		$pasienDipanggil = Antrian::where('tgl_periksa', date('Y-m-d'))
			// ->whereNotIn('status', ['belum'])
			// ->where('no_antrian','LIKE','L%')
			->where([
				'status'=>'belum',
				'is_pasien_baru'=>'Y'
			])
			->get()->count();
		$totalPasien = Antrian::where('tgl_periksa', date('Y-m-d'))
			// ->where('no_antrian','LIKE','B%')
			// ->whereIn('status',['belum','panggil'])
			->where('is_pasien_baru','Y')
			->get()->count();
		$daftarAntrian = [
			// "belumDipanggil" => str_pad($totalPasien - $pasienDipanggil, 4 ,"0",STR_PAD_LEFT) ,
			"belumDipanggil" => str_pad($pasienDipanggil, 4 ,"0",STR_PAD_LEFT) ,
			"totalPasien" => str_pad($totalPasien, 4 ,"0",STR_PAD_LEFT)
		];

		return [
			'status' => 'success',
			'code' => 200, 
			'message' => $type == "antrian" ? 'Berhasil Get Antrian Loket' : 'Berhasil Get Antrian Loket Saat Ini', 
			'data' => $type == "antrian" ? $Antrian : $panggilan,
			'dataPanggilan' => $type == "antrian" ? $panggilan : "",
			'dataDaftarAntrian' => $daftarAntrian,
		];
	}

	public function insertDataPemanggilan(Request $request){
		$no_antrian = isset($request->no_antrian) ? $request->no_antrian : "";
		$kode_booking = isset($request->kode_booking) ? $request->kode_booking : "";
		if($no_antrian != ""){
			$dataAntrian = DB::connection('mysql')->table('antrian')
				->where('tgl_periksa', date('Y-m-d'))
				->where('no_antrian_pbaru', $no_antrian)
				->first();

			//cek data jiak sudah ada maka hanya mengupdate status jadi 1 di table pemanggilan
			$cek_data = DB::connection('mysql')->table('pemanggilan')
				->where('antrian_id', $dataAntrian->id)
				->where('no_antrian', $dataAntrian->no_antrian_pbaru)
				->update(['status'=>1]);
			if($cek_data){
				//update data pasien sebelumnya ditable pemanggilan untuk ditampilkan sesuai loket
				$update = DB::connection('mysql')->table('pemanggilan')
					->orderBy('id', 'DESC')
					->update(['tampilkan' => 1]);
				return;
			}
		}
		if($kode_booking != ""){
			$dataAntrian = DB::connection('mysql')->table('antrian')
				->where('tgl_periksa', date('Y-m-d'))
				->where('kode_booking', $kode_booking)
				->first();

			//cek data jiak sudah ada maka hanya mengupdate status jadi 1 di table pemanggilan
			$cek_data = DB::connection('mysql')->table('pemanggilan')
				->where('antrian_id', $dataAntrian->id)
				->where('no_antrian', $dataAntrian->no_antrian_pbaru)
				->update(['status'=>1]);
			if($cek_data){
				//update data pasien sebelumnya ditable pemanggilan untuk ditampilkan sesuai loket
				$update = DB::connection('mysql')->table('pemanggilan')
					->orderBy('id', 'DESC')
					->update(['tampilkan' => 1]);
				return;
			}
		}

		
		//update data pasien sebelumnya ditable pemanggilan untuk ditampilkan sesuai loket
		$update = DB::connection('mysql')->table('pemanggilan')
			->orderBy('id', 'DESC')
			->update(['tampilkan' => 1]);

		$dataPost = [
			'antrian_id' => $dataAntrian->id,
			'no_antrian' => $dataAntrian->no_antrian_pbaru,
			'status' => 1,
			'dari' => "loket"
		];

		$simpan = DB::connection('mysql')->table('pemanggilan')->insert([$dataPost]);
	}

	public function ulangiDataPemanggilan(Request $request){
		$no_antrian = $request->no_antrian;
		$dataAntrian = DB::connection('mysql')->table('antrian')
			->where('tgl_periksa', date('Y-m-d'))
			->where('no_antrian_pbaru', $no_antrian)
			->first();

		$update = DB::connection('mysql')->table('pemanggilan')
			->where('antrian_id', $dataAntrian->id)
			->where('no_antrian', $no_antrian)->update(["status" => 1]);
	}

	public function changeStatusPanggilan(Request $request){
		// $no_antrian = $request->no_antrian;

		// $idPemanggilan = DB::connection('mysql')->table('pemanggilan as p')
		// 	->select('p.id','p.status')
		// 	->join('antrian as a', 'a.id', '=', 'p.antrian_id')
		// 	->where('a.tgl_periksa', date('Y-m-d'))
		// 	->where('p.status', 1)
		// 	->where('p.no_antrian', $no_antrian)
		// 	->orderBy('p.id','ASC')
		// 	->first();
		// 	// ->update(['p.status' => 0]);

		// DB::connection('mysql')->table('pemanggilan')
		// 	->where('id', $idPemanggilan->id)
		// 	->update(['status' => 0]);

		$no_antrian = $request->no_antrian;

		DB::connection('mysql')->table('pemanggilan')
			->where('status', 1)
			->where('no_antrian', $no_antrian)
			->update(['status' => 0]);
	}

	public function toKonterA(){
		return view('display.konter_poli_a');
	}

	public function konter_poli_a(Request $request){
		$this->data['jam'] = 'JAM';
		$this->data['tanggal'] = $this->tanggal_format();
		$this->data['link'] = $this->getLink(2);
		return view('display.konter_poli_a')->with('data', $this->data);
	}

	public function konter_poli_b(Request $request){
		$this->data['jam'] = 'JAM';
		$this->data['tanggal'] = $this->tanggal_format();
		$this->data['link'] = $this->getLink(3);
		return view('display.konter_poli_b')->with('data', $this->data);
	}

	public function konter_poli_c1(Request $request){
		$this->data['jam'] = 'JAM';
		$this->data['tanggal'] = $this->tanggal_format();
		$this->data['link'] = $this->getLink(4);
		return view('display.konter_poli_c1')->with('data', $this->data);
	}

	public function konter_poli_c2(Request $request){
		$this->data['jam'] = 'JAM';
		$this->data['tanggal'] = $this->tanggal_format();
		$this->data['link'] = $this->getLink(5);
		return view('display.konter_poli_c2')->with('data', $this->data);
	}

	public function konter_poli_d(Request $request){
		$this->data['jam'] = 'JAM';
		$this->data['tanggal'] = $this->tanggal_format();
		$this->data['link'] = $this->getLink(6);
		return view('display.konter_poli_d')->with('data', $this->data);
	}

	public function konter_poli_e(Request $request){
		$this->data['jam'] = 'JAM';
		$this->data['tanggal'] = $this->tanggal_format();
		$this->data['link'] = $this->getLink(7);
		return view('display.konter_poli_e')->with('data', $this->data);
	}

	# Data Farmasi
	public function dataFarmasi(Request $request){
		$kode = $request->kode;
		$type = $request->type;
		if($kode=='LFAR'){
			if($type == "antrian"){
				$kronis = Antrian::select(
						'antrian.id',
						'antrian.tgl_periksa',
						'antrian.status',
						'antrian.No_Register',
						'p.*',
						'af.id_antrian_farmasi',
						'af.antrian_id',
						'af.no_antrian_farmasi',
						'trm.No_Register as NoregResep',
						'trm.KodeGD',
						'trm.kronis'
					)
					->join('pemanggilan as p', 'p.antrian_id', '=', 'antrian.id')
					->join('antrian_farmasi as af','af.antrian_id','=','antrian.id')
					->join(DB::connection('dbwahidin')->raw('wahidin_data2020.tr_resep_m as trm'),'antrian.No_Register','=','trm.No_Register')
					->where('p.tampilkan',1)
					->where('antrian.tgl_periksa', date('Y-m-d'))
					->where('antrian.status', 'panggilfarmasi')
					->where('p.api_sirama_erm',1)
					->where('p.dari','rj1')
					->where('trm.kronis','Y')
					// ->where('antrian.jenis_pasien','!=','UMUM')
					->whereIn('antrian.jenis_pasien',['BPJS','BPJS NON PBI','BPJS PBI','BPJS KESEHATAN','AMBIL OBAT'])
					->orderBy('p.id','DESC')
					->first();
				$nonkronis = Antrian::select(
						'antrian.id',
						'antrian.tgl_periksa',
						'antrian.status',
						'antrian.No_Register',
						'p.*',
						'af.id_antrian_farmasi',
						'af.antrian_id',
						'af.no_antrian_farmasi',
						'trm.No_Register as NoregResep',
						'trm.KodeGD',
						'trm.kronis'
					)
					->join('pemanggilan as p', 'p.antrian_id', '=', 'antrian.id')
					->join('antrian_farmasi as af','af.antrian_id','=','antrian.id')
					->join(DB::connection('dbwahidin')->raw('wahidin_data2020.tr_resep_m as trm'),'antrian.No_Register','=','trm.No_Register')
					->where('p.tampilkan',1)
					->where('antrian.tgl_periksa', date('Y-m-d'))
					->where('antrian.status', 'panggilfarmasi')
					->where('p.api_sirama_erm',1)
					->where('p.dari','rj1')
					->where('trm.kronis','!=','Y')
					->whereIn('antrian.jenis_pasien',['BPJS','BPJS NON PBI','BPJS PBI','BPJS KESEHATAN','AMBIL OBAT'])
					->orderBy('p.id','DESC')
					->first();
				$umum = Antrian::select(
						'antrian.id',
						'antrian.tgl_periksa',
						'antrian.status',
						'antrian.No_Register',
						'p.*',
						'af.id_antrian_farmasi',
						'af.antrian_id',
						'af.no_antrian_farmasi'
					)
					->join('pemanggilan as p', 'p.antrian_id', '=', 'antrian.id')
					->join('antrian_farmasi as af','af.antrian_id','=','antrian.id')
					->where('p.tampilkan',1)
					->where('antrian.tgl_periksa', date('Y-m-d'))
					->where('antrian.status', 'panggilfarmasi')
					->where('p.api_sirama_erm',1)
					->where('p.dari','rj1')
					->whereNotIn('antrian.jenis_pasien',['BPJS','BPJS NON PBI','BPJS PBI','BPJS KESEHATAN','AMBIL OBAT'])
					->orderBy('p.id','DESC')
					->first();
					
				$Antrian = [
					"kronis" => $kronis,
					"nonkronis" => $nonkronis,
					"umum" => $umum
				];

				$panggilan = DB::connection('mysql')->table('pemanggilan')
					->where('status', '1')
					->where('api_sirama_erm', '1')
					->where('dari','rj1')
					->orderBy('id', 'DESC')
					->first();
			}

			if($type == "saatIni"){
				$panggilan = DB::connection('mysql')->table('pemanggilan')
					->where('status', '1')
					->where('api_sirama_erm', '1')
					->where('dari','rj1')
					->orderBy('id', 'DESC')
					->first();
			}
			$pasienDipanggil = Antrian::select(
					'antrian.id',
					'antrian.No_Register',
					'antrian.tgl_periksa',
					'antrian.status',
					'trm.No_Register',
					'trm.KodeGD',
				)
				->join(DB::connection('dbwahidin')->raw('wahidin_data2020.tr_resep_m as trm'),'antrian.No_Register','=','trm.No_Register')
			    ->where('antrian.tgl_periksa', date('Y-m-d'))
			    ->where('KodeGD','LFAR')
			    ->where('antrian.status', 'antrifarmasi')
			    ->get()->count();
			$totalPasien = Antrian::select(
					'antrian.id',
					'antrian.No_Register',
					'antrian.tgl_periksa',
					'antrian.status',
					'trm.No_Register',
					'trm.KodeGD',
				)
				->join(DB::connection('dbwahidin')->raw('wahidin_data2020.tr_resep_m as trm'),'antrian.No_Register','=','trm.No_Register')
			    ->where('antrian.tgl_periksa', date('Y-m-d'))
			    ->where('KodeGD','LFAR')
			    ->whereIn('status',['antrifarmasi','panggilfarmasi','akhirfarmasi'])
			    ->get()->count();
			$daftarAntrian = [
				"belumDipanggil" => str_pad($pasienDipanggil, 4 ,"0",STR_PAD_LEFT) ,
				"totalPasien" => str_pad($totalPasien, 4 ,"0",STR_PAD_LEFT)
			];
		} else{
			if($type == "antrian"){
				$racikan = Antrian::select(
						'antrian.id',
						'antrian.tgl_periksa',
						'antrian.status',
						'antrian.No_Register',
						'p.*',
						'af.id_antrian_farmasi',
						'af.antrian_id',
						'af.no_antrian_farmasi',
						'af.jenis_resep'
					)
					->join('pemanggilan as p', 'p.antrian_id', '=', 'antrian.id')
					->join('antrian_farmasi as af','af.antrian_id','=','antrian.id')
					->where('p.tampilkan',1)
					->where('antrian.tgl_periksa', date('Y-m-d'))
					->where('antrian.status', 'panggilfarmasi')
					->where('p.api_sirama_erm',1)
					->where('af.jenis_resep','Racikan')
					->where('p.dari','rj2')
					->where('antrian.jenis_pasien','!=','UMUM')
					->orderBy('p.id','DESC')
					->first();
				$nonracikan = Antrian::select(
						'antrian.id',
						'antrian.tgl_periksa',
						'antrian.status',
						'antrian.No_Register',
						'p.*',
						'af.id_antrian_farmasi',
						'af.antrian_id',
						'af.no_antrian_farmasi',
						'af.jenis_resep'
					)
					->join('pemanggilan as p', 'p.antrian_id', '=', 'antrian.id')
					->join('antrian_farmasi as af','af.antrian_id','=','antrian.id')
					->where('p.tampilkan',1)
					->where('antrian.tgl_periksa', date('Y-m-d'))
					->where('antrian.status', 'panggilfarmasi')
					->where('p.api_sirama_erm',1)
					->where('p.dari','rj2')
					->where('af.jenis_resep','Non racikan')
					->where('antrian.jenis_pasien','!=','UMUM')
					->orderBy('p.id','DESC')
					->first();
				$umum = Antrian::select(
						'antrian.id',
						'antrian.tgl_periksa',
						'antrian.status',
						'antrian.No_Register',
						'p.*',
						'af.id_antrian_farmasi',
						'af.antrian_id',
						'af.no_antrian_farmasi'
					)
					->join('pemanggilan as p', 'p.antrian_id', '=', 'antrian.id')
					->join('antrian_farmasi as af','af.antrian_id','=','antrian.id')
					->where('p.tampilkan',1)
					->where('antrian.tgl_periksa', date('Y-m-d'))
					->where('antrian.status', 'panggilfarmasi')
					->where('p.api_sirama_erm',1)
					->where('p.dari','rj2')
					->where('antrian.jenis_pasien','=','UMUM')
					->orderBy('p.id','DESC')
					->first();
					
				$Antrian = [
					"racikan" => $racikan,
					"nonracikan" => $nonracikan,
					"umum" => $umum
				];

				$panggilan = DB::connection('mysql')->table('pemanggilan')
					->where('status', '1')
					->where('api_sirama_erm', '1')
					->where('dari','rj2')
					->orderBy('id', 'DESC')
					->first();
			}

			if($type == "saatIni"){
				$panggilan = DB::connection('mysql')->table('pemanggilan')
					->where('status', '1')
					->where('api_sirama_erm', '1')
					->where('dari','rj2')
					->orderBy('id', 'DESC')
					->first();
			}
			$pasienDipanggil = Antrian::select(
					'antrian.id',
					'antrian.No_Register',
					'antrian.tgl_periksa',
					'antrian.status',
					'trm.No_Register',
					'trm.KodeGD',
				)
				->join(DB::connection('dbwahidin')->raw('wahidin_data2020.tr_resep_m as trm'),'antrian.No_Register','=','trm.No_Register')
			    ->where('antrian.tgl_periksa', date('Y-m-d'))
			    ->where('KodeGD','LKRJ')
			    ->where('antrian.status', 'antrifarmasi')
			    ->get()->count();
			$totalPasien = Antrian::select(
					'antrian.id',
					'antrian.No_Register',
					'antrian.tgl_periksa',
					'antrian.status',
					'trm.No_Register',
					'trm.KodeGD',
				)
				->join(DB::connection('dbwahidin')->raw('wahidin_data2020.tr_resep_m as trm'),'antrian.No_Register','=','trm.No_Register')
			    ->where('antrian.tgl_periksa', date('Y-m-d'))
			    ->where('KodeGD','LKRJ')
			    ->whereIn('status',['antrifarmasi','panggilfarmasi','akhirfarmasi'])
			    ->get()->count();
			$daftarAntrian = [
				"belumDipanggil" => str_pad($pasienDipanggil, 4 ,"0",STR_PAD_LEFT) ,
				"totalPasien" => str_pad($totalPasien, 4 ,"0",STR_PAD_LEFT)
			];
		}
		return [
			'status' => 'success',
			'code' => 200, 
			'message' => $type=="antrian"?'Berhasil Get Antrian Farmasi':'Berhasil Get Antrian Farmasi Saat Ini',
			'data' => $type == "antrian" ? $Antrian : $panggilan,
			'dataPanggilan' => $type == "antrian" ? $panggilan : "",
			'dataDaftarAntrian' => $daftarAntrian,
		];
	}
	# Display farmasi
	public function farmasiRJ1(Request $request){
		$this->data['jam'] = 'JAM';
		$this->data['tanggal'] = $this->tanggal_format();
		$this->data['link'] = $this->getLink(2);
		return view('display.farmasi_rj1')->with('data', $this->data);
	}
	public function farmasiRJ2(Request $request){
		$this->data['jam'] = 'JAM';
		$this->data['tanggal'] = $this->tanggal_format();
		$this->data['link'] = $this->getLink(2);
		return view('display.farmasi_rj2')->with('data', $this->data);
	}

	public function getLink($id){
		$dataPoli = MstKonterPoli::where('user_id',$id)->first();
		if(!empty($dataPoli)){
			$exp = explode('/', $dataPoli->url);
			$res = end($exp);
		}else{
			$res = "";
		}
		return $res;
	}

	function tanggal_format(){
		$tgl = date('d');
		$bln = date('m');
		$thn = date('Y');
		switch ($bln) {
			case '01':
				$bln = 'Januari';
				break;
			case '02':
				$bln = 'Februari';
				break;
			case '03':
				$bln = 'Maret';
				break;
			case '04':
				$bln = 'April';
				break;
			case '05':
				$bln = 'Mei';
				break;
			case '06':
				$bln = 'Juni';
				break;
			case '07':
				$bln = 'Juli';
				break;
			case '08':
				$bln = 'Agustus';
				break;
			case '09':
				$bln = 'September';
				break;
			case '10':
				$bln = 'Oktober';
				break;
			case '11':
				$bln = 'November';
				break;
			case '12':
				$bln = 'Desember';
				break;
			default:
				$bln = date('F');
				break;
		}
		return $tgl.' '.$bln.' '.$thn;
	}
}