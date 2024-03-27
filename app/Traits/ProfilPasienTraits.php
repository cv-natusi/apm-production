<?php

namespace App\Traits;

use Carbon\Carbon;

trait ProfilPasienTraits {

	public function generateTmCustomer($data,$nameFoto)
	{
		$data = (object) $data;
		$dataPost = [
			'KodeCust' => $data->no_rm,
			'NamaCust' => $data->nama_pasien,
			'NoKtp' => $data->no_ktp,
			'Tempat' => $data->tempat_lahir,
			'JenisKel' => $data->jenis_kelamin,
			'Agama' => $data->agama,
			'Pekerjaan' => $data->pekerjaan,
			'status' => $data->s_perkawinan,
			'warganegara' => $data->kewarganegaraan,
			'suku' => $data->sukuras,
			'goldarah' => $data->gol_darah,
			'umur' => Carbon::parse($data->tgl_lahir)->age,
			'asuransi' => $data->asuransi,
			'Alamat' => $data->alamat,
			'rt' => $data->rt,
			'rw' => $data->rw,
			'kelurahan' => $data->desa_id,
			'kecamatan' => $data->kecamatan_id,
			'Kota' => $data->kabupaten_id,
			'Telp' => $data->telepon,
			'Fax' => $data->fax,
			'email' => $data->email,
			'kodepos' => $data->kodepos,
			'FieldCust1' => !empty($data->noasuransi) ? $data->noasuransi : null,
			'TglLahir' => $data->tgl_lahir,
			'fotoCust' => $nameFoto, //menyusul
		];

		return $dataPost;
	}

	public function generateAntrianPasienBaru($data, $custId)
	{
		$data = (object) $data;
		$dataPost = [
			'cust_id' => $custId,
			'provinsi_id' => $data->provinsi_id,
			'kabupaten_id' => $data->kabupaten_id,
			'kecamatan_id' => $data->kecamatan_id,
			'desa_id' => $data->desa_id,
			'nik' => $data->no_ktp,
			'no_rm' => "0000000000",
			'pend_terakhir' => $data->pend_terakhir,
		];

		return $dataPost;
	}

}