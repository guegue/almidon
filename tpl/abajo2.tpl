{include file="/www/cms/tpl/header.tpl"}
<table>
<tr valign="top">
<td>
{if $rows}
  {datagrid rows=$rows key1=$key1 key2=$key2 title=$title dd=$dd options=$options maxcols=5 maxrows=5 paginate=true cmd=true name=$object}
{else}
  No hay datos.
{/if}
</td>
</tr>
<tr>
{dataform dd=$dd key1=$key1 key2=$key2 title=$title row=$row name="new" object=$object edit=$edit options=$options}</td>
</tr>
</table>
{include file="/www/cms/tpl/footer.tpl"}
