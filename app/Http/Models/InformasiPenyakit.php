<?php

namespace app\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Libraries\Datagrid;

class InformasiPenyakit extends Model
{
    protected $table = 'informasi_penyakit';
    protected $primaryKey = 'id_informasi_penyakit';
    public $timestamps = false;

    public static function getJson($input)
    {
        $table  = 'informasi_penyakit';
        $select = 'informasi_penyakit.*';
        
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