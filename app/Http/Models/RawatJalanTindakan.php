<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class RawatJalanTindakan extends Model
{
    protected $table = 'tr_rawatjalantindakan';
    protected $primaryKey = 'RwID';
    public $timestamps = false;
}
