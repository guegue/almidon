<?php
// vim:set expandtab tabstop=2 shiftwidth=2 fdm=marker:

/**
 * db.dal.php
 *
 * Wrapper para DAL de almidon de: pgsql, mysql y sqlite3
 *
 * @copyright &copy; 2005-2009 Guegue Comunicaciones - guegue.com
 * @license http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @version $Id: db.dal.php,v 2009112101 javier $
 * @package almidon
 */


class AlmData {

  # Parses DSN
  function parseDSN($dsn) {
    list($dbtype,$tmp) = preg_split('/:\/\//',$dsn);
    list($auth,$dbname) = preg_split('/\//',$tmp);
    list($auth,$host) = preg_split('/@/',$auth);
    list($username,$pass) = preg_split('/:/',$auth);
    return array($dbtype,$dbname,$host,$username,$pass);
  }

  function printError($error_msg, $raise = true, $die = false) {
    error_log($error_msg);
    if (DEBUG === true) {
      print '<table bgcolor="red"><tr><td>';
      trigger_error(htmlentities($error_msg) . "<br/>\n");
      print '</td></tr></table>';
    }
    if ($die) die;
  }

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

# wrappers
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
  function disconnect() {
    $db_disconnect = $this->db_disconnect;
    return @$db_disconnect();
  }
  function rows($data = null) {
    $db_rows = $this->db_rows;
    if ($data == null)
      return $db_rows($this->data);
    else
      return $db_rows($data);
  }
  function escape($var) {
    $db_escape = $this->db_escape;
    return $db_escape($var);
  }
  function query($sqlcmd) {
    $db_query = $this->db_query;
 
    return @$db_query($cnx, $sqlcmd);
  }
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
