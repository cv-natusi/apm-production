<?php
namespace Webhook\ManagementPoli;
use Webhook\ManagementPoli;

class LiburNasional extends ManagementPoli{
	public function __construct(){
		
	}

	public static function liburNasional($request){
		return $request->all();
		return json_encode($request->all(), JSON_PRETTY_PRINT);
		return self::$namaHari;
	}
}