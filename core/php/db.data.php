<?php
    global $DSN;
    // FIXME: hay una variable DSN? o esto es para algun backward-compatibility?
    if ($DSN)
      $this->database = almdata::connect ($DSN);
    else
      $this->database = almdata::connect (DSN);
    $this->num = 0;
    $this->cols = 0;
    $this->max = MAXROWS;
    $this->current_pg = (isset($_REQUEST['pg'])) ? (int)$_REQUEST['pg'] : '1';
