<?php
require_once('config.php');
$almidondir = defined('ALMIDODIR') ? ALMIDONDIR : $_SERVER['DOCUMENT_ROOT'] . '/../../';
require_once($almidondir . '/php/db2.class.php');
require_once($almidondir . '/php/lang.php');
require_once($almidondir . '/php/Smarty/Smarty.class.php');

$smarty = new Smarty;
$smarty->template_dir = ROOTDIR . '/templates/';
$smarty->compile_dir = ROOTDIR . '/templates_c/';
$smarty->config_dir = ROOTDIR . '/configs/';
$smarty->cache_dir = ROOTDIR . '/cache/';
                                                                                
require('tables.class.php');
require('extra.class.php');
require('users.class.php');
