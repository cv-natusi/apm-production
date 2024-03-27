<?php

namespace app\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Libraries\Datagrid;
use Sentinel;

class Apm extends Model
{
    protected $table = 'apm';
    protected $primaryKey = 'id_apm';
    public $timestamps = false;

    public function poli(){
        return $this->belongsTo('App\Http\Models\Poli','KodePoli');        
    }

}
