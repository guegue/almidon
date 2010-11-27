{if $rows}
{datagrid rows=$rows keys=$keys title=$title dd=$dd options=$options maxcols=$maxcols|default:5 maxrows=$maxrows|default:$smarty.const.MAXROWS paginate=true cmd=$cmd|default:true name=$object have_child=$have_child num_rows=$num_rows search=$search}
{else}
  {$smarty.const.ALM_NODATA}
{/if}
