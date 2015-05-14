<?php

namespace App\Http\Controllers;

use App\Services\API;
use Illuminate\Http\Request;
use \Session;

class WelcomeController extends Controller {

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
		return view('welcome', [
			'error_code' => '',
			'error_message' => '']);
	}

	public function login() 
	{
		// try login with api
		$response = API::post('/users/login', $this->request->all());

		// if fail, print error
		if ($response->error) {
			
			return view('welcome', [
				'error_code' => $response->error_code,
				'error_message' => $response->response->message]);
		}
		// else, store session token and redirect to dashboard
		else {
			Session::put('session_token', $response->response->session_token);
			Session::put('user', $response->response->user);
			return redirect('/dashboard');
		}
	}

	public function logout() {
		API::get('/users/logout', []);
		Session::flush();
		return redirect('/');
	}

}
