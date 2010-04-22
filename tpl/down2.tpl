{include file="$header"}
<table>
<tr valign="top">
<td>
{if $rows}
  {datagrid rows=$rows key1=$key1 key2=$key2 title=$title dd=$dd options=$options maxcols=$maxcols|default:5 maxrows=$maxrows|default:5 paginate=true cmd=true name=$object num_rows=$num_rows}
{else}
  {$smarty.const.ALM_NODATA}
{/if}
</td>
</tr>
<tr>
{dataform dd=$dd key1=$key1 key2=$key2 title=$title row=$row name="new" object=$object edit=$edit options=$options}</td>
</tr>
</table>
{include file="$footer"}
