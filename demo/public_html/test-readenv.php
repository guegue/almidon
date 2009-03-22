<?php
require('../classes/app.class.php');
$pagina = new paginaTable();
$pagina->readEnv();
$row = $pagina->readRecord();
$rand = rand(1,5);
echo '<a href="/test-readenv.php?idpagina='.$rand.'"/>test-readenv.php?idpagina='.$rand.'</a>';
echo "<pre>";
print_r($row);
echo "</pre>";
