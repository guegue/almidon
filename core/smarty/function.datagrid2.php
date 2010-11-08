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
require(dirname(__FILE__) . '/define.datagrid.php');
//para las tablas detalle
define('DGCMD_det', '<td class="dgcmd"><a class="dgcmd_link" href="javascript:openwindow(\'./_FORM_.php?parent=_PARENT_=_PARENTID_&amp;f=_FORM_&amp;action=record&amp;_KEY_={_ID_}\');"><img src="/almidon/img/view.png" border="0" title="'. ALM_VIEW_LB .'" alt="'. ALM_VIEW_LB .'"/></a> <a href="javascript:confirm_delete_det(\'_FORM_\',\'_KEY_\',\'{_ID_}\',\'{_ID_}\');"><img src="/almidon/img/delete.png" height="16" width="16" border="0" title="'. ALM_DEL_LB .'" alt="' . ALM_DEL_LB . '"/></a></td>');
define('DGCMD_NOSHORT_EDIT', '<td class="dgcmd"><a class="dgcmd_link" href="_SELF_?f=_FORM_&amp;action=record&amp;_KEY_={_ID_}&amp;_FORM_pg=_PG_"><img src="/almidon/img/view.png" border="0" title="'. ALM_VIEW_LB .'" alt="'. ALM_VIEW_LB .'"/></a> <a href="javascript:confirm_delete(\'_FORM_\',\'_KEY_\',\'{_ID_}\',\'{_ID_}\');"><img src="/almidon/img/delete.png" height="16" width="16" border="0" title="'. ALM_DEL_LB .'" alt="'. ALM_DEL_LB .'"/></a></td>');
//para las tablas detalle
define('DGCMDMOD', '<td class="dgcmd"><a href="_SELF_?f=_FORM_&amp;_KEY_={_ID_}&amp;_FORM_pg=_PG_&amp;_FORM_sort=_SORT_&amp;_PARENT_=_PARENTID_"><img src="/almidon/img/cancel.png" border="0" title="'. ALM_CAN_LB .'" alt="'. ALM_CAN_LB .'"></a> <a href="javascript:postBack(document._FORM_, \'dgsave\');"><img src="/almidon/img/save.png" border="0" title="'. ALM_SAVE_LB .'" alt="'. ALM_SAVE_LB .'"></a></td>');
define('PREV','<a href="_SELF_?q=_Q_&amp;f=_FORM_&amp;_FORM_sort=_SORT_&amp;_FORM_pg=_PGPREV_">&lt; '. ALM_PREV_LB .'</a> |');
define('NEXT','| <a href="_SELF_?q=_Q_&amp;f=_FORM_&amp;_FORM_sort=_SORT_&amp;_FORM_pg=_PGNEXT_">'. ALM_NEXT_LB .' &gt;</a>&nbsp;');
define('NPG','<a href="_SELF_?q=_Q_&amp;f=_FORM_&amp;_FORM_sort=_SORT_&amp;_FORM_pg=_NPG_"> _NPG_ </a>');
define('CURRENTPG','<strong>_NPG_</strong>');
define('PAGINATE','<table><tr><td nowrap><br>_PGS_<br></td></tr></table>');
  

function smarty_function_datagrid2($params, &$smarty)
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
  $is_child = null;
  $have_child = null;
  
  $extra = '';
  foreach($params as $_key => $_val) {
    switch($_key) {
      case 'name':
      case 'title':
      case 'key':
      case 'key1':
      case 'key2':
      case 'parent':
      case 'search':
        $$_key = (string)$_val;
        break;
      case 'options':
      case 'rows':
      case 'dd':
        $$_key = (array)$_val;
        break;
      case 'shortEdit':
        if(isset($params[$_key])) $$_key = (bool)$_val;
        break;
      case 'paginate':
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

  if (empty($rows) && empty($search)) {
    #$smarty->trigger_error("datagrid: rows attribute must be present", E_USER_NOTICE);
    return ALM_NODATA; /* raise error here? */
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
  
  # Crea el Buscador del datagrid
  if(!empty($search)) {
    $_html_search = preg_replace("/_FIELDS_/", 'Filtro', DS);
    $fields = preg_split('/,/',trim($search));
    $_html_sch = '';
    if($fields)
      foreach($fields as $field) {
        $_tmp = '';
        if(!empty($_html_sch)) $_html_sch .= ' | ';
        switch($dd[$field]['type']) {
          case 'varchar':
            $_tmp = preg_replace("/_LABEL_/",$dd[$field]['label'],DSLABEL);
            $_tmp .= '&nbsp;' . preg_replace("/_VALUE_/", '', DGCELLMODSTR);
            $_tmp = preg_replace("/_FIELD_/", $field.'search', $_tmp);
            $_tmp = preg_replace("/_SIZE_/", $dd[$field]['size'], $_tmp);
            if($dd[$field]['extra']['autocomplete']) {
              $_tmp .= '<script type="text/javascript">
                          $(\'#' . $field . 'search\').autocomplete({ serviceUrl:' . ($dd[$field]['extra']['autocomplete_sr']?$dd[$field]['extra']['autocomplete_sr']:'\'/almidon/js/autocomplete/autocomplete.php\'') . ', delimiter: /(,|;)\s*/, params: { table:\'' . ($dd[$field]['extra']['autocomplete_tb']?$dd[$field]['extra']['autocomplete_tb']:$table) . '\', field:\'' . ($dd[$field]['extra']['autocomplete_fd']?$dd[$field]['extra']['autocomplete_fd']:($dd[$field]['extra']['autocomplete_tb']?$dd[$field]['extra']['autocomplete_tb']:$field)) . '\' } });
                        </script>';
            }
            break;
        }
        $_html_sch .= $_tmp;
      }
    $_html_search = preg_replace("/_DSFIELDS_/", $_html_sch,$_html_search);
  }

  # Crea los encabezados del datagrid
  if(!empty($rows)) {
    $_cols = 0;
    if (!empty($headers)) {
      foreach ($headers as $_key=>$_val) {
        if ($maxcols && ($_cols >= $maxcols))
          break;
        if ($parent == $_key || $dd[$_key]['type'] == 'external')
          continue;
        $_html_header = DGHEADERCELL;
        $_field =  ($dd[$_key]['references']) ?  ($dd[$_key]['references']) : $_key;
        if ($_SESSION[$name . 'sort'] == $_field) {
          $_img = '<img src="/almidon/img/up.gif" border="0" />';
          $_html_header = preg_replace("/_DESC_/", ' desc', $_html_header);
          $_html_header = preg_replace("/_SORTIMG_/", $_img, $_html_header);
        } else {
          $_html_header = preg_replace("/_DESC_/", '', $_html_header);
        }
        if ($_SESSION[$name . 'sort'] == $_field . ' desc') {
          $_img = '<img src="/almidon/img/down.gif" border="0" />';
          $_html_header = preg_replace("/_SORTIMG_/", $_img, $_html_header);
        } else {
          $_html_header = preg_replace("/_SORTIMG_/", '', $_html_header);
        }
        $_html_header = preg_replace("/_LABEL_/", $_val, $_html_header);
        $_html_header = preg_replace("/_FIELD_/", $_field, $_html_header);
        $_html_headers .= $_html_header;
        ++$_cols;
      }
    } else {
      foreach ($rows[0] as $_key=>$_val) {
        if ($maxcols && ($_cols >= $maxcols))
          break;
        $_html_header = preg_replace("/_LABEL_|_FIELD_/", $_key, DGHEADERCELL);
        $_html_headers .= $_html_header;
        ++$_cols;
      }
    }
  
    # Crea las filas del datagrid
    $_i = 0;
    $pg = ($_SESSION[$name . 'pg']) ? $_SESSION[$name . 'pg'] : 1; 
    foreach ((array)$rows as $row) {
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
          if ($parent == $_key) {
            $parentid = $_val;
            $dd[$_key]['type'] = 'hidden';
          } elseif ($dd[$_key]['references']) {
            $_selected = $_val;
            $_val = $row[$dd[$_key]['references']];
            $dd[$_key]['type'] = 'references';
          }
          switch ($dd[$_key]['type']) {
            case 'hidden':
              $_tmp = '<input type="hidden" name="'.$_key.'" value="'.$_val.'"  />';
              break;
            case 'file':
            case 'image':
            case 'img':
              $_tmp = '';
              if ($_val) $_tmp = '<input type="checkbox" checked name="' . $_key . '_keep" /> Conservar archivo actual (' . $_val . ')<br /><img src="' . URL .'/almidon/pic/50/'. $table . '/' . $_val . '" alt="' . $_val  . '" width="50" border="0" /><br />';
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
              $_tmp = preg_replace("/_VALUE_/", qdollar($_val), DGCELLMODTXT);
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
                $_tmp = preg_replace("/_VALUE_/", qdollar($_val), DGCELLMODSTR);
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
              $_tmp = preg_replace("/_REFERENCE_/", $_tmp, DGCELLMODREF);
              $_tmp = preg_replace("/_FIELD_/", $_key, $_tmp);
              break;
            case 'order':
              $_tmp = '- +';
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
        $_dgcmdmod = ($key2) ? DGCMD2MOD : DGCMDMOD;
        $_html_cmd = preg_replace("/{_ID_}/", $row[$key], $_dgcmdmod);
      } else {
        $_cols = 0;
        foreach ($row as $_key=>$_val) {
          if ($maxcols && ($_cols >= $maxcols))
            break;
          if (!$dd[$_key] || $dd[$_key]['type'] == 'external') {
            continue;
          }
          if ($parent == $_key) {
            $parentid = $_val;
            continue;
          }
          if ($dd[$_key]['references']) {
	    $pos = strpos($dd[$_key]['references'],'.');
            if($pos!==false) {
              $r_table = substr($dd[$_key]['references'],0,$pos);
              $pos_2 = strpos($dd[$_key]['references'],'[');
              if($pos_2!==false) {
                $r_field = substr($dd[$_key]['references'],$pos_2+1,strlen($dd[$_key]['references'])-($pos_2+2));
              }else{
                $r_field = $r_table;
	      }
	      $_val = $row[$r_field];
	    }else $_val = $row[$dd[$_key]['references']];
          }
          switch ($dd[$_key]['type']) {
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
	    case 'video':
	      if($_val) {
                require_once $smarty->_get_plugin_filepath('shared','video_service');

                $a_vs = smarty_function_video_service();
                //      XML
                require_once 'XML/Unserializer.php';
                $options = array(
                  XML_UNSERIALIZER_OPTION_ATTRIBUTES_PARSE    => true,
                  XML_UNSERIALIZER_OPTION_ATTRIBUTES_ARRAYKEY => false
                );
                $unserializer = new XML_Unserializer($options);
                $status = $unserializer->unserialize($_val, false);
                if (PEAR::isError($status)){
                  echo 'Error: ' . $status->getMessage();
                }else{ 
                  $vs = $unserializer->getUnserializedData();
                }
                $_tmp = '<a href="javascript:openwindow(\'' . URL . '/almidon/video.php?src='.$vs['src'].'&type='.$vs['tipo'].'\',400,333)"><img src="/almidon/img/'.$vs['tipo'].'.png" alt="'.$a_vs[$vs['tipo']].'" title="'.$a_vs[$vs['tipo']].'" border="0" /></a>';
	      } else
	        $_tmp = '--';
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
                $_tmp = '<a href="' . URL . '/files/' . $table. '/' . $_val . '" target="_new"><img src="/almidon/img/' . $_icon . '" alt="' . $_val  . '" border="0" /></a>';
              } else {
                $_tmp = '--';
              }
              break;
            case 'image':
              if ($_val) {
                if (THUMBNAILING)
                  $_tmp = '<a href="javascript:openimage(\'' . URL . '/files/' . $table . '/' . $_val . '\',\'Imagen: ' . $_val . '\')"><img src="'. URL .'/almidon/pic/50/' . $table . '/' . $_val . '" alt="' . $_val  . '" width="50" border="0" /></a>';
                else
                  $_tmp = '<a href="javascript:openimage(\'/files/' . $table . '/' . $_val . '\',\'Imagen: ' . $_val . '\')"><img src="/_' . $table . '/' . $_val . '" alt="' . $_val  . '" width="50" height="20" border="0" /></a>';
              } else {
                $_tmp = '--';
              } 
              break;
            case 'order':
              $_tmp = '';
              if ($_SESSION[$name . 'first'] != $row[$key]) $_tmp .= '<a href="_SELF_?action=move&'.$key.'='.$row[$key].'&sense=up&key=' . $_key . '"><img src="/almidon/img/up.gif" border="0"/></a>';
              if ($_SESSION[$name . 'last'] != $row[$key]) {
                if(!empty($_tmp)) $tmp = ' ';
                $_tmp .= '<a href="_SELF_?action=move&'.$key.'='.$row[$key].'&sense=down&key=' . $_key . '"><img src="/almidon/img/down.gif" border="0"/></a>';
              }
              if(empty($_tmp)) $_tmp = '--';
              break;
            case 'text':
     	    case 'html':
            case 'xhtml':
              # strip_tags quita los tags [x]html, preg_replace reemplaza los &nbsp; por espacio en blanco, el otro preg_replace quita mas de un espacio en blanco conjunto y lo reemplaza por un solo espacio y trim quita los espacios en blanco al final e inicio de la cadena.
	      $_val = trim(preg_replace('/\s\s+/',' ',preg_replace('/&nbsp;/',' ',strip_tags($_val))));
	    case 'varchar':
	      if ($dd[$_key]['extra']['list_values']) {
                $_options = $dd[$_key]['extra']['list_values'];
                $_tmp = $_options[trim($_val)];
	        break;
              }
            default:
              if ($truncate)
                $_tmp = smarty_modifier_truncate($_val, 50);
              else
                $_tmp = smarty_modifier_wordwrap($_val, 50, "<br/>");
              $_tmp = smarty_modifier_url($_tmp);
          }
          $_html_row .= preg_replace("/_VALUE_/", qdollar($_tmp), DGCELL);
          $_cols++;
        }
        if ($key2)
          $_dgcmd = ($_cols <= 3 || $parent) ? DGCMD2R : DGCMD2;
        elseif ($is_child) {
          $_dgcmd = DGCMD_det;
        } else
          $_dgcmd = (($_cols <= 3 || $parent) && !$have_child) ? ($shortEdit===false?DGCMD_NOSHORT_EDIT:DGCMDR) : DGCMD;
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
    if(!empty($search))  $_html_result = $_html_search . $_html_result;
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
    # christian | a new way to know if this pages is the 404.php file
    $params = preg_split('/\//',$_SERVER['PHP_SELF']);
    $page = $params[count($params) - 1];
    # end
    if ($page == '404.php' || $page == '404c.php')
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
  } else {
    if(!empty($search))  {
      $_html_result = $_html_search;
      if($_SESSION[$table . 'query']) $_html_result .= DSL . "<br />";
    }
    # christian | a new way to know if this pages is the 404.php file
    $params = preg_split('/\//',$_SERVER['PHP_SELF']);
    $page = $params[count($params) - 1];
    # end
    if ($page == '404.php' || $page == '404c.php')
      $_html_result = preg_replace("/_SELF_/", SELF, $_html_result);
    else
      $_html_result = preg_replace("/_SELF_/", $_SERVER['PHP_SELF'], $_html_result);
    $_html_result = preg_replace("/_DSQUERY_/",$_SESSION[$table . 'ssearch'],$_html_result);
    $_html_result .= ALM_NODATA;
  }
  
  return $_html_result;

}
