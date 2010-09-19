<?php
    if (!$id && isset($this->request[$this->key])) $id = $this->request[$this->key];
    # Nos devuelve el ultimo registro de la tabla, si es qe no se proporciona un id
    if (!$id) $id = $this->getVar("SELECT MAX(" . $this->key . ") FROM " . $this->name);
    if ($this->join) {
      $sqlcmd = "SELECT $this->all_fields FROM $this->name " . $this->getJoin() . " WHERE $this->name.$this->key = '$id'";
    } else {
      $sqlcmd = "SELECT $this->fields FROM $this->name WHERE $this->name.$this->key = '$id'";
    }

    /* checks cache options */
    if (is_null($cache))
      $cache = (ALM_CACHE && !ADMIN);
    $this->filecache = ROOTDIR.'/cache/'.md5($sqlcmd).".$this->name.".__FUNCTION__.'.dat';
    if ($cache === true && file_exists($this->filecache) && (time()-filemtime($this->filecache)<=ALM_CACHE_TIME)) {
      $row = unserialize(file_get_contents($this->filecache));
    } else {
      $this->execSql($sqlcmd);
      if (almdata::isError($sqlcmd)) return;
      $row = almdata::fetchRow($this->data);
      file_put_contents($this->filecache, serialize($row));
    }
    if ($this->html) {
      foreach($row as $key=>$val)
        $row[$key] = htmlentities($val);
    }
    $this->current_record = $row;
