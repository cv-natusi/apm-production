<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class SettingJadwalPoli extends Model{
	protected $connection = 'mysql';
	protected $table = 'setting_jadwal_poli';
	protected $primaryKey = 'id';
	public $timestamps = true;
}