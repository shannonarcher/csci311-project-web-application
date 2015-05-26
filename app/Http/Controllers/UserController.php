<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\API;
use \Session;
use \Exception;

class UserController extends Controller {

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
		$call = API::get('/users', []);

		if ($call->error) {
			throw new Exception($call->error_message);
		}

		return view('users/welcome', array_merge(Session::all(), ['users' => $call->response]));
	}

	public function profile($id) 
	{
		$call = API::get('/users/'.$id, []);

		if ($call->error) {
			throw new Exception($call->error_message);
		}

		return view('users/profile', array_merge(Session::all(), ['profile' => $call->response]));
	}

	public function add() 
	{
		return view('users/add', array_merge(Session::all(), []));
	}

	public function create() 
	{
		$all = $this->request->all();
		$all["is_admin"] = ((isset($all["is_admin"]) && $all["is_admin"] == "on") ? 1 : 0);
		$all["is_archived"] = ((isset($all["is_archived"]) && $all["is_archived"] == "on") ? 1 : 0);

		$call = API::post('/users', $all);

		if ($call->error) {
			if (isset($call->response->message)) {
				Session::flash('error_message', $call->response->message);
				return redirect('users/add');
			}

			throw new Exception($call->error_message);
		}

		Session::flash('success_message', 'User created with password "'.$call->response->password.'".');

		return redirect('users/'.$call->response->user->id.'/profile');
	}

	public function edit($id) 
	{
		$call = API::get('/users/'.$id, []);

		if ($call->error) {
			throw new Exception($call->error_message);
		}

		return view('users/edit', array_merge(Session::all(), ['profile' => $call->response]));
	}

	public function save($id) 
	{
		$all = $this->request->all();
		$all["is_admin"] = ((isset($all["is_admin"]) && $all["is_admin"] == "on") ? 1 : 0);
		$all["is_archived"] = ((isset($all["is_archived"]) && $all["is_archived"] == "on") ? 1 : 0);

		$call = API::put('/users/'.$id, $all);

		if ($call->error) {
			if (isset($call->response->message)) {
				Session::flash('error_message', $call->response->message . ". This incident has been logged.");
				return redirect("/users/$id/profile");
			}

			throw new Exception($call->error_message);
		}

		Session::flash('success_message', 'User updated.');

		return redirect('users/'.$id.'/profile/edit');
	}
}
