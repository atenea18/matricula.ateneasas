<?php

namespace App\Traits;


trait utf8Helper
{

	/**
	*
	*
	*/
	protected function hideTilde($text)
	{	

		try
		{
			return iconv('UTF-8', 'windows-1252', html_entity_decode($text));

		}catch(\Exception $e){
			return;
		}
		$content = $text;
		$decoded = false;

		if(strstr($content, '&ndash;')){
			$content = str_replace('&ndash;', '-', $content);
			$decoded = true;
		}
	}
}