<?php
define('ADMIN', true);
require($_SERVER['DOCUMENT_ROOT'] . '/../classes/app.class.php');
$smarty->caching = false;

if (!isset($adminlinks)) {
  $classes = get_declared_classes();
  foreach($classes as $key)
  if (strstr($key, 'table') && $key != 'table' && $key != 'tabledoublekey') {
    error_log($key);
    $table_object = new $key;
    $key = substr($key, 0, strpos($key, 'table'));
    $adminlinks[$key] = $table_object->title;
  }
  $smarty->assign('adminlinks', $adminlinks);
}


if (file_exists(ROOTDIR."/templates/admin/index.tpl")) {  
  if(file_exists(ROOTDIR."/templates/admin/header.tpl"))
  	$smarty->assign('header',ROOTDIR.'/templates/admin/header.tpl');
  else $smarty->assign('header',ALMIDONDIR.'/tpl/header.tpl');
  $smarty->display(ROOTDIR."/templates/admin/index.tpl");
} else {
  $smarty->display(ALMIDONDIR.'/tpl/index.tpl');
}
?>
