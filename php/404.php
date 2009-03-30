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

# Tell Almidon to use admin user, admin links, etc
define('ADMIN', true);

# Fetch app.class.php, wherever it is...
$script_filename = $_SERVER['SCRIPT_FILENAME'];
$app_base = '/../classes/app.class.php';
$app_filename = substr($script_filename, 0, strrpos($script_filename,'/')) . $app_base;
if (file_exists($app_filename)) require($app_filename);
else require($_SERVER['DOCUMENT_ROOT'] . $app_base);
# Si no se define la variable utiliza la por defecto
if(!defined('ALMIDONDIR')) define('ALMIDONDIR','/www/cms');

# No cache in admon, of course
$smarty->caching = false;

# Who am I?
$params = explode('/', $_SERVER['REQUEST_URI']);
$object = $params[count($params)-1];
if (strpos($object, '?')) {
  $object = substr($object, 0, strpos($object, '?'));
  define('SELF', substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'],'?')));
} else {
  define('SELF', $_SERVER['REQUEST_URI']);
}
if(strrpos($object, '.')===true) $object = substr($object, 0, strrpos($object, '.'));

# If I am... Go ahead try to create object (or setup)
if ($object) {
  if ($object == 'setup') {
    require('setup.php');
    exit;
  }
  $ot = $object . 'Table';
  $$object = new $ot;
  require(ALMIDONDIR . '/php/typical.php');
  $$object->destroy();
  $tpl = ($$object->cols > 5) ? 'abajo' : 'normal';
  if (isset($$object->key2)) $tpl .= '2';
  if (file_exists(ROOTDIR.'/templates/admin/header.tpl')) {
    $smarty->assign('header',ROOTDIR."/templates/admin/header.tpl");
    $smarty->assign('footer',ROOTDIR."/templates/admin/footer.tpl");
  } else {
    $smarty->assign('header',ALMIDONDIR.'/tpl/header.tpl');
    $smarty->assign('footer', ALMIDONDIR . '/tpl/footer.tpl');
  }
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
    $tpl = 'index';
  }
}

# Creates admin links
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
      if(isset($extralinks)){
         foreach($extralinks as $key=>$link){
           $adminlinks[$key] = $link;
         }
      }
    }
  $smarty->assign('adminlinks', $adminlinks);
}

# Display object's forms (or index)
$smarty->display(ALMIDONDIR.'/tpl/'.$tpl.'.tpl');
