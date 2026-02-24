<?php


namespace App\Helpers;


class ApiHelper {

	public static function return_error( $code, $message = null, $debugger = null ) {
		$error_data["error"]             = array();
		$error_data["error"]["message"]  = $message;
		$error_data["error"]["debugger"] = $debugger;

		return response( $error_data, $code );
	}

	public static function methodNotAllowedError() {
		return self::return_error( 405, "The method you are calling is not defined", "Method Not Allowed" );
	}

	public static function methodFailedError() {
		return self::return_error( 420, "Something went wrong and the request could not be completed", "Method Failed" );
	}

	public static function notFoundError() {
		return self::return_error( 404, "This resource doesn't exist", "Not Found" );
	}

	public static function forbiddenError() {
		return self::return_error( 403, "Not allowed to view this resource", "Forbidden" );
	}

}
