<?php

namespace app\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Libraries\Datagrid;

class Users extends Model
{
    protected $table = 'users';
    protected $fillable = ['id','email','password','level','permissions','last_login','name_user','alias','phone','address_user','photo_user','active','created_at','updated_at'];

    public static function getJsonEditor($input)
    {
        $table  = 'users';
        $select = 'users.*';
        
        $replace_field  = [
            ['old_name' => 'image', 'new_name' => 'photo_user'],
        ];

        $param = [
            'input'         => $input->all(),
            'select'        => $select,
            'table'         => $table,
            'replace_field' => $replace_field
        ];
        $datagrid = new Datagrid;
        $data = $datagrid->datagrid_query($param, function($data){
            return $data->where('level','2');
        });
        return $data;
    }

    public static function getJson($input)
    {
        $table  = 'users';
        $select = 'users.*';
        
        $replace_field  = [
            ['old_name' => 'image', 'new_name' => 'photo_user'],
        ];

        $param = [
            'input'         => $input->all(),
            'select'        => $select,
            'table'         => $table,
            'replace_field' => $replace_field
        ];
        $datagrid = new Datagrid;
        $data = $datagrid->datagrid_query($param, function($data){
            return $data->where('level','1');
        });
        return $data;
    }
}
