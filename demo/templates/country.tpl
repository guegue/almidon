<html><head><title>Paises</title></head><body><h1>Paises</h1>
<table border="1">{section name=i loop=$rows}
{if $smarty.section.i.first}<tr>
<td>Id</td><td>Pais</td></tr>
{/if}
<tr><td><a href="?idcountry={$rows[i].idcountry}">{$rows[i].idcountry}</a></td><td>{$rows[i].country}</td></tr>
{/section}
</table>
{if $row}
<table border="1">
<tr><td>Id</td><td>{$row.idcountry}</td></tr>
<tr><td>Pais</td><td>{$row.country}</td></tr>
</table>
{/if}
</body>
</html>
