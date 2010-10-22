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
  #static var $database;
  var $database;
  var $data;
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

  /**
   * construye Data, crea conexion a BD si aun no se hace
   */
  function Data () {
    require('db.data.php');
  }

  /**
   * Revisa si comando sql ha dado error, guarda mensajes en bitacora y pantalla (private)
   * @param string $sqlcmd comando sql a revisar
   * @param string $extra texto adicional a reportar
   * @param bool $die muere después de revisar?
   */
  function check_error($sqlcmd, $extra = '', $die = false) {
    require('db.error.php');
  }
 
  /**
   * crea linea en bitacora de comando sql (private)
   * @param string $logtext texto a agregar en la bitacora sql
   */
  function sql_log($logtext) {
    require('db.logging.php');
  } 

  /**
   *  hace la consulta utilizando almdata (private, generalmente)
   *  @param string $sqlcmd comando SQL
   *  @return resource recuerso tipo query
   */
  function query($sqlcmd) {
    require('db.query.php');
    return $result;
  }
  
  /**
   * wrapper para el ejecutor de queries en base de datos
   * @param string $sqlcmd comando sql a ejectuar
   * @param bool $cache usar cache?
   * @param string $table tabla a usar (usada por limpiador de cache luego)
   */
  function execSql($sqlcmd, $cache = null, $table = null) {
    $this->data = $this->query($sqlcmd);
    if (!almdata::isError($sqlcmd) && $this->data && (preg_match('/^SELECT/',$sqlcmd) || preg_match('/^SHOW/',$sqlcmd))) {
      $this->num = almdata::rows($this->data);
    }
  }

  /**
   * obtiene el nombre de usuario autenticado via http
   * usa PHP_AUTH_DIGEST o PHP_AUTH_USER
   * @return string con el username
   */
  function http_auth_user() {
    require('db.http_auth_user.php');
    return $auth_user;
  }

  /**
   * construye Data, crea conexion a BD si aun no se hace
   * FIXME: Mejor usar readDataSQL, funcion repetida. Alguien aun la usa?
   */
  function readList($sqlcmd) {
    $this->execSql($sqlcmd);
    return $this->getArray();
  }

  /**
   * obtiene un valor específico de una tabla
   * @param string $sqlcmd comando SQL para obtener valor: SELECT foo FROM bar WHERE pk=999;
   * @return mixed valor obtenido del tipo que sea
   */
  function getVar($sqlcmd) {
    $this->execSql($sqlcmd);
    if ($this->data)
      $row = almdata::fetchRow($this->data, false);
    if (isset($row[0]))
      return $row[0];
  }

  /**
   * Lee un statement sql y devuelve una lista de una sola columna (la primera)
   * @param string $sqlcmd comando SQL para obtener lista: SELECT foo FROM bar;
   * @return array arreglo con lista de valores
   */
  function getList($sqlcmd) {
    require('db.getlist.php');
    if (isset($array_rows))
      return $array_rows;
  }

  /**
   * obtiene registros del recurso actual de datos (private)
   * usado generalmente por queries que devuelven varios registros: readData, etc.
   * @param bool $cache usar cache?
   * @return array arreglo con arreglos de registros
   */
  function getArray($cache = null) {
    require('db.getarray.php');
    return (isset($array_rows) ? $array_rows : null);
  }

  # FIXME: selectMenu abaraca esta opcion. deprecated!
  #function selectList($sqlcmd) {
  #  require('db.selectlist.php');
  #  return $menu;
  #}

  /**
   * obtiene arreglo de una tabla para ser usado en combo box
   * @param string $sqlcmd comando SQL para obtener par key=>val, puede ser tambien nombre de la tabla
   * @param string $filter filtro para el query, reduce el menu a foo criterio
   * @return array arreglo con pares asociados key=>val
   */
  function selectMenu($sqlcmd = null, $filter = null) {
    require('db.selectmenu.php');
    return $menu;
  }
  
  /**
   * Pagina los datos del query actual segun $num y $max (private?)
   * @return array lista de páginas a enumerar
   */
  function getNumbering() {
    unset($this->pg);
    $numpg = ceil($this->num / $this->max);
    for ($n = 1; $n <= $numpg; $n++)
      $this->pg[] = $n;
    return $this->pg;
  }

  /**
   * se desconecta de la base de datos
   */
  function destroy() {
    almdata::disconnect();
  }

  #function tablesColumnExists($column_name) {
  #  $exists = preg_match("/(,|^)$column_name(,|$)/",$this->fields);
  #  return $exists;
  #}

  #function catalogColumnExists($column_name) {
  #  require('db.catalogcolumnexists.php');
  #  return $exists;
  #}

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
  var $keys;
  var $order;
  var $join;
  var $all_fields;
  var $escaped;
  var $id;
  var $action;

  function Table($name) {
    $this->Data();
    $this->name = $name;
  }

  /**
   * borra todo cache generado para esta tabla
   * OJO: puede ser pesado para tablas con muchos datos
  */
  function clearCache() {
    $cachefiles = ROOTDIR.'/cache/*.'.$this->name.'.*dat';
    foreach (glob($cachefiles) as $filename) {
     unlink($filename);
    }
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
    $remove_files = null;
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

  /**
  * Refresca colección de campos al agregar una nueva columna (campo) con addColumn
  */
  function refreshFields() {
    require('db.refreshfields.php');
  }

  /**
  * Agregar una nueva columna (campo)
   * @param string $name nombre del campo
   * @param string $type tipo de dato: integer, varchar, image, etc
   * @param integer $size tamaño del campo
   * @param string $references tabla foranea (si es FK)
   * @param string $label etiqueta descriptiva
   * @param array $extra propiedades adicionales del campo
  */
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
  
  function updateRecord($id = null, $maxcols = 0, $nofiles = 0) {
    require('db.updaterecord.php');
  }

  function deleteRecord($id = null) {
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
        $foreign_key = $global_dd[$column['references']]['keys'][0];
        if ($references[$column['references']] == 1) {
          $join .= " LEFT OUTER JOIN " . $column['references'] . " ON " . $this->name . "." . $column['name'] . "=" . $column['references'] . "." . $foreign_key;
        } else {
          $tmptable = $column['references'] . $references[$column['references']];
          $join .= " LEFT OUTER JOIN " . $column['references'] . " AS $tmptable ON " . $this->name . "." . $column['name'] . "=" . $tmptable . "." . $forenign_key;
        }
      }
    return $join;
  }

  /**
  * Lee un registro de la tabla
  * @param mixed $id indica el id del registro, si no se especifica, agarra el último
  * @param bool $cache indica si utilizar cache o no
  * @return array con el registro (devuelto por *_fetch_row)
  */
  function readRecord($id = null, $cache = null) {
    require('db.readrecord.php');
    if (isset($row))
      return $row;
  }

  /**
  * Lee un registro de la tabla usando un comando SQL
  * @param string $sqlcmd comando SQL a usar
  * @return array con el registro (devuelto por *_fetch_row)
  */
  function readRecordSQL($sqlcmd) {
    $this->execSql($sqlcmd);
    $row = almdata::fetchRow($this->data);
    $this->current_record = $row;
    return $row;
  }

  /**
  * Lee un conjunto de registros usando comando sql
  * @param bool $cache indica si utilizar cache o no
  * @return array con array de registros
  */
  function readDataSQL($sqlcmd, $cache = null) {
    /* checks cache options */
    if (is_null($cache))
      $cache = (ALM_CACHE && !ADMIN);
    $this->filecache = ROOTDIR.'/cache/'.md5($sqlcmd).".$this->name.".__FUNCTION__.'.dat';
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

  /**
  * Lee un conjunto de registros de la tabla actual
  * @param bool $cache indica si utilizar cache o no
  * @return array con array de registros
  */
  function readData($cache = null) {
    require('db.readdata.php');
    return $this->getArray($cache);
  }

  /**
  * Lee un conjunto de registros de la tabla actual limitados por un filtro (WHERE)
  * @param string $filter filtro WHERE para query
  * @param bool $cache indica si utilizar cache o no
  * @return array con array de registros
  */
  function readDataFilter($filter, $cache = null) {
    require('db.readdatafilter.php');
    return $this->getArray($cache);
  }

  /**
  * Exporta (o hace un dump) de datos de la tabla actual
  * @param string $format formato a usar para exportar
  * @param string $session
  */
  function dumpData($format = 'php', $session = null) {
    require('db.dumpdata.php');
  }
  
  /**
  * "escapea" una cadena para poder usarla de manera segura en comando sql
  * @param string $var cadena a "escapear"
  * @return string escaped string, lista para usar en sql
  */
  function escape($var) {
  	  return almdata::escape($var);
  }
}
