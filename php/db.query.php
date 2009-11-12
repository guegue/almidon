<?php
    if (preg_match("/(?!')'(\s*?);/",$sqlcmd)) {
      error_log(date("[D M d H:i:s Y]") . " Query invalido. " . $sqlcmd . "\n");
      return false;
    }
    $result = $this->database->query($sqlcmd);
    $this->check_error($result, $sqlcmd);
