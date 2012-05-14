<?php
function genColumnModel($column, $dbtype) {
  $sql = '';
  $type = $column['type'];
  if ($type == 'external') return;
  if ($type == 'automatic') {
    switch($column['extra']['automatic']) {
    case 'now':
      $type = 'timestamp';
      $size = null;
      break;
    default:
      $type = 'CharField';
      $size = '32';
    }
  } elseif ($type == 'file' || $type == 'image' || $type == 'autoimage') {
    $type = 'CharField';
    $size = '500';
  } elseif ($type == 'html' || $type == 'xhtml' || $type == 'text') {
    $type = 'TextField';
    $size = null;
  } elseif ($type == 'datetime') {
    $type = 'timestamp';
    $size = null;
  } elseif ($type == 'datenull') {
    $type = 'DateField';
    $size = null;
  } elseif ($type == 'password') {
    $type = 'CharField';
  } elseif ($type == 'varchar') {
    $type = 'CharField';
  } elseif ($type == 'int') {
    $type = 'IntegerField';
  } elseif ($type == 'date') {
    $type = 'DateField';
  } elseif ($type == 'bool') {
    $type = 'BooleanField';
  }

  if ($dbtype == 'pgsql') {
    if ($type == 'order') $type = 'serial NULL';
  } elseif ($dbtype == 'mysql') {
    if ($type == 'order' ||  $type == 'serial') $type = 'int AUTO_INCREMENT';
  }
  $size = isset($size) ? $size : $column['size'];
  $size = preg_replace("/\./", ",", $size);
  $model .= $column['name']." = models.".$type;
  if ($size) $model .= " (max_length=".$size.")";
  if ($column['pk']) $model .= " (primary_key=True)";
  if ($column['references']) $sql .= " REFERENCES ".$column['references'];
  return $model;
}
function genModel($object) {
  global $admin_dsn;
  $o = $object . "Table";
  $data = new $o;
  list($dbtype,$tmp) = preg_split('/:\/\//',$admin_dsn);
  $model = "class $data->name(models.Model):\n";
  $i = 0;
  if($data->definition)
  foreach($data->definition as $column) {
    unset($size);
    if (isset($type) && $type == 'external') next($data->definition);
    if ($i) $model .= "\n";
    $model .= "    " . genColumnModel($column, $dbtype);
    ++$i;
  }
  $sql .= ', PRIMARY KEY ( '. join(',',$data->keys) .' ) ';
  $model .= "\n\n";
  return($model);
}
