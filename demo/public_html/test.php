<?php
echo "PHP funciona? " . 5*8 . " = 40<br/>";
?>
<?
echo "Short tags funcionan " . 5*8 . " = 40<br/>";
?>
<?
$conn = pg_pconnect("host=localhost port=5432 dbname=almidondemo user=almidondemo password=secreto1");
if ($conn) echo "Conexion a base de datos con usuario almidondemo: OK <br/>";
$conn2 = pg_pconnect("host=localhost port=5432 dbname=almidondemo user=almidondemowww password=secreto2");
if ($conn2) echo "Conexion a base de datos con usuario almidondemowww: OK <br/>";
$result = pg_query($conn, "SELECT * FROM pagina");
if ($result) echo "Lectura de datos: OK<br/>";
?>
