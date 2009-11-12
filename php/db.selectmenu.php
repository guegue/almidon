<?php
    if (!$sqlcmd) {
      if(isset($this->dd[$this->name])) {
        $sqlcmd = "SELECT $this->key, $this->name FROM $this->name _WHERE_ ORDER BY $this->name.$this->name";
      } else {
        $sqlcmd = "SELECT $this->key, $this->key AS $this->name FROM $this->name _WHERE_ ORDER BY $this->name";
      }
    }

    if (!preg_match("/SELECT/", $sqlcmd))
      $sqlcmd = "SELECT id$sqlcmd, $sqlcmd FROM $sqlcmd _WHERE_ ORDER BY $sqlcmd";

    if($filter)
      $sqlcmd = preg_replace('/_WHERE_/ ',"WHERE $filter",$sqlcmd);
    else
      $sqlcmd = preg_replace('/_WHERE_/ ','',$sqlcmd);


    $result = $this->query($sqlcmd);
    $num = $result->numRows();
    $menu = array();
    for ($i=0; $i < $num; $i++) {
      $r = $result->fetchRow(MDB2_FETCHMODE_ORDERED);
      $new = array($r[0] => $r[1]);
      $menu = $menu + $new;
    }
