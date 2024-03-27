<?php

namespace app\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Libraries\Datagrid;
use Sentinel;

class Poli extends Model
{
    protected $table = 'tm_poli';
    protected $primaryKey = 'KodePoli';
    public $timestamps = false;

}
