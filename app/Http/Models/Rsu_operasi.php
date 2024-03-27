<?php

namespace app\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Libraries\Datagrid;

class Rsu_Operasi extends Model
{
	
    protected $connection = 'dbrsudlain';
    protected $table = 'tm_operasi';
    public $timestamps = false;

       public static function getJson($input){
        $connection = 'dbrsudlain';
        $table  = 'tm_operasi';
        $select = '*';
        
        $replace_field  = [
            // ['old_name' => 'status_exku', 'new_name' => 'status_exkul'],
        ];

        $param = [
            'input'         => $input->all(),
            'select'        => $select,
            'table'         => $table,
            'replace_field' => $replace_field
        ];
        $datagrid = new Datagrid;
        $data = $datagrid->datagrid_query($param, function($data){
            return $data;
        });
        return $data;
    }

}
