<meta http-equiv="refresh" content="2" >
<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
<script src="/js/main.js"></script>
<body id="report_bug">
<?php
try {
    $dbh = new PDO('mysql:host=localhost;dbname=timetable', 'root', '', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
$timetable = (isset($_GET['timetable']))?(($_GET['timetable']=='timetable_green')?"timetable_green":"timetable_red"):"timetable_green";
$dbh->query("UPDATE ". $timetable. " SET is_short='1', visible='1', colspan='1'");
$week = (isset($_GET['timetable']))?(($_GET['timetable']=='timetable_green')?"Зеленая":"Красная"):"Зеленая";
$years_period = date('Y') . " - " . (date('Y')+1);
$semester = $_GET['course'] * 2 - 1 . " СЕМЕСТР (ОСЕННИЙ)";
$groups_array = $dbh->query('SELECT group_name FROM groups WHERE course='. $_GET['course'] .' ORDER BY sort, group_name')->fetchAll(PDO::FETCH_COLUMN);
$days_of_week = array('П<br>О<br>Н<br>Е<br>Д<br>Е<br>Л<br>Ь<br>Н<br>И<br>К', 'В<br>Т<br>О<br>Р<br>Н<br>И<br>К', 'С<br>Р<br>Е<br>Д<br>А', 'Ч<br>Е<br>Т<br>В<br>Е<br>Р<br>Г', 'П<br>Я<br>Т<br>Н<br>И<br>Ц<br>А', 'С<br>У<br>Б<br>Б<br>О<br>Т<br>А');

foreach ($days_of_week as $day_of_week) {
	
	for ($i=1; $i<=6 ; $i++) { 
	$lessons_id_s = '';
	$count = 1; 
	
	foreach ($groups_array as $group) {
		$stmt = $dbh->prepare('SELECT * FROM ' . $timetable . ' WHERE ' . $timetable . '.day = :day AND ' . $timetable . '.study_group = :group_name AND ' . $timetable . '.order_num=' . $i);
		$stmt->execute(array('day' => str_replace("<br>", "", $day_of_week), 'group_name' => $group));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if(!is_array($row)) {
			$count=1;
			$lessons_id_s = '';
			continue;
		}
		if(is_array($row)) {
			if($lessons_id_s!==$row['lesson_id']) {
				$ident=$row['uniq_id'];
				$count=1;
				//echo "First id:" . $row['lesson_id'] . " : " . str_replace("<br>", "", $day_of_week) . "<br>";
				//echo $row['lesson_id'] . "<br>";
			} else {
				$count++;
				//echo "ident: " . $ident . "count: " . $count;
				$dbh->query("UPDATE ". $timetable. " SET visible='0' WHERE uniq_id = '". $row['uniq_id'] ."'");
				$dbh->query("UPDATE ". $timetable. " SET colspan='" .$count. "', is_short='" . (($count>2)?0:1) . "' WHERE uniq_id = '". $ident ."'");
			}
			$lessons_id_s=$row['lesson_id'];
		}
	}
	}

}
function print_lesson($day_of_week, $group, $i) {
	global $dbh, $timetable;
	$stmt = $dbh->prepare('SELECT * FROM '. $timetable. ' LEFT JOIN lessons ON (lessons.id = '. $timetable. '.lesson_id) WHERE '. $timetable. '.study_group = :group_name AND '. $timetable. '.order_num = :ordr_num AND '. $timetable. '.day = :day');
	$stmt->execute(array('group_name' => $group, 'ordr_num' => $i, 'day' => str_replace("<br>", "", $day_of_week)));
	if(is_array($row = $stmt->fetch(PDO::FETCH_ASSOC))) {
		if($row['visible']==1) {
		echo "<td colspan=\"" . $row['colspan'] . "\" style=\"text-align:center; white-space: nowrap;\">";
    	echo $row[($row['is_short']=='1')?'name_short':'name'] . "<br><i>" . $row[($row['is_short']=='1')?'teacher_short':'teacher'] . "</i><br>" . $row['room'] . "\n";
		echo "</td>";
	}
	} else {
	echo "<td style=\"background: #C0C0C0\"></td>";
	}
}
?>
<style>
   table { 
    /*border: 2px solid black;*/
    border-collapse: collapse;
    font-weight: bold;
   }
   th { 
    padding: 5px;

   }
   td { 
    padding: 5px;
    border: 2px solid black;
   }
   .border_l_r {
   	border-left: 3px solid black;
   	border-right: 3px solid black;
   	color:<?php echo ($timetable=='timetable_green')?'#33FF00':'#FF0000'; ?>;
   	font-size: 1.3em;
   }
   .timetable {
   	text-align:center;
   	background: <?php echo ($timetable=='timetable_green')?'#33FF00':'#FF0000'; ?>;
   }
  </style>
<table style="width:100%">
	<tr>
		<th colspan="7" style="text-align:left;">Утверждаю</th>
		<th class="border_l_r" colspan="2" rowspan="3"><?php echo $_GET['course'];?> курс</th>
		<th colspan="7"><?php echo $years_period;?></th>
	</tr>
	<tr>
		<th colspan="7" style="text-align:left;">Директор Физико-технического института</th>
		<th colspan="7">РАСПИСАНИЕ ЗАНЯТИЙ<br><?php echo $_GET['course'];?>-го КУРСА</th>
	</tr>
	<tr>
		<th colspan="7" style="text-align:right;">доцент Глумова М.В.</th>
		<th colspan="7">ФИЗИКО-ТЕХНИЧЕСКОГО ИНСТИТУТА</th>
	</tr>
	<tr>
		<th colspan="7">&nbsp;</th>
		<th class="border_l_r" colspan="2" rowspan="2"><?php echo $week;?> неделя</th>
		<th rowspan="2" colspan="7"><?php echo $semester;?></th>
	</tr>
	<tr>
		<th colspan="7">"___" _____________ 2015 г.</th>
	</tr>
	<tr>
		<td class="timetable" colspan="2">&nbsp;</td>
		<?php
			foreach ($groups_array as $group) {
				echo "<td class=\"timetable\"> $group </td>";
			}
		?>
	</tr>
	<?php
			foreach ($days_of_week as $day_of_week) {
				echo "<tr>";
				echo "<td rowspan=\"5\" class=\"timetable\"> $day_of_week </td>";
				for ($i=1; $i <= 5 ; $i++) { 
					if ($i==1) {
						echo "<td class=\"timetable\">1</td>";
						foreach ($groups_array as $group) {
							echo print_lesson($day_of_week, $group, $i);
						}
						echo "</tr>";
					} else {
					echo "<tr>";
					echo "<td class=\"timetable\">$i</td>";
					foreach ($groups_array as $group) {
							echo print_lesson($day_of_week, $group, $i);
						}
					echo "</tr>";
					}
				}
			}
	?>
</table>
</body>