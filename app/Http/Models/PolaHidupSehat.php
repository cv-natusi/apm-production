<?php

namespace app\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Libraries\Datagrid;

class PolaHidupSehat extends Model
{
    protected $table = 'pola_hidup_sehat';
    protected $primaryKey = 'id_pola_hidup_sehat';
    public $timestamps = false;

    public static function getJson($input)
    {
        $table  = 'pola_hidup_sehat';
        $select = 'pola_hidup_sehat.*';
        
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