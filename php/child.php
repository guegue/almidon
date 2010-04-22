<?php
if(!defined('ADMIN')) define('ADMIN',true);

# Fetch app.class.php, wherever it is...
$script_filename = $_SERVER['SCRIPT_FILENAME'];
$app_base = '/../classes/app.class.php';
$app_filename = substr($script_filename, 0, strrpos($script_filename,'/')) . $app_base;
if (file_exists($app_filename)) require_once($app_filename);
else require_once($_SERVER['DOCUMENT_ROOT'] . $app_base);


if ($_REQUEST['action'] == 'add')
  $smarty->assign('added',true);
if ($_REQUEST['action'] == 'save')
  $smarty->assign('updated',true);
if ($_REQUEST['action'] == 'close')
  $smarty->assign('closed',true);

if(empty($object)) {
  $smarty->caching = false;
  $object = $_SERVER['SCRIPT_NAME'];
  $object = substr($object, strrpos($object, '/')+1, strrpos($object, '.') - (strrpos($object, '/') + 1));
  $ot = $object . 'Table';
  $$object = new $ot;
}
//$disable['prensa'] = true;
if(defined('ALMIDONDIR')) require(ALMIDONDIR."/php/typical.php");
/*$tpl = ($$object->cols > 5) ? 'down' : 'normal';
if ($$object->key2) $tpl .= '2';*/
if(defined('ALMIDONDIR')) {
  $smarty->assign('header',ALMIDONDIR."/tpl/child_header.tpl");
  $smarty->assign('footer',ALMIDONDIR."/tpl/footer.tpl");
}
$$object->readEnv();
if($_REQUEST['preset']) {
  $smarty->assign('preset',$_REQUEST['preset']);
} else {
  $smarty->assign('preset',$_REQUEST['parent']."=".$_REQUEST[$_REQUEST['parent']]);
}
$$object->destroy();
$smarty->display(ALMIDONDIR.'/tpl/child.tpl');
?>
