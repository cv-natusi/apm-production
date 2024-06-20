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
		// return 'ok';
		JadwalDokterInternal::query()->truncate();
		date_default_timezone_set('Asia/Jakarta');
		$date = date('Y-m-d',strtotime('now'));
		$dayOfWeek = date('w', strtotime($date)); # minggu(0)-sabtu(6)
		$startDay = date('Y-m-d', strtotime("$date -$dayOfWeek day"));

		### Jadwal dokter non-bpjs
		$num = 1;
		$dokterNonBPJS = rsu_dokter_bridging::whereNotNull('polibpjs')->
			whereNotNull('kodedokter')->
			whereIn('polibpjs',['GIG','GIZ','MCU','PSY','VCT'])->
			orderBy('polibpjs','asc')->
			get(['dokter','poli','polibpjs','kode','kodedokter']);
		$day = 1;
		for($i=0; $i<=13; $i++){
			$timestamp = strtotime("$startDay +$i day");
			if(date('w',$timestamp)!='0'){ # Minggu =='0'
				foreach($dokterNonBPJS as $k => $v){
					$id = date('ymd',strtotime($date)).'NB'.sprintf("%03d",$num);
					JadwalDokterInternal::create(
						[ # Update or create
							'id' => $id,
							'date' => date('Y-m-d',strtotime("$startDay +$i day")),
							'is_active' => true,
							'is_bpjs' => false,
							'hari' => $day,
							'jam_praktek' => '07:30-14:00',
							'kapasitas_pasien' => '60',
							'kode_dokter' => $v->kodedokter,
							'kode_poli_bpjs' => $v->polibpjs,
							'kode_poli_rs' => $v->kode,
							'nama_dokter' => $v->dokter,
							'status_pilih' => false,
						]
					);
					$num++;
				}
				$day = $day<6 ? $day+1 : 1;
			}
		}

		### Jadwal dokter bpjs
		$num = 1;
		$dokterBPJS = rsu_dokter_bridging::whereNotNull('polibpjs')->
			whereNotNull('kodedokter')->
			whereNotIn('polibpjs',['GIG','GIZ','MCU','PSY','UMU','VCT'])->
			groupBy('polibpjs')->
			orderBy('polibpjs','asc')->
			get(['dokter','poli','polibpjs','kode','kodedokter']);
		$jadwalDokter = new \App\Http\Controllers\BridgBpjsController;
		for($i=0; $i<=13; $i++){ # Looping senin - sabtu
			$timestamp = strtotime("$startDay +$i day");
			if(date('w',$timestamp)!='0'){ # Minggu =='0'
				foreach($dokterBPJS as $k => $v){ # Data dokter dari internal
					$request->merge([
						'kodePoli' => $v->polibpjs,
						'tanggal' => date('Y-m-d',strtotime("$startDay +$i day"))
					]);
					$sendRequest = $jadwalDokter->refJadDok($request); # Get jadwal HFIS
					if(isset($sendRequest['metaData']) && $sendRequest['metaData']->code==200){
						foreach($sendRequest['response'] as $key => $bpjs){
							$id = date('ymd',strtotime($date)).'B'.sprintf("%03d",$num);
							$kodePoliBPJS = $bpjs->kodesubspesialis;
							$kodePoliRS = $bpjs->kodesubspesialis=='017' ? '134' : ($bpjs->kodesubspesialis=='040' ? '137' : $v->kode);
							JadwalDokterInternal::create(
								[ # Update or create
									'id' => $id,
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
							$num++;
						}
					}
				}
			}
		}
		return 'Sinkron jadwal dokter berhasil';
	}
}