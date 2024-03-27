<?php

namespace app\Http\Models;

use Illuminate\Database\Eloquent\Model;

class AntrianFarmasi extends Model{
	protected $connection = 'mysql';
	protected $table = 'antrian_farmasi';
	protected $primaryKey = 'id_antrian_farmasi';
}
