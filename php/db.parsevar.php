<?php
    if ($this->database) 
      $tmpvar = $this->database->escape($tmpvar);
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
    #if ($type == 'string') {
    if ($type == 'string' && !$allow_js) {
      $tmpvar = preg_replace("/<script[^>]*?>.*?<\/script>/i", "", $tmpvar);
    } 
    if ($type == 'string' && !$html) {
      $tmpvar = strip_tags($tmpvar, ALM_ALLOW_TAGS);
    }
