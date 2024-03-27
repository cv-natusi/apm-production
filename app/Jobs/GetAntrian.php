<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;

class GetAntrian extends Job implements SelfHandling{
	/**
	* Create a new job instance.
	*
	* @return void
	*/
	public function __construct(){
		//
	}

	/**
	* Execute the job.
	*
	* @return void
	*/
	public function handle(){
		sleep(3);
		info('Hello');
	}
}
