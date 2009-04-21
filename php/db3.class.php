<?php
// vim: set expandtab tabstop=2 shiftwidth=2 fdm=marker:

# Comentado Jue 5 Marzo 2009
#foreach ($_POST as $j =>$value) {
# if (stristr($value,"Content-Type")) {
#   header("HTTP/1.0 403 Forbidden");
#   echo "No spam allowed.";
#   exit;
# }
#}

// Constantes
if (DEBUG === true) ini_set('display_errors', true);

# Where is Almidon?
if (!defined('ALMIDONDIR')) {
  $almidondir = dirname(__FILE__);
  $almidondir = substr($almidondir, 0, strrpos($almidondir,'/'));
  define ('ALMIDONDIR', $almidondir);
}

# Directorio de instalación de almidon
if (defined('ALMIDONDIR'))
  set_include_path(get_include_path() . PATH_SEPARATOR . ALMIDONDIR . '/php/pear:'.ALMIDONDIR.'/php');
# Permisos por defecto para los directorios que se creen en files
define('PERMIS_DIR',0775);
# Etiquetas permitidas
if(!defined('ALM_ALLOW_TAGS')) define('ALM_ALLOW_TAGS', '<br/><br><p><h1><h2><h3><b><i><div><span><img1><img2><img3><strong><li><ul><ol><table><tbody><tr><td><font><a><sup><object><param><embed><hr><hr /><hr/>');

require('DB.php');
require('image.class.php');

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

  // {{{ inicializacion y conexion
  function Data () {
    global $DSN;
    if ($DSN)
      $this->database = DB::connect ($DSN);
    else
      $this->database = DB::connect (DSN);
    $this->check_error($this->database,'',true);
    $this->num = 0;
    $this->cols = 0;
    $this->max = MAXROWS;
    $this->current_pg = isset($_REQUEST['pg']) ? (int)$_REQUEST['pg'] : '1';
  }
  // }}} inicializacion y conexion

  // {{{ errores y logs
  function check_error($obj, $extra = '', $die = false) {
    if (PEAR::isError($obj)) {
      $error_msg = $obj->getMessage();
      #if ($extra) $error_msg .= " -- " . $extra . " -- " . $_SERVER['SCRIPT_NAME'];
      $error_msg .= " -- " . $extra . " -- " . $_SERVER['SCRIPT_NAME'];
      if (DEBUG === true) trigger_error(htmlentities($error_msg));
      error_log(date("[D M d H:i:s Y]") . " Error: " . $error_msg . "\n");
      if ($die) die();
    } elseif (DEBUG === true && $extra)
      $this->sql_log($extra);
  }

  function sql_log($logtext) {
    $loghandle = fopen(SQLLOG, 'a');
    fwrite($loghandle, date("[D M d H:i:s Y]") . " " . $logtext . "\n");
    fclose($loghandle);
  }
  // }}} errores y logs

  // {{{ query
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
    if ($this->data && (strpos($sqlcmd,'SELECT') !== false))
      $this->num = $this->data->numRows();
  }
  // }}} query

  // {{{ funciones lectura de datos
  //Mejor usar readDataSQL, funcion repetida
  function readList($sqlcmd) {
    $this->execSql($sqlcmd);
    return $this->getArray();
  }

  function getVar($sqlcmd) {
    $this->execSql($sqlcmd);
    $row = $this->data->fetchRow(DB_FETCHMODE_ORDERED);
    return $row[0];
  }

  //Lee un statement sql y devuelve una lista de una sola columna (la primera)
  function getList($sqlcmd) {
    $this->execSql($sqlcmd);
    for ($i = 0; $i < $this->num; $i++) {
      $row = $this->data->fetchRow(DB_FETCHMODE_ORDERED);
      $array_rows[] = $row[0];
    }
    return $array_rows;
  }

  function getArray() {
    for ($i = 0; $i < $this->num; $i++) {
      $row = $this->data->fetchRow(DB_FETCHMODE_ASSOC);
      if ($row[$this->key] == $this->current_id)
        $this->current_record = $row;
      if ($this->html)
        foreach ($row as $key => $val)
          $row[$key] = htmlentities($val, ENT_COMPAT, 'UTF-8');
      $array_rows[] = $row;
    }
    if (isset($array_rows))
      return $array_rows;
    else return null;
  }

  function selectList($sqlcmd) {
    $result = $this->query($sqlcmd);
    $num = $result->numRows();
    $menu = array();
    for ($i=0; $i < $num; $i++) {
      $r = $result->fetchRow(DB_FETCHMODE_ORDERED);
      $new = array($r[0] => $r[1]);
      $menu = $menu + $new;
    }
    return $menu;
  }

  function selectMenu($sqlcmd = '', $filter = '') {
    if (!$sqlcmd)
      if(isset($this->dd[$this->name]))
        $sqlcmd = "SELECT $this->key, $this->name FROM $this->name _WHERE_ ORDER BY $this->name.$this->name";
      else
        $sqlcmd = "SELECT $this->key, $this->key AS $this->name FROM $this->name _WHERE_ ORDER BY $this->name";
    if (!preg_match("/SELECT/", $sqlcmd))
      $sqlcmd = "SELECT id$sqlcmd, $sqlcmd FROM $sqlcmd _WHERE_ ORDER BY $sqlcmd";
    if($filter)
      $sqlcmd = preg_replace('/_WHERE_/ ',"WHERE $filter",$sqlcmd);
    else
      $sqlcmd = preg_replace('/_WHERE_/ ','',$sqlcmd);
    $result = $this->query($sqlcmd);
    $num = $result->numRows();
    $menu = array();
    for ($i=0; $i < $num; $i++) {
      $r = $result->fetchRow(DB_FETCHMODE_ORDERED);
      $new = array($r[0] => $r[1]);
      $menu = $menu + $new;
    }
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
  var $detail;
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

  function http_digest_parse($digest = null) {
    if(!isset($digest)) $digest = $_SERVER['PHP_AUTH_DIGEST'];
    # edit needed parts, as you  want
    preg_match_all('@(username|nonce|uri|nc|cnonce|qop|response)'.'=[\'"]?([^\'",]+)@', $digest, $t);
    $data = array_combine($t[1], $t[2]);
    # all parts found?
    return (count($data)==7) ? $data : false;
  }

  function http_auth_user() {
    if(!empty($_SERVER['PHP_AUTH_DIGEST'])) {
      $_data = $this->http_digest_parse();
      return $_data['username'];
    } else {
      return $_SERVER['PHP_AUTH_USER'];
    }
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
      if ($column['type'] == 'serial' || $column['type'] == 'external' || ($column['type'] == 'auto' && empty($column['extra']['default'])) || $column['type'] == 'order' && $column['type']=='serial')
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
	  - arr_values
	  - label_bool
	  - filteropt
	  For FK (Foreign Key)
      - display
      - alias
  */
  function addColumn($name, $type, $size = 100, $pk = 0, $references = 0, $label = '', $extra = '') {
  #print $type;
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
      $tmpvar = $this->database->escapeSimple($tmpvar);
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
      $tmpvar = preg_replace("/javascript/i", "", $tmpvar); # Es necesario?
    }
    if ($type == 'string' && !$html) {
      $tmpvar = strip_tags($tmpvar, ALM_ALLOW_TAGS);
      //$tmpvar = strip_tags($tmpvar, "<br><p><h1><h2><h3><b><i><div><span><img1><img2><img3><strong><li><ul><ol><table><tbody><tr><td><font><a><sup>");
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

  function readEnv() {
    include('db.readenv.php');
  }

  function addRecord() {
    $n = 0;
    $values ="";
    foreach($this->definition as $column) {
      //if ($n > 0 && $column['type'] != 'external' && $column['type'] != 'auto' && $column['type'] != 'order')
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
          	/*
          	"seconds"	Representaci—n numŽrica de segundos	0 a 59
			      "minutes"	Representaci—n numŽrica de minutos	0 a 59
			      "hours"	Representaci—n numŽrica de horas	0 a 23
			      "mday"	Representaci—n numŽrica del d’a del mes	1 a 31
			      "wday"	Representaci—n numŽrica del d’a de la semana	0 (para el Domingo) a 6 (para el S‡bado)
			      "mon"	Representaci—n numŽrica de un mes	1 a 12
			      "year"	Una representaci—n numŽrica completa de un a–o, 4 d’gitos	Ejemplos: 1999 o 2003
			      "yday"	Representaci—n numŽrica del d’a del a–o	0 a 365
			      "weekday"	Una representaci—n textual completa del d’a de la semana	Sunday a Saturday
			      "month"	Una representaci—n textual completa de un mes, como January o March	January a December
			      0	Segundos desde el Epoch Unix, similar a los valores devueltos por time() y usados por date(). 	Depende del sistema, t’picamente -2147483648 a 2147483647 (equivalente al mktime).
          	*/
            $filename =  $timemark[0] . "_" . $this->request[$column['name']];
	          if (!is_dir(ROOTDIR . "/files/" . $this->name))  mkdir(ROOTDIR . "/files/" . $this->name, PERMIS_DIR);
            move_uploaded_file($this->files[$column['name']], ROOTDIR . "/files/" . $this->name . "/" . $filename);
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
	            if(!is_dir(PIXDIR."/".$timemark['year']))  mkdir(PIXDIR."/".$timemark['year'], PERMIS_DIR);
	            if(!is_dir(PIXDIR."/".$timemark['year']."/".$timemark['mon']))  mkdir(PIXDIR."/".$timemark['year']."/".$timemark['mon'], PERMIS_DIR);
              if($sizes)
                for($idx=0;$idx<count($sizes);$idx++) {
                  $pic = null;
	                list($w, $h, $crop) = split("x", trim($sizes[$idx]));
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
          }
          $value = $this->database->escapeSimple($this->request[$column['name']]);
          $values .= "'" . $value . "'";
          break;
        case 'file':
          if ($this->files[$column['name']]) {
            $filename =  mktime() . "_" . $this->request[$column['name']];
	    if(!is_dir(ROOTDIR . "/files/" . $this->name))  mkdir(ROOTDIR . "/files/" . $this->name, PERMIS_DIR);
            move_uploaded_file($this->files[$column['name']], ROOTDIR . "/files/" . $this->name . "/" . $filename);
            $this->request[$column['name']] = $filename;
          }
        case 'char':
          if ($this->request[$column['name']] == -1) {
            $this->request[$column['name']] = 'NULL';
            $values .= $this->request[$column['name']];
          } else {
            $value = $this->database->escapeSimple($this->request[$column['name']]);
            $values .= "'" . $value . "'";
          }
          break;
        case 'auth_user':
        case 'varchar':
          if ($this->request[$column['name']] == -1) {
            $this->request[$column['name']] = 'NULL';
            $values .= $this->request[$column['name']];
          } else {
            $value = ($this->escaped) ? $this->request[$column['name']] : $this->database->escapeSimple($this->request[$column['name']]);
            $values .= "'" . $value . "'";
          }
          break;
        case 'text':
          $value = ($this->escaped) ? $this->request[$column['name']] : $this->database->escapeSimple($this->request[$column['name']]);
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
            $value = $this->database->escapeSimple($this->request[$column['name']]);
            $values .= "'" . $value . "'";
          } else {
            $values .= 'NULL';
            #$values .= 'CURRENT_DATE';
          }
          break;
        default:
          $value = ($this->escaped) ? $this->request[$column['name']] : $this->database->escapeSimple($this->request[$column['name']]);
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
          if ($this->request[$column['name']] === -1 || $this->request[$column['name']] === '')
            $this->request[$column['name']] = 'NULL';
        case 'smallint':
        case 'numeric':
          $values .= $column['name'] . "=" . $this->request[$column['name']];
          break;
        case 'image':
          if ($nofiles || ($_REQUEST[$column['name'] . '_keep']&&!$this->files[$column['name']]) || !$this->files[$column['name']]) {
            if (!$_REQUEST[$column['name'] . '_keep'] && !$this->files[$column['name']])
              $values .= $column['name'] . "=''";
            else
              $values .= $column['name'] . "=" . $column['name'];
            if(($this->request['old_'.$column['name']] != $this->files[$column['name']]) && $this->request['old_'.$column['name']] && !$_REQUEST[$column['name'] . '_keep']) {
	            if(file_exists(ROOTDIR . "/files/" . $this->name . "/" . $this->request['old_'.$column['name']])) unlink(ROOTDIR . "/files/" . $this->name . "/" . $this->request['old_'.$column['name']]);
  	          if ($column['extra']['sizes'] && defined('PIXDIR'))  $sizes = explode(',',trim($column['extra']['sizes']));
     	        if (isset($sizes)) {
       			  // esta linea da un warning: Warning: Wrong parameter count for strpos() in /www/cms/php/db3.class.php on line 550 Warning: Wrong parameter count for substr() in /www/cms/php/db3.class.php on line 550
	          	  $timemark = getdate(substr($this->request['old_'.$column['name']],0,strpos($this->request['old_'.$column['name']]),"_"));
                if ($timemark['mon']<10 && strlen($timemark['mon'])==1)  $timemark['mon'] = "0" . $timemark['mon'];
                if($sizes)
	                foreach($sizes as $size) {
	                  list($w, $h, $crop) = split("x", trim($size));
	                  if(file_exists(PIXDIR . "/" .$timemark['year']."/".$timemark['mon']."/".$w.($h?"x$h":""). "_" . $this->request['old_'.$column['name']])) unlink(PIXDIR . "/" .$timemark['year']."/".$timemark['mon']."/".$w.($h?"x$h":""). "_" . $this->request['old_'.$column['name']]);
	                }
  	          }
            }
          } elseif ($this->files[$column['name']]) {
          	$timemark = getdate();
            $filename =  $timemark[0] . "_" . $this->request[$column['name']];
	          if(!is_dir(ROOTDIR . "/files/" . $this->name))  mkdir(ROOTDIR . "/files/" . $this->name, PERMIS_DIR);
            move_uploaded_file($this->files[$column['name']], ROOTDIR . "/files/" . $this->name . "/" . $filename);
            $value = $this->database->escapeSimple($filename);
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
              if(!is_dir(PIXDIR."/".$timemark['year']))  mkdir(PIXDIR."/".$timemark['year'], PERMIS_DIR);
              if(!is_dir(PIXDIR."/".$timemark['year']."/".$timemark['mon']))  mkdir(PIXDIR."/".$timemark['year']."/".$timemark['mon'], PERMIS_DIR);
              if($sizes)
                for($idx=0;$idx<count($sizes);$idx++) {
                  $pic = null;
                  list($w, $h, $crop) = split("x", trim($sizes[$idx]));
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
          #if ($nofiles) break;
          if ($nofiles || $_REQUEST[$column['name'] . '_keep'] || !$this->files[$column['name']]) {
            if (!$_REQUEST[$column['name'] . '_keep'] && !$this->files[$column['name']])
              $values .= $column['name'] . "=''";
            else
              $values .= $column['name'] . "=" . $column['name'];
          } elseif ($this->files[$column['name']]) {
            $filename =  mktime() . "_" . $this->request[$column['name']];
	        if(!is_dir(ROOTDIR . "/files/" . $this->name))  mkdir(ROOTDIR . "/files/" . $this->name, PERMIS_DIR);
            move_uploaded_file($this->files[$column['name']], ROOTDIR . "/files/" . $this->name . "/" . $filename);
            $value = $this->database->escapeSimple($filename);
            $values .= $column['name'] . "=" ."'" . $value . "'";
          }
          break;
        case 'char':
          if ($this->request[$column['name']] == -1) {
            $this->request[$column['name']] = 'NULL';
            $values .= $column['name'] . "=" . $this->request[$column['name']];
          } else {
            $value = $this->database->escapeSimple($this->request[$column['name']]);
            $values .= $column['name'] . "=" ."'" . $value . "'";
          }
          break;
        case 'varchar':
          if ($this->request[$column['name']] == -1) {
            $this->request[$column['name']] = 'NULL';
            $values .= $column['name'] . "=" . $this->request[$column['name']];
          } else {
            $value = ($this->escaped) ? $this->request[$column['name']] : $this->database->escapeSimple($this->request[$column['name']]);
            $values .= $column['name'] . "=" ."'" . $value . "'";
          }
          break;
        case 'text':
          $value = ($this->escaped) ? $this->request[$column['name']] : $this->database->escapeSimple($this->request[$column['name']]);
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
            $value = $this->database->escapeSimple($this->request[$column['name']]);
            $values .= $column['name'] . "= '" . $value . "'";
          } else {
            $values .= $column['name'] . "= NULL";
            #$values .= 'CURRENT_DATE';
          }
          break;
        default:
          $value = ($this->escaped) ? $this->request[$column['name']] : $this->database->escapeSimple($this->request[$column['name']]);
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
    if (!$id) $id = $this->request[$this->key];
    # Nos devuelve el ultimo registro de la tabla, si es qe no se proporciona un id
    #if (!$id) $id = $this->getVar("SELECT currval('$this->name"  . "_" . $this->key . "_seq')");
    if (!$id) $id = $this->getVar("SELECT MAX(" . $this->key . ") FROM " . $this->name);
    if ($this->join) {
      $sqlcmd = "SELECT $this->all_fields FROM $this->name " . $this->getJoin() . " WHERE $this->name.$this->key = '$id'";
    } else
      $sqlcmd = "SELECT $this->fields FROM $this->name WHERE $this->name.$this->key = '$id'";
    $this->execSql($sqlcmd);
    $row = $this->data->fetchRow(DB_FETCHMODE_ASSOC);
    if ($this->html)
      foreach($row as $key=>$val)
        $row[$key] = htmlentities($val);
    $this->current_record = $row;
    return $row;
  }

  function readRecordSQL($sqlcmd) {
    $this->execSql($sqlcmd);
    $row = $this->data->fetchRow(DB_FETCHMODE_ASSOC);
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
    if ($this->filter)
      $sqlcmd .= " WHERE $this->filter";
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
    if ($this->filter || $filter)
      $sqlcmd .= " WHERE ".(($this->filter)?$this->filter." AND ":"")."$filter";
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
    $row = $this->data->fetchRow(DB_FETCHMODE_ASSOC);
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
          //echo $datetime;
          $this->request[$column['name']] = $datetime;
        } elseif ($column['type'] == 'auth_user') {
          $this->request[$column['name']] = $this->parsevar($this->http_auth_user(), 'string');
        } else {
          echo 'Entro';
          $this->request[$column['name']] = $this->parsevar($_REQUEST[$column['name']], $column['type']);
        }
      }
    }
    $this->request['old_' . $this->key1] = $_REQUEST['old_' . $this->key1];
    $this->request['old_' . $this->key2] = $_REQUEST['old_' . $this->key2];
  }
}

?>
