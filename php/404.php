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
if(strrpos($object, '.')!==false) $object = substr($object, 0, strrpos($object, '.'));

# If I am... Go ahead try to create object (or setup)
if ($object) {
  if ($object == 'setup') {
    require(ALMIDONDIR.'/php/setup.php');
    exit;
  }
  $ot = $object . 'Table';
  $$object = new $ot;
  #If I'm a detail, not the master
  if($$object->is_detail) {
    require(ALMIDONDIR . '/php/detail.php');
    die();
  }
  # If it continues is because I'm master
  require(ALMIDONDIR . '/php/typical.php');
  $$object->destroy();
  $tpl = ($$object->cols > 5) ? 'abajo' : 'normal';
  if (isset($$object->key2)) $tpl .= '2';
  $tpl = ALMIDONDIR . '/tpl/' . $tpl . '.tpl';
  if (file_exists(ROOTDIR.'/templates/admin/header.tpl')) {
    $smarty->assign('header',ROOTDIR."/templates/admin/header.tpl");
  } else {
    $smarty->assign('header',ALMIDONDIR.'/tpl/header.tpl');
  }
  if (file_exists(ROOTDIR.'/templates/admin/footer.tpl')) {
    $smarty->assign('footer',ROOTDIR."/templates/admin/footer.tpl");
  } else {
    $smarty->assign('footer', ALMIDONDIR . '/tpl/footer.tpl');
  }
} else {
  if (file_exists(ROOTDIR.'/templates/admin/index.tpl')) {  
    if(file_exists(ROOTDIR.'/templates/admin/header.tpl'))
      $smarty->assign('header',ROOTDIR.'/templates/admin/header.tpl');
    else 
      $smarty->assign('header', ALMIDONDIR . '/tpl/header.tpl');
    if(file_exists(ROOTDIR.'/templates/admin/footer.tpl'))
      $smarty->assign('header',ROOTDIR.'/templates/admin/footer.tpl');
    else 
      $smarty->assign('footer', ALMIDONDIR . '/tpl/footer.tpl');
    $tpl = ROOTDIR . "/templates/admin/index.tpl";
  } else {
    if(file_exists(ROOTDIR.'/templates/admin/header.tpl'))
      $smarty->assign('header',ROOTDIR.'/templates/admin/header.tpl');
    else
      $smarty->assign('header', ALMIDONDIR . '/tpl/header.tpl');
    if(file_exists(ROOTDIR.'/templates/admin/footer.tpl'))
      $smarty->assign('header',ROOTDIR.'/templates/admin/footer.tpl');
    else
      $smarty->assign('footer', ALMIDONDIR . '/tpl/footer.tpl');
    $tpl = ALMIDONDIR . '/tpl/index.tpl';
  }
}

require (ALMIDONDIR . '/php/createlinks.php');

# Display object's forms (or index)
$smarty->display($tpl);
