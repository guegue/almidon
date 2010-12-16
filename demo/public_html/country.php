<?php
require('../classes/app.class.php');
$data = new countryTable();
$data->readEnv();
if (isset($data->request['idcountry'])) {
  $row = $data->readRecord();
  $smarty->assign('row',$row);
  if (isset($data->child)) {
    $children = preg_split('/,/',$data->child);
    foreach ($children as $child) {
      $namec = $child . 'Table';
      $datac = new $namec;
      $datac->filter = $datac->name . '.' . $data->key . '=' . $row[$data->key];
      $$child = $datac->readData();
      $smarty->assign($child,$$child);
    }
  }
} else {
  $rows = $data->readData();
  $smarty->assign('rows',$rows);
}
$smarty->display('country.tpl');
