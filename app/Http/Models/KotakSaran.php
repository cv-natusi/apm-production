<?php

namespace app\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Libraries\Datagrid;

class KotakSaran extends Model
{
    protected $table = 'kotak_saran';
    protected $primaryKey = 'id_kotak_saran';
    public $timestamps = false;

    public static function getJson($input)
    {
        $table  = 'kotak_saran';
        $select = 'kotak_saran.*, NamaCust, Alamat';
        
        $replace_field  = [
            ['old_name' => 'KodeCust', 'new_name' => 'kotak_saran.KodeCust'],
        ];

        $param = [
            'input'         => $input->all(),
            'select'        => $select,
            'table'         => $table,
            'replace_field' => $replace_field
        ];
        $datagrid = new Datagrid;
        $data = $datagrid->datagrid_query($param, function($data){
            return $data->leftjoin('tm_customer','tm_customer.KodeCust','=','kotak_saran.KodeCust');
        });
        return $data;
    }
}