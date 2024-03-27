<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class AntPasienBaru extends Model
{
    protected $table = 'antrian_pasien_baru';
	protected $primaryKey = 'id';
	public $timestamps = false;
}
