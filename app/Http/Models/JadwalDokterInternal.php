<?php

namespace app\Http\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalDokterInternal extends Model{
	protected $connection = 'mysql';
	protected $table = 'jadwal_dokter_internal';
	protected $primaryKey = 'id';
	// protected $primaryKey = null;
	// public $incrementing = false;

	protected $fillable = [
		'id',
		'date',
		'is_active',
		'is_bpjs',
		'hari',
		'jam_praktek',
		'kapasitas_pasien',
		'kode_dokter',
		'kode_poli_bpjs',
		'kode_poli_rs',
		'nama_dokter',
		'status_pilih',
	];

	public $timestamps = false;

	public static function updateToTrue($request){
		return JadwalDokterInternal::where('id',$request->id)->update([
			'status_pilih' => true
		]);
	}
}
