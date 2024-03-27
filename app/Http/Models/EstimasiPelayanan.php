<?php

namespace app\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Libraries\Datagrid_rsudlain;

class EstimasiPelayanan extends Model
{
    protected $connection = 'dbrsudlain';
    protected $table = 'layanan';
    protected $primaryKey = 'kodepoli';
    public $timestamps = false;

    public static function getJson($input)
    {
        $table  = 'layanan';
        $select = 'layanan.*';
        
        $replace_field  = [
            // ['old_name' => 'image', 'new_name' => 'photo_user'],
        ];

        $param = [
            'input'         => $input->all(),
            'select'        => $select,
            'table'         => $table,
            'replace_field' => $replace_field
        ];

        $datagrid = new Datagrid_rsudlain;
        $data = $datagrid->datagrid_query($param, function($data){
            return $data;
        });
        return $data;
    }
}