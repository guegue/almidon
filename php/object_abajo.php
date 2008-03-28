<?php
require("./classes/app.class.php");
$smarty->caching = false;
$object = $_SERVER['SCRIPT_NAME'];
$object = substr($object, strrpos($object, '/')+1, strrpos($object, '.') - (strrpos($object, '/') + 1));
$ot = $object . 'Table';
$$object = new $ot;
require(ALMIDONDIR."/php/typical.php");
$$object->destroy();
$tpl = 'abajo';
if ($$object->key2) $tpl .= '2';
$smarty->display(ALMIDONDIR.'/tpl/' . $tpl . '.tpl');
?>
