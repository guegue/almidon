<?php
function genColumnModel($column, $dbtype) {
  $sql = '';
  $type = $column['type'];
  $name = $column['name'];
  if ($type == 'external') return;
  if ($type == 'automatic') {
    switch($column['extra']['automatic']) {
    case 'now':
      $type = 'DateTimeField';
      $size = null;
      break;
    default:
      $type = 'CharField';
      $size = '32';
    }
  } elseif ($type == 'image' || $type == 'autoimage') {
    $type = 'ImageField';
  } elseif ($type == 'file') {
    $type = 'FileField';
  } elseif ($type == 'html' || $type == 'xhtml' || $type == 'text') {
    $type = 'TextField';
    $size = null;
  } elseif ($type == 'datetime') {
    $type = 'DateTimeField';
    $size = null;
  } elseif ($type == 'datenull') {
    $type = 'DateField';
    $size = null;
  } elseif ($type == 'password') {
    $type = 'CharField';
  } elseif ($type == 'varchar') {
    $type = 'CharField';
    if ($name == 'email') $type = 'EmailField';
    if ($name == 'url') $type = 'UrlField';
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
  if ($column['references']) {
    $name = preg_replace('/^id/', '', $name);
    $model = "$name = ForeignKey";
    $params[] = $column['references'];
  } else {
    $model = "$name = models.".$type;
    if ($size) $params[] = "max_length=$size";
    if ($column['pk']) $params[] = "primary_key=True";
  }
  if ($column['label']) $params[] = 'verbose_name="' . $column['label'] . '"';
  if ($params) $model .= '(' . join(', ', $params) . ')';
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
  $model .= "\n    class Meta:\n";
  $model .= "        db_table = '$object'\n";
  if ($data->order) {
    $order = preg_replace('/(.*) DESC/', '-$1', $data->order);
    $model .= "        ordering = ['" . $order . "']\n";
  }
  $model .= "\n\n";
  return($model);
}
