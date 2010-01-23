<?php
    unset ($this->request);
    unset ($this->files);
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
      if (($column['type'] != 'external' || $column['type'] != 'auto') && (isset($_REQUEST[$tmpcolumn]) || $column['type'] == 'auth_user' || isset($_FILES[$column['name']]))) {
        if (($column['type'] == 'file' || $column['type'] == 'image')  && $_FILES[$column['name']]['name']) {
          $this->request[$column['name']] = $_FILES[$column['name']]['name'];
          $this->files[$column['name']] = $_FILES[$column['name']]['tmp_name'];
        } elseif (preg_match('/^(date|datetime|datenull|time)$/', $column['type'])) {
          $date = ''; $time = '';
          if (isset($_REQUEST[$column['name']])) {
            if (preg_match('/^(date|datetime|datenull)$/', $column['type']))
              $date = $this->parsevar($_REQUEST[$column['name']]);
            else
              $time = $this->parsevar($_REQUEST[$column['name']]);
          }
          if ($_REQUEST[$column['name'] . '_Year']) {
            $year = $this->parsevar($_REQUEST[$column['name'] . '_Year'], 'int');
            $month = $this->parsevar($_REQUEST[$column['name'] . '_Month'], 'int');
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
        } elseif ($column['type'] == 'auth_user') {
          $this->request[$column['name']] = $this->parsevar($this->http_auth_user(), 'string');
        } elseif(isset($column['extra']['allow_js']) && $column['extra']['allow_js']!==false) {
          $this->request[$column['name']] = $this->parsevar($_REQUEST[$column['name']], $column['type'], false, $column['extra']['allow_js']);
        } else {
          $this->request[$column['name']] = $this->parsevar($_REQUEST[$column['name']], $column['type']);
        }
      }
    }

    if (isset($_REQUEST['old_' . $this->key1]) && isset($_REQUEST['old_' . $this->key2])) {
      $this->request['old_' . $this->key1] = $_REQUEST['old_' . $this->key1];
      $this->request['old_' . $this->key2] = $_REQUEST['old_' . $this->key2];
    }
    $this->escaped = true;
