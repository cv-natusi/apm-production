<?php

namespace app\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Libraries\Datagrid;

class Bantuan extends Model
{
    protected $table = 'bantuan';
    protected $primaryKey = 'id_bantuan';
    public $timestamps = false;

    public static function getJson($input)
    {
        $table  = 'bantuan';
        $select = 'bantuan.*';
        
        $replace_field  = [
            // ['old_name' => 'image', 'new_name' => 'photo_user'],
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