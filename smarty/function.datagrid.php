<?php
/**
 * Smarty {datagrid} function plugin
 *
 * File:   function.datagrid.php<br>
 * Type:   function<br>
 * Name:   datagrid<br>
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
 *       - cmd        show datagrid commands - boolean
 *       - truncate   truncate values - boolean
 *       - parent     ommit main label and add delete cmd
 * Purpose:  Prints datagrid from datasource
 *       according to the passed parameters
 * @param array
 * @param Smarty
 * @return string
 * @uses smarty_function_escape_special_chars()
 */

include dirname(__FILE__) . '/shared.lang.php';

define('DG', '<form action="_SELF_" method="post" name="_FORM_" enctype="multipart/form-data">
<input type="hidden" name="old__KEY_" value="{_ID_}" />
<input type="hidden" name="_PARENT_" value="_PARENTID_" />
<input type="hidden" name="_KEY_" value="{_ID_}" />
<input type="hidden" name="_FORM_sort" value="_SORT_" />
<input type="hidden" name="_FORM_pg" value="_PG_" />
<input type="hidden" name="maxcols" value="_MAXCOLS_" />
<input type="hidden" name="f" value="_FORM_" />
<input type="hidden" name="action" value="save" />
<table class="dgtable" border="0" cellspacing="0" cellpadding="2"><tr><th>_TITLE_</th> <th align="right"><div align="right">(_ROWS_ '.ALM_REC_LB.')</div></th></tr>
<tr><td colspan="2"><table class="dgsubtable" border="0" cellspacing="0" cellpadding="0"><tr>_DGHEADER__DGHEADERCMD_</tr>
_DGROW_
</table></td></tr><tr><td class="paginate">_PAGINATE_</td></tr></table></form>');
define('DG2', '<form action="_SELF_" method="POST" name="_FORM_">
<input type="hidden" name="old__KEY1_" value="_ID1_">
<input type="hidden" name="old__KEY2_" value="_ID2_">
<input type="hidden" name="_FORM_sort" value="_SORT_">
<input type="hidden" name="_FORM_pg" value="_PG_">
<input type="hidden" name="maxcols" value="_MAXCOLS_">
<input type="hidden" name="f" value="_FORM_">
<input type="hidden" name="action" value="save">
<table class="dgtable" border="0" cellspacing="0" cellpadding="2"><tr><th>_TITLE_</th> <th align="right"><div align="right">(_ROWS_ '.ALM_REC_LB.')</div></th></tr>
<tr><td colspan="2"><table class="dgsubtable" border="0" cellspacing="0" cellpadding="0"><tr>_DGHEADER__DGHEADERCMD_</tr>
_DGROW_
</table></td></tr><tr><td class="paginate">_PAGINATE_</td></tr></table></form>');
define('DGHEADERCMD', '<th>'.ALM_OPT_LB.'</th>');
define('DGHEADERCELL', '<th><a class="dgheader_link" href="_SELF_?q=_Q_&amp;f=_FORM_&amp;_FORM_sort=_FIELD__DESC_">_LABEL__SORTIMG_</a></th>');
define('DGROW', '<tr class="dgrow">_DGCELL_</tr>'."\n");
define('DGCELL', '<td class="dgcell">_VALUE_</td>');
define('DGCELLMODSTR', '<input type="text" name="_FIELD_" value="_VALUE_" size="20" maxlength="_SIZE_"/>');
define('DGCELLMODREF', '<select name="_FIELD_"><option value="-1">--</option>_REFERENCE_</select>');
define('DGCMD', '<td class="dgcmd"><a class="dgcmd_link" href="_SELF_?f=_FORM_&amp;action=record&amp;_KEY_={_ID_}&amp;_FORM_pg=_PG_"><img src="/cms/img/view.png" border="0" title="'. ALM_VIEW_LB .'" alt="'.ALM_VIEW_LB.'"/></a> <a href="javascript:confirm_delete(\'_FORM_\',\'_KEY_\',\'{_ID_}\',\'{_ID_}\');"><img src="/cms/img/delete.png" height="16" width="16" border="0" title="'. ALM_DEL_LB .'" alt="'.ALM_DEL_LB.'"/></a> <a href="_SELF_?q=_Q_&amp;f=_FORM_&amp;action=mod&amp;_KEY_={_ID_}&amp;_FORM_pg=_PG_&amp;_FORM_sort=_SORT_"><img src="/cms/img/edit.png" border="0" title="'. ALM_EDIT_LB .'" alt="'.ALM_EDIT_LB.'"/></a></td>');
define('DGCMDR', '<td class="dgcmd"><a href="javascript:confirm_delete(\'_FORM_\',\'_KEY_\',\'{_ID_}\',\'{_ID_}\');"><img src="/cms/img/delete.png" border="0" title="'. ALM_DEL_LB .'" alt="'.ALM_DEL_LB.'"/></a> <a href="_SELF_?q=_Q_&amp;f=_FORM_&amp;action=mod&amp;_KEY_={_ID_}&amp;_PARENT_=_PARENTID_&amp;_FORM_pg=_PG_&amp;_FORM_sort=_SORT_"><img src="/cms/img/edit.png" border="0" title="'. ALM_EDIT_LB .'" alt="'.ALM_EDIT_LB.'"/></a></td>');
define('DGCMD2', '<td class="dgcmd"><a class="dgcmd_linksss" href="_SELF_?f=_FORM_&amp;action=record&amp;_KEY1_=_ID1_&amp;_KEY2_=_ID2_"><img src="/cms/img/view.png" border="0" title="'. ALM_VIEW_LB .'" alt="'.ALM_VIEW_LB.'"/></a> <a href="javascript:confirm_delete2(\'_FORM_\',\'_KEY1_\',\'_KEY2_\',\'_ID1_\',\'_ID2_\',\'_ID1_ / _ID2_ \');"><img src="/cms/img/delete.png" border="0" title="'. ALM_DEL_LB .'" alt="'.ALM_DEL_LB.'"/></a> <a href="_SELF_?f=_FORM_&amp;action=mod&amp;_KEY1_=_ID1_&amp;_KEY2_=_ID2_&amp;_FORM_pg=_PG_&amp;_FORM_sort=_SORT_"><img src="/cms/img/edit.png" border="0" title="'. ALM_EDIT_LB .'" alt="'.ALM_EDIT_LB.'"/></a></td>');
define('DGCMD2R', '<td class="dgcmd"><a href="javascript:confirm_delete2(\'_FORM_\',\'_KEY1_\',\'_KEY2_\',\'_ID1_\',\'_ID2_\',\'_ID1_ / _ID2_ \');"><img src="/cms/img/delete.png" border="0" title="'. DEL__LB .'" alt="'.ALM_DEL_LB.'"/></a> <a href="_SELF_?f=_FORM_&amp;action=mod&amp;_KEY1_=_ID1_&amp;_KEY2_=_ID2_&amp;_FORM_pg=_PG_&amp;_FORM_sort=_SORT_"><img src="/cms/img/edit.png" border="0" title="'. ALM_EDIT_LB .'" alt="'.ALM_EDIT_LB.'"/></a></td>');
define('DGCMDMOD', '<td class="dgcmd"><a href="_SELF_?f=_FORM_&amp;_KEY_={_ID_}&amp;_FORM_pg=_PG_&amp;_FORM_sort=_SORT_&amp;_PARENT_=_PARENTID_"><img src="/cms/img/cancel.png" border="0" title="'. ALM_CAN_LB .'" alt="'.ALM_CAN_LB.'"></a> <a href="javascript:postBack(document._FORM_, \'dgsave\');"><img src="/cms/img/save.png" border="0" title="'. ALM_SAVE_LB .'" alt="'.ALM_SAVE_LB.'"></a></td>');
define('DGCMD2MOD', '<td class="dgcmd"><a href="_SELF_?f=_FORM_&amp;_KEY1_=_ID1_&amp;_KEY2_=_ID2_&amp;_FORM_pg=_PG_&amp;_FORM_sort=_SORT_"><img src="/cms/img/cancel.png" border="0" title="'. ALM_CAN_LB .'" alt="'.ALM_CAN_LB.'"></a> <a href="javascript:postBack(document._FORM_, \'dgsave\');"><img src="/cms/img/save.png" border="0" title="'. ALM_SAVE_LB .'" alt="'.ALM_SAVE_LB.'"></a></td>');
define('PREV','<a href="_SELF_?q=_Q_&amp;f=_FORM_&amp;_FORM_sort=_SORT_&amp;_FORM_pg=_PGPREV_">&lt; '.ALM_PREV_LB.'</a> |');
define('NEXT','| <a href="_SELF_?q=_Q_&amp;f=_FORM_&amp;_FORM_sort=_SORT_&amp;_FORM_pg=_PGNEXT_">'.ALM_NEXT_LB.' &gt;</a>&nbsp;');
define('NPG','<a href="_SELF_?q=_Q_&amp;f=_FORM_&amp;_FORM_sort=_SORT_&amp;_FORM_pg=_NPG_"> _NPG_ </a>');
define('CURRENTPG','<strong>_NPG_</strong>');
define('PAGINATE','<table><tr><td nowrap><br>_PGS_<br></td></tr></table>');

define('DGCMD2RVER', '<td class="dgcmd"></a></td>');
define('DGCMD2REDIT', '<td class="dgcmd"><a href="_SELF_?f=_FORM_&amp;action=mod&amp;_KEY1_=_ID1_&amp;_KEY2_=_ID2_&amp;_FORM_pg=_PG_&amp;_FORM_sort=_SORT_"><img src="/cms/img/edit.png" border="0" title="'. ALM_EDIT_LB .'" alt="'.ALM_EDIT_LB.'"/></a></td>');
define('DGCMD2RDEL', '<td class="dgcmd"><a href="javascript:confirm_delete2(\'_FORM_\',\'_KEY1_\',\'_KEY2_\',\'_ID1_\',\'_ID2_\',\'_ID1_ / _ID2_ \');"><img src="/cms/img/delete.png" border="0" title="'. DEL__LB .'" alt="'.ALM_DEL_LB.'"/></a></td>');
define('DGCMD2VER', '<td class="dgcmd"><a class="dgcmd_linksss" href="_SELF_?f=_FORM_&amp;action=record&amp;_KEY1_=_ID1_&amp;_KEY2_=_ID2_"><img src="/cms/img/view.png" border="0" title="'. ALM_VIEW_LB .'" alt="'.ALM_VIEW_LB.'"/></a></td>');
define('DGCMD2EDIT', '<td class="dgcmd"><a class="dgcmd_linksss" href="_SELF_?f=_FORM_&amp;action=record&amp;_KEY1_=_ID1_&amp;_KEY2_=_ID2_"><img src="/cms/img/view.png" border="0" title="'. ALM_VIEW_LB .'" alt="'.ALM_VIEW_LB.'"/></a><a href="_SELF_?f=_FORM_&amp;action=mod&amp;_KEY1_=_ID1_&amp;_KEY2_=_ID2_&amp;_FORM_pg=_PG_&amp;_FORM_sort=_SORT_"><img src="/cms/img/edit.png" border="0" title="'. ALM_EDIT_LB .'" alt="'.ALM_EDIT_LB.'"/></a></td>');
define('DGCMD2DEL', '<td class="dgcmd"><a class="dgcmd_linksss" href="_SELF_?f=_FORM_&amp;action=record&amp;_KEY1_=_ID1_&amp;_KEY2_=_ID2_"><img src="/cms/img/view.png" border="0" title="'. ALM_VIEW_LB .'" alt="'.ALM_VIEW_LB.'"/></a></td>');
define('DGCMDEDIT', '<td class="dgcmd"><a class="dgcmd_link" href="_SELF_?f=_FORM_&amp;action=record&amp;_KEY_={_ID_}&amp;_FORM_pg=_PG_"><img src="/cms/img/view.png" border="0" title="'. ALM_VIEW_LB .'" alt="'.ALM_VIEW_LB.'"/></a><a href="_SELF_?q=_Q_&amp;f=_FORM_&amp;action=mod&amp;_KEY_={_ID_}&amp;_FORM_pg=_PG_&amp;_FORM_sort=_SORT_"><img src="/cms/img/edit.png" border="0" title="'. ALM_EDIT_LB .'" alt="'.ALM_EDIT_LB.'"/></a></td>');
define('DGCMDVER', '<td class="dgcmd"><a class="dgcmd_link" href="_SELF_?f=_FORM_&amp;action=record&amp;_KEY_={_ID_}&amp;_FORM_pg=_PG_"><img src="/cms/img/view.png" border="0" title="'. ALM_VIEW_LB .'" alt="'.ALM_VIEW_LB.'"/></a></td>');
define('DGCMDDEL', '<td class="dgcmd"><a class="dgcmd_link" href="_SELF_?f=_FORM_&amp;action=record&amp;_KEY_={_ID_}&amp;_FORM_pg=_PG_"><img src="/cms/img/view.png" border="0" title="'. ALM_VIEW_LB .'" alt="'.ALM_VIEW_LB.'"/></a> <a href="javascript:confirm_delete(\'_FORM_\',\'_KEY_\',\'{_ID_}\',\'{_ID_}\');"><img src="/cms/img/delete.png" height="16" width="16" border="0" title="'. ALM_DEL_LB .'" alt="'.ALM_DEL_LB.'"/></a></td>');
define('DGCMDRVER', '<td class="dgcmd"></td>');
define('DGCMDRDEL', '<td class="dgcmd"><a href="javascript:confirm_delete(\'_FORM_\',\'_KEY_\',\'{_ID_}\',\'{_ID_}\');"><img src="/cms/img/delete.png" border="0" title="'. ALM_DEL_LB .'" alt="'.ALM_DEL_LB.'"/></a></td>');
define('DGCMDREDIT', '<td class="dgcmd"><a href="_SELF_?q=_Q_&amp;f=_FORM_&amp;action=mod&amp;_KEY_={_ID_}&amp;_PARENT_=_PARENTID_&amp;_FORM_pg=_PG_&amp;_FORM_sort=_SORT_"><img src="/cms/img/edit.png" border="0" title="'. ALM_EDIT_LB .'" alt="'.ALM_EDIT_LB.'"/></a></td>');


function smarty_function_datagrid($params, &$smarty)
{
  require_once $smarty->_get_plugin_filepath('shared','escape_special_chars');
  require_once $smarty->_get_plugin_filepath('function','html_options');
  require_once $smarty->_get_plugin_filepath('function','html_select_date');
  require_once $smarty->_get_plugin_filepath('function','html_select_time');
  require_once $smarty->_get_plugin_filepath('modifier','truncate');
  require_once $smarty->_get_plugin_filepath('modifier','wordwrap');
  require_once $smarty->_get_plugin_filepath('modifier','url');
  $rows = array();
  $dd = array();
  $options = array();
  $paginate = false;
  $selected = null;
  $key = null;
  $key1 = null;
  $key2 = null;
  $maxrows = (defined('MAXROWS')) ? MAXROWS : 5;
  $maxcols = (defined('MAXCOLS')) ? MAXCOLS : 5;
  $name = 'dgform';
  $table = null;
  $parent = null;
  $truncate = true;

  $extra = '';
  foreach($params as $_key => $_val) {
    switch($_key) {
      case 'name':
      case 'title':
      case 'key':
      case 'key1':
      case 'key2':
      case 'parent':
        $$_key = (string)$_val;
        break;
      case 'options':
      case 'rows':
      case 'dd':
        $$_key = (array)$_val;
        break;
      case 'paginate':
      case 'cmd':
      case 'truncate':
        $$_key = (bool)$_val;
        break;
      case 'selected':
        if(is_array($_val)) {
          $smarty->trigger_error('datagrid: the "' . $_key . '" attribute cannot be an array', E_USER_WARNING);
        } else {
          $selected = (string)$_val;
        }
        break;
      case 'num_rows':
      case 'maxcols':
      case 'maxrows':
        $$_key = (int)$_val;
        break;
      default:
        if(!is_array($_val)) {
          $extra .= ' '.$_key.'="'.smarty_function_escape_special_chars($_val).'"';
        } else {
          $smarty->trigger_error("datagrid: extra attribute '$_key' cannot be an array", E_USER_NOTICE);
        }
        break;
    }
  }

  if (empty($rows) ) {
    $smarty->trigger_error("datagrid: rows attribute must be present", E_USER_NOTICE);
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

  #
  # Crea los encabezados del datagrid
  #
  $_cols = 0;

  if (!empty($headers)) {
    foreach ($headers as $_key=>$_val) {
      if ($maxcols && ($_cols >= $maxcols))
        break;
      # Tenemos permiso para mostrar este campo?
      if (isset($dd[$_key]['extra']['role']) && $dd[$_key]['extra']['role'] !== $_SESSION['idalm_role']) {
        $dd[$_key]['type'] = 'hidden';
      }
      if ($parent == $_key || $dd[$_key]['type'] == 'external' || $dd[$_key]['type'] == 'hidden')
        continue;
      $_html_header = DGHEADERCELL;

      # Para permitir referencias multiples a la misma tabla
      if ($dd[$_key]['references']) {
        $references[$dd[$_key]['references']] = 0;
        $references[$dd[$_key]['references']]++;
        $n = ($references[$dd[$_key]['references']] == 1) ? '' : $references[$dd[$_key]['references']];
        $_field = $dd[$_key]['references'] . $n;
      } else {
        $_field = $_key;
      }
      if ($_SESSION[$name . 'sort'] == $_field) {
        $_img = '<img src="/cms/img/up.gif" border="0" />';
        $_html_header = preg_replace("/_DESC_/", ' desc', $_html_header);
        $_html_header = preg_replace("/_SORTIMG_/", $_img, $_html_header);
      } else {
        $_html_header = preg_replace("/_DESC_/", '', $_html_header);
      }
      if ($_SESSION[$name . 'sort'] == $_field . ' desc') {
        $_img = '<img src="/cms/img/down.gif" border="0" />';
        $_html_header = preg_replace("/_SORTIMG_/", $_img, $_html_header);
      } else {
        $_html_header = preg_replace("/_SORTIMG_/", '', $_html_header);
      }
      $_html_header = preg_replace("/_LABEL_/", $_val, $_html_header);
      $_html_header = preg_replace("/_FIELD_/", $_field, $_html_header);
      $_html_headers .= $_html_header;
      ++$_cols;
    }
    unset($references);
  } else {
    foreach ($rows[0] as $_key=>$_val) {
      if ($maxcols && ($_cols >= $maxcols))
        break;
      $_html_header = preg_replace("/_LABEL_|_FIELD_/", $_key, DGHEADERCELL);
      $_html_headers .= $_html_header;
      ++$_cols;
    }
  }
 
  $_i = 0;
  $pg = ($_SESSION[$name . 'pg']) ? $_SESSION[$name . 'pg'] : 1; 
  foreach ((array)$rows as $row) {
    unset($references);
    # 
    # Crea las filas del datagrid en modo edicion
    #
    $_html_row = '';
    $_chosen = ($key2) ? ($_REQUEST[$key1] == $row[$key1] && $_REQUEST[$key2] == $row[$key2]) : ($_REQUEST[$key] == $row[$key]); 
    if ($_REQUEST['f'] == $name && $_REQUEST['action'] == 'mod' && $_chosen) {
      $_cols = 0;
      foreach ($row as $_key=>$_val) {
        if ($maxcols && ($_cols >= $maxcols))
          break;
        if (!$dd[$_key]) {
          continue;
        }
        # Tenemos permiso para mostrar este campo?
        if (isset($dd[$_key]['extra']['role']) && $dd[$_key]['extra']['role'] !== $_SESSION['idalm_role']) {
          $dd[$_key]['type'] = 'hidden';
        }
        if ($parent == $_key) {
          $parentid = $_val;
          $dd[$_key]['type'] = 'hidden';
        } elseif ($dd[$_key]['references']) {
          $_selected = $_val;
          if (!isset($references[$dd[$_key]['references']])) $references[$dd[$_key]['references']] = 0;
          $references[$dd[$_key]['references']]++;
          $n = ($references[$dd[$_key]['references']] == 1) ? '' : $references[$dd[$_key]['references']];
          $_val = $row[$dd[$_key]['references'] . $n];
          $dd[$_key]['type'] = 'references';
        }
        switch ($dd[$_key]['type']) {
          case 'file':
          case 'image':
          case 'img':
            $_tmp = '';
            if ($_val) $_tmp = '<input type="checkbox" checked name="' . $_key . '_keep" /> Conservar archivo actual (' . $_val . ')<br /><img src="'.URL.'/cms/pic/50/'. $table . '/' . $_val . '" alt="' . $_val  . '" width="50" border="0" /><br />';
            $_tmp .= '<input type="file" name="' . $_key . '" value="' .$_val . '" />';
            break;
          case 'time':
            $_tmp = smarty_function_html_select_time(array('prefix'=>$_key . '_', 'time'=>$_val, 'display_seconds'=>false), $smarty);
            break;
          case 'datetime':
            $_tmp = '<input type="hidden" name="'.$_key.'" value="'.$_val.'" />';
            $_tmp .= $_val;
            break;
          case 'date':
            $_tmp = smarty_function_html_select_date(array('prefix'=>$_key . '_', 'time'=>$_val, 'start_year'=>"-10", 'end_year'=>"+10"), $smarty);
            break;
          case 'boolean':
          case 'bool':
            if ($dd[$_key]['extra']['label_bool']) {
              list($_si, $_no)  = preg_split('/:/',$dd[$_key]['extra']['label_bool']);
              $_tchecked = ($_val == 't') ? 'checked' : '';
              $_fchecked = ($_val == 'f') ? 'checked' : '';
              $_tmp = $_si . '<input type="radio" name="' . $_key . '" ' . $_tchecked . ' value="on">' . $_no . '<input type="radio" name="' . $_key . '" ' . $_fchecked .' value="">';
            } else {
              $_checked = ($_val == 't') ? 'checked' : '';
              $_tmp = '<input type="checkbox" name="' . $_key . '" ' . $_checked . ' />';
            }
            break;
          case 'text':
            $_tmp = preg_replace("/_VALUE_/",  qdollar(htmlentities($_val,ENT_COMPAT,'UTF-8')), DGCELLMODSTR);
            $_tmp = preg_replace("/_FIELD_/", $_key, $_tmp);
            break;
          case 'varchar':
          case 'char':
            if (preg_match("/=/", $dd[$_key]['extra'])) {
              $_list = preg_split('/:/', $dd[$_key]['extra']);
              $_options = '';
              foreach($_list as $_list_pair) {
                list($_list_key, $_list_val) = preg_split('/=/', $_list_pair);
                $_options[$_list_key] = $_list_val;
              }
              $_tmp = smarty_function_html_options(array('options'=>$_options, 'selected'=>$_val), $smarty);
              $_tmp = preg_replace("/_REFERENCE_/", $_tmp, DGCELLMODREF);
              $_tmp = preg_replace("/_FIELD_/", $_key, $_tmp);
            } else {
              $_tmp = preg_replace("/_VALUE_/",  qdollar(htmlentities($_val,ENT_COMPAT,'UTF-8')), DGCELLMODSTR);
              $_tmp = preg_replace("/_FIELD_/", $_key, $_tmp);
              $_tmp = preg_replace("/_SIZE_/", $dd[$_key]['size'], $_tmp);
            }
            break;
          case 'int':
          case 'numeric':
            $_tmp = preg_replace("/_VALUE_/", $_val, DGCELLMODSTR);
            $_tmp = preg_replace("/_FIELD_/", $_key, $_tmp);
            $_tmp = preg_replace("/_SIZE_/", 10, $_tmp);
            break;
          case 'references':
            $_options = $options[$_key];
            $_tmp = smarty_function_html_options(array('options'=>$_options, 'selected'=>$_selected), $smarty);
            $_tmp = preg_replace("/_REFERENCE_/", qdollar($_tmp), DGCELLMODREF);
            $_tmp = preg_replace("/_FIELD_/", $_key, $_tmp);
            break;
          case 'order':
            $_tmp = '- +';
            break;
          #case 'hidden':
          #  $_tmp = '<input type="hidden" name="'.$_key.'" value="'.$_val.'"  />';
          #  break;
          case 'hidden':
            $hidden = true;
            $_tmp = '';
            break;
          default:
            $_tmp = $_val;
        }
        if ($dd[$_key]['type'] != 'hidden') {
          $_tmp = preg_replace("/_VALUE_/", qdollar($_tmp), DGCELL);
          $_cols++;
        }
        $_html_row .= $_tmp;
      }
      if ($key2) {
        if ($_SESSION['credentials'][$table] == 'full' || $_SESSION['credentials'][$table] == 'edit' || $_SESSION['idalm_user'] === 'admin'){
           $_dgcmdmod = DGCMD2MOD;
        }
      } else {
        if($_SESSION['credentials'][$table] == 'full' || $_SESSION['credentials'][$table] == 'edit' || $_SESSION['idalm_user'] === 'admin'){
          $_dgcmdmod = DGCMDMOD;
        }
      }
      $_html_cmd = preg_replace("/{_ID_}/", $row[$key], $_dgcmdmod);
    } else {
    # 
    # Crea las filas del datagrid en modo lectura
    #
      $_cols = 0;
      foreach ($row as $_key=>$_val) {
        if ($maxcols && ($_cols >= $maxcols))
          break;
        if (!$dd[$_key] || $dd[$_key]['type'] == 'external') {
          continue;
        }
        # Tenemos permiso para mostrar este campo?
        if (isset($dd[$_key]['extra']['role']) && $dd[$_key]['extra']['role'] !== $_SESSION['idalm_role']) {
          $dd[$_key]['type'] = 'hidden';
        }
        if ($parent == $_key) {
          $parentid = $_val;
          continue;
        }
        if ($dd[$_key]['references']) {
          if (!isset($references[$dd[$_key]['references']])) $references[$dd[$_key]['references']] = 0;
          $references[$dd[$_key]['references']]++;
          $n = ($references[$dd[$_key]['references']] == 1) ? '' : $references[$dd[$_key]['references']];
          $_val = $row[$dd[$_key]['references'] . $n];
        }
        switch ($dd[$_key]['type']) {
          case 'char':
            if (preg_match("/=/", $dd[$_key]['extra'])) {
              $_list = preg_split('/:/', $dd[$_key]['extra']);
              $_options = '';
              foreach($_list as $_list_pair) {
                list($_list_key, $_list_val) = preg_split('/=/', $_list_pair);
                $_options[$_list_key] = $_list_val;
              }
              $_tmp = $_options[$_val];
            } else {
              $_tmp = smarty_modifier_truncate($_val, 50);
              $_tmp = smarty_modifier_url($_tmp);
              $_tmp = preg_replace("/_SIZE_/", $dd[$_key]['size'], $_tmp);
            }
            break;
          case 'bool':
          case 'boolean':
            $_si = ALM_YES;
            $_no = ALM_NO;
            if ($dd[$_key]['extra']['label_bool']) {
              list($_si, $_no)  = preg_split('/:/',$dd[$_key]['extra']['label_bool']);
            }
            $_tmp = ($_val == 't') ? $_si : $_no;
            break;
          case 'file':
            if ($_val) {
              $_file =  HOMEDIR . "/_" . $table . "/" . $_val;
              $_icon = 'doc.png';
              $_p = explode('.', $_file);
              $_pc = count($_p);
              $ext = $_p[$_pc - 1];
              if (preg_match('/doc|rtf|swx|osd/i',$ext)) $_icon = 'doc.png';
              if (preg_match('/pdf/i',$ext)) $_icon = 'pdf.png';
              if (preg_match('/xls/i',$ext)) $_icon = 'excel.png';
              if (preg_match('/jpg|gif|png/i',$ext)) $_icon = 'image.png';
              $_tmp = '<a href="' . URL . '/files/' . $table. '/' . $_val . '" target="_new"><img src="/cms/img/' . $_icon . '" alt="' . $_val  . '" border="0" /></a>';
            } else {
              $_tmp = '--';
            }
            break;
          case 'image':
            if ($_val) {
              if (THUMBNAILING)
                $_tmp = '<a href="javascript:openimage(\'' . URL . '/files/' . $table . '/' . $_val . '\',\'Imagen: ' . $_val . '\')"><img src="'. URL .'/cms/pic/50/' . $table . '/' . $_val . '" alt="' . $_val  . '" width="50" border="0" /></a>';
              else
                $_tmp = '<a href="javascript:openimage(\'/files/' . $table . '/' . $_val . '\',\'Imagen: ' . $_val . '\')"><img src="/_' . $table . '/' . $_val . '" alt="' . $_val  . '" width="50" height="20" border="0" /></a>';
            } else {
              $_tmp = '--';
            } 
            break;
          case 'order':
            $_tmp = '<a href="' . $_SERVER['PHP_SELF'] . '?move=up&key=' . $_key . '&val=' . $_val . '"><img src="/cms/img/up.gif" border="0"/></a> <a href="' . $_SERVER['PHP_SELF'] . '?move=down&key=' . $_key . '&val=' . $_val . '"><img src="/cms/img/down.gif" border="0"/></a>';
            break;
          case 'hidden':
            $hidden = true;
            $_tmp = '';
            break;
          case 'text':
          case 'html':
          case 'xhtml':
            # strip_tags quita los tags [x]html, preg_replace reemplaza los &nbsp; por espacio en blanco, el otro preg_replace quita mas de un espacio en blanco conjunto y lo reemplaza por un solo espacio y trim quita los espacios en blanco al final e inicio de la cadena.
            $_val = trim(preg_replace('/\s\s+/',' ',preg_replace('/&nbsp;/',' ',strip_tags($_val))));
          default:
            if ($truncate)
              $_tmp = smarty_modifier_truncate($_val, 50);
            else
              $_tmp = smarty_modifier_wordwrap($_val, 50, "<br/>");
            $_tmp = smarty_modifier_url($_tmp);
        }
        if ($dd[$_key]['type'] != 'hidden') {
          $_tmp = preg_replace("/_VALUE_/", qdollar($_tmp), DGCELL);
          $_cols++;
        }
        $_html_row .= $_tmp;
      }
      if ($key2) {
        if ($_cols <= 3 || $parent){
          if($_SESSION['credentials'][$table] == 'full' || $_SESSION['idalm_user'] === 'admin'){
            $_dgcmd = DGCMD2R;
          }elseif($_SESSION['credentials'][$table] == 'edit'){
            $_dgcmd = DGCMD2RVER;
          }
        }else{
          $_dgcmd = DGCMD2;
        }
      } else {
        if($_cols <= 3 || $parent){
          if($_SESSION['credentials'][$table] == 'full' || $_SESSION['idalm_user'] === 'admin'){
            $_dgcmd =  DGCMDR;
          }elseif($_SESSION['credentials'][$table] == 'edit'){
            $_dgcmd =  DGCMDREDIT;
          }elseif($_SESSION['credentials'][$table] == 'delete'){
            $_dgcmd =  DGCMDRDEL;
          }elseif($_SESSION['credentials'][$table] == 'read'){
            $_dgcmd =  DGCMDRVER;
          }
        }else{
          if($_SESSION['credentials'][$table] == 'full' || $_SESSION['idalm_user'] === 'admin'){
            $_dgcmd =  DGCMD;
          }elseif($_SESSION['credentials'][$table] == 'edit'){
            $_dgcmd =  DGCMDEDIT;
          }elseif($_SESSION['credentials'][$table] == 'delete'){
            $_dgcmd =  DGCMDDEL;
          }elseif($_SESSION['credentials'][$table] == 'read'){
            $_dgcmd =  DGCMDVER;
          }
        }
      }
      $_html_cmd = preg_replace("/{_ID_}/", $row[$key], $_dgcmd);
      $_html_cmd = preg_replace("/_ID1_/", $row[$key1], $_html_cmd);
      $_html_cmd = preg_replace("/_ID2_/", $row[$key2], $_html_cmd);
    }
    if ($cmd)
      $_html_row .= $_html_cmd;
    $_i++;
    $_tmp = DGROW;
    if (!($_i % 2)) {
      $_tmp = preg_replace("/class=\"dgrow\"/", "class=\"dgrow2\"", $_tmp);
    }
    $_html_rows .= preg_replace("/_DGCELL_/", qdollar($_html_row), $_tmp);
    if ($paginate && $maxrows && ($_i >= ($maxrows * $pg)) ) {
      $_need_paginate = true;
      break;
    }
  }
  $_dg= ($key2) ? DG2 : DG;
  $_html_result = preg_replace("/_DGHEADER_/", $_html_headers, $_dg);
  if ($cmd)
    $_html_result = preg_replace("/_DGHEADERCMD_/", DGHEADERCMD, $_html_result);
    $_html_result = preg_replace("/_DGHEADERCMD_/", '', $_html_result);
  $_html_result = preg_replace("/_TITLE_/", $title, $_html_result);
  $_html_result = preg_replace("/_ROWS_/", $num_rows, $_html_result);
  $_html_result = preg_replace("/_DGROW_/", qdollar($_html_rows), $_html_result);

  # Paginacion del datagrid
  $_npgs = ceil($num_rows / $maxrows);
  $_paginate = '';
  if ($paginate && $_npgs > 1) {
    if ($pg != 1)
      $_paginate = PREV;
    for ($_j=1; $_j<=$_npgs; $_j++) {
      if ( ($_j>2) && $_npgs>10 && (($_npgs-$_j)>=2) && (abs($pg-$_j)>3) ) {
        $npg = ($npg != '...' && $npg) ? '...' : ''; 
      } else {
        $npg = ($_j == $pg) ? CURRENTPG : NPG;
      }
      $_paginate .= preg_replace("/_NPG_/", $_j, $npg);
    }
    if ($pg != $_npgs )
      $_paginate .= NEXT;
  }

  $_html_result = preg_replace("/_PARENT_/", $parent, $_html_result);
  $_html_result = preg_replace("/_PARENTID_/", $parentid, $_html_result);
  $_html_result = preg_replace("/_PAGINATE_/", $_paginate, $_html_result);
  if ($_SERVER['PHP_SELF'] == '/almidon/404.php')
    $_html_result = preg_replace("/_SELF_/", SELF, $_html_result);
  else
    $_html_result = preg_replace("/_SELF_/", $_SERVER['PHP_SELF'], $_html_result);
  $_html_result = preg_replace("/_KEY_/", $key, $_html_result);
  $_html_result = preg_replace("/_KEY1_/", $key1, $_html_result);
  $_html_result = preg_replace("/_KEY2_/", $key2, $_html_result);
  $_html_result = preg_replace("/{_ID_}/", $_REQUEST[$key], $_html_result);
  $_html_result = preg_replace("/_ID1_/", $_REQUEST[$key1], $_html_result);
  $_html_result = preg_replace("/_ID2_/", $_REQUEST[$key2], $_html_result);
  $_html_result = preg_replace("/_SORT_/", $_SESSION[$name . 'sort'], $_html_result);
  $_html_result = preg_replace("/_PG_/", $_SESSION[$name . 'pg'], $_html_result);
  $_html_result = preg_replace("/_PGPREV_/", ($pg-1), $_html_result);
  $_html_result = preg_replace("/_PGNEXT_/", ($pg+1), $_html_result);
  $_html_result = preg_replace("/_MAXCOLS_/", $maxcols, $_html_result);
  $_html_result = preg_replace("/_FORM_/", $name, $_html_result);
  $_html_result = preg_replace("/_Q_/", $_REQUEST['q'], $_html_result);
  
  return $_html_result;

}
