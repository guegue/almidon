<?php
require_once('config.php');
require_once(ALMIDONDIR . '/php/db2.class.php');
require_once(ALMIDONDIR . '/php/Smarty/Smarty.class.php');

$smarty = new Smarty;
$smarty->template_dir = ROOTDIR . '/templates/';
$smarty->compile_dir = ROOTDIR . '/templates_c/';
$smarty->config_dir = ROOTDIR . '/configs/';
$smarty->cache_dir = ROOTDIR . '/cache/';
                                                                                
require('tables.class.php');
require('extra.class.php');
