<?php
// vim: set expandtab tabstop=2 shiftwidth=2 fdm=marker:

/**
 * db.readenv.php
 *
 * Lee argumentos de Request o URI
 *
 * @copyright &copy; 2005-2009 Guegue Comunicaciones - guegue.com
 * @license http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @version $Id: db.readenv.php,v 2010092601 javier $
 * @package almidon
 */
    unset ($this->request);
    unset ($this->files);
    if ($friendly === true) {
      $params = explode("/", $_SERVER['PATH_INFO']);
      $i=1;
      foreach($this->definition as $column) {
        if (!isset($params[$i])) break;
        $_REQUEST[$column['name']] = $params[$i];
        $i++;
      }
    }
    if ($this->definition)
    foreach($this->definition as $column) {
      # Si es fecha o video, el valor del request no coincide con el nombre en tables.class
      if(preg_match('/^(date|datetime|datenull|time)$/', $column['type']) && !isset($_REQUEST[$column['name']])) {
        if($column['type']=='time') 
          $tmpcolumn = $column['name']."_Hour";
        else
          $tmpcolumn = $column['name']."_Year";
      } elseif($column['type']=='video') {
        $tmpcolumn = $column['name']."_type";
      } else {
        $tmpcolumn = $column['name'];
      }


        # Estable datos para tipo automatic (auto) 
        if ($column['type'] === 'automatic') {
          switch($column['extra']['automatic']) {
          case 'auth_user':
            # is this HTTP auth or ALM auth?
            if (!empty($_SERVER['PHP_AUTH_USER']) || !empty($_SERVER['PHP_AUTH_DIGEST']))
              $this->request[$column['name']] = $this->parsevar($this->http_auth_user(), 'string');
            else
              $this->request[$column['name']] = $_SESSION['idalm_user'];
            break;
          case 'ip':
            $this->request[$column['name']] = $_SERVER['REMOTE_ADDR'];
            break;
          case 'now':
            $this->request[$column['name']] = time();
            break;
          case 'srandom':
            $this->request[$column['name']] = md5(uniqid(rand()));
            break;
          case 'nrandom':
            $this->request[$column['name']] = rand();
            break;
          default:
          }
        }

      if (($column['type'] != 'external' || $column['type'] != 'auto') && (isset($_REQUEST[$tmpcolumn]) || isset($_FILES[$column['name']]))) {

        # Recepcion de una imagen
        if ($column['type'] == 'file' || $column['type'] == 'image') {
          if(!empty($_FILES[$column['name']]['name'])) {
            $this->request[$column['name']] = $this->parsevar($_FILES[$column['name']]['name'], $column['type']);
            $this->files[$column['name']] = $_FILES[$column['name']]['tmp_name'];
          } else {
            $this->request[$column['name']] = '';
	        }
          if (isset($_REQUEST['old_'.$column['name']]))
            $this->request['old_'.$column['name']] = $_REQUEST['old_'.$column['name']];
        # Recepcion de un password
        } elseif ($column['type'] == 'password') {
           $this->request[$column['name']] = md5($_REQUEST[$column['name']]);
        # Recepcion de una fecha
        } elseif (preg_match('/^(date|datetime|datenull|time)$/', $column['type'])) {
          $date = ''; $time = '';
          if (isset($_REQUEST[$column['name']])) {
            if (preg_match('/^(date|datetime|datenull)$/', $column['type']))
              $date = $this->parsevar($_REQUEST[$column['name']]);
            else
              $time = $this->parsevar($_REQUEST[$column['name']]);
          }
          if (isset($_REQUEST[$column['name'] . '_Year'])) {
            $year = $this->parsevar($_REQUEST[$column['name'] . '_Year'], 'int');
            $month = $this->parsevar($_REQUEST[$column['name'] . '_Month'], 'int');
            if ($month<10 && strlen($month)==1) $month = '0'.$month;
            $day = $this->parsevar($_REQUEST[$column['name'] . '_Day'], 'int');
            $date = $year . '-' . $month . '-' . $day;
          }
          if (isset($_REQUEST[$column['name'] . '_Hour'])) {
            if (isset($year))
              $this->request[$column['name']] = $year . '-' . $month . '-' . $day;
            $hour = $this->parsevar($_REQUEST[$column['name'] . '_Hour'], 'int');
            $minute = $this->parsevar($_REQUEST[$column['name'] . '_Minute'], 'int');
            if (isset($_REQUEST[$column['name'] . '_Second']))
              $second = $this->parsevar($_REQUEST[$column['name'] . '_Second'], 'int');
            else
              $second = '00';
            $time = $hour . ':' . $minute . ':' . $second;
          }
          $datetime = trim("$date $time");
          $this->request[$column['name']] = $datetime;

        # Recepcion de texto html
        } elseif ($column['type'] == 'html' || ($column['type'] == 'xhtml')) {
          $this->request[$column['name']] = $this->parsevar($_REQUEST[$column['name']], 'string', true);

        # Recepcion de enteros
        } elseif ($column['type'] == 'int') {
          $this->request[$column['name']] = $this->parsevar($_REQUEST[$column['name']], $column['type']);
        } elseif($column['type'] == 'video') {
          $strXml = '<?xml version="1.0" encoding="UTF-8"?><video><tipo>'.$_REQUEST[$column['name'].'_type'].'</tipo><src>'.htmlentities($_REQUEST[$column['name'].'_src']).'</src></video>';
          $this->request[$column['name']] = $this->parsevar($strXml, 'string', true);
        } elseif(isset($column['extra']['allow_js']) && $column['extra']['allow_js']!==false) {
          $this->request[$column['name']] = $this->parsevar($_REQUEST[$column['name']], $column['type'], false, $column['extra']['allow_js']);
        } else {
          $this->request[$column['name']] = $this->parsevar($_REQUEST[$column['name']], $column['type']);
        }
      }
    }
    if (isset($this->key1) && isset($this->key2) && isset($_REQUEST['old_' . $this->key1]) && isset($_REQUEST['old_' . $this->key2])) {
      $this->request['old_' . $this->key1] = $_REQUEST['old_' . $this->key1];
      $this->request['old_' . $this->key2] = $_REQUEST['old_' . $this->key2];
    } elseif (isset($_REQUEST['old_' . $this->key])) {
      $this->request['old_' . $this->key] = $_REQUEST['old_' . $this->key];
    }
    $this->escaped = true;
