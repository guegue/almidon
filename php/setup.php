<?php
if (!defined('ADMIN')) define ('ADMIN', true);
require_once($_SERVER['DOCUMENT_ROOT'] . '/../classes/app.class.php');
function checkPerms($filepath) {
  if (!file_exists($filepath)) return "File does not exist";
  if (is_writeable($filepath)) return true;
  else return "Not writeable";
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
    if ($column['size'] == '0') $column['size'] = '&nbsp;';
    if ($column['references'] == '0') $column['references'] = '&nbsp;';
    $col = array($column['name'],$column['type'],$column['size'],$column['references'],$column['label']);
    $dd[] = $col;
  }
  return $dd;
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
    if (!isset($type)) $type = '';
    if (!isset($size)) $size = '';
    if ($type == 'external') next($data->definition);
    if ($i) $sql .= " ,\n";
    $type = $column['type'];
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
    }
    if ($dbtype == 'pgsql') {
      if ($type == 'order') $type = 'serial NULL';
    } elseif ($dbtype == 'mysql') {
      if ($type == 'order' ||  $type == 'serial') $type = 'int AUTO_INCREMENT';
    }
    if (!$size) $size = $column['size'];
    $size = preg_replace("/\./", ",", $size);
    $sql .= "  ".$column['name']." ".$type;
    if ($size) $sql .= " (".$size.")";
    if ($column['name'] == $data->key) $sql .= " PRIMARY KEY NOT NULL";
    if ($column['references']) $sql .= " REFERENCES ".$column['references'];
    ++$i;
  }
  $sql .= "\n);\n\n";
  return($sql);
}

$options = array(
  'test'=>'Probar configuraci&oacute;n',
  'sql'=>'Generar SQL basado en tables.class',
  'dd'=>'Generar diccionario de datos',
  'erd'=>'Geenrar diagrama entidad relacion');
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
if ($action != 'erd')
foreach($options as $k=>$option) {
  print "<a href=\"?action=$k\">$option</a><br/>";
}
if (isset($_REQUEST['action'])) {
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
        $output .= "<tr>";
        foreach($col as $val)
          $output .= "<td>$val</td>";
        $output .= "</tr>\n";
      }
    }
    $output = "\n<table border=\"1\" cellpadding=\"1\" cellspacing=\"1\">$output</table>\n";
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
    $red = '<font color="red">FALL&Oacute;</font>';
    $green = '<font color="green">PAS&Oacute;</font>';
    print "Probando conexion a base de datos... ";
    $db =& MDB2::connect (DSN);
    if (PEAR::isError($db)) {
      $error_msg = $db->getMessage();
      print "$red <i>$error_msg</i><br/>";
    } else {
      print "$green<br/>";
    }
    print "Probando configuracion de PHP... ";
    if (get_cfg_var('short_open_tag') != 1) {
      print "$red <i>short_open_tag = ".get_cfg_var('short_open_tag')."</i><br/>";
    } else {
      print "$green<br/>";
    }
    print "Probando permisos de directorios... ";
    if (checkPerms($smarty->compile_dir = ROOTDIR . '/templates_c/') != 1) {
      print "$red <i>mod = ".checkPerms($smarty->compile_dir = ROOTDIR . '/templates_c/')."</i><br/>";
    } else {
      print "$green<br/>";
    }
  }
  switch($action) {
  case 'erd':
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
