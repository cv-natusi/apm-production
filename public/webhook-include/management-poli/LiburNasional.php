<?php
include "../ManagementPoli.php";
class LiburNasional extends ManagementPoli{
	public static function liburNasional($request){
		return $request->all();
	}
}
// echo "\n\nTESTING\n\n";