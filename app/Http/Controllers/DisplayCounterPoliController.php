<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Models\Antrian;
use App\Http\Models\MstKonterPoli;
use App\Http\Models\Users;
use App\Http\Models\Rsu_Bridgingpoli;
use App\Traits\KonfirmasiAntrianTraits;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DisplayCounterPoliController extends Controller{
	use KonfirmasiAntrianTraits;

	public function dataCounter(Request $request){
		$id_display = $request->id;
		$type = $request->type;

		$qs = MstKonterPoli::query()->with(['trans_konter_poli.tm_poli.mapping_poli_bridging']);
		if($id_display=='counterc1'){
			$nama_counter = Users::where('lv_user','counterc')->first();
			$nama = 'Counter C1';
			$q = $qs->where('user_id', '4');
		}elseif($id_display=='counterc2'){
			$nama_counter = Users::where('lv_user','counterc')->first();
			$nama = 'Counter C2';
			$q = $qs->where('user_id', '5');
		}else{
			$nama_counter = Users::where('lv_user',$id_display)->first();
			$nama = $nama_counter->name_user;
			$q = $qs->where('user_id', $nama_counter->id);
		}
		$dataPoli = $q->get();

		if($type == "initialPage"){
			// $user = Users::where('lv_user',$id_display)->first();
			if($id_display=='counterc1'){
				$nama = 'Counter C1';
			}elseif($id_display=='counterc2'){
				$nama = 'Counter C2';
			}else{
				$nama = $nama_counter->name_user;
			}
			$dataPage = [
				// 'nama_counter' => $user->name_user
				'nama_counter' => $nama
			];

			return [
				'status' => 'success',
				'code' => 200,
				'message' => 'berhasil get data initial page',
				'data' => $dataPage
			];
		}

		// $dataPoli = MstKonterPoli::with(['trans_konter_poli.tm_poli.mapping_poli_bridging'])
		// 	->where('user_id', $nama_counter->id)
		// 	->get();

		$arrPoli = [];
		foreach ($dataPoli as $key => $value) {
			foreach ($value->trans_konter_poli as $k => $v) {
				if(!empty($tmPoli = $v->tm_poli)){
					if(!empty($tmPoli->mapping_poli_bridging)){
						$getKodePoli = $v->tm_poli->mapping_poli_bridging->kdpoli;
						array_push($arrPoli, $getKodePoli);
					}
				}
			}
		}

		if($id_display=='counterd'){
			$jiw = $arrPoli[0];
			$tht = $arrPoli[3];
			$arrPoli[0] = $tht;
			$arrPoli[3] = $jiw;
		}

		$arrDtPoli = [];
		if($type == "antrian"){
			if (count($arrPoli) > 0) {
				foreach ($arrPoli as $key => $dtPoli) {
					$namaPoli = Rsu_Bridgingpoli::with('tm_poli')->where('kdpoli', $dtPoli)->first();
					$dataCounter = Antrian::with(['mapping_poli_bridging.tm_poli'])
						->select('antrian.*','p.antrian_id','p.no_antrian','p.status','p.dari','p.tampilkan')
						->join('pemanggilan as p', 'p.antrian_id', '=', 'antrian.id')
						->where('kode_poli', $dtPoli)
						->where('p.tampilkan',1)
						->orderBy('p.id','DESC')
						->where('antrian.tgl_periksa', date('Y-m-d'))
						->whereIn('antrian.status', ['panggilpoli','layanpoli','akhirpoli'])
						// ->where('at.to', 'poli')
						// ->where('at.status_tracer', 2)
						->first();

					array_push($arrDtPoli, [$dataCounter,$namaPoli]);
				}
			}

			// $dataCounter = Antrian::with(['tm_customer','mapping_poli_bridging.tm_poli'])
			//     ->whereIn('kode_poli', $arrPoli)
			//     ->orderBy('id','ASC')
			//     ->where('tgl_periksa', date('Y-m-d'))
			//     ->where('status', 'antripoli')
			//     ->get();
			// if(count($dataCounter) > 0){
			//     foreach($dataCounter as $kdc => $v){
			//         $namaPoli = $this->convertPoli($v->kode_poli, "toNamaPoli");
			//         // $v->nama_poli = str_replace("POLI", "KLINIK", $namaPoli);
			//         $v->nama_poli = $namaPoli;
			//     }
			// }
		}

		if($type == "saatIni"){
			$Panggilan = DB::connection('mysql')->table('pemanggilan')
				->where('status', 1)
				// ->where('dari', $nama_counter->lv_user)
				->where('dari', $id_display)
				->orderBy('id', 'ASC')
				->first();

			if(!empty($Panggilan)){
				$kodePoli = Antrian::where('tgl_periksa', date('Y-m-d'))
					->where('nomor_antrian_poli', $Panggilan->no_antrian)
					// ->where('status', 'panggilpoli')
					->select('kode_poli')
					->first();
			}

			if(!empty($kodePoli)){
				$namaPoli = $this->convertPoli($kodePoli->kode_poli, "toNamaPoli");
				$Panggilan->nama_poli = $namaPoli;
			}
		}

		return [
			'status' => 'success', 
			'code' => 200, 
			'message' => $type == "antrian" ? 'Berhasil Get Antrian Counter' : 'Berhasil Get Antrian Counter Saat Ini', 
			'arrPol' => $arrPoli,
			'dt' => $arrDtPoli,
			'data' => ($type == "antrian" ? $arrDtPoli : $Panggilan),
		];
	}
}