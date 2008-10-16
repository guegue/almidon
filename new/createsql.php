<?
define ('ADMIN', true);
require 'app.class.php';

function genSQL($object) {
  $o = $object . "Table";
  $data = new $o;
  $sql = "CREATE TABLE $data->name (\n";
  $i = 0;
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
    }

    if ($type == 'order') $type = 'serial NULL';
    if (!$size) $size = $column['size'];
    $size = preg_replace("/\./", ",", $size);
    $sql .= "  ".$column['name']." ".$type;
    if ($size) $sql .= " (".$size.")";
    if ($column['name'] == $data->key) $sql .= " PRIMARY KEY";
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

foreach($tables as $key)
  genSQL($key);

?>
