<?php
// vim:set expandtab tabstop=2 shiftwidth=2 fdm=marker:

/**
 * db2.class.php
 *
 * DAL para almidon
 *
 * @copyright &copy; 2005-2009 Guegue Comunicaciones - guegue.com
 * @license http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @version $Id: db2.class.php,v 2008101301 javier $
 * @package almidon
 */

require('db.const.php');

# Finally... the DAL...

require_once('db.dal.php');

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
    require('db.data.php');
  }

  function check_error($sqlcmd, $extra = '', $die = false) {
    require('db.error.php');
  }
 
  function sql_log($logtext) {
    require('db.logging.php');
  } 

  function query($sqlcmd) {
    require('db.query.php');
    return $result;
  }
  
  #function tablesColumnExists($column_name) {
  #  $exists = preg_match("/(,|^)$column_name(,|$)/",$this->fields);
  #  return $exists;
  #}

  #function catalogColumnExists($column_name) {
  #  require('db.catalogcolumnexists.php');
  #  return $exists;
  #}

  function execSql($sqlcmd) {
    $this->data = $this->query($sqlcmd);
    if (!almdata::isError($sqlcmd) && $this->data && (preg_match('/^SELECT/',$sqlcmd) || preg_match('/^SHOW/',$sqlcmd))) {
      $this->num = almdata::rows($this->data);
    }
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
      $row = almdata::fetchRow($this->data, false);
    if (isset($row[0]))
      return $row[0];
  }

  //Lee un statement sql y devuelve una lista de una sola columna (la primera)
  function getList($sqlcmd) {
    require('db.getlist.php');
    if (isset($array_rows))
      return $array_rows;
  }

  function getArray() {
    require('db.getarray.php');
    return (isset($array_rows) ? $array_rows : null);
  }

  function selectList($sqlcmd) {
    require('db.selectlist.php');
    return $menu;
  }

  function selectMenu($sqlcmd = '', $filter = '') {
    require('db.selectmenu.php');
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
    almdata::disconnect();
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

  # Aplica cambios necesarios a la BD desde tables.class.php
  function syncToDB() {
    require('db.synctodb.php');
  }

  # Aplica cambios necesario a tables.class.php
  function syncFromAlm() {
    $autosave = true;
    require('setup.autotables.php');
    if (!$saved)
      echo "tables.class.php no escribible!<br/>\n";
    else
      echo "tables.class.php actualizado!<br/>\n";
  }

  function refreshFields() {
    global $global_dd;
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
      if ($column['references'] && isset($global_dd[$column['references']]['descriptor'])) {
        if (!isset($references[$column['references']])) $references[$column['references']] = 0;
        $references[$column['references']]++;
        if ($references[$column['references']] == 1) {
	  $this->all_fields .= "," . $column['references'] . "." . $global_dd[$column['references']]['descriptor'];
        } else {
          $tmptable = $column['references'] . $references[$column['references']];
          $tmpcolumn =  $global_dd[$column['references']]['descriptor'];
	  $this->all_fields .= "," . $tmptable . "." . $tmpcolumn . " AS " . $tmptable;
        }
      }
      $n++;
      $ns++;
    }
  }

  function addColumn($name, $type, $size = 100, $pk = 0, $references = 0, $label = '', $extra = '') {
    require('db.addcolumn.php');
  }

  function parsevar($tmpvar, $type = 'string', $html = false, $allow_js = false) {
    require('db.parsevar.php');
    return $tmpvar;
  }

  function readArgs() {
    require('db.readargs.php');
    return $args;
  }

  function readEnv($friendly = false) {
    include('db.readenv.php');
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
          if (!isset($this->request[$column['name']]) || $this->request[$column['name']] == -1)
            $this->request[$column['name']] = 'NULL';
        case 'smallint':
        case 'numeric':
          $values .= $this->request[$column['name']];
          break;
        case 'image':
          $value = '';
          if (isset($this->files[$column['name']])) {
            $filename =  mktime() . "_" . $this->request[$column['name']];
            if (!file_exists(ROOTDIR . '/files/' . $this->name)) mkdir(ROOTDIR . '/files/' . $this->name);
            move_uploaded_file($this->files[$column['name']], ROOTDIR . '/files/' . $this->name . '/' . $filename);
            $this->request[$column['name']] = $filename;
            if ($column['extra'] && defined('PIXDIR'))  $sizes = explode(',',$column['extra']);
            if(isset($sizes)) {
              foreach($sizes as $size) {
                $image = imagecreatefromstring(file_get_contents(ROOTDIR.'/files/'.$this->name.'/'.$filename));
                list($ancho,$alto) = preg_split('/x/', $size);
                $alto_original = imagesy($image);
                $ancho_original = imagesx($image);
                if (!$alto) $alto = ceil($alto_original*($ancho/$ancho_original));
                $new_image = imagecreatetruecolor ($ancho, $alto);
                imagecopyresampled($new_image, $image, 0, 0, 0, 0, $ancho, $alto, $ancho_original, $alto_original);
                imagejpeg($new_image,PIXDIR.'/'.$size.'_'.$filename,72);
              }
            }
            $value = almdata::escape($this->request[$column['name']]);
          }
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
            $value = almdata::escape($this->request[$column['name']]);
            $values .= "'" . $value . "'";
          }
          break;
        case 'varchar':
          if (!isset($this->request[$column['name']]) || $this->request[$column['name']] == -1) {
            $this->request[$column['name']] = 'NULL';
            $values .= $this->request[$column['name']];
          } else {
            $value = ($this->escaped) ? $this->request[$column['name']] : almdata::escape($this->request[$column['name']]);
            $values .= "'" . $value . "'";
          }
          break;
        case 'text':
          if (isset($this->request[$column['name']])) {
            $value = ($this->escaped) ? $this->request[$column['name']] : almdata::escape($this->request[$column['name']]);
            $values .= "'" . $value . "'";
          } else {
            $values .= "NULL";
          } 
          break;
        case 'bool':
        case 'boolean':
          $value = (isset($this->request[$column['name']])) ? $this->request[$column['name']] : false;
          $value = (!$value || $value == 'false' || $value == '0' || $value == 'f') ? '0' : '1';
          $values .= "'" . $value . "'";
          break;
        case 'date':
        case 'datetime':
        case 'datenull':
          $value = $this->request[$column['name']];
          if (isset($value) && $value != '0-00-0' && !empty($value)) {
            $value = almdata::escape($this->request[$column['name']]);
            $values .= "'" . $value . "'";
          } else {
            $values .= 'NULL';
          }
          break;
        default:
          if (isset($this->request[$column['name']])) {
            $value = ($this->escaped) ? $this->request[$column['name']] : almdata::escape($this->request[$column['name']]);
            $values .= "'" . $value . "'";
          } else {
            $values .= "NULL";
          }
          break;
      }
      $n++;
    }
    $sqlcmd = "INSERT INTO $this->name ($this->fields_noserial) VALUES ($values)";
    $result = $this->query($sqlcmd);
  }

  function preUpdateRecord($maxcols = 0, $nofiles = 0) {
    $n = 0;
    $skipped_cols = 0;
    $values = "";
    foreach($this->definition as $column) {
      if ($n > 0 && $column['type'] != 'external' && $column['type'] != 'auto' && $column['type'] != 'order' && $column['type'] != 'serial')
        $values .= ",";
      switch($column['type']) {
      	case 'external':
      	case 'auto':
      	case 'order':
        case 'serial':
          $skipped_cols++;
          $n--;
          break;
        case 'int':
          if (!isset($this->request[$column['name']]) || $this->request[$column['name']] === -1 || $this->request[$column['name']] === '')
            $this->request[$column['name']] = 'NULL';
        case 'smallint':
        case 'numeric':
          $values .= $column['name'] . "=" . $this->request[$column['name']];
          break;
        case 'image':
	  if ($nofiles || isset($_REQUEST[$column['name'] . '_keep']) || !isset($this->files[$column['name']]) || empty($this->files[$column['name']]) ) {
            if (!isset($_REQUEST[$column['name'] . '_keep']) && empty($this->files[$column['name']])) {
              $values .= $column['name'] . "=''";
            } else {
              $values .= $column['name'] . "=" . $column['name'];
            }
          } elseif ($this->files[$column['name']]) {
            $filename =  mktime() . "_" . $this->request[$column['name']];
            if (!file_exists(ROOTDIR . '/files/' . $this->name)) mkdir(ROOTDIR . '/files/' . $this->name);
            move_uploaded_file($this->files[$column['name']], ROOTDIR . '/files/' . $this->name . '/' . $filename);
            $value = almdata::escape($filename);
            $values .= $column['name'] . "=" ."'" . $value . "'";
            if ($column['extra'] && defined('PIXDIR'))  $sizes = explode(',',$column['extra']);
            if(isset($sizes)) {
              foreach($sizes as $size) {
                $image = imagecreatefromstring(file_get_contents(ROOTDIR.'/files/'.$this->name.'/'.$filename));
                list($ancho,$alto) = preg_split('/x/', $size);
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
            $value = almdata::escape($filename);
            $values .= $column['name'] . "=" ."'" . $value . "'";
          }
          break;
        case 'char':
          if ($this->request[$column['name']] == -1) {
            $this->request[$column['name']] = 'NULL';
            $values .= $column['name'] . "=" . $this->request[$column['name']];
          } else {
            $value = almdata::escape($this->request[$column['name']]);
            $values .= $column['name'] . "=" ."'" . $value . "'";
          }
          break;
        case 'varchar':
          if (!isset($this->request[$column['name']]) || $this->request[$column['name']] == -1) {
            $this->request[$column['name']] = 'NULL';
            $values .= $column['name'] . "=" . $this->request[$column['name']];
          } else {
            $value = ($this->escaped) ? $this->request[$column['name']] : almdata::escape($this->request[$column['name']]);
            $values .= $column['name'] . "=" ."'" . $value . "'";
          }
          break;
        case 'text':
          if (isset($this->request[$column['name']])) {
            $value = ($this->escaped) ? $this->request[$column['name']] : almdata::escape($this->request[$column['name']]);
            $values .= $column['name'] . "=" ."'" . $value . "'";
          } else {
            $values .= $column['name'] . "=NULL";
          }
          break;
        case 'bool':
        case 'boolean':
          $value = (isset($this->request[$column['name']])) ? $this->request[$column['name']] : '0';
          $value = (!$value || $value == 'false' || $value == '0') ? '0' : '1';
          $values .= $column['name'] . "=" ."'" . $value . "'";
          break;
        case 'date':
        case 'datenull':
          $value = $this->request[$column['name']];
          if (isset($value) && $value != '0-00-0') {
            $value = almdata::escape($this->request[$column['name']]);
            $values .= $column['name'] . "= '" . $value . "'";
          } else {
            $values .= $column['name'] . "=NULL";
          }
          break;
        default:
          if (isset($this->request[$column['name']])) {
            $value = ($this->escaped) ? $this->request[$column['name']] : almdata::escape($this->request[$column['name']]);
            $values .= $column['name'] . "=" ."'" . $value . "'";
          } else {
            $values .= $column['name'] . "=NULL";
          }
          break;
      }
      $n++;
      if ($maxcols && (($n+$skipped_cols) >= $maxcols)) break;
    }
    return $values;
  }
  
  function updateRecord($id = 0, $maxcols = 0, $nofiles = 0) {
    require('db.updaterecord.php');
  }

  function deleteRecord($id = 0) {
    require('db.deleterecord.php');
  }

  function getJoin() {
    global $global_dd;
    $join = "";
    $references = array();
    foreach ($this->definition as $column)
      if ($column['references'] !== 0 && $column['references'] !== false) {
        if (!isset($references[$column['references']])) $references[$column['references']] = 0;
        $references[$column['references']]++;
        if ($references[$column['references']] == 1) {
          $join .= " LEFT OUTER JOIN " . $column['references'] . " ON " . $this->name . "." . $column['name'] . "=" . $column['references'] . "." . $global_dd[$column['references']]['key'];
        } else {
          $tmptable = $column['references'] . $references[$column['references']];
          $tmpcolumn =  $global_dd[$column['references']]['key'];
          $join .= " LEFT OUTER JOIN " . $column['references'] . " AS $tmptable ON " . $this->name . "." . $column['name'] . "=" . $tmptable . "." . $tmpcolumn;
        }
      }
    return $join;
  }

  function readRecord($id = 0) {
    require('db.readrecord.php');
    if (isset($row))
      return $row;
  }

  function readRecordSQL($sqlcmd) {
    $this->execSql($sqlcmd);
    $row = almdata::fetchRow($this->data);
    $this->current_record = $row;
    return $row;
  }

  //remplaza a readList
  function readDataSQL($sqlcmd) {
    $this->execSql($sqlcmd);
    return $this->getArray(); 
  }

  function fetchNext($current) {
    require('db.fetchnext.php');
    return $next;
  }

  function fetchPrev($current) {
    require('db.fetchprev.php');
    return $prev;
  }

  function readData() {
    require('db.readdata.php');
    return $this->getArray();
  }

  function readDataFilter($filter) {
    require('db.readdatafilter.php');
    return $this->getArray();
  }

  function dumpData() {
    require('db.dumpdata.php');
  }

}

class TableDoubleKey extends Table {
  var $key1;
  var $key2;

  function deleteRecord($id1 = 0, $id2 = 0) {
    require('db.deleterecord2.php');
  }

  function updateRecord($id1 = 0, $id2 = 0, $maxcols = 0, $nofiles = 0) {
    require('db.updaterecord2.php');
  }

  function readRecord($id1 = 0, $id2 = 0) {
    require('db.readrecord2.php');
    return $row;
  }

  function readEnv() {
    require('db.readenv2.php');
  }
}

