<?php
namespace App\Helpers;
use Config;

class Env{
	public static function status(){
		return Config::get('setting.project.env');
	}
}