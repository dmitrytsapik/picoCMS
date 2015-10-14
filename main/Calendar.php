<?php
class Calendar extends Page_Template {
	/* Исходный код был взят тут:
	   http://www.phpfaq.ru/calendar
	   соответственно потом был переработан :) */
    private function make_calendar ($y, $m) {
    	$fill = array(date("Y-m-d"));
        $month_names=array("Январь","Февраль","Март","Апрель","Май","Июнь", "Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"); 
		$month_stamp=mktime(0,0,0,$m,1,$y);
  		$day_count=date("t",$month_stamp);
  		$weekday=date("w",$month_stamp);
  		if ($weekday==0) $weekday=7;
  		$start=-($weekday-2);
  		$last=($day_count+$weekday-1) % 7;
  		if ($last==0) $end=$day_count; else $end=$day_count+7-$last;
  		$today=date("Y-m-d");
  		$i=0;
?> 
<style>
.flexbox {
  display: flex; /* or inline-flex */
  justify-content: center;
  flex-wrap: wrap;
}
table {
	border: 1px solid;
	border-collapse: collapse;
	margin: 1em;
}
td {
	text-align: center;
	padding: 0.3em;
	border: 1px solid black; /* Граница вокруг ячеек */
}
.wr {
	background-color: #F80000;
	
}
.wg {
	background-color: #00FF00;
}
</style>
<table cellspacing=0 cellpadding=2> 
 <tr>
  <td colspan=7> 
     <b><?php echo $month_names[$m-1]; ?></b>
  </td> 
 </tr> 
 <tr><td>Пн</td><td>Вт</td><td>Ср</td><td>Чт</td><td>Пт</td><td>Сб</td><td>Вс</td><tr>
<?php 
  for($d=$start;$d<=$end;$d++) { 
    if (!($i++ % 7)) { 
    	// выведет: July 1, 2000 is on a Saturday
		$background = (date("W", mktime(0, 0, 0, $m, $d, $y)) % 2)?"wr":"wg";
    	echo " <tr>\n"; }
    echo "  <td class=\"$background\">";
    if ($d < 1 OR $d > $day_count) {
      echo "&nbsp";
    } else {
      $now="$y-$m-".sprintf("%02d",$d);
      if (is_array($fill) AND in_array($now,$fill)) {
        echo '<b style="color: blue; font-weight:bold;"><!--<a href="'.$_SERVER['PHP_SELF'].'?date='.$now.'">-->'.$d.'<!--</a>--></b>'; 
      } else {
        echo $d;
      }
    } 
    echo "</td>\n";
    if (!($i % 7))  echo " </tr>\n";
  } 
echo "</table>"; 
    }
    public function make() {
        $this->Header_Page("Календарь - ФТИ КФУ им. В. И. Вернадского");
        echo "<h1>Календарь на 2015 &mdash; 2016 учебный год</h1>";
        echo "<div class=\"flexbox\">";
        //$y=date("Y");
  		//$m=date("m");
  		$y=2015;
  		for ($i=9; $i <= 12 ; $i++) { 
	        $this->make_calendar($y, $i);
  		}
        for ($i=2; $i <= 5 ; $i++) { 
	        $this->make_calendar($y + 1, $i);
  		}
	    echo "</div>";
        $this->Footer_Page();
    }
}
?>