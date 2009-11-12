<?php
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
          $this->request[$column['name']] = $datetime;
        } elseif ($column['type'] == 'auth_user') {
          $this->request[$column['name']] = $this->parsevar($this->http_auth_user(), 'string');
        } elseif($column['extra']['ena_js']!==false) {
          $this->request[$column['name']] = $this->parsevar($_REQUEST[$column['name']], $column['type'], false, $column['extra']['ena_js']);
        } else {
          $this->request[$column['name']] = $this->parsevar($_REQUEST[$column['name']], $column['type']);
        }
      }
    }
    $this->request['old_' . $this->key1] = $_REQUEST['old_' . $this->key1];
    $this->request['old_' . $this->key2] = $_REQUEST['old_' . $this->key2];
  }
