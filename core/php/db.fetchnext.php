<?php
    $sqlcmd = "SELECT $this->key FROM $this->name";
    if ($this->order)
    	$sqlcmd .= " ORDER BY $this->order";
    $this->execSql($sqlcmd);
    $rows = @pg_fetch_all($this->data);
    foreach($rows as $row) {
      $value = $row[$this->key];
      if ($next) {
        $next = $value;
        break;
      } elseif ($value == $current)
        $next = $value;
    }
