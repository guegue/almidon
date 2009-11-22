<?php
    $this->execSql($sqlcmd);
    for ($i = 0; $i < $this->num; $i++) {
      $row = almdata::fetchRow(null, false);
      $array_rows[] = $row[0];
    }
