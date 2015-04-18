<?php namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

use \Session;
use \App;

abstract class Controller extends BaseController {

	use DispatchesCommands, ValidatesRequests;

	public function __construct() 
	{
		if (Session::has('user'))
			App::setLocale(Session::get('user')->lang);
	}

}
