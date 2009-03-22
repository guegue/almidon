<?php
require('../classes/app.class.php');
$pagina = new paginaTable();
$pagina->readEnv(true);
$row = $pagina->readRecord();
$rand = rand(1,5);
echo '<a href="/test-friendly/'.$rand.'"/>test-friendly/'.$rand.'</a>';
echo "<pre>";
print_r($row);
echo "</pre>";
