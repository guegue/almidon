<?php
// vim:set expandtab tabstop=2 shiftwidth=2 fdm=marker:

/**
 * db2.class.php
 *
 * DAL para almidon, clases y funciones ppales para manejo de datos
 *
 * @copyright &copy; 2005-2010 Guegue Comunicaciones - guegue.com
 * @license http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @version $Id: db2.class.php,v 2010090901 javier $
 * @package almidon
 */

/**
 * where's almidon? and other server-wide constants
 */
require('db.const.php');

# Finally... the DAL...

/**
 * main DAL, multi-db support, cdn support
 */
require_once('db.dal.php');

/**
 * @package almidon
*/

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
 
  /**
   *  crea linea en bitacora de comando sql
   */
  function sql_log($logtext) {
    require('db.logging.php');
  } 

  /**
   *  hace la consulta utilizando almdata
   *  @name $sqlcmd comando SQL
   *  @return recuerso tipo query
   */
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

  function execSql($sqlcmd, $cache = null, $table = null) {
    $this->data = $this->query($sqlcmd);
    if (!almdata::isError($sqlcmd) && $this->data && (preg_match('/^SELECT/',$sqlcmd) || preg_match('/^SHOW/',$sqlcmd))) {
      $this->num = almdata::rows($this->data);
    }
  }

  function http_auth_user() {
    require('db.http_auth_user.php');
    return $auth_user;
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

  function getArray($cache = null) {
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

/**
 * @package almidon
*/

class Table extends Data {
  var $name;
  var $definition;
  var $dd;
  var $title;
  var $request;
  var $files;
  var $fields; // comma-separated list of fields: name, age, sex
  var $table_fields; // comma-separated list: person.name, person.age, person.sex
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
    # FIXME: schemas solo los soporta postgresql? estamos usando esto?
    $this->schema = $schema;
    if ($schema && $schema != 'public')
      $this->query("SET search_path = $schema, public, pg_catalog");
  }

  /**
   * lee un registro o registros de una tabla
   * usado tipicamente por php publicos, lee un registro si se envia un id, lee todos si no.
   * no hay parametros, los toma de request, y no hay return, los asigna a smarty
  */
  function readRows() {
   global $smarty;
    $this->readEnv();
    if (isset($this->request[$this->key])) {
      $row = $this->readRecord();
      $smarty->assign('row',$row);
    } else {
      $rows = $this->readData();
      $smarty->assign('rows',$rows);
    }
  }

  /**
  * devuelve la lista de campos con archivos sean image, file, etc. en la tabla
  * @return array lista de archivos
  */
  function getFiles() {
    foreach($this->dd as $key=>$val) {
      $type = $this->dd[$key]['type'];
      if ($type == 'image' || $type == 'file')
        $remove_files[] = $key;
    }
    return($remove_files);
  }

  /**
  * syncToDB, aplica cambios necesarios a la BD desde tables.class.php
  */
  function syncToDB() {
    require('db.synctodb.php');
  }

  /**
  * syncFromAlm, aplica cambios necesarios a tables.class.php (desde tablas alm_*)
  */
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
    $this->table_fields = '';
    foreach($this->definition as $column) {
      if ($n > 0) {
        $this->fields .= ",";
        $this->table_fields .= ",";
        $this->all_fields .= ",";
      }
      if ($this->schema != 'public')
        $this->all_fields .= $this->schema . ".";
      if ($ns > 0 && $column['type'] != 'external' && ($column['type'] != 'auto' || !empty($column['extra']['default'])) && $column['type'] != 'order' && $column['type'] != 'serial')
        $this->fields_noserial .= ",";
      if ($column['type'] == 'serial' || $column['type'] == 'external' || ($column['type'] == 'auto' && empty($column['extra']['default'])) || $column['type'] == 'order')
        $ns--;
      else 
        $this->fields_noserial .= $column['name'];
      $this->fields .= $column['name'];
      $this->table_fields .= $this->name . '.' . $column['name'];
      if ($column['type'] == 'external')
        $this->all_fields .= $column['name'];
      else
        $this->all_fields .= $this->name . "." . $column['name'];
      if ($column['references'] && isset($global_dd[$column['references']]['descriptor'])) {
        if (!isset($references[$column['references']]))
          $references[$column['references']] = 0;
        if ($column['references'] == $this->name && !$references[$column['references']])
          $references[$column['references']]+=2;
        else
          $references[$column['references']]++;
        if ($references[$column['references']] == 1) {
          if (!empty($column['extra']['display'])) {
            $this->all_fields .= ",(" . $column['extra']['display'] . ") AS " . $column['references'];
            # FIXME: Is 'alias' useless?
            #if(empty($column['extra']['alias']))  $this->all_fields .= ",(" . $column['extra']['display'] . ") AS " . $column['references'];
            #else  $this->all_fields .= ",(" . $column['extra']['display'] . ") AS " . $column['extra']['alias'];
          } else {
            # FIXME? Y si existe ya un campo llamado como la tabla foranea en la tabla actual?
            $this->all_fields .= "," . $column['references'] . "." . $global_dd[$column['references']]['descriptor'] . " AS " . $column['references'];
            #$this->all_fields .= "," . $column['references'] . "." . $global_dd[$column['references']]['descriptor'];
          }
        } else {
          # FIXME: Second reference to same table does not enjoy display/alias (not yet)
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
    include('db.addrecord.php');
  }

  function preUpdateRecord($maxcols = 0, $nofiles = 0) {
    include('db.preupdate.php');
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
      if ($column['references'] !== 0 && $column['references'] !== false && !empty($column['references'])) {
        if (!isset($references[$column['references']])) $references[$column['references']] = 0;
        if ($column['references'] == $this->name && !$references[$column['references']]) {
          $references[$column['references']]+=2;
        } else
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
  function readDataSQL($sqlcmd, $cache = null) {
    /* checks cache options */
    if (is_null($cache))
      $cache = (ALM_CACHE && !ADMIN);
    $this->filecache = ROOTDIR.'/cache/'.md5($sqlcmd).".$this->name.".__FUNCTION__.'dat';
    if (!($cache === true && file_exists($this->filecache) && (time()-filemtime($this->filecache)<=ALM_CACHE_TIME)))
      $this->execSql($sqlcmd);
    return $this->getArray($cache); 
  }

  function fetchNext($current) {
    require('db.fetchnext.php');
    return $next;
  }

  function fetchPrev($current) {
    require('db.fetchprev.php');
    return $prev;
  }

  function readData($cache = null) {
    require('db.readdata.php');
    return $this->getArray($cache);
  }

  function readDataFilter($filter, $cache = null) {
    require('db.readdatafilter.php');
    return $this->getArray($cache);
  }

  function dumpData($format = 'php', $session = null) {
    require('db.dumpdata.php');
  }
  
  function escape($var) {
  	  return almdata::escape($var);
  }

}

/**
 * @package almidon
*/

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

