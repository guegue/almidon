<?php
header("Content-Type: application/x-javascript");

# Tell Almidon to use admin user, admin links, etc
define('ADMIN', true);

# Fetch app.class.php, wherever it is...
$script_filename = $_SERVER['SCRIPT_FILENAME'];
$app_base = '/../classes/app.class.php';
$app_filename = substr($script_filename, 0, strrpos($script_filename,'/')) . $app_base;
if (file_exists($app_filename)) require($app_filename);
else require($_SERVER['DOCUMENT_ROOT'] . $app_base);

$table = (string)$_GET['table'];
$field = (string)$_GET['field'];
$class = $table . 'Table';
if($class != 'Table' && class_exists($class) && !empty($field)) {
  $$table = new $class;
  $query = trim($_GET['query']);
  if(!empty($query)) {
    $rows = $$table->readDataSQL("SELECT etiqueta FROM (SELECT trim(regexp_split_to_table(trim(" . $$table->database->escape($field) . "),',')) AS etiqueta FROM " . $$table->database->escape($table) . " WHERE trim(" . $$table->database->escape($field) . ") IS NOT NULL AND trim(" . $$table->database->escape($field) . ") <> '' GROUP BY etiqueta) AS etiqueta WHERE lower(etiqueta) LIKE lower('" . $$table->database->escape($query) . "%') ORDER BY etiqueta");
    $etiquetas = '';
    if($rows)
      foreach($rows as $row)
        if(!empty($etiquetas))  $etiquetas .= ",'" . $row['etiqueta'] . "'";
        else $etiquetas = "'" . $row['etiqueta'] . "'";
    $str = '{ query:\'' . $query . '\',suggestions:[' . $etiquetas . '] }';
  }
}
echo $str;
?>
