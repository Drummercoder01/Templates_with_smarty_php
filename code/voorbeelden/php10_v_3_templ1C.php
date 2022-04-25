<?php
require("../smarty/mySmarty.inc.php");


$_cursussen = array("Javascript","PHP","DataBases", "WEB-Apps", "Project WEBO");

// We kennen de variabelen toe
$_smarty->assign('naam', 'De Pauw');
$_smarty->assign('voornaam', 'Micky');
$_smarty->assign('cursussen', $_cursussen);

// display it
$_smarty->display('template1C.tpl');

?>
