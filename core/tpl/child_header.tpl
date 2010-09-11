<?xml version="1.0"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<script language="JavaScript1.2" type="text/javascript" src="/cms/js/common.js"></script>
<link rel="stylesheet" href="/cms/css/adm.css"/>
<title>{$title}</title>
<script language="javascript" type="text/javascript">
{literal}
<!--
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
-->
{/literal}
</script>
</head>
<body>
<h1>{$title|default:""}</h1>
