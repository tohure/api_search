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

		$jobs = Job::where("id","<>",$data["idjob"])->where('status', '1')->get();
		$recom_jobs = array();
		$id_jobs = array();

		$pre_tags = explode(",",$data["tags"]);

		foreach ($jobs as $job) {
			$pre_keywords = $job["keywords"];
			$keywords = explode(",",$pre_keywords);
			foreach ($pre_tags as $pre_tag) {
				if (in_array($pre_tag, $keywords) && !in_array($job['id'],$id_jobs)) {
				    array_push($recom_jobs, $job);
				    array_push($id_jobs,$job["id"]);
				}
			}
		}

		if ($recom_jobs) {
			return ['error' => 0, 'data' => $recom_jobs];
		}else{
			return ['error' => 1, 'message' => "Bad Request"];
		}
	}
}
