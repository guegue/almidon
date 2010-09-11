<?php
define('ADMIN', true);
require($_SERVER['DOCUMENT_ROOT'] . '/../classes/app.class.php');
if (file_exists(ROOTDIR."/templates/admin/index.tpl")) {  
  if(file_exists(ROOTDIR."/templates/admin/header.tpl")) {
    $smarty->assign('header',ROOTDIR.'/templates/admin/header.tpl');
    $smarty->assign('footer',ROOTDIR.'/templates/admin/footer.tpl');
  } else {
    $smarty->assign('header',ALMIDONDIR.'/tpl/header.tpl');
    $smarty->assign('footer', ALMIDONDIR.'/tpl/footer.tpl');
  }
  $smarty->display(ROOTDIR."/templates/admin/index.tpl");
} else {
  $smarty->assign('header',ALMIDONDIR.'/tpl/header.tpl');
  $smarty->assign('footer', ALMIDONDIR . '/tpl/footer.tpl');
  $smarty->display(ALMIDONDIR.'/tpl/index.tpl');
}
