<?php
    if (!$id && isset($this->request[$this->key])) $id = $this->request[$this->key];
    # Nos devuelve el ultimo registro de la tabla, si es qe no se proporciona un id
    if (!$id) $id = $this->getVar("SELECT MAX(" . $this->key . ") FROM " . $this->name);
    if ($this->join) {
      $sqlcmd = "SELECT $this->all_fields FROM $this->name " . $this->getJoin() . " WHERE $this->name.$this->key = '$id'";
    } else
      $sqlcmd = "SELECT $this->fields FROM $this->name WHERE $this->name.$this->key = '$id'";
    $this->execSql($sqlcmd);
    if (!PEAR::isError($this->data)) {
      $row = $this->data->fetchRow(MDB2_FETCHMODE_ASSOC);
      if ($this->html) {
        foreach($row as $key=>$val)
          $row[$key] = htmlentities($val);
      }
      $this->current_record = $row;
    }
