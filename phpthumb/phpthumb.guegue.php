<?php
$args = explode("/", $_SERVER['PATH_INFO']);
if (!isset($args[3])) exit;
$_GET['src'] = "/../files/" . $args[2] . "/" . $args[3];
if(strpos($args[1],'x')!==false) {
  list($w, $h) = split("x", $args[1]);
} else {
  $w = $args[1];
  $h = null;
}
$_GET['w'] = $w; $_GET['h'] = $h;
