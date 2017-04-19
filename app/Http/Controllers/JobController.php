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

	public function jobDetail(Request $request) {
		$data = $request->all();

		$idjob = $data["idjob"];

		$jobs = Job::find($idjob);

		if ($jobs) {
			return ['error' => 0, 'data' => $jobs];
		}else{
			return ['error' => 1, 'message' => "Bad Request"];
		}

	}

	public function recomendados(Request $request) {
		$data = $request->all();

		$tag = $data["tag"];

		$jobs = Job::where('status', '1')->get();
		$recom_jobs = array();

		foreach ($jobs as $job) {
			$pre_keywords = $job["keywords"];
			$keywords = explode(",",$pre_keywords);
			if (in_array($tag, $keywords)) {
			    array_push($recom_jobs, $job);
			}
		}

		if ($recom_jobs) {
			return ['error' => 0, 'data' => $recom_jobs];
		}else{
			return ['error' => 1, 'message' => "Bad Request"];
		}
	}
}
