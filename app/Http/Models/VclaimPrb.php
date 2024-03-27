<?php

namespace app\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Libraries\Datagrid;

class VclaimPrb extends Model
{
    protected $table = 'tb_prb_vclaim';
    protected $primaryKey = 'id_prb';
    public $timestamps = false;
}
