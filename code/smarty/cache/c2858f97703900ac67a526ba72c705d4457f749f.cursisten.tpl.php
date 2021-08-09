<?php
/* Smarty version 3.1.31, created on 2017-01-17 17:07:55
  from "/Applications/MAMP/htdocs/webo/B_php/php10/smarty/templates/cursisten.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_587e415b7dafc9_15347262',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9063c5fdee3e27fd97d070508bf79838b5b069f8' => 
    array (
      0 => '/Applications/MAMP/htdocs/webo/B_php/php10/smarty/templates/cursisten.tpl',
      1 => 1484663206,
      2 => 'file',
    ),
  ),
  'cache_lifetime' => 120,
),true)) {
function content_587e415b7dafc9_15347262 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html  lang="nl">
<head>
<meta charset='UTF-8'>
<link rel='stylesheet' href='../css/cursisten.css'>
<script  src="../js_lib/copyright.js"></script>

<title>Smarty en Cursisten</title>
</head>

<body>
<div id='wrapper'>
	<header>
		<img src='../images/webontwikkeling.jpeg'  height=100% alt='webontwikkeling'>
		<h1>Web-ontwikkeling</h1>
	</header>
	<main>
	  <h1>Tonen</h1>  
<form  method=post action='/webo/B_php/php10/oefeningen/PHP10_C_tonen.php'>
<label>Voornaam</label>
<input type=text name=vnaam>
<br>
<label>Naam</label>
<input type=text name=naam>
<br>
<label>Gemeente</label>
<input type=text name=pcode placeholder=postcode >
<input type=text name=gemeente  placeholder=gemeente>
<br>
<br><br><br>
<input type=submit name='submit' value=Verzenden >
</form>
	</main>
  
	<footer>
	
		<script>
    
			document.write(copyRight('webontwikkeling.info'));
		</script>
		
	</footer>
  
</div>

</body>
</html>
<?php }
}
