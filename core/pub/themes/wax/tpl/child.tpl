{include file="$header"}
<table>
<tr valign="top">
<td>
{* Show a normal dataform, using dataform smarty function, you can use dataform function instead of this, but you have fill the params it needs. For an example see core/tpl/dataform.tpl *}
{almtemplate file="dataform.tpl"}
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
