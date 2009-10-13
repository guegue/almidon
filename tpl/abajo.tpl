{include file="$header"}
<table>
<tr valign="top">
<td>
{if $smarty.const.DB3 === true}
  {datagrid2 rows=$rows key=$key title=$title dd=$dd options=$options maxcols=$maxcols|default:5 maxrows=$maxrows|default:10 paginate=true cmd=$cmd|default:true name=$object have_detail=$have_detail num_rows=$num_rows search=$search}
{else}
  {if $rows}
  {datagrid rows=$rows key=$key title=$title dd=$dd options=$options maxcols=$maxcols|default:5 maxrows=$maxrows|default:5 paginate=true cmd=true name=$object num_rows=$num_rows}
  {else}
  No hay datos.
  {/if}
{/if}
</td>
</tr>

<tr>
<td>
   {if in_array(1,$credenciales) || ((in_array(4,$credenciales) && $smarty.session.accion == 'leer')) || (in_array(2,$credenciales))}
	   {if $add===true || $row}{if $smarty.const.DB3 === true}
	        {dataform2 dd=$dd key=$key title=$title row=$row name="new" object=$object edit=$edit options=$options}
	   {else}
	        {dataform dd=$dd key=$key title=$title row=$row name="new" object=$object edit=$edit options=$options}
	   {/if}
	   {else}&nbsp;<!--No esta permito agregar solo modificar-->{/if}
   {/if}	   
</td>
</tr>
</table>
{section name=i loop=$detail}
<br />
{if $detail[i]._fkey}
<h2>Detalle: {$detail[i].title}</h2>
<table>
<tr valign="top">
<td>
  {if $detail[i].rows}
    {datagrid2 parent=$detail[i]._fkey rows=$detail[i].rows key=$detail[i].key title=$detail[i].title dd=$detail[i].dd maxcols=$detail[i].maxcols|default:5 maxrows=$detail[i].maxrows|default:8 paginate=true cmd=true name=$detail[i].name object=$detail[i].name options=$detail[i]._options is_detail=true num_rows=$detail[i].num_rows}
    <br /><a href="javascript:openwindow('{$detail[i].name}.php?parent={$detail[i]._fkey}&{$detail[i]._fkey}={$detail[i]._fkey_value|escape}');">Agregar</a>
  {else}
    No existen items relacionados. <a href="javascript:openwindow('{$detail[i].name}.php?parent={$detail[i]._fkey}&{$detail[i]._fkey}={$detail[i]._fkey_value|escape}');">Agregar detalle</a>
  {/if}
</td>
</tr>
</table>
{/if}
{/section}
{include file="$footer"}
