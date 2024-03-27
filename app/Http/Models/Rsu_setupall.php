<?php

namespace app\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Libraries\Datagrid;

class Rsu_setupall extends Model
{
	protected $connection = 'dbrsud';
    protected $table = 'tm_setupall';
    public $timestamps = false;

}
