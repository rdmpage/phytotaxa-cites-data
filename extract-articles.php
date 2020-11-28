<?php

// Process pages of contents and extract individual articles

require_once(dirname(__FILE__) . '/lib.php');

require_once 'vendor/autoload.php';
use Sunra\PhpSimple\HtmlDomParser;

$use_biotaxa = false; // Use mapress.com by default
$use_biotaxa = true; // Sometimes www.mapress.com doesn't have refs but www.biotaxa.org does!?

$basedir = dirname(__FILE__) . '/articles';
$sourcedir = dirname(__FILE__) . '/contents';

$files = scandir($sourcedir);

/*
$files = array(
'134.1.html'
);
*/


$count = 1;


foreach ($files as $filename)
{
	// echo "filename=$filename\n";
	
	if (preg_match('/\.html$/', $filename))
	{	
		$html = file_get_contents($sourcedir . '/' . $filename);
		
		$dom = HtmlDomParser::str_get_html($html);
		
		foreach ($dom->find('div[class=tocTitle] a') as $a)
		{
			echo $a->href . "\n";
			
			$url = $a->href;
			
			$ok = true;
			
			// https://www.mapress.com/j/zt/article/view/7515
			if (preg_match('/view\/\d+$/', $url))
			{
				$ok = false;
			}
			
			if (preg_match('/@/', $url))
			{
				$ok = false;
			}

			if (preg_match('/web.ebscohost.com/', $url))
			{
				$ok = false;
			}
			
				
			if ($ok)
			{
				echo $url . "\n";
				
				$filename = preg_replace('/https?:\/\/www.mapress.com\/j\/pt\/article\/view\/phytotaxa\./i', '', $url);

				$filename = preg_replace('/https?:\/\/www.mapress.com\/j\/pt\/article\/view\//i', '', $filename);
				
				// https://www.mapress.com/j/zt/article/view/1%E2%80%9391
				$filename = urldecode($filename);
				
				$filename .= '.html';
				
				echo $filename . "\n";
			
				if (preg_match('/^(?<volume>\d+)(–\d+)?\.?/u', $filename, $m))
				{
					$volume = $m['volume'];
				
					// folder for volume
				
					$dir = $basedir . '/' . $volume ;
					if (!file_exists($dir))
					{
						$oldumask = umask(0); 
						mkdir($dir, 0777);
						umask($oldumask);
					}
	
					$filename = $dir . '/' . $filename;
				
					if (file_exists($filename))
					{
						echo "Have it\n";
					}
					else 
					{
						if ($use_biotaxa)
						{
							$url = preg_replace('/https?:\/\/www.mapress.com\/j\/pt\//', 'https://www.biotaxa.org/Phytotaxa/', $url);
						}				
				
						$article = get($url);
			
						file_put_contents($filename, $article);
			
						// Give server a break every 10 items
						if (($count++ % 10) == 0)
						{
							$rand = rand(1000000, 3000000);
							echo "\n ...sleeping for " . round(($rand / 1000000),2) . ' seconds' . "\n\n";
							usleep($rand);
						}
					}
				
				
				}
				else
				{
					echo "Problem\n";
					exit();
				}
			}
			
		}
		
	}

}

		
?>

