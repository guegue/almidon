<?php
// vim: set expandtab tabstop=2 shiftwidth=2 fdm=marker:

/**
 * almidon.php
 *
 * Carga los php necesarios para almidon. Llamado por app.class.php
 *
 * @copyright &copy; 2005-2009 Guegue Comunicaciones - guegue.com
 * @license http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @version $Id: almidon.php,v 2009111201 javier $
 * @package almidon
 */

if(!isset($almidondir))
  $almidondir = defined('ALMIDONDIR') ? ALMIDONDIR : $_SERVER['DOCUMENT_ROOT'] . '/../../core';
/*
 * DAL for almidon, classes y main functions to manage data
 */
require_once($almidondir . '/php/db2.class.php');
/*
 * Is ADMIN defined?
 */
if (!defined('ADMIN')) define('ADMIN', false);
/*
 * Language constants, only if it is admon
 */
if ( ADMIN === true ) {
  require_once($almidondir . '/php/lang.php');
}
/*
 * Loading some functions
 */
require_once($almidondir . '/php/functs.inc.php');
/*
 * Good old Smarty: see http://www.smarty.net/
 */
# Put it in core/include.d
alm_require('Smarty/Smarty.class.php');

# Set smarty (it can be re-config locally)
$smarty = new Smarty;
$smarty->template_dir = ROOTDIR . '/templates/';
$smarty->compile_dir = ROOTDIR . '/templates_c/';
$smarty->config_dir = ROOTDIR . '/configs/';
$smarty->cache_dir = ROOTDIR . '/cache/smarty/';
$smarty->plugins_dir = array('plugins', $almidondir.'/smarty/',$almidondir.'/smarty/validate/');
/*
 * @ignore - ADMIN, Am I in admin mode?
 */
if (ADMIN === true && !isset($_SESSION['idalm_role'])) $_SESSION['idalm_role'] = null;
if (ADMIN === true && !isset($_SESSION['idalm_user'])) $_SESSION['idalm_user'] = null;
/*
 * Loading local table definitions
 */
alm_require(ROOTDIR . '/classes/tables.class.php');
/*
 * Extra tables, they are not automatically modified
 */
alm_require(ROOTDIR . '/classes/extra.class.php');
/*
 * Loading global table definitions, (alm_*)
 */
if ( ADMIN === true ) {
  alm_require($almidondir . '/php/alm.tables.class.php');
}

$classes = get_declared_classes();
global $global_dd;
foreach($classes as $key) {
  if (stristr($key, 'table') && $key != 'table' && $key != 'Table') {
    $table_object = new $key;
    $global_dd[$table_object->name]['keys'] = $table_object->keys;
    if(isset($table_object->descriptor)) {
      $global_dd[$table_object->name]['descriptor'] = $table_object->descriptor;
    } elseif (preg_match('/(^|,)'.$table_object->name.'(,|$)/', $table_object->fields)) {
      $global_dd[$table_object->name]['descriptor'] = $table_object->name;
    } else {
      $global_dd[$table_object->name]['descriptor'] = $table_object->key;
    }
  }
}

/*
 * qdollar is used to escape strings, because in pgreg_replace uses "$" as a dollar sign, and no backreference
 */
function qdollar($value) {
  return str_replace('$', '\$', $value);
}
