<?php
require('../classes/app.class.php');
$data = new noticiaTable();
$data->readEnv();
if (isset($data->request['idnoticia'])) {
  $row = $data->readRecord();
  $smarty->assign('row',$row);
} else {
  $rows = $data->readData();
  $smarty->assign('rows',$rows);
}
$smarty->display('noticia.tpl');
