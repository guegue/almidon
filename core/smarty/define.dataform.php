<?php
# URL almidon
if ( !defined('ALM_URI') )
  define('ALM_URI','almidon');

define('F', 
  '<form action="_SELF_" method="post" name="_FORM_" enctype="multipart/form-data">
  {_OLDKEYS_}
  <input type="hidden" name="f" value="_FORM_"/>
  <input type="hidden" name="o" value="_OBJECT_"/>
  <input type="hidden" name="action" value="_ACTION_"/>
  <input type="hidden" name="_OBJECT_pg" value="_PG_"/>
  <table class="dgtable" border="0" cellspacing="0" cellpadding="2"><tr><th>_TITLE_</th></tr>
  <tr><td><table class="dgsubtable" border="0" cellspacing="0" cellpadding="0">
  _FROW_</table></td></tr><tr><td>_PAGINATE_</td></tr></table></form>');
define('FHEADERCMD', '<th>'. ALM_OPT_LB .'</th>');
define('FHEADERCELL', '<th><a class="dgheader_link" href="_SELF_?f=_FORM_&amp;sort=_FIELD_">_LABEL_</a></th>'."\n");
define('FROW', '<tr valign="top" class="dgrow"><td class="dgcell">_LABEL_</td> <td class="dgcell">_FCELL_</td></tr>'."\n");
define('FCELLMODSTR', '<input type="text" name="_FIELD_" value="_VALUE_" size="30" maxlength="_SIZE_"/>');
define('FCELLMODREF', '<select name="_FIELD_"><option value="-1">--</option>_REFERENCE_</select>');
define('FCMD',
  '<tr><td class="dgcmd"><input type="submit" value="'. ALM_EDIT_LB .'" /></td> <td class="dgcmd"><input type="button" value="'. ALM_CAN_LB .'" onclick="location.href=\'_REFERER_\'" /></td></tr>');
define('FCMDMOD',
  '<tr><td class="dgcmd"><input type="submit" value="'. ALM_SAVE_LB .'" /></td> <td class="dgcmd"><input type="button" value="'. ALM_CAN_LB .'" onclick="location.href=\'_REFERER_\'" /></td></tr>');

define('FCMDADD', '<tr><td class="dgcmd"><input type="submit" value="'. ALM_ADD_LB .'" /></td></tr>');
define('PREV','<a href="_SELF_?f=_FORM_&amp;sort=_SORT_&amp;pg=_PGPREV_">&lt; '. ALM_PREV_LB .'</a> |');
define('NEXT','| <a href="_SELF_?f=_FORM_&amp;sort=_SORT_&amp;pg=_PGNEXT_">'. ALM_NEXT_LB .' &gt;</a>&nbsp;');
define('NPG','<a href="_SELF_?f=_FORM_&amp;sort=_SORT_&amp;pg=_NPG_"> _NPG_ </a>');
define('CURRENTPG','<strong>_NPG_</strong>');
define('PAGINATE','<table><tr><td nowrap><br>_PGS_<br></td></tr></table>');
