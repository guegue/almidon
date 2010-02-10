{include file="$header"}
<table>
<tr valign="top">
<td>
{if $smarty.const.DB3 === true}
  {datagrid2 rows=$rows key=$key title=$title dd=$dd options=$options maxcols=$maxcols|default:5 maxrows=$maxrows|default:8 paginate=true cmd=$cmd|default:true name=$object have_detail=$have_detail num_rows=$num_rows shortEdit=$shortEdit}
{else}
  {if $rows}
  {datagrid rows=$rows key=$key title=$title dd=$dd options=$options maxcols=$maxcols|default:5 maxrows=$maxrows|default:$smarty.const.MAXROWS paginate=true cmd=$cmd|default:true name=$object num_rows=$num_rows}
  {else}
  No hay datos.
  {/if}
{/if}
</td>
<td>
    {if $smarty.session.idalm_user eq 'admin' || $credentials eq 'full' || ($credentials eq 'read' && $smarty.session.accion == 'leer') || $credentials eq 'edit'}
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
<br/>
{if $detail._fkey}
<h2>Detalle</h2>
<table>
<tr valign="top">
<td>
  {if $detail.rows}
    {datagrid parent=$detail._fkey rows=$detail.rows key=$detail.key" title=$detail.title dd=$detail.dd maxcols=$detail.maxcols|default:5 maxrows=$detail.maxrows|default:15 paginate=true cmd=true name=$detail.name object=$detail.name options=$detail._options is_detail=true num_rows=$detail.num_rows}
    <br /><a href="javascript:openwindow('{$detail.name}.php?parent={$detail._fkey}&{$detail._fkey}={$detail._fkey_value|escape}');">Agregar</a>
  {else}
    No existen items relacionados. <a href="javascript:openwindow('{$detail.name}.php?parent={$detail._fkey}&{$detail._fkey}={$detail._fkey_value|escape}');">Agregar detalle</a>
  {/if}
</td>
</tr>
</table>
{/if}
{include file="$footer"}
