<?php

require("../smarty/mySmarty.inc.php");


$_cursussen[0]['naam'] = "Javascript";
$_cursussen[0]['code'] = 7843;
$_cursussen[1]['naam'] = "PHP5 deel 1";
$_cursussen[1]['code'] = 7844;
$_cursussen[2]['naam'] = "PHP5 deel 2";
$_cursussen[2]['code'] = 7848;
$_cursussen[3]['naam'] = "Databases";
$_cursussen[3]['code'] = 7845;
$_cursussen[4]['naam'] = "Project WEBO";
$_cursussen[4]['code'] = 7847;



// We kennen de variabelen toe
$_smarty->assign('naam', 'De Pauw');
$_smarty->assign('voornaam', 'Micky');
$_smarty->assign('cursussen', $_cursussen);

// display it
$_smarty->display('template3A.tpl');

?>
