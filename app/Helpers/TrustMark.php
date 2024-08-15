<?php
namespace App\Helpers;
use Config;

class TrustMark{
	public static function status(){
		return Config::get('setting.project.env');
	}
}