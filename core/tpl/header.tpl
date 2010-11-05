<?xml version="1.0"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">  
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="content-language" content="{$smarty.const.ALM_CONTENT_LANG}" />
<meta name="keywords" content="" />
<meta name="description" content="" />
<meta name="generator" content="almidon v 20100910" />
<meta name="author" content="Guegue Comunicaciones - guegue.com" />
<meta name="copyright" content="Guegue Comunicaciones &copy; 2005-2010" />
<title>{if $title}{$title}{else}{$smarty.const.ALM_TITLE_INDEX}{/if} - {$smarty.session.alm_user} @ {$smarty.const.DOMAIN}</title>
{if $index!==true}
<script language="JavaScript1.2" type="text/javascript" src="/almidon/js/common.js"></script>
<script language="JavaScript1.2" type="text/javascript" src="/almidon/js/tools.js"></script>
{if $js_inc.html===true}
<!-- Editor HTML WYSSYG -->
<script language="JavaScript" type="text/javascript" src="/almidon/js/html/wysiwyg.js"></script>
{/if}
{if $js_inc.xhtml===true}
<!-- Editor XHTML TinyMCE -->
{if $smarty.const.ALM_TINY_COMPRESSOR!==false}
<script type="text/javascript" src="/almidon/js/tiny_mce/tiny_mce_gzip.js"></script>
<script language="javascript" type="text/javascript">{literal}
tinyMCE_GZ.init({
  	plugins : "paste,fullscreen",
  	theme : {/literal}{if "TINYMCE_THEME"|defined}"{$smarty.const.TINYMCE_THEME}"{else}"advanced"{/if}{literal},
     	skin : {/literal}{if "TINYMCE_SKIN"|defined}"{$smarty.const.TINYMCE_SKIN}"{else}"o2k7"{/if}{literal},
  	skin_variant : {/literal}{if "TINYMCE_SKIN_VAR"|defined}"{$smarty.const.TINYMCE_SKIN_VAR}"{else}"black"{/if}{literal},
        language: "{/literal}{if "ALM_TINY_LANG"|defined}{$smarty.const.ALM_TINY_LANG}{else}es{/if}{literal}",
	disk_cache : true,
	debug : false
});
{/literal}</script>
{else}
<script type="text/javascript" src="/almidon/js/tiny_mce/tiny_mce.js"></script>
{/if}
<script language="javascript" type="text/javascript">{literal}
tinyMCE.init({
  mode : "none",
  language: "{/literal}{if "ALM_TINY_LANG"|defined}{$smarty.const.ALM_TINY_LANG}{else}es{/if}{literal}",
  theme : {/literal}{if "TINYMCE_THEME"|defined}"{$smarty.const.TINYMCE_THEME}"{else}"advanced"{/if}{literal},
  skin : {/literal}{if "TINYMCE_SKIN"|defined}"{$smarty.const.TINYMCE_SKIN}"{else}"o2k7"{/if}{literal},
  skin_variant : {/literal}{if "TINYMCE_SKIN_VAR"|defined}"{$smarty.const.TINYMCE_SKIN_VAR}"{else}"black"{/if}{literal},
  theme_advanced_buttons1 : {/literal}{if "TINYMCE_TOOLBAR1"|defined}"{$smarty.const.TINYMCE_TOOLBAR1}"{else}"bold,italic,underline,strikethrough,separator,justifyleft,justifycenter,justifyright,justifyfull,separator,hr,pastetext,pasteword,selectall,fullscreen"{/if}{literal},
  theme_advanced_buttons2 : {/literal}{if "TINYMCE_TOOLBAR2"|defined}"{$smarty.const.TINYMCE_TOOLBAR2}"{else}"sub,sup,separator,bullist,numlist,separator,outdent,indent,separator,undo,redo,separator,link,unlink,anchor,image,cleanup,code,fullscreen"{/if}{literal},
  theme_advanced_buttons3 : {/literal}{if "TINYMCE_TOOLBAR3"|defined}"{$smarty.const.TINYMCE_TOOLBAR3}"{else}""{/if}{literal},
  theme_advanced_toolbar_location : "top",
  theme_advanced_toolbar_align : "left",
  theme_advanced_resizing : true,
  plugins : "paste,fullscreen",
  {/literal}{if "TINYMCE_EXTENDED_VALID_ELEMENTS"|defined}extended_valid_elements:"{$smarty.const.TINYMCE_EXTENDED_VALID_ELEMENTS}",{/if}{literal}
  fullscreen_settings : {
    theme_advanced_path_location : "top"
  }
});
{/literal}</script>
{/if}
{confirm_delete}
{if $js_inc.autocomplete===true}
<!-- Autocomplete -->
<script type="text/javascript" src="/almidon/js/jquery.js"></script>
<script type="text/javascript" src="/almidon/js/autocomplete/jquery.autocomplete.min.js"></script>
<link rel="stylesheet" href="/almidon/js/autocomplete/jquery.autocomplete.css" />
{/if}
{/if}
<!-- Default CSS -->
<link rel="stylesheet" href="/almidon/css/adm.css"/>
</head>
<body>
{strip}
{if $sectionlinks}
  {if $sectionlinks|@count >= 1}
    <a href="{$smarty.const.SSL_URL}/"{if $cur_section=='inicio' || $cur_section|trim == ''} class="stay"{/if}>Inicio</a> | &nbsp;{foreach key=key item=item from=$sectionlinks name=i}<a href="{$smarty.const.SSL_URL}/{$key}/{$item.index}"{if $cur_section==$key} class="stay"{/if}>{$item.label}</a>{if !$smarty.foreach.i.last} | &nbsp;{/if}{/foreach}<hr size="1 noshade="noshade" />
  {/if}
  {if $adminlinks}
    {foreach key=key item=item from=$adminlinks name=j}<a href="{$smarty.const.SSL_URL}/{$cur_section}/{$key}"{if ($cur_page==$key)||($object==$key)} class="stay"{/if}>{$item}</a>{if !$smarty.foreach.j.last} | &nbsp;{/if}{/foreach}
  <hr size="1" noshade="noshade" />
  {/if}
{elseif $adminlinks}
  {foreach key=key item=item from=$adminlinks name=i}<a href="{$key}"{if ($cur_page==$key)||($object==$key)} class="stay"{/if}>{$item}</a>{if !$smarty.foreach.i.last} | {/if}{/foreach}
  {if $smarty.session.alm_user} &nbsp; [ {$smarty.session.alm_user} ]{/if}
  <hr size="1" noshade="noshade" />
{/if}
{/strip}
<h1>{$title|default:""}</h1>
