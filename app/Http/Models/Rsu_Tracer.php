<?php

namespace app\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Libraries\Datagrid;

class Rsu_Tracer extends Model
{
	
    protected $connection = 'dbrsud';
    protected $table = 'tr_tracer';
    public $timestamps = false;

}
