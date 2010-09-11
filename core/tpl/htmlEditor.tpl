<html>
<head>
<script language="JavaScript" type="text/javascript" src="/cms/html/wysiwyg.js"></script>
{literal}
<script lanpuage="javascript" type="text/javascript">
<!--
function save() {
 //window.opener.insertHTML(document.forms['textform'].text.value, '{/literal}{$smarty.request.field}{literal}')//
 window.document.write(document.forms['textform'].wysiwygtext.getAttribute);
 //opener.focus();
 //window.close();
 }
 //-->
</script>
{/literal}
</head>
<body>
<form name="textform" method="post" action="">
<input type="submit" value="guardar" onclick="save()" />
<br />
<input type="hidden" name="field" value="{$smarty.request.form}" />
<input type="hidden" name="field" value="{$smarty.request.field}" />
<textarea id="text" name="text" rows=20 cols=80></textarea>
<script language="JavaScript">generate_wysiwyg('text');</script>
</form>
</body>
</html>
