<?php

/**
 * 404.php
 *
 * rewrite magic: para generar admin de tabla automaticamente (via mod_rewrite)
 *
 * @copyright &copy; 2005-2008 Guegue Comunicaciones - guegue.com
 * @license http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @version $Id: 404.php,v 2008032801 javier $
 * @package almidon
 */

define('ADMIN', true);
require($_SERVER['DOCUMENT_ROOT'] . '/../classes/app.class.php');
$smarty->caching = false;

# Crea enlaces superiores
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

$params = explode('/', $_SERVER['REQUEST_URI']);
$object = $params[2];
if (strpos($object, '?')) {
  $object = substr($object, 0, strpos($object, '?'));
  define('SELF', substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'],'?')));
} else {
  define('SELF', $_SERVER['REQUEST_URI']);
}
if ($object) {
  $ot = $object . 'Table';
  $$object = new $ot;
  require(ALMIDONDIR . '/php/typical.php');
  $$object->destroy();
  $tpl = ($$object->cols > 5) ? 'abajo' : 'normal';
  if ($$object->key2) $tpl .= '2';
  if (file_exists(ROOTDIR.'/templates/admin/header.tpl'))
    $smarty->assign('header',ROOTDIR."/templates/admin/header.tpl");
  else {
    $smarty->assign('header',ALMIDONDIR.'/tpl/header.tpl');
    $smarty->assign('footer', ALMIDONDIR . '/tpl/footer.tpl');
  }
  $smarty->display(ALMIDONDIR.'/tpl/'.$tpl.'.tpl');
} else {
  if (file_exists(ROOTDIR.'/templates/admin/index.tpl')) {  
    if(file_exists(ROOTDIR.'/templates/admin/header.tpl'))
      $smarty->assign('header',ROOTDIR.'/templates/admin/header.tpl');
    else  {
      $smarty->assign('header', ALMIDONDIR . '/tpl/header.tpl');
      $smarty->assign('footer', ALMIDONDIR . '/tpl/footer.tpl');
    }
    $smarty->display(ROOTDIR."/templates/admin/index.tpl");
  } else {
    $smarty->assign('header', ALMIDONDIR . '/tpl/header.tpl');
    $smarty->assign('footer', ALMIDONDIR . '/tpl/footer.tpl');
    $smarty->display(ALMIDONDIR . '/tpl/index.tpl');
  }
}
?>
