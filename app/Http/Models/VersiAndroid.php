<?php

namespace app\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Libraries\Datagrid;

class VersiAndroid extends Model
{
    protected $table = 'versi_android';
    protected $primaryKey = 'id_versi';
    public $timestamps = false;

    public static function getJson($input)
    {
        $table  = 'versi_android';
        $select = 'versi_android.*';
        
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