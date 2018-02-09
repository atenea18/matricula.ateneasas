<?php

namespace App\Traits;

use Illuminate\Support\Collection;

trait ApiResponse
{
	private function successResonpe($data, $code)
	{
		return response()->json($data, $code);
	}

	protected function errorResponse($message, $code)
	{
		return response()->json(['error'=>$message, 'code'=>$code], $code);
	}

	protected function showAll(Collection $collection, $code = 200)
	{
		return $this->successResonpe(['data'=>$collection], $code);
	}

	protected function showOne(Model $instance, $code = 200)
	{
		return $this->successResonpe(['data'=>$instance], $code);	
	}
}