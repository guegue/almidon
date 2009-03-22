<?php
define ('ADMIN', true);
require_once($_SERVER['DOCUMENT_ROOT'] . '/../classes/app.class.php');
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
  print $sql;
}

$classes = get_declared_classes();
foreach($classes as $key) {
  if (stristr($key, 'table') && $key != 'table' && $key != 'tabledoublekey' && $key != 'Table' && $key != 'TableDoubleKey') {
    if(substr($key, 0, strpos($key, 'Table')) !== false) $key = substr($key, 0, strpos($key, 'Table'));
    else $key = substr($key, 0, strpos($key, 'table'));
    $tables[] = $key;
  }
}
echo '<pre>';
foreach($tables as $key)
  genSQL($key);
echo '</pre>';
?>
