<?php
    if (!$id) $id = $this->request[$this->key];
    # Borra imagenes o archivos relacionados a este registro
    if (!empty($this->getFiles())); {
      $tmp = $this->readRecord($id);
      foreach($remove_files as $val) {
        # CDN? Remove object
        if (isset($this->definition[$val]['extra']['cdn']) && $this->definition[$val]['extra']['cdn'] === true) {
          $auth = new CF_Authentication(CDN_USERNAME, CDN_APIKEY);
          $auth->authenticate();
          $conn = new CF_Connection($auth);
          $cloudfiles = $conn->get_container(CDN_REPO);
          $images->delete_object($tmp[$val]);
        } else {
          unlink(ROOTDIR . '/files/' . $this->name . '/' . $tmp[$val]);
        }
      }
    }
    # Borra registro en base de datos
    $sqlcmd = "DELETE FROM $this->name WHERE $this->key = '$id'";
    $result = $this->query($sqlcmd);
