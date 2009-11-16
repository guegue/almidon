<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../classes/app.class.php');
$alm_tables = "/^(alm_table|alm_user|alm_access|alm_role|alm_column)$/";
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
    $test_output .= "BD Almidonizada? ";
    $sqlcmd = "SELECT relname FROM pg_class WHERE  pg_class.relkind = 'r' AND pg_class.relname LIKE 'alm_%'";
    $data = new Data();
    $var = $data->getList($sqlcmd);
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
    else $column['PK'] = null;
    if ($column['references'] == '0') $column['references'] = null;
    $col = array($column['name'],$column['type'],$column['size'],$column['references'],$column['label'],$column['PK']);
    $dd[] = $col;
  }
  return $dd;
}
function genColumnSQL($column, $dbtype, $key = false) {
  $sql = '';
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
    if (isset($type) && $type == 'external') next($data->definition);
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
  'tables'=>'Aplicar cambios a BD desde tables.class.php',
  'alm_tables'=>'Almidonizar BD (crea alm_tables, etc)',
  'autotables'=>'Generar tables.class.php desde alm_tables',
  'sql2almidon'=>'Generar tables.class.php desde BD',
  'exec'=>'Ejecutar c&oacute;digo SQL',
  'sql'=>'Ver SQL basado en tables.class',
  'dd'=>'Ver diccionario de datos',
  'erd'=>'Ver diagrama entidad relacion',
  'erdcol'=>'Ver diagrama entidad relacion detallado');
if ($action != 'erd' && $action != 'erdcol' && !$failed) {
  print "<html>\n<head>\n<title>Almidon - Setup</title>\n</head>\n<body>\n";
  print "<small>Herramientas:<br/>";
  foreach($options as $k=>$option) {
    print "<li><a href=\"?action=$k\">$option</a><br/></li>";
  }
  print '<li><a href="./">Regresar a administraci&oacute;n</a><br/></li>';
  print "</small>";
}
if (!empty($action)) {
  $classes = get_declared_classes();
  $output = '';
  foreach($classes as $key) {
    if (stristr($key, 'table') && $key != 'table' && $key != 'tabledoublekey' && $key != 'Table' && $key != 'TableDoubleKey') {
      if(substr($key, 0, strpos($key, 'Table')) !== false) $key = substr($key, 0, strpos($key, 'Table'));
      else $key = substr($key, 0, strpos($key, 'table'));
      if(!preg_match($alm_tables, $key))
        $tables[] = $key;
    }
  }
  switch ($action) {
  case 'alm_tables':
    $alm_sqlcmd = file_get_contents(ALMIDONDIR . '/sql/almidon.sql');
    $alm_tables_sqlcmd = file_get_contents(ALMIDONDIR . '/sql/tables.sql');
    $data = new Data();
    $sqlcmd = "SELECT relname FROM pg_class WHERE  pg_class.relkind = 'r' AND pg_class.relname LIKE 'alm_%'";
    $data = new Data();
    $data->execSql($sqlcmd);
    $var = $data->getList($sqlcmd);
    if (count($var) >= 5) {
       $output .= 'Tablas de almidon ya existen. Re-generando solo meta-datos.<br/>';
      $data->execSql($alm_tables_sqlcmd);
    } else {
      $data->execSql($alm_sqlcmd);
      $output = "BD Almidonizada!<br/>Codigo SQL aplicado:<br/><pre>$alm_sqlcmd</pre><br/>";
    }
    $alm_table = new alm_tableTable();
    $alm_column = new alm_columnTable();
    # Nota: no hay soporte para TableDoubleKey yet...
    $rank = 1;
    $output .= "Re-generando: ";
    foreach($tables as $key) {
      $keyTable = $key . 'Table';
      $data = new $keyTable;
      $alm_column->execSql("DELETE FROM alm_column WHERE idalm_table='$key'");
      $alm_table->deleteRecord($key);
      $alm_table->request['idalm_table'] = $key;
      $alm_table->request['key'] = $data->key;
      $alm_table->request['alm_table'] = $data->name;
      $alm_table->request['orden'] = $data->order;
      $alm_table->request['rank'] = $rank;
      $alm_table->addRecord();
      $rank++;
      $i = 1;
      if($data->definition)
        foreach($data->definition as $column) {
          if (isset($type) && $type == 'external') next($data->definition);
          $alm_column->request['idalm_table'] = $key;
          $alm_column->request['idalm_column'] = $column['name'];
          $alm_column->request['type'] = $column['type'];
          $alm_column->request['size'] = $column['size'];
          $alm_column->request['pk'] = ($column['name'] == $data->key) ? '1' : '0';
          $alm_column->request['fk'] = $column['references'];
          $alm_column->request['alm_column'] = $column['label'];
          $alm_column->request['rank'] = $i;
          $alm_column->request['extra'] = '';
          $alm_column->addRecord();
          ++$i;
        }
      $output .= "$key ";
      }
    break;
  case 'sql2almidon':
    $output = '';
    #usa mismo colnames que alm_table para facilitar luego usar el mismo codigo como parser
    #$sqlcmd = "SELECT c.oid, c.relname AS idalm_table FROM pg_class c LEFT JOIN pg_namespace n ON n.oid = c.relnamespace WHERE  c.relkind = 'r' AND n.nspname='public';";
    $sqlcmd = "SELECT c.oid, c.relname AS idalm_table FROM pg_class c LEFT JOIN pg_namespace n ON n.oid = c.relnamespace WHERE  c.relkind = 'r' AND n.nspname='public' AND c.relname NOT LIKE 'alm_%';";
    $data = new Data();
    $data->execSql($sqlcmd);
    $sqldata = $data->getArray();
    foreach ($sqldata as $table_datum) {
      $table_output = '';
      $table_output .= "class " . $table_datum['idalm_table'] . "Table extends Table {\n";
      $table_output .= "  function ".$table_datum['idalm_table']."Table() {\n";
      $table_output .= "    \$this->Table('".$table_datum['idalm_table']."');\n";
      $table_output .= "    \$this->key = '".$table_datum['idalm_table']."';\n";
      $table_output .= "    \$this->title ='".$table_datum['idalm_table']."';\n";
      #$output .= "    \$this->order ='".$table_datum['orden']."';\n";
      $sqlcmd = "SELECT attname AS idalm_column,pg_type.typname AS type, atttypmod-4 AS size, contype AS key, (SELECT relname FROM pg_class WHERE pg_class.oid=confrelid) AS fk FROM pg_catalog.pg_attribute JOIN pg_type ON atttypid=pg_type.oid LEFT OUTER JOIN pg_constraint ON attrelid=conrelid AND attnum = ANY (conkey) WHERE attname NOT IN ('xmin','cmin','cmax','xmax','max_value','min_value','ctid','tableoid') AND attrelid='".$table_datum['oid']."' AND NOT attisdropped";
      $data->execSql($sqlcmd);
      $cols = $data->getArray();
      $key = null;
      $key1 = null;
      $key2 = null;
      foreach ($cols as $datum) {
        if ($datum['size'] <= 0) $datum['size'] = 0;
        if ($datum['type'] == 'int4') $datum['type'] = 'int';
        $datum['pk'] = 0;
        if ($datum['key'] == 'p') {
          $datum['pk'] = 1;
          if (!empty($key)) {
            $key1 = $key;
            $key2 = $datum['idalm_column'];
            $key = null;
          } else {
            $key = $datum['idalm_column'];
          }
        }
        if (empty($datum['fk'])) $datum['fk'] = 0;
        else $datum['fk'] = "'" .$datum['fk']."'";
        $table_output .= "    \$this->addColumn('". $datum['idalm_column'] . "','" . $datum['type'] . "'," . $datum['size'] . "," . $datum['pk'] . "," .$datum['fk'] . ",'" . $datum['idalm_column'] . "','');\n";
      }
      if (isset($key2)) {
            $table_output = preg_replace("/key = '".$table_datum['idalm_table']."';/","key1 = '".$key1."';\n    \$this->key2 = '".$key2."';",$table_output);
            $table_output = preg_replace("/extends Table/", "extends TableDoubleKey", $table_output);
      } else {
            $table_output = preg_replace("/key = '".$table_datum['idalm_table']."';/","key = '".$key."';",$table_output);
      }
      $table_output .= "  }\n}\n";
      $output .= $table_output;
    }
    if (isset($_REQUEST['save']) && $_REQUEST['save'] == '1') {
      if (!is_writable(ROOTDIR.'/classes/tables.class.php')) {
        print "No se puede escribir en classes/tables.class.php. Copiar el siguiente c&oacute;digo manualmente a tables.class.php:<br/><br/>\n";
      } else {
        $fp = fopen(ROOTDIR.'/classes/tables.class.php', 'w');
        fwrite($fp, "<?php\n$output");
        fclose($fp);
        print "Se ha actualizado tables.class.php.<br/>";
        exit;
      }
    }
    break;
  case 'autotables':
    $alm_table = new alm_tableTable();
    $alm_column = new alm_columnTable();
    $table_data = $alm_table->readData();

    foreach ($table_data as $table_datum) {
      $output .= "class " . $table_datum['idalm_table'] . "Table extends Table {\n";
      $output .= "  function ".$table_datum['idalm_table']."Table() {\n";
      $output .= "    \$this->Table('".$table_datum['idalm_table']."');\n";
      $output .= "    \$this->key = '".$table_datum['key']."';\n";
      $output .= "    \$this->title ='".$table_datum['alm_table']."';\n";
      if (!empty($table_datum['orden']))
        $output .= "    \$this->order ='".$table_datum['orden']."';\n";
      $data = $alm_column->readDataFilter("alm_column.idalm_table='".$table_datum['idalm_table']."'");
      if ($data)
      foreach ($data as $datum) {
        if ($datum['pk'] == 't') $datum['pk'] = 1;
        if ($datum['pk'] == 'f') $datum['pk'] = 0;
        if (empty($datum['fk'])) $datum['fk'] = 0;
        else $datum['fk'] = "'".$datum['fk']."'";
        $output .= "    \$this->addColumn('". $datum['idalm_column'] . "','" . $datum['type'] . "'," . $datum['size'] . "," . $datum['pk'] . "," .$datum['fk'] . ",'" . $datum['alm_column'] . "','" . $datum['extra']  . "');\n";
      }
      $output .= "  }\n}\n";
    }
    if (isset($_REQUEST['save']) && $_REQUEST['save'] == '1') {
      if (!is_writable(ROOTDIR.'/classes/tables.class.php')) {
        print "No se puede escribir en classes/tables.class.php. Copiar el siguiente c&oacute;digo manualmente a tables.class.php:<br/><br/>\n";
      } else {
        $fp = fopen(ROOTDIR.'/classes/tables.class.php', 'w');
        fwrite($fp, "<?php\n$output");
        fclose($fp);
        print "Se ha actualizado tables.class.php.<br/>";
        exit;
      }
    }
    break;
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
              if (!isset($data->key)) $data->key = false;
              if (!isset($dd)) $dd = null;
              $sql_fix .= "ALTER TABLE $data->name ADD COLUMN " . genColumnSQL($data->dd[$campo], $dbtype, $dd[$campo]['name'] === $data->key).";\n";
            }
          }
        }
      } else
        $tables_output .= "$green<br/>";
    }
    break;
  case 'exec':
    if (isset($_REQUEST['sqlcmd']))
      $sqlcmd = pg_escape_string($_REQUEST['sqlcmd']);
    else
      $sqlcmd = '';
    if (!isset($_REQUEST['fix']))
      $output .= '<form><input type="hidden" name="action" value="exec"/><textarea name="sqlcmd" cols="80">'.$sqlcmd.'</textarea><br/><input type="submit" value="Ejecutar SQL"></form>';
    else
      $output .= "SQL Aplicado: " . $sqlcmd;
    if ($sqlcmd) {
      $data = new Data();
      $data->execSql($sqlcmd);
      $sqldata = $data->getArray();
      $output .= "<pre>" . print_r($sqldata,1) . "</pre>";
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
    $links = '';
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
  case 'exec':
    print $output;
    break;
  case 'sql2almidon':
    $save_tables = '<br/><form><input type="hidden" name="action" value="sql2almidon"/><input type="hidden" name="save" value="1"/><input type="submit" value="Guardar en tables.class.php"></form>';
    print $save_tables;
    print highlight_string("<?php\n".$output, 1);
    print $save_tables;
    break;
  case 'autotables':
    $save_tables = '<br/><form><input type="hidden" name="action" value="autotables"/><input type="hidden" name="save" value="1"/><input type="submit" value="Guardar en tables.class.php"></form>';
    print $save_tables;
    print highlight_string("<?php\n".$output, 1);
    print $save_tables;
    break;
  case 'tables':
    print "$tables_output";
    if ($tables_failed) {
      print '<br/><font color="red">Debes corregir estos errores en la base de datos.</font><br/>Puedes ayudarte del <a href="?action=sql">sql generado desde tables.class</a> o del c&oacute;digo a continuaci&oacute;n:';
      if (isset($sql_fix)) {
        $sql_fix = trim($sql_fix);
        print '<form><pre>'.$sql_fix.'</pre>';
        print '<input type="hidden" name="action" value="exec"/><input type="hidden" name="sqlcmd" value="'.$sql_fix.'"/><input type="hidden" name="fix" value="1"/><input type="submit" value="Aplicar SQL"></form>';
      }
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
  case 'alm_tables':
    print $output;
    break;
  case 'dd':
    print "$output";
    break;
  }
}
if ($action != 'erd' && $action != 'erdcol' && !$failed) {
  print "</body>\n</html>\n";
}
