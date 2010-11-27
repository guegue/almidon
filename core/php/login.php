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
if ($_POST && !isset($_SESSION)) {
  error_log("ALM: No hay soporte para sesiones.");
  $smarty->assign('sError', true);
  $tpl = 'login';
  $smarty->display(ALMIDONDIR.'/pub/themes/' . ALM_ADMIN_THEME . '/tpl/' . $tpl . '.tpl');	
  exit;
}
$_SESSION['session'] = 1;
require_once(ALMIDONDIR . '/php/lang.php');
require_once(ALMIDONDIR . '/php/users.php');
require_once(ALMIDONDIR . '/php/ip_in_range.php');

# Loading the whitelist address
if ( !defined('ALM_WHITELIST') ) define('ALM_WHITELIST','127.0.0.1/32,::1'); # IPv4,IPv6
$whitelist_ips = explode(',',ALM_WHITELIST);
# Verifyin localhost is in the list
if ( !in_array('127.0.0.1',$whitelist_ips) ) $whitelist_ips[] = '127.0.0.1/32';
if ( !in_array('::1',$whitelist_ips) ) $whitelist_ips[] = '::1';
# Verifying if the address if whitelisting
$ip = explode(',', (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])&&$_SERVER['REMOTE_ADDR']=='127.0.0.1'?$_SERVER['HTTP_X_FORWARDED_FOR']:$_SERVER['REMOTE_ADDR']));
$ip = trim($ip[count($ip) - 1]);
$whitelist = ip_in_range($ip,$whitelist_ips);
$smarty->assign('whitelist',$whitelist);

if(!empty($_POST)) {
  # Aqui tomo en cuenta que el servidor puede estar usando varnish
  //Cargo credenciales y voy a 404
  $txtcaptcha = preg_replace('/[^A-Za-z0-9]/', '', $_POST['txtcaptcha']);
  if (!$whitelist && (md5($txtcaptcha) === $_SESSION['key']) && check_user($_POST['alm_user'],$_POST['password'])) {
    #error_log("ALM CAPTCHA: Good $txtcaptcha " . md5($txtcaptcha) . "!== " . $_SESSION['key']);
    if(empty($_REQUEST['redirect_to']))
      header('location: ./');
    else header('location: ' . $_REQUEST['redirect_to']);
    exit;
  } elseif ($whitelist && check_user($_POST['alm_user'],$_POST['password'])) {
    if(empty($_REQUEST['redirect_to']))
      header('location: ./');
    else header('location: ' . $_REQUEST['redirect_to']);
    exit;
  } else {
    if (!$whitelist && md5($txtcaptcha) !== $_SESSION['key'])
      error_log("ALM CAPTCHA: Wrong $txtcaptcha " . md5($txtcaptcha) . "!== " . $_SESSION['key']);
    else 
      error_log("ALM AUTH: Wrong auth data for user " . $_POST['alm_user']);
    $tpl = 'login';
    $smarty->assign('bError', true);
    $smarty->display(ALMIDONDIR.'/pub/themes/' . ALM_ADMIN_THEME . '/tpl/' . $tpl . '.tpl');	
  }
} else {
  $tpl = 'login';
  $smarty->display(ALMIDONDIR.'/pub/themes/' . ALM_ADMIN_THEME . '/tpl/' . $tpl . '.tpl');
}
