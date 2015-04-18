<?php

namespace App\Http\Controllers;

use App\Services\API;
use \Request;
use \Session;

class DashboardController extends Controller {

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
		$call = API::get('/user', []);
		Session::put('user', $call->response);
		return view('dashboard/welcome', Session::all());
	}

}
