<?php
if (!$object)
  $object = ($_REQUEST['o']) ? $_REQUEST['o'] : $_REQUEST['f'];
$$object->readEnv();

$action = (isset($_REQUEST['action'])) ? $_REQUEST['action'] : null;
if (!isset($_SESSION['credentials'][$object])) $_SESSION['credentials'][$object] = 'unknown';
switch ($action) {
  case 'edit':
    $edit = true;
    $row = $$object->readRecord();
    break;
  case 'record':
    $row = $$object->readRecord();
    $_SESSION['accion'] = 'leer';
    break;
  case 'add':
    if ($_SESSION['credentials'][$object] == 'full' || $_SESSION['credentials'][$object] == 'edit' || $_SESSION['idalm_user'] == 'admin') {
      $$object->addRecord();
      if($object === 'alm_column' || $object === 'alm_table') $$object->syncFromAlm();
      # Para los tags
      verifyNewTags($$object);
    } else {
      die("SEGURIDAD: Credenciales no tienen sentido!");
    }
    break;
  case 'delete':
    if ($_SESSION['credentials'][$object] == 'full' || $_SESSION['credentials'][$object] == 'edit' || $_SESSION['credentials'][$object] == 'delete' || $_SESSION['idalm_user'] == 'admin') {
      $$object->deleteRecord();
      if($object === 'alm_column' || $object === 'alm_table') $$object->syncFromAlm();
    } else {
      die("SEGURIDAD: Credenciales no tienen sentido!");
    }
    break;
  case 'save':
    if ($_SESSION['credentials'][$object] == 'full' || $_SESSION['credentials'][$object] == 'edit' || $_SESSION['idalm_user'] == 'admin') {
      $$object->updateRecord();
      if($object === 'alm_column' || $object === 'alm_table') $$object->syncFromAlm();
      verifyNewTags($$object);
    } else {
      die("SEGURIDAD: Credenciales no tienen sentido!");
    }
    break;
  case 'dgsave':
    if ($_SESSION['credentials'][$object] == 'full' || $_SESSION['credentials'][$object] == 'edit' || $_SESSION['idalm_user'] == 'admin') {
      $maxcols = ($_REQUEST['maxcols']) ? $_REQUEST['maxcols'] : MAXCOLS;
      # Por que nofiles? Tambien debe poder cambiar, nofiles es la tercera opcion de updateRecord
      if (isset($$object->key2))
        $$object->updateRecord(0, 0, $maxcols, 0);
      else
        $$object->updateRecord(0, $maxcols, 0);
      if($object === 'alm_column' || $object === 'alm_table') $$object->syncFromAlm();
    } else {
      die("SEGURIDAD: Credenciales no tienen sentido!");
    }
    break;
  case 'move':
    $limit = $$object->limit;
    $$object->limit = 1;
    if($_REQUEST['sense']=='up') {
      list($curr) = $$object->readDataFilter($$object->key . " = " . $$object->request[$$object->key]);
      list($prev) = $$object->readDataFilter($$object->order . " < " . $curr[$$object->order]);
      if($prev) {
        $$object->query("UPDATE $object SET " . $$object->order . " = '" . $prev[$$object->order] . "' WHERE " . $$object->key ." = '" . $$object->request[$$object->key] . "'");               
        $$object->query("UPDATE $object SET " . $$object->order . " = '" . $curr[$$object->order] . "' WHERE " . $$object->key ." = '" . $prev[$$object->key] . "'");
      }
    } elseif($_REQUEST['sense']=='down') {
      list($curr) = $$object->readDataFilter($$object->key . " = " . $$object->request[$$object->key]);
      list($next) = $$object->readDataFilter($$object->order . " > " . $curr[$$object->order]);
      if($next) {
        $$object->query("UPDATE $object SET " . $$object->order . " = '" . $next[$$object->order] . "' WHERE " . $$object->key ." = '" . $$object->request[$$object->key] . "'"); 
        $$object->query("UPDATE $object SET " . $$object->order . " = '" . $curr[$$object->order] . "' WHERE " . $$object->key ." = '" . $next[$$object->key] . "'"); 
      }
    }
    $$object->limit = $limit;
    unset($limit);
    break;
  case 'search':
    # quick search - javier
    if (isset($_REQUEST['q'])) {
      $q = $$object->escape($_REQUEST['q']);
      foreach($$object->dd as $dd) {
        if (isset($dd['extra']['search']) && $dd['extra']['search'] === true)
          $cols[] = $dd['name'];
      } 
      if(isset($cols)) {
        foreach($cols as $col)
          $filter[] = $$object->name . ".$col LIKE '%" . $q . "%'";
        $$object->filter = join(' OR ', $filter);
        $$object->offset = 0;
        $$object->limit = 0;
      }
    # FIXME: normal search - no longer works!
    } elseif (isset($$object->search)) {
      $cols = preg_split('/,/',$$object->search);
      $s_ftr = '';
      $q_ftr = '';
      if(isset($cols)) {
        foreach($cols as $col) {
          if(!empty($_REQUEST[$col . 'search'])) {
            if(!empty($q_ftr)) $q_ftr .= ' AND ';
            $q_ftr .= "lower($col)" . ' LIKE lower(\'%' . $$object->database->escape($_REQUEST[$col . 'search']) . '%\')';
            $s_ftr .= "$col => " . htmlspecialchars($_REQUEST[$col . 'search'],ENT_COMPAT,'UTF-8');
          }
        }
        $_SESSION[$object . 'ssearch'] = $s_ftr;
        $_SESSION[$object . 'query'] = $q_ftr;
      }
    }
    break;
  #FIXME: do we need this?
  case 'clear':
    unset($_SESSION[$object . 'ssearch']);
    unset($_SESSION[$object . 'query']);
    break;
}
foreach($$object->dd as $dd)
  if (isset($dd['extra']['search']) && $dd['extra']['search'] === true)
    $smarty->assign('search', 'true');

# Para la busqueda
if(!empty($_SESSION[$object . 'query'])) {
  if(!empty($$object->filter)) $$object->filter .= ' AND ';
  else $$object->filter = '';
  $$object->filter .= "(" . $_SESSION[$object . 'query'] . ")";
}
# End - Para la busqueda
if (isset($_REQUEST[$object . 'sort']) && !empty($_REQUEST[$object . 'sort'])) $_SESSION[$object . 'sort'] = $_REQUEST[$object . 'sort'];
if (isset($_REQUEST[$object . 'pg']) && !empty($_REQUEST[$object . 'pg'])) $_SESSION[$object . 'pg'] = $_REQUEST[$object . 'pg'];
$$object->order = (isset($_SESSION[$object . 'sort'])) ? $_SESSION[$object .'sort'] : $$object->order;
$$object->pg = (isset($_SESSION[$object . 'pg'])) ? $_SESSION[$object . 'pg'] : 1;

# Adding master-child functionality
$child = array();
if(isset($$object->child)) {
  $smarty->assign('have_child', true);
  $classes = preg_split('/,/',$$object->child);
  foreach ($classes as $class) {
    $obj = trim($class);
    $ot = $obj.'Table';
    $$obj = new $ot;
    $$obj->readEnv();
    if (!isset($_REQUEST['actiond'])) $_REQUEST['actiond'] = null;
    switch($_REQUEST['actiond']) {
      case 'delete':
        if ($_SESSION['credentials'][$object] == 'full' || $_SESSION['credentials'][$object] == 'edit' || $_SESSION['credentials'][$object] == 'delete' || $_SESSION['idalm_user'] == 'admin') {
          if($_REQUEST['od']==$obj) {
            $tmp = $$obj->readRecord();
            $$obj->deleteRecord();
            //$smarty->clear_all_cache();
            header('Location: ./'.$object.'.php?f='.$object.'&action=record&'.$$object->key.'='.$tmp[$$object->key]);
          }
        } else {
          die("SEGURIDAD: Credenciales no tienen sentido!");
        }
      break;
    }
    if(isset($row)) {
      $filter = "$obj.".$$object->key." = '".$row[$$object->key]."'";
      $child[] = array (
		'name' => $obj,
		'_ftable' => $object,
		'_fkey' => $$object->key,
		'_fkey_value' => $$object->request[$$object->key],
		'_options' => fillOpt($$obj),
		'rows' => $$obj->readDataFilter($filter),
		'dd' => $$obj->dd,
		'key' => $$obj->key,
		'title' => $$obj->title,
		#'maxrows'=> $$obj->maxrows,
		#'maxcols'=> $$obj->maxcols,
                'num_rows'=> $$obj->getVar("SELECT COUNT(*) FROM ".$$obj->name." WHERE ".$filter)
	    );
    } 
  }
}
// --
$smarty->assign('object', $object);
$smarty->assign('cur_page', "$object.php");
if (isset($child))
  $smarty->assign('child', $child);
if (isset($edit))
  $smarty->assign('edit', $edit);
if (isset($row))
  $smarty->assign('row', $row);
if (isset($$object->shortEdit))
  $smarty->assign('shortEdit',$$object->shortEdit);
$smarty->assign('options', fillOpt($$object));

# Limits/Paginando
  if(!isset($$object->maxrows)) $$object->maxrows = 8;
  else $$object->maxrows = (int) $$object->maxrows;
  if (!isset($$object->offset))
    $$object->offset = (isset($$object->pg))?(((int)$$object->pg)-1)*$$object->maxrows:0;
  if (!isset($$object->limit))
    $$object->limit = ($$object->maxrows)?$$object->maxrows:8;
# -- End Limits

# To know who the first one and the last one is, this is useful when use order type of field
# FIXME: Are we even using this?
  $order_is_valid = preg_split('/ /',trim($$object->order));
  if(count($order_is_valid) > 1) $order_is_valid = false;
  else $order_is_valid = true;
  if (isset($$object->order) && $order_is_valid && !isset($$object->key2)) {  // Temporalmente desabilitando para TableDoubleKey
    $_SESSION[$object . 'first'] = $$object->getVar("SELECT " . $$object->key . " FROM " . $$object->name . " ORDER BY " . $$object->order . " LIMIT 1");
    $_SESSION[$object . 'last'] = $$object->getVar("SELECT " . $$object->key . " FROM " . $$object->name . " ORDER BY " . $$object->order . " DESC LIMIT 1");
  }
# -- end order

$smarty->assign('rows', $$object->readData());
$count_key = $$object->key ? $$object->key : $$object->key1;
$smarty->assign('num_rows', $$object->getVar("SELECT COUNT(*) FROM ".$$object->name.(!empty($$object->filter)?" WHERE ".$$object->filter:"")));
$smarty->assign('dd', $$object->dd);
$smarty->assign('key', $$object->key);
if (isset($$object->search))
  $smarty->assign('search', $$object->search);
$smarty->assign('add', isset($$object->add)?$$object->add:true);
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
  if ($object->dd)
  foreach ($object->dd as $key => $val)
    if ($object->dd[$key]['references']) {
      if (!isset($object->dd[$key]['extra']['depend']) && !isset($object->dd[$key]['extra']['readonly'])) {
        if(isset($object->dd[$key]['extra']['references_filter']))
          $where = $object->dd[$key]['extra']['references_filter'];
        if(isset($object->dd[$key]['extra']['display'])) {
          $ot = $object->dd[$key]['references'] . 'Table';
          $robject = new $ot;
	  $wwhere = (empty($where)) ? '' : "WHERE $where";
          $options[$key] = $object->selectMenu("SELECT " . $robject->key . ", " . $object->dd[$key]['extra']['display'] . " AS " . $object->dd[$key]['references'] . " FROM " . $object->dd[$key]['references']." $wwhere ORDER BY " . $object->dd[$key]['references']);
        } else {
	  $pos = strpos($object->dd[$key]['references'],'.');
          if($pos!==false)
            $references = substr($object->dd[$key]['references'],0,$pos);
          else 
            $references = $object->dd[$key]['references'];
          $where = (isset($where) ? $where : null);
          $options[$key] = $object->selectMenu($references, $where);
        }
      }
    }
  if (isset($options))
    return $options;
}

function verifyNewTags (&$object) {
  # Begining - Para los tags
  foreach($object->dd as $col) {
    # Cuando se agarran de otra tabla
    unset($tags);
    if(!empty($col['extra']['autocomplete_tb']) && $object->name != $col['extra']['autocomplete_tb']) {
      $object->sql_log("Examinando en busca de nuevas palabras claves");
      $tags = preg_split('/,/',trim($_REQUEST[$col['name']]));
      if($tags)
        foreach($tags as $tag) {
          $tag = trim($tag);
          if(!empty($tag)) {
            $exist = (bool) $object->getVar("SELECT " . (!empty($col['extra']['autocomplete_fd'])?$col['extra']['autocomplete_fd']:$col['extra']['autocomplete_tb']) . " FROM " . $col['extra']['autocomplete_tb'] . " WHERE lower(" . (!empty($col['extra']['autocomplete_fd'])?$col['extra']['autocomplete_fd']:$col['extra']['autocomplete_tb']) . ") LIKE lower('" . $object->database->escape($tag) . "') LIMIT 1");
            if(!$exist) $object->query("INSERT INTO " . $col['extra']['autocomplete_tb'] . " (" . (!empty($col['extra']['autocomplete_fd'])?$col['extra']['autocomplete_fd']:$col['extra']['autocomplete_tb']) . ") VALUES ('" . $object->database->escape($tag) . "')");
          }
        }
      $object->sql_log("Fin Examen de nuevas palabras claves");
    }
  }
  # End - Para los tags
}
