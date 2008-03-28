<?xml version="1.0"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN">
<html>
<head>
<script language="JavaScript1.2" type="text/javascript" src="/cms/js/common.js"></script>
<script language="JavaScript" type="text/javascript" src="/cms/html/wysiwyg.js"></script>
<link rel="stylesheet" href="/cms/css/adm.css">
<title>{$title}</title>
{if $closed}
<script>
window.close();
</script>
{/if}
<script language="javascript">
{literal}
  function confirm_delete(o, idfield, id, desc) {
    if (window.confirm('"'+desc+'": Estas seguro de querer borrar este registro?')) {
        {/literal}location.href = '{$smarty.server.PHP_SELF}?o='+o+'&action=delete&'+idfield+'='+id;{literal}
    }
  }

  function confirm_delete2(o, idfield1, idfield2, id1, id2, desc) {
    if (window.confirm('"'+desc+'": Estas seguro de querer borrar este registro?')) {
        {/literal}location.href = '{$smarty.server.PHP_SELF}?o='+o+'&action=delete&'+idfield1+'='+id1+'&'+idfield2+'='+id2;{literal}
    }
  }
{/literal}
</script>
</head>
<body>
{if $row}
  {if $smarty.const.DB3 === true}{dataform2 dd=$dd key=$key title=$title row=$row name="new" object=$object edit=$edit options=$options}{else}{dataform dd=$dd key=$key title=$title row=$row name="new" object=$object edit=$edit options=$options}{/if}
{else}
  No hay datos.
{/if}
</body>
</html>
