<?php
if (!$object)
  $object = ($_REQUEST['o']) ? $_REQUEST['o'] : $_REQUEST['f'];
$$object->readEnv();
switch ($_REQUEST['action']) {
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
    $$object->updateRecord(0, $maxcols, 1);
    //$smarty->clear_all_cache();
    break;
}
if ($_REQUEST[$object . 'sort']) $_SESSION[$object . 'sort'] = $_REQUEST[$object . 'sort'];
if ($_REQUEST[$object . 'pg']) $_SESSION[$object . 'pg'] = $_REQUEST[$object . 'pg'];
$$object->order = ($_SESSION[$object . 'sort']) ? $_SESSION[$object .'sort'] : $$object->order;
$$object->pg = $_SESSION[$object . 'pg'];
// Codigo nuevo, agregado para la funcionalidad del detalle
// Detalle
if($$object->detail) {
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
$smarty->assign('detail', $detail);
$smarty->assign('edit', $edit);
$smarty->assign('row', $row);
$smarty->assign('options', fillOpt($$object));
$smarty->assign('rows', $$object->readData());
$smarty->assign('dd', $$object->dd);
$smarty->assign('key', $$object->key);
$smarty->assign('key1', $$object->key1);
$smarty->assign('key2', $$object->key2);
$smarty->assign('title', $$object->title);
$smarty->assign('maxrows', $$object->maxrows);
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
      } elseif (!$object->dd[$key]['extra']['depend']&&!$object->dd[$key]['extra']['readonly']) {
        if($object->dd[$key]['extra']['display']) {
          if($object->dd[$key]['extra']['filteropt'])
            $where = $object->dd[$key]['extra']['filteropt'];
          $ot = $object->dd[$key]['references'] . 'Table';
          $robject = new $ot;
	  if($where)
            $options[$key] = $object->selectMenu("SELECT " . $robject->key . ", " . $object->dd[$key]['extra']['display'] . " AS " . $object->dd[$key]['references'] . " FROM " . $object->dd[$key]['references']." WHERE $where");
	  else
            $options[$key] = $object->selectMenu("SELECT " . $robject->key . ", " . $object->dd[$key]['extra']['display'] . " AS " . $object->dd[$key]['references'] . " FROM " . $object->dd[$key]['references']);
        }else {
	  $pos = strpos($object->dd[$key]['references'],'.');
          if($pos!==false) {
            $references = substr($object->dd[$key]['references'],0,$pos);
          } else $references = $object->dd[$key]['references'];
          $options[$key] = $object->selectMenu($references, $where);
        }
      }
    }
  return $options;
}

?>
