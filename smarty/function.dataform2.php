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
 *       - key        (required primary key) - string
 *       - key1       (required primary key) - string
 *       - key2       (required primary key) - string
 *       - title      - string
 *       - options    data for select menus - associative array
 *       - edit       allow editing of data - bool
 *       - cmd        show dataform commands - int 0=none 1=save/cancel 2=change/cance 3=add/none
 *       - object     name of the object to save - string
 *       - table      table o image dir - string
 *       - preset     id already set
 *       - is_detail  it is a detail form
 * Purpose:  Prints dataform from datasource
 *       according to the passed parameters
 * @param array
 * @param Smarty
 * @return string
 * @uses smarty_function_escape_special_chars()
 */

include dirname(__FILE__) . '/shared.lang.php';

define('F', 
  '<form action="_SELF_" method="post" name="_FORM_" enctype="multipart/form-data">
  <input type="hidden" name="old__KEY_" value="{_ID_}"/>
  <input type="hidden" name="_KEY_" value="{_ID_}"/>
  <input type="hidden" name="f" value="_FORM_"/>
  <input type="hidden" name="o" value="_OBJECT_"/>
  <input type="hidden" name="action" value="_ACTION_"/>
  <input type="hidden" name="_OBJECT_pg" value="_PG_"/>
  _PRESET_
  <table class="dgtable" border="0" cellspacing="0" cellpadding="2"><tr class="adm"><th class="adm">_TITLE_</th></tr>
  <tr class="adm"><td class="adm"><table class="dgsubtable" border="0" cellspacing="0" cellpadding="0">
  _FROW_</table></td></tr><tr class="adm"><td class="adm">_PAGINATE_</td></tr></table></form>');
define('F2', 
  '<form action="_SELF_" method="post" name="_FORM_">
  <input type="hidden" name="old__KEY1_" value="_ID1_"/>
  <input type="hidden" name="old__KEY2_" value="_ID2_"/>
  <input type="hidden" name="f" value="_FORM_"/>
  <input type="hidden" name="o" value="_OBJECT_"/>
  <input type="hidden" name="action" value="_ACTION_"/>
  _PRESET_
  <table class="dgtable" border="0" cellspacing="0" cellpadding="2"><tr class="adm"><th class="adm">_TITLE_</th></tr>
  <tr class="adm"><td class="adm"><table class="dgsubtable" border="0" cellspacing="0" cellpadding="0">
  _FROW_</table></td></tr><tr class="adm"><td class="adm">_PAGINATE_</td></tr></table></form>');
define('FHEADERCMD', '<th class="adm">'. ALM_OPT_LB .'</th>');
define('FHEADERCELL', '<th class="adm"><a class="dgheader_link" href="_SELF_?f=_FORM_&amp;sort=_FIELD_">_LABEL_</a></th>'."\n");
define('FROW', '<tr valign="top" class="dgrow"><td class="dgcell">_LABEL_</td> <td class="dgcell">_FCELL_</td></tr>'."\n");
define('FCELLMODSTR', '<input type="text" name="_FIELD_" id="_FIELD_" value="_VALUE_" size="30" maxlength="_SIZE_"/>');
define('FCELLHIDEN', '_LABEL_<input type="hidden" name="_FIELD_" value="_VALUE_" />');
define('FCELLMODREF', '<select name="_FIELD_" id="_FIELD_" onchange="_CHANGE_"><option value="-1">--</option>_REFERENCE_</select>');
define('FCMD',
  '<tr class="adm"><td class="dgcmd"><input type="submit" value="'. ALM_EDIT_LB .'" /></td> <td class="dgcmd"><input type="button" value="'. ALM_CAN_LB .'" onclick="location.href=\'_REFERER_\'" /></td></tr>');
define('FCMDMOD',
  '<tr class="adm"><td class="dgcmd"><input type="submit" value="'. ALM_SAVE_LB .'" /></td> <td class="dgcmd"><input type="button" value="'. ALM_CAN_LB .'" onclick="location.href=\'_REFERER_\'" /></td></tr>');
define('FCMDMOD_det',
  '<tr class="adm"><td class="dgcmd"><input type="submit" value="'. ALM_SAVE_LB .'" /></td> <td class="dgcmd"><input type="button" value="'. ALM_CAN_LB .'" onclick="location.href=\'_REFERER_\'" /></td></tr>');
define('FCMDADD', '<tr class="adm"><td class="dgcmd"><input type="submit" value="'. ALM_ADD_LB .'" /></td></tr>');
define('PREV','<a href="_SELF_?f=_FORM_&amp;sort=_SORT_&amp;pg=_PGPREV_">&lt; '. ALM_PREV_LB .'</a> |');
define('NEXT','| <a href="_SELF_?f=_FORM_&amp;sort=_SORT_&amp;pg=_PGNEXT_">'. ALM_NEXT_LB .' &gt;</a>&nbsp;');
define('NPG','<a href="_SELF_?f=_FORM_&amp;sort=_SORT_&amp;pg=_NPG_"> _NPG_ </a>');
define('CURRENTPG','<strong>_NPG_</strong>');
define('PAGINATE','<table><tr class="adm"><td nowrap><br>_PGS_<br></td></tr></table>');

require_once('rteSafe.php');

function smarty_function_dataform2($params, &$smarty)
{
  require_once $smarty->_get_plugin_filepath('shared','escape_special_chars');
  require_once $smarty->_get_plugin_filepath('shared','video_service');
  require_once $smarty->_get_plugin_filepath('function','html_options');
  require_once $smarty->_get_plugin_filepath('function','html_select_date');
  require_once $smarty->_get_plugin_filepath('function','html_select_time');
  require_once $smarty->_get_plugin_filepath('modifier','truncate');
  require_once $smarty->_get_plugin_filepath('modifier','nl2br');
  require_once $smarty->_get_plugin_filepath('modifier','wordwrap');
  require_once $smarty->_get_plugin_filepath('modifier','return_bytes');
  require_once $smarty->_get_plugin_filepath('modifier','fsize_format');
  $row = array();
  $dd = array();
  $options = array();
  $key = null;
  $key1 = null;
  $key2 = null;
  $type = 0;
  $name = 'dfform';
  $object = null;
  $edit = false;
  $table = null;
  $preset = null;
  $is_detail = false;
  
  $extra = '';
  foreach($params as $_key => $_val) {
    switch($_key) {
      case 'name':
      case 'object':
      case 'title':
      case 'key':
      case 'key1':
      case 'key2':
      case 'preset':
        $$_key = (string)$_val;
        break;
      case 'options':
      case 'row':
      case 'dd':
        $$_key = (array)$_val;
        break;
      case 'paginate':
      case 'is_detail':
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

  $h_f = ($key2) ? F2 : F;
  # Determinar el tamaño máximo de los archivo
  $upload = smarty_modifier_return_bytes(ini_get('upload_max_filesize'));
  $post = smarty_modifier_return_bytes(ini_get('post_max_size'));
  $max_size = 0;
  if($upload<$post)
    $max_size = $upload;
  else $max_size = $post;
  $max_size = smarty_modifier_fsize_format($max_size);

  # Crea las filas del dataform
  $_i = 0;
  // Para los preset
  $_pre = '';
  if ($type) {
    foreach ((array)$dd as $_key=>$_val) {
      if (!$dd[$_key]) {
        continue;
      }
      if ($type == 2) $_val = '';
      if ($type == 1) $_val = $row[$_val['name']];
      if ($dd[$_key]['references'] && $dd[$_key]['type'] != 'auto' && $dd[$_key]['type'] != 'hidden') {
        if ($_preset[$_key]) {
          $_selected = $_preset[$_key];
        } else {
          $_selected = $_val;
          $_val = $row[$dd[$_key]['references']];
        }
        $dd[$_key]['type'] = 'references';
      }
      $_start_year = "-10";
      $_end_year = "+10";
      $_ispreset = false;
      
      switch ($dd[$_key]['type']) {
	//Add
	case 'video':
	  $_tmp = '';
	  $_options = smarty_function_video_service();
          if ($_val) {
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
            //  -----
	    $_tmp .= smarty_function_html_options(array('name'=>$_key.'_type','options'=>$_options,'selected'=>$vs['tipo']), $smarty).'<input type="text" size="25" name="'.$_key.'_src" maxlength="500" value="'.$vs['src'].'" />'; 
          } else {
	    $_tmp .= smarty_function_html_options(array('name'=>$_key.'_type','options'=>$_options,'selected'=>$_val), $smarty).'<input type="text" size="25" name="'.$_key.'_src" maxlength="500" />'; 
          }
	  break;
 	//End Add
        case 'auto':
          if($dd[$_key]['extra']['default']) $_tmp = '<span class="auto">' . $dd[$_key]['extra']['default'] . '</span>';
          else $_tmp = !empty($_val)?$_val:'';
          break;
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
            $_tmp = '<input type="checkbox" checked name="' . $_key . '_keep" /> Conservar archivo actual (' . $_val . ')<br /><img src="/cms/img/' . $_icon . '" alt="' . $_val  . '" border="0" /><br />';
          }
          $_tmp .= '[' . $max_size . ']<input type="file" name="' . $_key . '" value="' .$_val . '" />';
          break;
        case 'image':
        case 'img':
          $_tmp = '';
          $_icon = 'image.png';
          $_tmp = '[' . $max_size . ']<input name="old_'.$_key.'" type="hidden" value="'.$_val.'" /><img src="/cms/img/' .$_icon . '" border="0" alt="Imagen" title="Imagen" />';
          if ($_val) $_tmp .= '<input type="checkbox" checked name="' . $_key . '_keep" /> Conservar archivo actual (' . $_val . ')<br /><img src="' . URL . '/cms/pic/50/' . $table . '/' . $_val . '" alt="' . $_val  . '" width="50" border="0" /><br />';
          $_tmp .= '<input type="file" name="' . $_key . '" value="' .$_val . '" />';
          break;
        case 'boolean':
        case 'bool':
          if (preg_match("/:/", $dd[$_key]['extra']['range'])) {
            list($_si, $_no)  = preg_split('/:/',$dd[$_key]['extra']['range']);
            $_tchecked = ($_val == 't') ? "checked" : "";
            $_fchecked = ($_val == 'f') ? "checked" : "";
            $_tmp = $_si . '<input type="radio" name="' . $_key . '" ' . $_tchecked . ' value="on" />' . $_no . '<input type="radio" name="' . $_key . '" ' . $_fchecked .' value="" />';
          } else {
            $_checked = ($_val == 't') ? "checked" : "";
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
          if(!$_val)  $_val = $dd[$_key]['extra']['default'];
          if (preg_match("/:/", $dd[$_key]['extra']['range']))
            list($_start_year, $_end_year)  = preg_split('/:/',$dd[$_key]['extra']['range']);
          $_tmp = smarty_function_html_select_date(array('prefix'=>$_key . '_', 'time'=>$_val, 'start_year'=>$_start_year, 'end_year'=>$_end_year), $smarty);
          break;
        case 'time':
          $_tmp = smarty_function_html_select_time(array('prefix'=>$_key . '_', 'time'=>$_val, 'display_seconds'=>false), $smarty);
          break;
        case 'datetime':
          if(!$_val)  $_val = $dd[$_key]['extra']['default'];
          if (preg_match("/:/", $dd[$_key]['extra']['range']))
            list($_start_year, $_end_year)  = preg_split('/:/',$dd[$_key]['extra']['range']);
          $_tmp = smarty_function_html_select_date(array('prefix'=>$_key . '_', 'time'=>$_val, 'start_year'=>$_start_year, 'end_year'=>$_end_year), $smarty);
          $_tmp .= smarty_function_html_select_time(array('prefix'=>$_key . '_', 'time'=>$_val, 'display_seconds'=>false), $smarty);
          break;
        case 'datetimenull':
          if(!$_val)  $_val = $dd[$_key]['extra']['default'];
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
          $_tmp = '<textarea rows="5" cols="40" class="adm" id="' . $_key . '" name="' . $_key . '">' . $_val . '</textarea>';
          $_tmp .= "<script language=\"JavaScript\">\ngenerate_wysiwyg('" . $_key . "');\n</script>\n";
          break;
        case 'xhtml':
          $_tmp = '<textarea rows="5" cols="40" class="adm" id="' . $_key . '" name="' . $_key . '"'.(($dd[$_key]['extra']['style'])?' style="'.$dd[$_key]['extra']['style'].'"':'').'>' . $_val . '</textarea>';
          $_tmp .= "<script language=\"JavaScript\">\ntinyMCE.execCommand(\"mceAddControl\", true,\"$_key\");\n</script>\n";
	  break;
        #case 'html':
        #  $_val = rteSafe($_val);
        #  $_tmp = '<script language="JavaScript" type="text/javascript">' . "\nwriteRichText('" . $_key . "', '" . $_val . "', 520, 200, true, false);\n</script>\n";
        #  break;
        case 'varchar':
        case 'char':
          if ($dd[$_key]['extra']['arr_values']) {
            $_options = $dd[$_key]['extra']['arr_values'];
            $_tmp = smarty_function_html_options(array('options'=>$_options, 'selected'=>$_val), $smarty);
            $_tmp = preg_replace("/_REFERENCE_/", $_tmp, FCELLMODREF);
            $_tmp = preg_replace("/_FIELD_/", $_key, $_tmp);
          } else {
            $_val = preg_replace("/\"/", "&quot;", $_val);
            $_tmp = preg_replace("/_VALUE_/", $_val, FCELLMODSTR);
            $_tmp = preg_replace("/_FIELD_/", $_key, $_tmp);
            $_tmp = preg_replace("/_SIZE_/", $dd[$_key]['size'], $_tmp);
            if($dd[$_key]['extra']['source']) {
              $_tmp .= '<script type="text/javascript">
                          addEvent(document.getElementById(\'' . $dd[$_key]['extra']['source'] . '\'), \'blur\', function () { if(document.getElementById(\'' . $_key . '\').value.replace(/(^\s*)|(\s*$)/g, \'\') == \'\')  document.getElementById(\'' . $_key . '\').value = ' . $dd[$_key]['extra']['apply_funct_js'] . '(document.getElementById(\'' . $dd[$_key]['extra']['source'] . '\').value) });
                        </script>';
            }
            if($dd[$_key]['extra']['autocomplete']) {
              $_tmp .= '<script type="text/javascript">
                          $(\'#' . $_key . '\').autocomplete({ serviceUrl:' . ($dd[$_key]['extra']['autocomplete_sr']?$dd[$_key]['extra']['autocomplete_sr']:'\'/cms/js/autocomplete/autocomplete.php\'') . ', delimiter: /(,|;)\s*/, params: { table:\'' . ($dd[$_key]['extra']['autocomplete_tb']?$dd[$_key]['extra']['autocomplete_tb']:$table) . '\', field:\'' . ($dd[$_key]['extra']['autocomplete_fd']?$dd[$_key]['extra']['autocomplete_fd']:($dd[$_key]['extra']['autocomplete_tb']?$dd[$_key]['extra']['autocomplete_tb']:$_key)) . '\' } });
                        </script>';
            }
          }
          break;
        case 'numeric':
        case 'int':
          if ($dd[$_key]['extra']['arr_values']) {
            $_options = $dd[$_key]['extra']['arr_values'];
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
	    $_tmp = '';
	    $_ispreset = true;
            $_pre .= '<input type="hidden" name="' . $_key . '" value="' . $_selected . '" />';
            //$_tmp = '<input type="hidden" name="' . $_key . '" value="' . $_selected . '" />';
            //$_tmp .= $_selected;
          } else {
            if($dd[$_key]['extra']['readonly']) {
              $_tmp = preg_replace("/_FIELD_/", $_key, FCELLHIDEN);
              $_tmp = preg_replace("/_VALUE_/", $_selected, $_tmp);
              $_tmp = preg_replace("/_LABEL_/", $_val, $_tmp);
            } else {
              $_options = $options[$_key];
              $_tmp = smarty_function_html_options(array('options'=>$_options, 'selected'=>$_selected), $smarty);
              $_tmp = preg_replace("/_REFERENCE_/", $_tmp, FCELLMODREF);
              $_tmp = preg_replace("/_FIELD_/", $_key, $_tmp);
              if($dd[$_key]['extra']['son'])
                $_tmp = preg_replace("/_CHANGE_/", "updateCombo('$_key', '".$dd[$_key]['extra']['son']."', null)", $_tmp);
              else
                $_tmp = preg_replace("/_CHANGE_/", "", $_tmp);
              if($dd[$_key]['extra']['open_popup']) $_tmp .= '&nbsp;<a href="javascript:openPopUp(\'/cms/query.php?f='.$dd[$_key]['references'].'&action=record&'.$_key.'=\'+document.forms[\'new\'].'.$_key.'.value,'.$dd[$_key]['extra']['height'].','.$dd[$_key]['extra']['width'].');">consultar</a>';
              if($dd[$_key]['extra']['depend'])
                $_tmp .= "<script>updateCombo('".$dd[$_key]['extra']['depend']."', '$_key', '$_selected')</script>";
            }
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
      if(!$_ispreset) $_tmp = ereg_replace("_FCELL_", $_tmp, FROW);
      $_tmp = ereg_replace("_LABEL_", $labels[$_key], $_tmp);
      if (!$hidden) $_html_rows .= $_tmp;
      $hidden = false;
    }
  } else {
    foreach ((array)$row as $_key=>$_val) {
      $_tmp = '';
      if (!$dd[$_key]) {
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
          if ($dd[$_key]['extra']['arr_values']) {
            $_options = $dd[$_key]['extra']['arr_values'];
            $_tmp = $_options[$_val];
          } else {
            $_tmp = smarty_modifier_truncate($_val, 50);
          }
          break;
        case 'bool':
        case 'boolean':
          $_si = "S&iacute;";
          $_no = "No";
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
	    //	-----
	    $_tmp = '<a href="javascript:openwindow(\''. URL .'/cms/video.php?src='.$vs['src'].'&type='.$vs['tipo'].'\',400,333)"><img src="/cms/img/'.$vs['tipo'].'.png" alt="'.$a_vs[$vs['tipo']].'" title="'.$a_vs[$vs['tipo']].'" border="0" /></a>';
	  } else
	    $_tmp = '--';
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
            $_tmp = '<a title="' . $_val . '" href="' . URL . '/files/' . $table. '/' . $_val . '" target="_new"><img src="/cms/img/' . $_icon . '" alt="' . $_val  . '" border="0" /></a>';
          } else {
            $_tmp = '--';
          }
          break;
        case 'image':
          if ($_val) {
            if (THUMBNAILING)
              $_tmp = '<a href="javascript:openimage(\'' . URL . '/files/' . $table . '/' . $_val . '\',\'Imagen: ' . $_val . '\')"><img src="' . URL . '/cms/pic/50/' . $table . '/' . $_val . '" alt="' . $_val  . '" width="50" border="0" /></a>';
            else
              $_tmp = '<a href="javascript:openimage(\'/files/' . $table . '/' . $_val . '\',\'Imagen: ' . $_val . '\')"><img src="'. URL .'/cms/pic/50/' . $table . '/' . $_val . '" alt="' . $_val . '" width="50" border="0" /></a>';
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
    	  if($dd[$_key]['extra']['arr_values']) {
            $_options = $dd[$_key]['extra']['arr_values'];
            $_val = $_options[trim($_val)];
          }
          #$_tmp = smarty_modifier_truncate($_val, 50);
	  if($_preset[$_key]) {
	    $_tmp = '';
	    if(!empty($_pre))	$_pre .= ',';
	    $_pre .= $_key .'='.$_preset[$_key];
	  } else {
            $_tmp = smarty_modifier_wordwrap($_val);
            $_tmp = smarty_modifier_nl2br($_tmp);
	  }
      }
      if(!$_preset[$_key]) {
        $_tmp = ereg_replace("_FCELL_", $_tmp, FROW);
        $_tmp = ereg_replace("_LABEL_", $labels[$_key], $_tmp);
      }
      if (!$hidden) $_html_rows .= $_tmp;
      $hidden = false;
    }
    $_html_cmd = ereg_replace("{_ID_}", $row[$key], FCMD);
    if(!empty($_pre)) $_pre = '<input type="hidden" name="preset" value="' .$_pre . '" />';
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
  $_f = preg_replace('/_PRESET_/',$_pre,$h_f);
  //$_f = $h_f;
  $_html_result = preg_replace("/_FHEADER_/", $_html_labels, $_f);
  if ($cmd)
    $_html_result = preg_replace("/_FHEADERCMD_/", FHEADERCMD, $_html_result);
    $_html_result = preg_replace("/_FHEADERCMD_/", '', $_html_result);
  $_html_result = preg_replace("/_TITLE_/", $title, $_html_result);
  $_html_result = ereg_replace("_FROW_", $_html_rows, $_html_result);
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

  # christian | a new way to know if this pages is the 404.php file
  $params = preg_split('/\//',$_SERVER['PHP_SELF']);
  $page = $params[count($params) - 1];
  # end
  if ($page == '404.php' || $page == '404c.php')
    $_html_result = preg_replace("/_SELF_/", SELF, $_html_result);
  else
    $_html_result = preg_replace("/_SELF_/", $_SERVER['PHP_SELF'], $_html_result);

  $_referer = preg_replace("/\//", "\/", $_SERVER['PHP_SELF']);
  if (preg_match("/$_referer/", $_SERVER['HTTP_REFERER']) || $is_detail || $_SERVER['PHP_SELF'] == '/cms/query.php') {
    $_referer = $_SERVER['PHP_SELF'];
  } else {
    if(defined('SELF'))  $_referer = SELF;
    else $_referer = $_SERVER['PHP_SELF'];
  }
  if($is_detail)  $_referer .= '?action=close';
  // Si es el popup de consulta
  if($_SERVER['PHP_SELF'] == '/cms/query.php') {  $_referer .= '?action=close'; }
  
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
  if ($type == 0) $_html_result = preg_replace('/old_/', '', $_html_result);
  
  if(strpos($_html_rows,'value="Agregar"') != '') {
  	  $_SESSION['accion'] = 'add';
  } elseif(isset($_SESSION['accion'])) {
  	  unset($_SESSION['accion']);
  } 
  
  return $_html_result;

}
?>
