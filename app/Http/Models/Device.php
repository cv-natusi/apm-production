<?php

namespace app\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Libraries\Datagrid;

class Device extends Model
{
    protected $table = 'device_apm';
    protected $primaryKey = 'id_device';
    public $timestamps = false;

    public static function getJson($input)
    {
        $table  = 'device_apm';
        // $select = 'device_apm.*, NamaCust, Alamat';
        $select = 'device_apm.*';
        
        $replace_field  = [
            ['old_name' => 'KodeCust', 'new_name' => 'device_apm.KodeCust'],
        ];

        $param = [
            'input'         => $input->all(),
            'select'        => $select,
            'table'         => $table,
            'replace_field' => $replace_field
        ];
        $datagrid = new Datagrid;
        $data = $datagrid->datagrid_query($param, function($data){
            // return $data->leftjoin('tm_customer','tm_customer.KodeCust','=','device_apm.KodeCust');
            return $data;
        });
        return $data;
    }
}