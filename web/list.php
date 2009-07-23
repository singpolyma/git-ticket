<?php

require dirname(__FILE__).'/include/config.php';

$tickets = explode("\n",shell_exec("cd '".REPOSITORY_PATH."'; git ticket list"));

echo "<ul>\n";
foreach($tickets as $ticket) {
	if(!$ticket) continue;
	$uticket = rawurlencode($ticket);
	$ticket = htmlspecialchars($ticket);
	echo "\t<li><a href=\"show.php?$uticket\">$ticket</a></li>\n";
}
echo "</ul>\n";

?>
