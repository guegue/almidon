{capture name=alm_output}
{include file="$header"}
{debug}
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
  {* Show a normal grid, using datagrid smarty function, you can use datagrid function instead of this, but you have fill the params it needs. For an example see core/tpl/datagrid.tpl *}
  {almtemplate file="datagrid.tpl"}
</td>
{if $down}
</tr>
<tr>
{/if}
<td>
{* Show a normal dataform, using dataform smarty function, you can use dataform function instead of this, but you have fill the params it needs. For an example see core/tpl/dataform.tpl *}
{almtemplate file="dataform.tpl"}
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
{* Show a child grid, using datagrid smarty function, you can use datagrid function instead of this, but you have fill the params it needs. For an example see core/tpl/dgchild.tpl *}
{almtemplate file="dgchild.tpl"}
</td>
</tr>
</table>
{/if}
{/section}
{include file="$footer"}
{/capture}
{if $smarty.const.ALM_ADMIN_COMPRESS===true}{$smarty.capture.alm_output|strip}{else}{$smarty.capture.alm_output}{/if}
