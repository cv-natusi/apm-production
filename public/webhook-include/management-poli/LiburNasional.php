<?php
namespace Webhook\ManagementPoli;
use Webhook\ManagementPoli;

class LiburNasional extends ManagementPoli
{
	// public static function getData()
	// {
	// 	$curl = curl_init();

	// 	curl_setopt_array($curl, array(
	// 		CURLOPT_URL => '192.168.1.8:8191/api/member/add',
	// 		CURLOPT_RETURNTRANSFER => true,
	// 		CURLOPT_ENCODING => '',
	// 		CURLOPT_MAXREDIRS => 10,
	// 		CURLOPT_TIMEOUT => 0,
	// 		CURLOPT_FOLLOWLOCATION => true,
	// 		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	// 		CURLOPT_CUSTOMREQUEST => 'POST',
	// 		CURLOPT_POSTFIELDS => array(
	// 			'is_member' => '1',
	// 			'is_new_member' => '1',
	// 			'no_ktp' => '1111111111111112',
	// 			'no_bpjs' => '',
	// 			'no_rm' => '467227',
	// 			'nama' => 'TES TES TES',
	// 			'foto_identitas'=> new CURLFILE('/path/to/file'),
	// 			'foto_profile'=> new CURLFILE('/path/to/file'),
	// 			'gender' => '1',
	// 			'tgllahir' => '1997-06-06',
	// 			'status_hubungan' => 'lainnya',
	// 			'alamat' => 'tes2',
	// 			'kode_provinsi' => '35',
	// 			'kode_kabupaten' => '3525',
	// 			'kode_kecamatan' => '352510',
	// 			'id_tn' => '118',
	// 			'wa' => '0895320894992',
	// 			'email' => 'testestes2@gmail.com'
	// 		),
	// 		CURLOPT_HTTPHEADER => array(
	// 			'Authorization: Bearer 1078|ngvuMArVvxgoMqTT0ua2bz42HCbsEpV2cuXcafFh'
	// 		),
	// 	));
	// 	$response = curl_exec($curl);
	// 	curl_close($curl);
	// 	echo $response;

	// }
	// public static function liburNasional($request)
	// {
	// 	return 'Libur Nasional';
	// }
}