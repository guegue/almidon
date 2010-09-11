<?php
    $sqlcmd = "SELECT $this->key FROM $this->name";
    if ($this->order)
        $sqlcmd .= " ORDER BY $this->order";
    $this->execSql($sqlcmd);
    $rows = @pg_fetch_all($this->data);
    foreach($rows as $row) {
      $value = $row[$this->key];
      if ($value == $current) {
        $prev = $oldvalue;
        break;
      }
      $oldvalue = $value;
    }
