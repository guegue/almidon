<?php
    if (preg_match("/(?!')'(\s*?);/",$sqlcmd)) {
      error_log(date("[D M d H:i:s Y]") . " Query invalido. " . $sqlcmd . "\n");
      return false;
    }

    $result = almdata::query($sqlcmd);
    $this->check_error($sqlcmd, $sqlcmd);
