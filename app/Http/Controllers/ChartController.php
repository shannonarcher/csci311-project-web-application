<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\API;
use \Session;
use \Exception;

class ChartController extends Controller {

	private $request = null;
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(Request $request)
	{
		parent::__construct();
		$this->request = $request;
	}

	public function gannt($id) 
	{
		$call = API::get("/projects/$id/tasks", []);
		$proper_format = [];

		$tasks = $call->response;

		$proper_format['data'] = [];
		$proper_format['links'] = [];
		$i = 0;
		foreach ($tasks as $task) {
			$start_date = new \DateTime($task->started_at);

			$pf_task = [
				'id' => $task->id,
				'text' => $task->title,
				'start_date' => $start_date->format('d-m-Y'),
				'duration' => $task->estimation_duration / 86400,
				'order' => 0,
				'progress' => $task->progress / 100,
				'open' => ($task->progress == 100)
			];

			foreach ($task->dependencies as $dep) {
				$i++;
				$proper_format['links'][] = [
					'id' => $i,
					'source' => $dep->id,
					'target' => $task->id,
					'type' => "0"
				];
			}

			if ($task->parent_id != null) {
				$pf_task['parent'] = $task->parent_id;
			}

			$proper_format['data'][] = $pf_task;
		}

		return view('charts/gannt', array_merge(Session::all(), ['tasks' => $proper_format]));
	}
}
