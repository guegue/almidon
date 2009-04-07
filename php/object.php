<?php
/**
 * object.php
 *
 * symlink magic: para generar admin de tabla automaticamente
 *
 * @copyright &copy; 2005-2008 Guegue Comunicaciones - guegue.com
 * @license http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @version $Id: object.php,v 2009032901 javier $
 * @package almidon
 */

# Tell Almidon to use admin user, admin links, etc
define('ADMIN',true);

# Fetch app.class.php, wherever it is...
$script_filename = $_SERVER['SCRIPT_FILENAME'];
$app_base = '/../classes/app.class.php';
$app_filename = substr($script_filename, 0, strrpos($script_filename,'/')) . $app_base;
if (file_exists($app_filename)) require($app_filename);
else require($_SERVER['DOCUMENT_ROOT'] . $app_base);

# No cache in admon, of course
$smarty->caching = false;

# Who am I?
$object = $_SERVER['SCRIPT_NAME'];
$object = substr($object, strrpos($object, '/')+1, strrpos($object, '.') - (strrpos($object, '/') + 1));

# If I am... Go ahead try to create object (or setup)
$ot = $object . 'Table';
$$object = new $ot;
require(ALMIDONDIR."/php/typical.php");
$$object->destroy();
if(isset($$obj))	$$obj->destroy();
$tpl = ($$object->cols > 5) ? 'abajo' : 'normal';
if (isset($$object->key2)) $tpl .= '2';
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


# Display object's forms (or index)
$smarty->display(ALMIDONDIR.'/tpl/' . $tpl . '.tpl');
