<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class RfidApm extends Model
{
    protected $table = 'rfid_apm';
    protected $primaryKey = 'id_rfid_apm';
    public $timestamps = false;
}
