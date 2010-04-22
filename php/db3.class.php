<?php
// vim: set expandtab tabstop=2 shiftwidth=2 fdm=marker:

/**
 * db3.class.php
 *
 * DAL entre almidon y PEAR::MDB2
 *
 * @copyright &copy; 2005-2009 Guegue Comunicaciones - guegue.com
 * @license http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @version $Id: db3.class.php,v 20090703 christian $
 * @package almidon
 */

# Use Almidon's PEAR
if (defined('ALMIDONDIR')) {
  if(defined('KEEP_INCPATH')&&KEEP_INCPATH===false)
    set_include_path(ALMIDONDIR . '/php/pear:'.ALMIDONDIR.'/php:'.ALMIDONDIR.'/ext-libs');
  else
    set_include_path(get_include_path() . PATH_SEPARATOR . ALMIDONDIR . '/php/pear:'.ALMIDONDIR.'/php:'.ALMIDONDIR.'/ext-libs');
}

require('db.const.php');

require('image.class.php');

# Finally... the DAL...
require_once('MDB2.php');

class Data {
  // {{{ variables
  var $data;
  var $database;
  var $num;
  var $max;
  var $limit;
  var $offset;
  var $filter;
  var $current_pg;
  var $current_record;
  var $current_id;
  var $key;
  var $html;
  var $cols;
  // }}} variables

  function Data () {
    require('db.data.php');
  }

  function check_error($obj, $extra = '', $die = false) {
    require('db.error.php');
  }

  function sql_log($logtext) {
    require('db.logging.php');
  }

  function query($sqlcmd) {
    require('db.query.php');
    return $result;
  }

  function execSql($sqlcmd) {
    $this->data = $this->query($sqlcmd);
    if ($this->data && (strpos($sqlcmd,'SELECT') !== false))
      $this->num = $this->data->numRows();
  }

  function http_auth_user() {
    require('db.http_auth_user.php');
    return $auth_user;
  }

  // {{{ funciones lectura de datos
  //Mejor usar readDataSQL, funcion repetida
  function readList($sqlcmd) {
    $this->execSql($sqlcmd);
    return $this->getArray();
  }

  function getVar($sqlcmd) {
    $this->execSql($sqlcmd);
    $row = $this->data->fetchRow(MDB2_FETCHMODE_ORDERED);
    return $row[0];
  }

  //Lee un statement sql y devuelve una lista de una sola columna (la primera)
  function getList($sqlcmd) {
    require('db.getlist.php');
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
  // }}} funciones lectura de datos

  // {{{ paginacion
  //Pagina los datos del query actual segun $num y $max
  function getNumbering() {
    unset($this->pg);
    $numpg = ceil($this->num / $this->max);
    for ($n = 1; $n <= $numpg; $n++)
      $this->pg[] = $n;
    return $this->pg;
  }
  // }}} paginacion

  // {{{ destruccion
  function destroy() {
    $this->database->disconnect();
  }
  // }}} destuccio
}

class Table extends Data {
  // {{{ variables Table
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
  var $child;
  var $hide;
  // }}} variables Table

  function Table($name, $schema = 'public') {
    $this->Data();
    $this->name = $name;
    $this->schema = $schema;
    if ($schema && $schema != 'public')
      $this->query("SET search_path = $schema, public, pg_catalog");
    $this->hide = false;
  }

  // {{{ refreshFields()
  /**
   * Actualiza lista de campos a usar en SELECT a partir de $this->definition
   * guarda valor de campos en $this->all_fields, $this->fields_noserial y $this->fields
   *
   * @access public
   */
  function refreshFields() {
    $n = 0;
    $ns = 0;
    unset($this->fields_noserial);
    unset($this->all_fields);
    unset($this->fields);
    foreach($this->definition as $column) {
      if ($n > 0) {
        $this->fields .= ",";
        $this->all_fields .= ",";
      }
      if ($this->schema != 'public')
        $this->all_fields .= $this->schema . ".";
      if ($ns > 0 && $column['type'] != 'external' && ($column['type'] != 'auto'||!empty($column['extra']['default'])) && $column['type'] != 'order' && $column['type'] != 'serial')
        $this->fields_noserial .= ",";
      if ($column['type'] == 'serial' || $column['type'] == 'external' || ($column['type'] == 'auto' && empty($column['extra']['default'])) || $column['type'] == 'order' || $column['type']=='serial')
        $ns--;
      else 
        $this->fields_noserial .= $column['name'];
      $this->fields .= $column['name'];
      if ($column['type'] == 'external')
        $this->all_fields .= $column['name'];
      else
        $this->all_fields .= $this->name . "." . $column['name'];
      if ($column['references']) {
        if (!empty($column['extra']['display'])) {
          if(empty($column['extra']['alias']))  $this->all_fields .= ",(" . $column['extra']['display'] . ") AS " . $column['references'];
          else  $this->all_fields .= ",(" . $column['extra']['display'] . ") AS " . $column['extra']['alias'];
        } else {
          $pos = strpos($column['references'],'.');
 	        if($pos!==false) {
             $r_table = substr($column['references'],0,$pos);
  	         $pos_2 = strpos($column['references'],'[');
	           if($pos_2 !== false) {
   	           $r_field = substr($column['references'],$pos+1,$pos_2-$pos-1);
   	           $r_alias = substr($column['references'],$pos_2+1,strlen($column['references'])-($pos_2+2));
	           }else $r_field = substr($column['references'],$pos+1);
          }else{
             $r_table = $r_field = $column['references'];
	        }
 	        $this->all_fields .= "," . (empty($r_alias)?$r_table:$r_alias) . "." . $r_table;
	        if(!empty($r_alias)) $this->all_fields .= " AS $r_alias";
	        #$this->all_fields .= "," . $column['references'] . "." . $column['name'];
        }
      }
      $n++;
      $ns++;
    }
  }
  // }}} refreshFields()

  /*
  @array extra
         keys:
	  - sizes
	  - range
      - defualt
	  - list_values
	  - label_bool
	  - filter
	  For FK (Foreign Key)
      - display
      - alias
  */
  function addColumn($name, $type, $size = 100, $pk = 0, $references = 0, $label = '', $extra = '') {
    require('db.addcolumn.php');
  }

  function parsevar($tmpvar, $type = 'string', $html = false, $allow_js = false) {
    require('db.parsevar.php');
  }

  function readArgs() {
    require('db.readargs.php');
  }

  function readEnv($friendly = false) {
    include('db.readenv.php');
  }

  function addRecord() {
    $n = 0;
    $values ="";
    foreach($this->definition as $column) {
      if ($n > 0 && $column['type'] != 'external' && ($column['type'] != 'auto'||!empty($column['extra']['default'])) && $column['type'] != 'order' && $column['type'] != 'serial')
        $values .= ",";
      switch($column['type']) {
        case 'auto':
          if(!empty($column['extra']['default'])) {
            $values .= "'".$column['extra']['default']."'";
            break;
          }
      	case 'external':
        case 'serial':
        case 'order':
          $n--;
          break;
        case 'int':
          if ($this->request[$column['name']] == -1 || (!isset($this->request[$column['name']]) || $this->request[$column['name']]==NULL))
            $this->request[$column['name']] = 'NULL';
        case 'smallint':
        case 'numeric':
          $values .= $this->request[$column['name']];
          break;
        case 'image':
          if ($this->files[$column['name']]) {
          	$timemark = getdate();
            $filename =  $timemark[0] . "_" . $this->request[$column['name']];
	    if (!is_dir(ROOTDIR . "/files/" . $this->name))  mkdir(ROOTDIR . "/files/" . $this->name, ALM_MASK);
            if(move_uploaded_file($this->files[$column['name']], ROOTDIR . "/files/" . $this->name . "/" . $filename)) {
              $this->request[$column['name']] = $filename;
	      if ($column['extra']['sizes'] && defined('PIXDIR'))  {
                $sizes = explode(',',trim($column['extra']['sizes']));
                if ($column['extra']['radius'])  $radius = explode (',',trim($column['extra']['radius']));
              }
	      if(isset($sizes))  {
                $image = new almImage();
                if ($timemark['mon']<10&&strlen($timemark['mon'])==1) $timemark['mon'] = "0" . $timemark['mon'];
                // Comprueba que existan los directorios y sino
	        // los crea
	        if(!is_dir(PIXDIR."/".$timemark['year']))  mkdir(PIXDIR."/".$timemark['year'], ALM_MASK);
	        if(!is_dir(PIXDIR."/".$timemark['year']."/".$timemark['mon']))  mkdir(PIXDIR."/".$timemark['year']."/".$timemark['mon'], ALM_MASK);
                if($sizes)
                  for($idx=0;$idx<count($sizes);$idx++) {
                    $pic = null;
	            list($w, $h, $crop) = preg_split('/x/', trim($sizes[$idx]));
		    if($crop&&$h) {
                      $pic = $image->crop(ROOTDIR . "/files/" . $this->name . "/" . $filename,$w,$h);
                    } else {
                      $pic = $image->resize(ROOTDIR . "/files/" . $this->name . "/" . $filename,$w,$h);
                    }
                    $thumbf = PIXDIR . "/" . $timemark['year'] . "/" . $timemark['mon'] . "/$w" . ($h?"x$h":"") . "_" . $filename;
                    if($radius[$idx]>0)  $pic = $image->rounded($pic,$radius[$idx]);
                    if (imagejpeg($pic, $thumbf, IMG_QUALITY) === FALSE) {
                      error_log("ERROR al escribir " . $thumbf);
                    }
                  }
	      }
            } else {
              $this->request[$column['name']] = '';
            }
          } else {
            $this->request[$column['name']] = '';
          }
          $value = $this->database->escape($this->request[$column['name']]);
          $values .= "'" . $value . "'";
          break;
        case 'file':
          if ($this->files[$column['name']]) {
            $filename =  mktime() . "_" . $this->request[$column['name']];
	    if(!is_dir(ROOTDIR . "/files/" . $this->name))  mkdir(ROOTDIR . "/files/" . $this->name, ALM_MASK);
            if(move_uploaded_file($this->files[$column['name']], ROOTDIR . "/files/" . $this->name . "/" . $filename))
              $this->request[$column['name']] = $filename;
          } else {
            $this->request[$column['name']] = '';
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
        case 'auth_user':
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
          $value = (!$value || $value == 'false' || $value == '0') ? '0' : '1';
          $values .= "'" . $value . "'";
          break;
        case 'date':
        case 'datetime':
        case 'datenull':
          $value = $this->request[$column['name']];
          if (isset($value) && $value != 'CURRENT_DATE' && $value != '0-00-0' && !empty($value)) {
            $value = $this->database->escape($this->request[$column['name']]);
            $values .= "'" . $value . "'";
          } else {
            $values .= 'NULL';
            #$values .= 'CURRENT_DATE';
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
      if ($n > 0 && $column['type'] != 'external' && $column['type'] != 'auto' && $column['type'] != 'order' && $column['type'] != 'serial' && $column['type'] != 'auth_user')
        $values .= ",";
      switch($column['type']) {
      	case 'external':
      	case 'auth_user':
      	case 'auto':
      	case 'order':
        case 'serial':
          $n--;
          break;
        case 'int':
          if ($this->request[$column['name']] === -1 || trim($this->request[$column['name']]) === '' || ($this->request[$column['name']]==0 && !empty($column['references'])))
            $this->request[$column['name']] = 'NULL';
        case 'smallint':
        case 'numeric':
          $values .= $column['name'] . "=" . $this->request[$column['name']];
          break;
        case 'image':
          if ($nofiles || ($_REQUEST[$column['name'] . '_keep']&&!$this->files[$column['name']]) || !$this->files[$column['name']]) {
            if (!$_REQUEST[$column['name'] . '_keep'] && !$this->files[$column['name']]) {
              $values .= $column['name'] . "=''";
            } else {
              $values .= $column['name'] . "=" . $column['name'];
            }
            if(($this->request['old_'.$column['name']] != $this->files[$column['name']]) && $this->request['old_'.$column['name']] && !$_REQUEST[$column['name'] . '_keep']) {
	      if(file_exists(ROOTDIR . "/files/" . $this->name . "/" . $this->request['old_'.$column['name']])) unlink(ROOTDIR . "/files/" . $this->name . "/" . $this->request['old_'.$column['name']]);
  	      if ($column['extra']['sizes'] && defined('PIXDIR'))  $sizes = explode(',',trim($column['extra']['sizes']));
     	      if (isset($sizes)) {
       	        // FIXME: esta linea da un warning: Warning: Wrong parameter count for strpos() in /www/cms/php/db3.class.php on line 550 Warning: Wrong parameter count for substr() in /www/cms/php/db3.class.php on line 550
	        $timemark = getdate(substr($this->request['old_'.$column['name']],0,strpos($this->request['old_'.$column['name']]),"_"));
                if ($timemark['mon']<10 && strlen($timemark['mon'])==1)  $timemark['mon'] = "0" . $timemark['mon'];
                if($sizes)
	          foreach($sizes as $size) {
	            list($w, $h, $crop) = preg_split('/x/', trim($size));
	            if(file_exists(PIXDIR . "/" .$timemark['year']."/".$timemark['mon']."/".$w.($h?"x$h":""). "_" . $this->request['old_'.$column['name']])) unlink(PIXDIR . "/" .$timemark['year']."/".$timemark['mon']."/".$w.($h?"x$h":""). "_" . $this->request['old_'.$column['name']]);
	          }
  	        }
            }
          } elseif ($this->files[$column['name']]) {
            $timemark = getdate();
            $filename =  $timemark[0] . "_" . $this->request[$column['name']];
	    if(!is_dir(ROOTDIR . "/files/" . $this->name))  mkdir(ROOTDIR . "/files/" . $this->name, ALM_MASK);
            if(move_uploaded_file($this->files[$column['name']], ROOTDIR . "/files/" . $this->name . "/" . $filename))
              $value = $this->database->escape($filename);
            else $value ='';
            $values .= $column['name'] . "=" ."'" . $value . "'";
            if ($timemark['mon']<10 && strlen($timemark['mon'])==1)  $timemark['mon'] = "0" . $timemark['mon'];
            if ($column['extra']['sizes'] && defined('PIXDIR')) {
              $sizes = explode(',',trim($column['extra']['sizes']));
              if ($column['extra']['radius'])  $radius = explode (',',trim($column['extra']['radius']));
            }
            if(isset($sizes))  {
              $image = new almImage();
              // Comprueba que existan los directorios y sino
              // los crea
              if(!is_dir(PIXDIR."/".$timemark['year']))  mkdir(PIXDIR."/".$timemark['year'], ALM_MASK);
              if(!is_dir(PIXDIR."/".$timemark['year']."/".$timemark['mon']))  mkdir(PIXDIR."/".$timemark['year']."/".$timemark['mon'], ALM_MASK);
              if($sizes)
                for($idx=0;$idx<count($sizes);$idx++) {
                  $pic = null;
                  list($w, $h, $crop) = preg_split('/x/', trim($sizes[$idx]));
                  if($crop&&$h) {
                    $pic = $image->crop(ROOTDIR . "/files/" . $this->name . "/" . $filename,$w,$h);
                  } else {
                    $pic = $image->resize(ROOTDIR . "/files/" . $this->name . "/" . $filename,$w,$h);
                  }
                  $thumbf = PIXDIR . "/" . $timemark['year'] . "/" . $timemark['mon'] . "/$w" . ($h?"x$h":"") . "_" . $filename;
                  if($radius[$idx]>0)  $pic = $image->rounded($pic,$radius[$idx]);
                  if (imagejpeg($pic, $thumbf, IMG_QUALITY) === FALSE) {
                    error_log("ERROR al escribir " . $thumbf);
                  }
                }
              if(file_exists(ROOTDIR . "/files/" . $this->name . "/" . $this->request['old_'.$column['name']]) && $this->request['old_'.$column['name']]) unlink(ROOTDIR . "/files/" . $this->name . "/" . $this->request['old_'.$column['name']]);
            }
          }
          break;
        case 'file':
          if ($nofiles || $_REQUEST[$column['name'] . '_keep'] || !$this->files[$column['name']]) {
            if (!$_REQUEST[$column['name'] . '_keep'] && !$this->files[$column['name']])
              $values .= $column['name'] . "=''";
            else
              $values .= $column['name'] . "=" . $column['name'];
          } elseif ($this->files[$column['name']]) {
            $filename =  mktime() . "_" . $this->request[$column['name']];
	    if(!is_dir(ROOTDIR . "/files/" . $this->name))  mkdir(ROOTDIR . "/files/" . $this->name, ALM_MASK);
            if(move_uploaded_file($this->files[$column['name']], ROOTDIR . "/files/" . $this->name . "/" . $filename))
              $value = $this->database->escape($filename);
            else $value = '';
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
          if ($value && $value != 'CURRENT_DATE' && $value != ' ') {
            $value = $this->database->escape($this->request[$column['name']]);
            $values .= $column['name'] . "= '" . $value . "'";
          } else {
            $values .= $column['name'] . "= NULL";
            #$values .= 'CURRENT_DATE';
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
    require('db.updaterecord.php');
  }

  function deleteRecord($id = 0) {
    require('db.deleterecord.php');
  }

  function getJoin() {
    $join = "";
    foreach ($this->definition as $column)
      if (!empty($column['references'])) {
        if (isset($references[$column['references']])) $references[$column['references']]++;
        else $references[$column['references']] = 1;
        // Si solo hay una unica referencia a la tabla
        if ($references[$column['references']] == 1) {
          $pos = strpos($column['references'],'.');
          if($pos!==false) {
      	    $r_table = substr($column['references'],0,$pos);
            $pos_2 = strpos($column['references'],'[');
      	    if($pos_2!==false) {
	            $r_field = substr($column['references'],$pos+1,$pos_2-$pos-1);
              $r_alias = substr($column['references'],$pos_2+1,strlen($column['references'])-($pos_2+2));
            }else{
       	      $r_field = substr($column['references'],$pos+1);
      	    }
            $join .= " LEFT OUTER JOIN " . $r_table . " ".(empty($r_alias)?"":"AS ".$r_alias." ")."ON " . $this->name . "." . $column['name'] . "=" . (empty($r_alias)?$r_table:$r_alias).".".$r_field;
          } else {
            $join .= " LEFT OUTER JOIN " . $column['references'] . " ON " . $this->name . "." . $column['name'] . "=" . $column['references'] . "." . (!empty($column['extra']['foreign'])?$column['extra']['foreign']:$column['name']);
          }
        } else {
          $tmptable = $column['references'] . $references[$column['references']];
          $tmpcolumn =  "id" . $column['references'];
          $join .= " LEFT OUTER JOIN " . $column['references'] . " AS $tmptable ON " . $this->name . "." . $column['name'] . "=" . $tmptable . "." . $tmpcolumn;
        }
      }
    return $join;
  }

  function readRecord($id = 0) {
    require('db.readrecord.php');
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
