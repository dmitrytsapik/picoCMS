<?php
header('Content-Type:text/html; charset=UTF-8');
try {
    $dbh = new PDO('mysql:host=localhost;dbname=timetable', 'root', '', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br>";
    die();
}
$timetable = (isset($_GET['timetable']))?(($_GET['timetable']=='timetable_green')?"timetable_green":"timetable_red"):"timetable_green";
if(isset($_POST["insert_group"])) {
$allowed = array("group_name", "course", "sort");
$stmt = $dbh->prepare("INSERT INTO groups (group_name, course, sort) VALUES (:group_name, :course, :sort)");
$stmt->bindParam(':group_name', $_POST["insert_group"]);
$stmt->bindParam(':course', $_POST["course"]);
$stmt->bindParam(':sort', $_POST["sort"]);
$stmt->execute();
}
if(isset($_POST["insert_lesson"])) {
$stmt = $dbh->prepare("INSERT INTO lessons (id, name, name_short, teacher, teacher_short, course, sort) VALUES (:id, :name, :name_short, :teacher, :teacher_short, :course_s, :sort_s)");
$stmt->bindParam(':id', time());
$stmt->bindParam(':name', $_POST["insert_lesson"]);
$stmt->bindParam(':name_short', $_POST["name_short"]);
$stmt->bindParam(':teacher', $_POST["teacher"]);
$stmt->bindParam(':teacher_short', $_POST["teacher_short"]);
$stmt->bindParam(':course_s', $_POST["course"]);
$stmt->bindParam(':sort_s', $_POST["sort"]);
$stmt->execute();
}
if(isset($_POST["delete_lesson"])) {
$stmt = $dbh->prepare("DELETE FROM ". $timetable. " WHERE uniq_id=:id");
$stmt->bindParam(':id', $_POST["delete_lesson"]);
$stmt->execute();
}
if(isset($_POST["add_order_lesson"])) {
$stmt = $dbh->prepare("INSERT INTO ". $timetable. " (lesson_id, day, study_group, order_num, room, is_short, uniq_id) VALUES (:_lesson_id, :day_, :study_group_, :order_num_, :room_, :is_short_, :uniq_id_)");
$room = $_POST['set_room'];
$is_short = "1";
$stmt->bindParam(':_lesson_id', $_POST['set_lesson']);
$stmt->bindParam(':day_', $_POST["day_of_week"]);
$stmt->bindParam(':study_group_', $_POST["group_name"]);
$stmt->bindParam(':order_num_', $_POST["add_order_lesson"]);
$stmt->bindParam(':room_', $room);
$stmt->bindParam(':is_short_', $is_short);
$stmt->bindParam(':uniq_id_', time());
$stmt->execute();
}
if(isset($_POST["insert_room"])) {
$stmt = $dbh->prepare("INSERT INTO rooms (name, description) VALUES (:name, :description)");
$stmt->bindParam(':name', $_POST["insert_room"]);
$stmt->bindParam(':description', $_POST["insert_room_description"]);
$stmt->execute();
}
?>
<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
<script type='text/javascript'>
$(document).ready(function() { 
   $('input[name=timetable]').change(function(){
        $('form[id=selector]').submit();
   });
  });
</script>
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
if (isset($_GET["group"])) {
	echo "<table style=\"border-collapse: collapse;\">";
	echo "<tr style=\"border: 2px solid black;\"><th colspan=5><h1>Расписание группы: " . $_GET["group"] . "</h1></th></tr>";
	foreach ($days_of_week as $day_of_week) {
		$day_of_week = str_replace("<br>", "", $day_of_week);
		echo "<tr><th colspan=5>" . $day_of_week . "</th></tr>";
		echo "<tr><td colspan=5><form method=\"POST\">";
		echo "	<select style=\"color: red;\" name=\"add_order_lesson\">";
				for ($i=1; $i<=5 ; $i++) {
				$stmt = $dbh->prepare('SELECT * FROM ' . $timetable . ' WHERE study_group = :group AND order_num=:order AND day = :day_s');
				$stmt->execute(array('group' => $_GET["group"], 'order' => $i, 'day_s' => $day_of_week));
				$row = $stmt->fetch(PDO::FETCH_ASSOC); 
				if(!is_array($row)) echo "<option value=\"$i\">$i пара</option>";
				}
		echo "<input type=\"hidden\" name=\"day_of_week\" value=\"" . $day_of_week . "\">";
		echo "<input type=\"hidden\" name=\"group_name\" value=\"" . $_GET["group"] . "\">";
		echo "	</select>
				<select style=\"color: blue;\" name=\"set_lesson\">";
		$stmt = $dbh->prepare('SELECT * FROM groups WHERE group_name = :group');
		$stmt->execute(array('group' => $_GET["group"]));
		$row = $stmt->fetch(PDO::FETCH_ASSOC); 
		$stmt = $dbh->prepare('SELECT * FROM lessons WHERE (sort=0 OR sort='. $row['sort'] .') AND course='. $row['course'] . ' ORDER BY name');
		$stmt->execute();
		foreach ($stmt as $row)
		{
			echo "<option value=\"". $row['id'] ."\">" . $row['name'] . " : " . $row['teacher'] . "</option>";
		}
		echo "</select>
		<select style=\"color: green;\" name=\"set_room\">";
		$stmt = $dbh->prepare('SELECT * FROM rooms ORDER BY name');
		$stmt->execute();
		foreach ($stmt as $row)
		{
			echo "<option value=\"". $row['name'] ."\">" . $row['name'] . " :: " . $row['description'] . "</option>";
		}
		echo "</select>".
			"<input type=\"submit\" value=\"Добавить\">" .
			"</form>".
			"</td>
			</tr>";
		$stmt = $dbh->prepare('SELECT * FROM '. $timetable. ' LEFT JOIN lessons ON (lessons.id = '. $timetable. '.lesson_id) WHERE '. $timetable. '.study_group = :group_name AND '. $timetable. '.day = :day ORDER BY '. $timetable. '.order_num');
		$stmt->execute(array('group_name' => $_GET['group'], 'day' => $day_of_week));
		foreach ($stmt as $row)
		{
			echo "<tr>".
			"<td>" . $row['order_num'] . "</td>".
			"<td>" . $row['name'] . "</td>".
			"<td>" . $row['teacher'] . "</td>".
			"<td>" . $row['room'] . "</td>".
			"<td>" .
				"<form method=\"POST\">
					<input size=15 type=\"hidden\" name=\"delete_lesson\" value=\"" . $row['uniq_id'] . "\">
					<input type=\"submit\" value=\"Удалить\">
					</form>" .
			"</td>".
			"</tr>\n";
		}
	}
	echo "</table>";
	exit();
}
echo "<table>";
echo "<tr>
		<th colspan=6>
			<form id=\"selector\" method=\"get\">
				Переключатель недель:
				<input type=\"radio\" name=\"timetable\" value=\"timetable_green\"" . ((isset($_GET['timetable']))?(($_GET['timetable']=='timetable_green')?"checked":""):"checked") . ">Зеленая неделя
				<input type=\"radio\" name=\"timetable\" value=\"timetable_red\"" . ((isset($_GET['timetable']))?(($_GET['timetable']=='timetable_green')?"":"checked"):"") . ">Красная неделя
			</form> 
		</th>
	  </tr>";
echo "<tr>
		<th colspan=\"6\">Список групп</th>
	  </tr>
	  <tr>";
	  for ($i=1; $i<=6 ; $i++) { 
		echo "<th>$i курс [<a href=\"table.php?course=$i&timetable=$timetable\" target=\"_blank\">посмотреть</a>]</th>";
	  }
echo "</tr>
	  <tr>";
	 for ($i=1; $i<=6 ; $i++) {
	 echo "<td>
	 		<ul>";
	 			$stmt = $dbh->prepare('SELECT * FROM groups WHERE course=' .$i .' ORDER BY sort, group_name');
	 			$stmt->execute();
	 			foreach ($stmt as $row)
	 			{
				echo "<li><a href=\"?group=". $row['group_name'] ."&timetable=" .$timetable. "\">" . $row['group_name'] . " [" . $row['sort'] . "]</a></li>";
	 			}
	 echo "</ul>
	 		</td>";
	 }
?>
</tr>
<tr>
	<th colspan=2>Добавить новую академгруппу</th>
	<th colspan=2>Добавить новый предмет</th>
	<th colspan=2>Добавить новую аудиторию (лабораторию)</th>
</tr>
<tr>
	<td colspan=2>
		<form method="POST">
			Шифр:
			<input size=5 type="text" name="insert_group">
			<select name="course">
				<option value="1">1 курс</option>
				<option value="2">2 курс</option>
				<option value="3">3 курс</option>
				<option value="4">4 курс</option>
				<option value="5">5 курс</option>
				<option value="6">6 курс</option>
			</select>
			<select name="sort">
				<option value="1">1 колонка</option>
				<option value="2">2 колонка</option>
				<option value="3">3 колонка</option>
				<option value="4">4 колонка</option>
				<option value="5">5 колонка</option>
				<option value="6">6 колонка</option>
			</select><br>
			<input type="submit" value="Отправить">
		</form>
	</td>
	<td colspan=2>
		<form method="POST">
		Название: <input size=15 type="text" name="insert_lesson">
		<br>
		Короткое название: <input size=15 type="text" name="name_short">
		<br>
		Имя преподавателя: <input size=15 type="text" name="teacher">
		<br>
		Короткое имя преподавателя: <input size=15 type="text" name="teacher_short">
		<br>
		Курс обучения:
		<select name="course">
			<?php 
				for ($i=1; $i <=6 ; $i++) { 
					echo "<option value=\"$i\">$i курс</option>";
				}
			?>
		</select>
		<br>
		Колонка:
		<select name="sort" style="color:red;">
			<?php 
				for ($i=0; $i <=6 ; $i++) { 
					if($i==0) {
						echo "<option style=\"color:green;\" value=\"$i\">Всеобщий!</option>"; 
					} else {
					echo "<option value=\"$i\">$i колонка</option>";
					}
				}
			?>
		</select>
		<br>
		<input type="submit" value="Отправить">
		</form>
	</td>
	<td colspan=2>
		<form method="POST">
		Номер комнаты: <input size=15 type="text" name="insert_room"><br>
		Описание комнаты: <input size=30 type="text" name="insert_room_description">
		<br>
		<input type="submit" value="Отправить">
		</form>
	</td>
</tr>
</table>
<b>*** Примечание ***</b>
<p>Поле "колонка" имеет две функции: 1. сортирует при выдаче на печать листа группы (физики, радио, тф, ксс); 2. закрывает видимость предметов от других групп или открывает при "Всеобщий!"</p>