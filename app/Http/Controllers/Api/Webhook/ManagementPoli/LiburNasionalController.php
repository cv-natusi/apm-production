<?php

namespace App\Http\Controllers\Api\Webhook\ManagementPoli;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
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
		$data = Holidays::where([
			'kategori'=>'libur-nasional',
			'is_active'=>1,
		])
		->whereDate('tanggal','>=',date('Y-m-d'))
		->get();

		return response()->json($data);
	}

	public static function message(Request $request)
	{
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
