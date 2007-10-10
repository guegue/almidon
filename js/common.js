  function edittext(form, field, text)
  {
    desktop = window.open('text','_text','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=700,height=400,screenX=100,screenY=50,top=100,left=50');
    html = '<html>\n<head>\n<script lanpuage="javascript" type="text/javascript">\n<!--\nfunction save() {\n';
    html += 'opener.document.forms[\'' + form + '\'].' + field + '.value = document.forms[\'textform\'].text.value;\n opener.focus();\n window.close();\n }\n //-->\n<\/script>\n';
    html += '</head><body><form name="textform" method="post" action=""><input type="submit" value="guardar" onclick="save()" /> <br/><input type="hidden" name="field" value="' + form + '" /><input type="hidden" name="field" value="' + field + '" /><textarea name="text" rows=20 cols=80>' + text + '</textarea></form></body></html>';
    desktop.document.write(html);
    desktop.document.close();
  }

  function openwindow(href, w, h) {
    if (!w) w = 350;
    if (!h) h = 160;
    desktop = window.open('','_blank','toolbar=no,location=no,status=no,menubar=no,  scrollbars=yes,resizable=yes,width='+ w +',height='+ h +',screenX=100,screenY=50,top=100,left=50');
    desktop.location.href = href;
  }

  function openimage(url) {
    image = new Image();
    image.src = url;
    desktop = window.open(url,'_blank','toolbar=no,width='+image.width+',height='+image.height+'');
    desktop.focus();
  } 

        function doPostBack( selectedaction ) {
                document.dataform.f.value = selectedaction;
                document.dataform.submit();
        }
        function postBack( theform, selectedaction ) {
                theform.action.value = selectedaction;
                theform.submit();
        }
