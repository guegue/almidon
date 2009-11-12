<?php
    if (PEAR::isError($obj)) {
      $error_msg = $obj->getMessage();
      $error_msg .= " -- " . $extra . " -- " . $_SERVER['SCRIPT_NAME'];
      if (DEBUG === true) trigger_error(htmlentities($error_msg));
      error_log(date("[D M d H:i:s Y]") . " Error: " . $error_msg . "\n");
      if ($die) die();
    } elseif (ALM_SQL_DEBUG !== false && $extra) {
      $this->sql_log($extra);
    }
