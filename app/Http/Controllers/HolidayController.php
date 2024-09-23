<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

### Library / package
use File, Auth, Redirect, Validator, DB;
use Datatables;

### Models
use App\Http\Models\Antrian;
use App\Http\Models\Holidays;
use App\Http\Models\Rsu_Bridgingpoli;
use App\Helpers\apm as Help;

class HolidayController extends Controller
{
	public function main(Request $request)
	{
		$this->data['classtutup'] = '';
		return view('Admin.Holidays.index')->with('data', $this->data);
	}

	public function form(Request $request)
	{
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

	public function store(Request $request)
	{
		### Validasi start
		$rules = $message = [];
		if (in_array($request->kategori, ['libur-nasional', 'libur-poli']) || $request->format == 'tanggal') {
			$rules['tanggal'] = 'required';
			$message['tanggal.required'] = 'Tanggal harus diisi';
		}
		if (in_array($request->kategori, ['libur-poli', 'kuota-poli'])) {
			$rules['kode_poli'] = 'required';
			$message['kode_poli.required'] = 'Poli harus diisi';
		}
		if ($request->kategori == 'kuota-poli') {
			$rules['format'] = 'required';
			$message['format.required'] = 'Format kuota harus diisi';

			$rules['kuota_wa'] = 'required|numeric|min:0';
			$message['kuota_wa.required'] = 'Kuota WA harus diisi';
			$message['kuota_wa.numeric'] = 'Kuota WA harus berupa angka';
			$message['kuota_wa.min'] = 'Kuota WA harus diisi 0 atau lebih';
			
			$rules['kuota_kiosk'] = 'required|numeric|min:0';
			$message['kuota_kiosk.required'] = 'Kuota KIOSK harus diisi';
			$message['kuota_kiosk.numeric'] = 'Kuota KIOSK harus berupa angka';
			$message['kuota_kiosk.min'] = 'Kuota KIOSK harus diisi 0 atau lebih';
		}
		if ($request->format == 'hari') {
			$rules['hari'] = 'required';
			$message['hari.required'] = 'Hari harus diisi';
		}
		$validator = Validator::make($request->all(), $rules, $message);
		if ($validator->fails()) {
			$errMsg = "";
			$error = array_reverse($validator->messages()->all());
			foreach($error as $key => $val){
				$errMsg .= "$val.".($key+1 < count($error) ? "\n" : "")."";
			}
			return response()->json([
				'metadata' => [
					'code' => 422,
					'message' => $errMsg
				],
			], 422);
		}
		### Validasi end

		$ifHari = $request->format=='hari';
		$nRequest = $ifHari ? $request->hari : date('N',strtotime($request->tanggal));
		$ifTanggal = $request->format=='tanggal';
		if($request->holiday_id){
			$store = Holidays::find($request->holiday_id);
		}else{
			$store = new Holidays;
			$store->is_active = true;
		}


		$query = Holidays::where('kategori',$request->kategori);
		if ($request->holiday_id) {
			$query->where('id_holiday','!=',$request->holiday_id);
		}

		if ($request->kode_poli) {
			$query->where('poli_id',$request->kode_poli);
		}
		if ($ifHari) {
			$query->where('hari',$request->hari);
		}
		if ($ifTanggal || $request->kategori != 'kuota-poli') {
			$query->where('tanggal',date('Y-m-d',strtotime($request->tanggal)));
		}
		$check = $query->first();
		$count = 0;
		if (
			(
				$request->kategori=='kuota-poli'
				&& (
					$count = count(
						(
							$kuotaPoli = Holidays::
								where(
									fn($q)=>$q->where(
										fn($q)=>$q->whereNotNull('tanggal')->where('tanggal','>=',date('Y-m-d'))
									)->orWhere(
										fn($q)=>$q->where('hari',$nRequest)
									)
								)->where('poli_id',$request->kode_poli)
								->where('kategori',$request->kategori)
								->get()
						)
					)
				) > 0
			)
			|| (
				$request->kategori!='kuota-poli'
				&& (
					(!$request->holiday_id && $check) 
					|| $check
				)
			)
		) {
			$break = false;
			if ($count) {
				foreach($kuotaPoli as $key => $val){
					$dayInNum = $val->is_hari==true ? $val->hari : (int)date('N',strtotime($val->tanggal));
					if(
						$dayInNum == $nRequest
						&& (
							!$request->holiday_id
							|| (
								$request->holiday_id && (
									$request->kode_poli != $store->poli_id
									|| (
										$request->kode_poli == $val->poli_id
										&& $val->id_holiday != $store->id_holiday
									)
								)
							)
						)
					){
						$break = true;
						break;
					}
				}
			}
			if ($break || $request->kategori!='kuota-poli') {
				return response()->json([
					'metadata' => [
						'code' => 409,
						'message' => 'Data sudah pernah ditambahkan pada hari/tanggal yang sama'
					],
					'response' => $check,
				],409);
			}
		}

		if ($ifHari) {
			$store->hari = $request->hari;
			$store->tanggal = null;
		} else {
			$store->tanggal = date('Y-m-d',strtotime($request->tanggal));
			$store->hari = null;
		}

		$store->is_hari = $ifHari ? 1 : 0;
		$store->keterangan = $request->keterangan;
		$store->poli_id = $request->kode_poli;

		if (($kiosk = $request->kuota_kiosk)) {
			$store->kuota_kiosk = $kiosk;
		}
		
		if (($wa = $request->kuota_wa)) {
			$store->kuota_wa = $wa;
		}

		if( $mapping = Rsu_Bridgingpoli::where('kdpoli_rs',$request->kode_poli)->first()) {
			$store->poli_bpjs_id = $mapping->kdpoli;
		}

		$store->kategori = $request->kategori;
		$store->save();

		if ($store) {
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

	public function dataTable(Request $request)
	{
		$query = Holidays::where('kategori',$request->kategori)
			->with('poli')->orderBy('is_active','DESC');
		if ($request->kategori == 'kuota-poli') {
			$query->orderBy('tanggal','ASC');
			$query->orderBy('hari','ASC');
		}
		if ($request->kategori == 'libur-nasional') {
			$query->orderBy('tanggal','DESC');
		}
		$data = $query->get();

		return Datatables::of($data)
			->addIndexColumn()
			->addColumn('nama_poli',fn($row)=>$row->poli?$row->poli->NamaPoli:'-')
			->editColumn('tanggal',function($row)use($request){
				$request->merge(['nama_hari_en'=>$row->hari]);
				return $row->is_hari?ucfirst(Help::namaHariID($request)):$row->tanggal;
			})
			->addColumn('status',function($row){
				$status = $row->is_active==1 ? 'Aktif' : 'Tidak Aktif';
				$class = $row->is_active==1 ? 'success' : 'secondary';
				return "<span class='badge badge-$class'>$status</span>";
			})
			->addColumn('display_kuota_wa', function($row){
				if ($row->kategori == 'kuota-poli') {
					$query = DB::table('bot_pasien as bp')
						->select('bp.id')
						->join('bot_data_pasien as bdp', 'bp.id', '=', 'bdp.idBots')
						->whereDate('bp.tgl_periksa', '=', date('Y-m-d', $row->date_to_timestamps))
						->where('bp.statusChat', 99);

					$gigi = ['BDM', 'GIG', 'GND', 'KON'];
					if (in_array($row->poli_bpjs_id, $gigi)) {
						$query->whereIn('bdp.kodePoli', $gigi);
					}else{
						$query->where('bdp.kodePoli', $row->poli_bpjs_id);
					}
					$count = $query->count();
					return $count . '/' . $row->kuota_wa;
				}
				return $row->kuota_wa;
			})
			->addColumn('display_kuota_kiosk', function($row){
				if ($row->kategori == 'kuota-poli') {
					$query = Antrian::select('id','kode_poli')
						->where([
							'tgl_periksa' => date('Y-m-d', $row->date_to_timestamps),
							'metode_ambil' => 'KIOSK',
						]);
					$gigi = ['BDM', 'GIG', 'GND', 'KON'];
					if (in_array($row->poli_bpjs_id, $gigi)) { # Poli gigi
						$query->whereIn('kode_poli', $gigi);
					} else {
						$query->where('kode_poli', $row->poli_bpjs_id);
					}
					$count = $query->count();
					return $count . '/' . $row->kuota_kiosk;
				}
				return $row->kuota_kiosk;
			})
			->addColumn('aksi',function($row){
				// $tanggal = $row->tanggal;
				// $jam = $row->jam;
				$keterangan = $row->keterangan;
				$poli = $row->poli;
				// unset($row->tanggal);
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

				// $row->tanggal = $tanggal;
				// $row->jam = $jam;
				// $row->keterangan = $keterangan;
				// $row->poli = $poli;
				return $btn;
			})
			->make(true);
	}

	public function updateStatus(Request $request)
	{
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

	public function destroy(Request $request)
	{
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
}
