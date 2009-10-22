<?php
/**
 * login.php -
 *
 * hace el login: verifica el user, pass y captcha, deja entrar si acaso...
 * 
 * @copyright &copy; 2005-2009 Guegue Comunicaciones - guegue.com
 * @license http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @version login.php,v 20091022 Alejandro y Alexis
 * @package almidon
 */
session_start();
if ($_POST && !$_SESSION['session']) {
  error_log("ALM: No hay soporte para sesiones.");
  $smarty->assign('sError', true);
  $tpl = 'login';
  $smarty->display(ALMIDONDIR.'/tpl/' . $tpl . '.tpl');	
  exit;
}
$_SESSION['session'] = 1;
require_once(ALMIDONDIR . '/php/users.php');
if(!empty($_POST)) {
  //Cargo credenciales y voy a 404 
  $txtcaptcha = preg_replace('/[^A-Za-z0-9]/', '', $_POST['txtcaptcha']);
  if ((md5($txtcaptcha) === $_SESSION['key']) && check_user($_POST['almuser'],$_POST['password'])) {
    error_log("ALM CAPTCHA: Good $txtcaptcha " . md5($txtcaptcha) . "!== " . $_SESSION['key']);
    header('location: ./');
  } else {
    if (md5($txtcaptcha) !== $_SESSION['key'])
      error_log("ALM CAPTCHA: Wrong $txtcaptcha " . md5($txtcaptcha) . "!== " . $_SESSION['key']);
    $tpl = 'login';
    $smarty->assign('bError', true);
    $smarty->display(ALMIDONDIR.'/tpl/' . $tpl . '.tpl');	
  }
} else {
  $tpl = 'login';
  $smarty->display(ALMIDONDIR.'/tpl/' . $tpl . '.tpl');
}
