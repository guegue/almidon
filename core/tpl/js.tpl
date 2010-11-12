{if $index!==true}
<script language="JavaScript1.2" type="text/javascript" src="{$smarty.const.URL}/{$smarty.const.ALM_URI}/js/common.js"></script>
<script language="JavaScript1.2" type="text/javascript" src="{$smarty.const.URL}/{$smarty.const.ALM_URI}/js/tools.js"></script>
{if $js_inc.html===true}
<!-- Editor HTML WYSSYG -->
<script language="JavaScript" type="text/javascript" src="{$smarty.const.URL}/{$smarty.const.ALM_URI}/js/html/wysiwyg.js"></script>
{/if}
{if $js_inc.xhtml===true}
<!-- Editor XHTML TinyMCE -->
{if $smarty.const.ALM_TINY_COMPRESSOR!==false}
<script type="text/javascript" src="{$smarty.const.URL}/{$smarty.const.ALM_URI}/js/tiny_mce/tiny_mce_gzip.js"></script>
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
<script type="text/javascript" src="{$smarty.const.URL}/{$smarty.const.ALM_URI}/js/tiny_mce/tiny_mce.js"></script>
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
<script type="text/javascript" src="{$smarty.const.URL}/{$smarty.const.ALM_URI}/js/jquery.js"></script>
<script type="text/javascript" src="{$smarty.const.URL}/{$smarty.const.ALM_URI}/js/autocomplete/jquery.autocomplete.min.js"></script>
<link rel="stylesheet" href="{$smarty.const.URL}/{$smarty.const.ALM_URI}/js/autocomplete/jquery.autocomplete.css" />
{/if}
{/if}
