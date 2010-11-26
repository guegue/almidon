<?php
# URL almidon
if ( !defined('ALM_URI') )
  define('ALM_URI','almidon');

# FIXME: are we using this? form to search
define('DS','<form action="_SELF_" method="POST" name="_FORM_search" class="search"><input type="hidden" name="action" value="search" />_DSFIELDS_&nbsp;<input type="submit" value="'. ALM_SEARCH_LB .'" /><input type="reset" value="'. ALM_RESET_LB .'" /></form>');
define('DSL','Resultados para <b>_DSQUERY_</b> [<a href="_SELF_?action=clear">' . ALM_SHOWALL . '</a>]');
define('DSLABEL','<label for="_FIELD_">_LABEL_</label>');
# end
# para las tablas detalle
define('DGCMD_det', '<td class="dgcmd"><a class="dgcmd_link" href="javascript:openwindow(\'./_FORM_.php?parent=_PARENT_&_PARENT_=_PARENTID_&amp;f=_FORM_&amp;action=record&amp;{_KEY=ID_}\');"><img src="/' . ALM_URI . '/themes/' . ALM_ADMIN_THEME . '/img/view.png" border="0" title="'. ALM_VIEW_LB .'" alt="'. ALM_VIEW_LB .'"/></a> <a href="javascript:confirm_delete_det(\'_FORM_\',{_KEY:ID_},{_IDS_});"><img src="/' . ALM_URI . '/themes/' . ALM_ADMIN_THEME . '/img/delete.png" height="16" width="16" border="0" title="'. ALM_DEL_LB .'" alt="' . ALM_DEL_LB . '"/></a></td>');

define('DGSEARCH', '<form method="get"><input type="hidden" name="action" value="search" />
<input type="text" name="q" value="{_Q_}" /><input type="submit" name="search" value="'.ALM_SEARCH_LB.'"/></form>');
define('DG', '{_DGSEARCH_}<form action="_SELF_" method="post" name="_FORM_" enctype="multipart/form-data">
{_OLDKEYS_}
<input type="hidden" name="_PARENT_" value="_PARENTID_" />
<input type="hidden" name="_FORM_sort" value="_SORT_" />
<input type="hidden" name="_FORM_pg" value="_PG_" />
<input type="hidden" name="maxcols" value="_MAXCOLS_" />
<input type="hidden" name="f" value="_FORM_" />
<input type="hidden" name="action" value="save" />
<table class="dgtable" border="0" cellspacing="0" cellpadding="2"><tr><th>_TITLE_</th> <th align="right"><div align="right">(_ROWS_ '. ALM_REC_LB .')</div></th></tr>
<tr><td colspan="2"><table class="dgsubtable" border="0" cellspacing="0" cellpadding="0"><tr>_DGHEADER__DGHEADERCMD_</tr>
_DGROW_
</table></td></tr><tr><td class="paginate">_PAGINATE_</td></tr></table></form>');
define('DGHEADERCMD', '<th>'. ALM_OPT_LB .'</th>');
define('DGHEADERCELL', '<th><a class="dgheader_link" href="_SELF_?q=_Q_&amp;f=_FORM_&amp;_FORM_sort=_FIELD__DESC_">_LABEL_ _SORTIMG_</a></th>');
define('DGROW', '<tr class="dgrow">_DGCELL_</tr>'."\n");
define('DGCELL', '<td class="dgcell">_VALUE_</td>');
define('DGCELLMODSTR', '<input type="text" name="_FIELD_" id="_FIELD_" value="_VALUE_" size="20" maxlength="_SIZE_"/>');
define('DGCELLMODTXT', '<textarea name="_FIELD_" id="_FIELD_">_VALUE_</textarea><br /><a href="javascript:edittext(\'_FORM_\', \'_FIELD_\', document.getElementById(\'_FIELD_\').value);">'. MAX .'</a>');
define('DGCELLMODREF', '<select name="_FIELD_"><option value="-1">--</option>_REFERENCE_</select>');
define('DGCMD', '<td class="dgcmd"><a class="dgcmd_link" href="_SELF_?f=_FORM_&amp;action=record&amp;{_KEY=ID_}&amp;_FORM_pg=_PG_"><img src="' . URL . '/' . ALM_URI . '/themes/' . ALM_ADMIN_THEME . '/img/view.png" border="0" title="'. ALM_VIEW_LB .'" alt="'. ALM_VIEW_LB .'"/></a> <a href="javascript:confirm_delete(\'_FORM_\',{_KEY:ID_},{_IDS_});"><img src="' . URL . '/' . ALM_URI . '/themes/' . ALM_ADMIN_THEME . '/img/delete.png" height="16" width="16" border="0" title="'. ALM_DEL_LB .'" alt="'. ALM_DEL_LB .'"/></a> <a href="_SELF_?q=_Q_&amp;f=_FORM_&amp;action=mod&amp;{_KEY=ID_}&amp;_FORM_pg=_PG_&amp;_FORM_sort=_SORT_"><img src="' . URL . '/' . ALM_URI . '/themes/' . ALM_ADMIN_THEME . '/img/edit.png" border="0" title="'. ALM_EDIT_LB .'" alt="'. ALM_EDIT_LB .'"/></a></td>');
define('DGCMDR', '<td class="dgcmd"><a href="javascript:confirm_delete(\'_FORM_\',{_KEY:ID_},{_IDS_});"><img src="' . URL . '/' . ALM_URI . '/themes/' . ALM_ADMIN_THEME . '/img/delete.png" border="0" title="'. ALM_DEL_LB .'" alt="' . ALM_DEL_LB . '"/></a> <a href="_SELF_?q=_Q_&amp;f=_FORM_&amp;action=mod&amp;{_KEY=ID_}&amp;_PARENT_=_PARENTID_&amp;_FORM_pg=_PG_&amp;_FORM_sort=_SORT_"><img src="' . URL . '/' . ALM_URI . '/themes/' . ALM_ADMIN_THEME . '/img/edit.png" border="0" title="'. ALM_EDIT_LB .'" alt="'. ALM_EDIT_LB .'"/></a></td>');
define('DGCMDMOD', '<td class="dgcmd"><a href="_SELF_?f=_FORM_&amp;{_KEY=ID_}&amp;_FORM_pg=_PG_&amp;_FORM_sort=_SORT_&amp;_PARENT_=_PARENTID_"><img src="' . URL . '/' . ALM_URI . '/themes/' . ALM_ADMIN_THEME . '/img/cancel.png" border="0" title="'. ALM_CAN_LB .'" alt="'.ALM_CAN_LB.'"></a> <a href="javascript:postBack(document._FORM_, \'dgsave\');"><img src="' . URL . '/' . ALM_URI . '/themes/' . ALM_ADMIN_THEME . '/img/save.png" border="0" title="'. ALM_SAVE_LB .'" alt="'.ALM_SAVE_LB.'"></a></td>');

#
# Paginacion
#
define('PREV','<a href="_SELF_?q=_Q_&amp;f=_FORM_&amp;_FORM_sort=_SORT_&amp;_FORM_pg=_PGPREV_">&lt; '.ALM_PREV_LB.'</a> |');
define('NEXT','| <a href="_SELF_?q=_Q_&amp;f=_FORM_&amp;_FORM_sort=_SORT_&amp;_FORM_pg=_PGNEXT_">'.ALM_NEXT_LB.' &gt;</a>&nbsp;');
define('NPG','<a href="_SELF_?q=_Q_&amp;f=_FORM_&amp;_FORM_sort=_SORT_&amp;_FORM_pg=_NPG_"> _NPG_ </a>');
define('CURRENTPG','<strong>_NPG_</strong>');
define('PAGINATE','<table><tr><td nowrap><br>_PGS_<br></td></tr></table>');

#
# Definicion para distintos roles
#
define('DGCMDEDIT', '<td class="dgcmd"><a class="dgcmd_link" href="_SELF_?f=_FORM_&amp;action=record&amp;{_KEY=ID_}&amp;_FORM_pg=_PG_"><img src="' . URL . '/' . ALM_URI . '/themes/' . ALM_ADMIN_THEME . '/img/view.png" border="0" title="'. ALM_VIEW_LB .'" alt="'.ALM_VIEW_LB.'"/></a><a href="_SELF_?q=_Q_&amp;f=_FORM_&amp;action=mod&amp;{_KEY=ID_}&amp;_FORM_pg=_PG_&amp;_FORM_sort=_SORT_"><img src="' . URL . '/' . ALM_URI . '/themes/' . ALM_ADMIN_THEME . '/img/edit.png" border="0" title="'. ALM_EDIT_LB .'" alt="'.ALM_EDIT_LB.'"/></a></td>');
define('DGCMDVER', '<td class="dgcmd"><a class="dgcmd_link" href="_SELF_?f=_FORM_&amp;action=record&amp;{_KEY=ID_}&amp;_FORM_pg=_PG_"><img src="' . URL . '/' . ALM_URI . '/themes/' . ALM_ADMIN_THEME . '/img/view.png" border="0" title="'. ALM_VIEW_LB .'" alt="'.ALM_VIEW_LB.'"/></a></td>');
define('DGCMDDEL', '<td class="dgcmd"><a class="dgcmd_link" href="_SELF_?f=_FORM_&amp;action=record&amp;{_KEY=ID_}&amp;_FORM_pg=_PG_"><img src="' . URL . '/' . ALM_URI . '/themes/' . ALM_ADMIN_THEME . '/img/view.png" border="0" title="'. ALM_VIEW_LB .'" alt="'.ALM_VIEW_LB.'"/></a> <a href="javascript:confirm_delete(\'_FORM_\',{_KEY:ID_},{_IDS_});"><img src="' . URL . '/' . ALM_URI . '/themes/' . ALM_ADMIN_THEME . '/img/delete.png" height="16" width="16" border="0" title="'. ALM_DEL_LB .'" alt="'.ALM_DEL_LB.'"/></a></td>');
define('DGCMDRVER', '<td class="dgcmd"></td>');
define('DGCMDRDEL', '<td class="dgcmd"><a href="javascript:confirm_delete(\'_FORM_\',{_KEY:ID_},{_IDS_});"><img src="' . URL . '/' . ALM_URI . '/themes/' . ALM_ADMIN_THEME . '/img/delete.png" border="0" title="'. ALM_DEL_LB .'" alt="'.ALM_DEL_LB.'"/></a></td>');
define('DGCMDREDIT', '<td class="dgcmd"><a href="_SELF_?q=_Q_&amp;f=_FORM_&amp;action=mod&amp;{_KEY=ID_}&amp;_PARENT_=_PARENTID_&amp;_FORM_pg=_PG_&amp;_FORM_sort=_SORT_"><img src="' . URL . '/' . ALM_URI . '/themes/' . ALM_ADMIN_THEME . '/img/edit.png" border="0" title="'. ALM_EDIT_LB .'" alt="'.ALM_EDIT_LB.'"/></a></td>');

