<?php

namespace App\Http\Controllers;

use App\Services\API;
use \Request;
use \Session;

class UserController extends Controller {

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
		$response = API::get('/users', []);

		if ($response->error)
			return $response->error_message;

		return view('users/welcome', array_merge(Session::all(), ['users' => $response->response]));
	}

	public function profile($id) 
	{
		$response = API::get('/users/'.$id, []);

		if ($response->error)
			return $response->error_message;

		return view('users/profile', array_merge(Session::all(), ['profile' => $response->response]));
	}
}
