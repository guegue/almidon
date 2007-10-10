<?php
require("./classes/app.class.php");
$smarty->caching = false;
$object = $_SERVER['SCRIPT_NAME'];
$object = substr($object, strrpos($object, '/')+1, strrpos($object, '.') - (strrpos($object, '/') + 1));
$ot = $object . 'Table';
$$object = new $ot;
$current = $_REQUEST['val'];
$key = $_REQUEST['key'];
if ($_REQUEST['move'] == 'up') {
  $lower = $$object->getVar("SELECT MAX($key) FROM ".$$object->name." WHERE $key < $current");
  if ($lower) {
    $$object->execSQL("UPDATE ".$$object->name." SET $key=NULL WHERE $key=$lower");
    $$object->execSQL("UPDATE ".$$object->name." SET $key=$lower WHERE $key=$current");
    $$object->execSQL("UPDATE ".$$object->name." SET $key=$current WHERE $key IS NULL");
  }
}
if ($_REQUEST['move'] == 'down') {
  $higher = $$object->getVar("SELECT MIN($key) FROM ".$$object->name." WHERE $key > $current");
  if ($higher) {
    $$object->execSQL("UPDATE ".$$object->name." SET $key=NULL WHERE $key=$higher");
    $$object->execSQL("UPDATE ".$$object->name." SET $key=$higher WHERE $key=$current");
    $$object->execSQL("UPDATE ".$$object->name." SET $key=$current WHERE $key IS NULL");
  }
}
require("/www/cms/php/typical.php");
$$object->destroy();
$tpl = ($$object->cols > 5) ? 'abajo' : 'normal';
if ($$object->key2) $tpl .= '2';
if (file_exists("/www/".DOMAIN."/templates/admin/header.tpl"))
  $smarty->assign('header',"/www/".DOMAIN."/templates/admin/header.tpl");
else 
  $smarty->assign('header',"/www/cms/tpl/header.tpl");
$smarty->display('/www/cms/tpl/' . $tpl . '.tpl');
?>
