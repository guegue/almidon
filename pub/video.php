<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/../classes/config.php');
require_once('/www/cms/php/db3.class.php');
require_once('Smarty/Smarty.class.php');

function getArgs() {
  $params = explode("/", $_SERVER['PATH_INFO']);
  for($i = 1; $i < sizeof($params); $i++)
    $args[$i] = $params[$i];
  return $args;
}

$smarty = new Smarty;
$smarty->template_dir = ROOTDIR . '/templates/';
$smarty->compile_dir = ROOTDIR . '/templates_c/';
$smarty->config_dir = ROOTDIR . '/configs/';
$smarty->cache_dir = ROOTDIR . '/cache/';
$smarty->caching = false;

$smarty->display('/www/cms/tpl/video.tpl');
?>
