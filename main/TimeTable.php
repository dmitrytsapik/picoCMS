<?php
class TimeTable extends Page_Template {
    public function make() {
        if(isset($_COOKIE["week_color"]) || (isset($_POST['group_set']) && isset($_POST['timetable']))) {
			if((isset($_POST['group_set']) && isset($_POST['timetable']))) {
			setcookie('group_id', null, -1, '/');
    		setcookie('week_color', null, -1, '/');
			setcookie("group_id", $_POST['group_set'], time()+60*60*24*7);
			setcookie("week_color", $_POST['timetable'], time()+60*60*24*7);
			}
			$timetable = isset($_POST['timetable'])?$_POST['timetable']:$_COOKIE["week_color"];
		}
        $this->Header_Page("Расписание занятий - ФТИ КФУ им. В. И. Вернадского");
        echo "<h1>Расписание занятий</h1><h1 style=\"color:red;\">Обращаем Ваше внимание, что расписание не соответствует действительности, а находится в режиме разработки!</h1>";
        try {
    		$dbh = new PDO('mysql:host=localhost;dbname=timetable', 'root', '', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			} catch (PDOException $e) {
    		print "Error!: " . $e->getMessage() . "<br>";
    		die();
		}
echo "<form method=\"post\" style=\"margin: 1em;\">
	<select name=\"group_set\">";
	 			$stmt = $dbh->prepare('SELECT * FROM groups ORDER BY sort, group_name');
	 			$stmt->execute();
	 			foreach ($stmt as $row)
	 			{
				echo "<option value=\"" . $row['uniq_id'] . "\">". $row['group_name'] ."</option>";
	 			}
echo "</select>
    <input type=\"radio\" name=\"timetable\" value=\"timetable_green\">Зеленая неделя
	<input type=\"radio\" name=\"timetable\" value=\"timetable_red\">Красная неделя
<input type=\"submit\" value=\"Сменить\">
</form>";
?>
<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
<style>
   table { 
    /*border: 2px solid black; /* Рамка вокруг таблицы */
    width: 100%;
    border-collapse: collapse; /* Отображать только одинарные линии */
    /*font-weight: bold;*/
   }
   th { 
    padding: 5px; /* Поля вокруг содержимого ячеек */
    border: 2px solid black; /* Граница вокруг ячеек */

   }
   td { 
    padding: 5px; /* Поля вокруг содержимого ячеек */
    border: 2px solid black; /* Граница вокруг ячеек */
   }
   form {
   	margin: 0;
   	padding: 0;
   }
  </style>
<?php
$days_of_week = array('П<br>О<br>Н<br>Е<br>Д<br>Е<br>Л<br>Ь<br>Н<br>И<br>К', 'В<br>Т<br>О<br>Р<br>Н<br>И<br>К', 'С<br>Р<br>Е<br>Д<br>А', 'Ч<br>Е<br>Т<br>В<br>Е<br>Р<br>Г', 'П<br>Я<br>Т<br>Н<br>И<br>Ц<br>А', 'С<br>У<br>Б<br>Б<br>О<br>Т<br>А');
if (isset($_POST['group_set']) || isset($_COOKIE["group_id"])) {
	$stmt = $dbh->prepare('SELECT * FROM groups WHERE uniq_id = :group');
	$group_id = (isset($_POST['group_set']))?$_POST['group_set']:$_COOKIE["group_id"];
	$stmt->execute(array('group' => $group_id));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$group_name =  $row["group_name"];
	$week = ($timetable=='timetable_green')?"Зеленая":"Красная";
	echo "<table style=\"border-collapse: collapse;\">";
	echo "<tr style=\"border: 2px solid black;\">
			<th colspan=5><h3>Расписание группы: " . $group_name . "</h3><br>" . $week . " неделя</th>
		  </tr>";
	foreach ($days_of_week as $day_of_week) {
		$day_of_week = str_replace("<br>", "", $day_of_week);
		echo "<tr><th colspan=5>" . $day_of_week . "</th></tr>";
		$stmt = $dbh->prepare('SELECT * FROM '. $timetable. ' LEFT JOIN lessons ON (lessons.id = '. $timetable. '.lesson_id) WHERE '. $timetable. '.study_group = :group_name AND '. $timetable. '.day = :day ORDER BY '. $timetable. '.order_num');
		$stmt->execute(array('group_name' => $group_name, 'day' => $day_of_week));
		foreach ($stmt as $row)
		{
			echo "<tr>".
			"<td><b>" . $row['order_num'] . "</b></td>".
			"<td>" . $row['name'] . "</td>".
			"<td>" . $row['teacher'] . "</td>".
			"<td>" . $row['room'] . "</td>".
			"</tr>\n";
		}
	}
	echo "</table>";
}
        $this->Footer_Page();
    }
}
?>