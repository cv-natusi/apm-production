<?php
namespace Webhook\ManagementPoli;
use Webhook\ManagementPoli;

class LiburPoli extends ManagementPoli{
	public function __construct(){
		
	}

	public static function liburPoli($request){
		return $request->all();
		return json_encode($request->all(), JSON_PRETTY_PRINT);
		return self::$namaHari;
	}
}