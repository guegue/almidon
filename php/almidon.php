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

require_once($almidondir . '/php/db2.class.php');
require_once($almidondir . '/php/lang.php');
require_once($almidondir . '/php/Smarty/Smarty.class.php');

# Configura smarty (puede re-configurarse localmente)

$smarty = new Smarty;
$smarty->template_dir = ROOTDIR . '/templates/';
$smarty->compile_dir = ROOTDIR . '/templates_c/';
$smarty->config_dir = ROOTDIR . '/configs/';
$smarty->cache_dir = ROOTDIR . '/cache/';

# Carga archivos locales

require(ROOTDIR . '/classes/tables.class.php');
require(ROOTDIR . '/classes/extra.class.php');
require_once($almidondir . '/php/alm.tables.class.php');
