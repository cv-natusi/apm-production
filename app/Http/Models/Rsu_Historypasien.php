<?php

namespace app\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Rsu_Historypasien extends Model
{
    protected $connection = 'dbrsud';
    protected $table = 'tr_tracer';
    public $timestamps = false;

}
