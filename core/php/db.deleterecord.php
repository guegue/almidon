<?php

    # No matter how many keys...
    if (isset($id) && !is_array($id)) $id = array($id); //old-fashioned way readRecord(int);
    if (!isset($id)) foreach($this->keys as $key=>$val) $id[] = null;
    foreach($id as $key=>$val) {
      if(empty($val)) $val = $this->request[$this->keys[$key]];
      $keyfilter[] = $this->keys[$key] . " = '$val'";
    }
    $filter = join(' AND ', $keyfilter);

    # Borra imagenes o archivos relacionados a este registro
    $getfiles = $this->getFiles();
    if (!empty($getfiles)) {
      $tmp = $this->readRecord($id);
      foreach($getfiles as $val) {
        if(!empty($tmp[$val])) {
          # CDN? Remove object
          if (isset($this->dd[$val]['extra']['cdn']) && $this->dd[$val]['extra']['cdn'] === true) {
            $cloudfiles = almdata::cdn_connect();
            $cloudfiles->delete_object($tmp[$val]);
          } else {
            unlink(ROOTDIR . '/files/' . $this->name . '/' . $tmp[$val]);
          }
        }
      }
    }

    # Borra registro en base de datos
    $sqlcmd = "DELETE FROM $this->name WHERE $filter";
    $result = $this->query($sqlcmd);
