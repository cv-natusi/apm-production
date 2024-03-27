<?php

namespace app\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Libraries\Datagrid;

class Rsu_cc extends Model
{
    protected $connection = 'dbrsudlain';
    protected $table = 'cc';
    public $timestamps = false;
}