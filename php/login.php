<?php
require_once(ALMIDONDIR . '/php/users.php');

if(!empty($_POST)) {
   //Cargo credenciales y voy a 404 
   check_user($_POST['usrname'],$_POST['pass']);
   $url = ((strpos($_SERVER['REQUEST_URI'],'logout') > 0) || (!isset($_SESSION['credentials'] [$object]))) ? '/admin' : $_SERVER['REQUEST_URI'];
   header("location:". $url);
} else {
     $tpl = 'login';
     $smarty->display(ALMIDONDIR.'/tpl/' . $tpl . '.tpl');
}

?>
