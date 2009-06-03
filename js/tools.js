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

function friendly_url(str,max) {
  if (max === undefined) max = 32;
  var a_chars = new Array(
    new Array("a",/[áàâãªÁÀÂÃ]/g),
    new Array("e",/[éèêÉÈÊ]/g),
    new Array("i",/[íìîÍÌÎ]/g),
    new Array("o",/[òóôõºÓÒÔÕ]/g),
    new Array("u",/[úùûÚÙÛ]/g),
    new Array("c",/[çÇ]/g),
    new Array("c",/[Ññ]/g)
  );
  // Replace vowel with accent without them
  for(var i=0;i<a_chars.length;i++)
    str = str.replace(a_chars[i][1],a_chars[i][0]);
  // first replace whitespace by -, second remove repeated - by just one, third turn in low case the chars,
  // fourth delete all chars which are not between a-z or 0-9, fifth trim the string and
  // the last step truncate the string to 32 chars 
  return str.replace(/\s+/g,'-').toLowerCase().replace(/[^a-z0-9\-]/g, '').replace(/\-{2,}/g,'-').replace(/(^\s*)|(\s*$)/g, '').substr(0,max);
}

function addEvent( obj, type, fn ) {
  if ( obj.attachEvent ) {
    obj['e'+type+fn] = fn;
    obj[type+fn] = function(){obj['e'+type+fn]( window.event );}
    obj.attachEvent( 'on'+type, obj[type+fn] );
  } else
    obj.addEventListener( type, fn, false );
}
