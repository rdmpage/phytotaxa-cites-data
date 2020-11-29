<?php

// Process individual article pages and extract list of references

require_once(dirname(__FILE__) . '/lib.php');

require_once 'vendor/autoload.php';
use Sunra\PhpSimple\HtmlDomParser;


$basedir = dirname(__FILE__) . '/articles';
$sourcedir = dirname(__FILE__) . '/articles';

$files1 = scandir($sourcedir);

//$files1 = array('1026');

//$files1 = array('3904');

// debugging

$count = 1;


foreach ($files1 as $directory)
{
	//echo $directory . "\n";
	if (preg_match('/^\d+$/', $directory))
	{	
		//echo $directory . "\n";
		
		$files2 = scandir($sourcedir . '/' . $directory);

		foreach ($files2 as $filename)
		{
			echo $filename . "\n";
			if (preg_match('/\.html$/', $filename))
			{
				$base_filename = str_replace('.html', '', $filename);
			
				$html = file_get_contents($sourcedir . '/' . $directory . '/' . $filename);
			
				$dom = HtmlDomParser::str_get_html($html);
				
				//echo $html;
				
				$references = array();
				
				foreach ($dom->find('div[id=articleCitations] p') as $p)
				{
					$text = $p->plaintext;
					
					$text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
					
					if (preg_match('/^\s+https?:\/\/(dx.)?doi/', $text))
					{
						$n = count($references);
						$references[$n-1] .= $text;
					}
					else
					{
						// https://stackoverflow.com/a/18992691/9684
						$text = preg_replace('/\R/u', ' ', $text);					
						$references[] = $text;
					}
					
				}
				
				print_r($references);
				
				$text = join("\n",$references);
				
				$textfilename = $sourcedir . '/' . $directory . '/' . $base_filename . '.txt';
				$jsonfilename = $sourcedir . '/' . $directory . '/' . $base_filename . '.json';
				
				file_put_contents($textfilename, $text);
				
				$command = 'anystyle -f csl parse ' . $textfilename . ' > ' . $jsonfilename;
				
				echo $command . "\n";
								
				system($command);
				
			

			}
		}
	}
}


?>

