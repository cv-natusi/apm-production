<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Models\MstKonterPoli;
use App\Http\Models\Bridgingpoli;
use App\Http\Models\Rsu_Bridgingpoli;
use App\Http\Models\rsu_poli;
use App\Http\Models\Users;
use App\Http\Models\TransKonterPoli;
use Redirect, Validator, Datatables, DB, Auth, DateTime;

class MstKonterPoliController extends Controller{
	public function index(){
		return view('Admin.mstkonterpoli.main');
	}

	public function datagrid(Request $request){
		if ($request->ajax()) {
			$mst = MstKonterPoli::with(['trans_konter_poli'])->get();
			// $poli = rsu_poli::has('mapping_poli_bridging')->get();
			// $arrKodePoli = [];
			// foreach($mst as $keyM =>$valM){
			// 	foreach($valM->trans_konter_poli as $keyP => $valP){
			// 		if(in_array($valP->poli_id, $arrKodePoli)){
			// 		}else{
			// 			array_push($arrKodePoli,$valP->poli_id);
			// 		}
			// 	}
			// }
			// $data = rsu_poli::has('mapping_poli_bridging')->with('trans_konter_poli.mst_konterpoli')
			// ->whereIn('KodePoli',$arrKodePoli)
			// ->get();

			//modifidata
			foreach ($mst as $key => $value) {
				foreach ($value->trans_konter_poli as $key => $vl) {
					$namaPoli = DB::connection('mysql')->table('tm_poli')
					->where('kode_poli',$vl->poli_id)
					->first();
					if(!empty($namaPoli)){
						if(!isset($value->listPoli)){
							$value->listPoli = $namaPoli->NamaPoli;
						}else{
							$value->listPoli .= "," . $namaPoli->NamaPoli;
						}
					}
				}
			}

			return Datatables::of(collect($mst))    
			->addIndexColumn()
			// ->addColumn('namaKonter',function($row){
			// 	return $row->trans_konter_poli->mst_konterpoli->nama_konterpoli;
			// })
			// ->addColumn('urlVideo',function($row){
			// 	return $row->trans_konter_poli->mst_konterpoli->url;
			// })
			->make(true);
		}
		return view('Admin.mstkonterpoli.main');
	}

	public function form(Request $request){
		if (!empty($request->id)) {
			$data['data'] = MstKonterPoli::with('trans_konter_poli.tm_poli')
			->where('id', $request->id)->first();
			//modifidata
			$listPoli = [];
			foreach ($data['data']->trans_konter_poli as $key => $vl) {
				array_push($listPoli,$vl->poli_id);
			}
			
			$data['menu'] = 'Edit';
		} else {
			$data ['data'] = '';
			$data ['menu'] = 'Tambah';
		}
		$data['getPoli'] = TransKonterPoli::with('tm_poli')->get();
		$arrkonter = [];
		foreach ($data['getPoli'] as $key => $v) {
			$getPoliId = $v->poli_id;
			array_push($arrkonter, $getPoliId);
		}
		$data['poli'] = Rsu_Bridgingpoli::with('tm_poli')
			->groupBy('mapping_poli_bridging.kdpoli_rs')
			->whereNotIn('kdpoli_rs', $arrkonter)
			->orderBy('kdpoli_rs','ASC')
			->get();
		$data ['getUser'] = Users::whereNotIn('id', [1,8,9])->get();
		$content = view('Admin.mstkonterpoli.form', $data)->render();
		return ['status' => 'success', 'content' => $content, 'data' => $data];
	}
	 
	public function getPoli(Request $request){
		$data = rsu_poli::find($request->id);
		return response()->json($data, 200);
	}

	public function store(Request $request){
		if (!empty($request->id)) {
			$data = MstKonterPoli::find($request->id);
		} else {
			$data = new MstKonterPoli;
		}
		$data->nama_konterpoli = $request->konterpoli;
		$data->user_id = $request->user_id;
		$data->url = $request->url;
		$data->save();
		if ($request->has('poli_id')) {
			$this->saveTransKonterPoli($data->id, $request->input('poli_id'));
		}
		if ($data) {
			return ['status' => 'success', 'code' => 200, 'message' => 'Data Pasien Berhasil Disimpan!', 'data' => $data];
		}
		return ['status' => 'error', 'code' => 500, 'message' => 'Gagal Disimpan!', 'data' => ''];
	}

	public function editForm(Request $request){
		$data['data'] = MstKonterPoli::with('trans_konter_poli.tm_poli')
			->where('id', $request->id)
			->first();
		$data['poli'] = Rsu_Bridgingpoli::with('tm_poli')
			->groupBy('mapping_poli_bridging.kdpoli_rs')
			// ->whereNotIn('kdpoli_rs', $arrkonter)
			->orderBy('kdpoli_rs','ASC')
			->get();
		$data['user'] = Auth::user()->id;
		$content = view('Admin.mstkonterpoli.form', $data)->render();
		return ['status' => 'success', 'content' => $content, 'data' => $data];
	}

	public function saveTransKonterPoli($konter_poli_id, $poli_id){
		$i = 0;
		foreach ($poli_id as $key => $value) {
			$transKonterPoli = new TransKonterPoli;
			$transKonterPoli->konter_poli_id = $konter_poli_id;
			$transKonterPoli->poli_id = $poli_id[$i];
			$transKonterPoli->save();
			$i++;
		}
	}

	public function deletePoli(Request $request){
		$data = TransKonterPoli::where('poli_id', $request->id)->first();
		if(!empty($data)){
			$data = TransKonterPoli::where('poli_id', $request->id)->delete();
			return ['status' => 'success','message' => 'Berhasil Menghapus Poli','title' => 'Success'];
		}
		return ['status' => 'error','message' => 'Gagal Menghapus Poli','title' => 'Success'];
	}
}