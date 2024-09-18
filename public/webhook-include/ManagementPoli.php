<?php
namespace Webhook;
require_once "webhook-include/Helper.php";
use Webhook\Helper;
class ManagementPoli{
	protected static $namaHari;

	public function __construct($request){
      Helper::dateDetail($request); # Add variable to request object
	}
}