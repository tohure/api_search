<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Job;

class JobController extends Controller
{
	public function index() {
		$jobs = Job::where('status',"1")->get();

		if ($jobs) {
			return ['error' => 0, 'data' => $jobs];
		}else{
			return ['error' => 1, 'message' => "Bad Request"];
		}

	}

	public function search($query){
		$jobs = Job::where('title', $query)->orWhere('title', 'like', '%' . $query . '%')->get();

		if ($jobs) {
			return ['error' => 0, 'data' => $jobs];
		}else{
			return ['error' => 1, 'message' => "Bad Request"];
		}
	}
}
