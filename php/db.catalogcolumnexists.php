<?php

# FIXME: Esto no se usa actualmente. Deberia? Es mas efectivo y preciso, pero tambien mas dependiente de una version especifica.

  if ($dbtype = $data->database->dsn['phptype'] == 'pgsql') {
    $sqlcmd = "SELECT attname AS idalm_column,pg_type.typname AS type, atttypmod-4 AS size, contype AS key, (SELECT relname FROM pg_class WHERE pg_class.oid=confrelid) AS fk,relname FROM pg_attribute JOIN pg_type ON atttypid=pg_type.oid LEFT OUTER JOIN pg_constraint ON attrelid=conrelid AND attnum = ANY (conkey) JOIN pg_class ON attrelid=pg_class.oid WHERE attname NOT IN ('xmin','cmin','cmax','xmax','max_value','min_value','ctid','tableoid') AND pg_class.relname = '".$this->name."' AND attname='".$column_name."' AND NOT attisdropped";
    $this->execSql($sqlcmd);
    $rows = $this->getArray();
  }
  if ($dbtype = $data->database->dsn['phptype'] == 'mysql') {
    $sqlcmd = "SHOW COLUMN ".$column_name." FROM ".$this->name;
    $this->execSql($sqlcmd);
    $rows = $this->getArray();
  }
  $exists = (count($rows) >= 1) ? true : false;
