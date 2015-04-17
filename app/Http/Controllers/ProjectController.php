<?php

namespace App\Http\Controllers;

use App\Services\API;
use \Request;
use \Session;

class ProjectController extends Controller {

	private $request = null;
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		$call = API::get('/projects', []);

		if ($call->error)
			return view('projects/welcome', array_merge(Session::all(), ['projects' => []]));

		return view('projects/welcome', array_merge(Session::all(), ['projects' => $call->response]));
	}

	public function dashboard($id) 
	{
		$call = API::get('/projects/'.$id, []);

		if ($call->error)
			return view('projects/dashboard', array_merge(Session::all(), ['project' => []]));

		return view('projects/dashboard', array_merge(Session::all(), ['project' => $call->response]));
	}

	public function tasks($id) 
	{		
		$call = API::get('projects/'.$id.'/tasks', []);

		if ($call->error)
			throw new Exception($call->error_message);

		return view('projects/tasks/all', array_merge(Session::all(), ['tasks' => $call->response]));
	}

	public function task($id, $t_id) 
	{
		$call = API::get('/tasks/'.$t_id, []);

		if ($call->error)
			throw new Exception($call->error_message);

		return view('projects/tasks/single', array_merge(Session::all(), ['task' => $call->response]));
	}
}
