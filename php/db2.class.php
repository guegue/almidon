<?php
// vim: set expandtab tabstop=2 shiftwidth=2 fdm=marker:

/**
 * db2.class.php
 *
 * DAL entre almidon y PEAR::MDB2
 *
 * @copyright &copy; 2005-2009 Guegue Comunicaciones - guegue.com
 * @license http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @version $Id: db2.class.php,v 2008101301 javier $
 * @package almidon
 */

foreach ($_POST as $j =>$value) {
   if (stristr($value,"Content-Type")) {
       header("HTTP/1.0 403 Forbidden");
       echo "No spam allowed.";
       exit;
   }
}

if (!defined('ALM_SQL_DEBUG')) define('ALM_SQL_DEBUG', true);
if (!defined('ALM_DEBUG')) define('ALM_DEBUG', false);
if (DEBUG === true) ini_set('display_errors', true);
if (defined('ALMIDONDIR'))
  set_include_path(get_include_path() . PATH_SEPARATOR . ALMIDONDIR . '/php/pear');
if(!defined('ALM_ALLOW_TAGS')) define('ALM_ALLOW_TAGS', '<br/><br><p><h1><h2><h3><b><i><div><span><img1><img2><img3><img4><strong><li><ul><ol><table><tbody><tr><td><font><a><sup><object><param><embed><hr><hr /><hr/>');

require_once('MDB2.php');

class Data {
  var $data;
  var $database;
  var $num;
  var $max;
  var $limit;
  var $offset;
  var $current_pg;
  var $current_record;
  var $current_id;
  var $key;
  var $html;
  var $cols;

  function Data () {
    global $DSN;
    if ($DSN)
      $this->database =& MDB2::connect ($DSN);
    else
      $this->database =& MDB2::connect (DSN);
    $this->check_error($this->database,'',true);
    $this->num = 0;
    $this->cols = 0;
    $this->max = MAXROWS;
    $this->current_pg = (isset($_REQUEST['pg'])) ? $_REQUEST['pg'] : '1';
  }

  function check_error($obj, $extra = '', $die = false) {
    if (PEAR::isError($obj)) {
      $error_msg = $obj->getMessage();
      #if ($extra) $error_msg .= " -- " . $extra . " -- " . $_SERVER['SCRIPT_NAME'];
      $error_msg .= " -- " . $extra . " -- " . $_SERVER['SCRIPT_NAME'];
      if (DEBUG === true) trigger_error(htmlentities($error_msg));
      error_log(date("[D M d H:i:s Y]") . " Error: " . $error_msg . "\n");
      if ($die) die();
    } elseif (ALM_SQL_DEBUG !== false && $extra) {
      $this->sql_log($extra);
    }
  }

  function sql_log($logtext) {
    $loghandle = @fopen(SQLLOG, 'a');
    if (is_writable(SQLLOG)) {
      fwrite($loghandle, date("[D M d H:i:s Y]") . " " . $logtext . "\n");
      fclose($loghandle);
    }
  }
  
  function query($sqlcmd) {
    if (preg_match("/(?!')'(\s*?);/",$sqlcmd)) {
      error_log(date("[D M d H:i:s Y]") . " Query invalido. " . $sqlcmd . "\n");
      return false;
    }
    $result = $this->database->query($sqlcmd);
    $this->check_error($result, $sqlcmd);
      /* if (preg_match("/violates foreign key/", $error_msg)) {
        preg_match("/DETAIL: Key \((.*)\)\=\((.*?)\)(.*)from table \"(.*?)\"/", $error_msg, $error_detail);
        preg_match("/Key \((.*?)\)=\((.*?)\)(.*?)\"(.*?)\"/", $error_msg, $error_detail);
        $msg = "ERROR: Registro $error_detail[1]=$error_detail[2] es usado en la tabla $error_detail[4]";
        if (DEBUG) print $msg;
        global $smarty;
        if ($smarty) $smarty->assign('error', "$msg <br/> $error_msg");
      } */
    return $result;
  }
  
  function execSql($sqlcmd) {
    $this->data = $this->query($sqlcmd);
    if (!PEAR::isError($this->data) && $this->data && (strpos($sqlcmd,'SELECT') !== false))
      $this->num = $this->data->numRows();
  }

  //Mejor usar readDataSQL, funcion repetida
  function readList($sqlcmd) {
    $this->execSql($sqlcmd);
    return $this->getArray();
  }

  function getVar($sqlcmd) {
    $this->execSql($sqlcmd);
    #if (!PEAR::isError($this->data))
    if ($this->data)
      $row = $this->data->fetchRow(MDB2_FETCHMODE_ORDERED);
    return $row[0];
  }

  //Lee un statement sql y devuelve una lista de una sola columna (la primera)
  function getList($sqlcmd) {
    $this->execSql($sqlcmd);
    for ($i = 0; $i < $this->num; $i++) {
      $row = $this->data->fetchRow(MDB2_FETCHMODE_ORDERED);
      $array_rows[] = $row[0];
    }
    return $array_rows;
  }

  function getArray() {
    for ($i = 0; $i < $this->num; $i++) {
      $row = $this->data->fetchRow(MDB2_FETCHMODE_ASSOC);
      if (isset($row[$this->key]))
      if ($row[$this->key] == $this->current_id)
        $this->current_record = $row;
      if ($this->html)
        foreach ($row as $key => $val)
          $row[$key] = htmlentities($val, ENT_COMPAT, 'UTF-8');
      $array_rows[] = $row;
    }
    return $array_rows;
  }

  function selectList($sqlcmd) {
    $result = $this->query($sqlcmd);
    $num = $result->numRows();
    $menu = array();
    for ($i=0; $i < $num; $i++) {
      $r = $result->fetchRow(MDB2_FETCHMODE_ORDERED);
      $new = array($r[0] => $r[1]);
      $menu = $menu + $new;
    }
    return $menu;
  }

  function selectMenu($sqlcmd = '') {
    if (!$sqlcmd)
      $sqlcmd = "SELECT $this->key, $this->name FROM $this->name ORDER BY $this->name";
    if (!preg_match("/SELECT/", $sqlcmd))
      $sqlcmd = "SELECT id$sqlcmd, $sqlcmd FROM $sqlcmd ORDER BY $sqlcmd";
    $result = $this->query($sqlcmd);
    $num = $result->numRows();
    $menu = array();
    for ($i=0; $i < $num; $i++) {
      $r = $result->fetchRow(MDB2_FETCHMODE_ORDERED);
      $new = array($r[0] => $r[1]);
      $menu = $menu + $new;
    }
    return $menu;
  }

  //Pagina los datos del query actual segun $num y $max
  function getNumbering() {
    unset($this->pg);
    $numpg = ceil($this->num / $this->max);
    for ($n = 1; $n <= $numpg; $n++)
      $this->pg[] = $n;
    return $this->pg;
  }

  function destroy() {
    $this->database->disconnect();
  }
}

class Table extends Data {
  var $name;
  var $definition;
  var $dd;
  var $title;
  var $request;
  var $files;
  var $fields;
  var $fields_noserial;
  var $key;
  var $order;
  var $join;
  var $all_fields;
  var $escaped;
  var $id;
  var $action;

  function Table($name, $schema = 'public') {
    $this->Data();
    $this->name = $name;
    $this->schema = $schema;
    if ($schema && $schema != 'public')
      $this->query("SET search_path = $schema, public, pg_catalog");
  }

  function refreshFields() {
    $n = 0;
    $ns = 0;
    $this->fields_noserial = '';
    $this->all_fields = '';
    $this->fields = '';
    foreach($this->definition as $column) {
      if ($n > 0) {
        $this->fields .= ",";
        $this->all_fields .= ",";
      }
      if ($this->schema != 'public')
        $this->all_fields .= $this->schema . ".";
      if ($ns > 0 && $column['type'] != 'external' && $column['type'] != 'auto' && $column['type'] != 'order' && $column['type'] != 'serial')
        $this->fields_noserial .= ",";
      if ($column['type'] == 'serial' || $column['type'] == 'external' || $column['type'] == 'auto' || $column['type'] == 'order' && $column['type'] == 'serial')
        $ns--;
      else
        $this->fields_noserial .= $column['name'];
      $this->fields .= $column['name'];
      if ($column['type'] == 'external')
        $this->all_fields .= $column['name'];
      else
        $this->all_fields .= $this->name . "." . $column['name'];
      if ($column['references']) {
        #if ($column['extra'])
        if (preg_match("/\|\|/", $column['extra'])) {
          $this->all_fields .= ",(" . $column['extra'] . ") AS " . $column['references'];
        } else {
	  $this->all_fields .= "," . $column['references'] . "." . $column['references'];
	  #$this->all_fields .= "," . $column['references'] . "." . $column['name'];
        }
      }
      $n++;
      $ns++;
    }
  }

  function addColumn($name, $type, $size = 100, $pk = 0, $references = 0, $label = '', $extra = '') {
    $column = array('name'=>$name,'type'=>$type,'size'=>$size,'references'=>$references, 'label'=>$label, 'extra'=>$extra);
    $this->definition[] = $column;
    $this->dd[$name] = $column;
    if ($references)
      $this->join = 1;
    $this->refreshFields();
    $this->cols++;
  }

  function parsevar($tmpvar, $type = 'string', $html = false) {
    if ($this->database) 
      $tmpvar = $this->database->escape($tmpvar);
    switch ($type) {
      case 'varchar':
        $type = 'string';
        break;
      case 'numeric':
        $type = 'float';
        break;
      case 'int':
      case 'smallint':
      case 'serial':
        $type = 'int';
        break;
      default:
        $type = 'string';
    }
    settype($tmpvar,$type);
    if ($type == 'string') {
      $tmpvar = preg_replace("/<script[^>]*?>.*?<\/script>/i", "", $tmpvar);
      $tmpvar = preg_replace("/javascript/i", "", $tmpvar); # es necesario?
    } 
    if ($type == 'string' && !$html) {
      $tmpvar = strip_tags($tmpvar, ALM_ALLOW_TAGS);
      #$tmpvar = preg_replace("/<|>/", "", $tmpvar);
    }
    return $tmpvar;
  }

  function readArgs() {
    $params = explode("/", $_SERVER['PATH_INFO']);
    for($i = 1; $i < sizeof($params); $i++)
      $args[$i] = $params[$i];
    if (is_numeric($args[1])) {
      $this->id = $args[1];
      $this->action = $args[2];
    } else {
      $this->action = $args[1];
    }
    return $args;
  }

  function readEnv($friendly = false) {
    include_once('db.readenv.php');
  }

  function addRecord() {
    $n = 0;
    $values ="";
    foreach($this->definition as $column) {
      if ($n > 0 && $column['type'] != 'external' && $column['type'] != 'auto' && $column['type'] != 'order' && $column['type'] != 'serial')
        $values .= ",";
      switch($column['type']) {
        case 'auto':
      	case 'external':
        case 'serial':
        case 'order':
          $n--;
          break;
        case 'int':
          if ($this->request[$column['name']] == -1 || !isset($this->request[$column['name']]))
            $this->request[$column['name']] = 'NULL';
        case 'smallint':
        case 'numeric':
          $values .= $this->request[$column['name']];
          break;
        case 'image':
          if ($this->files[$column['name']]) {
            $filename =  mktime() . "_" . $this->request[$column['name']];
            if (!file_exists(ROOTDIR . '/files/' . $this->name)) mkdir(ROOTDIR . '/files/' . $this->name);
            move_uploaded_file($this->files[$column['name']], ROOTDIR . '/files/' . $this->name . '/' . $filename);
            $this->request[$column['name']] = $filename;
            if ($column['extra'] && defined('PIXDIR'))  $sizes = explode(',',$column['extra']);
            if(isset($sizes)) {
              foreach($sizes as $size) {
                $image = imagecreatefromstring(file_get_contents(ROOTDIR.'/files/'.$this->name.'/'.$filename));
                list($ancho,$alto) = split('x', $size);
                $alto_original = imagesy($image);
                $ancho_original = imagesx($image);
                if (!$alto) $alto = ceil($alto_original*($ancho/$ancho_original));
                $new_image = imagecreatetruecolor ($ancho, $alto);
                imagecopyresampled($new_image, $image, 0, 0, 0, 0, $ancho, $alto, $ancho_original, $alto_original);
                imagejpeg($new_image,PIXDIR.'/'.$size.'_'.$filename,72);
              }
            }
          }
          $value = $this->database->escape($this->request[$column['name']]);
          $values .= "'" . $value . "'";
          break;
        case 'file':
          if ($this->files[$column['name']]) {
            $filename =  mktime() . "_" . $this->request[$column['name']];
            if (!file_exists(ROOTDIR . '/files/' . $this->name)) mkdir(ROOTDIR . '/files/' . $this->name);
            move_uploaded_file($this->files[$column['name']], ROOTDIR . '/files/' . $this->name . '/' . $filename);
            $this->request[$column['name']] = $filename;
          }
        case 'char':
          if ($this->request[$column['name']] == -1) {
            $this->request[$column['name']] = 'NULL';
            $values .= $this->request[$column['name']];
          } else {
            $value = $this->database->escape($this->request[$column['name']]);
            $values .= "'" . $value . "'";
          }
          break;
        case 'varchar':
          if ($this->request[$column['name']] == -1) {
            $this->request[$column['name']] = 'NULL';
            $values .= $this->request[$column['name']];
          } else {
            $value = ($this->escaped) ? $this->request[$column['name']] : $this->database->escape($this->request[$column['name']]);
            $values .= "'" . $value . "'";
          }
          break;
        case 'text':
          $value = ($this->escaped) ? $this->request[$column['name']] : $this->database->escape($this->request[$column['name']]);
          $values .= "'" . $value . "'";
          break;
        case 'bool':
        case 'boolean':
          $value = $this->request[$column['name']];
          $value = (!$value || $value == 'false' || $value == '0' || $value == 'f') ? '0' : '1';
          $values .= "'" . $value . "'";
          break;
        case 'date':
        case 'datenull':
          $value = $this->request[$column['name']];
          if (isset($value) && $value != '0-00-0') {
            $value = $this->database->escape($this->request[$column['name']]);
            $values .= "'" . $value . "'";
          } else {
            $values .= 'NULL';
          }
          break;
        default:
          $value = ($this->escaped) ? $this->request[$column['name']] : $this->database->escape($this->request[$column['name']]);
          $values .= "'" . $value . "'";
          break;
      }
      $n++;
    }
    $sqlcmd = "INSERT INTO $this->name ($this->fields_noserial) VALUES ($values)";
    $result = $this->query($sqlcmd);
  }

  function preUpdateRecord($maxcols = 0, $nofiles = 0) {
    $n = 0;
    $values = "";
    foreach($this->definition as $column) {
      if ($n > 0 && $column['type'] != 'external' && $column['type'] != 'auto' && $column['type'] != 'order' && $column['type'] != 'serial')
        $values .= ",";
      switch($column['type']) {
      	case 'external':
      	case 'auto':
      	case 'order':
        case 'serial':
          $n--;
          break;
        case 'int':
          if ($this->request[$column['name']] == -1)
            $this->request[$column['name']] = 'NULL';
        case 'smallint':
        case 'numeric':
          $values .= $column['name'] . "=" . $this->request[$column['name']];
          break;
        case 'image':
	  if ($nofiles || isset($_REQUEST[$column['name'] . '_keep']) || !isset($this->files[$column['name']]) || empty($this->files[$column['name']]) ) {
            if (!isset($_REQUEST[$column['name'] . '_keep']) && !isset($this->files[$column['name']])) {
              $values .= $column['name'] . "=''";
            } else {
              $values .= $column['name'] . "=" . $column['name'];
            }
          } elseif ($this->files[$column['name']]) {
            $filename =  mktime() . "_" . $this->request[$column['name']];
            if (!file_exists(ROOTDIR . '/files/' . $this->name)) mkdir(ROOTDIR . '/files/' . $this->name);
            move_uploaded_file($this->files[$column['name']], ROOTDIR . '/files/' . $this->name . '/' . $filename);
            $value = $this->database->escape($filename);
            $values .= $column['name'] . "=" ."'" . $value . "'";
            if ($column['extra'] && defined('PIXDIR'))  $sizes = explode(',',$column['extra']);
            if(isset($sizes)) {
              foreach($sizes as $size) {
                $image = imagecreatefromstring(file_get_contents(ROOTDIR.'/files/'.$this->name.'/'.$filename));
                list($ancho,$alto) = split('x', $size);
                $alto_original = imagesy($image);
                $ancho_original = imagesx($image);
                if (!$alto) $alto = ceil($alto_original*($ancho/$ancho_original));
                $new_image = imagecreatetruecolor ($ancho, $alto);
                imagecopyresampled($new_image, $image, 0, 0, 0, 0, $ancho, $alto, $ancho_original, $alto_original);
                imagejpeg($new_image,PIXDIR.'/'.$size.'_'.$filename,72);
              }
            }
          }
          break;
        case 'file':
          #if ($nofiles) break;
          if ($nofiles || $_REQUEST[$column['name'] . '_keep'] || !$this->files[$column['name']]) {
            if (!$_REQUEST[$column['name'] . '_keep'] && !$this->files[$column['name']])
              $values .= $column['name'] . "=''";
            else
              $values .= $column['name'] . "=" . $column['name'];
          } elseif ($this->files[$column['name']]) {
            $filename =  mktime() . "_" . $this->request[$column['name']];
            if (!file_exists(ROOTDIR . '/files/' . $this->name)) mkdir(ROOTDIR . '/files/' . $this->name);
            move_uploaded_file($this->files[$column['name']], ROOTDIR . '/files/' . $this->name . '/' . $filename);
            $value = $this->database->escape($filename);
            $values .= $column['name'] . "=" ."'" . $value . "'";
          }
          break;
        case 'char':
          if ($this->request[$column['name']] == -1) {
            $this->request[$column['name']] = 'NULL';
            $values .= $column['name'] . "=" . $this->request[$column['name']];
          } else {
            $value = $this->database->escape($this->request[$column['name']]);
            $values .= $column['name'] . "=" ."'" . $value . "'";
          }
          break;
        case 'varchar':
          if ($this->request[$column['name']] == -1) {
            $this->request[$column['name']] = 'NULL';
            $values .= $column['name'] . "=" . $this->request[$column['name']];
          } else {
            $value = ($this->escaped) ? $this->request[$column['name']] : $this->database->escape($this->request[$column['name']]);
            $values .= $column['name'] . "=" ."'" . $value . "'";
          }
          break;
        case 'text':
          $value = ($this->escaped) ? $this->request[$column['name']] : $this->database->escape($this->request[$column['name']]);
          $values .= $column['name'] . "=" ."'" . $value . "'";
          break;
        case 'bool':
        case 'boolean':
          $value = $this->request[$column['name']];
          $value = (!$value || $value == 'false' || $value == '0') ? '0' : '1';
          $values .= $column['name'] . "=" ."'" . $value . "'";
          break;
        case 'date':
        case 'datenull':
          $value = $this->request[$column['name']];
          if (isset($value) && $value != '0-00-0') {
            $value = $this->database->escape($this->request[$column['name']]);
            $values .= $column['name'] . "= '" . $value . "'";
          } else {
            $values .= $column['name'] . "= NULL";
          }
          break;
        default:
          $value = ($this->escaped) ? $this->request[$column['name']] : $this->database->escape($this->request[$column['name']]);
          $values .= $column['name'] . "=" ."'" . $value . "'";
          break;
      }
      $n++;
      if ($maxcols && (($n+1) >= $maxcols)) break;
    }
    return $values;
  }
  
  function updateRecord($id = 0, $maxcols = 0, $nofiles = 0) {
    if (!$id && $this->request['old_' . $this->key]) $id = $this->request['old_' . $this->key];
    if (!$id) $id = $this->request[$this->key];
    $values = $this->preUpdateRecord($maxcols, $nofiles);
    $sqlcmd = "UPDATE $this->name SET $values WHERE $this->key = '$id'";
    $result = $this->query($sqlcmd);
  }

  function deleteRecord($id = 0) {
    if (!$id) $id = $this->request[$this->key];
    $sqlcmd = "DELETE FROM $this->name WHERE $this->key = '$id'";
    $result = $this->query($sqlcmd);
  }

  function getJoin() {
    $join = "";
    $references = array();
    foreach ($this->definition as $column)
      if ($column['references'] !== 0 && $column['references'] !== false) {
        if (!isset($references[$column['references']])) $references[$column['references']] = 0;
        $references[$column['references']]++;
        if ($references[$column['references']] == 1) {
          $join .= " LEFT OUTER JOIN " . $column['references'] . " ON " . $this->name . "." . $column['name'] . "=" . $column['references'] . "." . $column['name'];
        } else {
          $tmptable = $column['references'] . $references[$column['references']];
          $tmpcolumn =  "id" . $column['references'];
          $join .= " LEFT OUTER JOIN " . $column['references'] . " AS $tmptable ON " . $this->name . "." . $column['name'] . "=" . $tmptable . "." . $tmpcolumn;
        }
      }
    return $join;
  }

  function readRecord($id = 0) {
    if (!$id && isset($this->request[$this->key])) $id = $this->request[$this->key];
    #if (!$id) $id = $this->getVar("SELECT currval('" . $this->name . "_" . $this->key . "_seq')");
    if (!$id) $id = $this->getVar("SELECT MAX(" . $this->key . ") FROM " . $this->name);
    if ($this->join) {
      $sqlcmd = "SELECT $this->all_fields FROM $this->name " . $this->getJoin() . " WHERE $this->name.$this->key = '$id'";
    } else
      $sqlcmd = "SELECT $this->fields FROM $this->name WHERE $this->name.$this->key = '$id'";
    $this->execSql($sqlcmd);
    $row = $this->data->fetchRow(MDB2_FETCHMODE_ASSOC);
    if ($this->html)
      foreach($row as $key=>$val)
        $row[$key] = htmlentities($val);
    $this->current_record = $row;
    return $row;
  }

  function readRecordSQL($sqlcmd) {
    $this->execSql($sqlcmd);
    $row = $this->data->fetchRow(MDB2_FETCHMODE_ASSOC);
    $this->current_record = $row;
    return $row;
  }

  //remplaza a readList
  function readDataSQL($sqlcmd) {
    $this->execSql($sqlcmd);
    return $this->getArray(); 
  }

  function fetchNext($current) {
    $sqlcmd = "SELECT $this->key FROM $this->name";
    if ($this->order)
    	$sqlcmd .= " ORDER BY $this->order";
    $this->execSql($sqlcmd);
    $rows = @pg_fetch_all($this->data);
    foreach($rows as $row) {
      $value = $row[$this->key];
      if ($next) {
        $next = $value;
        break;
      } elseif ($value == $current)
        $next = $value;
    }
    return $next;
  }

  function fetchPrev($current) {
    $sqlcmd = "SELECT $this->key FROM $this->name";
    if ($this->order)
        $sqlcmd .= " ORDER BY $this->order";
    $this->execSql($sqlcmd);
    $rows = @pg_fetch_all($this->data);
    foreach($rows as $row) {
      $value = $row[$this->key];
      if ($value == $current) {
        $prev = $oldvalue;
        break;
      }
      $oldvalue = $value;
    }
    return $prev;
  }

  function readData() {
    if ($this->join) {
      $sqlcmd = "SELECT $this->all_fields FROM $this->name " . $this->getJoin();
    } else {
      $sqlcmd = "SELECT $this->fields FROM $this->name";
    }
    if ($this->order)
      $sqlcmd .= " ORDER BY $this->order";
    if ($this->limit)
      $sqlcmd .= " LIMIT $this->limit";
    if ($this->offset)
      $sqlcmd .= " OFFSET $this->offset";
    $this->execSql($sqlcmd);
    return $this->getArray();
  }

  function readDataFilter($filter) {
    if ($this->join) {
      $sqlcmd = "SELECT $this->all_fields FROM $this->name " . $this->getJoin();
    }
    else
      $sqlcmd = "SELECT $this->fields FROM $this->name";
    $sqlcmd .= " WHERE $filter";
    if ($this->order)
    	$sqlcmd .= " ORDER BY $this->order";
    if ($this->limit)
      $sqlcmd .= " LIMIT $this->limit";
    if ($this->offset)
      $sqlcmd .= " OFFSET $this->offset";
    $this->execSql($sqlcmd);
    return $this->getArray();

  }

  function dumpData() {
    print "<table border=1>";
    $rows = $this->readData();
    if ($rows)
      foreach($rows as $row) {
        print "<tr>";
        foreach($row as $column)
          print "<td>$column</td>";
        print "</tr>";
      }
    print "</table>";
  }

}

class TableDoubleKey extends Table {
  var $key1;
  var $key2;

  function deleteRecord($id1 = 0, $id2 = 0) {
    if (!$id1) $id1 = $this->request[$this->key1];
    if (!$id2) $id2 = $this->request[$this->key2];
    $sqlcmd = "DELETE FROM $this->name WHERE $this->key1 = '$id1' AND $this->key2 = '$id2'";
    $result = $this->query($sqlcmd);
  }

  function updateRecord($id1 = 0, $id2 = 0, $maxcols = 0, $nofiles = 0) {
    if (!$id1) $id1 = $this->request['old_' . $this->key1];
    if (!$id2) $id2 = $this->request['old_' . $this->key2];
    $values = $this->preUpdateRecord($maxcols, $nofiles);
    $sqlcmd = "UPDATE $this->name SET $values WHERE $this->key1 = '$id1' AND $this->key2 = '$id2'";
    $result = $this->query($sqlcmd);
  }

  function readRecord($id1 = 0, $id2 = 0) {
    if (!$id1) $id1 = $this->request[$this->key1];
    if (!$id2) $id2 = $this->request[$this->key2];
    if ($this->join) {
      $sqlcmd = "SELECT $this->all_fields FROM $this->name " . $this->getJoin() . " WHERE $this->name.$this->key1 = '$id1' AND $this->name.$this->key2 = '$id2'";
    } else
      $sqlcmd = "SELECT $this->fields FROM $this->name WHERE $this->name.$this->key1 = '$id1' AND $this->name.$this->key2 = '$id2'";
    $this->execSql($sqlcmd);
    $row = $this->data->fetchRow(MDB2_FETCHMODE_ASSOC);
    $this->current_record = $row;
    return $row;
  }

  function readEnv() {
    unset ($this->request);
    unset ($this->files);
    foreach($this->definition as $column) {
      if ($column['type'] != 'external' && $column['type'] != 'auto') {
        if (($column['type'] == 'file' || $column['type'] == 'image')  && $_FILES[$column['name']]['name']) {
          $this->request[$column['name']] = $_FILES[$column['name']]['name'];
          $this->files[$column['name']] = $_FILES[$column['name']]['tmp_name'];
        } elseif (preg_match('/^(date|datetime|datenull|time)$/', $column['type'])) {
          $date = ''; $time = '';
          if (preg_match('/^(date|datetime|datenull)$/', $column['type']))
            $date = $this->parsevar($_REQUEST[$column['name']]);
          else
            $time = $this->parsevar($_REQUEST[$column['name']]);
          if ($_REQUEST[$column['name'] . '_Year']) {
            $year = $this->parsevar($_REQUEST[$column['name'] . '_Year'], 'int');
            $month = $this->parsevar($_REQUEST[$column['name'] . '_Month'], 'int');
            $day = $this->parsevar($_REQUEST[$column['name'] . '_Day'], 'int');
            $date = $year . '-' . $month . '-' . $day;
          }
          if ($_REQUEST[$column['name'] . '_Hour']) {
            $this->request[$column['name']] = $year . '-' . $month . '-' . $day;
            $hour = $this->parsevar($_REQUEST[$column['name'] . '_Hour'], 'int');
            $minute = $this->parsevar($_REQUEST[$column['name'] . '_Minute'], 'int');
            $second = $this->parsevar($_REQUEST[$column['name'] . '_Second'], 'int');
            $time = $hour . ':' . $minute . ':' . $second;
          }
          $datetime = trim("$date $time");
          $this->request[$column['name']] = $datetime;
        } else {
          $this->request[$column['name']] = $this->parsevar($_REQUEST[$column['name']], $column['type']); 
        }
      }
    }
    $this->request['old_' . $this->key1] = $_REQUEST['old_' . $this->key1];
    $this->request['old_' . $this->key2] = $_REQUEST['old_' . $this->key2];
  }
}

?>
