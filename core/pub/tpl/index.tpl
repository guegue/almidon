{capture name=alm_output}
{include file="$header" index=true}
{include file="$footer"}
{/capture}
{if $smarty.const.ALM_ADMIN_COMPRESS===true}{$smarty.capture.alm_output|strip}{else}{$smarty.capture.alm_output}{/if}
