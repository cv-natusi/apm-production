<?php

namespace App\Http\Controllers\Antrian;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

# Library / package
use App\Http\Libraries\Requestor;
use Auth, DateTime, DB;
use Yajra\Datatables\Datatables;
# Models
use App\Http\Models\Antrian;
use App\Http\Models\MstKonterPoli;
use App\Http\Models\Rsu_Bridgingpoli;
use App\Http\Models\Rsu_Register;
use App\Http\Models\Rsu_setupall;
use App\Http\Models\Users;

class CounterController extends Controller{
	public function formListCounter(Request $request){
		if ($request->ajax()) {
			$today = date('Y-m-d');
			$data['user'] = Auth::user()->id;
			$cekUser = Users::find($data['user']);
			if(!empty($cekUser)){
				if($cekUser->lv_user!='admin'){
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
					if(in_array('GIG', $arrpoli)||in_array('BDM', $arrpoli)||in_array('GND', $arrpoli)||in_array('KON', $arrpoli)){
						$arrpoli[] = 'GIG';
						$arrpoli[] = 'GND';
						$arrpoli[] = 'BDM';
						$arrpoli[] = 'KON';
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
				return $this->templateAction($row);
			})
			->make(true);
		}
		return view('Admin.antreanBPJS.listAntrian.mainCounterPoli');
	}
	function templateAction($data){
		// Get No RM
		$pm = json_encode((object)['kodebooking' => $data->kode_booking,'nomor_antrian_poli' => $data->nomor_antrian_poli]);
		$am = json_encode((object)['id' => $data->id, 'nomor_antrian_poli' => $data->nomor_antrian_poli]);
		$norm = $data->no_rm;
		$btn = "<div class='text-center'>";
		// $btn .= "<button class='btn btn-sm' style='background-color: #2CBA44; margin-right: 10px; color: #fff;' title='Detail' onclick='detail(`$data->kode_booking`)'>Detail</button>";
		$btn .= "<button class='btn btn-sm' style='background-color: #2CBA44; margin-right: 10px; color: #fff;' title='Detail' onclick='detail(`$data->id`)'>Detail</button>";
		if ($data->jenis_pasien == 'BPJS') {
			$btn .= "<button class='btn btn-sm' style='background-color: #D9D9D9; color: #000;' onclick='cetaksep(`$norm`)'>Cetak SEP</button>";
		} else {
			$btn .= "<button class='btn btn-sm' style='background-color: #92FD6D; color: #000;' onclick='tracer(`$am`)'>Tracer</button>";
		}
		// $btn .= "<button class='btn btn-sm btn-danger' title='Batalkan' style='margin-left: 5px;' onclick='batalkan(`$data->kode_booking`)'><i class='fa fa-remove' aria-hidden='true'></i></button> <br>";
		// $btn .= "<button class='btn btn-sm btn-warning' title='Cetak SEP' style='margin-top: 5px;' onclick='cetaksep(`$norm`)'><i class='fa fa-print' aria-hidden='true'></i></button>";
		// $btn .= "<button class='btn btn-sm btn-primary' title='Kirim Ke Poli' style='margin-left: 5px; margin-top: 5px;' onclick='arahkan(`$pm`)'><i class='fa fa-arrow-right' aria-hidden='true'></i></button>";
		$btn .= "</div>";

		return $btn;
	}

	public function pindahPoli(Request $request){
		$data['data'] = Antrian::with(['tm_customer','mapping_poli_bridging.tm_poli'])
		->where('id', $request->id)
		->first();

		$data['poli'] = Rsu_Bridgingpoli::join('tm_poli', 'mapping_poli_bridging.kdpoli_rs', '=', 'tm_poli.KodePoli')->get();
		$data['jenis_pasien'] = Rsu_setupall::where('groups','Asuransi')->get();
		$data['jenis_pasien_detail'] = collect($data['jenis_pasien'])->filter(function($item)use($data){
			$jenisPasien = $data['data']->jenis_pasien;
			if(
				($jenisPasien=='ASURANSILAIN' && !in_array($item->subgroups,['1001','1007','1008']))
				|| ($jenisPasien=='BPJS' && in_array($item->subgroups,['1007','1008']))
				|| ($jenisPasien=='UMUM' && in_array($item->subgroups,['1001']))
			){
				return $item;
			}
		})->values();
		// $id = $request->id;
		$content = view('Admin.antrean.pindah-poli.counter',$data)->render();
		return response()->json([
			'metadata' => [
				'code' => 200,
				'message'=> 'Oke',
			],
			'response' => $content
			// 'response' => $data
		]);
	}

	/**
	 * @param string $request
	 */
	public function resetNomorAntrianPoli(Request $request){
		$poliBpjsLama = $request->poli_bpjs_lama;
		$poliBpjsBaru = $request->poli_bpjs_baru;
		try{
			if(!($antrian = Antrian::where('id',$request->id_antrian)->first())){
				return response()->json([
					'metadata' => [
						'code' => 204
					]
				],204);
			}
			if(
				Antrian::where([
					'nik' => $antrian->nik,
					'tgl_periksa'=>$antrian->tgl_periksa,
					'kode_poli' => $poliBpjsBaru
				])
				->where('id','!=',$request->id_antrian)
				->first()
			){
				return response()->json([
					'metadata' => [
						'code' => 400,
						'message' => 'Poli tujuan tidak bisa dirubah, pasien sudah pernah berkunjung di poli tersebut hari ini',
					]
				],400);
			}
			if(in_array($antrian->status,['batal', 'akhirpoli', 'antrifarmasi', 'panggilfarmasi', 'akhirfarmasi'])){
				return response()->json([
					'metadata' => [
						'code' => 400,
						'message' => 'Poli tujuan tidak bisa dirubah, karena antrian sudah dilayani poli atau sudah dibatalkan',
					]
				],400);
			}
			$kodeRS['poli_rs_lama'] = Rsu_Bridgingpoli::where('kdpoli',$antrian->kode_poli)->first()->kdpoli_rs;
			if($antrian->kode_poli!=$poliBpjsBaru){
				$kodeRS['poli_rs_baru'] = Rsu_Bridgingpoli::where('kdpoli',$poliBpjsBaru)->first()->kdpoli_rs;
			}else{
				$kodeRS['poli_rs_baru'] = $kodeRS['poli_rs_lama'];
			}
			$request->merge($kodeRS);

			$antrian->kode_poli = $poliBpjsBaru;
			$antrian->nomor_antrian_poli = null;
			if(in_array($antrian->status,['antripoli','panggilpoli','layanpoli'])){
				$antrian->status = 'antripoli';
			}
			$antrian->save();

			# Update kode-poli-rs di tr_registrasi
			DB::connection('dbrsud')->table('tr_registrasi')
			->where([
				'No_RM'=>$antrian->no_rm,
				'Kode_Poli1'=>$request->poli_rs_lama
			])
			->whereDate('Tgl_Register','=',$antrian->tgl_periksa)
			->update(['Kode_Poli1'=>"$request->poli_rs_baru"]);
			return response()->json([
				'metadata' => [
					'code' => 200,
					'message' => 'Oke'
				],
				'response' => $antrian
			],200);
		}catch(\Throwable $e){
			return response()->json([
				'metadata' => [
					'code' => 500,
					'message' => 'Terjadi kesalahan sistem'
				],
			],500);
		}
	}

	public function gantiPenjamin(Request $request){
		try{
			if(!($antrian = Antrian::where('id',$request->id_antrian)->first())){
				return response()->json([
					'metadata' => [
						'code' => 204
					]
				],204);
			}
			$request->merge([
				'poli_rs' => Rsu_Bridgingpoli::where('kdpoli',$antrian->kode_poli)->first()->kdpoli_rs
			]);
			$antrian->jenis_pasien = $request->jenis_pasien;
			$antrian->pembayaran_pasien = $request->pembayaran_baru;
			$antrian->save();

			# Update penjamin di tr_registrasi
			DB::connection('dbrsud')->table('tr_registrasi')
				->where([
					'No_RM'=>$antrian->no_rm,
					'Kode_Poli1'=>$request->poli_rs
				])
				->whereDate('Tgl_Register','=',$antrian->tgl_periksa)
				->update(['Kode_Ass'=>"$antrian->pembayaran_pasien"]);
			return response()->json([
				'metadata' => [
					'code' => 200,
					'message' => 'Oke'
				],
				'response' => $antrian
			],200);
		}catch(\Throwable $e){
			return response()->json([
				'metadata' => [
					'code' => 500,
					'message' => 'Terjadi kesalahan sistem'
				],
			],500);
		}
	}
}
