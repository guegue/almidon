<?php
define('ADMIN', true);
require($_SERVER['DOCUMENT_ROOT'] . '/../classes/app.class.php');
$smarty->caching = false;

if (!isset($adminlinks)) {
  $classes = get_declared_classes();
  foreach($classes as $key)
  if (stristr($key, 'table') && $key != 'table' && $key != 'tabledoublekey' && $key != 'Table' && $key != 'TableDoubleKey') {

    $table_object = new $key;
    // Modificacion hecho por lo antes comentado entre php5 y php4
    if(substr($key, 0, strpos($key, 'Table'))!==false) {
      $key = substr($key, 0, strpos($key, 'Table'));
    } else { $key = substr($key, 0, strpos($key, 'table')); }
    // End
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
  $smarty->assign('header', ALMIDONDIR . '/tpl/header.tpl');
  $smarty->assign('footer', ALMIDONDIR . '/tpl/footer.tpl');
  $smarty->display(ALMIDONDIR.'/tpl/index.tpl');
}
