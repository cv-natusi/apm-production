<?php

namespace app\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Libraries\Datagrid;

class KodeAwalanPoli extends Model
{
    protected $table = 'kode_awalan_poli';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function tm_poli(){
        return $this->belongsTo('App\Http\Models\rsu_poli','kdpoli_rs','id');
    }
}
