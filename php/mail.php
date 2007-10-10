<?
require_once("/www/guegue.com/classes/panel.class.php");
$user = new usersTable();
//session_start();


if ($_REQUEST['logout']) {
  $_SESSION['panel_logged_in'] = false;
  unset($_SESSION['panel_username']);
}

$logged_in = $_SESSION['panel_logged_in'];

#if ($logged_in) {
  $a = explode("/", $_SERVER['SCRIPT_NAME']);
  $domain = $a[1];
  $username = $_SESSION['panel_username'];
  $idusers = $user->getVar("SELECT users.idusers FROM userservicio JOIN users ON userservicio.idusers = users.idusers WHERE idservicio=10 AND users.username='" . $username . "'");
  $idusers = $user->getVar("SELECT users.idusers FROM userservicio JOIN users ON userservicio.idusers = users.idusers WHERE idservicio=10 AND users.username='stecnico@cepresi.org.ni'");
  $allowed_domain =  $user->getVar("SELECT domain FROM userservicio WHERE idusers='$idusers'");
  if ($domain != $allowed_domain) {
    exit;
  }
  print $username;
  echo ' <a href="?logout=true">salir</a>';
  $user->destroy();
  require_once("/www/guegue.com/classes/mail.class.php");
  $mail = new userTable();
  $object = 'mail';
if (!$object)
  $object = ($_REQUEST['o']) ? $_REQUEST['o'] : $_REQUEST['f'];
$$object->readEnv();
$$object->request['domain_name'] = $domain;
switch ($_REQUEST['action']) {
  case 'edit':
    $edit = true;
    $row = $$object->readRecord();
    break;
  case 'record':
    $row = $$object->readRecord();
    break;
  case 'add':
    $$object->addRecord();
    break;
  case 'delete':
    $$object->deleteRecord();
    break;
  case 'save':
    $$object->updateRecord();
    break;
  case 'dgsave':
    $maxcols = ($_REQUEST['maxcols']) ? $_REQUEST['maxcols'] : MAXCOLS;
    $$object->updateRecord(0, $maxcols, 1);
    break;
}
if ($_REQUEST[$object . 'sort']) $_SESSION[$object . 'sort'] = $_REQUEST[$object . 'sort'];
if ($_REQUEST[$object . 'pg']) $_SESSION[$object . 'pg'] = $_REQUEST[$object . 'pg'];
$$object->order = ($_SESSION[$object . 'sort']) ? $_SESSION[$object .'sort'] : $$object->order;
$$object->pg = $_SESSION[$object . 'pg'];

  $rows = $mail->readDataFilter("domain_name='$domain'");
  $$object->addColumn('quota','int',0,0,0,'Quota');
  foreach ($rows as $key=>$val) {
    $rows[$key]['quota'] = exec("/usr/local/guegue/quota.pl " . $val['user']);
  }
  $smarty->assign('object', $object);
  $smarty->assign('edit', $edit);
  $smarty->assign('row', $row);
  $smarty->assign('dd', $$object->dd);
  $smarty->assign('key', $$object->key);
  $smarty->assign('title', $$object->title);
  $smarty->assign('maxrows', $$object->maxrows);
  $smarty->assign('maxcols', $$object->maxcols);
  $smarty->assign('rows', $rows);
  $smarty->assign('header',"/www/cms/tpl/header.tpl");
  $smarty->display('/www/cms/tpl/normal.tpl');
#}
#else
#  echo "NO";

?>
