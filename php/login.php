<?php
require_once(ALMIDONDIR . '/php/users.php');

if(!empty($_POST)) {
   //Cargo credenciales y voy a 404 
   check_user($_POST['usrname'],$_POST['pass']);
   header("location:".$_SERVER['REQUEST_URI']);
} else {
     $tpl = 'login';
     $smarty->display(ALMIDONDIR.'/tpl/' . $tpl . '.tpl');
}
