<?php

namespace app\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Libraries\Datagrid;

class Sep extends Model
{
    protected $table = 'tb_sep';
    protected $primaryKey = "nosep";
    public $timestamps = false;

/*    public static function getDokter($input)
    {
        $table  = 'exkul';
        $select = '*';
        
        $replace_field  = [
            ['old_name' => 'status_exku', 'new_name' => 'status_exkul'],
        ];

        $param = [
            'input'         => $input->all(),
            'select'        => $select,
            'table'         => $table,
            'replace_field' => $replace_field
        ];
        $datagrid = new Datagrid;
        $data = $datagrid->datagrid_query($param, function($data){
            return $data->where('status_exkul','!=','3')->where('kategori','1');
        });
        return $data;
    }*/
}
