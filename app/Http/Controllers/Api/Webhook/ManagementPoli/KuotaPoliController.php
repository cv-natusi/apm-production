<?php

namespace App\Http\Controllers\Api\Webhook\ManagementPoli;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use DB;
use App\Http\Libraries\RequestorWaBot;

### Helpers
use App\Helpers\apm as Help;

### Models
use App\Http\Models\Antrian;
use App\Http\Models\Holidays;
use App\Http\Models\rsu_poli;

class KuotaPoliController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public static function getData(Request $request)
	{
		// return [
		// 	date('d-m-Y',strtotime('now')),
		// 	date('w',strtotime('now +5day')), # Minggu = 0, Senin = 1
		// 	date('N',strtotime('now +5day')), # Minggu = 7, Senin = 1
		// ];
		try {
			$query = Holidays::where([
				'kategori'=>'kuota-poli',
				'is_active'=>1,
			]);

			if ($request->metode_ambil==='wa') {
				Help::dateWhatsApp($request); # Add variable tanggal to request object
				$query->whereDateWhatsapp($request);
			} else {
				// $date = $request->tanggal_berobat ? : date('Y-m-d');
				// $query->whereDate('tanggal','=',$date);
				$query->whereDateKiosk($request);
			}

			$data = $query->get([
				'id_holiday',
				'is_active',
				'is_hari',
				DB::raw('null as tanggal_temp'),
				DB::raw('null as timestamps'),
				'tanggal',
				'hari',
				DB::raw('null as nama_hari'),
				DB::raw('null as hari_temp'),
				'kuota_kiosk',
				'kuota_wa',
				'poli_id',
				'poli_bpjs_id',
				'keterangan',
			]);

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
					'message' => $e->getMessage()
				],
			],500);
		}
	}

	public static function message(Request $request)
	{
		try {
			$request->merge(['metode_ambil' => 'wa']);
			$exec = self::getData($request);
			$data = $exec->getData();

			if ($data->metadata->code == 200) {
				$groupedData = [];

				foreach ($data->response as $item) {
					$poliId = $item->poli_id;

					if (!isset($groupedData[$poliId])) {
						$groupedData[$poliId] = [
							'data' => [],
							'nama_poli' => rsu_poli::where('KodePoli', $item->poli_id)->first()->NamaPoli,
							'poli_id' => $item->poli_id,
						];
					}

					$groupedData[$poliId]['data'][] = $item;
					$groupedData[$poliId]['keterangan'][] = str_replace('"', "'", $item->keterangan);
				}

				$data = array_values($groupedData);
				usort($data, function ($a, $b) {
					return strcmp($a['nama_poli'], $b['nama_poli']);
				});

				### Print text start
				$text = "*Untuk sementara waktu.*\n";
				$text .= "*==============================*\n";

				foreach ($data as $key => $item) {
					$num = 1;
					$item = (object) $item;
					$namaPoli = $item->nama_poli;
					$text .= "Pendaftaran terbatas *$namaPoli* dengan kuota sebagai berikut:\n";

					# Urutkan data berdasarkan key hari_temp (ASC)
					array_multisort(array_column($item->data, 'hari_temp'), SORT_ASC, $item->data);

					foreach ($item->data as $keys => $items) {
						$increment = $keys + 1;
						$tanggal = date('d-m-Y', $items->timestamps);
						$namaHari = $items->nama_hari;

						$countPendaftaran = DB::table('bot_pasien as bp')
							->join('bot_data_pasien as bdp', 'bp.id', '=', 'bdp.idBots')
							->whereDate('bp.tgl_periksa', '=', date('Y-m-d', $items->timestamps))
							->where([
								'bp.statusChat' => 99,
								'bdp.kodePoli' => $items->poli_bpjs_id
							])->count();

						$kuota = $countPendaftaran <= $items->kuota_wa ? $countPendaftaran : $items->kuota_wa;
						$total = "$kuota/$items->kuota_wa";
						$text .= "$num. $namaHari $tanggal, kuota terpakai $total";

						foreach ($item->keterangan as $vals) {
							if (
								($keterangan = $vals)
								&& $item->poli_id == $items->poli_id
								&& $increment == count($item->data)
							) {
								$keterangan = self::replaceHtmlTagsWithSeparator($keterangan);
								$text .= "\n$keterangan";
							}
						}

						if ($increment < count($item->data) || $key + 1 == count($data)) {
							$text .= "\n";
						} elseif ($key + 1 < count($data)) {
							$text .= "\n\n";
						}

						$num++;
					}
				}

				$text .= "*==============================*\n\n";
				### Print text end

				return response()->json([
					'metadata' => [
						'code' => 200,
						'message' => 'Ok',
					],
					'response' => $text
				], 200);
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

	public static function replaceHtmlTagsWithSeparator($inputString)
	{
		# Daftar penggantian tag HTML
		$replacements = [
			"/<strong>/" => "*",    # Ganti tag <strong> dengan string kosong
			"/<\/strong>/" => "*",  # Ganti tag </strong> dengan string kosong
			"/<p[^>]*>/" => "",     # Ganti tag <p> dengan string kosong
			"/<\/p>/" => "",        # Ganti tag </p> dengan string kosong
			"/<s[^>]*>/" => "",     # Ganti tag <s> dengan string kosong
			"/<\/s[^>]*>/" => "",   # Ganti tag </s> dengan string kosong
			"/<u>/" => "",          # Ganti tag <u> dengan string kosong
			"/<\/u>/" => "",        # Ganti tag </u> dengan string kosong
			"/<em>/" => "_",        # Ganti tag <em> dengan karakter _
			"/<\/em>/" => "_",      # Ganti tag </em> dengan karakter _
			"/<br\s*\/?>/" => "\n", # Ganti tag <br> dengan newline
			'/\r\n\r\n/' => "\n",   # Ganti "\r\n\r\n" dengan newline
			'/\r\n/' => "",         # Ganti "\r\n" dengan string kosong
		];
	
		# Loop melalui array penggantian dan ganti tag HTML
		foreach ($replacements as $pattern => $replacement) {
			$inputString = preg_replace(
				$pattern,
				$replacement,
				$inputString
			);
		}
	
		return $inputString;
	}

	public static function ignorePoli(Request $request)
	{
		try {
			// Help::dateWhatsApp($request); # Add variable tanggal to request object
			$ignorePoli = ['ALG','UGD','ANU']; # Default ignore
			if ($request->metode_ambil !== 'kiosk') {
				array_push($ignorePoli, 'GIG');
			}

			$exec = self::getData($request);
			$data = $exec->getData();

			if ($data->metadata->code==200) {
				### Mapping array => cek duplikat => reindex array
				// $kodePoli = array_values(array_unique(array_map(fn($item)=>$item->poli_bpjs_id, $data->response)));
				foreach($data->response as $keys => $items){
					$kodePoliBpjs = $items->poli_bpjs_id;
					if ($request->metode_ambil === 'wa') {
						$request->merge([
							'timestamps' => $items->timestamps,
							'poli_bpjs_id' => $kodePoliBpjs,
						]);
						$antrian = Help::kuotaWa($request);
						$limit = $items->kuota_wa;
					} else {
						$date = $request->tanggal_berobat ? date('Y-m-d',strtotime($request->tanggal_berobat)) : date('Y-m-d');
						$antrian = Antrian::select('id','kode_poli')
							->where([
								'tgl_periksa' => $date,
								'metode_ambil' => 'KIOSK',
								'kode_poli' => $kodePoliBpjs,
							])
							->count();
						$limit = $items->kuota_kiosk;
					}
					if ($antrian < $limit) {
						unset($data->response[$keys]);
					}
				}

				$data = array_values($data->response);
				$ignorePoli = array_merge($ignorePoli, array_values(array_unique(array_map(fn($item)=>$item->poli_bpjs_id, $data))));
			}
			return response()->json([
				'metadata' => [
					'code' => 200,
					'message' => 'Ok',
				],
				'response' => $ignorePoli
			],200);
		} catch (\Throwable $e) {
			return response()->json([
				'metadata' => [
					'code' => 500,
					'message' => $e->getMessage(),
				],
			],500);
		}
	}

	public static function testing(Request $request)
	{
		$request->merge([
			'url' => 'kuota-poli/ignore-poli',
			'payload' => "metode_ambil=$request->metode_ambil",
		]);
		if($exec = RequestorWaBot::managementPoli($request)){
         // return $exec;
			// $code = $exec->metadata->code;
			// $exec = $exec->response;
			return response()->json($exec);
		}

		return response()->json([
			'metadata' => [
				'code' => 500,
				'message' => "Gagal terhubung ke server.",
			]
		],500);
	}
}
