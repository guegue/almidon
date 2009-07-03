<?php
header("Content-Type: application/x-javascript");

include '../../classes/app.class.php';
$table = new Table('seccion');
$query = trim($_GET['query']);
if(!empty($query)) {
  $rows = $table->readDataSQL("SELECT etiqueta FROM (SELECT trim(regexp_split_to_table(trim(clave),',')) AS etiqueta, count(*) AS total FROM clave WHERE trim(clave) IS NOT NULL AND trim(clave) <> '' GROUP BY etiqueta) AS etiqueta WHERE lower(etiqueta) LIKE lower('$query%') ORDER BY etiqueta");
  $etiquetas = '';
  if($rows)
    foreach($rows as $row)
      if(!empty($etiquetas))  $etiquetas .= ",'" . $row['etiqueta'] . "'";
      else $etiquetas = "'" . $row['etiqueta'] . "'";
  $str = '{ query:\'' . $query . '\',suggestions:[' . $etiquetas . '] }';
}
echo $str;
?>
