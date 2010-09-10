<?php
/**
 * Wrapper para DAL de almidon de: pgsql, mysql y sqlite3
 * @package almidon
 */

/**
 * clase principal DAL, permite traducir los comandos a cada distinto servidor de base de datos
 */

class AlmData {

  /**
   * Obtiene info de conexion del DSN: dbtype, dbname, host, username, pass
   */
  function parseDSN($dsn) {
    list($dbtype,$tmp) = preg_split('/:\/\//',$dsn);
    list($auth,$dbname) = preg_split('/\//',$tmp);
    list($auth,$host) = preg_split('/@/',$auth);
    list($username,$pass) = preg_split('/:/',$auth);
    return array($dbtype,$dbname,$host,$username,$pass);
  }

  /**
   * Imprime un error en rojo, if DEBUG. error_log + trigger_error
   */
  function printError($error_msg, $raise = true, $die = false) {
    error_log($error_msg);
    if (DEBUG === true) {
      print '<table bgcolor="red"><tr><td>';
      trigger_error(htmlentities($error_msg) . "<br/>\n");
      print '</td></tr></table>';
    }
    if ($die) die;
  }

  /**
   * Define las distintas funciones SQL segun cada db server: postgresql, sqlite, mysql
   */
  function setFunctions($dbtype) {
    $this->dbtype = $dbtype;
    switch($dbtype) {
    case 'pgsql':
      $this->db_connect = 'pg_connect';
      $this->db_query = 'pg_query';
      $this->db_exec = 'pg_execute';
      $this->db_arows = 'pg_affected_rows';
      $this->db_rows = 'pg_num_rows';
      $this->db_fetch_row = 'pg_fetch_assoc';
      $this->db_fetch_array = 'pg_fetch_array';
      $this->db_disconnect = 'pg_close';
      $this->db_escape = 'pg_escape_string';
      $this->db_error = 'pg_last_error';
      break;
    case 'sqlite':
      $this->db_connect = 'sqlite_connect';
      $this->db_query = 'sqlite_query';
      $this->db_exec = 'sqlite_exec';
      $this->db_arows = null;
      $this->db_rows = 'sqlite_num_rows';
      $this->db_fetch_row = 'sqlite_fetch_array';
      $this->db_disconnect = null;
      $this->db_escape = 'sqlite_escape_string';
      $this->db_error = 'sqlite_last_error';
      break;
    case 'mysql':
      $this->db_connect = 'mysql_connect';
      $this->db_query = 'mysql_query';
      $this->db_exec = 'mysql_exec';
      $this->db_rows = 'mysql_affected_rows';
      $this->db_rows = 'mysql_num_rows';
      $this->db_fetch_row = 'mysql_fetch_assoc';
      $this->db_fetch_array = 'mysql_fetch_array';
      $this->db_disconnect = 'mysql_close';
      $this->db_escape = 'mysql_escape_string';
      $this->db_error = 'mysql_error';
      break;
    }
  }

  /**
   * Conexion a la base de datos
   * @return resource de conexion
   */
  function connect($dsn, $options = false) {

    global $alm_connect;

    list($dbtype,$dbname,$host,$username,$pass) = almdata::parseDSN($dsn);

    if (!isset($alm_connect[$dsn])) {
      $db = null;
      switch($dbtype) {
      case 'pgsql':
        $host = empty($host) ? '' : "host=$host";
        $db = pg_connect("$host dbname=$dbname user=$username password=$pass");
        break;
      case 'sqlite':
        break;
      case 'mysql':
        $db = mysql_connect($host, $username, $pass);
        if ($db) {
          $db_selected = mysql_select_db($dbname, $db);
        }
        break;
      default:
        almdata::printError("DBTYPE $dbtype not supported", true, true);
        break;
      }
      $alm_connect[$dsn] = $db;
    } else {
      $db = $alm_connect[$dsn];
    }
    if (!isset($this))
      return;
    else
      almdata::setFunctions($dbtype);
    return $db;
  }

  /**
   * se conecta al CDN (cloudfiles) en base a las constantes definidas en config.php
   * @return CND repo
  */
  function cdn_connect() {
    $auth = new CF_Authentication(CDN_USERNAME, CDN_APIKEY);
    $auth->authenticate();
    $conn = new CF_Connection($auth);
    $cloudfiles = $conn->get_container(CDN_REPO);
    return($cloudfiles);
  }
  /**
   * hace upload de un archivo al CDN (cloudfiles)
   * @return CND repo
  */
  function cdn_upload($cloudfiles, $filename, $tmp_file) {
    $afile = $cloudfiles->create_object($filename);
    $afile->content_type = mime_content_type($tmp_file);
    $afile->load_from_filename($tmp_file);
  }

  /**
   * obtiene el ultimo error del db server, distintos comandos para cada db server
   * @return cadena conteniendo el ultimo error (last_error)
   */
  function basicError($data = null, $dsn) {
    list($dbtype,$dbname,$host,$username,$pass) = almdata::parseDSN($dsn);
    switch($dbtype) {
    case 'pgsql':
      $error = @pg_last_error();
      break;
    case 'sqlite':
      $error = @sqlite_last_error();
      break;
    case 'mysql':
      $error = @mysql_error();
      break;
    default:
      $error = null;
    }
    return $error;
  }

  # FIXME: Como se llama isError? Como podemos reportar quien lo llamo,
  #        donde estaba el SQL que dio el error? Como usar $calling?
  /**
   * Reporta si ha habido un error en la db
   * @return true si ya esta registrado el error, o lo registra y devuelve true si last_error
   */
  function isError($sqlcmd = null, $calling = null) {
    if (is_string($sqlcmd)) {
      $hash = md5($sqlcmd);
      if (isset($this->errors[$hash]))
        return true;
    } else {
      $hash = null;
    }
    $db_error = $this->db_error;
    $error = @$db_error();
    if (!isset($error) || empty($error)) {
      return false;
    } else {
      $this->errors[$hash] = $error;
      if ($calling) $this->errors[$hash] .= ' -- ' . $calling;
      return true;
    }
  }

  /**
   * Se desconecta de la db
   * @return true si exito, false si falla
   */
  function disconnect() {
    $db_disconnect = $this->db_disconnect;
    return @$db_disconnect();
  }

  /**
   * Obtiene el numero de registros afectados por el ultimo comando sql
   * @return numero de registros afectados
   */
  function rows($data = null) {
    $db_rows = $this->db_rows;
    if ($data == null)
      return $db_rows($this->data);
    else
      return $db_rows($data);
  }

  /**
   * escape string by native db's function
   * @return escaped string
   */
  function escape($var) {
    $db_escape = $this->db_escape;
    return $db_escape($var);
  }


  /**
   * enviar consulta al db
   * @return recurso en caso de exito, false si falla
   */
  function query($sqlcmd) {
    $db_query = $this->db_query;
    return @$db_query($sqlcmd);
  }

  /**
   * obtiene un registro segun una consulta
   * @return un registro
   */
  function fetchRow($data = null, $assoc = true) {
    if ($assoc)
      $db_fetch_row = $this->db_fetch_row;
    else
      $db_fetch_row = $this->db_fetch_array;
    if ($data == null) 
      return @$db_fetch_row($this->data);
    else
      return $db_fetch_row($data);
  }
}
