<?php

namespace app\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Libraries\Datagrid;

class rsu_customer extends Model
{
    protected $connection = 'dbrsud';
    protected $table = 'tm_customer';
    protected $primaryKey = "KodeCust";
    public $timestamps = false;

    public function antrian(){
        return $this->hasMany('App\Http\Models\Antrian', 'KodeCust');
    }

    public function antrian_pasien_baru(){
        return $this->hasOne('App\Http\Models\AntPasienBaru','cust_id','cust_id');
    }
   /* public static function getDokter($input)
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
