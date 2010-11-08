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
 *       - keys       (primary keys) - array
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

require(dirname(__FILE__) . '/shared.lang.php');
require(dirname(__FILE__) . '/define.datagrid.php');

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
  $keys = array();
  $maxrows = (defined('MAXROWS')) ? MAXROWS : 5;
  $maxcols = (defined('MAXCOLS')) ? MAXCOLS : 5;
  $name = 'dgform';
  $table = null;
  $parent = null;
  $truncate = true;
  $is_child = null;
  $have_child = null;

  $extra = '';
  foreach($params as $_key => $_val) {
    switch($_key) {
      case 'name':
      case 'title':
      case 'parent':
        $$_key = (string)$_val;
        break;
      case 'options':
      case 'rows':
      case 'dd':
      case 'keys':
        $$_key = (array)$_val;
        break;
      case 'paginate':
      case 'search':
      case 'cmd':
      case 'truncate':
      case 'have_child':
      case 'is_child':
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
 
  $_html_search = ''; 
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
        if(!isset($references[$dd[$_key]['references']]))
          $references[$dd[$_key]['references']] = 0;
        if ($dd[$_key]['references'] == $name && !$references[$dd[$_key]['references']])
          $references[$dd[$_key]['references']]+=2;
        else
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
    $_chosen = true;
    foreach($keys as $val) {
      if ($_REQUEST[$val] !== $row[$val]) {
        $_chosen = false;
        break;
      }
    }
    if ($_REQUEST['f'] == $name && $_REQUEST['action'] == 'mod' && $_chosen) {
      $selected_row = $row;
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
          if(!isset($references[$dd[$_key]['references']]))
            $references[$dd[$_key]['references']] = 0;
          if ($dd[$_key]['references'] == $name && !$references[$dd[$_key]['references']])
            $references[$dd[$_key]['references']]+=2;
          else
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
            if ($_val) $_tmp = '<input type="checkbox" checked name="' . $_key . '_keep" /> ' . ALM_KEEP_FILE . ' (' . $_val . ')<br /><img src="'.URL.'/cms/pic/50/'. $table . '/' . $_val . '" alt="' . $_val  . '" width="50" border="0" /><br />';
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
          case 'xhtml':
            $_tmp = '<textarea rows="5" cols="40" class="adm" id="grid' . $_key . '" name="' . $_key . '"'.(($dd[$_key]['extra']['style'])?' style="'.$dd[$_key]['extra']['style'].'"':'').'>' . $_val . '</textarea>';
            $_tmp .= "<script language=\"JavaScript\">\ntinyMCE.execCommand(\"mceAddControl\", true,\"grid$_key\");\n</script>\n";
	    break;
          case 'varchar':
          case 'char':
            if ($dd[$_key]['extra']['list_values']) {
              $_options = $dd[$_key]['extra']['list_values'];
              $_tmp = smarty_function_html_options(array('options'=>$_options, 'selected'=>trim($_val)), $smarty);
              $_tmp = preg_replace("/_REFERENCE_/", qdollar($_tmp), DGCELLMODREF);
              $_tmp = preg_replace("/_FIELD_/", $_key, $_tmp);
            } else {
              $_tmp = preg_replace("/_VALUE_/",  qdollar(htmlentities($_val,ENT_COMPAT,'UTF-8')), DGCELLMODSTR);
              $_tmp = preg_replace("/_FIELD_/", $_key, $_tmp);
              $_tmp = preg_replace("/_SIZE_/", $dd[$_key]['size'], $_tmp);
            }
            break;
          case 'int':
          case 'numeric':
            if ($dd[$_key]['extra']['list_values']) {
              $_options = $dd[$_key]['extra']['list_values'];
              $_tmp = smarty_function_html_options(array('options'=>$_options, 'selected'=>trim($_val)), $smarty);
              $_tmp = preg_replace("/_REFERENCE_/", qdollar($_tmp), DGCELLMODREF);
              $_tmp = preg_replace("/_FIELD_/", $_key, $_tmp);
            } else {
              $_tmp = preg_replace("/_VALUE_/", $_val, DGCELLMODSTR);
              $_tmp = preg_replace("/_FIELD_/", $_key, $_tmp);
              $_tmp = preg_replace("/_SIZE_/", 10, $_tmp);
            }
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
      if($_SESSION['credentials'][$table] == 'full' || $_SESSION['credentials'][$table] == 'edit' || $_SESSION['idalm_user'] === 'admin'){
        $_dgcmdmod = DGCMDMOD;
      }
      /*
      $key_id = null;
      foreach($keys as $val)
        $key_id[] = $val .'='. $row[$val];
      $_html_cmd = preg_replace("/{_IDS_}/", 'array('. join(',',$key_id) .')', $_dgcmdmod);
      */
      $_html_cmd = $_dgcmdmod;
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
          if(!isset($references[$dd[$_key]['references']]))
            $references[$dd[$_key]['references']] = 0;
          if ($dd[$_key]['references'] == $name && !$references[$dd[$_key]['references']])
            $references[$dd[$_key]['references']]+=2;
          else
            $references[$dd[$_key]['references']]++;
          $n = ($references[$dd[$_key]['references']] == 1) ? '' : $references[$dd[$_key]['references']];
          $_val = empty($_val)?'--':$row[$dd[$_key]['references'] . $n];
        }
        switch ($dd[$_key]['type']) {
          case 'varchar':
          case 'char':
            if ($dd[$_key]['extra']['list_values']) {
              $_options = $dd[$_key]['extra']['list_values'];
              $_tmp = $_options[trim($_val)];
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
            $_tmp = '';
            if ($_SESSION[$name . 'first'] != $row[$key]) $_tmp .= '<a href="_SELF_?action=move&'.$key.'='.$row[$key].'&sense=up&key=' . $_key . '"><img src="/cms/img/up.gif" border="0"/></a>';
            if ($_SESSION[$name . 'last'] != $row[$key]) {
              if(!empty($_tmp)) $tmp = ' ';
              $_tmp .= '<a href="_SELF_?action=move&'.$key.'='.$row[$key].'&sense=down&key=' . $_key . '"><img src="/cms/img/down.gif" border="0"/></a>';
            }
            if(empty($_tmp)) $_tmp = '--';
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
            $_val = $_val===''?"&nbsp;":$_val;
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
      if($_cols <= 3 || $parent) {
        if($_SESSION['credentials'][$table] == 'full' || $_SESSION['idalm_user'] === 'admin'){
          $_dgcmd =  DGCMDR;
        }elseif($_SESSION['credentials'][$table] == 'edit'){
          $_dgcmd =  DGCMDREDIT;
        }elseif($_SESSION['credentials'][$table] == 'delete'){
          $_dgcmd =  DGCMDRDEL;
        }elseif($_SESSION['credentials'][$table] == 'read'){
          $_dgcmd =  DGCMDRVER;
        }
      } else {
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
      $ids = null;
      $key_id_url = null;
      $key_id_js = null;
      foreach($keys as $val) {
        $key_id_url[] = $val .'='. $row[$val];
        $key_id_js[] = "'$val':'".$row[$val]."'";
        $ids[] = "'".$row[$val]."'";
      }
      $_html_cmd = preg_replace("/{_KEY=ID_}/", join('&amp;',$key_id_url), $_dgcmd);
      $_html_cmd = preg_replace("/{_KEY:ID_}/", '{' . join(',',$key_id_js) .'}', $_html_cmd);
      $_html_cmd = preg_replace("/{_IDS_}/", '['. join(',',$ids) .']', $_html_cmd);
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
  $_dg = DG;
  $oldkeys = null;
  foreach($keys as $val)
    $oldkeys.= '<input type="hidden" name="alm_old_'.$val.'" value="'.$selected_row[$val].'" />';
  $_dg = preg_replace("/{_OLDKEYS_}/", $oldkeys, $_dg);
  $_html_result = preg_replace("/_DGHEADER_/", $_html_headers, $_dg);
  if ($search === true) {
    $_dgsearch = preg_replace("/{_Q_}/", htmlentities($_REQUEST['q']), DGSEARCH);
    $_html_result = preg_replace("/{_DGSEARCH_}/", $_dgsearch, $_html_result);
  } else {
    $_html_result = preg_replace("/{_DGSEARCH_}/", '', $_html_result);
  }
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
  if ($_SERVER['PHP_SELF'] == '/almidon/404.php' || $_SERVER['PHP_SELF'] == '/cms/404.php')
    $_html_result = preg_replace("/_SELF_/", SELF, $_html_result);
  else
    $_html_result = preg_replace("/_SELF_/", $_SERVER['PHP_SELF'], $_html_result);
  $akeys = null;
  foreach($keys as $val)
    $akeys[] = "'".$val."'";
  $_html_result = preg_replace("/{_KEYS_}/", '['. join(',',$akeys) .']',  $_html_result);
  #$_html_result = preg_replace("/{_ID_}/", $_REQUEST[$key], $_html_result);
  $_html_result = preg_replace("/_SORT_/", $_SESSION[$name . 'sort'], $_html_result);
  $_html_result = preg_replace("/_PG_/", $_SESSION[$name . 'pg'], $_html_result);
  $_html_result = preg_replace("/_PGPREV_/", ($pg-1), $_html_result);
  $_html_result = preg_replace("/_PGNEXT_/", ($pg+1), $_html_result);
  $_html_result = preg_replace("/_MAXCOLS_/", $maxcols, $_html_result);
  $_html_result = preg_replace("/_FORM_/", $name, $_html_result);
  $_html_result = preg_replace("/_Q_/", $_REQUEST['q'], $_html_result);
  
  return $_html_result;

}
