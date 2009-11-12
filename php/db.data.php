<?php
    global $DSN;
    if ($DSN)
      $this->database =& MDB2::connect ($DSN);
    else
      $this->database =& MDB2::connect (DSN);
    $this->check_error($this->database,'',true);
    $this->num = 0;
    $this->cols = 0;
    $this->max = MAXROWS;
    $this->current_pg = (isset($_REQUEST['pg'])) ? (int)$_REQUEST['pg'] : '1';
