<?
require 'app.class.php';

function genSQL($object) {
  $o = $object;
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
    }
    if ($type == 'order') $type = 'int NULL'; //esta linea se cambio
    if (!$size) $size = $column['size'];
    $size = preg_replace("/\./", ",", $size);
    $sql .= "  ".$column['name']." ".$type;
    if ($size) $sql .= " (".$size.")";
    if ($column['name'] == $data->key) $sql .= " PRIMARY KEY NOT NULL AUTO_INCREMENT "; //se cambio esta linea para que fuera compatible con MySQL.
    if ($column['references']) $sql .= " REFERENCES ".$column['references'];
    ++$i;
  }
  $sql .= "\n);\n\n";
  print $sql;
}
$tables[] = null;

$classes = get_declared_classes();
foreach($classes as $key) {
  if (strstr($key, 'table') && $key != 'table' && $key != 'tabledoublekey') {
    echo "entro al array";
    $key = substr($key, 0, strpos($key, 'table'));
    $tables[] = $key;
  }
}

// No funciona el foreach debido a que el arrary $tables no tiene ningun valor revisar funcion foreach que le precede. --fitoria
// foreach($tables as $key)
    genSQL($key);

?>
