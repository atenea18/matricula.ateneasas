<?php

namespace App\Traits;


trait ConvertFormant
{
	/**
	*
	*
	*/
	private function determineRound($value, $roundNumber, $decimals = true)
	{
		
		if(!$decimals)
			return number_format($value, 0);

		return number_format($value, $roundNumber);
	}
}