<?php
if (!$object)
  $object = ($_REQUEST['o']) ? $_REQUEST['o'] : $_REQUEST['f'];
$$object->readEnv();
//error_log($_REQUEST['action'] . "action");
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
foreach ($$object->dd as $key => $val)
  if ($$object->dd[$key]['references']) {
    if (preg_match("/\|\|/", $$object->dd[$key]['extra'])) {
      $ot = $$object->dd[$key]['references'] . 'Table';
      $robject = new $ot;
      $options[$key] = $$object->selectMenu("SELECT " . $robject->key . ", " . $$object->dd[$key]['extra'] . " AS " . $$object->dd[$key]['references'] . " FROM " . $$object->dd[$key]['references']);
    } else {
      if ($filter)
        $options[$key] = $$object->selectMenu("SELECT idnoticia,noticia FROM noticia WHERE " . $filter);
      else
        $options[$key] = $$object->selectMenu($$object->dd[$key]['references']);
    }
  }
$smarty->assign('object', $object);
$smarty->assign('edit', $edit);
$smarty->assign('row', $row);
$smarty->assign('options', $options);
//if ($_SESSION[DOMAIN . "_filter"]) {
  //if ($filter)
    //$smarty->assign('rows', $$object->readDataFilter($filter));
  //else
    //$smarty->assign('rows', $$object->readDataFilter($_SESSION[DOMAIN . "_filter"]));
  //$smarty->assign('preset', $_SESSION[DOMAIN . "_key"]);
//} else {
  $smarty->assign('rows', $$object->readData());
//}
$smarty->assign('dd', $$object->dd);
$smarty->assign('key', $$object->key);
$smarty->assign('key1', $$object->key1);
$smarty->assign('key2', $$object->key2);
$smarty->assign('title', $$object->title);
$smarty->assign('maxrows', $$object->maxrows);
$smarty->assign('maxcols', $$object->maxcols);
?>
