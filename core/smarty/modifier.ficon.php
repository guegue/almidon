<?php
/*
* Smarty plugin
* -------------------------------------------------------------
* Type:    modifier
* Name:    ficon
* Version:    0.1b
* Date:    2007-05-21
* Author:    Christian Torrez, chtorrez@gmail.com
* Purpose:  return the icon in almidon to file type specefied
* Usage:    In the template, use
            {$file_path|ficon:table_name}
            or
            {$file_path|ficon:table_name:absolute_path}    =>    /home/img/image.type
* Params:    
            string    file            the file
            string    table            table name where the file is saved
            bool      absolute_path        is a boolean, to know if return is a path absoulte of image, default true or is the pic name
* Install: Drop into the plugin directory
* Version:
*            2003-05-21    Version 0.1b    - initial release
* -------------------------------------------------------------
*/
function smarty_modifier_ficon($file, $table = '', $absolute_path = true)
{
  $_file =  ROOTDIR . "/files/" . $table."/".$file;
  $_icon = 'doc.png';
  $_p = explode('.', $_file);
  $_pc = count($_p);
  $ext = $_p[$_pc - 1];
  if (preg_match('/doc|rtf|swx|osd/i',$ext)) $_icon = 'word.png';
  if (preg_match('/rtf|swx|osd/i',$ext)) $_icon = 'doc.png';
  if (preg_match('/pdf/i',$ext)) $_icon = 'pdf.png';
  if (preg_match('/xls/i',$ext)) $_icon = 'excel.png';
  if (preg_match('/jpg|gif|png/i',$ext)) $_icon = 'image.png';
  if($absolute_path)
    return '/cms/img/'.$_icon;
  else return $_icon;
} //~ end function
?>
