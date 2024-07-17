<?php

namespace App\Http\Controllers\PengaturanJadwal;

# Library / package
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
# Models
use App\Http\Models\Rsu_Bridgingpoli;
use App\Http\Models\rsu_poli;
use App\Http\Models\SettingJadwalPoli;

class JadwalPoliController extends Controller{
	public function jadwalPoli(Request $request){
		// return $request;
		if($request->ajax()){
			return 'ajax';
		}
		$data = [
			'poli' => Rsu_Bridgingpoli::has('tm_poli')->with('tm_poli')
			->whereNotIn('kdpoli',['ALG','ANU','UGD'])
			->groupBy('kdpoli_rs')
			->get()
			// 'poli' => rsu_poli::has('mapping_poli_bridging')->with('mapping_poli_bridging')
			// // ->whereNotIn('KodePoli',['114','122','124','125','127','128','130','132','133','135','136','139','140','AMB','BD','BPOM','CID','COV19','CTL','CVD','GYTR','])
			// ->orderBy('KodePoli')
			// ->get(
			// 	[
			// 		'KodePoli',
			// 		'NamaPoli'
			// 	]
			// )
		];
		// return $data;
		return view('Admin.pengaturan-jadwal.poli.main',$data);
	}

	public function form(Request $request){
		// return $request->all();
		return Rsu_Bridgingpoli::with('tm_poli')->get();
		$menu = 'Tambah';
		$poli = collect([]);
		$html = view('Admin.pengaturan-jadwal.poli.form',compact('menu','poli'))->render();
		return ['status'=>'success', 'content'=>$html];
	}
}