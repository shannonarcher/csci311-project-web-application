<?php 

namespace App\Services;

use \App\Services\Curl;
use \Session;

class API {

	public static function base() {
		$base = getenv('APP_API');
		return $base;
	}

	public static function get($url, $data = []) {
		$url = preg_replace('/\s+/', '', $url);
		$url = API::base().$url;

		$data = API::addFields($data);

		$curl = new Curl();
		$curl->get($url, $data);
		
		return $curl;
	}

	public static function post($url, $data = []) {
		$url = preg_replace('/\s+/', '', $url);
		$url = API::base().$url;

		$data = API::addFields($data);

		$curl = new Curl();
		$curl->post($url, $data);

		return $curl;
	}

	public static function put($url, $data = []) {
		$url = preg_replace('/\s+/', '', $url);
		$url = API::base().$url;

		$data = API::addFields($data);

		$curl = new Curl();
		$curl->put($url, $data);

		return $curl;
	}

	public static function delete($url, $data = []) {
		$url = preg_replace('/\s+/', '', $url);
		$url = API::base().$url;

		$data = API::addFields($data);

		$curl = new Curl();
		$curl->delete($url, $data);

		return $curl;
	}

	public static function addFields($data) {
		$data["session_token"] = Session::get('session_token');
		return $data;
	}
}
