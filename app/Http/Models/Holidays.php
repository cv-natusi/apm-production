<?php

namespace app\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Libraries\Datagrid;
use App\Http\Models\rsu_poli;

class Holidays extends Model{
	protected $table = 'holidays';
	protected $primaryKey = 'id_holiday';
	public $timestamps = false;
	protected $fillable = [
		'id_holiday',
		'tanggal',
		'keterangan',
		'hari',
		'is_hari',
		'is_active',
		'jam',
		'poli_id',
		'poli_bpjs_id',
		'kuota_kiosk',
		'kuota_wa',
		'kategori',
	];

	/**
	 * Get the poli that owns the Holidays
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function poli(){
		return $this->belongsTo(rsu_poli::class, 'poli_id', 'KodePoli');
	}


	public static function getJson($input){
		$table  = 'holidays';
		$select = 'holidays.*';
		
		$replace_field  = [
			// ['old_name' => 'image', 'new_name' => 'photo_user'],
		];
		
		$param = [
			'input'         => $input->all(),
			'select'        => $select,
			'table'         => $table,
			'replace_field' => $replace_field
		];
		$datagrid = new Datagrid;
		$data = $datagrid->datagrid_query($param, function($data){
			return $data->where('kategori', 'Libur Nasional');
		});
		return $data;
	}

	public static function getJsonKuotaPoli($input){
		$table  = 'holidays';
		$select = '*';
		
		$replace_field  = [
			// ['old_name' => 'image', 'new_name' => 'photo_user'],
		];
		
		$param = [
			'input'         => $input->all(),
			'select'        => $select,
			'table'         => $table,
			'replace_field' => $replace_field
		];
		$datagrid = new Datagrid;
		$data = $datagrid->datagrid_query($param, function($data){
			return $data->leftjoin('tm_poli', 'tm_poli.kode_poli', '=', 'holidays.poli_id')->where('kategori', 'Kuota Poli');
		});
		return $data;
	}

	public static function getJsonLiburPoli($input){
		$table  = 'holidays';
		$select = '*';
		
		$replace_field  = [
			// ['old_name' => 'image', 'new_name' => 'photo_user'],
		];
		
		$param = [
			'input'         => $input->all(),
			'select'        => $select,
			'table'         => $table,
			'replace_field' => $replace_field
		];
		$datagrid = new Datagrid;
		$data = $datagrid->datagrid_query($param, function($data){
			return $data->leftjoin('tm_poli', 'tm_poli.kode_poli', '=', 'holidays.poli_id')->where('kategori', 'Libur Poli');
		});
		return $data;
	}
}