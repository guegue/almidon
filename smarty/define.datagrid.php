<?php
# FIXME: are we using this? form to search
define('DS','<form action="_SELF_" method="POST" name="_FORM_search" class="search"><input type="hidden" name="action" value="search" />_DSFIELDS_&nbsp;<input type="submit" value="'. ALM_SEARCH_LB .'" /><input type="reset" value="'. ALM_RESET_LB .'" /></form>');
define('DSL','Resultados para <b>_DSQUERY_</b> [<a href="_SELF_?action=clear">' . ALM_SHOWALL . '</a>]');
define('DSLABEL','<label for="_FIELD_">_LABEL_</label>');
# end
define('DGSEARCH', '<form method="get"><input type="hidden" name="action" value="search" />
<input type="text" name="q" value="" /><input type="submit" name="search" value="'.ALM_SEARCH_LB.'"/></form>');
define('DG', '_DGSEARCH_<form action="_SELF_" method="post" name="_FORM_" enctype="multipart/form-data">
<input type="hidden" name="old__KEY_" value="{_ID_}" />
<input type="hidden" name="_PARENT_" value="_PARENTID_" />
<input type="hidden" name="_KEY_" value="{_ID_}" />
<input type="hidden" name="_FORM_sort" value="_SORT_" />
<input type="hidden" name="_FORM_pg" value="_PG_" />
<input type="hidden" name="maxcols" value="_MAXCOLS_" />
<input type="hidden" name="f" value="_FORM_" />
<input type="hidden" name="action" value="save" />
<table class="dgtable" border="0" cellspacing="0" cellpadding="2"><tr><th>_TITLE_</th> <th align="right"><div align="right">(_ROWS_ '. ALM_REC_LB .')</div></th></tr>
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
define('DGHEADERCMD', '<th>'. ALM_OPT_LB .'</th>');
define('DGHEADERCELL', '<th><a class="dgheader_link" href="_SELF_?q=_Q_&amp;f=_FORM_&amp;_FORM_sort=_FIELD__DESC_">_LABEL__SORTIMG_</a></th>');
define('DGROW', '<tr class="dgrow">_DGCELL_</tr>'."\n");
define('DGCELL', '<td class="dgcell">_VALUE_</td>');
define('DGCELLMODSTR', '<input type="text" name="_FIELD_" id="_FIELD_" value="_VALUE_" size="20" maxlength="_SIZE_"/>');
define('DGCELLMODTXT', '<textarea name="_FIELD_" id="_FIELD_">_VALUE_</textarea><br /><a href="javascript:edittext(\'_FORM_\', \'_FIELD_\', document.getElementById(\'_FIELD_\').value);">'. MAX .'</a>');
define('DGCELLMODREF', '<select name="_FIELD_"><option value="-1">--</option>_REFERENCE_</select>');
