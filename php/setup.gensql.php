<?php
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
  } elseif ($type == 'password') {
    $type = 'varchar';
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
  global $admin_dsn;
  $o = $object . "Table";
  $data = new $o;
  list($dbtype,$tmp) = preg_split('/:\/\//',$admin_dsn);
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
