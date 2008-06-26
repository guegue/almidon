<?php
/*
* Smarty plugin
* -------------------------------------------------------------
* Type:    modifier
* Name:    fsize
* Version:    0.1b
* Date:    2007-05-21
* Author:    Christian Torrez, chtorrez@gmail.com
* Purpose:  return the filesize (in unit of measurement, default bytes) in a human-readable format, this function was created to work with almidon
* Usage:    In the template, use
            {$file_path|fsize}    =>    123.45 B|KB|MB|GB|TB
            or
            {$file_path|fsize:"MB"}    =>    123.45 MB
            or
            {$file_path|fsize:"TB":4}    =>    0.0012 TB
* Params:    
            string    path            the file path
            string    format            the format, the output shall be: B, KB, MB, GB or TB
            int        precision        the rounding precision
            string    dec_point        the decimal separator
            string    thousands_sep    the thousands separator    
* Install: Drop into the plugin directory
* Version:
*            2003-05-21    Version 0.1b    - initial release
* -------------------------------------------------------------
*/
function smarty_modifier_fsize($file, $table = 'doc',$format = '',$precision = 2, $dec_point = ".", $thousands_sep = ",")
{
  require_once SMARTY_DIR.'plugins/modifier.fsize_format.php';
  $_file =  ROOTDIR . "/files/" . $table."/".$file;
  $_size = filesize($_file);
  return smarty_modifier_fsize_format($_size,$format,$precision,$dec_point,$thousands_sep);
} //~ end function
?>
