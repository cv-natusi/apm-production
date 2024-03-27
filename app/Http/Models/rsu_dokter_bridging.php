<?php

namespace app\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Libraries\Datagrid;

class rsu_dokter_bridging extends Model
{
    protected $connection = 'dbrsud';
    protected $table = 'dokter_bridg';
    protected $primaryKey = "id";
    public $timestamps = false;
}
