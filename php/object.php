<?php
define('ADMIN',true);
require($_SERVER['DOCUMENT_ROOT'] . '/../classes/app.class.php');
$smarty->caching = false;
$object = $_SERVER['SCRIPT_NAME'];
$object = substr($object, strrpos($object, '/')+1, strrpos($object, '.') - (strrpos($object, '/') + 1));
$ot = $object . 'Table';
$$object = new $ot;
require(ALMIDONDIR."/php/typical.php");
$$object->destroy();
if(isset($$obj))	$$obj->destroy();
$tpl = ($$object->cols > 5) ? 'abajo' : 'normal';
if (isset($$object->key2)) $tpl .= '2';
if (file_exists(ROOTDIR."/templates/admin/header.tpl")) {
  $smarty->assign('header',ROOTDIR."/templates/admin/header.tpl");
  $smarty->assign('footer',ROOTDIR."/templates/admin/footer.tpl");
} else { 
  $smarty->assign('header',ALMIDONDIR."/tpl/header.tpl");
  $smarty->assign('footer',ALMIDONDIR."/tpl/footer.tpl");
}
$smarty->display(ALMIDONDIR.'/tpl/' . $tpl . '.tpl');
