<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Models\Holidays;
use App\Http\Models\Rsu_Bridgingpoli;
use App\Helpers\apm as Help;
use File, Auth, Redirect, Validator,DB;
use Datatables;

class HolidayController extends Controller{
	public function main(Request $request){
		$this->data['classtutup'] = '';
		// return view('Admin.Holidays.main')->with('data', $this->data);
		return view('Admin.Holidays.index')->with('data', $this->data);
	}

	public function form(Request $request){
		// return $request->all();

		// $request->merge(['nama_hari_en'=>date('D',strtotime('today'))]);
		// $namaHariID = Help::namaHariID($request);
		$poli = Rsu_Bridgingpoli::join('tm_poli', 'mapping_poli_bridging.kdpoli_rs', '=', 'tm_poli.KodePoli')
			->whereNotIn('kdpoli',['ALG','UGD','ANU'])
			->groupBy('mapping_poli_bridging.kdpoli_rs')
			->orderBy('tm_poli.NamaPoli','ASC')
			->get();

		$data = $request->id ? Holidays::find($request->id) : '';

		$content = view("Admin.Holidays.$request->jenis.form",['poli'=>$poli, 'data'=>$data])->render();
		return response()->json([
			'metadata' => [
				'code' => 200,
				'message' => 'Ok'
			],
			'response' => $content
		],200);
	}

	public function store(Request $request){
		// return response()->json([
		// 	'metadata' => [
		// 		'code' => 500,
		// 		'message' => 'Data gagal disimpan'
		// 	],
		// 	'response' => $request->all(),
		// ],500);
		if($request->holiday_id){
			$store = Holidays::find($request->holiday_id);
		}else{
			$store = new Holidays;
			$store->is_active = true;
		}
		// return $store;
		$ifHari = $request->format=='hari';
		if($ifHari){
			$store->hari = $request->hari;
			$store->tanggal = null;
		}else{
			$store->tanggal = date('Y-m-d',strtotime($request->tanggal));
			$store->hari = null;
		}
		$store->is_hari = $ifHari ? 1 : 0;
		$store->keterangan = $request->keterangan;
		$store->poli_id = $request->kode_poli;
		if(($kiosk = $request->kuota_kiosk)){
			$store->kuota_kiosk = $kiosk;
		}
		if(($wa = $request->kuota_wa)){
			$store->kuota_wa = $wa;
		}
		$store->kategori = $request->kategori;
		// return $store;
		$store->save();
		if($store){
			return response()->json([
				'metadata' => [
					'code' => 200,
					'message' => 'Data berhasil disimpan'
				],
			],200);
		}
		return response()->json([
			'metadata' => [
				'code' => 500,
				'message' => 'Data gagal disimpan'
			],
		],500);
	}

	public function dataTable(Request $request){
		$data = Holidays::
		// when($request->kategori)
		where('kategori',$request->kategori)
		->with('poli')->get();
		// unset($data->poli);
		// return $data;
		return Datatables::of($data)
			->addIndexColumn()
			->addColumn('nama_poli',fn($row)=>$row->poli?$row->poli->NamaPoli:'-')
			->editColumn('tanggal',fn($row)=>$row->is_hari?ucfirst($row->hari):$row->tanggal)
			->addColumn('status',function($row){
				$status = $row->is_active==1 ? 'Aktif' : 'Tidak Aktif';
				$class = $row->is_active==1 ? 'success' : 'secondary';
				return "<span class='badge badge-$class'>$status</span>";
			})
			->addColumn('aksi',function($row){
				// if($data->no_rm!='00000000000'){
				// 	$btn .= "<button class='btn btn-sm btn-warning' title='Cetak Tracer' onclick='cetakTracer(`$data->id`)'><i class='fa fa-print'></i></button> &nbsp;";
				// }
				// $data = $row->map(function($rows){
				// 	return collect($rows->toArray())
				// 	->only(['kategori'])
				// 	->all();
				// });

				$tanggal = $row->tanggal;
				$jam = $row->jam;
				$keterangan = $row->keterangan;
				$poli = $row->poli;
				unset($row->tanggal);
				unset($row->keterangan);
				unset($row->poli);
				$data = json_encode($row);

				// $btn = "<div class='text-center'>";
				$btn = "<button class='btn btn-sm btn-info' title='Edit' onclick='editForm(`$data`)'><i class='fa fa-pencil' aria-hidden='true'></i></button> &nbsp;";
				$title = $row->is_active ? 'Nonaktifkan' : 'Aktifkan';
				$color = $row->is_active ? 'secondary' : 'success';
				$btn .= "<button class='btn btn-sm btn-$color' title='$title' onclick='updateStatus(`$data`)'><i class='fa fa-power-off' aria-hidden='true'></i></button> &nbsp;";
				$btn .= "<button class='btn btn-sm btn-danger' title='Hapus' onclick='destroy(`$data`)'><i class='fa fa-trash' aria-hidden='true'></i></button> &nbsp;";
				// $btn .= "</div>";

				$row->tanggal = $tanggal;
				$row->jam = $jam;
				$row->keterangan = $keterangan;
				$row->poli = $poli;
				return $btn;
			})
			->make(true);
	}
	public function updateStatus(Request $request){
		// return $request->all();

		// return response()->json([
		// 	'metadata' => [
		// 		'code' => 204,
		// 		'message' => 'Data tidak ditemukan'
		// 	],
		// ],200);
		if($data = Holidays::find($request->holiday_id)){
			$data->is_active = $data->is_active==true ? false : true;
			$data->save();
			return response()->json([
				'metadata' => [
					'code' => 200,
					'message' => 'Data berhasil disimpan'
				],
			],200);
		}
		return response()->json([
			'metadata' => [
				'code' => 204,
				'message' => 'Data tidak ditemukan'
			],
		],204);
	}

	public function destroy(Request $request){
		if($data = Holidays::find($request->holiday_id)){
			if($data->delete()){
				return response()->json([
					'metadata' => [
						'code' => 200,
						'message' => 'Data berhasil dihapus'
					],
				],200);
			}
			return response()->json([
				'metadata' => [
					'code' => 500,
					'message' => 'Data gagal dihapus'
				],
			],500);
		}
		return response()->json([
			'metadata' => [
				'code' => 204,
				'message' => 'Data tidak ditemukan'
			],
		],204);
	}

	public function testing(Request $request){
		\Log::info('testing');
	}


	public function datagrid(Request $request){
		$data = Holidays::getJson($request);
		return response()->json($data);
	}
	
	public function datagridKuotaPoli(Request $request){
		try {
			$data = Holidays::getJsonKuotaPoli($request);
			return response()->json($data);
		} catch (\Throwable $e) {
			$log = ['ERROR DATAGRID ('.$e->getFile().')',false,$e->getMessage(),$e->getLine()];
			Help::logging($log);
			return false;
		}
	}
	
	public function datagridLiburPoli(Request $request){
		$data = Holidays::getJsonLiburPoli($request);
		return response()->json($data);
	}
	
	public function formAdd(Request $request){
		$content = view('Admin.Holidays.formAdd')->render();
		return ['status' => 'success', 'content' => $content];
	}
	
	public function formAddKuotaPoli(Request $request){
		$data['poli'] = DB::connection('dbrsud')->table('tm_poli as p')->join('mapping_poli_bridging as bp','bp.kdpoli_rs','=','p.KodePoli')->get();
		$content = view('Admin.Holidays.formAddKuotaPoli', $data)->render();
		return ['status' => 'success', 'content' => $content];
	}
	
	public function formAddLiburPoli(Request $request){
		$data['poli'] = DB::connection('dbrsud')->table('tm_poli as p')->join('mapping_poli_bridging as bp', 'bp.kdpoli_rs', '=', 'p.KodePoli')->get();
		$content = view('Admin.Holidays.formAddLiburPoli', $data)->render();
		return ['status' => 'success', 'content' => $content];
	}
	
	public function Add(Request $request){
		// $cekHoliday = Holidays::where('tanggal',date('Y-m-d',strtotime($request->tanggal_libur)))->first();
		// if (!empty($cekHoliday)) {
		$holiday = new Holidays;
		$holiday->tanggal = date('Y-m-d',strtotime($request->tanggal_libur));
		$holiday->keterangan = strip_tags($request->keterangan);
		$holiday->kategori = "Libur Nasional";
		$holiday->save();
		
		if ($holiday) {
			return Redirect::route('holiday')->with('title', 'Berhasil !!')->with('message', 'Tanggal Libur Berhasil Ditambahkan')->with('type', 'success');
		}else{
			return redirect()->route('holiday')->with('title', 'Maaf !!')->with('message', 'Tanggal Libur Gagal Ditambahkan')->with('type', 'error');
		}
		// }else{
		//     return redirect()->route('holiday')->with('title', 'Maaf !!')->with('message', 'Tanggal Libur Sudah Di Pilih Sebagai Tanggal Libur')->with('type', 'error');
		// }
	}
	
	public function AddKuotaPoli(Request $request){
		// $cekHoliday = Holidays::where('tanggal',date('Y-m-d',strtotime($request->tanggal_libur)))->first();
		// if (!empty($cekHoliday)) {
		$holiday = new Holidays;
		$holiday->tanggal = date('Y-m-d',strtotime($request->tanggal_libur));
		$holiday->poli_id = $request->pilih_poli;
		$holiday->kuota_wa = $request->kuota_wa;
		$holiday->kuota_kiosk = $request->kuota_kiosk;
		$holiday->keterangan = strip_tags($request->keterangan);
		$holiday->kategori = "Kuota Poli";
		$holiday->save();
		
		if ($holiday) {
			return Redirect::route('holiday')->with('title', 'Berhasil !!')->with('message', 'Tanggal Libur Berhasil Ditambahkan')->with('type', 'success')->with('kuota_poli', true);
		}else{
			return redirect()->route('holiday')->with('title', 'Maaf !!')->with('message', 'Tanggal Libur Gagal Ditambahkan')->with('type', 'error');
		}
		// }else{
		//     return redirect()->route('holiday')->with('title', 'Maaf !!')->with('message', 'Tanggal Libur Sudah Di Pilih Sebagai Tanggal Libur')->with('type', 'error');
		// }
	}
	
	public function AddLiburPoli(Request $request){
		// $cekHoliday = Holidays::where('tanggal',date('Y-m-d',strtotime($request->tanggal_libur)))->first();
		// if (!empty($cekHoliday)) {
		$holiday = new Holidays;
		$holiday->tanggal = date('Y-m-d',strtotime($request->tanggal_libur));
		$holiday->poli_id = $request->pilih_poli;
		$holiday->keterangan = strip_tags($request->keterangan);
		$holiday->kategori = "Libur Poli";
		$holiday->save();
		
		if ($holiday) {
			return Redirect::route('holiday')->with('title', 'Berhasil !!')->with('message', 'Tanggal Libur Berhasil Ditambahkan')->with('type', 'success')->with('libur_poli', true);
		}else{
			return redirect()->route('holiday')->with('title', 'Maaf !!')->with('message', 'Tanggal Libur Gagal Ditambahkan')->with('type', 'error');
		}
		// }else{
		//     return redirect()->route('holiday')->with('title', 'Maaf !!')->with('message', 'Tanggal Libur Sudah Di Pilih Sebagai Tanggal Libur')->with('type', 'error');
		// }
	}
	
	public function formUpdate(Request $request){
		$data['holiday'] = Holidays::find($request->id);
		$content = view('Admin.Holidays.formUpdate',$data)->render();
		return ['status' => 'success', 'content' => $content];
	}
	
	public function formUpdateKuotaPoli(Request $request){
		try {
			$data['poli'] = DB::connection('dbrsud')->table('tm_poli as p')->join('mapping_poli_bridging as bp','bp.kdpoli_rs','=','p.KodePoli')->get();
			$data['holiday'] = Holidays::find($request->id);
			$content = view('Admin.Holidays.formUpdateKuotaPoli',$data)->render();
			return ['status' => 'success', 'content' => $content];
		} catch (\Throwable $e) {
			$log = ['ERROR FORM KUOTA POLI ('.$e->getFile().')',false,$e->getMessage(),$e->getLine()];
			Help::logging($log);
			return false;
		}
		
	}
	
	public function formUpdateLiburPoli(Request $request){
		$data['poli'] = DB::connection('dbrsud')->table('tm_poli as p')->join('mapping_poli_bridging as bp','bp.kdpoli_rs','=','p.KodePoli')->get();
		$data['holiday'] = Holidays::find($request->id);
		$content = view('Admin.Holidays.formUpdateLiburPoli',$data)->render();
		return ['status' => 'success', 'content' => $content];
	}
	
	public function Updates(Request $request){
		$cekHoliday = Holidays::where('id_holiday','=',$request->id_holiday)->first();
		if ($cekHoliday) {
			$holiday = Holidays::find($request->id_holiday);
			$holiday->tanggal = date('Y-m-d',strtotime($request->tanggal_libur));
			$holiday->keterangan = strip_tags($request->keterangan);
			$holiday->save();
			
			if ($holiday) {
				return Redirect::route('holiday')->with('title', 'Berhasil !!')->with('message', 'Tanggal Libur Berhasil Diperbaharui')->with('type', 'success');
			}
			return redirect()->route('holiday')->with('title', 'Maaf !!')->with('message', 'Tanggal Libur Gagal Diperbaharui')->with('type', 'error');
		}
		return redirect()->route('holiday')->with('title', 'Maaf !!')->with('message', 'Tanggal Libur Sudah Di Pilih Sebagai Tanggal Libur')->with('type', 'error');
	}
	
	public function UpdatesKuotaPoli(Request $request){
		$cekHoliday = Holidays::where('id_holiday','=',$request->id_holiday)->first();
		if (!empty($cekHoliday)) {
			$holiday = Holidays::find($request->id_holiday);
			$holiday->tanggal = date('Y-m-d',strtotime($request->tanggal_libur));
			$holiday->poli_id = $request->pilih_poli;
			$holiday->kuota_wa = $request->kuota_wa;
			$holiday->kuota_kiosk = $request->kuota_kiosk;
			$holiday->keterangan = strip_tags($request->keterangan);
			$holiday->save();
			
			if ($holiday) {
				return Redirect::route('holiday')->with('title', 'Berhasil !!')->with('message', 'Tanggal Libur Berhasil Diperbaharui')->with('type', 'success')->with('kuota_poli', true);
			}else{
				return redirect()->route('holiday')->with('title', 'Maaf !!')->with('message', 'Tanggal Libur Gagal Diperbaharui')->with('type', 'error');
			}
		}else{
			return redirect()->route('holiday')->with('title', 'Maaf !!')->with('message', 'Tanggal Libur Sudah Di Pilih Sebagai Tanggal Libur')->with('type', 'error');
		}
	}
	
	public function UpdatesLiburPoli(Request $request){
		$cekHoliday = Holidays::where('id_holiday', '=', $request->id_holiday)->first();
		if (!empty($cekHoliday)) {
			$holiday = Holidays::find($request->id_holiday);
			$holiday->tanggal = date('Y-m-d',strtotime($request->tanggal_libur));
			$holiday->poli_id = $request->pilih_poli;
			$holiday->keterangan = strip_tags($request->keterangan);
			$holiday->save();
			
			if ($holiday) {
				return Redirect::route('holiday')->with('title', 'Berhasil !!')->with('message', 'Tanggal Libur Berhasil Diperbaharui')->with('type', 'success')->with('libur_poli', true);
			}else{
				return redirect()->route('holiday')->with('title', 'Maaf !!')->with('message', 'Tanggal Libur Gagal Diperbaharui')->with('type', 'error');
			}
		}else{
			return redirect()->route('holiday')->with('title', 'Maaf !!')->with('message', 'Tanggal Libur Sudah Di Pilih Sebagai Tanggal Libur')->with('type', 'error');
		}
	}
	
	public function delete(Request $request){
		$holiday = Holidays::find($request->id);
		if(!empty($holiday)){
			$holiday->delete();
			return ['status' => 'success', 'message' => 'Data berhasil dihapus'];
		}else{
			return ['status'=>'error', 'message'=>'Invalid user.'];
		}
	}
	
	public function deleteKuotaPoli(Request $request){
		$holiday = Holidays::find($request->id);
		if(!empty($holiday)){
			$holiday->delete();
			return ['status' => 'success', 'message' => 'Data berhasil dihapus'];
		}else{
			return ['status'=>'error', 'message'=>'Invalid user.'];
		}
	}
	
	public function deleteLiburPoli(Request $request){
		$holiday = Holidays::find($request->id);
		if(!empty($holiday)){
			$holiday->delete();
			return ['status' => 'success', 'message' => 'Data berhasil dihapus'];
		}else{
			return ['status'=>'error', 'message'=>'Invalid user.'];
		}
	}
}
