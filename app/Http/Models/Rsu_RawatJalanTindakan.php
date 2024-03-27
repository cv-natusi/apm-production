<?php

namespace app\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Libraries\Datagrid_rsu;

class Rsu_RawatJalanTindakan extends Model
{
    protected $connection = 'dbrsud';
    protected $table = 'tr_rawatjalantindakan';
    public $timestamps = false;

}
