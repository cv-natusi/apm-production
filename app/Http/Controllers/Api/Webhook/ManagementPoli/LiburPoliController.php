<?php

namespace App\Http\Controllers\Api\Webhook\ManagementPoli;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

### Helpers
use App\Helpers\apm as Help;

### Models
use App\Http\Models\Holidays;
use App\Http\Models\rsu_poli;

class LiburPoliController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public static function getData(Request $request)
	{
		try {
			$query = Holidays::where([
				'kategori'=>'libur-poli',
				'is_active'=>1,
			]);

			if ($request->metode_ambil==='wa') {
				$query->whereBetween('tanggal',[$request->date_start, $request->date_end]);
			} else {
				$query->whereDate('tanggal','=',date('Y-m-d'));
			}

			$data = $query->get();
			if (count($data)) {
				return response()->json([
					'metadata' => [
						'code' => 200,
						'message' => 'Ok'
					],
					'response' => $data
				],200);
			}
			return response()->json([
				'metadata' => [
					'code' => 204,
					'message' => 'No Content'
				],
			],204);
		} catch (\Throwable $e) {
			return response()->json([
				'metadata' => [
					'code' => 500,
					'message' => 'Internal server error'
				]
			],500);
		}
	}

	public static function message(Request $request)
	{
		try {
			$exec = self::getData($request);
			$data = $exec->getData();
			if ($data->metadata->code==200) {
				### Print text start
				$text = "LIBUR POLI :\n";
				$num = 1;
				foreach($data->response as $key => $val){
					if ($poli = rsu_poli::where('KodePoli',$val->poli_id)->first()) {
						$request->merge([
							'nama_hari_en' => (int)date('N',strtotime($val->tanggal))
						]);
						$tanggal = date('d-m-Y',strtotime($val->tanggal));
						$namaPoli = strtoupper($poli->NamaPoli);
						$namaHari = Help::namaHariID($request);
						$text .= "$num. $namaHari $tanggal, $namaPoli";
						$text .= $key+1 < count($data->response)  ? "\n" : "";
						$num++;
					}
				}
				### Print text end
				return response()->json([
					'metadata' => [
						'code' => 200,
						'message' => 'Ok',
					],
					'response' => $text
				],200);
			}
			return $exec;
		} catch (\Throwable $e) {
			return response()->json([
				'metadata' => [
					'code' => 500,
					'message' => $e->getMessage(),
				]
			],500);
		}
	}

	public static function ignorePoli(Request $request)
	{
		try {
			$exec = self::getData($request);
			$data = $exec->getData();
			if ($data->metadata->code==200) {
				### Mapping array => cek duplikat => reindex array
				$kodePoli = array_values(array_unique(array_map(fn($item)=>$item->poli_bpjs_id, $data->response)));

				return response()->json([
					'metadata' => [
						'code' => 200,
						'message' => 'Ok',
					],
					'response' => $kodePoli
				],200);
			}
			return $exec;
		} catch (\Throwable $e) {
			return response()->json([
				'metadata' => [
					'code' => 500,
					'message' => $e->getMessage(),
				]
			],500);
		}
	}
}