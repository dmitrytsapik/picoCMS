<?php
echo <<<HTML
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Панель инструментов администратора</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  </head>
  <body>
    <h1 class="text-center">Панель инструментов администратора</h1>
HTML;
if (isset($_POST['category'])) {
	$description = file_get_contents("../content/" . $_POST['category'] . "/" . $_POST['item']);
	echo "<h1>Тело</h1>
		  <form method=\"POST\">
			\n<input type=\"hidden\" name=\"write\" value=\"" . $_POST['category'] . "\">
			\n<input type=\"hidden\" name=\"item\" value=\"" . $_POST['item'] . "\">
			<textarea class=\"form-control\" cols=100 rows=10 name=\"main\">" . $description . "</textarea>
			<input type=\"submit\">
		  </form>";
	exit;
}
if (isset($_POST['write'])) {
	file_put_contents("../content/" . $_POST['write'] ."/" . $_POST['item'], $_POST['main']);
  	header ("Location: ".$_SERVER['PHP_SELF']);
	exit;
}
if (isset($_POST['empty'])) {
	$empty_content = "Месяц<br>число\n<a href=\"адрес ссылки\">Ссылка</a>тело новости\n14:00";
	if(file_put_contents("../content/" . $_POST['empty'] ."/" . time() , $empty_content)) {
echo <<<HTML
<div class="alert alert-success alert-dismissible fade in" id="alert" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4>Пустая новость добавлена!</h4>
</div>
HTML;
	}
	//header ("Location: ".$_SERVER['PHP_SELF']);
	//exit;
}
if (isset($_POST['delete'])) {
	unlink("../content/" . $_POST['delete'] ."/" . $_POST['item'] );
}

	function search_event($location) {
		$files = scandir($location);
    	unset($files[0], $files[1]);
    	arsort($files);
 		$files = array_values($files);
  		foreach ($files as $event) {
     		echo "<option value=". $event . ">" . $event . "</option>";
		}
	}

	//$loc_dir = array(array("annonce", "Анонс"), array("news", "Новости кафедр"), array("news_deps", "Объявления"), array("popular", "Новые статьи"));
	$loc_dir = array(array("news_deps", "Объявления"));
	foreach ($loc_dir as $category) {
		echo $category[1] . ": ";
		echo "\n<form method=\"POST\">
			  \n<input type=\"hidden\" name=\"category\" value=\"" . $category[0] . "\"> 
			  \n<select name=\"item\">";
		search_event("../content/" . $category[0] ."/");
		echo "\n</select>
			  \n<input type=\"submit\">
			\n</form>";
		echo "\n<form method=\"POST\">
			  \n<input type=\"hidden\" name=\"delete\" value=\"" . $category[0] . "\"> 
			  \n<select name=\"item\">";
		search_event("../content/" . $category[0] ."/");
		echo "\n</select>
			  \n<input type=\"submit\" value=\"Удалить новость в " . $category[1] ."\">
			\n</form>";
		echo "\n<form method=\"POST\">
			  \n<input type=\"hidden\" name=\"empty\" value=\"" . $category[0] . "\"> 
			  \n<input type=\"submit\" value=\"Пустая новость в " . $category[1] ."\">
			\n</form>";
	}
echo <<<HTML
</body>
</html>
HTML;
?>