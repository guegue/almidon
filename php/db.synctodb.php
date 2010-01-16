<?php
  global $global_dd;
  # Aplica a BD SQL los cambios necesarios
  require_once('setup.gensql.php');
  $dbtype = $this->dbtype;
  $sqlcmd = "SELECT $this->fields FROM $this->name LIMIT 1";
  @$this->execSql($sqlcmd);
  $sql_fix = '';
  if (almdata::isError($this->data)) {
    #$existe = $this->catalogTableExists($this->name);
    $sqlcmd = "SELECT * FROM $this->name LIMIT 1";
    @$this->execSql($sqlcmd);
    if (PEAR::isError($this->data)) {
      $sql_fix = genSQL($this->name);
      $this->execSql($sql_fix);
      echo "AUTO SQL $sql_fix<br/>\n";
    } else {
      $campos = preg_split('/,/',$this->fields);
      foreach($campos as $campo) {
        #$existe = $this->catalogColumnExists($campo['name']);
        $sqlcmd = "SELECT $campo FROM $this->name LIMIT 1";
        @$this->execSql($sqlcmd);
        if (PEAR::isError($this->data)) {
          $size = (isset($this->dd[$campo]['size']) && $this->dd[$campo]['size'] > 0) ? '('.$this->dd[$campo]['size'].')': '';
           if (!isset($this->key)) $this->key = false;
          if (!isset($global_dd[$campo])) $global_dd[$campo] = null;
          if (!isset($global_dd[$campo]['name'])) $global_dd[$campo]['name'] = null;
          $sql_fix = "ALTER TABLE $this->name ADD COLUMN " . genColumnSQL($this->dd[$campo], $dbtype, $global_dd[$campo]['name'] === $this->key)."; ";
          $this->execSql($sql_fix);
          echo "AUTO SQL $sql_fix<br/>\n";
        }
      }
    }
  }
  #if (!empty($sql_fix)) {
    #$this->execSql($sql_fix);
    #echo "AUTO SQL en $this->name : $sql_fix<br/>\n";
  #}
