<?php

namespace App\Pdf\Merge;

use setasign\Fpdi\Fpdi;

/**
* 
*/
class Merge extends Fpdi
{
	
	private $path;
	private $fileName;
	private $orientation;

	function __construct($path, $fileName = 'merge' , $orientation='p')
	{
		$this->path = $path;
		$this->fileName = $fileName;
		$this->orientation = $orientation;
	}

	public function merge()
	{
		$files = $this->getFiles();

		$this->setFiles($files);

		return $this;
	}

	private function getFiles()
	{
		$dir = opendir($this->path);
        $files = array();
        while ($file = readdir($dir)) {
                
            if (!is_dir($file)){
                // echo $file."<br />";
                array_push($files, $file);
            }
        }

        asort($files);

        return $files;
	}

	private function setFiles($files)
	{
		foreach ($files as $file) 
        { 
            $pageCount = $this->setSourceFile($this->path.'/'.$file); 

            for ($i=1; $i <= $pageCount; $i++) { 
                
                $tpl = $this->importPage($i);
                $this->addPage($this->orientation); 

                $this->useTemplate($tpl); 
            }
        }
	}

	private function download()
	{
		ob_clean();
		$this->Output('D',$this->fileName.'.pdf');
	}
}