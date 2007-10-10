<?php
/**
 * Smarty {datareport} function plugin
 *
 * File:   function.datareport.php<br>
 * Type:   function<br>
 * Name:   datareport<br>
 * Date:   10.sep.2004<br>
 * Input:<br>
 *       - name       data form name- string
 *       - rows       data readData - associative array
 *       - dd         dd definition - array of associative array
 *       - paginate   (paginate 0 o 1, default: 0) - boolean
 *       - selected   (selected id, optional, default to none) - int/string
 *       - key        (primary key) - string
 *       - key1       (primary key) - string
 *       - key2       (primary key) - string
 *       - title      - string
 *       - table      table o image dir - string
 *       - options    data for select menus - associative array
 *       - cmd        show datareport commands - boolean
 * Purpose:  Prints datareport from datasource
 *       according to the passed parameters
 * @param array
 * @param Smarty
 * @return string
 * @uses smarty_function_escape_special_chars()
 */

define('DR', '<form action="_SELF_" method="POST" name="_FORM_">
<input type="hidden" name="_KEY_" value="_ID_">
<input type="hidden" name="_FORM_sort" value="_SORT_">
<input type="hidden" name="_FORM_pg" value="_PG_">
<input type="hidden" name="maxcols" value="_MAXCOLS_">
<input type="hidden" name="f" value="_FORM_">
<input type="hidden" name="action" value="save">
<table class="dgtable" border="0" cellspacing="0" cellpadding="2"><tr><th>_TITLE_</th> <th align="right"><div align="right">(_ROWS_ registros)</div></th></tr>
<tr><td colspan="2"><table class="dgsubtable" border="0" cellspacing="0" cellpadding="0"><tr>_DRHEADER_</tr>
_DRROW_
<tr>_TOTAL_</tr>
</table></td></tr></table></form>');
define('DR2', '<form action="_SELF_" method="POST" name="_FORM_">
<input type="hidden" name="old__KEY1_" value="_ID1_">
<input type="hidden" name="old__KEY2_" value="_ID2_">
<input type="hidden" name="_FORM_sort" value="_SORT_">
<input type="hidden" name="_FORM_pg" value="_PG_">
<input type="hidden" name="maxcols" value="_MAXCOLS_">
<input type="hidden" name="f" value="_FORM_">
<input type="hidden" name="action" value="save">
<table class="dgtable" border="0" cellspacing="0" cellpadding="2"><tr><th>_TITLE_ _ROWS_ registros<</th></tr>
<tr><td><table class="dgsubtable" border="0" cellspacing="0" cellpadding="0"><tr>_DRHEADER_</tr>
_DRROW_
<tr>_TOTAL_</tr>
</table></td></tr></table></form>');
define('DRHEADERCELL', '<th><a class="dgheader_link" href="_SELF_?f=_FORM_&_FORM_sort=_FIELD_">_LABEL_</a> [<a class="dgheader_link" href="_SELF_?f=_FORM_&_FORM_group=_FIELD_">*</a>]</th>');
define('DRROW', '<tr class="dgrow">_DRCELL_</tr>'."\n");
define('DRCELL', '<td class="dgcell">_VALUE_</td>');
define('DRCELLMODSTR', '<input type="text" name="_FIELD_" value="_VALUE_" size="20" maxlength="_SIZE_"/>');
define('DRCELLMODREF', '<select name="_FIELD_"><option value="-1">--</option>_REFERENCE_</select>');

function getsubtotal() {
  global $_old_row, $group, $_key, $_val, $dd, $rows, $subtotals;
  $_tmp = "<td><font color=red>". $_old_row[$group]  . "</font></td>";
  if ($subtotals) {
    foreach ($rows[0] as $_key=>$_val) {
      if (($dd && !$dd[$_key]) || ($dd[$_key]['type'] == 'serial'))
        continue;
      if ($subtotals[$_key]) {
        $_tmp .= "<td><font color=red>" . number_format($subtotals[$_key],2) . "</font></td>";
        $subtotals[$_key] = 0;
      } else
        $_tmp .= "<td> -</td>";
    }
  }
  return "<tr>$_tmp</tr>";
}

function smarty_function_datareport($params, &$smarty)
{
global $_old_row, $group, $_key, $_val, $dd, $rows, $subtotals;
  require_once $smarty->_get_plugin_filepath('shared','escape_special_chars');
  require_once $smarty->_get_plugin_filepath('function','html_options');
  require_once $smarty->_get_plugin_filepath('function','html_select_date');
  require_once $smarty->_get_plugin_filepath('function','html_select_time');
  require_once $smarty->_get_plugin_filepath('modifier','truncate');
  $rows = array();
  $dd = array();
  $options = array();
  $paginate = false;
  $selected = null;
  $key = null;
  $key1 = null;
  $key2 = null;
  $maxrows = (MAXROWS) ? MAXROWS : 5;
  $maxcols = 0;
  $name = 'dgform';
  $table = null;
  
  $extra = '';
  foreach($params as $_key => $_val) {
    switch($_key) {
      case 'name':
      case 'title':
      case 'key':
      case 'key1':
      case 'key2':
        $$_key = (string)$_val;
        break;
      case 'options':
      case 'rows':
      case 'dd':
        $$_key = (array)$_val;
        break;
      case 'paginate':
      case 'cmd':
        $$_key = (bool)$_val;
        break;
      case 'selected':
        if(is_array($_val)) {
          $smarty->trigger_error('datareport: the "' . $_key . '" attribute cannot be an array', E_USER_WARNING);
        } else {
          $selected = (string)$_val;
        }
        break;
      case 'maxcols':
      case 'maxrows':
        $$_key = (int)$_val;
        break;
      default:
        if(!is_array($_val)) {
          $extra .= ' '.$_key.'="'.smarty_function_escape_special_chars($_val).'"';
        } else {
          $smarty->trigger_error("datareport: extra attribute '$_key' cannot be an array", E_USER_NOTICE);
        }
        break;
    }
  }

  if (empty($rows) ) {
    $smarty->trigger_error("datareport: rows attribute must be present", E_USER_NOTICE);
    return ''; /* raise error here? */
  }
  if (empty($dd) ) {
    $headers = null;
  } else {
    foreach ($dd as $_key => $_val)
      $headers[$_key] = $_val['label'];
  }
  if (!$table) $table = $name;
  
  $_html_headers = '';
  $_html_rows = '';
  $_html_result = '';

  # Crea los encabezados del datareport
  $_cols = 0;
  if (!empty($headers)) {
    foreach ($headers as $_key=>$_val) {
      if ($maxcols && ($_cols >= $maxcols))
        break;
      $_html_header = preg_replace("/_LABEL_/", $_val, DRHEADERCELL);
      if ($dd[$_key]['references'])
        $_html_header = preg_replace("/_FIELD_/", $dd[$_key]['references'], $_html_header);
      else
        $_html_header = preg_replace("/_FIELD_/", $_key, $_html_header);
      $_html_headers .= $_html_header;
      ++$_cols;
    }
  } else {
    foreach ($rows[0] as $_key=>$_val) {
      if ($maxcols && ($_cols >= $maxcols))
        break;
      $_html_header = preg_replace("/_LABEL_|_FIELD_/", $_key, DRHEADERCELL);
      $_html_headers .= $_html_header;
      ++$_cols;
    }
  }
  
  # Crea las filas del datareport
  $group =  $_REQUEST[$name . 'group'];
  $_i = 0;
  foreach ((array)$rows as $row) {
    $_html_row = '';
    $_chosen = ($key2) ? ($_REQUEST[$key1] == $row[$key1] && $_REQUEST[$key2] == $row[$key2]) : ($_REQUEST[$key] == $row[$key]); 
    $_cols = 0;
    if ($_i && $group && $row[$group] != $_old_row[$group]) {
      $_html_row .= getsubtotal();
    }
    foreach ($row as $_key=>$_val) {
      if ($maxcols && ($_cols >= $maxcols))
        break;
      if ($dd && !$dd[$_key]) {
        continue;
      }
      if ($dd[$_key]['references']) {
        $_val = $row[$dd[$_key]['references']];
      }
      if (!$dd && is_numeric($_val)) {
        $subtotals[$_key] += $_val;
        $totals[$_key] += $_val;
      }
      switch ($dd[$_key]['type']) {
        case 'numeric':
          $totals[$_key] += $_val;
          $subtotals[$_key] += $_val;
          $_tmp = $_val;
          break;
        case 'bool':
        case 'boolean':
          $_si = "S&iacute";
          $_no = "No";
          if ($dd[$_key]['extra']) {
            list($_si, $_no)  = split(':',$dd[$_key]['extra']);
          }
          $_tmp = ($_val == 't') ? $_si : $_no;
          break;
        case 'image':
          if ($_val) {
            $_tmp = '<a href="javascript:openimage(\'/_' . $table . '/' . $_val . '\',\'Imagen: ' . $_val . '\')"><img src="/_' . $table . '/' . $_val . '" alt="' . $_val  . '" width="50" height="20" border="0" /></a>';
          } else {
            $_tmp = '--';
          } 
          break;
        default:
          $_tmp = smarty_modifier_truncate($_val, 50);
      }
      $_html_row .= preg_replace("/_VALUE_/", $_tmp, DRCELL);
      $_cols++;
    }
    $_i++;
    $_tmp = DRROW;
    if (!($_i % 2)) {
      $_tmp = preg_replace("/class=\"dgrow\"/", "class=\"dgrow2\"", $_tmp);
    }
    $_html_rows .= preg_replace("/_DRCELL_/", $_html_row, $_tmp);
    $_old_row = $row;
  }
  if ($_i && $group) {
    $_html_rows .= preg_replace("/_DRCELL_/", getsubtotal(), DRROW);
  }
  $_html_result = preg_replace("/_DRHEADER_/", $_html_headers, DR);
  $_html_result = preg_replace("/_TITLE_/", $title, $_html_result);
  $_html_result = preg_replace("/_ROWS_/", count($rows), $_html_result);
  $_html_result = preg_replace("/_DRROW_/", $_html_rows, $_html_result);

  # Totales del datareport
  $_totals = '';
  if ($totals) {
    foreach ($rows[0] as $_key=>$_val) {
      if ($dd && !$dd[$_key]) {
        continue;
      }
      if ($totals[$_key])
        $_totals .= "<td>" . number_format($totals[$_key],2) . "</td>";
      else
        $_totals .= "<td> -</td>";
    }
  }

  $_html_result = preg_replace("/_TOTAL_/", $_totals, $_html_result);
  $_html_result = preg_replace("/_SELF_/", $_SERVER['PHP_SELF'], $_html_result);
  $_html_result = preg_replace("/_KEY_/", $key, $_html_result);
  $_html_result = preg_replace("/_KEY1_/", $key1, $_html_result);
  $_html_result = preg_replace("/_KEY2_/", $key2, $_html_result);
  $_html_result = preg_replace("/_ID_/", $_REQUEST[$key], $_html_result);
  $_html_result = preg_replace("/_ID1_/", $_REQUEST[$key1], $_html_result);
  $_html_result = preg_replace("/_ID2_/", $_REQUEST[$key2], $_html_result);
  $_html_result = preg_replace("/_SORT_/", $_SESSION[$name . 'sort'], $_html_result);
  $_html_result = preg_replace("/_MAXCOLS_/", $maxcols, $_html_result);
  $_html_result = preg_replace("/_FORM_/", $name, $_html_result);
  
  return $_html_result;

}

?>
