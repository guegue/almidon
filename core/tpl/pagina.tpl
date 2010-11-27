{include file=$header}
<h1>{$row.pagina}</h1>
{if $row.imagen1}<img src="/cms/pic/230/pagina/{$row.imagen1}" align="right"/>{/if}
{if $row.imagen2}<img src="/cms/pic/230/pagina/{$row.imagen2}" align="right"/>{/if}
<p>{$row.texto|url|nl2br}</p>
{if $row.archivo}<a href="/files/pagina/{$row.archivo}"><img src="/cms/img/pdf.png" border="0" alt="PDF" width="16" height="16" /> Descargar archivo</a>{/if}
{include file=$footer}
