<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../classes/app.class.php');
function performTests() {
    global $failed, $test_output, $action, $admin_db_failed, $public_db_failed, $admin_dsn, $public_dsn, $smarty;
    $failed = false;
    $red = '<font color="red">FALL&Oacute;</font>';
    $green = '<font color="green">PAS&Oacute;</font>';
    $test_output = "Probando conexion a base de datos (admin)... ";
    $db =& MDB2::connect ($admin_dsn);
    if (PEAR::isError($db)) {
      $error_msg = $db->getMessage();
      $test_output .= "$red <i>$error_msg</i><br/>";
      $failed = true;
      $admin_db_failed = true;
    } else {
      $test_output .= "$green<br/>";
    }
    $test_output .= "Probando conexion a base de datos (public)... ";
    $db =& MDB2::connect ($public_dsn);
    if (PEAR::isError($db)) {
      $error_msg = $db->getMessage();
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
    if ($failed) {
      $action='failed';
      $test_output .= '<br/><br/><font color="red">Por favor corregir antes de continuar con la instalaci&oacute;n</font>';
    }
}
if (!defined('ADMIN')) define ('ADMIN', true);
function checkPerms($filepath) {
  if (!file_exists($filepath)) return "No existe.";
  if (is_writeable($filepath)) return true;
  else return "Sin permisos de escritura.";
}
function getTitle($object) {
  $o = $object . "Table";
  $data = new $o;
  return $data->title;
}
function genLinks($object) {
  $o = $object . "Table";
  $data = new $o;
  if($data->definition)
  $dd = array();
  foreach($data->definition as $column) {
    if ($column['references'] != '0') $dd[] = $column['references'];
  }
  return $dd;
}
function genDD($object) {
  $o = $object . "Table";
  $data = new $o;
  if($data->definition)
  $dd = array();
  foreach($data->definition as $column) {
    if ($column['size'] == '0') $column['size'] = null;
    if ($column['name'] == $data->key) $column['PK'] = true;
    if ($column['references'] == '0') $column['references'] = null;
    $col = array($column['name'],$column['type'],$column['size'],$column['references'],$column['label'],$column['PK']);
    $dd[] = $col;
  }
  return $dd;
}
function genColumnSQL($column, $dbtype, $key = false) {
  $type = $column['type'];
  if ($type == 'external') return;
  if ($type == 'file' || $type == 'image' || $type == 'autoimage') {
    $type = 'varchar';
    $size = '500';
  } elseif ($type == 'html' || $type == 'xhtml') {
    $type = 'text';
    $size = null;
  } elseif ($type == 'datetime') {
    $type = 'timestamp';
    $size = null;
  } elseif ($type == 'auth_user') {
    $type = 'varchar';
    $size = '32';
  } elseif ($type == 'datenull') {
    $type = 'date';
    $size = null;
  }
  if ($dbtype == 'pgsql') {
    if ($type == 'order') $type = 'serial NULL';
  } elseif ($dbtype == 'mysql') {
    if ($type == 'order' ||  $type == 'serial') $type = 'int AUTO_INCREMENT';
  }
  $size = $column['size'];
  $size = preg_replace("/\./", ",", $size);
  $sql .= "  ".$column['name']." ".$type;
  if ($size) $sql .= " (".$size.")";
  if ($key) $sql .= " PRIMARY KEY NOT NULL";
  if ($column['references']) $sql .= " REFERENCES ".$column['references'];
  return $sql;
}
function genSQL($object) {
  $o = $object . "Table";
  $data = new $o;
  $dbtype = $data->database->dsn['phptype'];
  $sql = "CREATE TABLE $data->name (\n";
  $i = 0;
  if($data->definition)
  foreach($data->definition as $column) {
    unset($size);
    if ($type == 'external') next($data->definition);
    if ($i) $sql .= " ,\n";
    $sql .= genColumnSQL($column, $dbtype, $column['name'] == $data->key );
    ++$i;
  }
  $sql .= "\n);\n\n";
  return($sql);
}

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

if ($action == 'fixdb') {
  $config = file_get_contents(ROOTDIR.'/classes/config.ori.php');
  # $admin_dsn = 'pgsql://almidondemo:secreto1@/almidondemo';
  $pahost = ($_REQUEST['phost'] == '(local)') ? '' : $_REQUEST['phost'];
  $ahost = ($_REQUEST['host'] == '(local)') ? '' : $_REQUEST['host'];
  $admin_dsn = $_POST['type'] . '://' . $_POST['username'] . ':' . $_REQUEST['pass'] . '@'.$ahost.'/' . $_REQUEST['dbname'];
  $public_dsn = $_POST['ptype'] . '://' . $_POST['pusername'] . ':' . $_REQUEST['ppass'] . '@'.$pahost.'/' . $_REQUEST['pdbname'];
  $config = preg_replace('/admin_dsn = (.*)/',"admin_dsn = '$admin_dsn';",$config);
  $config = preg_replace('/public_dsn = (.*)/',"public_dsn = '$public_dsn';",$config);
  if (!is_writable(ROOTDIR.'/classes/config.php')) {
    print "No se puede escribir en classes/config.php. Copiar el siguiente c&oacute;digo manualmente a config.php:<br/><br/>\n";
    print '<table bgcolor="#f0f0f0" border="1" width="100%"><tr><td><pre>';
    print htmlentities($config);
    print "</pre></td></tr></table>";
  } else {
    $fp = fopen(ROOTDIR.'/classes/config.php', 'w');
    fwrite($fp, $config);
    fclose($fp);
    print "Se ha actualizado la configuraci&oacute;n.<br/>";
  }
  print '<a href="setup">Continuar con instalaci&oacute;n...</a>';
  exit;
} else {
  performTests();
}

$options = array(
  'test'=>'Probar configuraci&oacute;n',
  'tables'=>'Probar tablas y base de datos',
  'sql'=>'Generar SQL basado en tables.class',
  'dd'=>'Generar diccionario de datos',
  'erd'=>'Generar diagrama entidad relacion',
  'erdcol'=>'Generar diagrama entidad relacion detallado');
if ($action != 'erd' && $action != 'erdcol' && !$failed) {
  print "Herramientas:<br/>";
  foreach($options as $k=>$option) {
    print "<li><a href=\"?action=$k\">$option</a><br/></li>";
  }
}
if (!empty($action)) {
  $classes = get_declared_classes();
  $output = '';
  foreach($classes as $key) {
    if (stristr($key, 'table') && $key != 'table' && $key != 'tabledoublekey' && $key != 'Table' && $key != 'TableDoubleKey') {
      if(substr($key, 0, strpos($key, 'Table')) !== false) $key = substr($key, 0, strpos($key, 'Table'));
      else $key = substr($key, 0, strpos($key, 'table'));
      $tables[] = $key;
    }
  }
  switch ($action) {
  case 'tables':
    $sql_fix = '';
    $tables_failed = false;
    $red = '<font color="red">FALL&Oacute;</font>';
    $green = '<font color="green">PAS&Oacute;</font>';
    $tables_output = 'Probando tablas en base de datos:<br/>';
    foreach($tables as $key) {
      $sql = genSQL($key);
      $keyTable = $key . 'Table';
      $data = new $keyTable;
      $dbtype = $data->database->dsn['phptype'];
      #error_reporting(0);
      #if (!isset($id)) $id = $data->getVar("SELECT MAX(" . $data->key . ") FROM " . $data->name);
      if ($data->join) {
        #$sqlcmd = "SELECT $data->all_fields FROM $data->name " . $data->getJoin() . " WHERE $data->name.$data->key = '$id'";
        $sqlcmd = "SELECT $data->all_fields FROM $data->name " . $data->getJoin() . " LIMIT 1";
      } else {
        #$sqlcmd = "SELECT $data->fields FROM $data->name WHERE $data->name.$data->key = '$id'";
        $sqlcmd = "SELECT $data->fields FROM $data->name LIMIT 1";
      }
      $tables_output .= "Tabla $key ";
      @$data->execSql($sqlcmd);
      if (PEAR::isError($data->data)) {
        $error_msg = $data->data->getMessage();
        $native_msg = preg_replace('/(.*)Native message:(.*)\]/s','\2',$data->data->userinfo);
        $error_msg = '<small>'.preg_replace('/(.*)\n(.*)/','\1',$native_msg).'</small>';
        $tables_output .= "$red <!-- <i>$error_msg</i> --><br/>";
        $tables_failed = true;
        $sqlcmd = "SELECT * FROM $data->name";
        @$data->execSql($sqlcmd);
        if (PEAR::isError($data->data)) {
          $tables_output .= "&nbsp;&nbsp;&nbsp;&nbsp;<small>Error general en la tabla <!--".$data->data->getMessage() . "--></small><br/>";
          $sql_fix .= genSQL($data->name);
        } else {
          $campos = split(',',$data->fields);
          foreach($campos as $campo) {
            $sqlcmd = "SELECT $campo FROM $data->name";
            @$data->execSql($sqlcmd);
            if (PEAR::isError($data->data)) {
              $tables_output .= "&nbsp;&nbsp;&nbsp;&nbsp;<small>Error en campo $campo <!--".$data->data->getMessage() . "--></small><br/>";
              $size = ($data->dd[$campo]['size'] > 0) ? '('.$data->dd[$campo]['size'].')': '';
              $sql_fix .= "ALTER TABLE $data->name ADD COLUMN " . genColumnSQL($data->dd[$campo], $dbtype, $dd[$campo]['name'] == $data->key).";\n";
            }
          }
        }
      } else
        $tables_output .= "$green<br/>";
    }
    break;
  case 'sql':
    foreach($tables as $key)
      $output .= genSQL($key);
    break;
  case 'dd':
    foreach($tables as $key) {
      $dd = genDD($key);
      $output .= "\n".'<tr align="center"><td colspan="5" bgcolor="#f0f0f0"><br/>'.getTitle($key)." ($key)</br><br/></td></tr>\n";
      $output .= "\n<tr><th>Nombre</th><th>Tipo</th><th>Tama&ntilde;o</th><th>Referencias</th><th>Descripci&oacute;n</th></tr>\n";
      foreach($dd as $col) {
        $color = ($col[5]) ? '#bbbbbb' : '#ffffff';
        unset($col[5]);
        unset($col[6]);
        $output .= "<tr bgcolor=\"$color\">";
        foreach($col as $val)
          $output .= "<td>$val&nbsp;</td>";
        $output .= "</tr>\n";
      }
    }
    $output = "\n<table border=\"1\" cellpadding=\"1\" cellspacing=\"1\">$output</table>\n";
    break;
  case 'erdcol':
    $output = "\ndigraph g {\ngraph [\nrankdir = \"LR\"\n];\nnode [\nfontsize = \"16\"\nshape = \"ellipse\"\n];\nedge [\n];\n";
    foreach($tables as $key) {
      $j = 0;
      $output .= "\"$key\" [\nlabel = " . '< <TABLE BORDER="1" CELLBORDER="0" CELLSPACING="0"> <TR ><TD PORT="ltcol0"> </TD> <TD bgcolor="grey90" border="1" COLSPAN="4"> \N </TD> <TD PORT="rtcol0"></TD></TR>';
      $dd = genDD($key);
      foreach($dd as $col) {
        $i = 0;
        $output .= '<TR><TD PORT="ltcol'.$j.'" ></TD><TD align="left">'. $col[0] .'</TD><TD align="left">'. $col[1] . (($col[2]) ? '('.$col[2].')' : '') .'</TD><TD align="left">'. (($col[5]) ? 'PK' : '') .'</TD><TD align="left">'. (($col[3]) ? 'FK' : '') .'</TD><TD align="left" PORT="rtcol'.$j.'"> </TD></TR>';
        if ($col[3]) {
          $links .= "\"$key\":rtcol$j -> \"$col[3]\":ltcol0 [\nid = $i\n];\n";
          $i++;
        }
        $j++;
      }
      $output .= '</TABLE> >';
      $output .= "\nshape = \"plaintext\"\n];\n";
    }
    $output .= $links;
    $output .= "}\n";
    break;
  case 'erd':
    $output = "\ndigraph g {\ngraph [\nrankdir = \"LR\"\n];\nnode [\nfontsize = \"16\"\nshape = \"ellipse\"\n];\nedge [\n];\n";
    foreach($tables as $key) {
      $output .= "\"$key\" [\nlabel = \"<f0> $key| <f1>\"\nshape = \"record\"\n];\n";
      $dd = genLinks($key);
      $i = 0;
      foreach($dd as $link) {
        $output .= "\"$key\":f0 -> \"$link\":f0 [\nid = $i\n];\n";
        $i++;
      }
    }
    $output .= "}\n";
    break;
  case 'test':
    performTests();
    break;
  }
  switch($action) {
  case 'failed':
  case 'test':
    if (isset($test_output)) print $test_output;
    if (isset($admin_db_failed) || isset($public_db_failed)) {
      print '<br/>Revise los datos de conexi&oacute;n en classes/config.php: <br/><!--<table border="1"><tr><td>'.DSN.'</td></tr></table>-->';
      list($ptype,$ptmp) = split('://',$public_dsn);
      list($pauth,$pdbname) = split('/',$ptmp);
      list($pauth,$phost) = split('@',$pauth);
      $pahost = empty($phost) ? '(local)' : $phost;
      list($pusername,$ppass) = split(':',$pauth);
      list($type,$tmp) = split('://',$admin_dsn);
      list($auth,$dbname) = split('/',$tmp);
      list($auth,$host) = split('@',$auth);
      $ahost = empty($host) ? '(local)' : $host;
      list($username,$pass) = split(':',$auth);
      print "<form action=\"\" method=\"POST\">";
      print "<input type=\"hidden\" name=\"action\" value=\"fixdb\"/>";
      print "<table><tr><td></td><td>ADMIN</td><td>PUBLIC</td></tr>";
      print "<tr><td>Host:</td> <td><input type=\"text\" name=\"host\" value=\"$ahost\"/><br/></td> <td><input type=\"text\" name=\"phost\" value=\"$pahost\"/><br/></td></tr>";
      print "<tr><td>Tipo de base de datos:</td> <td><select name=\"type\"><option value=\"mysql\""; print ($type=='mysql') ? ' selected' : ''; print ">mysql</option>";
      print "<option value=\"pgsql\""; print ($type=='pgsql') ? ' selected': ''; print ">pgsql</option></select><br/></td>";
      print "<td><select name=\"ptype\"><option value=\"mysql\""; print ($ptype=='mysql') ? ' selected' : ''; print ">mysql</option>";
      print "<option value=\"pgsql\""; print ($ptype=='pgsql') ? ' selected': ''; print ">pgsql</option></select><br/></td></tr>";
      print "<tr><td>Nombre de la base de datos:</td> <td><input type=\"text\" name=\"dbname\" value=\"$dbname\"/><br/></td> <td><input type=\"text\" name=\"pdbname\" value=\"$pdbname\"/><br/></tr>"; 
      print "<tr><td>Usuario:</td> <td><input type=\"text\" name=\"username\" value=\"$username\"/><br/></td> <td><input type=\"text\" name=\"pusername\" value=\"$pusername\"/><br/></td></tr>";
      print "<tr><td>Password:</td> <td><input type=\"text\" name=\"pass\" value=\"$pass\"/><br/></td> <td><input type=\"text\" name=\"ppass\" value=\"$ppass\"/><br/></td</tr>";
      print "</table>";
      print "<input type=\"submit\" value=\"Guardar\"/>";
      print "</form>";
      // connect to a database named "mary" on "localhost" at port "5432"
      $myhost = empty($host) ? '' : $host;
    }
    break;
  case 'tables':
    print "$tables_output";
    if ($tables_failed) {
      print '<br/><font color="red">Debes corregir estos errores en la base de datos.</font><br/>Puedes ayudarte del <a href="?action=sql">sql generado desde tables.class</a> o del texto a continuaci&oacute;n:';
      if (isset($sql_fix))
        print '<pre>'.$sql_fix.'</pre>';
    }
    break;
  case 'erd':
  case 'erdcol':
    header('Content-type: plain/text');
    header('Content-Disposition: attachment; filename="erd.dot"');
    print $output;
    break;
  case 'sql':
    print "<pre>$output</pre>";
    break;
  case 'dd':
    print "$output";
    break;
  }
}
