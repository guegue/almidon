<?php
require('../classes/app.class.php');
$data = new paginaTable();
$id = $_SERVER['SCRIPT_NAME'];
$id = substr($id, strrpos($id, '/')+1, strrpos($id, '.') - (strrpos($id, '/') + 1));
$id = ($id) ? $id : 'index';
$row = $data->readRecord($id);
$smarty->assign('row', $row);
$lang = substr($_SERVER['REQUEST_URI'],-3);
if (preg_match('/\.es|\.en|\.fr|\.de/',$lang)) $extra_lang = substr($lang,-3);
$smarty->assign('header', $smarty->template_dir . '/header'.$extra_lang.'.tpl');
$smarty->assign('footer', $smarty->template_dir . '/footer'.$extra_lang.'.tpl');
$smarty->assign('title', $row['pagina']);
$smarty->display('/www/cms/tpl/pagina.tpl');
$data->destroy();
?>
