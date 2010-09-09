<?php
    if (!$id) $id = $this->request[$this->key];
    # Borra imagenes o archivos relacionados a este registro
    $getfiles = $this->getFiles();
    if (!empty($getfiles)); {
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
    $sqlcmd = "DELETE FROM $this->name WHERE $this->key = '$id'";
    $result = $this->query($sqlcmd);
