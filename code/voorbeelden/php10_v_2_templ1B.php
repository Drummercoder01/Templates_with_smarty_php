<?php

require("../smarty/mySmarty.inc.php");


// We kennen de variabelen toe
$_smarty->assign('naam', 'De Pauw');
$_smarty->assign('voornaam', 'Micky');
$_smarty->assign('cursus', 'PHP2');

// display it
$_smarty->display('template1B.tpl');

?>
