<?php

namespace app\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Datatables\Facades\Datatables;

class Filling extends Model
{
    protected $table = 'filling';
	protected $primaryKey = 'id';

    public function customer(){
		return $this->belongsTo('App\Http\Models\rsu_customer','no_rm','KodeCust');
	}
}
