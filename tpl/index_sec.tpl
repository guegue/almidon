<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<!--
HTML Coder: Christian Torres <christian@guegue.net>
-->
  <title>Administración | {if 'TITLE'|defined===true}{$smarty.const.TITLE}{else}{$smarty.const.DOMAIN}{/if}</title>
  <link href="/cms/css/adm2.css" rel="stylesheet" type="text/css" />
</head>
<body>
<img src="{$smarty.const.URL}/imgs/adminsite_small.png" alt="{$smarty.const.DOMAIN}" title="{$smarty.const.DOMAIN}" class="logo" />
<h1>Administración | {if 'TITLE'|defined===true}{$smarty.const.TITLE}{else}{$smarty.const.DOMAIN}{/if}</h1>
<div>Bienvenido!</div>
<div>Conectado como: {http_user_auth}</div>
<hr />
<br />
<div id="info">
<h3>Guegue: Contáctenos</h3>
<ul>
  <li>Información o consulta&lt;info@guegue.com&gt; 2270-9850</li>
  <li>Soporte Técnico 24 hrs 8467-2187</li>
  <li>Christian Torres &lt;christian@guegue.net&gt; 8465-9262</li>
  <li>Alfredo Wilson &lt;alfredo@guegue.net&gt; 8885-0833</li>
</ul>
Desarrollado por:<br />
<img src="{$smarty.const.URL}/cms/img/guegue_small.png" alt="Guegue Comunicaciones" title="Guegue Comunicaciones" />
<img src="{$smarty.const.URL}/cms/img/almidon-atma.png" alt="Almidon" title="Almidon" />
</div>
{foreach name=i from=$sectionlinks key=key item=section}
<h2>{$section.label}</h2>
{if $section.adminlinks}
<ul>
  {foreach name=j from=$section.adminlinks key=keyl item=link}
  <li><a href="{$keyl}">{$link}</a></li>
  {/foreach}
</ul>
{/if}
{/foreach}
</body>
</html>
