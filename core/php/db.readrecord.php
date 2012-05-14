<?php

    # No matter how many keys...
    if (isset($id) && !is_array($id)) $id = array($id); //old-fashioned way readRecord(int);
    if (!isset($id)) foreach($this->keys as $key=>$val) $id[] = null;
    foreach($id as $key=>$val) {
      if(empty($val)) $val = $this->request[$this->keys[$key]];
      # Nos devuelve el ultimo registro de la tabla, si es qe no se proporciona un id
      #if (empty($val)) $val = array($this->getVar("SELECT MAX(" . $this->key . ") FROM " . $this->name));
      $keyfilter[] = $this->name . "." . $this->keys[$key] . " = '$val'";
    }
    $filter = join(' AND ', $keyfilter);

    if ($this->join)
      $sqlcmd = "SELECT $this->all_fields FROM $this->name " . $this->getJoin() . " WHERE $filter";
    else
      $sqlcmd = "SELECT $this->fields FROM $this->name WHERE $filter";

    /* checks cache options */
    if (is_null($cache))
      $cache = (ALM_CACHE && !ADMIN);
    $this->filecache = ROOTDIR.'/cache/sql/'.md5($sqlcmd).".$this->name.".__FUNCTION__.'.dat';
    if ($cache === true && file_exists($this->filecache) && (time()-filemtime($this->filecache)<=ALM_CACHE_TIME)) {
        $row = unserialize(file_get_contents($this->filecache));
    } else {
        $this->execSql($sqlcmd);
        if (almdata::isError($sqlcmd)) return;
        $row = almdata::fetchRow($this->data);
        if ($cache === true)
          file_put_contents($this->filecache, serialize($row));
    }
    if ($this->html) {
      foreach($row as $key=>$val)
        $row[$key] = htmlentities($val);
    }
    $this->current_record = $row;
