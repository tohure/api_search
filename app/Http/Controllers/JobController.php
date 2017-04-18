<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
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

	public function search(Request $request){
		$data = $request->all();

		$query = $data["query"];
		$location = $data["location"];

		if (($query != null && $query != "") && ($location == null || $location == "")) {
			$jobs = Job::where('title', $query)->orWhere('title', 'like', '%' . $query . '%')->get();
		}else if (($query == null || $query == "") &&  ($location != null && $location != "")){
			$jobs = Job::where('place', $location)->orWhere('place', 'like', '%' . $location . '%')->get();
		}else if (($query != null && $query != "") &&  ($location != null && $location != "")){
			$jobs = Job::where('title', $query)->orWhere('title', 'like', '%' . $query . '%')->where(function($q) use ($location)  {
		          $q->where('place', $location)->orWhere('place', 'like', '%' . $location . '%');
		      })->get();
		}

		if ($jobs) {
			return ['error' => 0, 'data' => $jobs];
		}else{
			return ['error' => 1, 'message' => "Bad Request"];
		}
	}
}
