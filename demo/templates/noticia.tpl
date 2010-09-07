<html><head><title>Noticias</title></head><body><h1>Noticias</h1>
<table border="1">{section name=i loop=$rows}
{if $smarty.section.i.first}<tr>
<td>Foto grande</td><td>ID</td><td>Titulo</td><td>Fecha</td><td>Texto</td><td>Foto</td></tr>
{/if}
<tr><td><img src="{$smarty.const.CDN_URL}/{$rows[i].fotocdn}" width="100"/></td><td><a href="?idnoticia={$rows[i].idnoticia}">{$rows[i].idnoticia}</a></td><td>{$rows[i].noticia}</td><td>{$rows[i].fecha}</td><td>{$rows[i].texto}</td><td><img src="/cms/pic/100/noticia/{$rows[i].foto}"/></td></tr>
{/section}
</table>
{if $row}
<table border="1">
<tr><td>Foto grande</td><td>{$row.fotocdn}</td></tr>
<tr><td>ID</td><td>{$row.idnoticia}</td></tr>
<tr><td>Titulo</td><td>{$row.noticia}</td></tr>
<tr><td>Fecha</td><td>{$row.fecha}</td></tr>
<tr><td>Texto</td><td>{$row.texto}</td></tr>
<tr><td>Foto</td><td>{$row.foto}</td></tr>
</table>
{/if}
</body>
</html>
