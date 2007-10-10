<?php
$prefijo

foreach($_REQUEST as $key => $val) {
  if (substr($key,0,5) == $prefijo) {
    $f2m[$key] = $val;
  }
}

print_r($f2m);

?>
