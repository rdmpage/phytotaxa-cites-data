<?php

// Process issues web pages and extract links to list of contents for each issue

require_once(dirname(__FILE__) . '/lib.php');

require_once 'vendor/autoload.php';
use Sunra\PhpSimple\HtmlDomParser;


$basedir = dirname(__FILE__) . '/contents';
$sourcedir = dirname(__FILE__) . '/html';

$files = scandir($sourcedir);

// debugging
//$files=array('240.html');

$count = 1;


foreach ($files as $filename)
{
	// echo "filename=$filename\n";
	
	if (preg_match('/\.html$/', $filename))
	{	
		$html = file_get_contents($sourcedir . '/' . $filename);
		
		$dom = HtmlDomParser::str_get_html($html);
		
		foreach ($dom->find('div h4 a') as $a)
		{
			echo $a->href . "\n";
			
			$url = $a->href;
			
			$filename = str_replace('https://www.mapress.com/j/pt/issue/view/phytotaxa.', '', $url);
			$filename .= '.html';
			
			$filename = $basedir . '/' . $filename;
			
			if (file_exists($filename))
			{
				echo "Have this!\n";
			}
			else
			{
				$contents = get($url);
			
				file_put_contents($filename, $contents);
			
				// Give server a break every 10 items
				if (($count++ % 10) == 0)
				{
					$rand = rand(1000000, 3000000);
					echo "\n ...sleeping for " . round(($rand / 1000000),2) . ' seconds' . "\n\n";
					usleep($rand);
				}
			
			}
			
			
		}
		
	}

}

		
?>

