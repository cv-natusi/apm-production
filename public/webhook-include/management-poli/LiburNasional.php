<?php
require('ManagementPoli.php');
class LiburNasional extends ManagementPoli{
	function liburNasional($request){
		return $request->all();
	}
}
// echo "\n\nTESTING\n\n";