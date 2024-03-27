<?php

namespace app\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Libraries\Datagrid;

class VclaimObatPrb extends Model
{
    protected $table = 'tb_obat_prb_vclaim';
    protected $primaryKey = 'id_rincian_obat';
    public $timestamps = false;
}
