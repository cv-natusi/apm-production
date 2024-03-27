<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class TaskId extends Model{
	protected $connection = 'mysql';
	protected $table = 'task_id';
	protected $primaryKey = 'id';
	public $timestamps = false;

	public function antrian(){
		return $this->belongsTo('App\Http\Models\Antrian','kode_booking','kode_booking');
	}

	public static function store($params){
		$data = new TaskId;
		$data->task_id        = $params->taskid;
		$data->kode_booking   = $params->kodebooking;
		$data->timestamp      = $params->waktu;
		$data->tanggal_insert = date('Y-m-d');
		$data->save();
		return $data;
	}

	public static function filter($params){
		$data = TaskId::where([
			'task_id'      => $params->taskid,
			'kode_booking' => $params->kodebooking
		])->first();
		return $data ? $data : false;
	}

	public static function getByRange($params){
		return TaskId::with('antrian')->get();
	}
}