<?php

namespace App\Http\Controllers;

use App\Services\API;
use App\Services\AlbrechtFP;
use App\Services\COCOMO;
use Illuminate\Http\Request;
use \Session;
use \Exception;

use \DateTime;

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
			if (isset($call->response->message)) {
				Session::flash('error_message', $call->response->message);
				return redirect('/projects/add');
			} else {
				throw new Exception($call->error_message);
			}
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
			var_dump($call2->response);
			die();
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
			if (isset($call->response->message)) {
				Session::flash('error_message', $call->response->message);
			} else {
				throw new Exception($call->error_message);
			}
		}
		
		return redirect("/projects/$id/dashboard/edit");
	}

	public function dashboard($id) 
	{
		$call = API::get('/projects/'.$id, []);
		if ($call->error) {
			var_dump($call->response);
			die();
			throw new Exception($call->error_message);
		}

		$call2 = API::get("/projects/$id/notifications", ['limit' => 6]);		
		if ($call2->error) {
			throw new Exception($call2->error_message);
		}

		$project = $call->response;
		$tasks = $project->tasks;
		$all_std_dev = 0;
		$total_expected = 0;

		foreach ($tasks as $task) {
			$task->expected_time = ($task->optimistic_duration + 4 * $task->estimation_duration + $task->pessimistic_duration) / 6 / 86400;
			$task->std_dev = ($task->pessimistic_duration - $task->optimistic_duration) / 6 / 86400;
			
			$all_std_dev += pow($task->std_dev, 2); 
			$total_expected += $task->expected_time;
		}

		$all_std_dev = sqrt($all_std_dev);

		$start = DateTime::createFromFormat("Y-m-d H:i:s", $project->started_at);
		$end   = DateTime::createFromFormat("Y-m-d H:i:s", $project->expected_completed_at);
		$diff  = $end->getTimestamp() - $start->getTimestamp();
		$target = $diff / 86400;

		$z_value = 0;
		if ($all_std_dev != 0)
			$z_value = ($target - $total_expected) / $all_std_dev;

		$chance = 0;
		if ($z_value !== false)
			$chance = \App\Services\PERT::zToSuccess($z_value);
		$project->pert = $chance;

		return view('projects/dashboard', array_merge(Session::all(), [
			'project' => $project, 
			'notifications' => $call2->response ]));
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
			if (isset($call->response->message)) {
				Session::flash('error_message', $call->response->message);
				return redirect("/projects/$id/milestones/add");
			}
			throw new Exception($call->error_message);
		}

		Session::flash('message', "Milestone '" . $call->response->title . "' successfully created.");
		return redirect("/projects/$id/milestones/");
	}

	public function removeMilestone($id, $m_id) 
	{
		$call = API::delete("/milestones/$m_id");

		if ($call->error) {
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
		$call = API::get('/tasks/'.$t_id);

		if ($call->error) {
			var_dump($call->response);
			die();
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
			if (isset($call->response->message)) {
				Session::flash('error_message', $call->response->message);
				return redirect("/projects/$id/dashboard/edit");
			}
			throw new Exception($call->error_message);
		}

		Session::flash('message', $call->response->message);
		return redirect("/projects/$id/dashboard/edit");
	}

	public function detachUser($id, $u_id) 
	{
		$call = API::post("/projects/$id/detach/$u_id", []);

		if ($call->error) {
			if (isset($call->response->message)) {
				Session::flash('error_message', $call->response->message);
				return redirect("/projects/$id/dashboard/edit");
			}
			throw new Exception($call->error_message);
		}

		Session::flash('message', $call->response->message);
		return redirect("/projects/$id/dashboard/edit");
	}

	public function promoteUser($id, $u_id) 
	{
		$call = API::post("/projects/$id/promote/$u_id", []);

		if ($call->error) {
			$response = $call->response;
			if (isset($response->message)) {
				Session::flash('error_message', $response->message);
				return redirect("/projects/$id/dashboard/edit");
			}

			throw new Exception($call->error_message);
		}

		Session::flash('message', $call->response->message);
		return redirect("/projects/$id/dashboard/edit");
	}


	public function demoteUser($id, $u_id) 
	{
		$call = API::post("/projects/$id/demote/$u_id", []);

		if ($call->error) {
			if (isset($call->response->message)) {
				Session::flash('error_message', $call->response->message);
				return redirect("/projects/$id/dashboard/edit");
			}
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
		$all["optimistic_duration"] = $all["optimistic_duration"] * 86400;
		$all["estimation_duration"] = $all["estimation_duration"] * 86400;
		$all["pessimistic_duration"] = $all["pessimistic_duration"] * 86400;

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
		$call3 = API::get("/projects/$id/users");

		if ($call->error) {
			throw new Exception($call->error_message);
		}

		if ($call2->error) {
			throw new Exception($call2->error_message);
		}

		if ($call3->error) {
			throw new Exception($call3->error_message);
		}

		$assigned = [];
		foreach ($call->response->resources as $user) {
			$assigned[] = $user->id;
		}

		return view("projects/tasks/edit", array_merge(Session::all(), [
			'task' => $call->response, 
			'tasks' => $call2->response,
			'users' => $call3->response,
			'assigned' => $assigned
		]));
	}

	public function saveTask($id, $t_id) 
	{
		$all = $this->request->all();
		$all["optimistic_duration"] = $all["optimistic_duration"] * 86400;
		$all["estimation_duration"] = $all["estimation_duration"] * 86400;
		$all["pessimistic_duration"] = $all["pessimistic_duration"] * 86400;

		$call = API::put("/tasks/$t_id", $all);

		if ($call->error) {
			if (isset($call->response->message)) {
				Session::flash("error_message", $call->response->message);
				return redirect("/projects/$id/tasks/$t_id/edit");
			}
			throw new Exception($call->error_message);
		}

		$message = 'Task successfully updated.';
		if ($call->response->circular_dependencies > 0)
			$message .= " " . $call->response->circular_dependencies . " circular dependenc". ($call->response->circular_dependencies == 1 ? 'y' : 'ies') ." prevented.";
		if ($call->response->circular_parent > 0)
			$message .= " Circular parent depedency prevented.";

		Session::flash('message', $message);
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

	public function functionPoints($id) 
	{
		$call = API::get("/projects/$id");

		if ($call->error) {
			throw new Exception($call->error_message);
		}

		$gscs = AlbrechtFP::getGSCList();
		$complexity = AlbrechtFP::getComplexityTable();

		return view("projects/functionPoints", array_merge(Session::all(), [
			'project' => $call->response, 
			'gscs' => $gscs, 
			'complexity' => $complexity ]));
	}

	public function saveFunctionPoints($id) 
	{
		$call = API::post("/projects/$id/functionPoints", $this->request->all());

		if ($call->error) {
			throw new Exception($call->error_message);
		}

		Session::flash('message', 'Successfully saved function points.');
		Session::flash('project', $call->response);

		return redirect("projects/$id/functionPoints");
	}

	public function cocomo($id) 
	{
		$call = API::get("/projects/$id");
		$call2 = API::get("/cocomo/types");

		if ($call->error) {
			throw new Exception($call->error_message);
		}

		if ($call2->error) {
			throw new Exception($call2->error_message);
		}

		return view("projects/cocomo", array_merge(Session::all(), [
			'project' => $call->response,
			'types' => $call2->response,
			'sfs' => COCOMO::getScaleFactors(),
			'ems' => COCOMO::getEffortMultipliers(),
			'ratings' => COCOMO::getRatings()
			]));
	}

	public function saveCocomo($id) 
	{
		$call = API::post("/projects/$id/cocomo", $this->request->all());

		if ($call->error) {
			throw new Exception($call->error_message);
		}

		Session::flash('message', 'Successfully saved COCOMO I and II.');

		return $this->cocomo($id);
	}

	public function notifications($id) {
		$call = API::get("/projects/$id");
		$call2 = API::get("/projects/$id/notifications");

		if ($call->error) {
			throw new Exception($call->error_message);
		}
		if ($call2->error) {
			throw new Exception($call2->error_message);
		}

		return view('projects/notifications', array_merge(Session::all(), 
			['project' => $call->response, 
			 'notifications' => $call2->response]));
	}

	public function assignUserToTask($id, $task_id, $user_id) {
		$call = API::post("/tasks/$task_id/assign/$user_id");

		if ($call->error) {
			if (isset($call->response->message)) {
				Session::flash('error_message', $call->response->message);
				return redirect("/projects/$id/tasks/$task_id/edit");
			}
			throw new Exception($call->error_message);
		}

		Session::flash('message', "Successfully assigned resource to task.");
		return redirect("/projects/$id/tasks/$task_id/edit");
	}

	public function unassignUserFromTask($id, $task_id, $user_id) {
		$call = API::post("/tasks/$task_id/unassign/$user_id");

		if ($call->error) {
			throw new Exception($call->error_message);
		}

		Session::flash('message', "Successfully unassigned resource from task.");

		return redirect("/projects/$id/tasks/$task_id/edit");
	}

	public function pert($id) 
	{		
		$call = API::get('/projects/'.$id.'/tasks', []);
		$call2 = API::get('/projects/'.$id, []);

		if ($call->error) {
			throw new Exception($call->error_message);
		}

		if ($call2->error) {
			throw new Exception($call2->error_message);
		}

		$tasks = $call->response;
		$all_std_dev = 0;
		$total_expected = 0;

		foreach ($tasks as $task) {
			$task->expected_time = ($task->optimistic_duration + 4 * $task->estimation_duration + $task->pessimistic_duration) / 6 / 86400;
			$task->std_dev = ($task->pessimistic_duration - $task->optimistic_duration) / 6 / 86400;
			
			$all_std_dev += pow($task->std_dev, 2); 
			$total_expected += $task->expected_time;
		}

		$all_std_dev = sqrt($all_std_dev);

		$project = $call2->response;
		$start = DateTime::createFromFormat("Y-m-d H:i:s", $project->started_at);
		$end   = DateTime::createFromFormat("Y-m-d H:i:s", $project->expected_completed_at);
		$diff  = $end->getTimestamp() - $start->getTimestamp();
		$target = $diff / 86400;

		$z_value = 0;
		if ($all_std_dev != 0)
			$z_value = ($target - $total_expected) / $all_std_dev;

		$chance = \App\Services\PERT::zToSuccess($z_value);

		return view('projects/pert', array_merge(Session::all(), [
			'tasks' => $tasks, 
			'project' => $call2->response,
			'all_std_dev' => $all_std_dev,
			'target' => $target,
			'z_value' => $z_value,
			'chance' => $chance ]));
	}

	public function criticalChain($id) {
		$call = API::get('/projects/'.$id.'/tasks', []);
		$call2 = API::get('/projects/'.$id, []);

		if ($call->error) {
			throw new Exception($call->error_message);
		}

		if ($call2->error) {
			throw new Exception($call2->error_message);
		}

		$tasks = $call->response;
		$project_buffer = 0;

		foreach ($tasks as $task) {
			$project_buffer += round(($task->pessimistic_duration - $task->estimation_duration) / 86400 * 100) / 100;
		}

		return view('projects/cc', array_merge(Session::all(), [
			'tasks' => $tasks, 
			'project' => $call2->response,
			'project_buffer' => $project_buffer ]));
	}

	public function completeTask($id, $t_id) {
		$call = API::post("/tasks/$t_id/complete");

		if ($call->error) {
			if (isset($call->response->message)) {
				Session::flash("error_message", $call->response->message);
				return redirect("/projects/$id/tasks/$t_id");
			}
			throw new Exception($call->error_message);
		}

		Session::flash('message', 'Task marked as completed.');

		return redirect("/projects/$id/tasks/$t_id");
	}

	public function archive($id) {
		$call = API::post("/projects/$id/archive");

		if ($call->error) {
			if (isset($call->response->message)) {
				Session::flash("error_message", $call->response->message);
				return redirect("/projects/$id/dashboard/edit");
			}
			throw new Exception($call->error_message);
		}

		Session::flash('message', "Project archived.");
		return redirect("/projects/$id/dashboard/edit");
	}

	public function unarchive($id) {
		$call = API::post("/projects/$id/unarchive");

		if ($call->error) {
			var_dump($call->response);
			die();
			if (isset($call->response->message)) {
				Session::flash("error_message", $call->response->message);
				return redirect("/projects/$id/dashboard/edit");
			}
			throw new Exception($call->error_message);
		}

		Session::flash('message', "Project unarchived.");
		return redirect("/projects/$id/dashboard/edit");
	}

}
