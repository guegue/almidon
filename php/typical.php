<?php
if (!$object)
  $object = ($_REQUEST['o']) ? $_REQUEST['o'] : $_REQUEST['f'];
$$object->readEnv();
$action = (isset($_REQUEST['action'])) ? $_REQUEST['action'] : null;
switch ($action) {
  case 'edit':
    $edit = true;
    $row = $$object->readRecord();
    break;
  case 'record':
    $row = $$object->readRecord();
    break;
  case 'add':
    $$object->addRecord();
    //$smarty->clear_all_cache();
    break;
  case 'delete':
    $$object->deleteRecord();
    //$smarty->clear_all_cache();
    break;
  case 'save':
    $$object->updateRecord();
    //$smarty->clear_all_cache();
    break;
  case 'dgsave':
    $maxcols = ($_REQUEST['maxcols']) ? $_REQUEST['maxcols'] : MAXCOLS;
    # Por que nofiles? Tambien debe poder cambiar, nofiles es la tercera opcion de updateRecord
    $$object->updateRecord(0, $maxcols, 0);
    //$smarty->clear_all_cache();
    break;
}
if (isset($_REQUEST[$object . 'sort']) && !empty($_REQUEST[$object . 'sort'])) $_SESSION[$object . 'sort'] = $_REQUEST[$object . 'sort'];
if (isset($_REQUEST[$object . 'pg']) && !empty($_REQUEST[$object . 'pg'])) $_SESSION[$object . 'pg'] = $_REQUEST[$object . 'pg'];
$$object->order = (isset($_SESSION[$object . 'sort'])) ? $_SESSION[$object .'sort'] : $$object->order;
$$object->pg = (isset($_SESSION[$object . 'pg'])) ? $_SESSION[$object . 'pg'] : 1;
// Codigo nuevo, agregado para la funcionalidad del detalle
// Detalle
if(isset($$object->detail)) {
  $smarty->assign('have_detail', true);
  $obj = $$object->detail;
  $ot = $obj.'Table';
  $$obj = new $ot;
  $$obj->readEnv();
  switch($_REQUEST['actiond']) {
    case 'delete':
    $tmp = $$obj->readRecord();
    $$obj->deleteRecord();
    //$smarty->clear_all_cache();
    header('Location: ./'.$object.'.php?f='.$object.'&action=record&'.$$object->key.'='.$tmp[$$object->key]);
    break;
  }
  if($row) {
    $filter = "$obj.".$$object->key." = '".$row[$$object->key]."'";
    $detail = array (
		'name' => $$object->detail,
		'_ftable' => $object,
		'_fkey' => $$object->key,
		'_fkey_value' => $$object->request[$$object->key],
		'_options' => fillOpt($$obj),
		'rows' => $$obj->readDataFilter($filter),
		'dd' => $$obj->dd,
		'key' => $$obj->key,
		'title' => $$obj->title,
		'maxrows', $$obj->maxrows,
		'maxcols', $$obj->maxcols
	    );
  } 
}
// --
$smarty->assign('object', $object);
$smarty->assign('cur_page', "$object.php");
if (isset($detail))
  $smarty->assign('detail', $detail);
if (isset($edit))
  $smarty->assign('edit', $edit);
if (isset($row))
  $smarty->assign('row', $row);
$smarty->assign('options', fillOpt($$object));
# Limits/Paginando
if(!isset($$object->maxrows)) $$object->maxrows = 8;
else $$object->maxrows = (int) $$object->maxrows;
$$object->offset = (isset($_REQUEST[$object.'pg']))?(((int)$_REQUEST[$object.'pg'])-1)*$$object->maxrows:0;
$$object->limit = ($$object->maxrows)?$$object->maxrows:8;
# End Limits
$smarty->assign('rows', $$object->readData());
$count_key = $$object->key ? $$object->key : $$object->key1;
$smarty->assign('num_rows', $$object->getVar("SELECT COUNT(".$count_key.") FROM ".$$object->name.(!empty($$object->filter)?" WHERE ".$$object->filter:"")));
$smarty->assign('dd', $$object->dd);
$smarty->assign('key', $$object->key);
if (isset($$object->key1))
  $smarty->assign('key1', $$object->key1);
if (isset($$object->key2))
  $smarty->assign('key2', $$object->key2);
$smarty->assign('title', $$object->title);
if (isset($$object->maxrows))
  $smarty->assign('maxrows', $$object->maxrows);
if (isset($$object->maxcols))
  $smarty->assign('maxcols', $$object->maxcols);

function fillOpt(&$object) {
  foreach ($object->dd as $key => $val)
    if ($object->dd[$key]['references']) {
      # esta linea mantiene la compatibilidad con db2
      if (!is_array($object->dd[$key]['extra']) && !empty($object->dd[$key]['extra'])) {
        if (preg_match("/\|\|/", $object->dd[$key]['extra'])) {
          $ot = $object->dd[$key]['references'] . 'Table';
          $robject = new $ot;
          $options[$key] = $object->selectMenu("SELECT " . $robject->key . ", " . $object->dd[$key]['extra'] . " AS " . $object->dd[$key]['references'] . " FROM " . $object->dd[$key]['references']);
        }
        else {
	  $pos = strpos($object->dd[$key]['references'],'.');
          if($pos!==false) {
            $references = substr($object->dd[$key]['references'],0,$pos);
          } else $references = $object->dd[$key]['references'];
          $options[$key] = $object->selectMenu($references, $where);
        }
      // Esto sucede solo si extra esta manteniendo el formato ordenado de array de la version db3
      } elseif (!isset($object->dd[$key]['extra']['depend']) && !isset($object->dd[$key]['extra']['readonly'])) {
        if(isset($object->dd[$key]['extra']['display'])) {
          if(isset($object->dd[$key]['extra']['filteropt']))
            $where = $object->dd[$key]['extra']['filteropt'];
          $ot = $object->dd[$key]['references'] . 'Table';
          $robject = new $ot;
	  if(isset($where))
            $options[$key] = $object->selectMenu("SELECT " . $robject->key . ", " . $object->dd[$key]['extra']['display'] . " AS " . $object->dd[$key]['references'] . " FROM " . $object->dd[$key]['references']." WHERE $where");
	  else
            $options[$key] = $object->selectMenu("SELECT " . $robject->key . ", " . $object->dd[$key]['extra']['display'] . " AS " . $object->dd[$key]['references'] . " FROM " . $object->dd[$key]['references']);
        }else {
	  $pos = strpos($object->dd[$key]['references'],'.');
          if($pos!==false) {
            $references = substr($object->dd[$key]['references'],0,$pos);
          } else $references = $object->dd[$key]['references'];
          $where = (isset($where) ? $where : null);
          $options[$key] = $object->selectMenu($references, $where);
        }
      }
    }
  if (isset($options))
    return $options;
}
