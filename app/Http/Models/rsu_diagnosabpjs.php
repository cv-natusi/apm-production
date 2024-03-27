<?php

namespace app\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Libraries\Datagrid;
use Sentinel;

class rsu_diagnosabpjs extends Model
{
    protected $connection = 'dbrsud';
    protected $table = 'tm_diagnosa_bpjs';
    public $timestamps = false;

}
