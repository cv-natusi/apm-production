<?php

namespace app\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Libraries\Datagrid;

class Rsu_Rawatjalan extends Model
{
    protected $connection = 'dbrsud';
    protected $table = 'tm_rawatjalan';
    public $timestamps = false;

}
