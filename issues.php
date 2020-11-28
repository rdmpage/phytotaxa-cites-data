<?php

// Fetch issues from OJS page for Zootaxa hosted by mapress.com

require_once(dirname(__FILE__) . '/lib.php');

$base_dir = dirname(__FILE__) . '/html';


$count = 1;

for ($i = 1; $i < 43; $i++)
{
	$url = 'https://www.mapress.com/j/pt/issue/archive?issuesPage=' . $i;
	
	$html = get($url);
	
	file_put_contents($base_dir . '/' . $i . '.html', $html);
	
	// Give server a break every 10 items
	if (($count++ % 10) == 0)
	{
		$rand = rand(1000000, 3000000);
		echo "\n ...sleeping for " . round(($rand / 1000000),2) . ' seconds' . "\n\n";
		usleep($rand);
	}
	
	

}

?>
