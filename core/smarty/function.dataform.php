<?php
/**
 * Smarty {dataform} function plugin
 *
 * File:   function.dataform.php<br>
 * Type:   function<br>
 * Name:   dataform<br>
 * Date:   10.sep.2004<br>
 * Input:<br>
 *       - name       data form name- string
 *       - row        data readDataRecord - associative array
 *       - dd         dd definition - array of associative array
 *       - keys        (required primary key) - array
 *       - title      - string
 *       - options    data for select menus - associative array
 *       - edit       allow editing of data - bool
 *       - cmd        show dataform commands - int 0=none 1=save/cancel 2=change/cance 3=add/none
 *       - object     name of the object to save - string
 *       - table      table o image dir - string
 *       - preset     id already set
 * Purpose:  Prints dataform from datasource
 *       according to the passed parameters
 * @param array
 * @param Smarty
 * @return string
 * @uses smarty_function_escape_special_chars()
 */

include dirname(__FILE__) . '/shared.lang.php';
require(dirname(__FILE__) . '/define.dataform.php');

function smarty_function_dataform($params, &$smarty) {
  require_once $smarty->_get_plugin_filepath('shared','escape_special_chars');
  require_once $smarty->_get_plugin_filepath('function','html_options');
  require_once $smarty->_get_plugin_filepath('function','html_select_date');
  require_once $smarty->_get_plugin_filepath('function','html_select_time');
  require_once $smarty->_get_plugin_filepath('modifier','truncate');
  require_once $smarty->_get_plugin_filepath('modifier','nl2br');
  require_once $smarty->_get_plugin_filepath('modifier','wordwrap');
  $row = array();
  $dd = array();
  $options = array();
  $keys = array();
  $type = 0;
  $name = 'dfform';
  $object = null;
  $edit = false;
  $table = null;
  $preset = null;
  
  $extra = '';
  foreach($params as $_key => $_val) {
    switch($_key) {
      case 'name':
      case 'object':
      case 'title':
      case 'preset':
        $$_key = (string)$_val;
        break;
      case 'options':
      case 'row':
      case 'dd':
      case 'keys':
        $$_key = (array)$_val;
        break;
      case 'paginate':
      case 'edit':
        $$_key = (bool)$_val;
        break;
      default:
        if(!is_array($_val)) {
          $extra .= ' '.$_key.'="'.smarty_function_escape_special_chars($_val).'"';
        } else {
          $smarty->trigger_error("dataform: extra attribute '$_key' cannot be an array", E_USER_NOTICE);
        }
        break;
    }
  }

  if (!$object) $object = $name;
  if (!$table) $table = $object;
  if (empty($row) && empty($dd)) {
    $smarty->trigger_error("dataform: either dd or row attribute must be present", E_USER_NOTICE);
    return '';
  }
  if (empty($row) ) {
    $type = 2; /* new record */
  }
  if ($edit) $type = 1;
  if (empty($dd) ) {
    $labels = null;
  } else {
    foreach ($dd as $_key => $_val)
      $labels[$_key] = $_val['label'];
  }
  if ($preset) {
    list($_field, $_text) = preg_split('/,/', $preset);
    list($_fieldname, $_fieldvalue) = preg_split('/=/', $_field);
    $_preset[$_fieldname] = $_fieldvalue;
    $_preset_text[$_fieldname] = $_text;
  }
  
  $_html_rows = '';
  $_html_result = '';

  #
  # Crea las filas del dataform en modo edicion
  #
  $_i = 0;
  if ($type) {
    foreach ((array)$dd as $_key=>$_val) {
      if (!$dd[$_key]) {
        continue;
      }
      if ($type == 2) $_val = '';
      if ($type == 1) $_val = $row[$_val['name']];
      # Tenemos permiso para mostrar este campo?
      if (isset($dd[$_key]['extra']['role']) && $dd[$_key]['extra']['role'] !== $_SESSION['idalm_role']) {
        $dd[$_key]['type'] = 'hidden';
      }
      if ($dd[$_key]['references'] && $dd[$_key]['type'] != 'hidden') {
        if ($_preset[$_key]) {
          $_selected = $_preset[$_key];
        } else {
          $_selected = $_val;
          if (!isset($references[$dd[$_key]['references']])) $references[$dd[$_key]['references']] = 0;
          $references[$dd[$_key]['references']]++;
          $n = ($references[$dd[$_key]['references']] == 1) ? '' : $references[$dd[$_key]['references']];
          $_val = $row[$dd[$_key]['references'] . $n];
        }
        $dd[$_key]['type'] = 'references';
      }
      $_start_year = "-10";
      $_end_year = "+10";
      switch ($dd[$_key]['type']) {
        case 'file':
          $_tmp = '';
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
            $_tmp = '<input type="checkbox" checked name="' . $_key . '_keep" /> Conservar archivo actual (' . $_val . ')<br /><img src="' . URL . '/' . ALM_URI . '/themes/' . ALM_ADMIN_THEME . '/img/' . $_icon . '" alt="' . $_val  . '" border="0" /><br />';
          }
          $_tmp .= '<input type="file" name="' . $_key . '" value="' .$_val . '" />';
          break;
        case 'image':
        case 'img':
          $_tmp = '';
          $_icon = 'image.png';
          $_tmp = '<img src="' . URL . '/' . ALM_URI . '/themes/' . ALM_ADMIN_THEME . '/img/' .$_icon . '" border="0" alt="Imagen" title="Imagen" />';
          if ($_val) $_tmp .= '<input type="checkbox" checked name="' . $_key . '_keep" /> Conservar archivo actual (' . $_val . ')<br /><img src="' . URL . '/' . ALM_URI . '/pic/50/' . $table . '/' . $_val . '" alt="' . $_val  . '" width="50" border="0" /><br />';
          $_tmp .= '<input type="file" name="' . $_key . '" value="' .$_val . '" />';
          break;
        case 'boolean':
        case 'bool':
          if (preg_match("/:/", $dd[$_key]['extra']['label_bool'])) {
            list($_si, $_no)  = preg_split('/:/',$dd[$_key]['extra']['label_bool']);
            $_tchecked = ($_val == 't') ? 'checked' : '';
            $_fchecked = ($_val == 'f') ? 'checked' : '';
            $_tmp = $_si . '<input type="radio" name="' . $_key . '" ' . $_tchecked . ' value="on" />' . $_no . '<input type="radio" name="' . $_key . '" ' . $_fchecked .' value="" />';
          } else {
            $_checked = ($_val == 't') ? 'checked' : '';
            $_tmp = '<input type="checkbox" name="' . $_key . '" ' . $_checked . ' />';
          }
          break;
        case 'datenull':
          if (preg_match("/:/", $dd[$_key]['extra']['range']))
            list($_start_year, $_end_year)  = preg_split('/:/',$dd[$_key]['extra']['range']);
          if (!isset($_val) || empty($_val)) $_val = '--';;
          $_tmp = smarty_function_html_select_date(array('prefix'=>$_key . '_', 'time'=>$_val, 'start_year'=>$_start_year, 'end_year'=>$_end_year, 'day_empty'=>'--', 'month_empty'=>'--', 'year_empty'=>'--'), $smarty);
          break;
        case 'date':
          if (preg_match("/:/", $dd[$_key]['extra']['range']))
            list($_start_year, $_end_year)  = preg_split('/:/',$dd[$_key]['extra']['range']);
          $_tmp = smarty_function_html_select_date(array('prefix'=>$_key . '_', 'time'=>$_val, 'start_year'=>$_start_year, 'end_year'=>$_end_year), $smarty);
          break;
        case 'time':
          $_tmp = smarty_function_html_select_time(array('prefix'=>$_key . '_', 'time'=>$_val, 'display_seconds'=>false), $smarty);
          break;
        case 'datetime':
          if (preg_match("/:/", $dd[$_key]['extra']['range']))
            list($_start_year, $_end_year)  = preg_split('/:/',$dd[$_key]['extra']['range']);
          $_tmp = smarty_function_html_select_date(array('prefix'=>$_key . '_', 'time'=>$_val, 'start_year'=>$_start_year, 'end_year'=>$_end_year), $smarty);
          $_tmp .= smarty_function_html_select_time(array('prefix'=>$_key . '_', 'time'=>$_val, 'display_seconds'=>false), $smarty);
          break;
        case 'datetimenull':
          if (preg_match("/:/", $dd[$_key]['extra']['range']))
            list($_start_year, $_end_year)  = preg_split('/:/',$dd[$_key]['extra']['range']);
          $_tmp = smarty_function_html_select_date(array('prefix'=>$_key . '_', 'time'=>$_val, 'start_year'=>$_start_year, 'end_year'=>$_end_year, 'day_empty'=>'--', 'month_empty'=>'--', 'year_empty'=>'--'), $smarty);
          $_tmp .= smarty_function_html_select_time(array('prefix'=>$_key . '_', 'time'=>$_val, 'display_seconds'=>false), $smarty);
          break;
        case 'password':
          $_tmp = '<input type="password" name="' . $_key . '" size="20" maxlength="16" />';
          break;
        case 'text':
          $_tmp = '<textarea rows="5" cols="40" name="' . $_key . '">' . $_val . '</textarea> <a href="javascript:edittext(\'' . $name . '\', \'' . $_key . '\', document.forms[\'' . $name . '\'].' . $_key . '.value);">'.ALM_MAX.'</a>';
          break;
        case 'html':
          $_tmp = '<textarea rows="5" cols="40" id="' . $_key . '" name="' . $_key . '">' . $_val . '</textarea> <a href="javascript:edithtml(\'' . $name . '\', \'' . $_key . '\', document.forms[\'' . $name . '\'].' . $_key . '.value);">'.ALM_MAX.'</a>';
          $_tmp .= "<script language=\"JavaScript\">\ngenerate_wysiwyg('" . $_key . "');\n</script>\n";
          break;
        case 'xhtml':
          $_tmp = '<textarea rows="5" cols="40" class="adm" id="' . $_key . '" name="' . $_key . '"'.(($dd[$_key]['extra']['style'])?' style="'.$dd[$_key]['extra']['style'].'"':'').'>' . $_val . '</textarea>';
          $_tmp .= "<script language=\"JavaScript\">\ntinyMCE.execCommand(\"mceAddControl\", true,\"$_key\");\n</script>\n";
          break;
        case 'varchar':
        case 'char':
          if ($dd[$_key]['extra']['list_values']) {
            $_options = $dd[$_key]['extra']['list_values'];
            $_tmp = smarty_function_html_options(array('options'=>$_options, 'selected'=>$_val), $smarty);
            $_tmp = preg_replace("/_REFERENCE_/", $_tmp, FCELLMODREF);
            $_tmp = preg_replace("/_FIELD_/", $_key, $_tmp);
          } else {
            $_val = preg_replace("/\"/", "&quot;", $_val);
            $_tmp = preg_replace("/_VALUE_/", qdollar($_val), FCELLMODSTR);
            $_tmp = preg_replace("/_FIELD_/", $_key, $_tmp);
            $_tmp = preg_replace("/_SIZE_/", $dd[$_key]['size'], $_tmp);
          }
          break;
        case 'numeric':
        case 'int':
          if ($dd[$_key]['extra']['list_values']) {
            $_options = $dd[$_key]['extra']['list_values'];
            $_tmp = smarty_function_html_options(array('options'=>$_options, 'selected'=>$_val), $smarty);
            $_tmp = preg_replace("/_REFERENCE_/", $_tmp, FCELLMODREF);
            $_tmp = preg_replace("/_FIELD_/", $_key, $_tmp);
          } else {
            $_tmp = preg_replace("/_VALUE_/", $_val, FCELLMODSTR);
            $_tmp = preg_replace("/_FIELD_/", $_key, $_tmp);
            $_tmp = preg_replace("/_SIZE_/", 10, $_tmp);
          }
          break;
        case 'references':
          if ($_preset[$_key]) {
            $_tmp = '<input type="hidden" name="' . $_key . '" value="' . $_selected . '" />';
            $_tmp .= $_selected;
          } else {
            $_options = $options[$_key];
            $_tmp = smarty_function_html_options(array('options'=>$_options, 'selected'=>$_selected), $smarty);
            $_tmp = preg_replace("/_REFERENCE_/", qdollar($_tmp), FCELLMODREF);
            $_tmp = preg_replace("/_FIELD_/", $_key, $_tmp);
          }
          break;
        case 'hidden':
        case 'serial':
          $hidden = true;
          $_tmp = '';
          break;
        default:
          $_tmp = $_val;
      }
        if (!empty($dd[$_key]['extra']['help'])) {
          $_tmp .= '<sup title="' . $dd[$_key]['extra']['help'] . '">&nbsp;?&nbsp;</sup>';
        }
      $_tmp = preg_replace("/_FCELL_/", qdollar($_tmp), FROW);
      $_tmp = preg_replace("/_LABEL_/", $labels[$_key], $_tmp);
      if (!$hidden) {
        $_html_rows .= $_tmp;
      }
      $hidden = false;
    }
  } else {
  #
  # Crea las filas del dataform en modo lectura
  #
    foreach ((array)$row as $_key=>$_val) {
      $_tmp = '';
      if (!$dd[$_key]) {
        continue;
      }
      # Tenemos permiso para mostrar este campo?
      if (isset($dd[$_key]['extra']['role']) && $dd[$_key]['extra']['role'] !== $_SESSION['idalm_role']) {
        $dd[$_key]['type'] = 'hidden';
      }
      if ($dd[$_key]['references']) {
        if (!isset($references[$dd[$_key]['references']])) $references[$dd[$_key]['references']] = 0;
        $references[$dd[$_key]['references']]++;
        $n = ($references[$dd[$_key]['references']] == 1) ? '' : $references[$dd[$_key]['references']];
        $_val = $row[$dd[$_key]['references'] . $n];
      }
      switch ($dd[$_key]['type']) {
        case 'char':
          if ($dd[$_key]['extra']['list_values']) {
            $_options = $dd[$_key]['extra']['list_values'];
            $_tmp = $_options[$_val];
          } else {
            $_tmp = smarty_modifier_truncate($_val, 50);
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
            $_icon = $_val;
            $_file =  HOMEDIR . "/_" . $table . "/" . $_val;
            $_icon = 'doc.png';
            $_p = explode('.', $_file);
            $_pc = count($_p);
            if ($_p[$_pc - 1] == 'doc') $_icon = 'doc.png';
            if ($_p[$_pc - 1] == 'pdf') $_icon = 'pdf.png';
            if ($_p[$_pc - 1] == 'xls') $_icon = 'xls.png';
            $_tmp = '<a title="' . $_val . '" href="' . URL . '/files/' . $table. '/' . $_val . '" target="_new"><img src="' . URL . '/' . ALM_URI . '/themes/' . ALM_ADMIN_THEME . '/img/' . $_icon . '" alt="' . $_val  . '" border="0" /></a>';
          } else {
            $_tmp = '--';
          }
          break;
        case 'image':
          if ($_val) {
            if (THUMBNAILING)
              $_tmp = '<a href="javascript:openimage(\'' . URL . '/files/' . $table . '/' . $_val . '\',\'Imagen: ' . $_val . '\')"><img src="'. URL .'/' . ALM_URI . '/pic/50/' . $table . '/' . $_val . '" alt="' . $_val  . '" width="50" border="0" /></a>';
            else
              $_tmp = '<a href="javascript:openimage(\'' . URL . '/files/' . $table . '/' . $_val . '\',\'Imagen: ' . $_val . '\')"><img src="'. URL . '/' . ALM_URI . '/pic/50/' . $table . '/' . $_val . '" alt="' . $_val . '" width="50" border="0" /></a>';
          } else {
            $_tmp = '--';
          } 
          break;
        case 'hidden':
        case 'serial':
          $hidden = true;
          $_tmp = '';
          break;
        default:
          if($dd[$_key]['extra']['list_values']) {
            $_options = $dd[$_key]['extra']['list_values'];
            $_val = $_options[trim($_val)];
          }
          if($_preset[$_key]) {
            $_tmp = '';
            if(!empty($_pre))   $_pre .= ',';
            $_pre .= $_key .'='.$_preset[$_key];
          } else {
            $_tmp = smarty_modifier_wordwrap($_val);
            $_tmp = smarty_modifier_nl2br($_tmp);
          }
      }
      $_tmp = preg_replace('/_FCELL_/', qdollar($_tmp), FROW);
      $_tmp = preg_replace('/_LABEL_/', $labels[$_key], $_tmp);
      if (!$hidden) $_html_rows .= $_tmp;
      $hidden = false;
    }
    $_html_cmd = preg_replace("/{_ID_}/", $row[$key], FCMD);
  }
  if ($type == 2) { $_html_cmd = FCMDADD; $action = "add"; }
  if ($type == 1) { $_html_cmd = FCMDMOD; $action = "save"; }
  if ($type == 0) { $_html_cmd = FCMD; $action = "edit"; }
     
  //if ($cmd) 
    $_html_rows .= $_html_cmd;
  $_i++;
  $_tmp = FROW;
  if (!($_i % 2)) {
    $_tmp = preg_replace("/class=\"dgrow\"/", "class=\"dgrow2\"", $_tmp);
  }
  #$_html_rows .= preg_replace("/_FCELL_/", $_html_row, $_tmp);
  if ($paginate && $maxrows && ($_i >= ($maxrows * $pg)) ) {
    $_need_paginate = true;
    break;
  }
  $_f = F;
  $oldkeys = null;
  foreach($keys as $val) 
    $oldkeys.= '<input type="hidden" name="alm_old_'.$val.'" value="'.$row[$val].'" />';
  $_f = preg_replace("/{_OLDKEYS_}/", $oldkeys, $_f);
  $_f = preg_replace('/_PRESET_/',$_pre,$_f);
  $_html_result = preg_replace("/_FHEADER_/", $_html_labels, $_f);
  if ($cmd)
    $_html_result = preg_replace("/_FHEADERCMD_/", FHEADERCMD, $_html_result);
    $_html_result = preg_replace("/_FHEADERCMD_/", '', $_html_result);
  $_html_result = preg_replace("/_TITLE_/", $title, $_html_result);
  $_html_result = preg_replace("/_FROW_/", qdollar($_html_rows), $_html_result);
  #$_npgs = ceil(count($rows) / $maxrows);
  $_paginate = '';
  if ($paginate && $_npgs > 1) {
    if ($pg != 1)
      $_paginate = PREV;
    for ($_j=1; $_j<=$_npgs; $_j++) {
      $npg = ($_j == $pg) ? CURRENTPG : NPG;
      $_paginate .= preg_replace("/_NPG_/", $_j, $npg);
    }
    if ($pg != $_npgs )
      $_paginate .= NEXT;
  }
  $_html_result = preg_replace("/_PAGINATE_/", $_paginate, $_html_result);
  if ($_SERVER['PHP_SELF'] == '/' . ALM_URI . '/404.php' || $_SERVER['PHP_SELF'] == '/cms/404.php')
    $_html_result = preg_replace("/_SELF_/", SELF, $_html_result);
  else
    $_html_result = preg_replace("/_SELF_/", $_SERVER['PHP_SELF'], $_html_result);
  $_referer = preg_replace("/\//", "\/", $_SERVER['PHP_SELF']);
  
  if (preg_match("/$_referer/", $_SERVER['HTTP_REFERER']))
    $_referer = $_SERVER['PHP_SELF'];
  else
    $_referer = $_SERVER['HTTP_REFERER'];
  
  $_html_result = preg_replace("/_REFERER_/", $_referer, $_html_result);
  $_html_result = preg_replace("/_KEY_/", $key, $_html_result);
  $_html_result = preg_replace("/_KEY1_/", $key1, $_html_result);
  $_html_result = preg_replace("/_KEY2_/", $key2, $_html_result);
  $_html_result = preg_replace("/{_ID_}/", $_REQUEST[$key], $_html_result);
  $_html_result = preg_replace("/_ID1_/", $_REQUEST[$key1], $_html_result);
  $_html_result = preg_replace("/_ID2_/", $_REQUEST[$key2], $_html_result);
  $_html_result = preg_replace("/_SORT_/", $_REQUEST['sort'], $_html_result);
  $_html_result = preg_replace("/_PG_/", $_REQUEST[$object . 'pg'], $_html_result);
  $_html_result = preg_replace("/_PGPREV_/", ($pg-1), $_html_result);
  $_html_result = preg_replace("/_PGNEXT_/", ($pg+1), $_html_result);
  $_html_result = preg_replace("/_FORM_/", $name, $_html_result);
  $_html_result = preg_replace("/_OBJECT_/", $object, $_html_result);
  $_html_result = preg_replace("/_ACTION_/", $action, $_html_result);
  if ($type == 0) $_html_result = preg_replace('/alm_old_/', '', $_html_result);
  

  if(strpos($_html_rows,'value="Agregar"') != '') {
  	  $_SESSION['accion'] = 'add';
  } elseif(isset($_SESSION['accion'])) {
  	  unset($_SESSION['accion']);
  } 
  
  
  
  return $_html_result;

}
