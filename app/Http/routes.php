<?php
header('Access-Control-Allow-Origin: *');
header( 'Access-Control-Allow-Headers: Authorization, Content-Type' );
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::get('/tescurl','AaController@testing');
Route::get('/clear', function() {
    $exitCode = Artisan::call('route:clear');
    $exitCode = Artisan::call('view:clear');
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('config:cache');
    // return what you want
    return "Berhasil Clear!";
});
// Route::get('/', 'DashboardController@index')->name('quest');
Route::get('/', function(){
	$cekUser = Auth::User();
	if(!empty($cekUser)){
		$lvl = Auth::User()->lv_user;
		$cekCtr1 = $lvl=='countera'||$lvl=='counterb'||$lvl=='counterc1'||$lvl=='counterhd';
		$cekCtr2 = $lvl=='counterc2'||$lvl=='counterc'||$lvl=='counterd'||$lvl=='countere';
		if($lvl=='admin'){
			return redirect()->route('dashboardAdmin');
		}elseif($cekCtr1 || $cekCtr2){
			return redirect()->route('formListCounter');
		}elseif($lvl=='loket1'||$lvl=='loket2'){
			return redirect()->route('antreanPanggil');
		}elseif($lvl=='kiosk1'||$lvl=='kiosk2'||$lvl=='kiosk3'||$lvl=='kiosk4'||$lvl=='kiosk5'){
			return redirect()->route('index-antrian');
		}elseif($lvl=='operasi'){
			return redirect()->route('operasi');
		}elseif($lvl=='filling'){
			return redirect()->route('dashboardFilling');
		}else{
			return redirect()->route('dashboardAdmin');
		}
	}else{
		return redirect()->route('login');
	}
});

Route::post('/nomorantrian', 'DashboardController@nomorantrian')->name('nomorantrian');
Route::post('api/reminderCheck', 'APIAPMController@reminderCheck');
Route::post('api/jadwalOperasiRS', 'GetController@jadwalOperasiRS');

// Route Wilayah
Route::post('getKabupaten','GetController@getKabupaten')->name('get_kabupaten');
Route::post('getKecamatan','GetController@getKecamatan')->name('get_kecamatan');
Route::post('getDesa','GetController@getDesa')->name('get_desa');

// IMPORT ANTRIAN
Route::post('/import', 'GetController@import')->name('importAntrian');

// Konfirmasi Antrian
Route::get('/konfirmasi-antrian', 'RegistrationController@konfirmasiAntrian')->name('konfirmasi-antrian');
Route::get('/antrian-manual', 'RegistrationController@antrianManual')->name('antrian-manual');
Route::get('/show-antrian', 'RegistrationController@showAntrian')->name('show-antrian');

Route::post('/kode-cetak-ulang','RegistrationController@cariCetakUlang')->name('cariCetakUlang');

Route::get('/registration', 'RegistrationController@index')->name('registration');
Route::get('/helpRegistration', 'RegistrationController@help')->name('helpRegistration');
Route::post('/detailHelper', 'RegistrationController@detailHelper')->name('detailHelper');
Route::post('/formPendaftaran', 'RegistrationController@formPendaftaran')->name('formPendaftaran');
Route::post('/confirmCust', 'RegistrationController@confirmCust')->name('confirmCust');
Route::post('/reloadCekRujukan', 'RegistrationController@reloadCekRujukan')->name('reloadCekRujukan');
Route::post('/pilihPoli', 'RegistrationController@pilihPoli')->name('formPilihPoli');
Route::post('/doRegistrasi', 'RegistrationController@doRegistrasi')->name('doRegistrasi');
Route::post('/bridgingApm', 'RegistrationController@bridgingApm')->name('bridgingApm');
Route::post('/bridging2Cc', 'RegistrationController@bridging2Cc')->name('bridging2Cc');
Route::post('/bridging2Cc_apm', 'RegistrationController@bridging2Cc_apm')->name('bridging2Cc_apm');
Route::post('/cekDokterDpjp', 'RegistrationController@cekDokterDpjp')->name('cekDokterDpjp');

Route::post('/reloadNoRujukan', 'RegistrationController@reloadNoRujukan')->name('reloadNoRujukan');
Route::post('/getKdPoliBridging', 'RegistrationController@getKdPoliBridging')->name('getKdPoliBridging');

//setup konfirmasi scan dan manual
Route::post('/refreshQR', 'ApiSimapanController@requestQr')->name('refreshQR');
Route::post('/ubahStatusPrint/{token}', 'ApiSimapanController@ubahStatusPrint')->name('ubahStatusPrint');
Route::post('/konfirmasi-manual','AntrianController@konfirmasiManual')->name('konfirmasiManual');

Route::get('login', 'AuthController@index')->name('login');
Route::post('DoLogin', 'AuthController@DoLogin')->name('DoLogin');
Route::get('logout', function(){
	Auth::logout();
	return Redirect::route('login')->with('message', 'Anda telah logout.')->with('type', 'success');
})->name('logout');

/* DISPLAY */
Route::group(array('prefix'=>'display'), function(){
	Route::get('/list-konter', 'DisplayController@listKonter')->name('listKonter');
	Route::get('/to-konter-a', 'DisplayController@toKonterA')->name('toKonterA');
	Route::get('/to-konter-b', 'DisplayController@toKonterB')->name('toKonterB');
	Route::get('/to-konter-c1', 'DisplayController@toKonterC1')->name('toKonterC1');
	Route::get('/to-konter-c2', 'DisplayController@toKonterC2')->name('toKonterC2');
	Route::get('/to-konter-d', 'DisplayController@toKonterD')->name('toKonterD');
	Route::get('/to-konter-e', 'DisplayController@toKonterE')->name('toKonterE');

	Route::get('/countera', 'DisplayController@konter_poli_a')->name('konterA');
	Route::get('/counterb', 'DisplayController@konter_poli_b')->name('konterB');
	Route::get('/counterc1', 'DisplayController@konter_poli_c1')->name('konterC1');
	Route::get('/counterc2', 'DisplayController@konter_poli_c2')->name('konterC2');
	Route::get('/counterd', 'DisplayController@konter_poli_d')->name('konterD');
	Route::get('/countere', 'DisplayController@konter_poli_e')->name('konterE');

	# Display farmasi
	Route::get('/farmasi-rj1', 'DisplayController@farmasiRJ1')->name('displayFarmasiRJ1');
	Route::get('/farmasi-rj2', 'DisplayController@farmasiRJ2')->name('displayFarmasiRJ2');	
});

// Display Counter 
Route::group(['prefix'=>'counter'],function(){
	Route::post('/antrianCounter','DisplayCounterPoliController@dataCounter')->name('antrianCounter');
	Route::post('/antrianCounterSaatIni','DisplayCounterPoliController@dataCounterSaatIni')->name('antrianCounterSaatIni');
});

// CETAK ULANG SEP KONTERPOLI
Route::post('cetaksep-ulang','AntrianController@cetakUlangSep')->name('cetak_sep_ulang');

//Display Kiosk
Route::get('/antrian-registration/{id_kiosk}', 'RegistrationController@indexAntrian')->name('index-antrian');

// DISPLAY LOKET
Route::group(array('prefix'=>'display'), function(){
	Route::get('/display-loket', 'DisplayController@displayLoket')->name('displayLoket');
	Route::post('/changeStatusPanggilan', 'DisplayController@changeStatusPanggilan')->name('changeStatusPanggilan');
	Route::post('/insertDataPemanggilan', 'DisplayController@insertDataPemanggilan')->name('insertDataPemanggilan');
	Route::post('/ulangiDataPemanggilan', 'DisplayController@ulangiDataPemanggilan')->name('ulangiDataPemanggilan');
});

Route::post('/antrianLoket','DisplayController@dataLoket')->name('antrianLoket');
Route::post('/antrianFarmasi','DisplayController@dataFarmasi')->name('antrianFarmasi');

Route::get('/sendSms', 'DashboardController@sendSms')->name('sendSms');
Route::get('create', 'AuthController@create')->name('create');

Route::get('/verifikasi/{id}/{random}', 'WablasController@verifikasi')->name('verifikasi');
Route::get('verifikasiBerhasil','WablasController@verifBerhasil')->name('verifBerhasil');
Route::post('cekRujukanWaBot','WaBotBridgingController@cekRujukan')->name('cekRujukanWaBot');
Route::get('/api/send-message', 'WablasController@sendDataTesting')->name('testing');

// Ambil Antrian Kiosk
Route::post('/apidokter', 'RegistrationController@ApiDokter')->name('api-dokter');
Route::post('jadwal-dokter-kiosk', 'RegistrationController@jadwalDokterKiosk')->name('jadwal-dokter-kiosk');
Route::post('/caripasien', 'RegistrationController@cari')->name('cari');
Route::post('/pilihpasien', 'RegistrationController@pilihpasien')->name('pilih-pasien');
Route::post('/politujuan', 'RegistrationController@politujuan')->name('politujuan');
Route::post('/cari-rujukan', 'RegistrationController@cariRujukan')->name('cari-rujukan');
Route::post('/ambil-antrian-save', 'RegistrationController@ambilAntrianSave')->name('ambil-antrian-save');
Route::get('/cetak-antrian/{id}', 'RegistrationController@cetakAntrian')->name('cetak-antrian');
Route::get('/cetak-antrian-poli/{id}','AntrianController@cetakAntrianKonterPoli')->name('cetakAntrianKonterPoli');
// Cetak Tracer
Route::get('/cetak-tracer-pasien/{id}','AntrianController@cetakTracerPasien')->name('cetakTracerPasien');
Route::get('/cetak-tracer-pasien-poli/{id}','AntrianController@cetakTracerPasienPoli')->name('cetakTracerPasienPoli');

/*-----------  Admin  --------------*/
Route::group(['middleware' => 'auth'], function() {
	Route::group(['prefix'=>'filling'],function(){
		Route::get('/dashboard','FillingController@dashboard')->name('dashboardFilling');
		Route::get('/','FillingController@main')->name('mainFilling');
		Route::post('/cariFilling','FillingController@cariFilling')->name('cariFilling');
	});
	
	Route::group(array('prefix'=>'admin'), function(){
		// Antrean BPJS
		Route::group(['prefix'=>'filling'],function(){
			Route::post('/dataGridFilling','FillingController@dataGridFilling')->name('dataGridFilling');
			Route::post('/changeStatFilling','FillingController@changeStatFilling')->name('changeStatFilling');
		});

		// ANTREAN ONLINE (PASIEN LAMA)
		Route::group(['prefix'=>'antrean-online'],function(){
			Route::get('/','AntreanOnlineController@main')->name('list-antrian-online');
			Route::post('/get-antrian-online','AntreanOnlineController@datagrid')->name('get-antrian-online');
		});

		// Antrean BPJS
		Route::group(['prefix'=>'antrean'],function(){
			Route::group(['prefix'=>'dokter'],function(){
				Route::get('/ref/jadwal','BridgBpjsController@webRefJadDok')->name('webRefJadDok');
				Route::any('/update/jadwal','BridgBpjsController@webEditJadDok')->name('editJadDok');
				Route::post('/update/jadwal/get','BridgBpjsController@webGetJadDok')->name('getJadDok');
				Route::post('/update/jadwal/saveLokal','BridgBpjsController@webSaveJadDok')->name('saveJadDok');
			});

			Route::group(['prefix'=>'panggil'],function(){
				Route::post('/antreanPanggilData','AntrianController@antreanPanggilData')->name('antreanPanggilData');

				Route::get('/','BridgBpjsController@antreanPanggil')->name('antreanPanggil');
				Route::post('/panggilSelanjutnya','AntrianController@panggilSelanjutnya')->name('panggilSelanjutnya');
				Route::post('/panggilUlang','AntrianController@panggilUlang')->name('panggilUlang');
			});

			// RATA" PELAYANAN START
			Route::group(['prefix'=>'rata-rata-pelayanan'],function(){
				Route::get('/','RataPelayananController@main')->name('mainRataPelayanan');
				Route::get('/tunggu-admisi','RataPelayananController@tungguAdmisi')->name('tungguAdmisi');
				Route::get('/layan-admisi','RataPelayananController@layanAdmisi')->name('layanAdmisi');
				Route::get('/tunggu-poli','RataPelayananController@tungguPoli')->name('tungguPoli');
				Route::get('/layan-poli','RataPelayananController@layanPoli')->name('layanPoli');
				Route::get('/tunggu-farmasi','RataPelayananController@tungguFarmasi')->name('tungguFarmasi');
				Route::get('/layan-farmasi','RataPelayananController@layanFarmasi')->name('layanFarmasi');
				Route::get('/layan-admisi-farmasi','RataPelayananController@layanAdmisiFarmasi')->name('layanAdmisiFarmasi');
				Route::post('/tampilkan-rata-pelayanan','RataPelayananController@tampilkan')->name('tampilkanRataPelayanan');
			});
			// RATA" PELAYANAN END
			// GRAFIK PELAYANAN START
			Route::group(['prefix'=>'grafik-pelayanan'],function(){
				Route::get('/','GrafikPelayananController@main')->name('mainGrafikPelayanan');
				Route::post('/tampilkan-grafik','GrafikPelayananController@tampilkan')->name('tampilkanGrafik');
				Route::get('/grafik-jumlah-pasien','GrafikPelayananController@jumlah')->name('grafikJumlahPasien');
			});

			Route::group(['prefix'=>'pasien'],function(){
				Route::get('/loket','ListAntrianController@main')->name('listAntrian');
				Route::get('/loket-geriatri','ListAntrianController@mainGeriatri')->name('listAntrianGeriatri');
				Route::post('/getAntrianLoket','ListAntrianController@getAntrianLoket')->name('getAntrianLoket');
				Route::post('/getAntrianLoketGeriatri','ListAntrianController@getAntrianLoketGeriatri')->name('getAntrianLoketGeriatri');
				Route::get('/form-list-antrian/{id}','ListAntrianController@formList')->name('formListAntrian');
				Route::post('/save-list-antrian','ListAntrianController@saveList')->name('saveListAntrian');

				// Loket Start
				Route::post('/batal-antrian','ListAntrianController@batalAntrian')->name('batalAntrian');
				Route::post('/panggil-antrian','ListAntrianController@panggilAntrian')->name('panggilAntrian');
				Route::post('/cetak-rmantrian','ListAntrianController@cetakRMAntrian')->name('cetakRMAntrian');
				Route::post('/kerjakan-antrian','ListAntrianController@kerjakanAntrian')->name('kerjakanAntrian');
				Route::post('/dataGridLoket','ListAntrianController@dataGridLoket')->name('dataGridLoket');
				Route::post('/cariDataPasien','ListAntrianController@cariDataPasien')->name('cariDataPasien');
				Route::post('/cariFormPasien','ListAntrianController@cariFormPasien')->name('cariFormPasien');
				Route::post('/getDatapasien','ListAntrianController@getDatapasien')->name('getDatapasien');
				// Loket End

				// Counter Start
				Route::get('/counter','AntrianController@formListCounter')->name('formListCounter');
				Route::post('/detail-counter','AntrianController@detailListCounter')->name('detailListCounter');
				Route::post('/reset-counter','AntrianController@resetAntrianCounter')->name('resetCounter');
				Route::post('/generate-counter','AntrianController@generateAntrianCounter')->name('generateAntrianCounter');

				// Arahkan Start
				Route::post('/counterToPoli','AntrianController@counterToPoli')->name('counterToPoli');
				Route::post('/loketToCounter','AntrianController@loketToCounter')->name('loketToCounter');
				Route::post('/loketToPoli','AntrianController@loketToPoli')->name('loketToPoli');
				Route::post('/poliToFarmasi','AntrianController@poliToFarmasi')->name('poliToFarmasi');
				// Arahkan End

				Route::post('/pasienSelesai','AntrianController@pasienSelesai')->name('pasienSelesai');

				// Poli Start
				Route::get('/poli','AntrianController@listPoli')->name('listAntrianPoli');
				Route::post('/panggilPoli','AntrianController@panggilPoli')->name('panggilPoli');
				Route::post('/dataGridPoli','AntrianController@dataGridPoli')->name('dataGridPoli');
				// Poli End

				// Farmasi Start
				Route::get('/farmasi','AntrianController@listAntrianFarmasi')->name('listAntrianFarmasi');
				Route::post('/panggilFarmasi','AntrianController@panggilFarmasi')->name('panggilFarmasi');
				Route::post('/selesaiFarmasi','AntrianController@selesaiFarmasi')->name('selesaiFarmasi');
				Route::post('/dataGridFarmasi','AntrianController@dataGridFarmasi')->name('dataGridFarmasi');
				// Farmasi End
			});

			// Device
			Route::group(['prefix'=>'operasi'], function(){
				Route::get('/','OperasiController@main')->name('operasi');
				Route::post('datagrid','OperasiController@datagrid')->name('operasiDatagrid');
				Route::post('/form', 'OperasiController@formAdd')->name('formOperasi');
				Route::post('/store', 'OperasiController@store')->name('storeOperasi');
				Route::post('/formPasien', 'OperasiController@formPasien')->name('formPasienOperasi');
				Route::post('/formPoli', 'OperasiController@formPoli')->name('formPoliOperasi');
				Route::post('/formDPJP', 'OperasiController@formDPJP')->name('formDPJPOperasi');
				Route::post('/ubahStatusOP', 'OperasiController@ubahStatusOP')->name('ubahStatusOP');

			});
		});

		Route::get('/', 'DashboardController@indexAdmin')->name('dashboardAdmin');

		Route::get('/identitas', 'IdentitasController@identitas')->name('identitas');
		Route::post('/identitas/changeIdentity', 'IdentitasController@changeIdentity')->name('changeIdentity');
		Route::group(array('prefix'=>'web'), function(){
			Route::group(array('prefix'=>'logo'), function(){
				Route::get('/', 'AdminController@logo')->name('logo');
				Route::post('/update', 'AdminController@formUpdateLogo')->name('formUpdateLogo');
				Route::post('/doupdate', 'AdminController@UpdateLogo')->name('UpdateLogo');
			});
			Route::group(array('prefix'=>'fotohome'), function(){
				Route::get('/', 'AdminController@fotohome')->name('fotohome');
				Route::post('/updatefotohome', 'AdminController@formUpdatefotohome')->name('formUpdatefotohome');
				Route::post('/doupdatefotohome', 'AdminController@Updatefotohome')->name('Updatefotohome');
			});
		});

		Route::group(array('prefix'=>'pengguna'), function(){
			Route::group(array('prefix'=>'editor'), function(){
				Route::get('/', 'AdminController@editor')->name('editor');
				Route::post('/datagrid', 'AdminController@editorDatagrid')->name('editorDatagrid');
				Route::post('/add', 'AdminController@formAddEditor')->name('formAddEditor');
				Route::post('/doAdd', 'AdminController@AddEditor')->name('AddEditor');
				Route::post('/update', 'AdminController@formUpdateEditor')->name('formUpdateEditor');
				Route::post('/doUpdate', 'AdminController@UpdateEditor')->name('UpdateEditor');
				Route::post('/resetSandi', 'AdminController@resetSandiEditor')->name('resetSandiEditor');
			});
		});

		//antrian
		Route::group(['prefix'=>'antrian'], function(){
			Route::get('/','AdminController@antrianmain')->name('antrian');
			Route::post('getpasien','AdminController@getpasien')->name('getpasien'); //get data pasien RM
		});

		// RIWAYAT ANTRIAN
		Route::group(['prefix'=>'riwayat-antrian'], function(){
			Route::get('/','RiwayatAntrianController@index')->name('riwayatAntrian');
			Route::post('/riwayat-antrian-list','RiwayatAntrianController@getRiwayatAntrian')->name('riwayat-antrian-list');
		});

		// PROFIL PASIEN
		Route::group(['prefix'=>'profil-pasien'], function(){
			Route::get('/','ProfilPasienController@index')->name('profilPasien');
			Route::post('/getProfilPasien','ProfilPasienController@getProfilPasien')->name('getProfilPasien');
			Route::post('/simpanProfilPasien','ProfilPasienController@simpanProfil')->name('simpanProfilPasien');
			Route::post('/generateNoRm','ProfilPasienController@generateNoRm')->name('generateNoRm');
			Route::post('/viewProfilPasien','ProfilPasienController@view')->name('viewProfilPasien');
		});

		// RIWAYAT ANTRIAN KONTER POLI
		Route::group(['prefix'=>'riwayat-antrian-konterpoli'], function(){
			Route::get('/','RiwayatAntrianKonterPoliController@main')->name('riwayatAntrianKonter');
			Route::post('/riwayat-antrian-konterpol','RiwayatAntrianKonterPoliController@riwayatAntrianKonterPoli')->name('riwayatAntrianKonterPoli');
		});

		///// APM
		Route::group(['prefix'=>'apm'], function(){
			Route::get('/','ApmController@main')->name('apm');
			Route::post('carifromantrianrs','ApmController@carifromantrianrs')->name('carifromantrianrs');
			Route::post('cariAntrianApmrs','ApmController@cariAntrianApmrs')->name('cariAntrianApmrs');
			Route::post('prosesAntrianUmum','ApmController@prosesAntrianUmum')->name('prosesAntrianUmum');
		});


		Route::group(['prefix'=>'bridging'], function(){
			Route::get('/','BridgingController@main')->name('bridging');
			Route::post('cekstatuspeserta','BridgingController@cekpeserta')->name('cekpeserta'); //cek peserta bpjs
			Route::post('ceksep','BridgingController@ceksep')->name('ceksep'); // cek sep
			Route::post('destroySEP','BridgingController@destroySEP')->name('destroySEP'); // cek sep
			Route::post('cekrujukan','BridgingController@cekrujukan')->name('cekrujukan');
			Route::post('getDokterDpjp','BridgingController@getDokterDpjp')->name('getDokterDpjp');
			Route::post('getNoSurat','BridgingController@getNoSurat')->name('getNoSurat');

 			Route::post('deletesep','BridgingController@deletesep')->name('deletesep'); // delete sep
 			Route::post('insertsep','BridgingController@insertsep')->name('insertsep'); // insert sep
 			Route::post('getpasienrs','BridgingController@getpasienrs')->name('getpasienrs'); // insert sep
 			Route::post('cetaksep','BridgingController@cetaksepservice')->name('cetak_sep'); // Cetak SEP
 			// cetak Ulang
 			Route::post('getregister','BridgingController@getregister')->name('getregister');
			Route::group(['prefix'=>'persetujuan-sep'], function(){
				Route::get('/','BridgingController@main_persetujuan_sep')->name('persetujuanSEP');
				Route::post('create_persetujuan_sep','BridgingController@create_persetujuan_sep')->name('create_persetujuan_sep'); // cek sep
				Route::post('simpanPengajuanSEP','BridgingController@simpanPengajuanSEP')->name('simpanPengajuanSEP'); // cek sep
				Route::post('get_list_pengajuan','BridgingController@get_list_pengajuan')->name('get_list_pengajuan'); // cek sep
				Route::post('aproval_pengajuan_sep','BridgingController@aproval_pengajuan_sep')->name('aproval_pengajuan_sep'); // cek sep
			});
 			Route::post('cariSEP','BridgingController@cariSEP')->name('cariSEP');
 			Route::post('updateSEP','BridgingController@updateSEP')->name('updateSEP');
 			Route::post('carippk','BridgingController@carippk')->name('carippk');
 			Route::post('getFaskes','BridgingController@getFaskes')->name('getFaskes');
 			Route::post('caridpjp','BridgingController@caridpjp')->name('caridpjp');
 			Route::post('getDPJP','BridgingController@getDPJP')->name('getDPJP');
 			Route::post('cariLokasiLaka','BridgingController@cariLokasiLaka')->name('cariLokasiLaka');
 			Route::post('getLokasiLaka','BridgingController@getLokasiLaka')->name('getLokasiLaka');
 			Route::post('caridpjp','BridgingController@caridpjp')->name('caridpjp');
 			Route::post('cekSkdp','BridgingController@cekSkdp')->name('cekSkdp');
 			Route::post('cariSuplesi','BridgingController@cariSuplesi')->name('cariSuplesi');
 			Route::post('getSuplesi','BridgingController@getSuplesi')->name('getSuplesi');
 			Route::post('cariSEPInternal','BridgingController@cariSEPInternal')->name('cariSEPInternal');
 			Route::post('deleteSEPInternal','BridgingController@deleteSEPInternal')->name('deleteSEPInternal');
			Route::post('cariHistorySEP','BridgingController@cariHistorySEP')->name('cariHistorySEP');
			Route::post('countRujukan','BridgingController@countRujukan')->name('countRujukan');
		});

		Route::group(['prefix'=>'vclaim'], function(){
			Route::group(['prefix'=>'rujukan'], function(){
				Route::get('/','VclaimController@main_form_rujukan')->name('rujukan');
				Route::post('/create','VclaimController@create_form_rujukan')->name('createRujukan');
				Route::post('/form-ppkrujukan','VclaimController@form_ppk_rujukan')->name('formPPKRujukan');
				Route::get('/cek_ppk_rujukan','VclaimController@cek_ppk_rujukan')->name('cek_ppk_rujukan');
				Route::post('/cek_list_spesialistik_rujukan','VclaimController@cek_list_spesialistik_rujukan')->name('cek_list_spesialistik_rujukan');
				Route::post('/cek_list_sarana_rujukan','VclaimController@cek_list_sarana_rujukan')->name('cek_list_sarana_rujukan');
				Route::post('/cek_rujukan_sep','VclaimController@cek_rujukan_sep')->name('cek_rujukan_sep');
				Route::get('/cek_diagnosa','VclaimController@cek_diagnosa')->name('cek_diagnosa');
				Route::post('/insert_rujukan','VclaimController@insert_rujukan')->name('insert_rujukan');
				Route::post('/get_list_rujukan','VclaimController@get_list_rujukan')->name('get_list_rujukan');
				Route::post('/update_rujukan','VclaimController@update_rujukan')->name('update_rujukan');
				Route::post('/hapus_rujukan','VclaimController@hapus_rujukan')->name('hapus_rujukan');
				Route::post('/cetak_rujukan','VclaimController@cetak_rujukan')->name('cetak_rujukan');
			});

			Route::group(['prefix'=>'rujukan-khusus'], function(){
				Route::get('/','VclaimController@main_form_rujukan_khusus')->name('rujukan-khusus');
				Route::post('/cek_rujukan_khusus','VclaimController@cek_rujukan_khusus')->name('cek_rujukan_khusus');
				Route::post('/create','VclaimController@create_form_rujukan_khusus')->name('createRujukanKhusus');
				Route::get('/cek_procedure','VclaimController@cek_procedure')->name('cek_procedure');
				Route::post('/storeRujukanKhusus','VclaimController@storeRujukanKhusus')->name('storeRujukanKhusus');
			});


			Route::group(['prefix'=>'rencana-kontrol'], function(){
				Route::get('/','VclaimController@main_rencana_kontrol')->name('rencana-kontrol');
				Route::post('/rencana-kontrol-search','VclaimController@cari_rencana_kontrol')->name('rencana-kontrol-search');
				Route::post('/storeRencanaKontrol','VclaimController@storeRencanaKontrol')->name('storeRencanaKontrol');
				Route::post('/list-rencana-kontrol-search','VclaimController@cari_list_rencana_kontrol')->name('list-rencana-kontrol-search');
				Route::post('/JadwalRujKontrol','VclaimController@JadwalRujKontrol')->name('JadwalRujKontrol');
				Route::post('/cariPoliRujKontrol','VclaimController@cariPoliRujKontrol')->name('cariPoliRujKontrol');
				Route::post('/cariDokterRujKontrol','VclaimController@cariDokterRujKontrol')->name('cariDokterRujKontrol');
				Route::post('/destroyRencanaKontrol','VclaimController@destroyRencanaKontrol')->name('destroyRencanaKontrol');
			});

			Route::group(['prefix'=>'rujuk-balik'], function(){
				Route::get('/','VclaimController@main_form_prb')->name('rujuk-balik');
				Route::post('/create','VclaimController@create_form_prb')->name('createPrb');
				Route::get('/cek_diagnosaprb','VclaimController@cek_diagnosaprb')->name('cek_diagnosaprb');
				Route::post('/cek_dokter_dpjp','VclaimController@cek_dokter_dpjp')->name('cek_dokter_dpjp');
				Route::post('/cek_obat_prb','VclaimController@cek_obat_prb')->name('cek_obat_prb');
				Route::post('/storePrb','VclaimController@storePrb')->name('storePrb');
				Route::post('/listPRB','VclaimController@listPRB')->name('listPRB');
				Route::post('/updatePrb','VclaimController@updatePrb')->name('updatePrb');
				Route::post('/hapusPrb','VclaimController@hapusPrb')->name('hapusPrb');
				Route::post('/cetakPrb','VclaimController@cetakPrb')->name('cetakPrb');
			});
		});

		Route::group(['prefix' => 'pasien'], function(){
 			//////// form cari peserta
 			Route::post('carifromrs','BridgingController@carifromrs')->name('carifromrs'); // get form cari peserta
 			Route::post('caripasienrs','BridgingController@caripasienrs')->name('caripasienrs'); // get peserta

 			//////// form cari peserta
 			Route::post('cariformrmrs','BridgingController@cariformrmrs')->name('cariformrmrs'); // get form cari peserta
 			Route::post('carirmrs','BridgingController@carirmrs')->name('carirmrs'); // get peserta

 			//////// form cari poli
 			Route::post('cariformpolirs','BridgingController@cariformpolirs')->name('cariformpolirs'); // insert sep
 			Route::post('caripolirs','BridgingController@caripolirs')->name('caripolirs'); // insert sep
			Route::post('getpolinama','BridgingController@getpolinama')->name('getpolinama'); // insert sep

 			/////// form diagnosa
 			Route::post('carifromdiagnosars','BridgingController@carifromdiagnosars')->name('carifromdiagnosars'); // Form Cari dianosa
 			Route::post('cariDiagnosars','BridgingController@cariDiagnosars')->name('cariDiagnosars'); // Form Cari dianosa

 			///// update No. BPJS di database RM
 			Route::post('changeNokartu','BridgingController@changeNokartu')->name('changeNokartu');

 			// get history pasien
 			Route::post('gethistorypasien','BridgingController@gethistorypasien')->name('gethistorypasien');

 			//get form update tanggal pulang
 			Route::post('formupdatetglpulang','BridgingController@formupdatetglpulang')->name('formupdatetglpulang'); // Form Cari update tgl pulang
 			Route::post('saveChange','BridgingController@saveChange')->name('saveChange'); // Form Cari update tgl pulang
		});

		// User
		Route::group(['prefix'=>'user'], function(){
			Route::get('/','UsersController@main')->name('users');
			Route::post('datagrid','UsersController@datagrid')->name('usersDatagrid');
			Route::post('formAdd','UsersController@formAdd')->name('formAddUsers');
			Route::post('AddUsers','UsersController@AddUsers')->name('AddUsers');
			Route::post('formUpdate','UsersController@formUpdate')->name('formUpdateUsers');
			Route::post('UpdateUsers','UsersController@UpdateUsers')->name('UpdateUsers');
			Route::post('resetSandi','UsersController@resetSandi')->name('resetSandiUsers');
			Route::post('delete','UsersController@delete')->name('deleteUsers');
		});

		// Tanggal Libur
		Route::group(['prefix'=>'tanggal-libur'], function(){
			Route::get('/','HolidayController@main')->name('holiday');
			Route::post('datagrid','HolidayController@datagrid')->name('holidayDatagrid');
			Route::post('formAdd','HolidayController@formAdd')->name('formAddHoliday');
			Route::post('Add','HolidayController@Add')->name('AddHoliday');
			Route::post('formUpdate','HolidayController@formUpdate')->name('formUpdateHoliday');
			Route::post('Updates','HolidayController@Updates')->name('UpdateHoliday');
			Route::post('delete','HolidayController@delete')->name('deleteHoliday');
		});

		// Informasi Penyakit
		Route::group(['prefix'=>'informasi-penyakit'], function(){
			Route::get('/','InfoPenyakitController@main')->name('InfoPenyakit');
			Route::post('datagrid','InfoPenyakitController@datagrid')->name('InfoPenyakitDatagrid');
			Route::post('formAdd','InfoPenyakitController@formAdd')->name('formAddInfoPenyakit');
			Route::post('Add','InfoPenyakitController@Add')->name('AddInfoPenyakit');
			Route::post('detail','InfoPenyakitController@detail')->name('detailInfoPenyakit');
			Route::post('formUpdate','InfoPenyakitController@formUpdate')->name('formUpdateInfoPenyakit');
			Route::post('Updates','InfoPenyakitController@Updates')->name('UpdateInfoPenyakit');
			Route::post('delete','InfoPenyakitController@delete')->name('deleteInfoPenyakit');
		});

		// Pola Hidup Sehat
		Route::group(['prefix'=>'pola-hidup-sehat'], function(){
			Route::get('/','PolaHidupController@main')->name('PolaHidup');
			Route::post('datagrid','PolaHidupController@datagrid')->name('PolaHidupDatagrid');
			Route::post('formAdd','PolaHidupController@formAdd')->name('formAddPolaHidup');
			Route::post('Add','PolaHidupController@Add')->name('AddPolaHidup');
			Route::post('detail','PolaHidupController@detail')->name('detailPolaHidup');
			Route::post('formUpdate','PolaHidupController@formUpdate')->name('formUpdatePolaHidup');
			Route::post('Updates','PolaHidupController@Updates')->name('UpdatePolaHidup');
			Route::post('delete','PolaHidupController@delete')->name('deletePolaHidup');
		});

		// Estimasi Pelayanan
		Route::group(['prefix'=>'estimasi-pelayanan'], function(){
			Route::get('/','EstimasiPelayananController@main')->name('EstimasiPelayanan');
			Route::post('datagrid','EstimasiPelayananController@datagrid')->name('EstimasiPelayananDatagrid');
			Route::post('formAdd','EstimasiPelayananController@formAdd')->name('formAddEstimasiPelayanan');
			Route::post('Add','EstimasiPelayananController@Add')->name('AddEstimasiPelayanan');
			Route::post('tampil','EstimasiPelayananController@tampil')->name('tampilEstimasiPelayanan');
			Route::post('formUpdate','EstimasiPelayananController@formUpdate')->name('formUpdateEstimasiPelayanan');
			Route::post('Updates','EstimasiPelayananController@Updates')->name('UpdateEstimasiPelayanan');
			Route::post('delete','EstimasiPelayananController@delete')->name('deleteEstimasiPelayanan');
		});

		// Master Poli
		Route::group(['prefix'=>'master-poli'], function(){
			Route::get('/','MstPoliController@index')->name('masterpoli');
			Route::post('/mstpoli-list','MstPoliController@listMstPoli')->name('mstpoli-list');
			Route::post('/form','MstPoliController@form')->name('mstpoli-form');
			Route::post('/store','MstPoliController@store')->name('mstpoli-store');
		});

		// Master Konter Poli
		Route::group(['prefix'=>'mst-konterpoli'], function(){
			Route::get('/','MstKonterPoliController@index')->name('mstkonterpoli');
			Route::post('/datagrid','MstKonterPoliController@datagrid')->name('mstkonterpoli-list');
			Route::post('/form','MstKonterPoliController@form')->name('mstkonterpoli-form');
			Route::post('/get-poli','MstKonterPoliController@getPoli')->name('get-poli');
			Route::post('/delete-poli','MstKonterPoliController@deletePoli')->name('delete-poli');
			Route::post('/edit-form','MstKonterPoliController@editForm')->name('mstkonterpoli-editForm');
			Route::post('/store','MstKonterPoliController@store')->name('mstkonterpoli-store');
		});

		// Device
		Route::group(['prefix'=>'device'], function(){
			Route::get('/','DeviceController@main')->name('Device');
			Route::post('datagrid','DeviceController@datagrid')->name('DeviceDatagrid');
			Route::post('accepteds','DeviceController@accepteds')->name('AccDevice');
			Route::post('blockeds','DeviceController@blockeds')->name('BlockDevice');
			Route::post('reset','DeviceController@reset')->name('ResetDevice');
			Route::post('delete','DeviceController@delete')->name('deleteDevice');
		});

		// Kotan Saran
		Route::group(['prefix'=>'kotak-saran'], function(){
			Route::get('/','KotakSaranController@main')->name('KotakSaran');
			Route::post('datagrid','KotakSaranController@datagrid')->name('KotakSaranDatagrid');
			Route::post('detail','KotakSaranController@detail')->name('detailKotakSaran');
			Route::post('delete','KotakSaranController@delete')->name('deleteKotakSaran');
		});

		// Informasi Penyakit
		Route::group(['prefix'=>'bantuan'], function(){
			Route::get('/','BantuanController@main')->name('bantuan');
			Route::post('datagrid','BantuanController@datagrid')->name('bantuanDatagrid');
			Route::post('formAdd','BantuanController@formAdd')->name('formAddbantuan');
			Route::post('Add','BantuanController@Add')->name('Addbantuan');
			Route::post('detail','BantuanController@detail')->name('detailbantuan');
			Route::post('formUpdate','BantuanController@formUpdate')->name('formUpdatebantuan');
			Route::post('Updates','BantuanController@Updates')->name('Updatebantuan');
			Route::post('delete','BantuanController@delete')->name('deletebantuan');
		});

		//get kunjungan
		Route::post('getKunjungan','DashboardController@getkunjungan')->name('getkunjungan');

		// Data Register
		Route::group(['prefix'=>'register'], function(){
			Route::get('/','AdminController@register')->name('reg');
			Route::post('dataRegister','AdminController@dataRegister')->name('dataRegister');
			Route::post('detailregister','AdminController@detailregister')->name('detailregister');
			Route::post('delete','AdminController@deletereg')->name('deletereg');
		});

		// RFID
		Route::group(['prefix'=>'rfid'],function(){
			Route::get('/','RFIDController@index')->name('rfid');
			Route::post('/cek','RFIDController@cek')->name('cekRM');
			Route::post('/formAddRfid','RFIDController@formAddRfid')->name('formAddRfid');
			Route::post('/addRfid','RFIDController@addRfid')->name('addRfid');
			Route::post('/cetakRfid','RFIDController@cetakRfid')->name('cetakRfid');
			Route::get('/cetak/{id}','RFIDController@cetak');
		});
	});
	Route::get('profileAdmin', 'AdminController@profile')->name('profileAdmin');
	Route::post('formUbahPasswordAdmin', 'AdminController@form_ubah_password')->name('formUbahPasswordAdmin');
	Route::post('doUpdatePasswordAdmin', 'AdminController@ubah_password')->name('doUpdatePasswordAdmin');
});

Route::get('cekpeserta','DashboardController@cekcustomer'); //cek peserta bpjs

Route::get('mail',function(){
	// $ma = mail('iskand69@gmail.com','asd','asd','From: puskesmas');
	// if($ma){
	// 	echo 'berhasil';
	// }else{
	// 	echo 'gagal';
	// }
	// return $users = DB::connection('sqlsrv2')->select("SELECT * FROM tm_poli");
});

// Route::get('cekurut','BridgingController@insertsep')->name('cariformpolirs'); // insert sep

Route::group(['prefix'=>'api'], function(){
	Route::group(['prefix'=>'antreanBPJS'],function(){
		Route::any('refPoli','BridgBpjsController@refPoli');
		Route::any('refDokter','BridgBpjsController@refDokter');
		Route::any('refJadwalDokter','BridgBpjsController@refJadDok');
		Route::any('updateJadwalDokter','BridgBpjsController@updateJadDok');
		Route::any('antreanAdd','BridgBpjsController@antreanAdd');
		Route::any('updateWaktu','BridgBpjsController@updateWaktu');
		Route::any('batalAntrean','BridgBpjsController@batalAntrean');
		Route::any('getListTask','BridgBpjsController@getListTask');
		Route::any('dashboardPerTanggal','BridgBpjsController@dashboardPerTanggal');
		Route::any('dashboardPerBulan','BridgBpjsController@dashboardPerBulan');
	});

	Route::group(['prefix'=>'simapan'],function(){
		Route::post('poli','ApiSimapanController@getPoliKodeBPJS')->name('getPoliKodeBPJS');
		Route::any('kategori/pasien','ApiSimapanController@kategoriPasien')->name('kategoriPasien');
		Route::post('antrian/add','ApiSimapanController@antrianAdd')->name('antrianAdd');
		Route::post('cek-peserta','ApiSimapanController@cekBPJS')->name('cekBPJS');
		Route::post('cek-kode-unik','ApiSimapanController@cekKodeUnik')->name('cekKodeUnik');
		Route::post('generateQrCode','ApiSimapanController@generateQrCode')->name('generateQrCode');
		Route::post('konfirmasiAntrian','ApiSimapanController@konfirmasiAntrian')->name('konfirmasiAntrian');
		Route::post('cek-rujukan','ApiSimapanController@cekRujukan')->name('cekRujukan');
		Route::post('getNomorUrut','ApiSimapanController@nomorUrut')->name('getNomorUrut');
		Route::post('create-user','ApiSimapanController@createUser')->name('createUser');
		Route::post('refJadDok','ApiSimapanController@refJadDok')->name('refJadDok');
	});

	route::any('getPoli','APIAPMController@getPoli');
	route::any('cekIdentitas','APIAPMController@cekIdentitas');
	Route::group(['middleware'=>'apiAuth'], function(){
		route::any('ambilAntrian','APIAPMController@ambilAntrian');
		route::any('kotakSaran','APIAPMController@kotakSaran');
		route::any('getInfoPenyakit','APIAPMController@getInfoPenyakit');
		route::any('getPolaHidup','APIAPMController@getPolaHidup');
		route::any('getNoUrut','APIAPMController@getNoUrut');
		route::any('getDokterDpjp','APIAPMController@getDokterDpjp');
		route::any('getDiagnoseOnline','APIAPMController@getDiagnoseOnline');
		route::any('getTreatmentOnline','APIAPMController@getTreatmentOnline');
		route::any('simpanSobat','APIAPMController@simpanSobat');
		route::any('getRadiologiResult','APIAPMController@getRadiologiResult');
		route::any('getBed','APIAPMController@getBed');
		route::any('getReminder','APIAPMController@getReminder');
	});

	Route::group(['prefix'=>'siramaerm'],function(){
		Route::post('panggil','ApiSiramaErmController@panggil')->name('panggilPasien');
		Route::post('taskBpjs','ApiSiramaErmController@updateTaskBpjs')->name('taskBpjs');
		Route::post('updateTaskBpjsSirama','ApiSiramaErmController@updateTaskBpjsSirama')->name('updateTaskBpjsSirama');
		Route::post('antreanAddFarmasi','ApiSiramaErmController@antreanAddFarmasi')->name('antreanAddFarmasi');
	});
	
	Route::group(['prefix'=>'sirama'],function(){
		Route::post('panggil-sirama','ApiSiramaErmController@panggilSirama')->name('panggilSirama');
		Route::post('antreanAddFarmasi','ApiSiramaErmController@antreanAddFarmasi')->name('antreanAddFarmasi');
	});
	
	route::any('versi','APIAPMController@versiApp');
	route::any('addVersi','APIAPMController@addVersiApp');
	route::any('allVersi','APIAPMController@allVersiApp');
	Route::any('laborat','APILaboratController@main');

	Route::any('report','AaController@report');
	Route::group(['prefix'=>'cron'],function(){
		Route::post('random-jadwal-dokter', 'RegistrationController@jadwalDokterKiosk');
		Route::get('sync-jadwal-dokter','Cron\CronController@syncJadwalDokter');
	});
});

Route::get('refresh-csrf',function(){
	return csrf_token();
});
Route::get('trybrid','BridgingController@trymain');