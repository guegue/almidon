<?php
#  function check_error($sqlcmd, $extra = '', $die = false) {
    if (almdata::isError($sqlcmd)) {
      $error_msg = $this->errors[md5($sqlcmd)];
      if ($extra) $error_msg .= " -- " . $extra;
      $error_msg .= " -- " . $_SERVER['SCRIPT_NAME'];
      if (DEBUG === true) {
        print '<table bgcolor="red"><tr><td>';
        trigger_error(htmlentities($error_msg) . "<br/>\n");
        print '</td></tr></table>';
      }
      error_log(date("[D M d H:i:s Y]") . " Error: " . $error_msg . "\n");
      if ($die) die();
    } elseif (ALM_SQL_DEBUG !== false && $extra) {
      $this->sql_log($extra);
    }
