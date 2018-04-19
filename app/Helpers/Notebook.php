<?php

namespace App\Helpers;

use Illuminate\Http\Request;


use App\Enrollment;

class Notebook
{

	private $request = array();

	public function __construct(Request $request)
	{	
		$this->request = $request;
	}

	public function create(Enrollment $enrollment)
	{
		$response = array(
			'tittle'			=>	'INFORME DESCRIPTIVO Y VALORATIVO',
			'tittle_if' 		=> 	'INFORME DE EVALUACIÓN FINAL DEL PROCESO FORMATIVO',
			'current_period' 	=> 	$this->request->period,
			'date' 				=> 	date('Y-m-d'),
			'student' 			=> 	$enrollment->student,
			'grade' 			=> 	0,
			'periods'			=> 	array(),
			'config'			=>	array(),
			'parameters'		=> 	array()
		);


		return $response;
	}
}

