<?
//  Define this class which is necesary
define('CALENDAR_ENGINE', 'PearDate');
//  Incluido por la clase Calendar_Decorator
include_once 'Calendar/Calendar.php';
require_once 'Calendar/Month/Weekdays.php';
require_once 'Calendar/Day.php';
require_once 'Calendar/Decorator.php';


define('CALENDAR_ENGINE', 'PearDate');
//  Classes necesaries
// accepts multiple entries
class DiaryEvent extends Calendar_Decorator
{
  
    var $entries = array();

    function DiaryEvent($calendar) {
        Calendar_Decorator::Calendar_Decorator($calendar);
    }

    function addEntry($entry) {
      define('CALENDAR_ENGINE', 'PearDate');
        $this->entries[] = $entry;
    }

    function getEntry() {
      define('CALENDAR_ENGINE', 'PearDate');
        $entry = each($this->entries);
        if ($entry) {
            return $entry['value'];
        } else {
            reset($this->entries);
            return false;
        }
    }
}

class MonthPayload_Decorator extends Calendar_Decorator
{
    //Calendar engine
    var $cE;
    var $tableHelper;

    var $year;
    var $month;
    var $firstDay = false;

    function build($events=array())
    {
        //require_once 'Calendar/Day.php';
        require_once 'Calendar/Table/Helper.php';
        $this->tableHelper = & new Calendar_Table_Helper($this, $this->firstDay);
        $this->cE = & $this->getEngine();
        $this->year  = $this->thisYear();
        $this->month = $this->thisMonth();

        $daysInMonth = $this->cE->getDaysInMonth($this->year, $this->month);
        for ($i=1; $i<=$daysInMonth; $i++) {
            $Day = new Calendar_Day(2000,1,1); // Create Day with dummy values
            $Day->setTimeStamp($this->cE->dateToStamp($this->year, $this->month, $i));
            $this->children[$i] = new DiaryEvent($Day);
        }
        if (count($events) > 0) {
            $this->setSelection($events);
        }
        Calendar_Month_Weekdays::buildEmptyDaysBefore();
        Calendar_Month_Weekdays::shiftDays();
        Calendar_Month_Weekdays::buildEmptyDaysAfter();
        Calendar_Month_Weekdays::setWeekMarkers();
        return true;
    }

    function setSelection($events)
    {
        $daysInMonth = $this->cE->getDaysInMonth($this->year, $this->month);
        for ($i=1; $i<=$daysInMonth; $i++) {
            $stamp1 = strftime("%Y-%m-%d", strtotime($this->year . '-' . $this->month . '-' . $i));
            #$stamp1 = $this->cE->dateToStamp($this->year, $this->month, $i);
            #$stamp2 = $this->cE->dateToStamp($this->year, $this->month, $i+1);
            foreach ($events as $event) {
              // Listar solo los dias de inicio del evento
              list($start, $time) = preg_split('/ /', $event['start']);
              if ($stamp1 == $start) {
                    $this->children[$i]->addEntry($event);
                    $this->children[$i]->setSelected();
              }
                /*
                // Listar todos los dias mientras dure el evento
                if (($stamp1 >= $event['start'] && $stamp1 < $event['end']) ||
                    ($stamp2 >= $event['start'] && $stamp2 < $event['end']) ||
                    ($stamp1 <= $event['start'] && $stamp2 > $event['end'])
                ) {
                    $this->children[$i]->addEntry($event);
                    $this->children[$i]->setSelected();
                }
                */
            }
        }
    }

    function fetch()
    {
        $child = each($this->children);
        if ($child) {
            return $child['value'];
        } else {
            reset($this->children);
            return false;
        }
    }
}


function smarty_function_calendar($params, &$smarty){

        require_once $smarty->_get_plugin_filepath('function','html_select_date');
  $days = array('Domingo', 'Lunes', 'Martes', 'Mi&eacute;rcoles', 'Jueves', 'Viernes', 'S&aacute;bado');
  $months = array(1=>'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
  $start = 'start';
  $end = 'end';
  $desc = 'desc';
  $year = '';
  $month = '';
  $id = null;
  
  //  Month is required
  if (!isset($params['month']))
    $month = date("m");  
  else
    $month = $params['month'];
  
  //  Year is required
  if (!isset($params['year']))
    $year = date("Y");
  else  $year = $params['year'];
  
  //  Name of day of the week
  if(isset($params['days'])){
    unset($days);
    $days = $params['days'];
  }
  
  
  if(isset($params['months'])){
    unset($months);
    $months = $params['months'];
  }
  
  if(isset($params['events'])){
    //  This param describe who is the field that
    //  contain the info of  star date, if there's not
    //  is the same name
    if(isset($params['start'])){
      $start = $params['start'];
    }
    //  Describe name field of the end date
    if(isset($params['end'])){
      $end = $params['end'];
    }
    //  Field describe the description of event
    if(isset($params['desc'])){
      $desc = $params['desc'];
    }
    //  Field id is number unique of the event
    if(isset($params['id'])){
      $id = $params['id'];
    }
    
    $events = array();
    //  add the events
    for($i=0;$i<count($params['events']);$i++){
      $events[] = array(
        'id' => $params['events'][$i][$id],
        'start' => $params['events'][$i][$start],
        'end' => $params['events'][$i][$end],
        'desc' => $params['events'][$i][$desc]
      );
    }
  }
  
  $Month = & new Calendar_Month_Weekdays($year, $month);
  $MonthDecorator = new MonthPayload_Decorator($Month);
  $MonthDecorator->build($events);
  
  echo '<table class="calendar" cellspacing="0" cellpadding="0" border="0">';
  echo '<caption class="caption">';
   echo $months[$MonthDecorator->thisMonth()].' / '.$MonthDecorator->thisYear();
  echo '</caption>';
  echo '<tr>';
  //  Days of weeks
  for($i=0;$i<count($days);$i++)
    echo '<th class="day_week">'.$days[$i].'</th>';
  echo '</tr>';
  
  while ($Day = $MonthDecorator->fetch()) {

    if ($Day->isFirst()) {
        echo "<tr>\n";
    }

    echo '<td class="general calCell';
    if ($Day->isSelected()) {
        echo ' calCellBusy';
    } elseif ($Day->isEmpty()) {
        echo ' calCellEmpty';
    }
    echo '">';
    
    if($Day->isEmpty())
      echo '<div class="dayNumber2">'.$Day->thisDay().'</div>';
    else
      echo '<div class="dayNumber">'.$Day->thisDay().'</div>';

    if ($Day->isEmpty()) {
        echo '&nbsp;';
    } else {
        echo '<div class="dayContents"><ul>';
        while ($entry = $Day->getEntry()) {
            echo  '<li>';
            if(isset($entry['id']))
              echo '<a href="?'.$id.'='.$entry['id'].'" class="event">'.$entry['desc'].'</a>';
             else echo $entry['desc'];
            echo '</li>';
            //you can print the time range as well
        }
        echo '</ul></div>';
    }
    echo '</td>';

    if ($Day->isLast()) {
        echo "</tr>\n";
    }
    
  }
  echo '</table>';
  $PMonth = $Month->prevMonth('object');
  $prev = $_SERVER['PHP_SELF'].'?year='.$PMonth->thisYear().'&month='.$PMonth->thisMonth();
  $NMonth = $Month->nextMonth('object');
  $next = $_SERVER['PHP_SELF'].'?year='.$NMonth->thisYear().'&month='.$NMonth->thisMonth();
  echo '<table class="calendar_option"><tr><td class="prev"><a href="'.$prev.'" class="option">&lt;&lt; '.$months[$PMonth->thisMonth()].' '.$PMonth->thisYear().'</a></td><td class="next"><a href="'.$next.'" class="option">'.$months[$NMonth->thisMonth()].' '.$NMonth->thisYear().' &gt;&gt;</a></td></tr></table>';
        echo '<form method="GET"><table><tr><td>'.smarty_function_html_select_date(array('prefix'=>'','start_year'=>'-5','end_year'=>'+5','display_days'=>false),$smarty).'</td><td></td>'.'<td><input type="submit" value="Mostrar"/></td></table></form>';
}
?>
