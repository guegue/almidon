<?php
function genColumnModel($column, $table) {
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
    $params[] = "upload_to='$table'";
  } elseif ($type == 'file') {
    $type = 'FileField';
    $params[] = "upload_to='$table'";
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
    if ($name == 'url') $type = 'URLField';
  } elseif ($type == 'int') {
    $type = 'IntegerField';
  } elseif ($type == 'date') {
    $type = 'DateField';
  } elseif ($type == 'bool') {
    $type = 'BooleanField';
  } elseif ($type == 'serial') {
    $type = 'AutoField';
  } elseif ($type == 'auto') {
    if ($name == 'ip') $type = 'IPAddressField';
    if ($name == 'fecha') {
      $type = 'DateField';
      $params[] = 'auto_now_add=True';
    }
  }

  $size = isset($size) ? $size : $column['size'];
  $size = preg_replace("/\./", ",", $size);
  if ($column['references']) {
    $nname = preg_replace('/^id/', '', $name);
    $model = "$nname = models.ForeignKey";
    $params[] = $column['references'];
    $params[] = 'db_column="' . $name . '"';
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
  $model = "class $data->name(models.Model):\n";
  $i = 0;
  if($data->definition)
  foreach($data->definition as $column) {
    unset($size);
    if (isset($type) && $type == 'external') next($data->definition);
    if ($i) $model .= "\n";
    $model .= "    " . genColumnModel($column, $object);
    ++$i;
  }
  $model .= "\n    class Meta:\n";
  $model .= "        db_table = '$object'\n";
  $model .= "        verbose_name = '$data->title'\n";
  if ($data->order) {
    $data_order = preg_replace('/, /', ',', $data->order);
    $order_fields = explode(',', $data_order);
    foreach($order_fields as $order_field) {
      if (preg_match('/^id/', $order_field) && preg_replace('/ DESC/', '', $order_field) != $data->key) {
        $order_field = preg_replace('/^id/', '', $order_field);
      }
      $order[] = "'" . preg_replace('/(.*) DESC/', '-$1', $order_field) . "'";
    }
    $model .= "        ordering = [" . join(',', $order) . "]\n";
  }
  $model .= "\n    def __unicode__(self):\n";
  $model .= "        return self." . $object . "\n";
  $model .= "\n\n";
  return($model);
}
