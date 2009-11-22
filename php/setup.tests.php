<?php
function performTests() {
    global $failed, $test_output, $action, $admin_db_failed, $public_db_failed, $admin_dsn, $public_dsn, $smarty, $global_dd, $alm_connect;
    $failed = false;
    $red = '<font color="red">FALL&Oacute;</font>';
    $green = '<font color="green">PAS&Oacute;</font>';
    $test_output = "Probando conexion a base de datos (admin)... ";
    $db = almdata::connect ($admin_dsn);
    if (almdata::basicError($db, $admin_dsn) || !$alm_connect[$admin_dsn]) {
      $error_msg = almdata::basicError($db, $admin_dsn);
      $test_output .= "$red <i>$error_msg</i><br/>";
      $failed = true;
      $admin_db_failed = true;
    } else {
      $test_output .= "$green<br/>";
    }
    $test_output .= "Probando conexion a base de datos (public)... ";
    $db = almdata::connect ($public_dsn);
    if (almdata::basicError($db, $public_dsn) || !$alm_connect[$public_dsn]) {
      $error_msg = almdata::basicError($db, $public_dsn);
      $test_output .= "$red <i>$error_msg</i><br/>";
      $failed = true;
      $public_db_failed = true;
    } else {
      $test_output .= "$green<br/>";
    }
    $test_output .= "Probando configuracion de PHP... ";
    if (get_cfg_var('short_open_tag') != 1) {
      $test_output .= "$red <i>short_open_tag = ".get_cfg_var('short_open_tag')."</i><br/>";
      $failed=true;
    } else {
      $test_output .= "$green<br/>";
    }
    $test_output .= "Probando permisos de directorios... ";
    if (checkPerms($smarty->compile_dir) !== true)
      $test_output .= "$red <i> $smarty->compile_dir = ".checkPerms($smarty->compile_dir)."</i><br/>";
    if (checkPerms($smarty->cache_dir) !== true)
      $test_output .= "$red <i> $smarty->cache_dir = ".checkPerms($smarty->cache_dir)."</i><br/>";
    $logs_dir = ROOTDIR . '/logs';
    if (checkPerms($logs_dir) !== true)
      $test_output .= "$red <i> $logs_dir = ".checkPerms($logs_dir)."</i><br/>";
    $files_dir = ROOTDIR . '/files';
    if (checkPerms($files_dir) !== true)
      $test_output .= "$red <i> $files_dir = ".checkPerms($files_dir)."</i><br/>";
    if (checkPerms($smarty->compile_dir) === true && checkPerms($smarty->cache_dir) === true) {
      $test_output .= "$green<br/>";
    } else {
      $failed=true;
    }
    $test_output .= "Dónde está almidón? ";
    if (defined('ALMIDONDIR')) {
      $test_output .= '<font color="green">'.ALMIDONDIR.'</font><br/>';
    } else {
      $failed=true;
      $test_output .= $red;
    }
    $test_output .= "BD Almidonizada? ";
    list($type,$tmp) = split('://',$admin_dsn);
    if ($type == 'pgsql') {
      $sqlcmd = "SELECT relname FROM pg_class WHERE  pg_class.relkind = 'r' AND pg_class.relname LIKE 'alm_%'";
    } elseif($type == 'mysql') {
      $sqlcmd = "SHOW TABLES LIKE 'alm_%';";
    }
    $data = new Data();
    $var = @$data->getList($sqlcmd);
    if (count($var) >= 5) {
       $test_output .= '<font color="green">'.print_r($var,1).'</font>';
    } else {
      #$failed = true;
      $test_output .= $red;
    }
    if ($failed) {
      $action='failed';
      $test_output .= '<br/><br/><font color="red">Por favor corregir antes de continuar con la instalaci&oacute;n</font>';
    }
}
function checkPerms($filepath) {
  if (!file_exists($filepath)) return "No existe.";
  if (is_writeable($filepath)) return true;
  else return "Sin permisos de escritura.";
}
