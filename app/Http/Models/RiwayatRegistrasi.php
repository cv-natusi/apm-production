<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatRegistrasi extends Model
{
	protected $table = 'riwayat_registrasi';
	protected $primaryKey = 'id_riwayat_regis';
	public $timestamps = false;

	public function registrasi(){
		return $this->belongsTo('App\Http\Models\Register','No_Register');        
	}
}
