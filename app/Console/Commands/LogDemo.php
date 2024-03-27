<?php

namespace App\Console\Commands;
use App\Http\Controllers\AntrianController;
use Illuminate\Console\Command;
use Illuminate\Http\Request;

class LogDemo extends Command{
	/**
	* The name and signature of the console command.
	*
	* @var string
	*/
	protected $signature = 'log:demo';
	
	/**
	* The console command description.
	*
	* @var string
	*/
	protected $description = 'This command will add one line to the log file.';
	
	/**
	* Create a new command instance.
	*
	* @return void
	*/
	public function __construct(){
		parent::__construct();
	}
	
	/**
	* Execute the console command.
	*
	* @return mixed
	*/
	public function handle(){
		$getAntrian = (new AntrianController)->getAntrian();
		return $getAntrian;
	}
}
