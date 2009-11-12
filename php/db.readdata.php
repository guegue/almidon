<?php
    if ($this->join) {
      $sqlcmd = "SELECT $this->all_fields FROM $this->name " . $this->getJoin();
    } else {
      $sqlcmd = "SELECT $this->fields FROM $this->name";
    }
    if ($this->filter)
      $sqlcmd .= " WHERE $this->filter";
    if ($this->order)
      $sqlcmd .= " ORDER BY $this->order";
    if ($this->limit)
      $sqlcmd .= " LIMIT $this->limit";
    if ($this->offset)
      $sqlcmd .= " OFFSET $this->offset";
    $this->execSql($sqlcmd);
