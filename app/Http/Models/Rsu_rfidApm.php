<?php

namespace app\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Libraries\Datagrid;

class Rsu_rfidApm extends Model
{
    protected $connection = 'dbrsudlain';
    protected $table = 'rfid_apm';
    protected $primaryKey = 'id_rfid_apm';
    public $timestamps = false;
}