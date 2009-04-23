<?xml version="1.0"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN">
<html>
<head>
<script language="JavaScript1.2" type="text/javascript" src="/cms/js/common.js"></script>
<script language="JavaScript1.2" type="text/javascript" src="/cms/js/tools.js"></script>
<!-- Editor HTML WYSSYG -->
<script language="JavaScript" type="text/javascript" src="/cms/js/html/wysiwyg.js"></script>
<!-- Editor XHTML TinyMCE -->
<script type="text/javascript" src="/cms/js/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
{literal}
tinyMCE.init({
        mode : "none",
        theme : {/literal}{if "TINYMCE_THEME"|defined}"{$smarty.const.TINYMCE_THEME}"{else}"advanced"{/if}{literal},
	skin : {/literal}{if "TINYMCE_SKIN"|defined}"{$smarty.const.TINYMCE_SKIN}"{else}"o2k7"{/if}{literal},
	skin_variant : {/literal}{if "TINYMCE_SKIN_VAR"|defined}"{$smarty.const.TINYMCE_SKIN_VAR}"{else}"silver"{/if}{literal},

        theme_advanced_buttons1 : {/literal}{if "TINYMCE_TOOLBAR1"|defined}"{$smarty.const.TINYMCE_TOOLBAR1}"{else}"bold,italic,underline,strikethrough,separator,justifyleft,justifycenter,justifyright,justifyfull,separator,hr,pastetext,pasteword,selectall,fullscreen"{/if}{literal},
        theme_advanced_buttons2 : {/literal}{if "TINYMCE_TOOLBAR2"|defined}"{$smarty.const.TINYMCE_TOOLBAR2}"{else}"sub,sup,separator,bullist,numlist,separator,outdent,indent,separator,undo,redo,separator,link,unlink,anchor,image,cleanup,code,fullscreen"{/if}{literal},
        theme_advanced_buttons3 : {/literal}{if "TINYMCE_TOOLBAR3"|defined}"{$smarty.const.TINYMCE_TOOLBAR3}"{else}""{/if}{literal},
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_resizing : true,
        plugins : "paste",
        plugins : "fullscreen",
        fullscreen_settings : {
                theme_advanced_path_location : "top"
        }
});

{/literal}
</script>
<link rel="stylesheet" href="/cms/css/adm.css">
<title>{$title}</title>
{confirm_delete}
</head>
<body>
{strip}
{if $sectionlinks}
  {count var=num_sections value=$sectionlinks}
  {if $num_sections > 1}
    <a href="{$smarty.const.SSL_URL}/"{if ($cur_section=='inicio')} class="stay"{/if}>Inicio</a> | &nbsp;{foreach key=key item=item from=$sectionlinks name=i}<a href="{$smarty.const.SSL_URL}/{$key}/{$item.index}"{if $cur_section==$key} class="stay"{/if}>{$item.label}</a>{if !$smarty.foreach.i.last} | &nbsp;{/if}{/foreach}<hr size="1" color="#97ACBA" noshade />
  {/if}
  {if $adminlinks}
    {foreach key=key item=item from=$adminlinks name=j}<a href="{$smarty.const.SSL_URL}/{$cur_section}/{$key}"{if ($cur_page==$key)||($object==$key)} class="stay"{/if}>{$item}</a>{if !$smarty.foreach.j.last} | &nbsp;{/if}{/foreach}
  <hr size="1" color="#97ACBA" noshade />
  {/if}
{elseif $adminlinks}
  {foreach key=key item=item from=$adminlinks name=i}<a href="{$key}"{if ($cur_page==$key)||($object==$key)} class="stay"{/if}>{$item}</a>{if !$smarty.foreach.i.last} | &nbsp;{/if}{/foreach}
  {if $smarty.session.idusername}&nbsp;[{$smarty.session.idusername} ({$smarty.session.idrole}){if $smarty.const.LOGOUT===true} - <a href="/{$smarty.const.DOMAIN}/logout">salir</a>{/if}]{/if}
  <hr size="1" color="#97ACBA" noshade />
{/if}
{/strip}
{*strip}{foreach key=key item=item from=$adminlinks}
  <a href="{$key}">{$item}</a> | &nbsp;
{/foreach}{/strip}
{if $smarty.session.idusername}&nbsp;[{$smarty.session.idusername} ({$smarty.session.idrole}){if $smarty.const.LOGOUT===true} - <a href="/{$smarty.const.DOMAIN}/logout">salir</a>{/if}
<hr size="1" color="#97ACBA" noshade> *}
<h1>{$title|default:""}</h1>
