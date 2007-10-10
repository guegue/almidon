<?php
require("./classes/app.class.php");
$smarty->caching = false;
$object = $_SERVER['SCRIPT_NAME'];
$object = substr($object, strrpos($object, '/')+1, strrpos($object, '.') - (strrpos($object, '/') + 1));
$ot = $object . 'Table';
$$object = new $ot;
require("/www/cms/php/typical.php");
$$object->destroy();
$tpl = 'normal';
if ($$object->key2) $tpl .= '2';
$smarty->display('/www/cms/tpl/' . $tpl . '.tpl');
?>
