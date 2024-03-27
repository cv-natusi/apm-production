<?php

namespace app\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    protected $connection = 'mysql';
    protected $table = 'mst_provinsi';
    protected $primaryKey = 'id';
}
