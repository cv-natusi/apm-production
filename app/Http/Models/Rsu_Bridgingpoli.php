<?php

namespace app\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Libraries\Datagrid;

class Rsu_Bridgingpoli extends Model
{
	protected $connection = 'dbrsud';
    protected $table = 'mapping_poli_bridging';
    public $timestamps = false;

    public function antrian(){
        return $this->hasMany('App\Http\Models\Antrian', 'kdpoli');
    }

    public function tm_poli(){
        return $this->hasOne('App\Http\Models\rsu_poli','KodePoli','kdpoli_rs');
    } 

    public function kode_awalan_poli(){
        return $this->hasOne('App\Http\Models\KodeAwalanPoli','kdpoli_rs');
    }
}
