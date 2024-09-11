<?php
	use App\Http\Libraries\RequestorWaBot;
	use App\Http\Controllers\WaBotBridgingController;
	use App\Http\Models\rsu_dokter_bridging;
	use Illuminate\Http\Request;

	require('../vendor/autoload.php');

	header("Content-Type: text/plain");
	date_default_timezone_set("Asia/Jakarta");

	$data = json_decode(file_get_contents('php://input'), true);
	$id = $data['id'];
	$pushName = $data['pushName'];
	$isGroup = $data['isGroup'];
	if ($isGroup == true) {
		return false;
		$subjectGroup = $data['group']['subject'];
		$ownerGroup = $data['group']['owner'];
		$decriptionGroup = $data['group']['desc'];
		$partisipanGroup = $data['group']['participants'];
	}
	$isFromMe = $data['isFromMe'];
	if($isFromMe){
		return false;
	}
	$message = $data['message'];
	$phone = $data['phone'];
	// $phone = '6281335537942';

	### Info maintenance server
	// if($phone!='6281335537942'){ # Nomor untuk maintenance
	// 	if($phone!=""){
	// 		$msg = "*Sehubungan dengan perbaikan server data untuk optimalisasi sistem informasi, maka akan terjadi down sistem pada:*\n";
	// 		$msg .= "*Jum'at, 29 September 2023 pukul 17:00 s/d 19:00 WIB.*";
	// 		echo $msg;
	// 		die();
	// 	}
	// }else{
	// 	echo "MAINTENANCE";die();
	// 	die();
	// }

	### Libur
	// $msg = "Tanggal *09 - 11 Februari 2024*,\n";
	// $msg .= "pelayanan Rawat Jalan Poli Spesialis dan\n";
	// $msg .= "Poli Eksekutif *LIBUR*.\n";
	// $msg .= "*BUKA* kembali pada tanggal *12 Februari 2024*\n";
	// echo $msg;die();


	$messageType = $data['messageType'];
	$file = $data['file'];
	$mimeType = $data['mimeType'];
	$deviceId = $data['deviceId'];
	$sender = $data['sender'];
	$timestamp = $data['timestamp'];

	### Koneksi database
	// $wablas = mysqli_connect('103.55.39.180','rsudwahi_wablas','rsudwahi_wablas','rsudwahi_wablas');
	// $wablas = mysqli_connect('localhost','client','Wahidin123','natusi_apm','3307');

	$wablas = mysqli_connect('192.168.1.5','client','Wahidin123','natusi_apm');
	$dbrsud = mysqli_connect('192.168.1.5','client','Wahidin123','dbsimars');
	// $wablas = mysqli_connect('core-mysql','dwialim','36b6BtSCzW^6','natusi_apm');
	// $dbrsud = mysqli_connect('core-mysql','dwialim','36b6BtSCzW^6','dbsimars_baru');

	$request = new Request([
		'rsu_conn' => $dbrsud,
		'apm_conn' => $wablas,
		'natusi_apm' => $wablas,
		'phone' => $phone,
	]);
	if(!$wablas){
		die("tes Connection Failed".mysqli_connect_error());
	}
	if(!$dbrsud){
		die("tes Connection Failed".mysqli_connect_error());
	}

	if($request->phone=='6281335537942'){
		require('management-poli/libur-nasional');
		die();
	}

	$baseURL = "https://apm.rsuwahidinmojokerto.com/";

	$waText = strtolower($message);
	$exp = explode(" ", $waText); // explode with space
	$expN = explode("\n", $waText); // explode with new line(Enter)
	$txt1 = $exp[0];

	$dateNow = date("Y-m-d");
	// $dateNow = date("Y-m-d",strtotime("17-11-2022"));
	$dateNowPlus1 = date("d-m-Y",strtotime($dateNow."+1 day"));
	$cekStatus = "SELECT * FROM bot_pasien WHERE phone='$phone' AND tanggalChat='$dateNow' AND status_akun=true";
	$result = mysqli_query($wablas,$cekStatus);
	$rows = $result->fetch_assoc();
	$rowsDapas=$idPsn=$idBots=$statusChat="";
	$dataIn = [];
	$listPhone = ['6281335537942','6281330003568'];
	if($result->num_rows>0){
		$idBots = $rows['id'];
		$statusChat = $rows['statusChat'];
		$cekDapas = "SELECT * FROM bot_data_pasien WHERE idBots='$idBots'";
		$resDapas = mysqli_query($wablas,$cekDapas);
		if($resDapas->num_rows>0){
			$rowsDapas = $resDapas->fetch_assoc();
			$idPsn = $rowsDapas['cust_id'];
		}
	}

	$reset = stripos($waText,'reset')===false;

	### Info pendaftaran
	if($result->num_rows<1){
		$msg = msgWelcome($request);
		// if($phone=='6281335537942'){
		// 	echo pemberitahuanPoli($request);
		// 	// echo $msg;
		// 	die();
		// }
	}
	function waBotBridging(){
		return new WaBotBridgingController;
	}

	if($result->num_rows<1 && $waText=='b'){
		die('MAAF SAAT INI FITUR BELUM DAPAT DIGUNAKAN!');
		$msg = "MAAF SAAT INI FITUR BELUM DAPAT DIGUNAKAN!";
	}

	if($waText=='a'){
		$msg = "1. Daftar Pasien Baru\n";
		$msg .= "2. Daftar Pasien Lama\n\n";
		$msg .= "Ketik angka 1 atau 2 untuk melakukan pendaftaran.";
		if($result->num_rows==0){
			$statusChat1 = "INSERT INTO bot_pasien (phone,tanggalChat,statusChat,status_akun) VALUES('$phone','$dateNow',1,true)";
			mysqli_query($wablas,$statusChat1);
		}
		$updateChatLama = "UPDATE bot_pasien SET status_akun=false WHERE phone='$phone' AND statusChat<99 AND tanggalChat<'$dateNow' AND status_akun=true";
		mysqli_query($wablas,$updateChatLama);
		mysqli_close($wablas);
	}

	if(is_numeric($waText) && $statusChat==1 && $waText=='1'){
		if(($rows['pasien_baru']=="" || $rows['pasien_baru']==1) && $rows['pasien_id']==""){
			$msg = "Silahkan tuliskan\nNAMA,NIK,TanggalLahir,JENISPASIEN(UMUM/BPJS/ASURANSILAIN)\n";
			$msg .= "Contoh : ADI SUJATMIKO,1000200030004000,23-02-1987,UMUM";
			$dataIn = [
				'status' => 2,
				'trueFalse' => true
			];
			updateStatusChat('baruLama',$idBots,$wablas,$dataIn);
			mysqli_close($wablas);
		}else{
			if($rows['pasien_baru']==0){
				$msg = "Anda Sedang Mendaftar *Sebagai Pasien Lama!*\n";
				$msg .= "Yakin Ingin Mendaftar *Sebagai Pasien Baru?*\n";
				$msg .= "Jika Ya Ketik : Baru";
			}else{
				$msg = "Silahkan tuliskan\nNAMA,NIK,TanggalLahir,JENISPASIEN(UMUM/BPJS/ASURANSILAIN)\n\n";
				$msg .= "Contoh : ADI SUJATMIKO,1000200030004000,23-02-1987,UMUM";
			}
		}
	}else{
		if($statusChat==1 && !in_array($waText,[1,2])){
			$msg = "1. Daftar Pasien Baru\n";
			$msg .= "2. Daftar Pasien Lama\n\n";
			$msg .= "Ketik angka 1 atau 2 untuk melakukan pendaftaran.";
		}
	}
	if(is_numeric($waText) && $statusChat==1 && $waText=='2'){
		if(($rows['pasien_baru']=="" || $rows['pasien_baru']==0) && $rows['pasien_id']==""){
			$msg = "Silahkan masukkan Nomor Rekam Medis anda\n\n";
			$msg .= "Contoh : W2013881882";
			$dataIn = [
				'status' => 20,
				'trueFalse' => false
			];
			updateStatusChat('baruLama',$idBots,$wablas,$dataIn);
			mysqli_close($wablas);
		}
	}else{
		if($statusChat==1 && !in_array($waText,[1,2])){
			$msg = "1. Daftar Pasien Baru\n";
			$msg .= "2. Daftar Pasien Lama\n\n";
			$msg .= "Ketik angka 1 atau 2 untuk melakukan pendaftaran.";
		}
	}

	$um = stripos($waText,'umum')!==false;
	$bp = stripos($waText,'bpjs')!==false;
	$al = stripos($waText,'asuransilain')!==false;
	// inputDataPasienBaru
	if(($um || $bp || $al) && $statusChat==2 && $rows['pasien_baru']==true){
		$expDataPasienBaru = explode(",", $waText);
		$jenisPasien = ($um?"UMUM":($bp?"BPJS":'ASURANSILAIN'));
		if(isset($expN[1])){
			$msg = "*Silahkan masukkan data pasien sesuai dengan format!*\n";
			$msg .= "NAMA,NIK,TanggalLahir,JENISPASIEN(UMUM/BPJS/ASURANSILAIN)\n";
			$msg .= "Contoh : ADI SUJATMIKO,1000200030004000,23-02-1987,UMUM";
		}else{
			if(isset($expDataPasienBaru[1])){
				$expNik = join("",explode(" ",$expDataPasienBaru[1]));
			}else{
				$expNik = "";
			}
			if(strlen($expNik)==16){
				$expLahir = join("",explode(" ",$expDataPasienBaru[2]));
				if(preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])-[0-9]{4}$/",$expLahir)){
					$expNama = join("",explode(" ",$expDataPasienBaru[0]));
					if(!empty($expDataPasienBaru[0]) && strlen($expNama)>=3){
						$nama = $expDataPasienBaru[0];
						$nama = ($nama!=="")?str_replace("'","''",$nama):"";
						// $nik = $expDataPasienBaru[1];
						$nik = $expNik;
						$tglLahir = date("Y-m-d",strtotime($expDataPasienBaru[2]));
						$caraBayar = $jenisPasien;
						$lahir = date_create($tglLahir);
						$dateCur = date_create('now');
						$resUmur = $lahir->diff($dateCur);
						$isGeriatri = "";
						if($resUmur->y >= 60){
							$isGeriatri = "Y";
						}else{
							$isGeriatri = "N";
						}
						if($jenisPasien=="BPJS"){
							$dataPasien = "
								INSERT INTO bot_data_pasien (idBots,nik,nama,tglLahir,caraBayar,isGeriatri,masukMaster,is_pasien_baru,jenis_kunjungan)
								VALUES('$idBots','$nik','$nama','$tglLahir','$caraBayar','$isGeriatri','belum','1','1')
							";
							$msg = "Masukkan Nomor BPJS.\n";
							$msg .= "Contoh : 0001234567890";
						}else{
							$dataPasien = "
								INSERT INTO bot_data_pasien (idBots,nik,nama,tglLahir,caraBayar,isGeriatri,masukMaster,is_pasien_baru,jenis_kunjungan)
								VALUES('$idBots','$nik','$nama','$tglLahir','$caraBayar','$isGeriatri','belum','1','2')
							";
							$msg = msgTglBerobat($dateNowPlus1);
						}
						$resPasien = mysqli_query($wablas,$dataPasien);
						$dataIn = [
							'dataDiri' => true,
							'jenisPasien' => $jenisPasien
						];
						if($jenisPasien=="BPJS"){
							$dataIn['status'] = 13;
						}else{
							$dataIn['status'] = 3;
						}
						updateStatusChat('pasienBaruPembayaran',$idBots,$wablas,$dataIn);
						mysqli_close($wablas);
					}else{
						$msg = "Silahkan inputkan Nama dengan benar";
					}
				}else{
					$msg = "*DATA TANGGAL LAHIR TIDAK SESUAI!*\n";
					$msg .= "Pastikan data sudah sesuai dengan Format dibawah ini!\n";
					$msg .= "NAMA,NIK,TanggalLahir,JENISPASIEN(UMUM/BPJS/ASURANSILAIN)\n";
					$msg .= "*Contoh :*\n";
					$msg .= "ADI SUJATMIKO,1000200030004000,23-02-1987,BPJS";
				}
			}else{
				$msg = "*DATA NIK TIDAK SESUAI!*\n";
				$msg .= "Pastikan data sudah sesuai dengan Format dibawah ini!\n";
				$msg .= "NAMA,NIK,TanggalLahir,JENISPASIEN(UMUM/BPJS/ASURANSILAIN)\n";
				$msg .= "*Contoh :*\n";
				$msg .= "ADI SUJATMIKO,1000200030004000,23-02-1987,UMUM";
			}
		}
	}else{
		if($statusChat==2 && $rows['pasien_baru']==true && $reset){
			$msg = "*DATA JENISPASIEN TIDAK SESUAI!*\n";
			$msg .= "Pastikan data sudah sesuai dengan Format dibawah ini!\n";
			$msg .= "NAMA,NIK,TanggalLahir,JENISPASIEN(UMUM/BPJS/ASURANSILAIN)\n";
			$msg .= "*Contoh :*\n";
			$msg .= "ADI SUJATMIKO,1000200030004000,23-02-1987,UMUM";
		}
	}

	### Input nomor kartu
	$noBpjs = join("",explode(" ",$waText));
	$ifBpjs = ($statusChat==13 || $statusChat==33);
	if(is_numeric($noBpjs) && $ifBpjs){
		if($rowsDapas['caraBayar']=='BPJS'){
			if(strlen($noBpjs)==13){
				$cekStart = cekPeserta($noBpjs,'bpjs');
				$cekRespon = "";
				if($cekStart->code==200){
					$tglLahir = $cekStart->data->peserta->tglLahir;
					updateTglLahir($tglLahir,$idPsn,$wablas);
					$cekRespon = 200;
				}else{
					$nik = $rowsDapas['nik'];
					$cekStart = cekPeserta($nik,'nik');
					if($cekStart->code==200){
						$noBpjs = $cekStart->data->peserta->noKartu;

						$tglLahir = $cekStart->data->peserta->tglLahir;
						updateTglLahir($tglLahir,$idPsn,$wablas);
						$cekRespon = 200;
					}else{
						$msg = "Nomor BPJS tidak ditemukan.";
					}
				}

				if($cekRespon==200){ // jika cek noka berhasil, langsung cek rujukan
					// if($phone=='6281335537942'){
						$resRujuk = rujukanMultiRecord($noBpjs,'bpjs');
					// 	// die(json_encode($resRujuk,JSON_PRETTY_PRINT));
					// }else{
					// 	$resRujuk = cekRujukan($noBpjs,'bpjs');
					// }
					$stBaru = "";
					$stLama = "";
					if($resOK = $resRujuk->code==200){
						$noRef = strtoupper($resRujuk->data['noRujuk']);
						updateNoRef($noRef,$idPsn,$wablas);

						$tingkatRujuk = $resRujuk->data['tingkatRujuk'];
						$dataIn['jenisRujukan'] = ($tingkatRujuk=='Kontrol')?'kontrol':'faskes';
						$dataIn['rujukanAktif'] = ($tingkatRujuk=='Kontrol')?NULL:$resRujuk->data['tglKunjunganPlus'];
						upBotDataPasien('rujukan',$idBots,$wablas,$dataIn); // update jenisRujukan bot_data_pasien

						$msg = msgTglBerobat($dateNowPlus1);
						$stBaru = 15;
						$stLama = 35;
					}else{
						// $msg = "Nomor Rujukan / Nomor Surat Kontrol tidak ditemukan pada BPJS yang Anda gunakan.\n";
						// $msg = "Silahkan masukkan Nomor Rujukan/Nomor Surat Kontrol.\n";
						// $msg .= "Contoh : 0301R0010120K000003";
						$msg = "Masukkan nomor rujukan FKTP(puskesmas,klinik) apabila anda kunjungan pertama.\n";
						$msg .= "Masukkan nomor SKDP rawat jalan(dicetak oleh counter RS) apabila anda kunjungan kedua dstnya.\n";
						$msg .= "Masukkan nomor SKDP rawat inap(dicetak oleh ruang ranap) apabila anda kunjungan pertama setelah dirawat inap.";
						$stBaru = 14;
						$stLama = 34;
					}
					$dataIn['status'] = (($statusChat==13)?$stBaru:$stLama);
					updateStatusChat('statusChat',$idBots,$wablas,$dataIn);
					$upNoBpjs = "UPDATE bot_data_pasien SET nomor_kartu='$noBpjs' WHERE cust_id='$idPsn'";
					mysqli_query($wablas,$upNoBpjs);
					mysqli_close($wablas);
				}
			}else{
				$msg = "*Nomor BPJS Tidak Sesuai (13 Digit).*";
			}
		}
	}else{
		if($ifBpjs && $reset){
			$msg = "*Nomor BPJS hanya berupa angka.*\n";
			$msg .= "Contoh : 0001234567890";
		}
	}

	### Tutup kode poli supaya tidak ditampilkan format => ('kode',',kode')
	// $notInSementara = ",'PSY','URO'";
	$notInSementara = ",'PSY'";
	// $notInSementara = "";

	### Input tanggal berkunjung
	$ifTglBerobat = ($statusChat==3 || $statusChat==15 || $statusChat==23 || $statusChat==35);
	if((preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])-[0-9]{4}$/",$waText)) && $ifTglBerobat){
		$datePlus3 = date("Y-m-d",strtotime($dateNow."+3 day"));
		$datePlus1 = date("Y-m-d",strtotime($dateNow."+1 day"));
		$tglBerobat = date("Y-m-d",strtotime($waText));
		if($tglBerobat>=$datePlus1 && $tglBerobat<=$datePlus3){
			$cekDay = date("D",strtotime($tglBerobat));
			if($cekDay=="Sun"){
				$msg = "*Tanggal Berobat Tidak Bisa Pada Hari Minggu*\n";
				$msg .= "Silahkan Pilih Pada Tanggal Lain.";
			}else{
				$jenisPembayaran = $rowsDapas['caraBayar'];
				// if($jenisPembayaran=='BPJS' && $rowsDapas['jenisRujukan']!='kontrol' && $rowsDapas['rujukanAktif'] < $tglBerobat){
				if($jenisPembayaran=='BPJS' && !in_array($rowsDapas['jenisRujukan'], ['kontrol','internal']) && $rowsDapas['rujukanAktif'] < $tglBerobat){
					$msg = "Nomor Rujukan anda tidak aktif mulai tanggal *".date("d-m-Y",strtotime($rowsDapas['rujukanAktif']))."*";
					// echo json_encode($resRujukan1,JSON_PRETTY_PRINT);die();
				}else{
					$nik = $rowsDapas['nik'];
					$execQGetAntri = cekAntrian($tglBerobat,$nik,$wablas);
					if($execQGetAntri->num_rows>0){
						$msg = "Anda sudah pernah mengambil pendaftaran pada hari tersebut.";
					}else{

						// if($phone=='6281335537942'){
						// 	$request->merge([
						// 		'tanggal_berobat' => $tglBerobat,
						// 	]);
						// 	$getIgnorePoli = ignorePoli($request);
						// 	$notIn = "$getIgnorePoli";
						// 	// $poli = "SELECT tp.NamaPoli,mp.kdpoli_rs,mp.kdpoli FROM mapping_poli_bridging AS mp JOIN tm_poli AS tp ON mp.kdpoli_rs=tp.KodePoli WHERE mp.kdpoli NOT IN ('ALG','UGD','ANU') GROUP BY mp.kdpoli_rs ORDER BY tp.KodePoli ASC";
						// 	$poli = "SELECT tp.NamaPoli,mp.kdpoli_rs,mp.kdpoli FROM mapping_poli_bridging AS mp JOIN tm_poli AS tp ON mp.kdpoli_rs=tp.KodePoli WHERE mp.kdpoli NOT IN ($notIn) GROUP BY mp.kdpoli_rs ORDER BY tp.KodePoli ASC"; # GIG=="poli gigi dokter umum"
						// 	$resPoli = mysqli_query($dbrsud,$poli);
						// 	$msg = getPoli($resPoli);
						//    echo $msg;
						// 	// echo date('D-m-Y',strtotime($tglBerobat));
						// 	// $notIn = "'ALG','UGD','ANU','GIG'$notInSementara";
						// 	// $poli = "SELECT tp.NamaPoli,mp.kdpoli_rs,mp.kdpoli FROM mapping_poli_bridging AS mp JOIN tm_poli AS tp ON mp.kdpoli_rs=tp.KodePoli WHERE mp.kdpoli NOT IN ($notIn) GROUP BY mp.kdpoli_rs ORDER BY tp.KodePoli ASC"; # GIG=="poli gigi dokter umum"
						// 	// $resPoli = mysqli_query($dbrsud,$poli);
						// 	// echo json_encode($resPoli->fetch_all(MYSQLI_ASSOC),JSON_PRETTY_PRINT);
						// 	die();
						// }

						$dtPas = "UPDATE bot_data_pasien SET tglBerobat='$tglBerobat' WHERE cust_id='$idPsn'";
						mysqli_query($wablas,$dtPas);
						$upTglPeriksa = "UPDATE bot_pasien SET tgl_periksa='$tglBerobat' WHERE id='$idBots'";
						mysqli_query($wablas,$upTglPeriksa);
						$cekBatpas = "SELECT * FROM bot_data_pasien WHERE cust_id='$idPsn' AND tglBerobat='$tglBerobat' AND masukMaster='belum'";
						$resBatpas = mysqli_query($wablas,$cekBatpas);
						if($resBatpas->num_rows>0){
							if($statusChat==23){
								$dataIn['status'] = 24;
							}else{
								if($statusChat==35){
									$dataIn['status'] = 36;
								}else{
									if($rowsDapas['caraBayar']=='BPJS'){
										$dataIn['status'] = 16;
									}else{
										$dataIn['status'] = 4;
									}
								}
							}
							updateStatusChat('statusChat',$idBots,$wablas,$dataIn);

							$request->merge(['tanggal_berobat' => $tglBerobat]);
							$getIgnorePoli = kuotaPoliIgnore($request);

							$notIn = "$getIgnorePoli";
							// $poli = "SELECT tp.NamaPoli,mp.kdpoli_rs,mp.kdpoli FROM mapping_poli_bridging AS mp JOIN tm_poli AS tp ON mp.kdpoli_rs=tp.KodePoli WHERE mp.kdpoli NOT IN ('ALG','UGD','ANU') GROUP BY mp.kdpoli_rs ORDER BY tp.KodePoli ASC";
							$poli = "SELECT tp.NamaPoli,mp.kdpoli_rs,mp.kdpoli FROM mapping_poli_bridging AS mp JOIN tm_poli AS tp ON mp.kdpoli_rs=tp.KodePoli WHERE mp.kdpoli NOT IN ($notIn) GROUP BY mp.kdpoli_rs ORDER BY tp.KodePoli ASC"; # GIG=="poli gigi dokter umum"
							$resPoli = mysqli_query($dbrsud,$poli);
							$msg = getPoli($resPoli);
							mysqli_close($wablas);
						}else{
							$msg = "Data gagal di inputkan, silahkan hubungi admin!";
						}
					}
				}
			}
		}else{
			if($tglBerobat==$dateNow){
				$msg = inputData('berobat','salah');
			}elseif($tglBerobat<$dateNow){
				$msg = inputData('berobat','lewat');
			}else{
				$msg = inputData('berobat','gagal');
			}
		}
	}else{
		if($ifTglBerobat && $reset){
			$msg = inputData('berobat','gagal');
		}
	}


	### Input nomor RM(pasien lama)
	// if(strlen($waText)==11 && $rows['pasien_baru']==false && $statusChat==20){
	if(strlen($waText)==11 && $statusChat==20 && $rows['pasien_baru']==false){
		$noRM = strtoupper($waText);
		$queryCekPasLama = "SELECT * FROM tm_customer WHERE KodeCust='$noRM'";
		$execCekPasLama = mysqli_query($dbrsud,$queryCekPasLama);
		if($execCekPasLama->num_rows>0){
			$resCekPasLama = $execCekPasLama->fetch_assoc();
			$noRM = $resCekPasLama['KodeCust'];
			$nik = $resCekPasLama['NoKtp'];
			// $nama = ($resCekPasLama['NamaCust']!=="")?$resCekPasLama['NamaCust']:"";
			$nama = ($resCekPasLama['NamaCust']!=="")?str_replace("'","''",$resCekPasLama['NamaCust']):"";
			$tglLahir = $resCekPasLama['TglLahir'];
			$bpjs = !empty($resCekPasLama['FieldCust1'])?$resCekPasLama['FieldCust1']:"";
			$isGeriatri = 'N';
			if(!empty($tglLahir)){
				$tglLahir = date("Y-m-d",strtotime($tglLahir));
				$lahir = date_create($tglLahir);
				$dateCur = date_create('now');
				$resUmur = $lahir->diff($dateCur);
				if($resUmur->y >= 60){
					$isGeriatri = "Y";
				}else{
					$isGeriatri = "N";
				}
			}else{
				$tglLahir = null;
			}
			// $inDapas = "";
			$msg = "Selamat datang kembali\nSdr/i *".$resCekPasLama['NamaCust'].".*\n";
			$msg .= "Alamat : ".(($resCekPasLama['Alamat']!=="")?$resCekPasLama['Alamat']:'-')."\n";
			if($nik=="" || empty($nik)){
				$msg .= "Silahkan masukkan NIK Pasien.\n";
				$msg .= "*NB : Khusus untuk pasien bayi baru lahir mohon memasukkan No KK.*\n";
				$msg .= "Contoh : 351501xxxxxxxxxx";
				$dataIn = ['status' => 21];
				if($bpjs==""){
					$inDaPas = "
						INSERT INTO bot_data_pasien (idBots,KodeCust,nama,tglLahir,isGeriatri,masukMaster,is_pasien_baru)
						VALUES('$idBots','$noRM','$nama','$tglLahir','$isGeriatri','belum','0')
					";
				}else{
					$inDaPas = "
						INSERT INTO bot_data_pasien (idBots,KodeCust,nomor_kartu,nama,tglLahir,isGeriatri,masukMaster,is_pasien_baru)
						VALUES('$idBots','$noRM','$bpjs','$nama','$tglLahir','$isGeriatri','belum','0')
					";
				}
			}else{
				$msg .= "Silahkan isi Jenis Pasien : *UMUM/BPJS/ASURANSILAIN*\n";
				$msg .= "*Contoh* : UMUM";
				$dataIn = ['status' => 22];
				if($bpjs==""){
					$inDaPas = "
						INSERT INTO bot_data_pasien (idBots,KodeCust,nik,nama,tglLahir,isGeriatri,masukMaster,is_pasien_baru)
						VALUES('$idBots','$noRM','$nik','$nama','$tglLahir','$isGeriatri','belum','0')
					";
				}else{
					$inDaPas = "
						INSERT INTO bot_data_pasien (idBots,KodeCust,nomor_kartu,nik,nama,tglLahir,isGeriatri,masukMaster,is_pasien_baru)
						VALUES('$idBots','$noRM','$bpjs','$nik','$nama','$tglLahir','$isGeriatri','belum','0')
					";
				}
			}
			$resPasien = mysqli_query($wablas,$inDaPas);
			$dataIn['trueFalse'] = false;
			updateStatusChat('statusChat',$idBots,$wablas,$dataIn);
			$upDataDiri = "UPDATE bot_pasien SET data_diri=true WHERE id='$idBots'";
			$exceupDataDiri = mysqli_query($wablas,$upDataDiri);
			mysqli_close($wablas);
		}else{
			$msg = "*Nomor RM tidak ditemukan.*\n";
			$msg .= "Silahkan masukkan kembali nomor RM yang valid.";
		}
	}else{
		if($statusChat==20 && $reset){
			$msg = "Nomor RM Harus 11 Digit.\n";
			$msg .= "*Contoh* : W2013881882";
		}
	}

	### Input nik pasien lama(jika tm_customer "NoKtp" kosong)
	$cekNik = join("",explode(" ",$waText));
	if(strlen($cekNik)==16 && $rows['pasien_baru']==false && $statusChat==21){
		if(is_numeric($cekNik)){
			$msg = "Silahkan isi Jenis Pasien : *UMUM/BPJS/ASURANSILAIN*\n";
			$msg .= "*Contoh* : UMUM";

			$dataIn['nik'] = $cekNik;
			upBotDataPasien('nik',$idBots,$wablas,$dataIn);
			$dataIn['noRM'] = $rowsDapas['KodeCust'];
			updateCustomer('nik',$dataIn,$dbrsud);
			$dataIn['status'] = 22;
			updateStatusChat('statusChat',$idBots,$wablas,$dataIn);
			mysqli_close($wablas);
		}else{
			$msg = "*NIK tidak sesuai.*\n";
			$msg .= "NIK harus berupa angka.";
		}
	}else{
		if($statusChat==21 &&$reset){
			$msg = "*NIK tidak sesuai.*\n";
			$msg .= "NIK harus 16 digit";
		}
	}

	$um = $waText=='umum';
	$bp = $waText=='bpjs';
	$al =  $waText=='asuransilain';
	### Input jenis pembayaran(pasien lama)
	if(($um || $bp || $al) && $rows['pasien_baru']==false && $statusChat==22){
		$jenisPasien = ($um?"UMUM":($bp?"BPJS":'ASURANSILAIN'));

		$dataIn['caraBayar'] = $jenisPasien;
		if($bp){
			$dataIn['status'] = 33;
			if(!empty($rowsDapas['nomor_kartu'])){
				$cekStart = cekPeserta($rowsDapas['nomor_kartu'],'bpjs');
				if($cekStart->code==200){
					// if($phone=='6281335537942'){
						$resRujuk = rujukanMultiRecord($rowsDapas['nomor_kartu'],'bpjs');
					// 	// die(json_encode($resRujuk,JSON_PRETTY_PRINT));
					// }else{
					// 	$resRujuk = cekRujukan($rowsDapas['nomor_kartu'],'bpjs');
					// }
					$setST = "";
					if($resOK = $resRujuk->code==200){
						$noRef = strtoupper($resRujuk->data['noRujuk']);
						updateNoRef($noRef,$idPsn,$wablas);
						$tglLahir = $resRujuk->data['tglLahir'];
						updateTglLahir($tglLahir,$idPsn,$wablas);

						$dataIn['noRM'] = $rowsDapas['KodeCust'];
						$dataIn['tglLahir'] = $tglLahir;
						updateCustomer('tglLahir',$dataIn,$dbrsud); // update tanggal lahir tm_customer

						$tingkatRujuk = $resRujuk->data['tingkatRujuk'];
						$dataIn['jenisRujukan'] = ($tingkatRujuk=='Kontrol')?'kontrol':'faskes';
						$dataIn['rujukanAktif'] = ($tingkatRujuk=='Kontrol')?NULL:$resRujuk->data['tglKunjunganPlus'];
						upBotDataPasien('rujukan',$idBots,$wablas,$dataIn); // update jenisRujukan bot_data_pasien
						
						$msg = msgTglBerobat($dateNowPlus1);
						$setST = 35;
					}else{
						// $msg = "Nomor Rujukan / Nomor Referensi tidak ditemukan pada BPJS yang Anda gunakan.\n";
						// $msg = "Silahkan masukkan Nomor Rujukan / Nomor Referensi Anda.\n";
						// $msg .= "Contoh : 0001R0040116A000001";
						// $msg = "Silahkan masukkan Nomor Rujukan/Nomor Surat Kontrol.\n";
						// $msg .= "Contoh : 0301R0010120K000003";
						$msg = "Masukkan nomor rujukan FKTP(puskesmas,klinik) apabila anda kunjungan pertama.\n";
						$msg .= "Masukkan nomor SKDP rawat jalan(dicetak oleh counter RS) apabila anda kunjungan kedua dstnya.\n";
						$msg .= "Masukkan nomor SKDP rawat inap(dicetak oleh ruang ranap) apabila anda kunjungan pertama setelah dirawat inap.";
						$setST = 34;
					}

					$dataIn['status'] = $setST;
				}else{
					$msg = "Masukkan Nomor BPJS.\n";
					$msg .= "Contoh : 0001234567890";
				}
			}else{
				$cekStart = cekPeserta($rowsDapas['nik'],'nik');
				if($cekStart->code==200){
					$noka = $cekStart->data->peserta->noKartu;
					$dataIn['noRM'] = $rowsDapas['KodeCust'];
					$dataIn['bpjs'] = $noka;
					updateCustomer('bpjs',$dataIn,$dbrsud); // update nomorBPJS tm_customer

					if($noka!=""){
						$dataIn['noka'] = $noka;
						upBotDataPasien('noka',$idBots,$wablas,$dataIn); // update nomorBPJS bot_data_pasien
					}

					// if($phone=='6281335537942'){
						$resRujuk = rujukanMultiRecord($noka,'bpjs');
					// 	// die(json_encode($resRujuk,JSON_PRETTY_PRINT));
					// }else{
					// 	$resRujuk = cekRujukan($noka,'bpjs');
					// }
					if($resRujuk->code==200){
						$tglLahir = $resRujuk->data['tglLahir'];
						updateTglLahir($tglLahir,$idPsn,$wablas); // update tanggal lahir
						
						$dataIn['noRM'] = $rowsDapas['KodeCust'];
						$dataIn['tglLahir'] = $tglLahir;
						updateCustomer('tglLahir',$dataIn,$dbrsud); // update tanggal lahir tm_customer

						$noRef = strtoupper($resRujuk->data['noRujuk']);
						updateNoRef($noRef,$idPsn,$wablas); // update nomor referensi bot_data_pasien

						$tingkatRujuk = $resRujuk->data['tingkatRujuk'];
						$dataIn['jenisRujukan'] = ($tingkatRujuk=='Kontrol')?'kontrol':'faskes';
						$dataIn['rujukanAktif'] = ($tingkatRujuk=='Kontrol')?NULL:$resRujuk->data['tglKunjunganPlus'];
						upBotDataPasien('rujukan',$idBots,$wablas,$dataIn); // update jenisRujukan bot_data_pasien

						$msg = msgTglBerobat($dateNowPlus1);
						$setST = 35;
					}else{
						// $msg = "Nomor Rujukan / Nomor Referensi tidak ditemukan pada BPJS yang Anda gunakan.\n";
						$msg = "Silahkan masukkan Nomor Rujukan / Nomor Referensi Anda.\n";
						$msg .= "Contoh : 0001R0040116A000001";
						$setST = 34;
					}
					$dataIn['status'] = $setST;
				}else{
					$msg = "Masukkan Nomor BPJS.\n";
					$msg .= "Contoh : 0001234567890";
				}
			}
		}else{
			$tglLahir = $rowsDapas['tglLahir'];
			if($tglLahir==""||$tglLahir==null||$tglLahir=='0000-00-00 00:00:00'){
				$tglLahir = date('Y-m-d',strtotime("-37 year",strtotime("today"))); // set tanggal lahir -37 tahun dari sekarang
				updateTglLahir($tglLahir,$idPsn,$wablas);
			}
			$msg = msgTglBerobat($dateNowPlus1);
			$dataIn['status'] = 23;
		}
		$dataIn['jenisKunjungan'] = $bp?'1':'2';
		upBotDataPasien('caraBayar',$idBots,$wablas,$dataIn);
		updateStatusChat('statusChat',$idBots,$wablas,$dataIn);
		mysqli_close($wablas);
	}else{
		if($statusChat==22 && $reset){
			$msg = "*Jenis Pasien tidak sesuai*\n";
			$msg .= "Silahkan isi Jenis Pasien : *UMUM/BPJS/ASURANSILAIN*\n";
			$msg .= "*Contoh* : UMUM";
		}
	}

	### Input nomor referensi/rujukan (pasien bpjs)
	$ifNoRef = ($statusChat==14 || $statusChat==34);
	if(strlen($waText)==19 && $ifNoRef && $rowsDapas['caraBayar']=='BPJS'){
		$getNoBpjs = "SELECT * FROM bot_data_pasien WHERE cust_id='$idPsn'";
		$execGetNoBpjs = mysqli_query($wablas,$getNoBpjs);
		$resGetNoBpjs = $execGetNoBpjs->fetch_assoc();

		$nomor = strtoupper($waText);
		$prefix = substr($nomor,12,1);
		$statusRujuk = "";
		if($prefix!='N'){ # Prefix "N"=>rujukan internal
			// if($phone=='6281335537942'){
				$resRujukan1 = rujukanMultiRecord($nomor,'rujukan');
			// 	// die(json_encode($resRujukan1,JSON_PRETTY_PRINT));
			// }else{
			// 	$resRujukan1 = cekRujukan($nomor,'rujukan');
			// }
			if($resOK = $resRujukan1->code==200){
				// if($resRujukan1->data['nik']==$resGetNoBpjs['nik']){ // validasi jika nik(tm_cust) sesuai dengan nik(dari rujukan)
					$tglLahir = $resRujukan1->data['tglLahir'];
					updateTglLahir($tglLahir,$idPsn,$wablas); // update tanggal lahir
					
					$dataIn['noRM'] = $rowsDapas['KodeCust'];
					$dataIn['tglLahir'] = $tglLahir;
					updateCustomer('tglLahir',$dataIn,$dbrsud); // update tanggal lahir tm_customer

					$noRef = strtoupper($nomor);
					updateNoRef($noRef,$idPsn,$wablas); // update noReferensi

					$nik = $resRujukan1->data['nik'];
					if($nik!=""){
						$dataIn['nik'] = $nik;
						upBotDataPasien('nik',$idBots,$wablas,$dataIn); // update nik

						$dataIn['nik'] = $nik;
						updateCustomer('nik',$dataIn,$dbrsud); // update nik tm_customer
					}

					$noka = $resRujukan1->data['noBpjs'];
					if($noka!=""){
						$dataIn['noka'] = $noka;
						upBotDataPasien('noka',$idBots,$wablas,$dataIn); // update nomorBPJS bot_data_pasien

						$dataIn['bpjs'] = $noka;
						updateCustomer('bpjs',$dataIn,$dbrsud); // update nomorBPJS tm_customer
					}

					$tingkatRujuk = $resRujukan1->data['tingkatRujuk'];
					$dataIn['jenisRujukan'] = ($tingkatRujuk=='Kontrol')?'kontrol':'faskes';
					$dataIn['rujukanAktif'] = ($tingkatRujuk=='Kontrol')?NULL:$resRujukan1->data['tglKunjunganPlus'];
					upBotDataPasien('rujukan',$idBots,$wablas,$dataIn); // update jenisRujukan bot_data_pasien

					$statusRujuk = 200;

					$msg = msgTglBerobat($dateNowPlus1);
				// }else{
				// 	$msg = "Nomor Rujukan / Nomor Referensi tidak sesuai";
				// }
			}else{
				$nomor = $resGetNoBpjs['nomor_kartu'];
				// if($phone=='6281335537942'){
					$resRujukan2 = rujukanMultiRecord($nomor,'bpjs');
				// 	// die(json_encode($resRujuk,JSON_PRETTY_PRINT));
				// }else{
				// 	$resRujukan2 = cekRujukan($nomor,'bpjs');
				// }
				if($resOK = $resRujukan2->code==200){
					$tglLahir = $resRujukan2->data['tglLahir'];
					updateTglLahir($tglLahir,$idPsn,$wablas); // update tanggal lahir
					
					$dataIn['noRM'] = $rowsDapas['KodeCust'];
					$dataIn['tglLahir'] = $tglLahir;
					updateCustomer('tglLahir',$dataIn,$dbrsud); // update tanggal lahir tm_customer

					$noRef = strtoupper($resRujukan2->data['noRujuk']);
					updateNoRef($noRef,$idPsn,$wablas); // update noReferensi

					$nik = $resRujukan2->data['nik'];
					if($nik!=""){
						$dataIn['nik'] = $nik;
						upBotDataPasien('nik',$idBots,$wablas,$dataIn); // update nik
					}

					$tingkatRujuk = $resRujukan2->data['tingkatRujuk'];
					$dataIn['jenisRujukan'] = ($tingkatRujuk=='Kontrol')?'kontrol':'faskes';
					$dataIn['rujukanAktif'] = ($tingkatRujuk=='Kontrol')?NULL:$resRujukan2->data['tglKunjunganPlus'];
					upBotDataPasien('rujukan',$idBots,$wablas,$dataIn); // update jenisRujukan bot_data_pasien

					$statusRujuk = 200;

					$msg = msgTglBerobat($dateNowPlus1);
				}else{
					$msg = "Nomor Rujukan / Nomor Referensi tidak ditemukan.";
				}
			}
		}else{
			$noRef = strtoupper($nomor);
			updateNoRef($noRef,$idPsn,$wablas); // update noReferensi

			$dataIn['jenisRujukan'] = 'internal';
			$dataIn['rujukanAktif'] = NULL;
			upBotDataPasien('rujukan',$idBots,$wablas,$dataIn); // update jenisRujukan bot_data_pasien

			$statusRujuk = 200;
			$msg = msgTglBerobat($dateNowPlus1);
		}
		if($statusRujuk == 200){
			$dataIn['status'] = ($statusChat==14)?15:35;
			updateStatusChat('statusChat',$idBots,$wablas,$dataIn); // update statusChat
		}
		mysqli_close($wablas);
	}else{
		if($ifNoRef && $rowsDapas['caraBayar']=='BPJS' && $reset){
			$msg = "Nomor Rujukan / Nomor Referensi tidak sesuai.";
		}
	}

	### Input nomor poli
	$ifPoli = ($statusChat==4 || $statusChat==16 || $statusChat==24 || $statusChat==36);
	if(is_numeric($waText) && ($ifPoli)){
		// if($waText==4){
		// 	// echo "Untuk sementara waktu\npendaftaran *POLI GENERAL CHECK UP*\nHanya bisa dilakukan secara onsite.";
		// 	echo "*Untuk sementara waktu pendaftaran POLI GENERAL CHECK UP*\n*Hanya bisa dilakukan secara onsite.*";
		// 	die();
		// }

		// if($waText==2 && !in_array(date('D',strtotime($rows['tgl_periksa'])), ['Tue','Thu'])){
		// 	echo msgJadwalPoli();die();
		// }
		// if(cekLibur(date('d-m-Y',strtotime($rows['tgl_periksa']))) && $waText!=19){ # Pesan untuk hari libur RS
		// 	echo msgLibur();die();
		// }

		$request->merge(['tanggal_berobat' => $rows['tgl_periksa']]);
		$getIgnorePoli = kuotaPoliIgnore($request);

		$notIn = "mp.kdpoli NOT IN ($getIgnorePoli)";
		$poli = "
			SELECT
				tp.NamaPoli,mp.kdpoli_rs,mp.kdpoli
			FROM
				mapping_poli_bridging AS mp JOIN tm_poli AS tp
				ON mp.kdpoli_rs=tp.KodePoli
			WHERE
				$notIn
			GROUP BY mp.kdpoli_rs ORDER BY tp.KodePoli ASC
		"; # GIG=="poli gigi dokter umum"
		$resPoli = mysqli_query($dbrsud,$poli);
		$arrPoli = [];
		while($row=$resPoli->fetch_assoc()){
			array_push($arrPoli,$row);
		}
		$linkPasNew = $rows['pasien_baru']==1;
		$linkPasOld = $rows['pasien_baru']==0;
		if($rowsDapas['kodePoli']==NULL && $rows['data_diri']==true && ($linkPasOld || $linkPasNew)){
			$index = $txt1-1;
			$idBot = $rows['id'];
			$strRandom = randomString(7);
			if(array_key_exists($index, $arrPoli)){
				$namaPoli = $arrPoli[$index]['NamaPoli'];
				$kodePoli = $arrPoli[$index]['kdpoli'];
				$ifKode = "";
				// if(
				// 	( # Poli jiwa hanya buka selasa & kamis
				// 		$kodePoli=='JIW'
				// 		&& !in_array(date('D',strtotime($rows['tgl_periksa'])), ['Tue','Thu'])
				// 	)
				// 	// || ( # Poli bedah onkologi, hari kamis tutup
				// 	// 	$kodePoli=='017' && date('D',strtotime($rows['tgl_periksa']))=='Thu'
				// 	// )
				// ){
				// 	echo msgJadwalPoli($kodePoli);
				// 	die();
				// }
				if(cekLibur(date('d-m-Y',strtotime($rows['tgl_periksa']))) && $waText!=19){ # Pesan untuk hari libur RS
					echo msgLibur();
					die();
				}

				// if($phone=='6281335537942'){
					// echo $waText;die();
					// $query = "SELECT count(cust_id) as total FROM bot_pasien as bp
					// 	JOIN bot_data_pasien as bdp ON bp.id = bdp.idBots
					// 	WHERE bp.tgl_periksa = '2024-06-27'
					// 	AND bp.statusChat='99'
					// 	AND bdp.kodePoli='017'
					// ";
					// $res = mysqli_query($wablas,$query);
					// $total = mysqli_fetch_assoc($res)['total'];
					// if($kodePoli=='017' && date('d-m-Y',strtotime($rows['tgl_periksa']))=='27-06-2024' && $total>=25 ){
					// 	echo msgJadwalPolis($wablas);
					// 	die();
					// }
				// }

				// if($phone=='6281335537942'){
				// 	echo date('D',strtotime($rows['tgl_periksa']));
				// 	die();
				// }
				if($kodePoli=='040'){
					$upKode = $kodePoli;
					$ifKode = $kodePoli;
					$kodePoli = 'ANA';
				}else if($kodePoli=='017'){
					$upKode = $kodePoli;
					$ifKode = $kodePoli;
					$kodePoli = 'BED';
				}else if($kodePoli=='HDL'){
					$upKode = $kodePoli;
					$ifKode = '16454'; // pakai kode dokter, karna kd poli & sub poli nya INT, bukan HDL
					$kodePoli = 'INT';
				}else{
					$upKode = $kodePoli;
					$ifKode = $kodePoli;
				}

				// if(in_array($phone,$listPhone)){
					$request->merge([
						'kode_poli' => $arrPoli[$index]['kdpoli']=='HDL' ? 'INT' : $arrPoli[$index]['kdpoli'],
						'kode_poli_bpjs' => $arrPoli[$index]['kdpoli'],
					]);
					waBotBridging()->convertBPJStoRS($request);
					$request->merge([
						'jenis_pembayaran' => $rowsDapas['caraBayar'],
						'tanggal_periksa' => $rowsDapas['tglBerobat'],
					]);
					$dokter = json_decode(waBotBridging()->randomDokter($request));
					$request->query->remove('apm_conn');
					$request->query->remove('rsu_conn');
					if($dokter->metadata->code!='200'){
						$msg = "Tidak ada jadwal dokter pada hari & poli yang Anda pilih.\n";
						$msg .= "Silahkan pilih Nomor Poli Tujuan yang lain atau ubah tanggal kunjungan.";
						die($msg);
					}
					$dokter = $dokter->response;

					# Update kode poli & jadwal dokter
					$sptPhone = str_split($phone,2);
					if($sptPhone[0]=='62'){
						$sptPhone[0]='0';
					}
					mysqli_begin_transaction($wablas);
					mysqli_begin_transaction($dbrsud);
					$resPhone = join("",$sptPhone);
					mysqli_query($wablas,"UPDATE bot_data_pasien
						SET
							nohp = '$resPhone',
							kodePoli = '$upKode',
							kodedokter = '$dokter->kode_dokter',
							namaDokter = '$dokter->nama_dokter',
							jam_praktek = '$dokter->jam_praktek'
						WHERE cust_id = '$idPsn'
					");
					// $getTgl = strtotime($rowsDapas['tglBerobat']);
					// $tglPeriksa = date("d-m-Y",$getTgl);

					// mysqli_begin_transaction($wablas);
					// mysqli_begin_transaction($dbrsud);
					$dataIn['kodeBooking'] = $strRandom;
					$dataIn['status'] = 99; // CHAT SELESAI(GENERATE LINK KONFIRMASI)
					updateStatusChat('kodeBooking',$idBots,$wablas,$dataIn);
					// updateStatusChat('statusChat',$idBots,$wablas,$dataIn); # Gadipake

					$counter = "Pendaftaran"; # Pasien baru menuju pendaftaran(loket admisi)
					if($rowsDapas['is_pasien_baru']!=1){ # Pasien lama menuju counter
						$storeRsuRegister = storeRsuRegister($idPsn,$wablas,$dbrsud); // insert tr_registrasi && filling
						if(!$storeRsuRegister){
							mysqli_rollback($wablas);
							mysqli_rollback($dbrsud);
							echo 'Data gagal disimpan, *silahkan ketikkan kembali nomor poli yang Anda pilih*';
							die();
						}
						mysqli_commit($dbrsud);
						if(in_array($upKode,['INT','JAN','KLT'])){ $counter = 'Counter A'; }
						if(in_array($upKode,['017','BED','BSY','OBG','ORT','URO','VCT'])){ $counter = 'Counter B'; }
						if(in_array($upKode,['ANT','GIZ','IRM','PAR','SAR'])){ $counter = 'Counter C'; }
						if(in_array($upKode,['040','ANA','JIW','MAT','PSY','THT'])){ $counter = 'Counter D'; }
						if(in_array($upKode,['BDM','GIG','GND','KON','MCU'])){ $counter = 'Counter E'; }
						if(in_array($upKode,['HDL'])){ $counter = 'Counter HD'; }
					}
					mysqli_commit($wablas);

					$resPoli = mysqli_query($dbrsud,"SELECT tp.NamaPoli,mp.kdpoli_rs,mp.kdpoli
						FROM mapping_poli_bridging AS mp JOIN tm_poli AS tp ON mp.kdpoli_rs=tp.KodePoli
						WHERE mp.kdpoli='$upKode'
						GROUP BY mp.kdpoli_rs
						ORDER BY tp.KodePoli ASC
					");
					$tpPoli = $resPoli->fetch_assoc();

					$idBots = $rows['id'];
					$msg = "Selamat Sdr/I ".$rowsDapas['nama']." *BERHASIL* mendaftar antrian.\n";
					$msg .= "Kode Booking : *".$strRandom."*\n";
					$msg .= "Tanggal Berkunjung : ".date("d-m-Y",strtotime($rowsDapas['tglBerobat']))."\n";
					$msg .= "Poli Tujuan : *".$tpPoli['NamaPoli']."*\n";
					$msg .= "Silahkan menuju *$counter*, untuk mendapatkan nomor antrian dengan cara :\n";
					$msg .= "1. Masukkan kodebooking kedalam mesin antrian. Atau,\n";
					$msg .= "2. Klik tautan ini : ".$baseURL."verifikasi/".$idBots."/".$strRandom." ketika sudah di RS\n\n";
					$msg .= "Jika terdapat kendala hubungi hotline : 0815257200088 untuk mendapatkan bantuan.";
				// 	echo $msg;
				// 	mysqli_close($wablas);
				// 	mysqli_close($dbrsud);
				// 	die();
				// }

				// // get kode poli dan tanggal berobat untuk bridg jadwal dokter START
				// $queryKodePoli = "SELECT * FROM bot_data_pasien WHERE cust_id='$idPsn'";
				// $execKodePoli = mysqli_query($wablas,$queryKodePoli);
				// $resKodePoli = $execKodePoli->fetch_assoc();
				// // get kode poli dan tanggal berobat untuk bridg jadwal dokter END
				// $paramRef = [
				// 	$kodePoli,
				// 	$resKodePoli['tglBerobat']
				// ];
				// $arrDokterGigi = [];
				// $arrKodeDokter = [];
				// // if($ifKode=='GIG'){ // code lama
				// if(in_array($ifKode,['GIG','BDM','GND','KON'])){ // 14-11-2023
				// 	// $arrKodeDokter[] = 'GIG'; # Dokter umum tidak ditampilkan, permintaan mas Reza
				// 	$arrKodeDokter[] = 'GIG'; # Dokter tidak ter-cover HFIS ditampilkan, permintaan pak Adi 04-03-2024
				// 	$paramRef[0] = 'KON';
				// 	$resJadDok = refJadDok($paramRef); // get ref jadwal dokter HFIS
				// 	if($resJadDok['metaData']->code==200){
				// 		foreach($resJadDok['response'] as $key => $val) {
				// 			$arrDokterGigi[] = $val;
				// 			if(!in_array($val->kodepoli, $arrKodeDokter)){
				// 				$arrKodeDokter[] = $val->kodepoli;
				// 			}
				// 		}
				// 	}

				// 	$paramRef[0] = 'BDM';
				// 	$resJadDok = refJadDok($paramRef); // get ref jadwal dokter HFIS
				// 	if($resJadDok['metaData']->code==200){
				// 		foreach($resJadDok['response'] as $key => $val) {
				// 			$arrDokterGigi[] = $val;
				// 			if(!in_array($val->kodepoli, $arrKodeDokter)){
				// 				$arrKodeDokter[] = $val->kodepoli;
				// 			}
				// 		}
				// 	}

				// 	$paramRef[0] = 'GND';
				// 	$resJadDok = refJadDok($paramRef); // get ref jadwal dokter HFIS
				// 	if($resJadDok['metaData']->code==200){
				// 		foreach($resJadDok['response'] as $key => $val) {
				// 			$arrDokterGigi[] = $val;
				// 			if(!in_array($val->kodepoli, $arrKodeDokter)){
				// 				$arrKodeDokter[] = $val->kodepoli;
				// 			}
				// 		}
				// 	}
				// 	$resJadDok['response'] = $arrDokterGigi;
				// }else{
				// 	if(in_array($ifKode,['PSY','GIZ','VCT','MCU'])){
				// 		$dokterInternal = dokterInternal($ifKode,$resKodePoli['tglBerobat']);
				// 		$resJadDok = [
				// 			'metaData'=> (object)[
				// 				'code'=> 200,
				// 				'message'=> 'OK'
				// 			],
				// 			'response'=> json_decode(json_encode($dokterInternal))
				// 		];
				// 	}else{
				// 		$resJadDok = refJadDok($paramRef); // get ref jadwal dokter HFIS
				// 	}
				// }

				// if($resJadDok['metaData']->code==200) {
				// 	if($resJadDok['response']==""){
				// 		$msg .= "Tidak terdapat Jadwal Dokter pada hari & poli yang anda pilih";
				// 	}else{
				// 		$getTgl = strtotime($rowsDapas['tglBerobat']);
				// 		$tglPeriksa = date("d-m-Y",$getTgl);
				// 		if($statusChat==24){
				// 			$dataIn['status'] = 25;
				// 		}else{
				// 			if($statusChat==36){
				// 				$dataIn['status'] = 37;
				// 			}else{
				// 				if($rowsDapas['caraBayar']=='BPJS'){
				// 					$dataIn['status'] = 17;
				// 				}else{
				// 					$dataIn['status'] = 5;
				// 				}
				// 			}
				// 		}
				// 		$dataIn['kodeBooking'] = $strRandom;

				// 		if($ifKode=='017' || $ifKode=='040' || $ifKode=='UMU'){
				// 			$res = array_filter($resJadDok['response'],function($var)use($ifKode){
				// 				if(stripos($var->kodesubspesialis,$ifKode)!==false){
				// 					return $var;
				// 				}
				// 			});
				// 			$resJadDok['response'] = $res;
				// 		}else if($ifKode=='16454'){
				// 			$res = array_filter($resJadDok['response'],function($var)use($ifKode){
				// 				if(stripos($var->kodedokter,$ifKode)!==false){
				// 					return $var;
				// 				}
				// 			});
				// 			$resJadDok['response'] = $res;
				// 		}
				// 		if(count($resJadDok['response'])>0){
				// 			if($ifKode=='16454'){
				// 				$queryDokter = "SELECT * FROM dokter_bridg WHERE kodedokter='$ifKode'";
				// 				$execDokter = mysqli_query($dbrsud,$queryDokter) or die(mysqli_error($dbrsud));
				// 			// }elseif($ifKode=='GIG'){
				// 			}elseif(in_array($ifKode,['GIG','BDM','GND','KON'])){
				// 				$implodeKode = implode("','",$arrKodeDokter);
				// 				// $queryDokter = "SELECT * FROM dokter_bridg WHERE polibpjs IN ('{$implodeKode}')";
				// 				$queryDokter = "SELECT * FROM dokter_bridg WHERE polibpjs IN ('GIG','BDM','GND','KON')";
				// 				$execDokter = mysqli_query($dbrsud,$queryDokter) or die(mysqli_error($dbrsud));
				// 			}else{
				// 				$queryDokter = "SELECT * FROM dokter_bridg WHERE polibpjs='$ifKode'";
				// 				$execDokter = mysqli_query($dbrsud,$queryDokter) or die(mysqli_error($dbrsud));
				// 			}
				// 			// if($phone!='6281335537942'){
				// 				updateStatusChat('kodeBooking',$idBots,$wablas,$dataIn);

				// 				$addPoliNew = "UPDATE bot_data_pasien SET kodePoli='$upKode' WHERE cust_id='$idPsn'";
				// 				mysqli_query($wablas,$addPoliNew);
				// 			// }
				// 			$arrDokter = [];
				// 			while($row=$execDokter->fetch_assoc()){
				// 				array_push($arrDokter,$row);
				// 				if(in_array($ifKode,['GIG','BDM','GND','KON'])){
				// 					if($row['polibpjs']=='GIG'){ # Kode GIG push ke $resJadDok['response']
				// 						$getDay = date('D',strtotime($tglPeriksa));
				// 						$timeDokter = ($getDay != 'Sat') ? '08.00-14.00' : '08.00-12.00';
				// 						$pushToResJadDok = (object)[
				// 							'kodesubspesialis' => $row['polibpjs'],
				// 							'hari'=> 0,
				// 							'kapasitaspasien'=> 40,
				// 							'libur'=> 0,
				// 							'namahari'=> '',
				// 							'jadwal'=> $timeDokter,
				// 							'namasubspesialis'=> $row['poli'],
				// 							'namadokter'=> $row['dokter'],
				// 							'kodepoli'=> $row['polibpjs'],
				// 							'namapoli'=> $row['poli'],
				// 							'kodedokter'=> $row['kodedokter'],
				// 						];
				// 						array_push($resJadDok['response'],$pushToResJadDok);
				// 					}
				// 				}
				// 			}
				// 			$count1 = toDokter($arrDokter,$resJadDok,'hitung','');
				// 			$msg = toDokter($arrDokter,$resJadDok,'tampil',$count1);
				// 		}else{
				// 			$msg = "Tidak ada jadwal dokter pada hari & poli yang Anda pilih.\n";
				// 			$msg .= "Silahkan pilih Nomor Poli Tujuan yang lain atau ubah tanggal kunjungan.";
				// 		}
				// 	}
				// }else{
				// 	$msg = "Tidak ada jadwal dokter pada hari & poli yang Anda pilih.\n";
				// 	$msg .= "Silahkan pilih Nomor Poli Tujuan yang lain atau ubah tanggal kunjungan.";
				// }
			}else{
				$msg = "Nomor Poli Tidak Ditemukan!\n";
				$msg .= "Silahkan Pilih Nomor Poli Dengan Benar!";
			}
		}else{
		}
		mysqli_close($wablas);
	}

	### Input kode dokter / konfirmasi
	$ifKodeDokter = ($statusChat==5 || $statusChat==17 || $statusChat==25 || $statusChat==37);
	if(is_numeric($waText) && $ifKodeDokter){
		$queryKodePoli = "SELECT * FROM bot_data_pasien WHERE cust_id='$idPsn'";
		$execKodePoli = mysqli_query($wablas,$queryKodePoli);
		$resKodePoli = $execKodePoli->fetch_assoc();
		$kodePoli = $resKodePoli['kodePoli'];

		if($kodePoli=='040'){
			$upKode = $kodePoli;
			$kodePoli = 'ANA';
			// $kodePoli = 'HDL';
		}else if($kodePoli=='017'){
			$upKode = $kodePoli;
			$kodePoli = 'BED';
			// $kodePoli = 'ANA';
		}else if($kodePoli=='HDL'){
			$upKode = $kodePoli;
			// $ifKode = '16454'; // pakai kode dokter, karna kd poli & sub poli nya INT, bukan HDL
			$kodePoli = 'INT';
		}else{
			$upKode = $kodePoli;
		}
		// $queryDokter = "SELECT * FROM dokter_bridg WHERE polibpjs='$kodePoli'";
		$queryDokter = "SELECT * FROM dokter_bridg WHERE kodedokter='$waText' LIMIT 1";
		$execDokter = mysqli_query($dbrsud,$queryDokter);
		if($execDokter->num_rows>0){
			$kodeDokter = $waText;
			if($kodeDokter==""){
				$msg = "Silahkan masukkan kode dokter dengan benar!";
			}else{
				$tglLahir = date('Y-m-d',strtotime("-37 year",strtotime("today"))); // set tanggal lahir -37 tahun dari sekarang
				$dataDokter = $execDokter->fetch_assoc();
				if(in_array($dataDokter['polibpjs'],['GIG','BDM','GND','KON'])){
					$kodePoli = $dataDokter['polibpjs'];
					$updatePoli = "UPDATE bot_data_pasien SET kodePoli='$kodePoli' WHERE cust_id='$idPsn'";
					// $updatePoli = "SELECT  bot_data_pasien WHERE cust_id='$idPsn'";
					mysqli_query($wablas,$updatePoli) or die('*Terjadi kesalahan sistem, silahkan hubungi Admin.*');
				}
				$paramRef = [
					$kodePoli,
					$resKodePoli['tglBerobat'],
					$dataDokter,
				];
				if($kodePoli=='PSY' || $kodePoli=='GIZ' || $kodePoli=='VCT' || $kodePoli=='MCU'){
					$dokterInternal = dokterInternal($kodePoli,$resKodePoli['tglBerobat']);
					$resJadDok = [
						'metaData'=> (object)[
							'code'=> 200,
							'message'=> 'OK'
						],
						'response'=> json_decode(json_encode($dokterInternal))
					];
				}else{
					$resJadDok = refJadDok($paramRef);
					// if(in_array($kodePoli,['GIG','BDM','GND','KON'])){
					// 	if($kodePoli=='GIG'){
					// 		$getDay = date('D',strtotime($resKodePoli['tglBerobat']));
					// 		$timeDokter = ($getDay != 'Sat') ? '08.00-14.00' : '08.00-12.00';
					// 		$pushToResJadDok = (object)[
					// 			'kodesubspesialis' => $paramRef[2]['polibpjs'],
					// 			'hari'=> 0,
					// 			'kapasitaspasien'=> 40,
					// 			'libur'=> 0,
					// 			'namahari'=> '',
					// 			'jadwal'=> $timeDokter,
					// 			'namasubspesialis'=> $paramRef[2]['poli'],
					// 			'namadokter'=> $paramRef[2]['dokter'],
					// 			'kodepoli'=> $paramRef[2]['polibpjs'],
					// 			'namapoli'=> $paramRef[2]['poli'],
					// 			'kodedokter'=> $paramRef[2]['kodedokter'],
					// 		];
					// 		if(gettype($resJadDok['response'])=='string'){
					// 			$resJadDok['response'] = [];
					// 		}
					// 		array_push($resJadDok['response'],$pushToResJadDok);
					// 	}
					// }
				}
				$resFilterDokter = $resJadDok['response'] === "" ? [] : filterDokter($resJadDok,$kodeDokter);

				if(!empty($resFilterDokter) || count($resFilterDokter)>0){
					$kdDokterIn = $resFilterDokter->kodedokter;
					$nmDokterIn = $resFilterDokter->namadokter;
					$jpDokterIn = $resFilterDokter->jadwal;
					$sptPhone = str_split($phone,2);
					if($sptPhone[0]=='62'){
						$sptPhone[0]='0';
					}

					mysqli_begin_transaction($wablas);
					mysqli_begin_transaction($dbrsud);

					// if($phone=='6281335537942'){
					// 	$storeRsuRegister = storeRsuRegister($idPsn,$wablas,$dbrsud); // insert tr_registrasi && filling
					// 	if(!$storeRsuRegister){
					// 		mysqli_rollback($wablas);
					// 		mysqli_rollback($dbrsud);
					// 		echo 'gagal';die();
					// 	}
					// 	mysqli_rollback($wablas);
					// 	mysqli_rollback($dbrsud);
					// 	echo "$idPsn \n $phone";
					// 	die();
					// }

					$resPhone = join("",$sptPhone);
					$dtPas = "UPDATE bot_data_pasien SET nohp='$resPhone',kodeDokter='$kdDokterIn', namaDokter='$nmDokterIn', jam_praktek='$jpDokterIn' WHERE cust_id='$idPsn'";
					$updateDokter = mysqli_query($wablas,$dtPas);

					$dataIn['status'] = 99; // CHAT SELESAI(GENERATE LINK KONFIRMASI)
					updateStatusChat('statusChat',$idBots,$wablas,$dataIn);

					$poli = "SELECT tp.NamaPoli,mp.kdpoli_rs,mp.kdpoli FROM mapping_poli_bridging AS mp JOIN tm_poli AS tp ON mp.kdpoli_rs=tp.KodePoli WHERE mp.kdpoli='$upKode' GROUP BY mp.kdpoli_rs ORDER BY tp.KodePoli ASC";
					$resPoli = mysqli_query($dbrsud,$poli);
					$tpPoli = $resPoli->fetch_assoc();


					$cek = $upKode;
					$counter = "Pendaftaran";
					if(!$updateDokter){
						mysqli_rollback($wablas);
						echo 'Data gagal disimpan, *silahkan ketikkan kembali kode dokter yang Anda pilih.*';
						die();
					}
					// if($rowsDapas['is_pasien_baru']==0){
					if($rowsDapas['is_pasien_baru']!=1){
						$storeRsuRegister = storeRsuRegister($idPsn,$wablas,$dbrsud); // insert tr_registrasi && filling
						if(!$storeRsuRegister){
							mysqli_rollback($wablas);
							mysqli_rollback($dbrsud);
							echo 'Data gagal disimpan, *silahkan ketikkan kembali kode dokter yang Anda pilih*';
							die();
						}
						mysqli_commit($dbrsud);
						if($cek=='INT' || $cek=='KLT' || $cek=='JAN'){
							$counter = "Counter A";
						}elseif($cek=='URO'||$cek=='BSY'||$cek=='ORT'||$cek=='BED'||$cek=='OBG'||$cek=='017'||$cek=='VCT'){
							$counter = "Counter B";
						}elseif($cek=='IRM'||$cek=='PAR'||$cek=='SAR'||$cek=='GIZ'||$cek=='ANT'){
							$counter = "Counter C";
						}elseif($cek=='THT'||$cek=='MAT'||$cek=='ANA'||$cek=='JIW'){
							$counter = "Counter D";
						}elseif($cek=='GIG'){
							$counter = "Counter E";
						}
					}
					mysqli_commit($wablas);

					$idBots = $rows['id'];
					$msg = "Selamat Sdr/I ".$resKodePoli['nama']." *BERHASIL* mendaftar antrian.\n";
					$msg .= "Kode Booking : *".$rows['random']."*\n";
					$msg .= "Tanggal Berkunjung : ".date("d-m-Y",strtotime($resKodePoli['tglBerobat']))."\n";
					$msg .= "Poli Tujuan : *".$tpPoli['NamaPoli']."*\n";
					$msg .= "Silahkan menuju *$counter*, untuk mendapatkan nomor antrian dengan cara :\n";
					$msg .= "1. Masukkan kodebooking kedalam mesin antrian. Atau,\n";
					$msg .= "2. Klik tautan ini : ".$baseURL."verifikasi/".$idBots."/".$rows['random']." ketika sudah di RS\n\n";
					$msg .= "Jika terdapat kendala hubungi hotline : 0815257200088 untuk mendapatkan bantuan.";
				}else{
					$msg = "Kode Dokter tidak ditemukan.\n";
					$msg .= "Silahkan masukkan kode dengan benar.";
				}
			}
		}else{
			$msg = "Dokter tidak ditemukan silahkan hubungi admin";
		}
		mysqli_close($wablas);
	}

	if($statusChat==99){
		$msg = 'Silahkan ketik *KONFIRMASI* jika belum mendapatkan link untuk konfirmasi.';
		if($waText=='konfirmasi'){
			$queryKodePoli = "SELECT * FROM bot_data_pasien WHERE cust_id='$idPsn'";
			$execKodePoli = mysqli_query($wablas,$queryKodePoli);
			$resKodePoli = $execKodePoli->fetch_assoc();
			$kodePoli = $resKodePoli['kodePoli'];

			if($kodePoli=='040'){
				$upKode = $kodePoli;
				$kodePoli = 'ANA';
			}else if($kodePoli=='017'){
				$upKode = $kodePoli;
				$kodePoli = 'BED';
			}else if($kodePoli=='HDL'){
				$upKode = $kodePoli;
				// $ifKode = '16454'; // pakai kode dokter, karna kd poli & sub poli nya INT, bukan HDL
				$kodePoli = 'INT';
			}else{
				$upKode = $kodePoli;
			}

			$poli = "SELECT tp.NamaPoli,mp.kdpoli_rs,mp.kdpoli FROM mapping_poli_bridging AS mp JOIN tm_poli AS tp ON mp.kdpoli_rs=tp.KodePoli WHERE mp.kdpoli='$upKode' GROUP BY mp.kdpoli_rs ORDER BY tp.KodePoli ASC";
			$resPoli = mysqli_query($dbrsud,$poli);
			$tpPoli = $resPoli->fetch_assoc();
			mysqli_close($wablas);

			$counter = "Pendaftaran";
			// if($rowsDapas['is_pasien_baru']==0){
			$cek = $upKode;
			if($rowsDapas['is_pasien_baru']!=1){
				// $storeRsuRegister = storeRsuRegister($idPsn,$wablas,$dbrsud); // insert tr_registrasi && filling
				if($cek=='INT' || $cek=='KLT' || $cek=='JAN'){
					$counter = "Counter A";
				}elseif($cek=='URO'||$cek=='BSY'||$cek=='ORT'||$cek=='BED'||$cek=='OBG'||$cek=='017'||$cek=='VCT'){
					$counter = "Counter B";
				}elseif($cek=='IRM'||$cek=='PAR'||$cek=='SAR'||$cek=='GIZ'||$cek=='ANT'){
					$counter = "Counter C";
				}elseif($cek=='THT'||$cek=='MAT'||$cek=='ANA'||$cek=='JIW'){
					$counter = "Counter D";
				}elseif($cek=='GIG'){
					$counter = "Counter E";
				}
			}

			$idBots = $rows['id'];
			$msg = "Selamat Sdr/I ".$resKodePoli['nama']." *BERHASIL* mendaftar antrian.\n";
			$msg .= "Kode Booking : *".$rows['random']."*\n";
			$msg .= "Tanggal Berkunjung : ".date("d-m-Y",strtotime($resKodePoli['tglBerobat']))."\n";
			$msg .= "Poli Tujuan : *".$tpPoli['NamaPoli']."*\n";
			$msg .= "Silahkan menuju *$counter*, untuk mendapatkan nomor antrian dengan cara :\n";
			$msg .= "1. Masukkan kodebooking kedalam mesin antrian. Atau,\n";
			$msg .= "2. Klik tautan ini : ".$baseURL."verifikasi/".$idBots."/".$rows['random']." ketika sudah di RS\n\n";
			$msg .= "Jika terdapat kendala hubungi hotline : 0815257200088 untuk mendapatkan bantuan.";
			// $msg = json_encode($resKodePoli,JSON_PRETTY_PRINT);
		}
	}

	### Reset pendaftaran
	// if($waText=='reset' && ($statusChat>1 && $statusChat<99) && ($rows['pasien_baru']==false || $rows['pasien_baru']==true)){
	if($waText=='reset' && $statusChat>1 && ($rows['pasien_baru']==false || $rows['pasien_baru']=="" || $rows['pasien_baru']==true)){
		$msg = msgWelcome($request);
		if($statusChat==99 && $rowsDapas['is_pasien_baru']==false){
			$kodeCust = $rowsDapas['KodeCust'];
			$tglPeriksa = $rowsDapas['tglBerobat'];
			$qRegis = "DELETE FROM tr_registrasi WHERE No_RM='$kodeCust' AND DATE(Tgl_Register)='$tglPeriksa'";
			$execQRegis = mysqli_query($dbrsud,$qRegis);
			$qFilling = "DELETE FROM filling WHERE no_rm='$kodeCust' AND DATE(tgl_periksa)='$tglPeriksa'";
			$execQFilling = mysqli_query($wablas,$qFilling);
		}
		$qResetBotDaPas = "DELETE FROM bot_data_pasien WHERE idBots='$idBots'";
		$execQResetBotDaPas = mysqli_query($wablas,$qResetBotDaPas);
		$qResetBotPas = "DELETE FROM bot_pasien WHERE id='$idBots'";
		$execQResetBotPas = mysqli_query($wablas,$qResetBotPas);
		mysqli_close($wablas);
		mysqli_close($dbrsud);
	}



	if(isset($msg)){
		if(!empty($idBots) && $statusChat>=1 && $reset){
			if($ifKodeDokter || $statusChat==99){
			}else{
				$msg .= "\n\nUntuk mengulangi pendaftaran Silahkan ketik : *reset*";
			}
		}
		// echo 'tes';
		echo $msg;
	}

	# Tambahkan kode poli BPJS jika tidak poli tidak ingin ditampilkan
	// function ignorePoli($request){
	// 	$ignorePoli = "'ALG','UGD','ANU','GIG'"; # Default ignore
	// 	$ignorePoli .= ",'PSY'";
	// 	$tanggal = $request->tanggal_berobat;
	// 	$total = 0;
	// 	if($request->natusi_apm){
	// 		$query = "SELECT count(cust_id) as total FROM bot_pasien as bp
	// 			JOIN bot_data_pasien as bdp ON bp.id = bdp.idBots
	// 			WHERE bp.tgl_periksa = '$tanggal'
	// 			AND bp.statusChat='99'
	// 			AND bdp.kodePoli='017'
	// 		";
	// 		$res = mysqli_query($request->natusi_apm,$query);
	// 		$total = mysqli_fetch_assoc($res)['total'];
	// 	}

	// 	$request->merge(['nama_hari' => date('D', strtotime($tanggal))]);
	// 	$namaHari = namaHari($request);
	// 	if(($namaHari=='Selasa' && $total >= 50) || ($namaHari=='Kamis' && $total >= 30)){
	// 		$ignorePoli .= ",'017'"; # 017 => onkologi
	// 	}
	// 	return $ignorePoli;
	// }

	# Message kuota poli
	function pemberitahuanPoli($request){
		$dateNow = date('Y-m-d');
		// if($request->phone=='6281335537942'){
		// 	$dateNow = date('Y-m-d',strtotime('today +4day'));
		// }
		$total=0;

		$text = "*Untuk sementara waktu.*\n";
		$text .= "*Pendaftaran terbatas Poli Onkologi dengan Kuota sebagai berikut:*\n";

		$dt = date('D', strtotime($dateNow));
		$request->merge(['nama_hari' => $dt]);
		$namaHari = namaHari($request);
		$tanggal = [];
		// $tanggal = ['23-07-2024','25-07-2024'];
		# 017 => kode poli onkologi
		if($namaHari=='Senin'){ # selasa, rabu, kamis
			array_push($tanggal,date("d-m-Y",strtotime("$dateNow +1day")),date("d-m-Y",strtotime("$dateNow +3day")));
		}
		else if($namaHari=='Selasa'){ # rabu, kamis, jumat
			array_push($tanggal,date("d-m-Y",strtotime("$dateNow +2day")));
		}
		else if($namaHari=='Rabu'){ # kamis, jumat, sabtu
			array_push($tanggal,date("d-m-Y",strtotime("$dateNow +1day")));
		}
		else if($namaHari=='Sabtu'){ # minggu, senin, selasa
			array_push($tanggal,date("d-m-Y",strtotime("$dateNow +3day")));
		}
		else if($namaHari=='Minggu'){ # senin, selasa, rabu
			array_push($tanggal,date("d-m-Y",strtotime("$dateNow +2day")));
		}
		$num = 0;
		foreach($tanggal as $key => $val){
			$dt = date('D', strtotime($val));
			// $now = $request->phone=='6281335537942' ? strtotime('now +1day') : strtotime('now');
			if(strtotime('now') < strtotime($val)){
				$whereDate = date('Y-m-d',strtotime($val));
				$query = "SELECT count(cust_id) as total FROM bot_pasien as bp
					JOIN bot_data_pasien as bdp ON bp.id = bdp.idBots
					WHERE bp.tgl_periksa = '$whereDate'
					AND bp.statusChat='99'
					AND bdp.kodePoli='017'
				";
				$res = mysqli_query($request->natusi_apm,$query);
				$total = mysqli_fetch_assoc($res)['total'];

				$num++;
				$request->merge(['nama_hari' => $dt]);
				$namaHari = namaHari($request);
				$limit = $namaHari=='Selasa' ? 50 : 30;
				// $total = $namaHari=='Selasa' ? 30 : $total;
				$text .= "$num. $namaHari $val, kuota terpakai $total/$limit.".($key+1 < count($tanggal) ? "\n" : '');
			}else{
				$num = $num> 0 ? $num-- : 0;
			}
		}
		return $num!==0 ? $text : false;
	}
	function namaHari($request){
		$str = $request->nama_hari;
		switch (true) {
			case ($str==='Mon' || $str===1):
				$hari = 'Senin';
				break;
			case ($str==='Tue' || $str===2):
				$hari = 'Selasa';
				break;
			case ($str==='Wed' || $str===3):
				$hari = 'Rabu';
				break;
			case ($str==='Thu' || $str===4):
				$hari = 'Kamis';
				break;
			case ($str==='Fri' || $str===5):
				$hari = "Jum'at";
				break;
			case ($str==='Sat' || $str===6):
				$hari = 'Sabtu';
				break;
			default:
				$hari = 'Minggu';
				break;
		}
		return $hari;
	}

	function msgJadwalPolis($wablas = ''){
		$total=0;
		if($wablas){
			$query = "SELECT count(cust_id) as total FROM bot_pasien as bp
				JOIN bot_data_pasien as bdp ON bp.id = bdp.idBots
				WHERE bp.tgl_periksa = '2024-06-27'
				AND bp.statusChat='99'
				AND bdp.kodePoli='017'
			";
			$res = mysqli_query($wablas,$query);
			$total = mysqli_fetch_assoc($res)['total'];
		}
		$text = "*Untuk sementara waktu.*\n";
		$text .= "*Pelayanan Poli Jiwa hanya tersedia pada hari Selasa dan Kamis.*\n";
		$text .= "*Pendaftaran terbatas Poli Onkologi dengan Kuota 25 Pasien pada Tanggal 27 Juni 2024.*";
		if($total>0){
			// $text .= "\n*Kuota terpakai $total/25.*";
			$text .= "\n*Kuota terpakai 25/25.*";
		}
		return $text;
	}

	function msgJadwalPoli($kodePoli = ''){
		$text = "*Untuk sementara waktu.*";
		if(!$kodePoli || $kodePoli=='JIW'){
			$text .= "\n*Pelayanan Poli Jiwa hanya tersedia pada hari Selasa dan Kamis.*";
		}
		// if(!$kodePoli || $kodePoli=='017'){
		// 	$text .= "\n*Pelayanan Poli Bedah Onkologi tutup pada hari Kamis.*";
		// }
		return $text;
	}

	function cekLibur($date){ # Format(d-m-Y)
		$arrDate = ['23-05-2024','24-05-2024'];
		return in_array($date,$arrDate) ? : false;
	}

	function dokterInternal($ifKode,$tglBerobat){
		$getDay = date('D',strtotime($tglBerobat));
		$timeDokter = ($getDay != 'Sat') ? '08.00-14.00' : '08.00-12.00';
		$dokterInternal = [
			[
				'kodesubspesialis' => 'PSY',
				'kodedokter' => '161115',
				'kodepoli' => 'PSY',
				'jadwal' => $timeDokter,
				'namadokter' => 'HASRI ARDILLA S.Psi.,M.Psi.,Psikolog',
			]
		];
		if($ifKode=='GIZ'){
			$dokterInternal = [
				[
					'kodesubspesialis' => 'GIZ',
					'kodedokter' => '161111',
					'kodepoli' => 'GIZ',
					'jadwal' => $timeDokter,
					'namadokter' => 'SAYEKTI RENI NUGRAHENI, S.Gz',
				],
				[
					'kodesubspesialis' => 'GIZ',
					'kodedokter' => '161112',
					'kodepoli' => 'GIZ',
					'jadwal' => $timeDokter,
					'namadokter' => 'HERLINA MEI WULANDARI,S.Gz',
				],
				[
					'kodesubspesialis' => 'GIZ',
					'kodedokter' => '161113',
					'kodepoli' => 'GIZ',
					'jadwal' => $timeDokter,
					'namadokter' => 'ENI FAHIMA, S.Gz',
				],
				[
					'kodesubspesialis' => 'GIZ',
					'kodedokter' => '161114',
					'kodepoli' => 'GIZ',
					'jadwal' => $timeDokter,
					'namadokter' => 'UNTARI, SKM',
				]
			];
		}elseif($ifKode=='VCT'){
			$dokterInternal = [
				[
					'kodesubspesialis' => 'VCT',
					'kodedokter' => '16426',
					'kodepoli' => 'VCT',
					'jadwal' => $timeDokter,
					'namadokter' => 'dr. TUSY PUNGKI HARTANTO',
				]
			];
		}elseif($ifKode=='MCU'){
			$dokterInternal = [
				[
					'kodesubspesialis' => 'MCU',
					'kodedokter' => '16428',
					'kodepoli' => 'MCU',
					'jadwal' => $timeDokter,
					'namadokter' => 'dr. WIWIEK ANDAYANI',
				]
			];
		}
		return $dokterInternal;
	}

	function updateCustomer($cek,$data,$dbrsud){
		$noRM = $data['noRM'];
		$kode = "";
		if($cek=='nik'){
			$kode=200;
			$nik = $data['nik'];
			$upCust = "UPDATE tm_customer SET NoKtp='$nik' WHERE KodeCust='$noRM'";
		}elseif($cek=='bpjs'){
			$kode=200;
			$bpjs = $data['bpjs'];
			$upCust = "UPDATE tm_customer SET FieldCust1='$bpjs' WHERE KodeCust='$noRM'";
		}elseif($cek=='tglLahir'){
			$kode=200;
			$tglLahir = $data['tglLahir'];
			$upCust = "UPDATE tm_customer SET TglLahir='$tglLahir' WHERE KodeCust='$noRM'";
		}
		if($kode==200){
			$execUpCust = mysqli_query($dbrsud,$upCust) or die(mysqli_error($dbrsud));
			return $execUpCust;
		}else{
			return 'gagal';
		}
	}

	function storeRsuRegister($idPsn,$wablas,$dbrsud){
		$query = "SELECT * FROM bot_data_pasien WHERE cust_id='$idPsn'";
		$execQuery = mysqli_query($wablas,$query);
		$resQuery = $execQuery->fetch_assoc();

		$storeRsuRegister = new WaBotBridgingController;
		$data = [
			'data'   => (object)$resQuery,
			'wablas' => $wablas,
			'dbrsud' => $dbrsud
		];
		return $storeRsuRegister->storeRsuRegister(new Request($data));
	}

	function updateTglLahir($tglLahir,$idPsn,$wablas){
		if(!empty($tglLahir)){
			$upTglLahir = "UPDATE bot_data_pasien SET tglLahir='$tglLahir' WHERE cust_id='$idPsn'";
			mysqli_query($wablas,$upTglLahir);
		}
	}

	function msgTglBerobat($date){
		$msg = "Masukkan Tanggal Berobat.\n";
		// $msg .= "Catatan : Tanggal Berobat hanya bisa pada tanggal H-1 s/d H+3 dari tanggal sekarang.\n";
		$msg .= "Catatan : Hanya Bisa Melakukan Pendaftaran Pada H-1 s/d H-3 dari Tanggal Berobat.\n";
		//$msg .= "*Info : 19 April - 25 April 2023 LIBUR*.\n";
		$msg .= "Contoh : ".$date;
		return $msg;
	}

	function cekAntrian($tglBerobat,$nik,$wablas){
		$qGetAntri = "SELECT * FROM antrian WHERE tgl_periksa='$tglBerobat' AND nik='$nik'";
		$execQGetAntri = mysqli_query($wablas,$qGetAntri);
		return $execQGetAntri;
	}

	function getKuotaPoli($request){
		dateDetail($request); # Ambil tanggal dan nama hari untuk 3 hari kedepan, dari tanggal sekarang
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

	function kuotaPoliMessage($request){
		$groupedData = [];
		$dataKuota = getKuotaPoli($request);
		if(count($dataKuota)==0){ # Jangan cetak pesan jika data 0
			return false;
		}
		foreach ($dataKuota as $item) {
			$poliId = $item['poli_id'];
			if (!isset($groupedData[$poliId])) {
				$groupedData[$poliId] = [
					'poli_id' => $item['poli_id'],
					'data' => []
				];
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

			# Urutkan data berdasarkan hari/tanggal (ASC)
			$columnSort = $datas['data'][0]['hari']!="" ? 'hari' : 'tanggal';
			array_multisort(array_column($datas['data'], $columnSort), SORT_ASC, $datas['data']);
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
				// if($keys+1 < count($datas['data'])){
				// 	$msg .= "\n";
				// }elseif($key+1 < count($data)){
				// 	$msg .= "\n\n";
				// }
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


		// $text .= "$num. $namaHari $val, kuota terpakai $total/$limit.".($key+1 < count($tanggal) ? "\n" : '');

		$num = 1;
		// $msg = "Silahkan Pilih *Nomor Poli Tujuan* Anda!\n";
		$msg = "Untuk sementara waktu.\n";
		// 	$keterangan = str_replace('<br />', "\n", $row['keterangan']);
		// 	$keterangan = str_replace('<p>', '', $keterangan);
		// 	$keterangan = str_replace('</p>', '', $keterangan);
		// 	$keterangan = str_replace('<strong>', '*', $keterangan);
		// 	$keterangan = str_replace('</strong>', '*', $keterangan);
	}

	function kuotaPoliIgnore($request){
		// $request->merge(['tanggal_berobat'=>'2024-09-06']);
		$tanggal = $request->tanggal_berobat;
		$dataKuota = getKuotaPoli($request);

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

	function dateDetail($request){
		$arrayHari = [];
		$arrayTanggal = [];
		for($i=1; $i<=3; $i++){ # Ambil tanggal dan nama hari untuk 3 hari kedepan, dari tanggal sekarang
			$ts = strtotime("today +$i day");
			$dayInNum = date('N',$ts);
			array_push($arrayHari, $dayInNum);
			$request->merge(['nama_hari'=>(int)$dayInNum]);
			$arrayTanggal[$dayInNum] = (object)[
				'tanggal'=>date('d-m-Y',$ts),
				'nama_hari'=>namaHari($request),
			];
		}
		$tsNow = strtotime('now');
		$tsPlus = strtotime('now +3day');
		$request->merge([
			'array_hari'=>implode(",",$arrayHari),
			'tanggal_detail'=>$arrayTanggal,
			'ts_now'=>$tsNow,
			'ts_plus'=>$tsPlus,
			'dt_now'=>date('Y-m-d'),
			'dt_plus'=>date('Y-m-d',$tsPlus),
		]);
	}

	function msgWelcome($request){
		$msg = "Selamat datang di RSUD Dr. Wahidin Sudiro Husodo Kota Mojokerto, Anda sedang berinteraksi dengan Sistem Pendaftaran Antrian Otomatis, Silahkan Pilih Layanan :\n\n";
		$msg .= "A : Pendaftaran Antrian\n";
		$msg .= "B : Promo Menarik\n\n";
		$msg .= "Ketik A atau B lalu kirim.\n\n";
		// $msg .= "*Untuk sementara waktu pendaftaran POLI GENERAL CHECK UP*\n*Hanya bisa dilakukan secara onsite.*\n\n";
		// $msg .= msgLibur()."\n\n";

		// $msg .= "*INFORMASI LIBUR CUTI BERSAMA HARI RAYA IDUL FITRI 1445 H*\n";
		// $msg .= "_*--PELAYANAN POLI RAWAT JALAN dan POLI EKSEKUTIF--*_\n";
		// $msg .= "*LIBUR : 08 s/d 15 April 2024*\n";
		// $msg .= "*BUKA KEMBALI : 16 April 2024*\n";
		// $msg .= "*Pelayanan IGD & PONEK tetap buka 24 JAM*\n";
		// $msg .= "*Poli HD tetap buka seperti biasa, kecuali(Minggu)*\n\n";

		// $msg .= "*Pelayanan IGD & PONEK tetap buka 24 JAM*\n\n";
		// $msg .= "*Mohon Maaf Kuota untuk Medical Check Up Persyaratan Tenaga P3K Tanggal 10-18 Sudah Penuh*\n";
		// $msg .= "*Silahkan mendaftar kembali di tanggal 01 Juli 2023*\n";
		// $msg .= "*Terima Kasih*\n\n";

		// $msg .= msgJadwalPolis($wablas)."\n\n";
		// $msg .= msgJadwalPoli()."\n\n";
		// if($request->phone=='6281335537942' || $request->phone=='6281330003568'){
			// dateDetail($request); # Ambil tanggal dan nama hari untuk 3 hari kedepan, dari tanggal sekarang
			// echo kuotaPoliIgnore($request);
			// echo "\n\n\n";
			$kuotaPoliMessage = kuotaPoliMessage($request);
			$msg .= $kuotaPoliMessage!==false ? $kuotaPoliMessage : '';
		// 	echo $msg;
		// 	die();
		// 	$msg .= kuotaPoliMessage($request);
		// }else{
		// 	$txt = pemberitahuanPoli($request);
		// 	$msg .= $txt !== false ? "$txt\n\n" : '';
		// }

		$msg .= "Hotline 0815257200088 untuk mendapatkan bantuan apabila ada kendala pendaftaran.\n";
		$msg .= "Video tutorial penggunaan antrian cek di : shorturl.at/dhqz8";
		// $msg .= "\n\n*Sehubungan dengan perbaikan server data untuk optimalisasi sistem informasi, maka akan terjadi down sistem pada:*\n";
		// $msg .= "*Kamis, 28 September 2023 pukul 15:00 s/d 21:00 WIB.*";
		return $msg;
	}
	function msgLibur(){
		// $msg = "*INFORMASI LIBUR CUTI BERSAMA HARI RAYA IDUL FITRI 1445 H*\n";
		$msg = "*INFORMASI LIBUR*\n";
		$msg .= "_*--PELAYANAN POLI RAWAT JALAN dan POLI EKSEKUTIF--*_\n";
		$msg .= "*LIBUR : 23 s/d 24 Mei 2024*\n";
		$msg .= "*BUKA KEMBALI : 25 Mei 2024*\n";
		$msg .= "*Pelayanan IGD & PONEK tetap buka 24 JAM*\n";
		$msg .= "*Poli HD tetap buka seperti biasa, kecuali(Minggu)*";
		return $msg;
	}

	function updateNoRef($noRef,$idPasien,$wablas){
		$upNoRef = "UPDATE bot_data_pasien SET nomor_referensi='$noRef' WHERE cust_id='$idPasien'";
		mysqli_query($wablas,$upNoRef);
	}

	function upBotDataPasien($cek,$idBots,$wablas,$dataIn){
		$st = "";
		if($cek=='nik'){
			$data = $dataIn['nik'];
			$upPasien = "UPDATE bot_data_pasien SET nik='$data' WHERE idBots='$idBots'";
			$st =  200;
		}elseif($cek=='noka'){
			$data = $dataIn['noka'];
			$upPasien = "UPDATE bot_data_pasien SET nomor_kartu='$data' WHERE idBots='$idBots'";
			$st =  200;
		}elseif($cek=='caraBayar'){
			$data = $dataIn['caraBayar'];
			$jenisKunjungan = $dataIn['jenisKunjungan'];
			$upPasien = "UPDATE bot_data_pasien SET caraBayar='$data', jenis_kunjungan='$jenisKunjungan' WHERE idBots='$idBots'";
			$st = 200;
		}elseif($cek=='rujukan'){
			$jenis = $dataIn['jenisRujukan'];
			$aktif = $dataIn['rujukanAktif'];
			if($aktif==""){
				$upPasien = "UPDATE bot_data_pasien SET jenisRujukan='$jenis', rujukanAktif=null WHERE idBots='$idBots'";
			}else{
				$upPasien = "UPDATE bot_data_pasien SET jenisRujukan='$jenis', rujukanAktif='$aktif' WHERE idBots='$idBots'";
			}
			$st =  200;
		}
		if($st==200){
			$exec = mysqli_query($wablas,$upPasien) or die(mysqli_error($wablas));
		}
		return $exec;
	}

	function updateStatusChat($cek,$idBots,$wablas,$dataIn){
		$upResChat = "";
		if($cek=='statusChat'){
			$status = $dataIn['status'];
			$botPasien = "UPDATE bot_pasien SET statusChat='$status' WHERE id='$idBots'";
			$upResChat = 200;
		}elseif($cek=='baruLama'){
			$status = $dataIn['status'];
			$trueFalse = $dataIn['trueFalse']==true?1:0;
			$botPasien = "UPDATE bot_pasien SET statusChat='$status', pasien_baru='$trueFalse' WHERE id='$idBots'";
			$upResChat = 200;
		}elseif($cek=='kodeBooking'){
			$kodeBooking = $dataIn['kodeBooking'];
			$status = $dataIn['status'];
			$botPasien = "UPDATE bot_pasien SET random='$kodeBooking', statusChat='$status' WHERE id='$idBots'";
			$upResChat = 200;
		}elseif($cek=='pasienBaruPembayaran'){
			// $pasienId = $dataIn['pasienId'];
			$status = $dataIn['status'];
			$jenisPasien = $dataIn['jenisPasien'];
			// if($jenisPasien=='BPJS'){
				// $botPasien = "UPDATE bot_pasien SET statusChat=13, pasien_baru=true, data_diri=true, pasien_id='$pasienId' WHERE id='$idBots'";
				$botPasien = "UPDATE bot_pasien SET statusChat='$status', pasien_baru=true, data_diri=true WHERE id='$idBots'";
			// }else{
				// $botPasien = "UPDATE bot_pasien SET statusChat=3, pasien_baru=true, data_diri=true, pasien_id='$pasienId' WHERE id='$idBots'";
				// $botPasien = "UPDATE bot_pasien SET statusChat='$status', pasien_baru=true, data_diri=true WHERE id='$idBots'";
			// }
			$upResChat = 200;
		}
		if($upResChat==200){
			// return mysqli_query($wablas,$botPasien) or die($wablas->error);
			mysqli_query($wablas,$botPasien);
		}
	}

	function filterDokter($data,$kodeDokter){
		$dataDokter = [];
		foreach($data['response'] as $key => $val){
			if($val->kodedokter==$kodeDokter){
				array_push($dataDokter, $data['response'][$key]);
			}
		}
		if(count($dataDokter)>0){
			$msg = $dataDokter[0];
		}else{
			$msg = [];
		}
		return $msg;
	}

	function splitText($text){
		$split = str_split($text);
		if($split[0]==" " && $split[1]!=" "){
			array_shift($split);
			$nama = join("",$split);
		}else if($split[0]==" " && $split[1]==" "){
			array_splice($split,0,2);
			$nama = join("",$split);
		}else{
			$nama = join("",$split);
		}
		return $nama;
	}

	function inputData($p1,$p2,$p3=''){
		$msg = "";
		if($p1=='nama'){
			if($p2=='berhasil'){
				$msg = "Masukkan Nama Lengkap Anda.\n";
			}else{
				$msg = "Format Nama Tidak Sesuai!\n";
			}
			$msg .= "Copy Format Dibawah ini, isi Nama Anda (Cth. *Nama : Ahmad Habibi* , lalu kirim pesan).\n\n";
			$msg .= "Nama : masukkan nama";
		}else if($p1=='nik'){
			if($p2=='berhasil'){
				$msg = "Masukkan NIK Anda.\n";
			}else{
				if($p3=='numeric'){
					$msg = "*Format NIK Tidak Sesuai!*\n";
				}else{
					$msg = "Format NIK Tidak Sesuai!\n";
				}
			}
			$msg .= "Copy Format Dibawah ini, isi NIK Anda (Cth. *NIK : 35150xxx* , lalu kirim pesan). *Note : NIK harus 16 digit.\n\n";
			$msg .= "NIK : masukkan nik";
		}else if($p1=='lahir'){
			if($p2=='berhasil'){
				$msg = "Masukkan Tanggal Lahir Anda.\n";
			}else{
				$msg = "Format Tanggal Lahir Tidak Sesuai!\n";
			}
			$msg .= "Copy Format Dibawah ini, isi Tanggal Lahir Anda (Cth. *Tanggal Lahir : 01-05-1995* , lalu kirim pesan).\n\n";
			$msg .= "Tanggal Lahir : dd-mm-yyyy";
		}else if($p1=='ibu'){
			if($p2=='berhasil'){
				$msg = "Masukkan Nama Ibu Kandung Anda.\n";
			}else{
				$msg = "Format Nama Ibu Kandung Tidak Sesuai!\n";
			}
			$msg .= "Copy Format Dibawah ini, isi Nama Ibu Kandung (Cth. *Nama Ibu : Azizah* , lalu kirim pesan).\n\n";
			$msg .= "Nama Ibu : masukkan nama ibu";
		}else if($p1=='alamat'){
			if($p2=='berhasil'){
				$msg = "Masukkan Alamat Anda.\n";
			}else{
				$msg = "Format Alamat Tidak Sesuai!\n";
			}
			$msg .= "Copy Format Dibawah ini, isi Alamat Anda (Cth. *Alamat : Mojosari, Mojokerto* , lalu kirim pesan).\n\n";
			$msg .= "Alamat : masukkan alamat";
		}else if($p1=='berobat'){
				$dateNow = date("Y-m-d");
				$dateNowPlus1 = date("d-m-Y",strtotime($dateNow."+1 day"));
			if($p2=='berhasil'){
				$msg = "Masukkan Tanggal Berobat.\n";
			}else if($p2=='salah'){
				$msg = "*Tanggal berobat tidak bisa hari ini.*\n";
			}elseif($p2=='lewat'){
				$msg = "*Tanggal berobat sudah terlewat.*\n";
			}else{
				$msg = "*Tanggal berobat tidak sesuai!*\n";
			}
			// $msg .= "Catatan : Tanggal berobat hanya bisa pada tanggal H+1 s/d H+3 dari tanggal sekarang.\n";
			$msg .= "Catatan : Hanya Bisa Melakukan Pendaftaran Pada H-1 s/d H-3 dari Tanggal Berobat.\n";
			$msg .= "Contoh : ".$dateNowPlus1;
		}else if($p1=='bayar'){
			if($p2=='berhasil'){
				$msg = "Masukkan Cara Bayar.\n";
			}else{
				$msg = "Format Cara Bayar Tidak Sesuai!\n";
			}
			$msg .= "Copy Format Dibawah ini, isi Cara Bayar (Cth. *Bayar : UMUM* , lalu kirim pesan). *Note : Jika menggunakan asuransi lain, silahkan isi sesuai jenis asuransi yang digunakan sekarang.*\n\n";
			$msg .= "Bayar : UMUM/BPJS/Asuransi Lain";
		}
		return $msg;
	}

	function expPasienBaru($explode1){
		//$waText = "Nama : Dwi\nNIK : 3515012710010001\nTanggal Lahir : 01-01-2022\nNama Ibu Kandung : Ibu Dwi\nAlamat : \nTanggal Rencana Berobat : 01-01-2022\nCara Bayar : UMUM";
		$arr = [];
		foreach($explode1 as $key => $val){
			$explode2 = explode(":",$val)[1];
			array_push($arr,$explode2);
		}
		$res = [];
		foreach($arr as $key => $val){
			$split = str_split($val);
			if($split[0]==" " && $split[1]!=" "){
				array_shift($split);
				$val = join("",$split);
				$split2 = str_split($val);
				$slice = array_slice($split2,-2);
				if($slice[0]!=" " && $slice[1]==" "){
					array_splice($split2,-1);
					$val = join("",$split2);
				}else if($slice[0]==" " && $slice[1]==" "){
					array_splice($split2,-2);
					$val = join("",$split2);
				}
			}else if($split[0]==" " && $split[1]==" "){
				array_splice($split,0,2);
				$val = join("",$split);
				$split2 = str_split($val);
				$slice = array_slice($split2,-2);
				if($slice[0]!=" " && $slice[1]==" "){
					array_splice($split2,-1);
					$val = join("",$split2);
				}else if($slice[0]==" " && $slice[1]==" "){
					array_splice($split2,-2);
					$val = join("",$split2);
				}
			}
			array_push($res, $val);
		}
		return $res;
	}

	function validasiDataPasien($param,$data){
		if($param=='Nama'){
			$ms = $param;
		}else if($param=='NIK'){
			$ms = $param;
		}else if($param=='tglBerobat'){
			$ms = 'Tanggal Rencana Berobat';
		}else{
			$ms = 'Cara Bayar';
		}
		$msg = "Nama : ".$data[0]."\n";
		$msg .= "NIK : ".$data[1]."\n";
		$msg .= "Tanggal Lahir : ".$data[2]."\n";
		$msg .= "Nama Ibu Kandung : ".$data[3]."\n";
		$msg .= "Alamat : ".$data[4]."\n";
		$msg .= "Tanggal Rencana Berobat : ".$data[5]."\n";
		$msg .= "Cara Bayar : ".$data[6]."\n\n";
		$msg .= 'Data *'.$ms.'* Wajib Diisi!';
		return $msg;
	}

	function getPoli($data){
		$num = 1;
		$msg = "Silahkan Pilih *Nomor Poli Tujuan* Anda!\n";
		while($row=$data->fetch_assoc()){
			$msg .= "*".$num.". ".$row['NamaPoli']."*\n";
			$num++;
		}
		$msg .= "\nHanya Nomor, tanpa Nama POLI. Contoh : 1";
		return $msg;
	}

	function cekStr($waText,$str){
		if(stripos($waText, $str)!==false){
			return true;
		}else{
			return false;
		}
	}

	function randomString($length) {
		// $characters = '123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$characters = '0123456789';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}

	function toDokter($dokterLokal,$dokterBpjs,$param,$count1=''){
		$countDokter = [];
		$msg = "Silahkan pilih Kode Dokter :\n";
		$arrKodeDokter = [];
		$num = 1;
		// if($kodeDokter!='GIG'){
			foreach($dokterLokal as $keyDL => $valDL){
				foreach($dokterBpjs['response'] as $key => $val){
					if($param=='hitung'){
						// ambil dokter dengan kode yang sama(ada dari kedua sisi)
						if($valDL['kodedokter']==$val->kodedokter){
							array_push($countDokter, 1);
						}
					}else{
						if($valDL['kodedokter']==$val->kodedokter){
							if(count($arrKodeDokter)==0){
								array_push($arrKodeDokter,$val->kodedokter);
							}
							$msg .= "*Kode* : ".$val->kodedokter."\n";
							$msg .= "Nama : ".$val->namadokter."\n\n";
							// $msg .= "Jadwal : ".$val->jadwal."\n\n";
							$num++;
						}
					}
				}
			}
		// }else{
		// 	if(!in_array($kodeDokter,['GIG','BDM','GND','KON'])){
		// 		foreach($dokterLokal as $keyDL => $valDL){
		// 			foreach($dokterBpjs['response'] as $key => $val){
		// 				if($param=='hitung'){
		// 					// ambil dokter dengan kode yang sama(ada dari kedua sisi)
		// 					if($valDL['kodedokter']==$val->kodedokter){
		// 						array_push($countDokter, 1);
		// 					}
		// 				}else{
		// 					if($valDL['kodedokter']==$val->kodedokter){
		// 						if(count($arrKodeDokter)==0){
		// 							array_push($arrKodeDokter,$val->kodedokter);
		// 						}
		// 						$msg .= "*Kode* : ".$val->kodedokter."\n";
		// 						$msg .= "Nama : ".$val->namadokter."\n\n";
		// 						// $msg .= "Jadwal : ".$val->jadwal."\n\n";
		// 						$num++;
		// 					}
		// 				}
		// 			}
		// 		}
		// 	}else{
		// 		foreach($dokterBpjs['response'] as $key => $val){
		// 			if($param=='hitung'){
		// 				// ambil dokter dengan kode yang sama(ada dari kedua sisi)
		// 				if($valDL['kodedokter']==$val->kodedokter){
		// 					array_push($countDokter, 1);
		// 				}
		// 			}else{
		// 				if($valDL['kodedokter']==$val->kodedokter){
		// 					if(count($arrKodeDokter)==0){
		// 						array_push($arrKodeDokter,$val->kodedokter);
		// 					}
		// 					$msg .= "*Kode* : ".$val->kodedokter."\n";
		// 					$msg .= "Nama : ".$val->namadokter."\n";
		// 					$msg .= "Jadwal : ".$val->jadwal."\n\n";
		// 					$num++;
		// 				}
		// 			}
		// 		}
		// 	}
		// }
		if(count($arrKodeDokter)>0){
			$msg .= "Contoh : ".$arrKodeDokter[0];
		}

		if($param=='hitung'){
			return count($countDokter);
		}else{
			return $msg;
		}
	}

	// BRIDGING BPJS START
	function refJadDok($data){
		$refJadDok = new WaBotBridgingController;
		$kodePoli = $data[0];
		$tanggal = $data[1];
		if($kodePoli=='GIG'){
			$kodePoli = $data[2]['polibpjs'];
		}
		$data = [
			// 'kode'    => $data[0],
			// 'tanggal' => $data[1]
			'kode'    => $kodePoli,
			'tanggal' => $tanggal
		];
		$result = $refJadDok->refJadDok(new Request($data));
		return $result;
	}

	function cekPeserta($nomor,$jenis){
		$data = [
			'nomor' => $nomor,
			'jenis' => $jenis
		];
		$cekPeserta = new WaBotBridgingController;
		// $result = $cekPeserta->cekPeserta($nomor,$jenis);
		$result = $cekPeserta->cekPeserta(new Request($data));
		return (object)$result;
	}

	function cekRujukan($nomor,$jenis){
		$cekRujukan = new WaBotBridgingController;
		$data = [
			'nomor' => $nomor,
			'jenis' => $jenis
		];
		$result = $cekRujukan->cekRujukan(new Request($data));
		// $result = $cekRujukan->cekRujukan('0001387975239','bpjs');
		return (object)$result;
	}

	function rujukanMultiRecord($nomor,$jenis){
		$request = new Request([
			'nomor' => $nomor,
			'jenis' => $jenis
		]);
		$cekRujukan = new WaBotBridgingController;
		$result = $cekRujukan->cekMultiRujukan($request);
		return (object)$result;
	}
	// BRIDGING BPJS END




	// function pasienBaru($cek){
	// 	if($cek=='berhasil'){
	// 		$msg = "Masukkan Nama Lengkap Anda.\n";
	// 		$msg .= "Copy Format Dibawah ini, isi Nama Anda (Cth. *Nama : Ahmad Habibi* , lalu kirim pesan).\n\n";
	// 	}else{
	// 		$msg = "Pengiriman Data Gagal!\n";
	// 		$msg .= "Data Yang Dikirim Harus Sesuai Format!\n";
	// 		$msg .= "(Cth. *Nama : Ahmad Habibi* , lalu kirim pesan).*\n\n";
	// 	}
	// 	$msg .= "Nama : masukkan nama";
	// 	// $msg .= "Nama : ...\n";
	// 	// $msg .= "NIK : ...\n";
	// 	// $msg .= "Tanggal Lahir : ...\n";
	// 	// $msg .= "Nama Ibu Kandung : ...\n";
	// 	// $msg .= "Alamat : ...\n";
	// 	// $msg .= "Tanggal Rencana Berobat : Cth. 31-01-2002\n";
	// 	// $msg .= "Cara Bayar : UMUM / BPJS / Lainnya";
	// 	return $msg;
	// }

	// function pasienLama(){
	// 	$msg = 'Tuliskan  Nomor RM (Rekam Medis) Anda!';
	// 	return $msg;
	// }