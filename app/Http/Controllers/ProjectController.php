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
		parent::__construct();
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
		$call2 = API::get('/users', []);

		if ($call->error) {
			throw new Exception($call->error_message);
		}

		if ($call2->error) {
			throw new Exception($call->error_message);
		}

		$project = $call->response;
		foreach ($project->users as $user) {
			$project_roles = [];
			foreach ($user->roles as $role) {
				if ($role->pivot->assigned_for == $project->id)
					array_push($project_roles, $role);
			}
			$user->roles = $project_roles;
		}

		$users = $call2->response;
		foreach ($users as $user) {
			$roles = [];
			foreach ($user->roles as $role) {
				$exists = false;
				foreach ($roles as $r) {
					if ($r->name == $role->name) {
						$exists = true;
						break;
					}
				}
				if (!$exists) 
					$roles[] = $role;
			}
			$user->roles = $roles;

			$user->on_team = false;
			foreach ($project->users as $u2) {
				if ($u2->id == $user->id) {
					$user->on_team = true;
				}
			}
		}

		return view('projects/edit', array_merge(Session::all(), [
				'project' => $project,
				'users' => $call2->response ]));
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

	public function milestones($id) 
	{
		$call = API::get('/projects/'.$id.'/milestones');
		$call2 = API::get('/projects/'.$id);

		if ($call->error)
			throw new Exception($call->error_message);

		if ($call2->error) 
			throw new Exception($call->error_message);

		return view('projects/milestones/all', array_merge(Session::all(), ['milestones' => $call->response, 'project' => $call2->response]));
	}

	public function addMilestone($id)
	{
		return view('projects/milestones/add', array_merge(Session::all(), ['project_id' => $id]));
	}

	public function createMilestone($id) 
	{
		$all = $this->request->all();

		$call = API::post("/projects/$id/milestones", $all);

		if ($call->error) {
			throw new Exception($call->error_message);
		}

		Session::flash('message', "Milestone '" . $call->response->title . "' successfully created.");
		return redirect("/projects/$id/milestones/");
	}

	public function removeMilestone($id, $m_id) 
	{
		$call = API::delete("/milestones/$m_id");

		if ($call->error) {
			var_dump($call->response);
			throw new Exception($call->error_message);
		}

		return redirect("/projects/$id/milestones/");
	}

	public function tasks($id) 
	{		
		$call = API::get('/projects/'.$id.'/tasks', []);
		$call2 = API::get('/projects/'.$id, []);

		if ($call->error) {
			throw new Exception($call->error_message);
		}

		if ($call2->error) {
			throw new Exception($call2->error_message);
		}

		return view('projects/tasks/all', array_merge(Session::all(), ['tasks' => $call->response, 'project' => $call2->response]));
	}

	public function task($id, $t_id) 
	{
		$call = API::get('/tasks/'.$t_id, []);

		if ($call->error) {
			throw new Exception($call->error_message);
		}

		return view('projects/tasks/single', array_merge(Session::all(), ['task' => $call->response]));
	}

	public function attachUser($id, $u_id) 
	{
		// sort out roles
		$roles = $this->request->input("roles");
		if (is_array($roles)) {
			for ($i = 0; $i < count($roles); $i++) {
				$roles[$i] = explode(':', $roles[$i])[1];
			}
		} else {
			$roles = [];
		}

		$call = API::post("/projects/$id/attach/$u_id", 
							["is_manager" => $this->request->input("is_manager", 0),
							 "roles" => $roles]);

		if ($call->error) {
			throw new Exception($call->error_message);
		}

		Session::flash('message', $call->response->message);
		return redirect("/projects/$id/dashboard/edit");
	}

	public function detachUser($id, $u_id) 
	{
		$call = API::post("/projects/$id/detach/$u_id", []);

		if ($call->error) {
			throw new Exception($call->error_message);
		}

		Session::flash('message', $call->response->message);
		return redirect("/projects/$id/dashboard/edit");
	}

	public function promoteUser($id, $u_id) 
	{
		$call = API::post("/projects/$id/promote/$u_id", []);

		if ($call->error) {
			throw new Exception($call->error_message);
		}

		Session::flash('message', $call->response->message);
		return redirect("/projects/$id/dashboard/edit");
	}


	public function demoteUser($id, $u_id) 
	{
		$call = API::post("/projects/$id/demote/$u_id", []);

		if ($call->error) {
			throw new Exception($call->error_message);
		}

		Session::flash('message', $call->response->message);
		return redirect("/projects/$id/dashboard/edit");
	}

	public function addTask($id) 
	{
		$call = API::get("/projects/$id/tasks");

		if ($call->error) {
			throw new Exception($call->error_message);
		}

		return view('projects/tasks/add', array_merge(Session::all(), ['project_id' => $id, 'tasks' => $call->response]));
	}

	public function createTask($id) 
	{
		$all = $this->request->all();
		$all["estimation_duration"] = $all["estimation_duration"] * 86400;

		$call = API::post("/projects/$id/tasks", $all);

		if ($call->error) {
			throw new Exception($call->error_message);
		}

		return redirect("/projects/$id/tasks/".$call->response->id);
	}

	public function editTask($id, $t_id) 
	{
		$call = API::get("/tasks/$t_id");
		$call2 = API::get("/projects/$id/tasks");

		if ($call->error) {
			throw new Exception($call->error_message);
		}

		if ($call2->error) {
			throw new Exception($call2->error_message);
		}

		return view("projects/tasks/edit", array_merge(Session::all(), ['task' => $call->response, 'tasks' => $call2->response]));
	}

	public function saveTask($id, $t_id) 
	{
		$all = $this->request->all();
		$all["estimation_duration"] = $all["estimation_duration"] * 86400;
		$all["is_approved"] = (isset($all["is_approved"]) && ($all["is_approved"] == 'on'));
		
		$call = API::put("/tasks/$t_id", $all);

		if ($call->error) {
			var_dump($call->response);
			die();
			throw new Exception($call->error_message);
		}

		Session::flash('message', 'Task successfully updated.');
		return redirect("/projects/$id/tasks/$t_id/edit");
	}

	public function addRoleToUser($id, $u_id) 
	{
		$roles = $this->request->input("roles");
		if (is_array($roles)) {
			for ($i = 0; $i < count($roles); $i++) {
				$roles[$i] = explode(':', $roles[$i])[1];
			}
		} else {
			$roles = [];
		}

		$call = API::post("/projects/$id/addRole/$u_id", ['roles' => $roles]);

		if ($call->error) {
			var_dump($call->response);
			throw new Exception($call->error_message);
		}

		Session::flash('message', 'Role added to user');
		return redirect("/projects/$id/dashboard/edit");
	}

	public function removeRoleFromUser($id, $u_id) 
	{
		$roles = $this->request->input("roles");
		if (is_array($roles)) {
			for ($i = 0; $i < count($roles); $i++) {
				$roles[$i] = explode(':', $roles[$i])[0];
			}
		} else {
			$roles = [];
		}

		$call = API::post("/projects/$id/removeRole/$u_id", ['roles' => $roles]);

		if ($call->error) {
			var_dump($call->response);
			throw new Exception($call->error_message);
		}

		Session::flash('message', 'Role removed from user');

		return redirect("/projects/$id/dashboard/edit");
	}
}
