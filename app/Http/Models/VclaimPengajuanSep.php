<?php

namespace app\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Libraries\Datagrid;

class VclaimPengajuanSep extends Model
{
    protected $table = 'tb_pengajuan_sep_vclaim';
    protected $primaryKey = 'id_pengajuan';
    public $timestamps = false;
}
