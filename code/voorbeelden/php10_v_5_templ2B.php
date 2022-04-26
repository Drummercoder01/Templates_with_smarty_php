<?php
require("../smarty/mySmarty.inc.php");


$_cursussen = array("Javascript","PHP5 deel 1", "PHP5 deel 2", "Databases", "Project WEBO");


// We kennen de variabelen toe
$_smarty->assign('naam', 'De Pauw');
$_smarty->assign('voornaam', 'Micky');
$_smarty->assign('cursussen', $_cursussen);

// display it
$_smarty->display('template2B.tpl');

?>
