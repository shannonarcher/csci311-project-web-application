<?php 

namespace App\Services;

use \App\Services\Curl;
use \Session;

class API {

	public static function get($url, $data = []) {
		$data = API::addFields($data);

		$curl = new Curl();
		$curl->get(getenv('APP_API').$url, $data);
		
		return $curl;
	}

	public static function post($url, $data = []) {
		$data = API::addFields($data);

		$curl = new Curl();
		$curl->post(getenv('APP_API').$url, $data);

		return $curl;
	}

	public static function put($url, $data = []) {
		$data = API::addFields($data);

		$curl = new Curl();
		$curl->put(getenv('APP_API').$url, $data);

		return $curl;
	}

	public static function delete($url, $data = []) {
		$data = API::addFields($data);

		$curl = new Curl();
		$curl->delete(getenv('APP_API').$url, $data);

		return $curl;
	}

	public static function addFields($data) {
		$data["session_token"] = Session::get('session_token');
		return $data;
	}
}
