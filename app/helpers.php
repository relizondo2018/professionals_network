<?php

if (!function_exists('json_success')) {
	function json_success($data, $message = 'success', $status = 200)
	{
		return response()->json([
			'data' 	=> $data,
			'reason'=> $message,
			'result'=> true
		], $status);	
	}
}


if (!function_exists('json_error')) {
	function json_error($data, $message = 'failed', $status = 500)
	{
		return response()->json([
			'data' 	=> $data,
			'reason'=> $message,
			'result'=> false
		], $status);	
	}
}
