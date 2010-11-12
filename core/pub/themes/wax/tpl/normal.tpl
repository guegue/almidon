{capture name=alm_output}
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
  {datagrid2 rows=$rows keys=$keys title=$title dd=$dd options=$options maxcols=$maxcols|default:5 maxrows=$maxrows|default:10 paginate=true cmd=$cmd|default:true name=$object have_child=$have_child num_rows=$num_rows search=$search}
{else}
  {if $rows}
    {datagrid rows=$rows keys=$keys title=$title dd=$dd options=$options maxcols=$maxcols|default:5 maxrows=$maxrows|default:$smarty.const.MAXROWS paginate=true cmd=$cmd|default:true name=$object have_child=$have_child num_rows=$num_rows search=$search}
  {else}
    {$smarty.const.ALM_NODATA}
  {/if}
{/if}
</td>
{if $down}
</tr>
<tr>
{/if}
<td>
{if $smarty.session.idalm_user eq 'admin' || $credentials eq 'full' || ($credentials eq 'read' && $smarty.session.accion == 'leer') || $credentials eq 'edit'}
  {if $add===true || $row}{if $smarty.const.DB3 === true}
    {dataform2 dd=$dd keys=$keys title=$title row=$row name="new" object=$object edit=$edit options=$options}
  {else}
    {dataform dd=$dd keys=$keys title=$title row=$row name="new" object=$object edit=$edit options=$options}
  {/if}
  {else}&nbsp;<!--No esta permito agregar solo modificar-->{/if}
{/if}
</td>
</tr>
</table>
{section name=i loop=$child}
<br />
{if $child[i]._fkey}
<h2>{$smarty.const.ALM_DETAIL} : {$child[i].title}</h2>
<table>
<tr valign="top">
<td>
  {if $child[i].rows}
    {datagrid parent=$child[i]._fkey rows=$child[i].rows key=$child[i].key title=$child[i].title dd=$child[i].dd maxcols=$child[i].maxcols|default:5 maxrows=$child[i].maxrows|default:8 paginate=false cmd=true name=$child[i].name object=$child[i].name options=$child[i]._options is_child=true num_rows=$child[i].num_rows}
    <br /><a href="javascript:openwindow('{$child[i].name}.php?parent={$child[i]._fkey}&{$child[i]._fkey}={$child[i]._fkey_value|escape}');">{$smarty.const.ALM_ADD_LB}</a>
  {else}
    {$smarty.const.ALM_NORDATA}. <a href="javascript:openwindow('{$child[i].name}.php?parent={$child[i]._fkey}&{$child[i]._fkey}={$child[i]._fkey_value|escape}');">{$smarty.const.ALM_ADD_LB}</a>
  {/if}
</td>
</tr>
</table>
{/if}
{/section}
{include file="$footer"}
{/capture}
{if $smarty.const.ALM_ADMIN_COMPRESS===true}{$smarty.capture.alm_output|strip}{else}{$smarty.capture.alm_output}{/if}
