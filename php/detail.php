<?php
define('ADMIN',true);
require("../classes/app.class.php");
if ($_REQUEST['action'] == 'add')
  $smarty->assign('added',true);
if ($_REQUEST['action'] == 'save')
  $smarty->assign('updated',true);
if ($_REQUEST['action'] == 'close')
  $smarty->assign('closed',true);

$smarty->caching = false;
$object = $_SERVER['SCRIPT_NAME'];
$object = substr($object, strrpos($object, '/')+1, strrpos($object, '.') - (strrpos($object, '/') + 1));
$ot = $object . 'Table';
$$object = new $ot;
//$disable['prensa'] = true;
require(ALMIDONDIR."/php/typical.php");
/*$tpl = ($$object->cols > 5) ? 'abajo' : 'normal';
if ($$object->key2) $tpl .= '2';*/
$smarty->assign('header',"/www/cms/tpl/detail_header.tpl");
$$object->readEnv();
if($_REQUEST['preset']) {
  $smarty->assign('preset',$_REQUEST['preset']);
} else {
  $smarty->assign('preset',$_REQUEST['parent']."=".$_REQUEST[$_REQUEST['parent']]);
}
$$object->destroy();
$smarty->display(ALMIDONDIR.'/tpl/detail.tpl');
?>
