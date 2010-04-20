{include file="/www/cms/tpl/header.tpl"}
<table>
<tr valign="top">
<td>
{if $rows}{$cmd}
  {datagrid rows=$rows key=$key title=$title dd=$dd options=$options maxcols=7 maxrows=10 paginate=true cmd=$cmd|default:true name=$object}
{else}
  {$smarty.const.ALM_NODATA}
{/if}
</td>
</tr>
</table>
{include file="/www/cms/tpl/footer.tpl"}
