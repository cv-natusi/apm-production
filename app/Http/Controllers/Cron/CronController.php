<?php

namespace App\Http\Controllers\Cron;

# Library / package
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

# Models
use App\Http\Models\JadwalDokterInternal;
use App\Http\Models\rsu_dokter_bridging;

class CronController extends Controller{
	public function syncJadwalDokter(Request $request){
		// JadwalDokterInternal::query()->truncate(); # Hapus semua jadwal & reset autoincrement ke 0
		JadwalDokterInternal::query()->update([
			'date' => null,
			'is_active'=>false,
		]);
		date_default_timezone_set('Asia/Jakarta');
		$date = date('Y-m-d',strtotime('now +1day')); # Jika update(cron) hari sabtu
		// $date = date('Y-m-d'); # Jika update(cron) hari minggu
		// $date = date('Y-m-d',strtotime('16-03-2024 +1day'));
		$dayOfWeek = date('w', strtotime($date)); # minggu(0)-sabtu(6)
		$startDay = date('Y-m-d', strtotime("$date -$dayOfWeek day"));
		$dokterNonBPJS = rsu_dokter_bridging::whereNotNull('polibpjs')->
			whereNotNull('kodedokter')->
			whereIn('polibpjs',['GIG','GIZ','MCU','PSY','VCT'])->
			orderBy('polibpjs','asc')->
			get(['dokter','poli','polibpjs','kode','kodedokter']);
		for($i=1; $i<=6; $i++){
			foreach($dokterNonBPJS as $k => $v){
				JadwalDokterInternal::updateOrCreate(
					[ # Where condition
						'kode_dokter'=>$v->kodedokter,
						'hari'=>$i,
					],
					[ # Update or create
						'date' => date('Y-m-d',strtotime("$startDay +$i day")),
						'is_active' => true,
						'is_bpjs' => false,
						'hari' => $i,
						'jam_praktek' => '07:30-14:00',
						'kapasitas_pasien' => '60',
						'kode_dokter' => $v->kodedokter,
						'kode_poli_bpjs' => $v->polibpjs,
						'kode_poli_rs' => $v->kode,
						'nama_dokter' => $v->dokter,
						'status_pilih' => false,
					]
				);
			}
		}
		$dokterBPJS = rsu_dokter_bridging::whereNotNull('polibpjs')->
			whereNotNull('kodedokter')->
			whereNotIn('polibpjs',['GIG','GIZ','MCU','PSY','UMU','VCT'])->
			groupBy('polibpjs')->
			orderBy('polibpjs','asc')->
			get(['dokter','poli','polibpjs','kode','kodedokter']);
		$jadwalDokter = new \App\Http\Controllers\BridgBpjsController;
		for($i=1; $i<=6; $i++){ # Looping senin - sabtu
			foreach($dokterBPJS as $k => $v){ # Data dokter dari internal
				$request->merge([
					'kodePoli' => $v->polibpjs,
					'tanggal' => date('Y-m-d',strtotime("$startDay +$i day"))
				]);
				$sendRequest = $jadwalDokter->refJadDok($request); # Get jadwal HFIS
				if(isset($sendRequest['metaData']) && $sendRequest['metaData']->code==200){
					foreach($sendRequest['response'] as $key => $bpjs){
						$kodePoliBPJS = $bpjs->kodesubspesialis;
						// $kodePoliRS = $bpjs->kodesubspesialis=='017' ? '134' : ($bpjs->kodesubspesialis=='040' ? '137' : $bpjs->kodesubspesialis);
						$kodePoliRS = $bpjs->kodesubspesialis=='017' ? '134' : ($bpjs->kodesubspesialis=='040' ? '137' : $v->kode);
						JadwalDokterInternal::updateOrCreate(
							[ # Where condition
								'kode_dokter'=>$bpjs->kodedokter,
								'hari'=>$bpjs->hari,
							],
							[ # Update or create
								'date' => $request->tanggal,
								'is_active' => true,
								'is_bpjs' => true,
								'hari' => $bpjs->hari,
								'jam_praktek' => $bpjs->jadwal,
								'kapasitas_pasien' => $bpjs->kapasitaspasien,
								'kode_dokter' => $bpjs->kodedokter,
								'kode_poli_bpjs' => $kodePoliBPJS,
								'kode_poli_rs' => $kodePoliRS,
								'nama_dokter' => $bpjs->namadokter,
								'status_pilih' => false,
							]
						);
					}
				}
			}
		}
		return 'Ok';
	}
}