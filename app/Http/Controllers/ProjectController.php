<?php

namespace App\Http\Controllers;

use App\Services\API;
use Illuminate\Http\Request;
use \Session;
use \Exception;

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

		if ($call->error) {
			throw new Exception($call->error_message);
		}

		return view('projects/welcome', array_merge(Session::all(), ['projects' => $call->response]));
	}

	public function add() 
	{
		$call = API::get('/users', []);

		if ($call->error) {
			throw new Exception($call->error_message);
		}

		return view('projects/add', array_merge(Session::all(), ['users' => $call->response]));
	}

	public function create() 
	{
		$call = API::post('/projects', $this->request->all());

		if ($call->error) {
			throw new Exception($call->error_message);
		}


		return redirect('projects/'.$call->response->id.'/dashboard');
	}

	public function edit($id) 
	{
		$call = API::get('/projects/'.$id, []);

		if ($call->error) {
			throw new Exception($call->error_message);
		}

		return view('projects/edit', array_merge(Session::all(), ['project' => $call->response]));
	}

	public function save($id) 
	{
		$call = API::put('/projects/'.$id, $this->request->all());

		if ($call->error) {
			throw new Exception($call->error_message);
		}
		
		return view('projects/edit', array_merge(Session::all(), ['project' => $call->response]));
	}

	public function dashboard($id) 
	{
		$call = API::get('/projects/'.$id, []);

		if ($call->error) {
			throw new Exception($call->error_message);
		}

		return view('projects/dashboard', array_merge(Session::all(), ['project' => $call->response]));
	}

	public function tasks($id) 
	{		
		$call = API::get('projects/'.$id.'/tasks', []);

		if ($call->error) {
			throw new Exception($call->error_message);
		}
		return view('projects/tasks/all', array_merge(Session::all(), ['tasks' => $call->response]));
	}

	public function task($id, $t_id) 
	{
		$call = API::get('/tasks/'.$t_id, []);

		if ($call->error) {
			throw new Exception($call->error_message);
		}

		return view('projects/tasks/single', array_merge(Session::all(), ['task' => $call->response]));
	}
}
