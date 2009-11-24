{include file="$header"}
<table>
<tr valign="top">
<td>
{if $rows}
  <form action="{$smarty.const.SELF}" method="POST" name="" enctype="multipart/form-data">
  <table class="dgtable" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <th>Usuarios</th><th align="right"><div align="right">({$num_records} registros)</div></th>
  </tr>
  <tr>
    <td colspan="2">
    <table class="dgsubtable" border="0" cellspacing="0" cellpadding="0">
    <tr>
      {foreach name=i from=$dd item=column}
      <th><a class="dgheader_link" href="{$smarty.const.SELF}?sort={$column.name}">{$column.label}</a></th>
      {/foreach}
      <th>Opciones</th>
    </tr>
    {section name=i loop=$rows}
    <tr class="dgrow">
      <td class="dgcell">{$rows[i].usuario}</td><td class="dgcell">{$rows[i].passwd}</td><td class="dgcmd"><a href="javascript:confirm_delete('','usuario','{$rows[i].usuario}','{$rows[i].usuario}');"><img src="/cms/img/delete.png" border="0" alt="Borrar"></a><a href="{$smarty.const.SELF}?action=mod&usuario={$rows[i].usuario}"><img src="/cms/img/edit.png" border="0" alt="Editar"></a></td></tr>
    {/section}
    </table>
    </td>
  </tr>
  </table>
  </form>
{/if}
</td>
<td>
<form action="{$smarty.const.SELF}" method="post" name="new" enctype="multipart/form-data">
  {if $smarty.request.action != 'mod'}
  <input name="action" value="add" type="hidden">
  {else}
  <input name="action" value="save" type="hidden">
  <input name="usuario" value="{$smarty.request.usuario}" type="hidden">
  {/if}
  <table class="dgtable" border="0" cellpadding="2" cellspacing="0">
  <tr>
    <th>Usuarios</th>
  </tr>
  <tr>
  <td>
    <table class="dgsubtable" border="0" cellpadding="0" cellspacing="0">
    <tr class="dgrow" valign="top">
      <td class="dgcell">Nombre de Usuario</td>
      <td class="dgcell">{if $smarty.request.action != 'mod'}<input name="usuario" type="text" size="10" maxlength="20" />{else}{$smarty.get.usuario}{/if}</td>
    </tr>
    <tr class="dgrow" valign="top">
      <td class="dgcell">Contraseña</td>
      <td class="dgcell"><input name="passwd" type="password" size="10" maxlength="16" /></td>
    </tr>
    <tr>
      <td class="dgcmd">
       {if $smarty.request.action != 'mod'}
       <input value="Agregar" type="submit">
       {else}
       <input value="Guardar" type="submit" />&nbsp;<input value="Cancelar" type="button" onclick="location.href='{$smarty.const.SELF}'" />
       {/if}
      </td>
    </tr>
    </table>
  </td>
  </tr>
  <tr><td></td></tr>
  </table>
</form>
</td>
</tr>
</table>
{include file=$footer}
