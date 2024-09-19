<?php

namespace App\Http\Controllers\Api\Webhook\ManagementPoli;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

### Helpers
use App\Helpers\apm as Help;

use App\Http\Models\Holidays;

class LiburNasionalController extends Controller
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
				'kategori'=>'libur-nasional',
				'is_active'=>1,
			]);
			if ($request->metode_ambil === 'wa' && $request->jenis === 'message') {
				Help::dateWhatsApp($request); # Add variable tanggal to request object
				// return $request->all();
				$query->whereDateWhatsapp($request);
			} else {
				$query->whereDateKiosk($request);
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
					'line' => $e->getLine(),
					'message' => $e->getMessage(),
				]
			],500);
		}
	}

	public static function message(Request $request)
	{
		# return 'tesllll';
		try {
			$request->merge([
				'metode_ambil' => 'wa',
				'jenis' => 'message',
			]);
			$exec = self::getData($request);
			$data = $exec->getData();
			if ($data->metadata->code==200) {
				### Print text start
				$text = "Pengumuman Libur Nasional:\n";
				$num = 1;
				$data = collect($data->response)->sortBy('tanggal')->values();
				foreach($data as $key => $val){
					$request->merge([
						'nama_hari_en' => (int)date('N',strtotime($val->tanggal))
					]);
					$tanggal = date('d-m-Y',strtotime($val->tanggal));
					$namaHari = Help::namaHariID($request);
					$text .= "$num. $namaHari, $tanggal";
					$text .= $key+1 < count($data) ? "\n" : "";
					$num++;
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
					'line' => $e->getLine(),
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
				],
			],500);
		}
	}
}
