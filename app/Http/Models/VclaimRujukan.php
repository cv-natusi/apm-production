<?php

namespace app\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Libraries\Datagrid;

class VclaimRujukan extends Model
{
    protected $table = 'tb_rujukan_vclaim';
    protected $primaryKey = 'id_rujukan';
    public $timestamps = false;
}
