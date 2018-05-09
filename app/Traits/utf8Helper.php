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

		return iconv('UTF-8', 'windows-1252', html_entity_decode($text));
		$content = $text;
		$decoded = false;

		if(strstr($content, '&ndash;')){
			$content = str_replace('&ndash;', '-', $content);
			$decoded = true;
		}

		if(strstr($content, '&eacute;')){
			$content = str_replace('&eacute;', 'é', $content);
			$decoded = true;
		}
		
		if(strstr($content, '&Eacute;')){
			$content = str_replace('&Eacute;', 'É', $content);
			$decoded = true;
		}

		if(strstr($content, '&aacute;')){
			$content = str_replace('&aacute;', 'á', $content);
			$decoded = true;
		}
		
		if(strstr($content, '&Aacute;')){
			$content = str_replace('&Aacute;', 'Á', $content);
			$decoded = true;
		}
		
		if(strstr($content, '&iacute;')){
			$content = str_replace('&iacute;', 'í', $content);
			$decoded = true;
		}
		
		if(strstr($content, '&Iacute;')){
			$content = str_replace('&Iacute;', 'Í', $content);
			$decoded = true;
		}

		if(strstr($content, '&oacute;')){
			$content = str_replace('&oacute;', 'ó', $content);
			$decoded = true;
		}
		
		if(strstr($content, '&Oacute;')){
			$content = str_replace('&Oacute;', 'Ó', $content);
			$decoded = true;
		}
		
		if(strstr($content, '&uacute;')){
			$content = str_replace('&uacute;', 'ú', $content);
			$decoded = true;
		}
		
		if(strstr($content, '&Uacute;')){
			$content = str_replace('&Uacute;', 'Ú', $content);
			$decoded = true;
		}
		
		if(strstr($content, '&ntilde;')){
			$content = str_replace('&ntilde;', 'ñ', $content);
			$decoded = true;
		}
		
		if(strstr($content, '&Ntilde;')){
			$content = str_replace('&Ntilde;', 'Ñ', $content);
			$decoded = true;
		}
		
		if(strstr($content, '&ldquo;')){
			$content = str_replace('&ldquo;', '"', $content);
			$decoded = true;
		}
		
		if(strstr($content, '&rdquo;')){
			$content = str_replace('&rdquo;', '"', $content);
			$decoded = true;
		}
		
		if(strstr($content, '&hellip;')){
			$content = str_replace('&hellip;', '...', $content);
			$decoded = true;
		}	
		
		if(strstr($content, '&iquest;')){
			$content = str_replace('&iquest;', '¿', $content);
			$decoded = true;
		}
		
		if(strstr($content, '&nbsp;')){
			$content = str_replace('&nbsp;', ' ', $content);
			$decoded = true;
		}
		
		return ($decoded) ? utf8_decode($content) : $content;
	}
}