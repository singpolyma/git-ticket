<?php

if(!$_SERVER['QUERY_STRING']) {
	die('Must specify a ticket to view!');
}

require dirname(__FILE__).'/include/config.php';

// XXX Should come up with a way of escaping so that tickets can be in folders
$ticket = explode("\n\n",file_get_contents(REPOSITORY_PATH.'/.tickets/'.basename($_SERVER['QUERY_STRING'])),2);

$meta = explode("\n", $ticket[0]);
if(count($meta)) {
	echo "<dl>\n";
	foreach($meta as $line) {
		$line = preg_split('/:\s*/', $line, 2);
		if($line[0] && $line[1]) {
			if(strtolower($line[0]) == 'from') {
				$line[1] = preg_replace('/@[^<>]+/','',$line[1]);
			}
			$k = htmlspecialchars($line[0]);
			$v = htmlspecialchars($line[1]);
			echo "\t<dt>$k</dt>\n\t\t<dd>$v<\dd>\n";
		}
	}
	echo "</dl>\n";
}

echo "\n<p>".str_replace("\n\n","</p>\n\n<p>",$ticket[1])."</p>\n";

// XXX support threaded comments
$comments = explode("\n",shell_exec("cd '".REPOSITORY_PATH."/.tickets'; git ticket list comments '".basename($_SERVER['QUERY_STRING'])."'"));
if(count($comments)) {
	echo "\n<ul>\n";
	foreach($comments as $comment) {
		if(!$comment) continue;
		$comment = explode("\n\n",file_get_contents(REPOSITORY_PATH.'/.tickets/'.$comment));
		$comment[0] = explode("\n", $comment[0]);
		foreach($comment[0] as $meta) {
			if(!$meta) continue;
			$meta = preg_split('/:\s*/', $meta, 2);
			if(strtolower($meta[0]) == 'from') $from = preg_replace('/@[^<>]+/','',$meta[1]);
			if(strtolower($meta[0]) == 'date') $date = $meta[1];
		}
		echo "\t<li>\n";
		echo "\n\t\t<dl>\n";
		echo "\t\t\t<dt>From</dt>\n";
		echo "\t\t\t\t<dd>".htmlspecialchars($from)."</dd>\n";
		echo "\t\t\t<dt>Date</dt>\n";
		echo "\t\t\t\t<dd>".htmlspecialchars($date)."</dd>\n";
		echo "\t\t</dl>\n";
		echo "\n\t\t<p>".str_replace("\n\n","</p>\n\n\t\t<p>",$comment[1])."</p>\n";
		echo "\t</li>\n";
	}
	echo "</ul>\n";
}


?>
