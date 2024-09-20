<?php

namespace App\Http\Controllers\Api\Webhook\ManagementPoli;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

### Helpers
use App\Helpers\apm as Help;

use App\Http\Models\Holidays;
use App\Http\Models\Rsu_Bridgingpoli;

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
			\Log::error(json_encode([
				'title' => 'LIBUR NASIONAL GET DATA',
				'message' => $e->getMessage()
			], JSON_PRETTY_PRINT));
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
					'response' => $text,
					'tanggal' => $data->pluck('tanggal')->toArray(),
				],200);
			}
			return $exec;
		} catch (\Throwable $e) {
			\Log::error(json_encode([
				'title' => 'LIBUR NASIONAL MESSAGE',
				'message' => $e->getMessage()
			], JSON_PRETTY_PRINT));
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
			$ignorePoli = ['ALG', 'UGD', 'ANU', 'BDM', 'GIG', 'GND', 'KON']; # Default ignore
			// if ($request->metode_ambil !== 'kiosk') {
			// 	array_push($ignorePoli, 'GIG');
			// }

			$exec = self::getData($request);
			$data = $exec->getData();

			if ($data->metadata->code==200) {
				### Mapping array => cek duplikat => reindex array
				// $ignorePoli = array_merge($ignorePoli, array_values(array_unique(array_map(fn($item)=>$item->poli_bpjs_id, $data->response))));

				$data = Rsu_Bridgingpoli::join('tm_poli', 'mapping_poli_bridging.kdpoli_rs', '=', 'tm_poli.KodePoli')
					->whereNotNull('kdpoli')
					->whereNotIn('kdpoli',['HDL'])
					->groupBy('mapping_poli_bridging.kdpoli_rs')
					->orderBy('tm_poli.NamaPoli','ASC')
					->get()->toArray();

				### Mapping array => cek duplikat => reindex array
				// $kodePoli = array_values(array_unique(array_map(fn($item)=>$item['kdpoli'], $data)));
				$ignorePoli = array_merge($ignorePoli, array_values(array_unique(array_map(fn($item)=>$item['kdpoli'], $data))));

				return response()->json([
					'metadata' => [
						'code' => 200,
						'message' => 'Ok',
					],
					'response' => $ignorePoli
				],200);
			}
			return $exec;
		} catch (\Throwable $e) {
			\Log::error(json_encode([
				'title' => 'LIBUR NASIONAL IGNORE',
				'message' => $e->getMessage()
			], JSON_PRETTY_PRINT));
			return response()->json([
				'metadata' => [
					'code' => 500,
					'message' => $e->getMessage(),
				],
			],500);
		}
	}
}
