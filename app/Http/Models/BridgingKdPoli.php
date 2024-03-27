<?php

namespace app\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Libraries\Datagrid;

class BridgingKdPoli extends Model
{
    protected $table = 'bridging_kd_poli';
    protected $primaryKey = 'id_bridging_kd_poli';
    public $timestamps = false;
}