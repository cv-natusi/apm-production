<?php

namespace app\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Libraries\Datagrid;
use Sentinel;

class MstKonterPoli extends Model
{
    protected $table = 'mst_konterpoli';
    protected $primaryKey = 'id';
    public $timestamps = false;

    // public function tm_poli(){
    //     return $this->hasMany('App\Http\Models\rsu_poli', 'KodePoli');
    // }
    
    public function trans_konter_poli()
	{
		return $this->hasMany('App\Http\Models\TransKonterPoli','konter_poli_id');
	}
}


