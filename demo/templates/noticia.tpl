{include file="header.tpl"}
{debug}
<h1>Noticias</h1>
{if $row}
<table border="1">
<tr><td>ID</td><td>{$row.idnoticia}</td></tr>
<tr><td>Titulo</td><td>{$row.noticia}</td></tr>
<tr><td>Fecha</td><td>{$row.fecha}</td></tr>
<tr><td>Texto</td><td>{$row.texto}</td></tr>
<tr><td>Archivo CDN</td><td><a href="{$smarty.const.CDN_URL}/{$row.archivo}">{$row.archivo}</a></td></tr>
<tr><td>Foto grande</td><td><img src="{$smarty.const.CDN_URL}/{$row.fotocdn}" width="250"/></td></tr>
<tr><td>Foto</td><td><img src="/cms/pic/100/noticia/{$row.foto}"/></td></tr>
</table>
{else}
<table border="1">{section name=i loop=$rows}
{if $smarty.section.i.first}<tr>
<td>ID</td><td>Titulo</td><td>Fecha</td><td>Texto</td><td>Archivo CDN</td><td>Foto grande</td><td>Foto</td></tr>
{/if}
<tr><td><a href="/noticia/{$rows[i].idnoticia}">{$rows[i].idnoticia}</a></td><td>{$rows[i].noticia}</td><td>{$rows[i].fecha}</td><td>{$rows[i].texto|truncate}</td><td><a href="{$smarty.const.CDN_URL}/{$rows[i].archivo}">{$rows[i].archivo}</a></td><td><img src="{$smarty.const.CDN_URL}/{$rows[i].fotocdn}" width="100"/></td><td><img src="/cms/pic/100/noticia/{$rows[i].foto}"/></td></tr>
{/section}
</table>
{/if}
{include file="footer.tpl"}
