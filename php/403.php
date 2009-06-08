<?php
define('ADMIN', true);
require($_SERVER['DOCUMENT_ROOT'] . '/../classes/app.class.php');
$smarty->caching = false;

require (ALMIDONDIR . '/php/createlinks.php');

if (file_exists(ROOTDIR."/templates/admin/index.tpl")) {  
  if(file_exists(ROOTDIR."/templates/admin/header.tpl"))
  	$smarty->assign('header',ROOTDIR.'/templates/admin/header.tpl');
  else $smarty->assign('header',ALMIDONDIR.'/tpl/header.tpl');
} else {
  if(file_exists(ROOTDIR."/templates/admin/header.tpl"))
  	$smarty->assign('header',ROOTDIR.'/templates/admin/header.tpl');
  else $smarty->assign('header', ALMIDONDIR . '/tpl/header.tpl');
  if(file_exists(ROOTDIR."/templates/admin/footer.tpl"))
  	$smarty->assign('footer',ROOTDIR.'/templates/admin/footer.tpl');
  else $smarty->assign('footer', ALMIDONDIR . '/tpl/footer.tpl');
  if(KINDAMIN===2) $smarty->display(ALMIDONDIR."/tpl/index_sec.tpl");
  else $smarty->display(ALMIDONDIR.'/tpl/index.tpl');
}
