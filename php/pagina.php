<?php
define('ADMIN', true);
require("./classes/app.class.php");
$pagina = new paginaTable;
$pagina->readEnv();
$object = ($_REQUEST['o']) ? $_REQUEST['o'] : $_REQUEST['f'];
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
$pagina->order = $_SESSION['paginasort'];
$pagina->pg = $_SESSION['paginapg'];

$smarty->assign('object', pagina);
$smarty->assign('edit', $edit);
$smarty->assign('row', $row);
$smarty->assign('rows', $pagina->readData());
$smarty->assign('dd', $pagina->dd);
$smarty->assign('key', $pagina->key);
$smarty->assign('title', $pagina->title);
$smarty->assign('options', $options);
$smarty->display('admin/normal.tpl');
$pagina->destroy();
?>
