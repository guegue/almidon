<?php
define('ADMIN', true);
require("./classes/app.class.php");
if (file_exists(ROOTDIR."/templates/admin/index.tpl")) {  
  if(file_exists(ROOTDIR."/templates/admin/header.tpl"))
  	$smarty->assign('header',ROOTDIR.'/templates/admin/header.tpl');
  else $smarty->assign('header',ALMIDONDIR.'/tpl/header.tpl');
  $smarty->display(ROOTDIR."/templates/admin/index.tpl");
} else {
  $smarty->display(ALMIDONDIR.'/tpl/index.tpl');
}
?>
