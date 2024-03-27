<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Models\TaskId;
use App\Http\Models\Antrian;

class TaskIdController extends Controller{
	public function store(Request $params){
		if(!TaskId::filter($params)){
			return TaskId::store($params);
		}
	}

	public function getTask(Request $request){
		return Antrian::getTask($request);
	}
}