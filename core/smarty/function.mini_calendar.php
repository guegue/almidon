<?
/*
Created by: Christian Torres
Modified: 31-Marzo-2008
*/
/**
* Description: Create a Calendar using tables, complete customizable with CSS
**/

function getmicrotime(){
    list($usec, $sec) = explode(" ",microtime());
    return ((float)$usec + (float)$sec);
}

/*
Select Days
$selectedDays = array (
    new Calendar_Day($_GET['y'],$_GET['m'],$_GET['d']),
    new Calendar_Day($_GET['y'],12,25),
    );

// Build the days in the month
$Month->build($selectedDays);
*/

function smarty_function_mini_calendar($params, &$smarty){
  $start = getmicrotime();

  # includes
  require_once 'Calendar/Calendar.php';
  require_once 'Calendar/Month/Weekdays.php';
  require_once 'Calendar/Day.php';
  require_once $smarty->_get_plugin_filepath('modifier','date_format');
  require_once $smarty->_get_plugin_filepath('function','html_select_date');
  require_once $smarty->_get_plugin_filepath('modifier','strip');

  # Cuando se hacia un redirect usando el ErrorDocument del 403 al archivo, no se pasaban los
  # parametros
  if((!isset($_REQUEST['Year'])&&!isset($_REQUEST['Month']))||!isset($_REQUEST['Day'])) {
    $query = parse_url($_SERVER['REQUEST_URI'],PHP_URL_QUERY);
    parse_str($query,$_REQUEST);
  }
  if (!isset($_REQUEST['Year'])) $_REQUEST['Year'] = date('Y');
  if (!isset($_REQUEST['Month'])) $_REQUEST['Month'] = date('m');
  if (!isset($_REQUEST['Day'])) $_REQUEST['Day'] = date('d');
  
  // Build the month
  $Month = new Calendar_Month_Weekdays($_REQUEST['Year'],$_REQUEST['Month']);
  
  // Construct strings for next/previous links
  $PMonth = $Month->prevMonth('object'); // Get previous month as object
  $prev = '?Year='.$PMonth->thisYear().'&amp;Month='.$PMonth->thisMonth().'&amp;Day='.$PMonth->thisDay();
  $NMonth = $Month->nextMonth('object');
  $next = '?Year='.$NMonth->thisYear().'&amp;Month='.$NMonth->thisMonth().'&amp;Day='.$NMonth->thisDay();
  
  // Build the days in the month
  if($params['selectedDays'])
    foreach($params['selectedDays'] as $selectedDay) {
      $selectedDays[] = new Calendar_Day(smarty_modifier_date_format($selectedDay,"%Y"),smarty_modifier_date_format($selectedDay,"%m"), smarty_modifier_date_format($selectedDay,"%d"));
    }
  
  $class = ($params['custom_class']?$params['custom_class']:'');
  $Month->build($selectedDays);
  
  $out = "<table class=\"calendar\">";
  $out .= "<caption>".smarty_modifier_date_format($Month->getTimeStamp(),"%B %Y")."</caption>";
  $out .= "<tr>";
  $out .= "<th>Lun</th>";
  $out .= "<th>Mar</th>";
  $out .= "<th>Mie</th>";
  $out .= "<th>Jue</th>";
  $out .= "<th>Vie</th>";
  $out .= "<th".($params['dif_weekend']?" class=\"weekend sat\"":"").">Sáb</th>";
  $out .= "<th".($params['dif_weekend']?" class=\"weekend sun\"":"").">Dom</th>";
  $out .= "</tr>";
  while ( $Day = $Month->fetch() ) {
    // Build a link string for each day
    //$link = $_SERVER['PHP_SELF'].
    $link =     ($params['action']?$params['action']:"").'?Year='.$Day->thisYear().
                '&amp;Month='.$Day->thisMonth().
                '&amp;Day='.$Day->thisDay();

    
    //	Contain the week's day's number, 0 = sunday, ..., 6 = saturday
    $num_day = date("w",strtotime($Day->thisYear()."-".$Day->thisMonth()."-".$Day->thisDay()));
    // isFirst() to find start of week
    if ( $Day->isFirst() )
        $out .= "<tr>\n";

    if ( $Day->isSelected() ) {
        $out .= "<td class=\"selected\">";
        if($params['dif_weekend'])
          if($num_day == 6)
            $out .= "<div class=\"weekend sat\">";
          elseif($num_day == 0)
            $out .= "<div class=\"weekend sun\">";
          elseif($Day->thisDay()==date("d") AND $Day->thisMonth()==date("m") AND $Day->thisYear()==date("Y"))
            $out .= "<div class=\"now\">";
        $out .= "<a href=\"".$link."\"".(($class)?" class=\"$class\"":"").">".$Day->thisDay()."</a>";
        if(($params['dif_weekend'] && ($num_day == 6 || $num_day == 0)) || ($Day->thisDay()==date("d") && $Day->thisMonth()==date("m") && $Day->thisYear()==date("Y")))
          $out .= "</div>";
        $out .= "</td>\n";
    } else if ( $Day->isEmpty() ) {
        $out .= "<td>&nbsp;</td>\n";
    } elseif($Day->thisDay()==date("d") AND $Day->thisMonth()==date("m") AND $Day->thisYear()==date("Y")) {
        $out .= "<td><div class=\"now\">".$Day->thisDay()."</div></td>\n";
    } else {
        $out .= "<td>";
        if($num_day == 6 && $params['dif_weekend'])
          $out .= "<div class=\"weekend sat\">".$Day->thisDay()."</div>";
        elseif($num_day == 0 && $params['dif_weekend'])
          $out .= "<div class=\"weekend sun\">".$Day->thisDay()."</div>";
        else $out .= $Day->thisDay();
	$out .= "</td>\n";
    }
    // isLast() to find end of week
    if ( $Day->isLast() )
        $out .= "</tr>\n";
  }
 
  $start_year = ($params['start_year'])?$params['start_year']:"-5";
  $end_year = ($params['end_year'])?$params['end_year']:"+5";
  if (!isset($params['display_date'])) $params['display_date'] = true;
  $display_date = $params['display_date'];
  $day = $_REQUEST['Year']."-".$_REQUEST['Month']."-01";
  if($display_date) {
    $out .= "<tr>";
    $out .= "<td colspan=\"7\" class=\"control\"><form method=\"get\" action=\"".$params['action']."\"";
    $out .= ($params['frm_extra']?" ".$params['frm_extra']:"").">".smarty_function_html_select_date(array('time'=>$day,'prefix'=>'','start_year'=>$start_year,'end_year'=>$end_year,'display_days'=>false, 'display_months'=>true, 'display_years'=>true, 'month_extra'=>'id="Month"', 'year_extra'=>'id="Year"'),$smarty)."&nbsp;".($params['btn_img']?"<input name=\"btnsubmit\" type=\"image\" src=\"".$params['btn_img']."\"/>":"<input name=\"btnsubmit\" type=\"submit\" value=\"Ir\" />").($params['today_btn']===true?"&nbsp;<input name=\"today\" type=\"button\" value=\"Hoy\" title=\"Presione para ir al mes en curso\" onclick=\"location.href = './'\" />":"")."</form></td>";
    $out .= "</tr>";
  }
  $out .= "</table>";
  $out = smarty_modifier_strip($out);
  return $out;
}
?>
