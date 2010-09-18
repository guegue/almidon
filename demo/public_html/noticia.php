<?php
require('../classes/app.class.php');
$data = new noticiaTable();

/* testing cache with readDataFilter... */
#$rows = $data->readDataFilter('1=1');

/* testing cache with readDataSQL... */
$rows = $data->readDataSQL('SELECT * FROM noticia');

$smarty->assign('rows',$rows);

#$data->readRows();

$smarty->display($data->name.'.tpl');
