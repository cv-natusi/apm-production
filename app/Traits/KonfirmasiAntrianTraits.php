<?php

namespace App\Traits;

use App\Http\Models\Rsu_Bridgingpoli;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Http\Models\Antrian;

trait KonfirmasiAntrianTraits {
	use FillingTraits;

    public function generateQrCode($id_kiosk)
	{
		$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		//getlastdata yang belum diisi untuk ambil token
		$getData = DB::connection('mysql')->table('token_konfirmasi')
			->where('kiosk', $id_kiosk)
			->orderBy('created_at', 'DESC')
			->first();
		//getlastdata yang telah terisi untuk sekedar ditampilkan
		$data = DB::connection('mysql')->table('token_konfirmasi')
			->whereNotNull('pasien_baru_temporary_id')
			->where('kiosk', $id_kiosk)
			->where('tanggal_token',date('Y-m-d'))
			->orderBy('created_at', 'DESC')
			->first();
		//getdatapasienBelumPrint
		if(!empty($data)){
			$nikPasien = DB::connection('mysql')->table('pasien_baru_temporary')
			->where('id_pas', $data->pasien_baru_temporary_id)
			->first();
			
			if(!empty($nikPasien)){
				$pasien = DB::connection('mysql')->table('antrian')
				->where('nik', $nikPasien->nik)
				->where('tgl_periksa', date('Y-m-d'))
				->first();
			}

			if(!empty($pasien)){
				$dataPasien = $this->generateDataPasien($pasien);
			}
		}
		
		//pengecekan jika db masih kosong maka langsung create new
		if(empty($getData)){
			$saveData = DB::connection('mysql')->table('token_konfirmasi')->insert([
				'nama_token' => "token_kiosk" . $id_kiosk . "_1",
				'token' => substr(str_shuffle(str_repeat($pool, 5)), 0, 16),
				'tanggal_token' => date('Y-m-d'),
				'sudah_print' => 0,
				'status' => 0,
				'kiosk' => $id_kiosk,
				'created_at' => date('Y-m-d H:i:s')
			]);

			$lastInsertToken = DB::connection('mysql')->table('token_konfirmasi')
				->where('kiosk', $id_kiosk)
				->orderBy('created_at', 'DESC')
				->first()->token;
		}else{
			//jika db sudah ada data maka ambil yang terkahir dan cek apakah sudah terisi atau belum
			$status = $getData->status;
			if ($status == 1) {
				//nomor urut token hanya untuk penamaan saja
				$data_token_urut = DB::connection('mysql')->table('token_konfirmasi')
					->where('kiosk', $id_kiosk)
					->orderBy('created_at', 'DESC')
					->count();
				$token_urut = $data_token_urut + 1;
				//jika sudah terisi maka generate baru
				DB::connection('mysql')->table('token_konfirmasi')->insert([
					'nama_token' => "token_kiosk" . $id_kiosk . "_" . $token_urut,
					'token' => substr(str_shuffle(str_repeat($pool, 5)), 0, 16),
					'tanggal_token' => date('Y-m-d'),
					'sudah_print' => 0,
					'status' => 0,
					'kiosk' => $id_kiosk,
					'created_at' => date('Y-m-d H:i:s')
				]);
				
				$lastInsertToken = DB::connection('mysql')->table('token_konfirmasi')
				->where('kiosk', $id_kiosk)
				->orderBy('created_at', 'DESC')
				->first()->token;
			}else {
				//jika belum terisi langsung ambil tokennya
				$lastInsertToken = $getData->token;
			}
		}

		// QrCode::style('round')->gradient(1, 1, 1, 1, 1, 1, 'diagonal')->size(200)->generate($lastInsertToken, public_path('aset/images/tokenQR/'.$lastInsertToken.'.svg') );
		QrCode::size(300)->generate($lastInsertToken, public_path('aset/images/tokenQR/'.$lastInsertToken.'.svg') );
		//menghapus token lama
		if(!empty($data)){
			$tokenLama = public_path('aset/images/tokenQR/'.$data->token.'.svg');
			if(file_exists($tokenLama)){
				unlink($tokenLama);
			}
		}

		return [
			'status' => 'success',
			'message' => 'Berhasil Get/Generate Token',
			'kiosk' => $id_kiosk,
			'token'=>$lastInsertToken,
			'data'=>!empty($data) ? $data : "", 
			'dataPasien'=>!empty($dataPasien) ? $dataPasien : "" 
		];
	}

	// public function convertPoli($kodePoli, $tujuan = "toKodePoli") //$tujuan(toKodePoli / toNamaPoli)
	// {
	// 	try {
	// 		if($tujuan == "toKodePoli"){
	// 			$poli = DB::connection('dbrsud')->table('mapping_poli_bridging as m')
	// 				->join('tm_poli as p','m.kdpoli_rs','=','p.KodePoli')
	// 				->where('p.KodePoli', $kodePoli)
	// 				->first();
	// 		}else{
	// 			$poli = DB::connection('dbrsud')->table('mapping_poli_bridging as m')
	// 				->join('tm_poli as p','m.kdpoli_rs','=','p.KodePoli')
	// 				->where('m.kdpoli', $kodePoli)
	// 				->first();
	// 			if (!$poli) {
	// 				$poli = DB::connection('dbrsud')->table('mapping_poli_bridging as m')
	// 				->join('tm_poli as p','m.kdpoli_rs','=','p.KodePoli')
	// 				->where('m.kdpoli_rs', $kodePoli)
	// 				->first();
	// 			}
	// 		}
	// 	} catch (\Exception $th) {
	// 		return ['status'=> 'gagal', 'code'=>500 , 'message'=>$th];
	// 	}

	// 	if($tujuan == "toKodePoli"){
	// 		return $poli->kdpoli;
	// 	}else{
	// 		return $poli->NamaPoli;
	// 	}
	// }
	public function convertPoli($kodePoli, $tujuan = "toKodePoli") //$tujuan(toKodePoli / toNamaPoli)
	{
		try {
			if($tujuan == "toKodePoli"){
				$poli = DB::connection('dbrsud')->table('mapping_poli_bridging as m')
					->join('tm_poli as p','m.kdpoli_rs','=','p.KodePoli')
					->where('p.KodePoli', $kodePoli)
					->first();
			}else{
				$poli = DB::connection('dbrsud')->table('mapping_poli_bridging as m')
					->join('tm_poli as p','m.kdpoli_rs','=','p.KodePoli')
					->where('m.kdpoli', $kodePoli)
					->first();
				if (!$poli) {
					$poli = DB::connection('dbrsud')->table('mapping_poli_bridging as m')
					->join('tm_poli as p','m.kdpoli_rs','=','p.KodePoli')
					->where('m.kdpoli_rs', $kodePoli)
					->first();
				}
			}
		} catch (\Exception $th) {
			return ['status'=> 'gagal', 'code'=>500 , 'message'=>$th];
		}

		if($tujuan == "toKodePoli"){
			return $poli->kdpoli;
		}else{
			return $poli->NamaPoli;
		}
	}

	// public function generateNoKodeBooking($isPasienBaru, $tglPeriksa)
	// {
	// 	$prefix = $isPasienBaru == "Y" ? "B" : "L";
	// 	$antri = DB::connection('mysql')->table('antrian')
	// 			->where('tgl_periksa',$tglPeriksa)
	// 			->where('no_antrian','like',"$prefix%")
	// 			->orderBy('no_antrian','desc')
	// 			->select('no_antrian')
	// 			->first();
	// 	$num = 0;
	// 	if(!empty($antri)){
	// 		$num = (int)substr($antri->no_antrian, 1);
	// 	}
	// 	$angkaAntri = sprintf("%03d",$num+1);
	// 	$nextAntri = "$prefix".$angkaAntri;
	// 	// kode booking
	// 	$kodebooking = date('dmy').$nextAntri;
	// 	$noAntrian = substr($kodebooking,6);

	// 	return ["kode_booking" => $kodebooking, "nomor_antrian" => $noAntrian];
	// }
	public function generateNoKodeBooking($caraBayar, $tglPeriksa)
	{
		# No Antrean
		$prefix = 'NB';
		if($caraBayar=='BPJS'){
			$prefix = 'B';
		}
		$length = strlen($prefix)+3;
		$antri = DB::connection('mysql')->table('antrian')
				->select('no_antrian')
				->where('tgl_periksa',$tglPeriksa)
				->whereRaw("LENGTH(no_antrian)=$length")
				->where('no_antrian','like',"$prefix%")
				->orderBy('no_antrian','desc')->first();
		$num = 0;
		if(!empty($antri)){
			$num = (int)substr($antri->no_antrian, -3);
		}
		$angkaAntri      = sprintf("%03d",$num+1);
		$nextAntri       = "$prefix".$angkaAntri;
		# kode booking
		$kodebooking = date('dmy').$nextAntri;
		
		return ["kode_booking" => $kodebooking, "nomor_antrian" => $nextAntri];
	}

	public function generateNoAntrianPoli($kodePoli)
	{
		try {
			// Generate no. antrian
			$kodeAwal = DB::connection('mysql')->table('kode_awalan_poli')
				->where('kdpoli', $kodePoli)
				->first();

			$length = (strlen($kodeAwal->kode_awal))+3;
			$antri = Antrian::where('tgl_periksa',date('Y-m-d'))
				->whereRaw("LENGTH(nomor_antrian_poli)=$length")
				->where('nomor_antrian_poli','like', $kodeAwal->kode_awal.'%')
				->orderBy('nomor_antrian_poli','desc')
				->first();

			$num = 0;
			if(!empty($antri)){
				$num = (int)substr($antri->nomor_antrian_poli, -3);
			}

			$angkaAntri = sprintf("%03d",$num+1);
			$noAntrianPoli = $kodeAwal->kode_awal . $angkaAntri;

			Log::info('generateNoAntrianPoli - succes : ' . $noAntrianPoli);
			return $noAntrianPoli;
		} catch (\Exception $e) {
			Log::info('generateNoAntrianPoli - failed : ' . $e->getMessage() );
			abort(500, 'Gagal Generate No Antrian Poli');
		}
	}

	public function convertReqWaToSimapan($dataPasien)
	{
		$data = [
			'id_pas' => $dataPasien->id,
			'kodeUnik' => $dataPasien->random,
			'isPasienBaru' => $dataPasien->is_pasien_baru == 1 ? "Y" : "N",
			'nik' => $dataPasien->nik,
			'bpjs' => $dataPasien->nomor_kartu,
			'nama' => $dataPasien->nama,
			'tanggalLahir' => $dataPasien->tglLahir,
			'no_hp' => $dataPasien->phone,
			'namaIbu' => $dataPasien->namaIbu,
			'tanggalPeriksa' => $dataPasien->tglBerobat,
			'kodePoli' => $dataPasien->kodePoli,
			'isGeriatri' => $dataPasien->isGeriatri,
			'caraBayar' => $dataPasien->caraBayar,
			'masukMaster' => $dataPasien->masukMaster,
			'nomor_referensi' => $dataPasien->nomor_referensi,
			'kode_dokter' => $dataPasien->kodedokter,
			'jam_praktek' => $dataPasien->jam_praktek,
			'no_rm' => $dataPasien->KodeCust,
		];

		return (object) $data;
	}

	public function generateReqAntrean($dataPasien,$kodeBooking,$noAntrian, $destination = "toLocal", $from = "simapan", $noAntriPbaru="") //destination only (toLocal and toBpjs), from only (simapan dan wa)
	{
		$noRujuk = $dataPasien->nomor_referensi;
		$rujukanDipakai = Antrian::select('nomor_referensi')->where('nomor_referensi',$noRujuk)->first();
		$prefixRujuk = substr($noRujuk,12,1);
		$jenis_kunjungan = '2'; # Rujukan internal
		if(!$rujukanDipakai){ # Rujukan pertama kali berkunjung, jika tidak default "2"
			if(in_array($prefixRujuk, ['P','Y','U','G'])){ # Rujukan FKTP Tingkat 1 (puskesmas,klinik)
				$jenis_kunjungan = '1';
			}
			if($prefixRujuk=='B'){ # Rujukan FKTP Tingkat 2 (RS)
				$jenis_kunjungan = '4';
			}
			if($prefixRujuk=='K'){ # Kontrol
				$jenis_kunjungan = '3';
			}
		}
		$data = [
			"nik" => $dataPasien->nik,
			// "jenis_kunjungan" => $dataPasien->caraBayar == "BPJS" ? 1 : 2,
			"jenis_kunjungan" => $jenis_kunjungan,
			"no_rm" => $dataPasien->isPasienBaru == "Y" ? "00000000000" : $dataPasien->no_rm,
			"nohp" => "000000000000",
			"jenis_pasien" => $dataPasien->caraBayar,
		];

		$kodePoli = $dataPasien->kodePoli;
		if($from == "simapan"){
			$kodePoli = DB::connection('dbrsud')->table('mapping_poli_bridging as m')
					->join('tm_poli as p','m.kdpoli_rs','=','p.KodePoli')
					->where('m.kdpoli_rs', $kodePoli)
					->first()->kdpoli;
		}

		if($destination == "toLocal"){
			$data += [
				"kode_poli"          => $kodePoli,
				"no_antrian"         => $noAntrian,
				"status"             => $dataPasien->isPasienBaru == "Y" ? "belum" : "antripoli",
				"tgl_periksa"        => $dataPasien->tanggalPeriksa,
				"nomor_kartu"        => $dataPasien->caraBayar == "BPJS" ? $dataPasien->bpjs : "",
				"nomor_referensi"    => $dataPasien->caraBayar == "BPJS" ? $dataPasien->nomor_referensi : "",
				"kode_booking"       => $kodeBooking,
				"is_geriatri"        => $dataPasien->isGeriatri,
				"metode_ambil"       => $from == "simapan" ? "SIMAPAN" : "WA", //hardcode SIMAPAN
				"is_pasien_baru"     => $dataPasien->isPasienBaru,
				"kode_dokter"        => $dataPasien->kode_dokter,
				"jam_praktek"        => $dataPasien->jam_praktek,
				"cekin"              => null, //dinull karena diupdate via loket/counter
				"alasan_batal"       => null, //dinull karena diupdate via loket/counter
				"status_filling"     => "belum",
				"nomor_antrian_poli" => $dataPasien->isPasienBaru == "Y" ? null : $this->generateNoAntrianPoli($kodePoli),
				"no_antrian_pbaru"	 => $noAntriPbaru != '' ? $noAntriPbaru:''
				// "No_Register"        => $dataPasien->isPasienBaru == "Y" ? null : $this->generateNoRegistrasi(),
			];
		}else{
			$data += [
				"kodebooking"  => $kodeBooking,
				"no_bpjs"      => $dataPasien->caraBayar == "BPJS" ? $dataPasien->bpjs : "",
				"no_referensi" => $dataPasien->caraBayar == "BPJS" ? $dataPasien->nomor_referensi : "",
				"kodepoli"     => $from == "simapan" ? $this->convertPoli($dataPasien->kodePoli) : $dataPasien->kodePoli,
				"pasienbaru"   => $dataPasien->isPasienBaru == "Y" ? 1 : 0,
				"tglperiksa"   => $dataPasien->tanggalPeriksa,
				"kddokter"     => $dataPasien->kode_dokter,
				"jadwal"       => $dataPasien->jam_praktek,
				"metodes"      => "SIMAPAN", //hardcode BPJS
			];
		}
		return $data;
	}

	public function generateReqAntreanTracer($idAntrian, $dataPasien)
	{
		$data = [
			"antrian_id" => $idAntrian,
			"from" => "simapan",
			"to" => $dataPasien->isPasienBaru == "Y" ? "loket" : "poli",
			"status_tracer" => "1",
			"tgl" => date('Y-m-d'),
			"time" => date('H:i:s',strtotime( "+7 hour" )),
		];

		return $data;
	}

	public function generateReqUpdateWaktuBpjs($dataPasien, $taskId) //data ambil dari antrian, taskId tergantung darimana
	{
		$data = [
			"kodebooking" => $dataPasien->kode_booking,
			"taskid" => $taskId,
			"waktu" => strtotime(date('Y-m-d H:i:s')) * 1000,
		];

		return $data;
	}

	public function generateDataPasien($dataPasien)
	{
		if($dataPasien->is_pasien_baru=='N' || $dataPasien->nomor_antrian_poli!=""){
			$counter = Rsu_Bridgingpoli::where('kdpoli', $dataPasien->kode_poli)->with('tm_poli.trans_konter_poli.mst_konterpoli')->first();
			$tujuan = !empty($counter) ? $counter->tm_poli->trans_konter_poli->mst_konterpoli->nama_konterpoli : "";
			$noAntrian = $dataPasien->nomor_antrian_poli;
		}else{
			$tujuan = "Loket";
			$noAntrian = $dataPasien->no_antrian_pbaru;
		}
		// if($dataPasien->is_pasien_baru=='Y'){
		// 	$tujuan = "Loket";
		// 	$noAntrian = $dataPasien->no_antrian;
		// }else{
		// 	$counter = Rsu_Bridgingpoli::where('kdpoli', $dataPasien->kode_poli)->with('tm_poli.trans_konter_poli.mst_konterpoli')->first();
		// 	$tujuan = !empty($counter) ? $counter->tm_poli->trans_konter_poli->mst_konterpoli->nama_konterpoli : "";
		// 	$noAntrian = $dataPasien->nomor_antrian_poli;
		// }

		$dataPas = [
			"idAntrian" => $dataPasien->id,
			"noAntrian" => $noAntrian,
			"kodeBooking" => !empty($dataPasien->kode_booking) ? $dataPasien->kode_booking : "-",
			"poli" => $this->convertPoli($dataPasien->kode_poli, "toNamaPoli"),
			"tujuan" => $tujuan
		];

		return $dataPas;
	}
}