{capture name=html_output}
{include file="$header" index=true}
{include file="$footer"}
{/capture}
{$smarty.capture.html_output|strip}
