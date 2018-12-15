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
			return (int) round($value, 0);

		return round($value, $roundNumber);
	}
}