<?php
define('ADMIN', true);
require("./classes/app.class.php");
$smarty->caching = false;
$object = $_SERVER['SCRIPT_NAME'];
$object = substr($object, strrpos($object, '/')+1, strrpos($object, '.') - (strrpos($object, '/') + 1));
$ot = $object . 'Table';
$$object = new $ot;
require(ALMIDONDIR."/php/typical.php");
$$object->destroy();
$tpl = ($$object->cols > 5) ? 'abajo' : 'normal';
if ($$object->key2) $tpl .= '2';
if (file_exists(ROOTDIR."/templates/admin/header.tpl"))
  $smarty->assign('header',ROOTDIR."/templates/admin/header.tpl");
else 
  $smarty->assign('header',"/www/cms/tpl/header.tpl");
$smarty->display(ALMIDONDIR.'/tpl/' . $tpl . '.tpl');
?>
