<?php
    if ($this->database)
      $tmpvar = almdata::escape($tmpvar);
    switch ($type) {
      case 'varchar':
        $type = 'string';
        break;
      case 'numeric':
        $tmpvar = number_format((float)str_replace(',','',$tmpvar),2,'.','');
        $type = 'float';
        break;
      case 'int':
      case 'smallint':
      case 'serial':
        $tmpvar = (int)str_replace(',','',$tmpvar);
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
