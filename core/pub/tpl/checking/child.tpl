{include file="$header"}
<table>
<tr valign="top">
<td>
{if DB3 === true}
{dataform2 dd=$dd key=$key title=$title row=$row name="new" object=$object edit=$edit options=$options preset=$preset is_child=true}</td>
{else}
{dataform dd=$dd key=$key title=$title row=$row name="new" object=$object edit=$edit options=$options preset=$preset is_child=true}</td>
{/if}
</tr>
</table>
{if $added || $updated}
<script>
window.close();
window.opener.location.reload();
</script>
{elseif $closed}
<script>
window.close();
</script>
{/if}
{include file="$footer"}
