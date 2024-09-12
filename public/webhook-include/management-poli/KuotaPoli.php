<?php
namespace Webhook\ManagementPoli;
use Webhook\ManagementPoli;

class KuotaPoli extends ManagementPoli{
	public static function getKuotaPoli($request){
		// dateDetail($request); # Ambil tanggal dan nama hari untuk 3 hari kedepan, dari tanggal sekarang
		$query = "SELECT * FROM holidays
			WHERE kategori='kuota-poli'
			AND is_active=1
			AND (
				((tanggal BETWEEN '$request->dt_now' AND '$request->dt_plus') AND hari IS NULL)
				OR (tanggal IS NULL AND hari IN ($request->array_hari))
			)
		";
		$exec = mysqli_query($request->natusi_apm,$query);
		return $exec->fetch_all(MYSQLI_ASSOC);
	}

	public static function message($request): string
	{
		if(count($dataKuota = self::getKuotaPoli($request))==0){ # Jangan cetak pesan jika data 0
			return "";
		}
		$groupedData = [];
		foreach ($dataKuota as $item) {
			$poliId = $item['poli_id'];
			if (!isset($groupedData[$poliId])) {
				$groupedData[$poliId] = [
					'poli_id' => $item['poli_id'],
					'data' => []
				];
			}
			if($item['tanggal']){
				$item['hari'] = date('N',strtotime($item['tanggal']));
			}
			$groupedData[$poliId]['data'][] = $item;
		}
		$data = array_values($groupedData);

		# Cetak pesan
		$msg = "*Untuk sementara waktu.*\n";
		$msg .= "*==============================*\n";
		foreach ($data as $key => $datas) {
			$num = 1;
			$poliId = $datas['poli_id'];
			$query = "SELECT * FROM tm_poli WHERE KodePoli='$poliId'";
			$exec = mysqli_query($request->rsu_conn, $query);
			// $poli = $exec->fetch_all(MYSQLI_ASSOC);
			$poli = (object)$exec->fetch_assoc();
			$msg .= "Pendaftaran terbatas *$poli->NamaPoli* dengan kuota sebagai berikut:\n";

			# Urutkan data berdasarkan hari (ASC)
			array_multisort(array_column($datas['data'], 'hari'), SORT_ASC, $datas['data']);
			$sudahDigunakan = [];
			foreach ($datas['data'] as $keys => $items) {
				$increment = $keys+1;
				$items = (object)$items;
				$idx = isset($request->tanggal_detail[$items->hari]) ? $items->hari : (int)date('N',strtotime($items->tanggal));
				$tanggalDetail = $request->tanggal_detail[$idx];
				$namaHari = $tanggalDetail->nama_hari;
				$tanggal = $tanggalDetail->tanggal;
				$limit = $items->kuota_wa;

				$whereDate = date('Y-m-d',strtotime($tanggal));
				$query = "SELECT count(cust_id) as total FROM bot_pasien as bp
					JOIN bot_data_pasien as bdp ON bp.id = bdp.idBots
					WHERE bp.tgl_periksa = '$whereDate'
					AND bp.statusChat='99'
					AND bdp.kodePoli='$items->poli_bpjs_id'
				";
				$res = mysqli_query($request->apm_conn,$query);
				$total = mysqli_fetch_assoc($res)['total'];
				$total = $total > $limit ? $limit : $total;

				$msg .= "$num. $namaHari $tanggal, kuota terpakai $total/$limit";
				foreach ($datas['data'] as $val) {
					$keterangan = $val['keterangan'];
					if(
						$keterangan
						&& $val['poli_id'] == $datas['poli_id']
						&& !in_array($val['id_holiday'],$sudahDigunakan)
						&& $increment == count($datas['data'])
					){
						$keterangan = str_replace("<br />", "\n", $keterangan);
						$keterangan = str_replace("\r\n", "", $keterangan);
						$keterangan = str_replace("&nbsp", '', $keterangan);
						$keterangan = str_replace("<p>", '', $keterangan);
						$keterangan = str_replace("</p>", '', $keterangan);
						$keterangan = str_replace("<strong>", '*', $keterangan);
						$keterangan = str_replace("</strong>", '*', $keterangan);
						$msg .= "\n$keterangan";
						array_push($sudahDigunakan,$val['id_holiday']);
					}
				}
				if($increment < count($datas['data']) || $key+1 == count($data)){
					$msg .= "\n";
				}elseif($key+1 < count($data)){
					$msg .= "\n\n";
				}
				$num++;
			}
		}
		return "$msg*==============================*\n\n";
		// echo json_encode($data,JSON_PRETTY_PRINT);
		// $total = mysqli_fetch_assoc($res);
		// $total = $exec->fetch_all(MYSQLI_ASSOC);
	}

	public static function ignore($request): string
	{
		$tanggal = $request->tanggal_berobat;
		$dataKuota = self::getKuotaPoli($request);

		// $ignorePoli = "'ALG','UGD','ANU','GIG'"; # Default ignore
		$ignorePoli = ['ALG','UGD','ANU','GIG']; # Default ignore
		foreach($dataKuota as $item){
			$item = (object)$item;
			$total = 0;
			if(
				(
					$item->hari!="" && strtotime($request->tanggal_detail[$item->hari]->tanggal) == strtotime($tanggal)
				)
				|| strtotime($item->tanggal) == strtotime($tanggal)
			){
				$query = "SELECT count(cust_id) as total FROM bot_pasien as bp
					JOIN bot_data_pasien as bdp ON bp.id = bdp.idBots
					WHERE bp.tgl_periksa = '$tanggal'
					AND bp.statusChat='99'
					AND bdp.kodePoli='$item->poli_bpjs_id'
				";
				$res = mysqli_query($request->natusi_apm,$query);
				$total = mysqli_fetch_assoc($res)['total'];
				if($total >= $item->kuota_wa){
					array_push($ignorePoli, $item->poli_bpjs_id);
				}
			}
		}
		return "'".implode("','", $ignorePoli)."'";
	}
}