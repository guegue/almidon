{include file="header.tpl"}
<h1>{$title} {if $title && $row.$obj}:{/if} {$row.$obj}</h1>
{if $row}
  {if $row.imagen && !$imagen.imagen}
    <img src="/cms/pic/250/{$obj}/{$row.imagen}" align="left" class="imagen" />
  {/if}
  {if $row.direccion}
    <b>Direcci&oacute;n:</b> {$row.direccion}
    <br/>
  {/if}
  {if $row.telefono}
    <b>Tel&eacute;fono:</b> {$row.telefono}
    <br/>
  {/if}
  {if $row.texto}
    {$row.texto|nl2br}
  {elseif $row.resumen}
    {$row.resumen|nl2br}
  {elseif $row.descripcion}
    {$row.descripcion|nl2br}
  {/if}

  {*
   *  Muestra imagen de galeria
   *}
  <table align="center" border="0">
  <tr><td align="center">
  {if $imagen.imagen}
    <br/><img src="/cms/pic/400/{$foto}/{$imagen.imagen}" alt="imagen" class="imagen"/><br/><br/>
  {/if}
  </td></tr>
  <tr><td align="center">
  {section name=j loop=$fotos}
    <a href="/{$foto}/{$fotos[j].$idfoto}"><img src="/cms/pic/75/{$foto}/{$fotos[j].imagen}" align="left" class="imagen" /></a>
  {/section}
  </td></tr>
  </table>

  {if $row.url}
    <br /><br />
    <a target="_blank" href="{$row.url}">{$row.url}</a><br/>
  {/if}
  {if $row.archivo}
    <br/>
    <a href="/files/doc/{$row.archivo}"><img src="{$row.archivo|ficon:"doc"}" border="0" />&nbsp;Descargar Archivo&nbsp;({$row.archivo|fsize:"doc":"KB"})</a>
  {/if}
  <br clear="all" /><br />
{else}

  {* 
   *  Muestra de coleccion de datos
   *}
  {section name=i loop=$rows}
  {if $rows[i].texto || $rows[i].descripcion}
    <h2>{$rows[i].$obj}</h2>
  {else}
    <br/><a href="/{$obj}/{$rows[i].$key}" class="h2">{$rows[i].$obj}</a><br/>
  {/if}
  {if $rows[i].imagen}
    <img src="/cms/pic/75/{$obj}/{$rows[i].imagen}" alt="imagen" align="left" class="imagen"/>
  {/if}
  {if $rows[i].texto}
    {$rows[i].texto|truncate:500}
  {elseif $rows[i].resumen}
    {$rows[i].resumen|truncate:500}
  {elseif $rows[i].descripcion}
    {$rows[i].descripcion|truncate:500}
  {/if}
  {if $rows[i].archivo}
    <a href="/files/doc/{$rows[i].archivo}"><img src="{$rows[i].archivo|ficon:"doc"}" border="0" />{$rows[i].archivo|fsize:"doc":"KB"}</a>
  {/if}
  {if $rows[i].texto || $rows[i].descripcion || $rows[i].resumen}
    <a href="/{$obj}/{$rows[i].$key}">m&aacute;s...</a><br/>
  {/if}
  <br clear="all"/>
  {/section}
{/if}
{include file="footer.tpl"}
