<?php
    if (!$sqlcmd) {
      if(isset($this->dd[$this->name])) {
        $sqlcmd = "SELECT $this->key, $this->name FROM $this->name _WHERE_ ORDER BY $this->name.$this->name";
      } else {
        $sqlcmd = "SELECT $this->key, $this->key AS $this->name FROM $this->name _WHERE_ ORDER BY $this->name";
      }
    }

   /* $sqlcmd no contiene comando sql sino nombre de la tabla !?! */
    global $global_dd;
    if (!preg_match("/^SELECT/", $sqlcmd)) {
      $table = $sqlcmd;
      $id = $global_dd[$table]['key'];
      $descriptor = $global_dd[$table]['descriptor'];
      $sqlcmd = "SELECT $id, $descriptor FROM $sqlcmd _WHERE_ ORDER BY $descriptor";
    }

    if($filter)
      $sqlcmd = preg_replace('/_WHERE_/ ',"WHERE $filter",$sqlcmd);
    else
      $sqlcmd = preg_replace('/_WHERE_/ ','',$sqlcmd);

    $result = $this->query($sqlcmd);
    $num = almdata::rows($result);
    $menu = array();
    for ($i=0; $i < $num; $i++) {
      $r = almdata::fetchRow($result, false);
      $menu[$r[0]] = $r[1];
    }
