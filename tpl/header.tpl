<?xml version="1.0"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN">
<html>
<head>
<script language="JavaScript1.2" type="text/javascript" src="/cms/js/common.js"></script>
<script language="JavaScript1.2" type="text/javascript" src="/cms/js/tools.js"></script>
<script language="JavaScript" type="text/javascript" src="/cms/html/wysiwyg.js"></script>
<script type="text/javascript" src="/cms/js/tiny_mce/tiny_mce.js"></script>
{if $smarty.const.DB3===true}
<script type='text/javascript' src="http://{$smarty.server.SERVER_NAME}/cms/ajax/server.php?client=Util,main,dispatcher,httpclient,request,json,loading,iframe,queues"></script>
<script type='text/javascript' src="http://{$smarty.server.SERVER_NAME}/cms/ajax/server.php?stub=livefillcombo"></script>
{/if}
<link rel="stylesheet" href="/cms/css/adm.css">
<title>{$title}</title>
<script language="javascript">
{literal}
  function confirm_delete(o, idfield, id, desc) {
    if (window.confirm('"'+desc+'": Estas seguro de querer borrar este registro?')) {
        {/literal}location.href = '?o='+o+'&action=delete&'+idfield+'='+id;{literal}
    }
  }

  function confirm_delete_det(od, idfield, id, desc) {
    if (window.confirm('"'+desc+'": Estas seguro de querer borrar este registro?')) {
        {/literal}location.href = '?od='+od+'&actiond=delete&'+idfield+'='+id;{literal}
    }
  }

  function confirm_delete2(o, idfield1, idfield2, id1, id2, desc) {
    if (window.confirm('"'+desc+'": Estas seguro de querer borrar este registro?')) {
        {/literal}location.href = '?o='+o+'&action=delete&'+idfield1+'='+id1+'&'+idfield2+'='+id2;{literal}
    }
  }

  // callback hash, outputs the results of the search method
  callback = {
    filter_data: function(result) {  
        // if we have object this works right, if we have an array we get a problem
        // if we have sparse keys will get an array back otherwise will get an array
        alert(result);
        fillCombo(result);
    }
  }

  // setup our remote object from the generated proxy stub
  var remoteFillCombo = new livefillcombo(callback);

  // we could change the queue by overriding the default one, but generally you want to create a new one
  // set our remote object to use the rls queue
  remoteFillCombo.dispatcher.queue = 'rls';

  // create the rls queue, with a 350ms buffer, a larger interval such as 2000 is useful to see what is happening but not so useful in real life
  HTML_AJAX.queues['rls'] = new HTML_AJAX_Queue_Interval_SingleBuffer(350);

  // what to call on onkeyup, you might want some logic here to not search on empty strings or to do something else in those cases
  function updateCombo(pComboBox, comboBox, selected) {
    var objParent = document.getElementById(pComboBox);
    remoteFillCombo.filter_data(pComboBox, comboBox, objParent.value, selected);
  }
{/literal}
</script>
</head>
<body>
{strip}
{if $sectionlinks}
  {count var=num_sections value=$sectionlinks}
  {if $num_sections > 1}
    <a href="{$smarty.const.SSL_URL}/"{if ($section_actual=='inicio')} class="stay"{/if}>Inicio</a> | &nbsp;{foreach key=key item=item from=$sectionlinks name=i}<a href="{$smarty.const.SSL_URL}/{$key}/{$item.index}"{if $section_actual==$key} class="stay"{/if}>{$item.label}</a>{if !$smarty.foreach.i.last} | &nbsp;{/if}{/foreach}<hr size="1" color="#97ACBA" noshade />
  {/if}
  {if $adminlinks}
    {foreach key=key item=item from=$adminlinks name=j}<a href="{$smarty.const.SSL_URL}/{$section_actual}/{$key}"{if $cur_page==$key} class="stay"{/if}>{$item}</a>{if !$smarty.foreach.j.last} | &nbsp;{/if}{/foreach}
  <hr size="1" color="#97ACBA" noshade />
  {/if}
{elseif $adminlinks}
  {foreach key=key item=item from=$adminlinks name=i}<a href="{$key}"{if $cur_page==$key} class="stay"{/if}>{$item}</a>{if !$smarty.foreach.i.last} | &nbsp;{/if}{/foreach}
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
