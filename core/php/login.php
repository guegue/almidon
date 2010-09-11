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

if (!isset($_SESSION)) {
  session_start();
}
if (!isset($_SESSION['tries'])) $_SESSION['tries'] = 0;
if ($_POST && !isset($_SESSION)) {
  error_log("ALM: No hay soporte para sesiones.");
  $smarty->assign('sError', true);
  $tpl = 'login';
  $smarty->display(ALMIDONDIR.'/tpl/' . $tpl . '.tpl');	
  exit;
}
$_SESSION['session'] = 1;
require_once(ALMIDONDIR . '/php/lang.php');
require_once(ALMIDONDIR . '/php/users.php');
if(!empty($_POST)) {
  //Cargo credenciales y voy a 404
  if( $_SESSION['tries'] > 3 ) {
    $txtcaptcha = preg_replace('/[^A-Za-z0-9]/', '', $_POST['txtcaptcha']);
    if ((md5($txtcaptcha) === $_SESSION['key']) && check_user($_POST['alm_user'],$_POST['password'])) {
      #error_log("ALM CAPTCHA: Good $txtcaptcha " . md5($txtcaptcha) . "!== " . $_SESSION['key']);
      unset($_SESSION['tries']);
      if(empty($_REQUEST['redirect_to']))
        header('location: ./');
      else header('location: ' . $_REQUEST['redirect_to']);
    } else {
      if (md5($txtcaptcha) !== $_SESSION['key'])
        error_log("ALM CAPTCHA: Wrong $txtcaptcha " . md5($txtcaptcha) . "!== " . $_SESSION['key']);
      $tpl = 'login';
      $smarty->assign('bError', true);
      $smarty->display(ALMIDONDIR.'/tpl/' . $tpl . '.tpl');	
    }
  } else {
    if (check_user($_POST['alm_user'],$_POST['password'])) {
      unset($_SESSION['tries']);
      if(empty($_REQUEST['redirect_to']))
        header('location: ./');
      else header('location: ' . $_REQUEST['redirect_to']);
    } else {
      $_SESSION['tries']++;
      $tpl = 'login';
      $smarty->assign('bError', true);
      $smarty->display(ALMIDONDIR.'/tpl/' . $tpl . '.tpl');
    }
  }
  die();
} else {
  $tpl = 'login';
  $smarty->display(ALMIDONDIR.'/tpl/' . $tpl . '.tpl');
}
