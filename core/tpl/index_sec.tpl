<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<!--
HTML Coder: Christian Torres <christian@guegue.net>
-->
  <title>{lang_const name="ALM_ADMIN_TITLE"} | {if 'TITLE'|defined===true}{$smarty.const.TITLE}{else}{$smarty.const.DOMAIN}{/if}</title>
  <link href="/cms/css/adm2.css" rel="stylesheet" type="text/css" />
</head>
<body>
<img src="{$smarty.const.URL}/imgs/adminsite_small.png" alt="{$smarty.const.DOMAIN}" title="{$smarty.const.DOMAIN}" class="logo" />
<h1>{lang_const name="ALM_ADMIN_TITLE"} | {if 'TITLE'|defined===true}{$smarty.const.TITLE}{else}{$smarty.const.DOMAIN}{/if}</h1>
<div>{lang_const name="ALM_WCOME"} {http_user_auth}</div>
<hr />
<div id="wrap">
{foreach name=i from=$sectionlinks key=key item=section}
<div id="{$key}" class="box">
  <h2>{$section.label}</h2>
  {if $section.adminlinks}
  <ul>
    {foreach name=j from=$section.adminlinks key=keyl item=link}
    <li><a href="{$keyl}">{$link}</a></li>
    {/foreach}
  </ul>
  {/if}
</div><!--class:box-->
{/foreach}
</div><!--id:wrap-->
</body>
</html>
