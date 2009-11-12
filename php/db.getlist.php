<?php
    $this->execSql($sqlcmd);
    for ($i = 0; $i < $this->num; $i++) {
      $row = $this->data->fetchRow(MDB2_FETCHMODE_ORDERED);
      $array_rows[] = $row[0];
    }
