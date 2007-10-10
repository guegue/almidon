var count = 0;
function openPopUp (htmlFile, fileHeight, fileWidth) {
  window.open(htmlFile, count, "left=10, top=10, scrollbars=yes, status=no, height=" + fileHeight + ", width=" + fileWidth);
  count++;
}
function toggle(me) {
if (document.getElementById(me).style.visibility == 'visible') {
  document.getElementById(me).style.visibility='collapse';
} else {
  document.getElementById(me).style.visibility='visible';
}
}
function toggleDisplay(me) {
if (document.getElementById(me).style.display == 'none') {
  document.getElementById(me).style.display='inline';
} else {
  document.getElementById(me).style.display='none';
}
}
//  Funciones agregadas recientemente
function addoption(oCntrl, iPos, sTxt, sVal, selected){
  var selOpcion=new Option(sTxt, sVal,false,selected);
  eval(oCntrl.options[iPos]=selOpcion);
}
function fillCombo(arrValue){
  var Cntrl = arrValue[0];
  var oCntrl = document.getElementById(Cntrl);
  if(oCntrl) {
    while (oCntrl.length) oCntrl.remove(0);
    addoption(oCntrl, 0, "--", "-1");
    for(var i = 1; i < arrValue.length; i++)
      addoption(oCntrl, (i), arrValue[i][1], String(arrValue[i][0]),arrValue[i][2]);
    oCntrl.focus();
  }
}
