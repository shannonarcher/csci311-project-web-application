<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\API;
use \Session;
use \Exception;
use \Response;

class AjaxController extends Controller {

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

	public function getSkills() 
	{
		$call = API::get('/skills');
		return $call->response;
	}

	public function addSkill($id) 
	{
		if ($this->request->input("id") == 0) {
			$call = API::post("/skills", $this->request->all());
			$all = $this->request->all();
			$all["id"] = $call->response->id;
			$call = API::post("/users/$id/skills/".$call->response->id, $all);
		} else {
			$call = API::post("/users/$id/skills/".$this->request->input("id"), $this->request->all());
		}
		return $call->response;
	}

	public function removeSkill($id)
	{
		$call = API::delete("/users/$id/skills/".$this->request->input("id"), $this->request->all());
		return $call->response;
	}

	public function getRoles()
	{
		$call = API::get('/roles');
		return $call->response;
	}

	public function addComment($id) {
		$call = API::post("/tasks/$id/comments", [
			'comment' => $this->request->input('text')
			]);

		$comments = $call->response;

		foreach ($comments as $key => $value) {
			$value->updated_at = \App\Services\Moment::fromNow($value->updated_at, "Y-m-d H:i:s");
		}

		return \Response::json($comments, 200);
	}

	public function getComments($id) {
		$call = API::get("/tasks/$id/comments");
		
		$comments = $call->response;

		foreach ($comments as $key => $value) {
			$value->updated_at = \App\Services\Moment::fromNow($value->updated_at, "Y-m-d H:i:s");
		}

		return \Response::json($comments, 200);
	}
}
