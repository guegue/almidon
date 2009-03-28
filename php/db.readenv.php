<?php
// vim: set expandtab tabstop=2 shiftwidth=2 fdm=marker:

/**
 * db.readenv.php
 *
 * Lee argumentos de Request o URI
 *
 * @copyright &copy; 2005-2009 Guegue Comunicaciones - guegue.com
 * @license http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @version $Id: db.readenv.php,v 2009032201 javier $
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
    foreach($this->definition as $column) {
      if(preg_match('/^(date|datetime|datenull|time)$/', $column['type'])){
        if($column['type']=='time') 
          $tmpcolumn = $column['name']."_Hour";
        else
          $tmpcolumn = $column['name']."_Year";
      }else{
          $tmpcolumn = $column['name'];
      }

      if (($column['type'] != 'external' || $column['type'] != 'auto') && (isset($_REQUEST[$tmpcolumn]) || isset($_FILES[$column['name']]))) {
        # Recepcion de una imagen
        if ($column['type'] == 'file' || $column['type'] == 'image') {
          if(isset($_FILES[$column['name']]['name'])) {
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
          if (preg_match('/^(date|datetime|datenull)$/', $column['type']))
            $date = $this->parsevar($_REQUEST[$column['name']]);
          else
            $time = $this->parsevar($_REQUEST[$column['name']]);
          if (isset($_REQUEST[$column['name'] . '_Year'])) {
            $year = $this->parsevar($_REQUEST[$column['name'] . '_Year'], 'int');
            $month = $this->parsevar($_REQUEST[$column['name'] . '_Month'], 'int');
            if ($month<10 && strlen($month)==1) $month = '0'.$month;
            $day = $this->parsevar($_REQUEST[$column['name'] . '_Day'], 'int');
            $date = $year . '-' . $month . '-' . $day;
          }
          if (isset($_REQUEST[$column['name'] . '_Hour'])) {
            $this->request[$column['name']] = $year . '-' . $month . '-' . $day;
            $hour = $this->parsevar($_REQUEST[$column['name'] . '_Hour'], 'int');
            $minute = $this->parsevar($_REQUEST[$column['name'] . '_Minute'], 'int');
            $second = $this->parsevar($_REQUEST[$column['name'] . '_Second'], 'int');
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
          #if (isset($_REQUEST[$column['name']])) $this->request[$column['name']] = $this->parsevar($_REQUEST[$column['name']], $column['type']);
          #else $this->request[$column['name']] = 'NULL';
        } elseif($column['type'] == 'video') {
          $strXml = '<?xml version="1.0" encoding="UTF-8"?><video><tipo>'.$_REQUEST[$column['name'].'_type'].'</tipo><src>'.htmlentities($_REQUEST[$column['name'].'_src']).'</src></video>';
          $this->request[$column['name']] = $this->parsevar($strXml, 'string', true);
        } elseif ($column['type'] == 'auth_user') {
          $this->request[$column['name']] = $this->parsevar($this->http_auth_user(), 'string');
        } else {
          $this->request[$column['name']] = $this->parsevar($_REQUEST[$column['name']], $column['type']);
        }
      }
    }
    if (isset($_REQUEST['old_' . $this->key]))
      $this->request['old_' . $this->key] = $_REQUEST['old_' . $this->key];
    $this->escaped = true;
