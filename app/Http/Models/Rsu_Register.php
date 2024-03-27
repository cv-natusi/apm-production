<?php

namespace app\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Libraries\Datagrid_rsu;

class Rsu_Register extends Model
{
    protected $connection = 'dbrsud';
    protected $table = 'tr_registrasi';
    protected $primaryKey = 'No_Register';
    public $timestamps = false;

    public static function getJson($input)
    {
        $table  = 'tr_registrasi';
        $select = '*';
        
        $replace_field  = [
            // ['old_name' => 'KodeCust', 'new_name' => 'device_apm.KodeCust'],
        ];

        $param = [
            'input'         => $input->all(),
            'select'        => $select,
            'table'         => $table,
            'replace_field' => $replace_field
        ];
        $datagrid = new Datagrid_rsu;
        $data = $datagrid->datagrid_query($param, function($data){
            return $data->where('NoSep','!=','')->orderby('Tgl_Register','desc');
        });
        return $data;
    }

}
