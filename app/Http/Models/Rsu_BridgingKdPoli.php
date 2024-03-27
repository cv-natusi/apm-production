<?php

namespace app\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Libraries\Datagrid;

class Rsu_BridgingKdPoli extends Model
{
    protected $connection = 'dbrsudlain';
    protected $table = 'bridging_kd_poli';
    public $timestamps = false;
}