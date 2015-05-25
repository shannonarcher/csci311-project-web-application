<?php namespace App\Http\Middleware;

use Closure;

use \Response;
use \Session;

class Authenticate {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if (Session::has('session_token'))
			return $next($request);
		else
			return redirect("/");
	}

}
