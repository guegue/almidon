<?php

function getTitle($object) {
  $o = $object . "Table";
  $data = new $o;
  return $data->title;
}
function genLinks($object) {
  $o = $object . "Table";
  $data = new $o;
  if($data->definition)
  $dd = array();
  foreach($data->definition as $column) {
    if ($column['references'] != '0') $dd[] = $column['references'];
  }
  return $dd;
}
function genDD($object) {
  $o = $object . "Table";
  $data = new $o;
  if($data->definition)
  $dd = array();
  foreach($data->definition as $column) {
    if ($column['size'] == '0') $column['size'] = null;
    if ($column['name'] == $data->key) $column['PK'] = true;
    else $column['PK'] = null;
    if ($column['references'] == '0') $column['references'] = null;
    $col = array($column['name'],$column['type'],$column['size'],$column['references'],$column['label'],$column['PK']);
    $dd[] = $col;
  }
  return $dd;
}
