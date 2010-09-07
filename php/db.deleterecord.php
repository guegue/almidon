<?php
    if (!$id) $id = $this->request[$this->key];
    # Borra imagenes o archivos relacionados a este registro
    foreach($this->dd as $key=>$val) {
      $type = $this->dd[$key]['type'];
      if ($type == 'image' || $type == 'file' )
        $remove_files[] = $key;
    }
    if (!empty($remove_files)) {
      $tmp = $this->readRecord($id);
      foreach($remove_files as $val)
        unlink(ROOTDIR . '/files/' . $this->name . '/' . $tmp[$val]);
    }
    # Borra registro en base de datos
    $sqlcmd = "DELETE FROM $this->name WHERE $this->key = '$id'";
    $result = $this->query($sqlcmd);
