{include file="$header"}
<table>
{if $credentials eq 'full'}
<tr valign="top">
<td>
<a href="javascript: void(0)" onclick="window.open('exchange.php?table={$object}&action=import', 'alm_export', 'width=400,height=200, directories=no, location=no, menubar=no, resizable=yes, scrollbars=1, status=no, toolbar=no'); return false;">{$smarty.const.ALM_IMPORT_LB}</a>
<a href="javascript: void(0)" onclick="window.open('exchange.php?table={$object}&action=export', 'alm_export', 'width=400,height=200, directories=no, location=no, menubar=no, resizable=yes, scrollbars=1, status=no, toolbar=no'); return false;">{$smarty.const.ALM_EXPORT_LB}</a>
</td>
</tr>
{/if}
<tr valign="top">
<td>
{if $smarty.const.DB3 === true}
  {datagrid2 rows=$rows key=$key title=$title dd=$dd options=$options maxcols=$maxcols|default:5 maxrows=$maxrows|default:8 paginate=true cmd=$cmd|default:true name=$object have_child=$have_child num_rows=$num_rows shortEdit=$shortEdit}
{else}
  {if $rows}
  {datagrid rows=$rows key=$key title=$title dd=$dd options=$options maxcols=$maxcols|default:5 maxrows=$maxrows|default:$smarty.const.MAXROWS paginate=true cmd=$cmd|default:true name=$object num_rows=$num_rows}
  {else}
  {$smarty.const.ALM_NODATA}
  {/if}
{/if}
</td>
<td>
    {if $smarty.session.idalm_user eq 'admin' || $credentials eq 'full' || ($credentials eq 'read' && $smarty.session.accion == 'leer') || $credentials eq 'edit'}
	   {if $add===true || $row}{if $smarty.const.DB3 === true}
	        {dataform2 dd=$dd key=$key title=$title row=$row name="new" object=$object edit=$edit options=$options}
	   {else}
	        {dataform dd=$dd key=$key title=$title row=$row name="new" object=$object edit=$edit options=$options}
	   {/if}
	   {else}&nbsp;<!--No esta permito agregar solo modificar-->{/if}
   {/if}	   
</td>
</tr>
</table>
<br/>
{if $child._fkey}
<h2>{$smarty.const.ALM_DETAIL} : {$child[i].title}</h2>
<table>
<tr valign="top">
<td>
  {if $child.rows}
    {datagrid parent=$child._fkey rows=$child.rows key=$child.key" title=$child.title dd=$child.dd maxcols=$child.maxcols|default:5 maxrows=$child.maxrows|default:15 paginate=false cmd=true name=$child.name object=$child.name options=$child._options is_child=true num_rows=$child.num_rows}
    <br /><a href="javascript:openwindow('{$child.name}.php?parent={$child._fkey}&{$child._fkey}={$child._fkey_value|escape}');">{$smarty.const.ALM_ADD_LB}</a>
  {else}
    {$smarty.const.ALM_NORDATA}. <a href="javascript:openwindow('{$child.name}.php?parent={$child._fkey}&{$child._fkey}={$child._fkey_value|escape}');">{$smarty.const.ALM_ADD_LB}</a>
  {/if}
</td>
</tr>
</table>
{/if}
{include file="$footer"}
