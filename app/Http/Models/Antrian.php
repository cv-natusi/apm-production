<?php

namespace app\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Libraries\Datagrid;
use DB;

class Antrian extends Model{
	protected $connection = 'mysql';
	protected $table = 'antrian';
	protected $primaryKey = 'id';
	public $timestamps = true;

	public function asuransi(){
		return $this->belongsTo('App\Http\Models\Rsu_setupall','pembayaran_pasien','subgroups');
	}

	public function task_id(){
		return $this->hasMany('App\Http\Models\TaskId','kode_booking','kode_booking');
	}

	public function tm_customer(){
		return $this->belongsTo('App\Http\Models\rsu_customer','no_rm','KodeCust');
	}
	
	public function mapping_poli_bridging(){
		return $this->belongsTo('App\Http\Models\Rsu_Bridgingpoli','kode_poli','kdpoli');
	}

	public static function getTask(){
		return Antrian::has('task_id')->with('task_id')->get();
	}

	public static function getJsonPoli($input){
		$table = 'antrian as an';
		$select = 'an.kode_poli,an.tgl_periksa,an.no_antrian,an.jenis_pasien,an.kode_booking,an.status,'; // table antrian
		$select .= 'at.from,at.to,at.id_tracer,at.status_tracer,'; // table antrian_tracer
		$select .= 'tp.NamaPoli'; // table tm_poli

		$replace_field  = [
			['old_name' => 'statusAntrian', 'new_name' => 'an.status '],
		];

		$param = [
			'input'         => $input->all(),
			'select'        => $select,
			'table'         => $table,
			'replace_field' => $replace_field
		];
		$datagrid = new Datagrid;
		$data = $datagrid->datagrid_query($param, function($data){
			return $data
				->leftjoin('antrian_tracer as at','an.id','=','at.antrian_id')
				->join('db_simars.mapping_poli_bridging as mp','an.kode_poli','=','mp.kdpoli')
				->join('db_simars.tm_poli as tp','mp.kdpoli_rs','=','tp.KodePoli')
				->where('an.tgl_periksa',date('Y-m-d'))
				->where('at.to','poli')
				->whereIn('an.status',['antripoli','panggilpoli'])
				->orderBy('an.id');
		});
		return $data;
	}

	public static function getJsonLoket($input){
		$table = 'antrian as an';
		$select = 'an.kode_poli,an.tgl_periksa,an.no_antrian,an.jenis_pasien,an.kode_booking,an.status,an.metode_ambil,'; // table antrian
		$select .= 'tc.NamaCust,'; // table tm_customer
		$select .= 'tp.NamaPoli'; // table tm_poli

		$replace_field  = [
			['old_name' => 'statusAntrian', 'new_name' => 'an.status '],
			['old_name' => 'namaCust', 'new_name' => 'tc.NamaCust '],
		];

		$param = [
			'input'         => $input->all(),
			'select'        => $select,
			'table'         => $table,
			'replace_field' => $replace_field
		];
		$datagrid = new Datagrid;
		$data = $datagrid->datagrid_query($param, function($data){
			return $data
				->leftjoin('db_simars.tm_customer as tc','an.no_rm','=','tc.KodeCust')
				->join('db_simars.mapping_poli_bridging as poli', 'poli.kdpoli', '=', 'an.kode_poli')
				->join('db_simars.tm_poli as tp', 'tp.KodePoli', '=', 'poli.kdpoli_rs')
				->whereIn('an.status', ['panggil', 'belum'])
				->where('tgl_periksa', date("Y-m-d"))
				->orderBy('id', 'ASC');
		});
		return $data;
	}

	public static function getJsonFarmasi($input){
		$table = 'antrian as an';
		$select = 'an.kode_poli,an.tgl_periksa,an.no_antrian,an.jenis_pasien,an.kode_booking,an.status,'; // table antrian
		$select .= 'at.from,at.to,at.id_tracer,at.status_tracer,'; // table antrian_tracer
		$select .= 'tp.NamaPoli'; // table tm_poli

		$replace_field  = [
			['old_name' => 'statusAntrian', 'new_name' => 'an.status '],
		];

		$param = [
			'input'         => $input->all(),
			'select'        => $select,
			'table'         => $table,
			'replace_field' => $replace_field
		];
		$datagrid = new Datagrid;
		$data = $datagrid->datagrid_query($param, function($data){
			return $data
				->leftjoin('antrian_tracer as at','an.id','=','at.antrian_id')
				->join('db_simars.mapping_poli_bridging as mp','an.kode_poli','=','mp.kdpoli')
				->join('db_simars.tm_poli as tp','mp.kdpoli_rs','=','tp.KodePoli')
				->where('an.tgl_periksa',date('Y-m-d'))
				->where('at.to','poli')
				->whereIn('an.status',['antrifarmasi','panggilfarmasi'])
				->orderBy('an.id');
		});
		return $data;
	}
}
