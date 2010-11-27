{if $smarty.session.idalm_user eq 'admin' || $credentials eq 'full' || ($credentials eq 'read' && $smarty.session.accion == 'leer') || $credentials eq 'edit'}
  {if $add===true || $row}
  {dataform dd=$dd keys=$keys title=$title row=$row name="new" object=$object edit=$edit options=$options is_child=$is_child|default:false preset=$preset}
  {/if}
{else}&nbsp;<!--No esta permito agregar solo modificar-->{/if}
