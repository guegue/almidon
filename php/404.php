<?php
define('ADMIN', true);
require($_SERVER['DOCUMENT_ROOT'] . '/../classes/app.class.php');
$smarty->caching = false;

if (!isset($adminlinks)) {
  $classes = get_declared_classes();
  foreach($classes as $key)
  if (strstr($key, 'table') && $key != 'table' && $key != 'tabledoublekey') {
    $table_object = new $key;
    $key = substr($key, 0, strpos($key, 'table'));
    $adminlinks[$key] = $table_object->title;
  }
  $smarty->assign('adminlinks', $adminlinks);
}

$params = explode('/', $_SERVER['REQUEST_URI']);
$object = $params[2];
if (strpos($object, '?')) {
#  $qp = explode('&', $params[2]);
#  foreach($qp as $qpvar) {
#    list($k, $v) = split('=',$qpvar);
#    $_REQUEST[$k] = $v;
#    $_GET[$k] = $v;
#  }
  $object = substr($object, 0, strpos($object, '?'));
  define('SELF', substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'],'?')));
} else {
  define('SELF', $_SERVER['REQUEST_URI']);
}
if ($object) {
  $ot = $object . 'Table';
  $$object = new $ot;
  require("/www/cms/php/typical.php");
  $$object->destroy();
  $tpl = ($$object->cols > 5) ? 'abajo' : 'normal';
  if ($$object->key2) $tpl .= '2';
  if (file_exists("/www/".DOMAIN."/templates/admin/header.tpl"))
    $smarty->assign('header',"/www/".DOMAIN."/templates/admin/header.tpl");
  else 
    $smarty->assign('header',"/www/cms/tpl/header.tpl");
  $smarty->display('/www/cms/tpl/' . $tpl . '.tpl');
} else {
  if (file_exists("/www/".DOMAIN."/templates/admin/index.tpl")) {  
    if(file_exists("/www/".DOMAIN."/templates/admin/header.tpl"))
      $smarty->assign('header','/www/'.DOMAIN.'/templates/admin/header.tpl');
    else $smarty->assign('header','/www/cms/tpl/header.tpl');
      $smarty->display("/www/".DOMAIN."/templates/admin/index.tpl");
  } else {
    $smarty->display('/www/cms/tpl/index.tpl');
  }
}
?>
