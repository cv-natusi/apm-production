<?php

namespace app\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Libraries\Datagrid;

class Rsu_RiwayatRegistrasi extends Model
{
    protected $connection = 'dbrsudlain';
    protected $table = 'riwayat_registrasi';
	protected $primaryKey = 'id_riwayat_regis';
	public $timestamps = false;
}