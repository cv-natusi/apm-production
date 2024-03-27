<?php

namespace app\Http\Models;

use Illuminate\Database\Eloquent\Model;

class TransKonterPoli extends Model
{
    protected $table = 'trans_konter_poli';
    protected $primaryKey = 'id_trans_konter_poli';
    public $timestamps = false;

    public function mst_konterpoli()
    {
        return $this->belongsTo('App\Http\Models\MstKonterPoli', 'konter_poli_id', 'id');
    }

    public function tm_poli()
    {
        // return $this->hasOne('App\Http\Models\rsu_poli', 'KodePoli','id_trans_konter_poli');
        return $this->hasOne('App\Http\Models\rsu_poli', 'KodePoli','poli_id');
    }
}
