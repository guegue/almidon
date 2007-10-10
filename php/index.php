<?php
define('ADMIN', true);
require("./classes/app.class.php");
if (file_exists("/www/".DOMAIN."/templates/admin/index.tpl")) {  
  if(file_exists("/www/".DOMAIN."/templates/admin/header.tpl"))
  	$smarty->assign('header','/www/'.DOMAIN.'/templates/admin/header.tpl');
  else $smarty->assign('header','/www/cms/tpl/header.tpl');
  $smarty->display("/www/".DOMAIN."/templates/admin/index.tpl");
} else {
  $smarty->display('/www/cms/tpl/index.tpl');
}
?>
