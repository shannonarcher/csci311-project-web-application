<?php 

namespace App\Services;

use \App\Services\Curl;
use \Session;

class API {

	private static $address = "http://localhost:8080/csci311-project-api/public";

	public static function get($url, $data = []) {
		$data = API::addFields($data);

		$curl = new Curl();
		$curl->get(API::$address.$url, $data);
		
		return $curl;
	}

	public static function post($url, $data = []) {
		$data = API::addFields($data);

		$curl = new Curl();
		$curl->post(API::$address.$url, $data);

		return $curl;
	}

	public static function put($url, $data = []) {
		$data = API::addFields($data);

		$curl = new Curl();
		$curl->put(API::$address.$url, $data);

		return $curl;
	}

	public static function delete($url, $data = []) {
		$data = API::addFields($data);

		$curl = new Curl();
		$curl->delete(API::$address.$url, $data);

		return $curl;
	}

	public static function addFields($data) {
		$data["session_token"] = Session::get('session_token');
		return $data;
	}
}
