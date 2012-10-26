<?php

class CleanAssetsCommand extends CConsoleCommand {

    public function actionIndex($auto=false,$dir="") {
		defined('DS') or define('DS',DIRECTORY_SEPARATOR);
		if(empty($dir)) {
			$AM  = new CAssetManager;
			$dir = $AM->getBasePath();
		}
		if(file_exists($dir))
		{
			$files = glob($dir.DS."*");
			if(!$auto)
			{
				echo "Assets path is ".$dir.PHP_EOL;
				echo "Directory structure:".PHP_EOL;

				$sorted = array();
				foreach($files as $sort)
				{
					$prefix = "";
					if(is_dir($sort)) $prefix = DS;
					$sorted[] = $prefix.str_replace($dir.DS,"",$sort);
				}
				asort($sorted);
				foreach ($sorted as $filename)
				{
					echo date("Y-m-d    H:i:s",filectime($dir.DS.$filename))."    ";
					if(is_dir($dir.DS.$filename)){
						echo "<DIR>                ";
					} else {
						echo "        ";
						echo sprintf("% 12s  ",number_format(filesize($dir.DS.$filename),0,'.',' '));
					}

					echo $filename;
					echo PHP_EOL;
					
				}
				echo "Do you want to clean assets folder? (Y/N) ";
				$handle = fopen ("php://stdin","r");
				$line = fgets($handle);
				if(strtoupper(trim($line)) !== 'Y'){
					echo "Aborted".PHP_EOL;
					exit;
				}
				fclose($handle);
			}
			foreach($files as $del)
			{
				if(is_dir($del))
				{
					$this->rmDirRec($del);
				} else {
					unlink($del);
				}
			}
			if(!$auto) echo "finished!".PHP_EOL;
		} else {
			echo "Assets directory not exists";

		}
		exit();		
    }
	
	private function rmDirRec($dir)
    {
        $objs = glob($dir."/*");
        if ($objs)
        {
            foreach($objs as $obj)
            {
                is_dir($obj) ? $this->rmDirRec($obj) : unlink($obj);
            }
        }
        rmdir($dir);
    }
	
	public function getHelp()
	{
		echo 'console command to clean '.DIRECTORY_SEPARATOR.'assets folder'.PHP_EOL;
		echo 'Usage: yiic cleanassets [--auto] [--dir=/path/to/dir]'.PHP_EOL;
		echo PHP_EOL.'--auto makes script to run in silent mode.'.PHP_EOL;
		echo 'Warning! Confirmation question will not be asked!'.PHP_EOL;
		echo PHP_EOL.'--dir=/path/to/dir you can specify dir to clean.'.PHP_EOL;
		echo 'If blank - sustem default path will be applied'.PHP_EOL;
		echo 'Warning! Be carefull with this param not to loose data!'.PHP_EOL;
	}
}
?>