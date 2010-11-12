{if $child[i].rows}
{datagrid parent=$child[i]._fkey rows=$child[i].rows key=$child[i].key title=$child[i].title dd=$child[i].dd maxcols=$child[i].maxcols|default:5 maxrows=$child[i].maxrows|default:8 paginate=false cmd=true name=$child[i].name object=$child[i].name options=$child[i]._options is_child=true num_rows=$child[i].num_rows}
<br /><a href="javascript:openwindow('{$child[i].name}.php?parent={$child[i]._fkey}&{$child[i]._fkey}={$child[i]._fkey_value|escape}');">{$smarty.const.ALM_ADD_LB}</a>
{else}
{$smarty.const.ALM_NORDATA}. <a href="javascript:openwindow('{$child[i].name}.php?parent={$child[i]._fkey}&{$child[i]._fkey}={$child[i]._fkey_value|escape}');">{$smarty.const.ALM_ADD_LB}</a>
{/if}
