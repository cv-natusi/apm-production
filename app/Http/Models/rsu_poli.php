<?php

namespace app\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Libraries\Datagrid;
use Sentinel;

class rsu_poli extends Model
{
    protected $connection = 'dbrsud';
    protected $table = 'tm_poli';
    protected $primaryKey = 'KodePoli';
    public $timestamps = false;

    public function mapping_poli_bridging(){
        return $this->hasOne('App\Http\Models\Rsu_Bridgingpoli','kdpoli_rs','KodePoli');
    }

    public function kode_awalan_poli(){
        return $this->hasOne('App\Http\Models\KodeAwalanPoli','kdpoli_rs','KodePoli');
    }

    public function tm_customer(){
		return $this->belongsTo('App\Http\Models\rsu_customer','no_rm','KodeCust');
	}

    public function trans_konter_poli()
    {
        return $this->belongsTo('App\Http\Models\TransKonterPoli','KodePoli','poli_id');
    }

    // public function mst_konterpoli(){
	// 	return $this->belongsTo('App\Http\Models\MstKonterPoli', 'kdpoli', 'id');
	// }
}
