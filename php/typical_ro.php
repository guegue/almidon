<?php
if (!$object)
  $object = ($_REQUEST['o']) ? $_REQUEST['o'] : $_REQUEST['f'];
$$object->readEnv();
switch ($_REQUEST['action']) {
  case 'record':
    $row = $$object->readRecord();
    break;
}
if ($_REQUEST[$object . 'sort']) $_SESSION[$object . 'sort'] = $_REQUEST[$object . 'sort'];
if ($_REQUEST[$object . 'pg']) $_SESSION[$object . 'pg'] = $_REQUEST[$object . 'pg'];
$$object->order = ($_SESSION[$object . 'sort']) ? $_SESSION[$object .'sort'] : $$object->order;
$$object->pg = $_SESSION[$object . 'pg'];
$smarty->assign('object', $object);
$smarty->assign('edit', $edit);
$smarty->assign('cmd', false);
$smarty->assign('row', $row);
$smarty->assign('options', $options);
$smarty->assign('rows', $$object->readData());
$smarty->assign('dd', $$object->dd);
$smarty->assign('key', $$object->key);
$smarty->assign('key1', $$object->key1);
$smarty->assign('key2', $$object->key2);
$smarty->assign('title', $$object->title);
$smarty->assign('maxrows', $$object->maxrows);
$smarty->assign('maxcols', $$object->maxcols);
$$object->destroy();
?>
