<?php
require('../classes/app.class.php');
$data = new noticiaTable();
$data->readRows();
$smarty->display($data->name.'.tpl');
