<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/../classes/config.php');
require_once(ALMIDONDIR.'/php/db3.class.php');
require_once('Smarty/Smarty.class.php');

$smarty = new Smarty;
$smarty->template_dir = ROOTDIR . '/templates/';
$smarty->compile_dir = ROOTDIR . '/templates_c/';
$smarty->config_dir = ROOTDIR . '/configs/';
$smarty->cache_dir = ROOTDIR . '/cache/';
$smarty->caching = false;

$smarty->display(ALMIDONDIR.'/tpl/video.tpl');
?>
