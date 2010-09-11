<?php
    $result = $this->query($sqlcmd);
    $num = $result->numRows();
    $menu = array();
    for ($i=0; $i < $num; $i++) {
      $r = $result->fetchRow(MDB2_FETCHMODE_ORDERED);
      $new = array($r[0] => $r[1]);
      $menu = $menu + $new;
    }
