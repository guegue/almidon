<?php
    $loghandle = @fopen(SQLLOG, 'a');
    if (is_writable(SQLLOG)) {
      fwrite($loghandle, date("[D M d H:i:s Y]") . " " . $logtext . "\n");
      fclose($loghandle);
    }
